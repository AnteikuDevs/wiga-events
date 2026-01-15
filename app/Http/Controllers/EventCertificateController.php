<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\Participant;
use App\Models\ParticipantAttendance;
use App\Models\ParticipantCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use WigaPDF;
use WigaStorage;

class EventCertificateController extends Controller
{
    public function index(Request $request,string $code)
    {

        $participant = Participant::where('reg_code', "REG-".$code)->firstOrFail();

        $event = $participant->event;
        $certificate = $participant->certificateTemplate;
        

        $pdf = new WigaPDF('L', 'mm', 'A5','Sertifikat '.$participant->name . ' : '.$event->title);
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 12);
        $pageWidth = $pdf->GetPageWidth();
        $pageHeight = $pdf->GetPageHeight();
        $pdf->Image(public_path($certificate->image->url), 0, 0, $pageWidth, $pageHeight, $certificate->ext);

        if($certificate->model_id == '1')
        {
            $pdf->SetY(33);
            $pdf->SetFont('Tahoma', '', 16);
            $pdf->Cell(0, 0, $certificate->certificate_number, 0, 1, 'C');
        }

        
        $pdf->SetFont('Tahoma-Bold', '', 30);
        $pdf->SetY(60);
        // $pdf->SetTextColor(65, 10, 0);
        $pdf->Cell(0, 0, strtoupper($participant->name), 0, 1, 'C');

        $pdf->SetFont('Tahoma-Bold', '', 18);
        $pdf->SetY(81.5);
        // $pdf->SetTextColor(65, 10, 0);
        $pdf->Cell(0, 0, strtoupper($participant->certificate_as), 0, 1, 'C');

        return response($pdf->Output('S', $pdf->filename))
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="'.$pdf->filename.'"');
        
    }


    public function preview(Request $request)
    {

        $request->validate([
            'event' => 'required',
            'model' => 'required|in:1,2',
            'image_id' => 'required_without:image|nullable',
            'image' => 'required_without:image_id|nullable|image|mimes:jpeg,png,jpg',
            'certificate_number' => 'nullable',
            'certificate_as' => 'required',
        ]);

        $dataEvent = Event::findOrFail($request->event);

        if($request->image_id)
        {
            $imageFile = WigaStorage::find($request->image_id);
            $tempImagePath = public_path($imageFile->url);

            $extension = $imageFile->ext;

        }else{

            $file = $request->file('image');
            $tempImagePath = $file->getRealPath();
            
            $extension = $file->getClientOriginalExtension();
        }


        $pdf = new WigaPDF('L', 'mm', 'A5','Preview Sertifikat');
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 12);
        $pageWidth = $pdf->GetPageWidth();
        $pageHeight = $pdf->GetPageHeight();
        $pdf->Image($tempImagePath, 0, 0, $pageWidth, $pageHeight, $extension);

        if($request->model == '1')
        {
            $pdf->SetY(33);
            $pdf->SetFont('Tahoma', '', 16);
            $pdf->Cell(0, 0, $request->certificate_number, 0, 1, 'C');
        }

        
        $pdf->SetFont('Tahoma-Bold', '', 30);
        $pdf->SetY(60);
        // $pdf->SetTextColor(65, 10, 0);
        $pdf->Cell(0, 0, "NAMA LENGKAP", 0, 1, 'C');

        $pdf->SetFont('Tahoma-Bold', '', 18);
        $pdf->SetY(81.5);
        // $pdf->SetTextColor(65, 10, 0);
        $pdf->Cell(0, 0, strtoupper($request->certificate_as), 0, 1, 'C');

        return response($pdf->Output('S', $pdf->filename))
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="'.$pdf->filename.'"');

    }

}
