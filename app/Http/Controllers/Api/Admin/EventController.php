<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendance;
use Illuminate\Http\Request;
use WigaStorage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = WigaTable(Event::with(['image'])->withCount('participants'), $request, function($query,$search){
            $query->filterLike($search, ['title','description']);
        }, [
            'id',
            'title',
            'start_date',
            'end_date',
            'description'
        ]);

        return response($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            // 'until_finish' => 'required|in:0,1',
            'end_time' => 'nullable|date_format:Y-m-d\TH:i',
        ],[
            'image.required' => 'Banner wajib diisi',
            'image.image' => 'Banner harus berupa gambar',
        ]);

        $image_id = WigaStorage::store('image','events')->id();

        Event::create([
            'image_id' => $image_id,
            'title' => $request->title,
            'slug' => generateSlug(Event::class, 'title', $request->title),
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time? $request->end_time : null,
        ]);

        return response([
            'status' => true,
            'message' => "Berhasil menambahkan event",
        ]);

        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $data = Event::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $request->validate([
            'image_deleted' => 'nullable|boolean',
            'image' => 'nullable|required_if:image_deleted,1|image|mimes:jpeg,png,jpg,gif',
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            // 'until_finish' => 'required|in:0,1',
            'end_time' => 'nullable|date_format:Y-m-d\TH:i',
        ],[
            'image.required_if' => 'Banner harus diisi',
            'image.image' => 'Banner harus berupa gambar',
        ]);

        WigaStorage::update('image',$data->image_id);

        $data->update([
            'title' => $request->title,
            'slug' => $data->title != $request->title ? generateSlug(Event::class, 'title', $request->title) : $data->slug,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time? $request->end_time : null,
        ]);

        return response([
            'status' => true,
            'message' => "Berhasil mengubah event",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Event::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $data->delete();

        WigaStorage::delete($data->image_id,'image');

        return response([
            'status' => true,
            'message' => "Berhasil menghapus event",
        ]);

    }

    public function list()
    {

        $data = Event::with(['image'])->where('start_time','>=',date('Y-m-d H:i'))->get();

        if(empty($data)){
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        return response([
            'status' => true,
            'data' => $data
        ]);

    }

    public function generateAttendance(string $id)
    {
        $data = Event::find($id);

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Data tidak ditemukan",
            ]);
        }

        $cekLinkAttendance = EventAttendance::where('event_id', $data->id)->where('expired_at','>=',date('Y-m-d H:i:s'))->first();
        if($cekLinkAttendance){
            return response([
                'status' => true,
                'data' => $cekLinkAttendance
            ]);
        }

        $link = EventAttendance::create([
            'event_id' => $data->id,
            'token' => str_random(15),
            'expired_at' => date('Y-m-d H:i:s', strtotime('1 hour')),
        ]);

        return response([
            'status' => true,
            'data' => $link
        ]);
    }
}
