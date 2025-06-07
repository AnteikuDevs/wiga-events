<?php

use App\Models\MyStorage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WigaStorage {

    public $data = null;

    public static function store($field,$path)
    {
        $file = request()->file($field);
        $name = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        $mime_type = $file->getMimeType();
        $hash_name = Str::random(40);

        $file->storeAs($path, $hash_name.'.'.$ext, 'public');

        $data = new MyStorage();
        $data->name = $name;
        $data->hash_name = $hash_name.'.'.$ext;
        $data->path = $path;
        $data->url = '/storage/'.$path.'/'.$hash_name.'.'.$ext;
        $data->type = $mime_type;
        $data->ext = $ext;
        
        $data->save();

        
        $instance = new self;
        $instance->data = $data;

        return $instance;
    }

    public function id()
    {
        return $this->data->id;
    }

    public function url()
    {
        return url($this->data->url);
    }

    public function data()
    {
        return $this->data;
    }

    public static function update($field,$id)
    {

        $data = MyStorage::find($id);

        if(!$data){
            return false;
        }

        $file = request()->file($field);
        if($file){

            @unlink(public_path($data->url));

            $name = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $mime_type = $file->getMimeType();
            $hash_name = Str::random(40);

            $file->storeAs($data->path, $hash_name.'.'.$ext, 'public');

            $data->name = $name;
            $data->hash_name = $hash_name.'.'.$ext;
            $data->url = '/storage/'.$data->path.'/'.$hash_name.'.'.$ext;
            $data->type = $mime_type;
            $data->ext = $ext;
            $data->save();

        }


        $instance = new self;
        $instance->data = $data;

        return $instance;
    }

    public static function delete($id)
    {

        $data = MyStorage::find($id);

        if(!$data){
            return false;
        }

        @unlink(public_path($data->url));

        return $data->delete();
    }

    public static function find($id)
    {
        $data = MyStorage::find($id);
        $data->full_path = storage_path('app/public/'.$data->path.'/'.$data->hash_name);
        return $data;
    }

    public static function save($fromPath, $nameWithExt, $path)
    {

        // $data = MyStorage::where('path', $fromPath)->where('name', $nameWithExt)->first();

        // if(!$data){

            $hash_name = Str::random(40);
            $ext = explode('.',$nameWithExt);
            $ext = end($ext);

            $mime_type = guessMimeTypeFromPath(public_path($fromPath.'/'.$nameWithExt));

            Storage::disk('public')->put($path.'/'.$hash_name.'.'.$ext, file_get_contents(public_path($fromPath.'/'.$nameWithExt)));

            
            $data = new MyStorage();
            $data->name = $nameWithExt;
            $data->path = $path;
            $data->hash_name = $hash_name .'.'.$ext;
            $data->url = '/storage/'.$path.'/'.$hash_name.'.'.$ext;
            $data->type = $mime_type;
            $data->ext = $ext;
            $data->save();
        // }
        
        $instance = new self;
        $instance->data = $data;

        return $instance;
    }

}


function getMyFile($id)
{
    return WigaStorage::find($id);
}