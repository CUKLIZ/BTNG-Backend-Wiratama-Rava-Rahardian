<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Models\TestDrive;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function summary(): JsonResponse
    {
        $totalMobil = Mobil::count();
        $mobilTersedia = Mobil::where('status', 'tersedia')->count();
        $mobilSold = Mobil::where('status', 'sold')->count();
        $totalNilaiAset = Mobil::where('status', 'tersedia')->sum('harga');

        $totalTestDrive = TestDrive::count();
        $testDriveMenunggu = TestDrive::where('status', 'menunggu')->count();
        $testDriveDisetujui = TestDrive::where('status', 'disetujui')->count();

        return response()->json([
            'message' => 'Ringkasan data dashboard showroom berhasil diambil',
            'data'    => [
                'inventori_mobil' => [
                    'total_unit'       => $totalMobil,
                    'tersedia'         => $mobilTersedia,
                    'terjual'          => $mobilSold,
                    'total_nilai_aset' => (int) $totalNilaiAset,
                ],
                'test_drive' => [
                    'total_booking'    => $totalTestDrive,
                    'menunggu'         => $testDriveMenunggu,
                    'disetujui'        => $testDriveDisetujui,
                ],
            ]
        ], 200);
    }
}
