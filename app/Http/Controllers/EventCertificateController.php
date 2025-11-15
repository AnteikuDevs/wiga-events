<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\ParticipantAttendance;
use App\Models\ParticipantCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use WigaPDF;
use WigaStorage;

class EventCertificateController extends Controller
{
    public function index(Request $request,string $id)
    {
        $dataAttendance = ParticipantAttendance::findOrFail(str_replace('cert-', '', base64_decode($id)));
        
        $data = $dataAttendance->participant;

        $event = $dataAttendance->event;

        $template = $event->certificates()->where('participant_type', $data->type)->first();

        if(!$template)
        {
            return view('event.cert-not-published');
        }

        $cert = ParticipantCertificate::where([
            'event_id' => $event->id,
            'participant_id' => $data->id,
            'certificate_template_id' => $template->id
        ])->first();

        if(!$cert)
        {
            
            $cert = new ParticipantCertificate();
            $cert->event_id = $event->id;
            $cert->participant_id = $data->id;
            $cert->certificate_template_id = $template->id;
            $cert->participant_type = $data->type;
            $cert->certificate_number = $template->certificate_number;
            $cert->save();
        }else{
            $cert->update([
                'certificate_number' => $template->certificate_number
            ]);
        }

        $update = $request->input('update') == '1' ? true : false;
        
        return $this->renderCert($cert,$update);
        
    }

    private function renderCert(ParticipantCertificate $certificate,$update = false)
    {

        $pdf = new WigaPDF('L', 'mm', 'A5','Sertifikat '.$certificate->participant->name . ' : '.$certificate->event->title);
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 12);
        $pageWidth = $pdf->GetPageWidth();
        $pageHeight = $pdf->GetPageHeight();
        $pdf->Image(public_path($certificate->certificateTemplate->image->url), 0, 0, $pageWidth, $pageHeight);

        $pdf->SetY(53);
        $pdf->SetFont('OpenSans', '', 12);
        $pdf->Cell(0, 0, $certificate->certificate_number, 0, 1, 'C');
        
        $pdf->SetFont('SPD', '', 30);
        $pdf->SetY(73);
        $pdf->SetTextColor(65, 10, 0);
        $pdf->Cell(0, 0, strtoupper($certificate->participant->name), 0, 1, 'C');

        $pdfOutput = $pdf->Output('S');

        if(!$certificate->certificate_file_id || $update)
        {
            Storage::disk('public')->put('certificate/'.$certificate->event_id.'/'.$certificate->participant_id.'.pdf', $pdfOutput);
    
            $certificateFileId = WigaStorage::save('storage/certificate/'.$certificate->event_id, $certificate->participant_id.'.pdf', 'certificate/'.$certificate->event_id)->id();        
    
            $certificate->certificate_file_id = $certificateFileId;
            $certificate->save();
        }


        return redirect()->route('file.show', [$certificate->certificate_file_id]);

        return response($pdf->Output('S', $pdf->filename))
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="'.$pdf->filename.'"');

    }

}
