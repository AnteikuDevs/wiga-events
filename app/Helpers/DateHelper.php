<?php

function listHari()
{
    return [
        1 => "Senin",
        2 => "Selasa",
        3 => "Rabu",
        4 => "Kamis",
        5 => "Jum'at",
        6 => "Sabtu",
        7 => "Minggu",
    ];
}

function getDayIndexByDate($date)
{
    return date('N', strtotime($date));
}

function listBulan()
{
    return [
        1 => "Januari",
        2 => "Februari",
        3 => "Maret",
        4 => "April",
        5 => "Mei",
        6 => "Juni",
        7 => "Juli",
        8 => "Agustus",
        9 => "September",
        10 => "Oktober",
        11 => "November",
        12 => "Desember",
    ];
}

if(!function_exists('getBulan'))
{
    function getBulan($index = '')
    {
        $bulan = [
            1 => "Januari",
            2 => "Februari",
            3 => "Maret",
            4 => "April",
            5 => "Mei",
            6 => "Juni",
            7 => "Juli",
            8 => "Agustus",
            9 => "September",
            10 => "Oktober",
            11 => "November",
            12 => "Desember",
        ];

        return $index? $bulan[(int)$index] : $bulan;
    }
}

if(!function_exists('getNamaHari'))
{
    function getNamaHari($index)
    {
        $hari = listHari();
        return $hari[$index]?? '';
    }
    // generateHari(1);

}

if(!function_exists('getNamaBulan'))
{
    function getNamaBulan($index)
    {
        $bulan = listBulan();
        return $bulan[$index]?? '';
    }
    // generateHari(1);

}

function getRangeTanggal($tanggal_mulai, $jumlah_hari) {
    $tanggal_awal = DateTime::createFromFormat('Y-m-d', $tanggal_mulai);
    $tanggal_array = [];

    for ($i = 0; $i <= $jumlah_hari; $i++) {
        $tanggal_clone = clone $tanggal_awal;
        $tanggal_clone->modify("+$i days");

        $tanggal_array[] = [
            'hari' => getNamaHari($tanggal_clone->format('N')),
            'index_hari' => $tanggal_clone->format('N'),
            'tanggal' => $tanggal_clone->format('Y-m-d')
        ];
    }

    return $tanggal_array;
}

function getTanggalByRange($tanggal_mulai, $jumlah_hari, $index_hari)
{
    return collect(getRangeTanggal($tanggal_mulai, $jumlah_hari))
        ->firstWhere('index_hari', $index_hari)['tanggal'] ?? null;
}

function formatDateIndo($date,$tampilkanWaktu = true)
{
    if(empty($date))
    {
        return null;
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}/', $date)) {
        return 'Tanggal tidak valid';
    }
    $dateTimeParts = explode(' ', $date);
    $datePart = $dateTimeParts[0];
    $pecahkan = explode('-', $datePart);
    if (count($pecahkan) !== 3) {
        return 'Format tanggal tidak sesuai';
    }

    $tanggal = $pecahkan[2];
    $bulan = getBulan((int)$pecahkan[1]);
    $tahun = $pecahkan[0];
    $result = $tanggal . ' ' . $bulan . ' ' . $tahun;
    if (isset($dateTimeParts[1]) && $tampilkanWaktu) {
        $result .= ' ' . $dateTimeParts[1];
    }

    return $result;
}