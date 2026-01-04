<?php

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MyQRCode {

    public static function save($data, $prefix, $customFilename = null) {
        $logoPath = public_path('icon.png');

        // Cek apakah file logo ada
        if (!file_exists($logoPath)) {
            throw new \Exception("File logo tidak ditemukan di: " . $logoPath);
        }

        $filename = ($customFilename ?: \Illuminate\Support\Str::random(10)) . '.png';
        $path = 'QR-CODE/' . $prefix;
        $directory = storage_path('app/public/' . $path);

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $fullPath = $directory . '/' . $filename;

        // Tambahkan @ di depan QrCode untuk menekan warning libpng iCCP
        @QrCode::size(512)
            ->format('png')
            ->merge($logoPath, 0.3, true)
            ->errorCorrection('H')
            ->generate($data, $fullPath);

        return '/storage/' . $path . '/' . $filename;
    }

    public static function render($data) {

        $logoPath = public_path('icon.png');

        // Cek apakah file logo ada
        if (!file_exists($logoPath)) {
            throw new \Exception("File logo tidak ditemukan di: " . $logoPath);
        }

        $image = @QrCode::format('png')
        ->size(512)
        ->merge($logoPath, 0.3, true)
        ->color(15, 17, 33) // Electric Cyan (Modern Tech Blue)
                ->backgroundColor(255, 255, 255)
        ->errorCorrection('H')
        ->generate($data);

        // Ubah ke format Base64 Data URL
        return 'data:image/png;base64,' . base64_encode($image);
    }

}