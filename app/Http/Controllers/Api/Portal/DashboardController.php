<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Institution;
use App\Models\Participant;
use App\Models\ParticipantAttendance;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use WigaPDF;

class DashboardController extends Controller
{
    public function summary()
    {

        $totalParticipants = Participant::count();
        $totalAttendances = ParticipantAttendance::count();
        $total = Event::count();
        $totalInMonth = Event::whereMonth('created_at', date('m'))->count();
        $participantTotal = Participant::distinct('email')->count('email');

        $user = Auth::user();
        if($user->role_id !== Role::SUPER_ADMIN)
        {
            $totalParticipants = Participant::whereRelation('event', 'created_by', $user->id)->count();
            $totalAttendances = ParticipantAttendance::whereRelation('event', 'created_by', $user->id)->count();

            $total = Event::where('created_by', $user->id)->count();
            $totalInMonth = Event::where('created_by', $user->id)->whereMonth('created_at', date('m'))->count();
            $participantTotal = Participant::whereRelation('event', 'created_by', $user->id)->distinct('email')->count('email');
        }

        $attendanceRate = $totalParticipants > 0 
        ? ($totalAttendances / $totalParticipants) * 100 
        : 0;

        return response([
            'status' => true,
            'data' => [
                'event' => [
                    'total' => $total,
                    'total_in_month' => $totalInMonth,
                ],
                'average_attendance' => round($attendanceRate, 2).'%',
                'participant_total' => $participantTotal
            ]
        ]);

    }


    public function reportExport()
    {
        $user = Auth::user();
        
        // 1. Ambil Data
        $query = Event::query();
        if ($user->role_id !== Role::SUPER_ADMIN) {
            $query->where('created_by', $user->id);
        }
        $events = $query->withCount(['participants', 'participantAttendance'])->get();

        // 2. Inisialisasi PDF (A4 Portrait)
        $pdf = new WigaPDF('P', 'mm', 'A4', 'Laporan_Event_' . date('Ymd'));
        $pdf->SetAutoPageBreak(true, 25);
        $pdf->AddPage();

        // --- WARNA TEMA (Modern Blue/Dark Slate) ---
        $primaryColor = [44, 62, 80];   // Dark Blue
        $secondaryColor = [52, 152, 219]; // Blue
        $headerBg = [248, 249, 250];    // Light Gray

        // --- HEADER MODERN ---
        // Tambahkan garis dekoratif di paling atas
        $pdf->SetFillColor(...$secondaryColor);
        $pdf->Rect(0, 0, 210, 3, 'F');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', 'B', 18);
        $pdf->SetTextColor(...$primaryColor);
        $pdf->Cell(0, 10, 'LAPORAN RINGKASAN EVENT', 0, 1, 'L');
        
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(100, 5, 'Instansi: ' . $user->agency_name, 0, 0, 'L');
        $pdf->Cell(0, 5, 'Tanggal Cetak: ' . date('d F Y'), 0, 1, 'R');
        
        // Garis pemisah header
        $pdf->SetDrawColor(230, 230, 230);
        $pdf->Line(10, 32, 200, 32);
        $pdf->Ln(10);

        // --- TABEL DATA ---
        // Header Tabel Elegan
        $pdf->SetFillColor(...$primaryColor);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetDrawColor(...$primaryColor);
        $pdf->SetFont('Helvetica', 'B', 9);

        // Lebar kolom yang disesuaikan (Total 190mm)
        $w = [10, 55, 45, 20, 35, 25]; 

        $pdf->Cell($w[0], 12, 'NO', 1, 0, 'C', true);
        $pdf->Cell($w[1], 12, ' JUDUL EVENT', 1, 0, 'L', true);
        $pdf->Cell($w[2], 12, 'PERIODE', 1, 0, 'C', true);
        $pdf->Cell($w[3], 12, 'PESERTA', 1, 0, 'C', true);
        $pdf->Cell($w[4], 12, 'KEHADIRAN', 1, 0, 'C', true);
        $pdf->Cell($w[5], 12, 'STATUS', 1, 1, 'C', true);

        // Isi Tabel
        $pdf->SetTextColor(50, 50, 50);
        $pdf->SetFont('Helvetica', '', 8);
        $fill = false; // Untuk zebra striping

        foreach ($events as $index => $event) {
            // Zebra Striping (warna selang-seling)
            $pdf->SetFillColor(245, 247, 250);
            
            // Logika Waktu
            $startTime = date('d/m/y', strtotime($event->start_time));
            $endTime = (!empty($event->end_time)) ? date('d/m/y', strtotime($event->end_time)) : 'Selesai';
            $waktuTampilan = $startTime . ' - ' . $endTime;

            // Hitung Persentase
            $totalP = $event->participants_count;
            $totalA = $event->participant_attendance_count;
            $rate = $totalP > 0 ? round(($totalA / $totalP) * 100, 1) : 0;

            // Border bawah tipis saja untuk kesan modern
            $pdf->SetDrawColor(230, 230, 230);
            
            $pdf->Cell($w[0], 10, $index + 1, 'B', 0, 'C', $fill);
            $pdf->Cell($w[1], 10, ' ' . substr($event->title, 0, 30), 'B', 0, 'L', $fill);
            $pdf->Cell($w[2], 10, $waktuTampilan, 'B', 0, 'C', $fill);
            $pdf->Cell($w[3], 10, $totalP, 'B', 0, 'C', $fill);
            
            // Warna teks berdasarkan performa kehadiran
            if($rate >= 80) $pdf->SetTextColor(39, 174, 96); // Hijau
            elseif($rate >= 50) $pdf->SetTextColor(243, 156, 18); // Oranye
            else $pdf->SetTextColor(192, 57, 43); // Merah
            
            $pdf->Cell($w[4], 10, $rate . '% (' . $totalA . ')', 'B', 0, 'C', $fill);
            
            // Reset warna teks untuk kolom status
            $pdf->SetTextColor(50, 50, 50);
            $status = strtoupper($event->status_publish);
            $pdf->Cell($w[5], 10, $status, 'B', 1, 'C', $fill);

            $fill = !$fill; // Tukar warna zebra
        }


        return response($pdf->Output('S', 'Laporan_Event.pdf'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Laporan_Event.pdf"');
    }
}
