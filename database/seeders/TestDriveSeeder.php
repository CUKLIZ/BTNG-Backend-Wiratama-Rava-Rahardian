<?php

namespace Database\Seeders;

use App\Models\Mobil;
use App\Models\TestDrive;
use Illuminate\Database\Seeder;

class TestDriveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $avanza = Mobil::where('nama', 'like', '%Avanza%')->first();
        $civic = Mobil::where('nama', 'like', '%Civic%')->first();

        if ($avanza) {
            TestDrive::create([
                'mobil_id'      => $avanza->id,
                'nama_customer' => 'Budi Santoso',
                'email'         => 'budi.santoso@example.com',
                'no_telp'       => '081234567890',
                'tanggal'       => now()->addDays(2)->format('Y-m-d'),
                'catatan'       => 'Ingin mencoba transmisi CVT di tanjakan.',
                'status'        => 'menunggu',
            ]);
        }

        if ($civic) {
            TestDrive::create([
                'mobil_id'      => $civic->id,
                'nama_customer' => 'Siti Rahma',
                'email'         => 'siti.rahma@example.com',
                'no_telp'       => '085712345678',
                'tanggal'       => now()->addDays(1)->format('Y-m-d'),
                'catatan'       => 'Datang sore sekitar pukul 15.00 WIB.',
                'status'        => 'disetujui',
            ]);
        }
    }
}
