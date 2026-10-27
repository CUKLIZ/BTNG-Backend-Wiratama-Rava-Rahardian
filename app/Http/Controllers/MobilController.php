<?php

namespace App\Http\Controllers;

use App\Http\Resources\MobilResource;
use App\Models\Mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index(Request $request)
    {
        $query = Mobil::query();

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->query('brand'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('transmisi')) {
            $query->where('transmisi', $request->query('transmisi'));
        }

        if ($request->filled('bahan_bakar')) {
            $query->where('bahan_bakar', $request->query('bahan_bakar'));
        }

        if ($request->has('page') || $request->has('per_page')) {
            $perPage = (int) $request->query('per_page', 10);
            $mobils = $query->latest()->paginate($perPage);
        } else {
            $mobils = $query->latest()->get();
        }

        return MobilResource::collection($mobils)
            ->additional(['message' => 'Daftar mobil berhasil diambil']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'brand'       => 'required|string|max:100',
            'harga'       => 'required|integer|min:1',
            'tahun'       => 'required|integer|min:1900|max:2030',
            'transmisi'   => 'required|in:Manual,Matic,CVT',
            'bahan_bakar' => 'required|in:Bensin,Diesel,Hybrid,Listrik',
            'status'      => 'required|in:tersedia,sold',
            'foto'        => 'nullable|url',
        ]);

        $mobil = Mobil::create($validated);

        return (new MobilResource($mobil))
            ->additional(['message' => 'Data mobil berhasil ditambahkan'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $id)
    {
        $mobil = Mobil::findOrFail($id);

        return (new MobilResource($mobil))
            ->additional(['message' => 'Detail mobil berhasil diambil']);
    }

    public function update(Request $request, string $id)
    {
        $mobil = Mobil::findOrFail($id);

        $validated = $request->validate([
            'nama'        => 'sometimes|required|string|max:255',
            'brand'       => 'sometimes|required|string|max:100',
            'harga'       => 'sometimes|required|integer|min:1',
            'tahun'       => 'sometimes|required|integer|min:1900|max:2030',
            'transmisi'   => 'sometimes|required|in:Manual,Matic,CVT',
            'bahan_bakar' => 'sometimes|required|in:Bensin,Diesel,Hybrid,Listrik',
            'status'      => 'sometimes|required|in:tersedia,sold',
            'foto'        => 'nullable|url',
        ]);

        $mobil->update($validated);

        return (new MobilResource($mobil))
            ->additional(['message' => 'Data mobil berhasil diperbarui']);
    }

    public function destroy(string $id)
    {
        $mobil = Mobil::findOrFail($id);
        $mobil->delete();

        return response()->json([
            'message' => 'Data mobil berhasil dihapus',
            'data'    => null,
        ], 200);
    }

    public function simulasiKredit(Request $request, string $id)
    {
        $mobil = Mobil::findOrFail($id);

        $validated = $request->validate([
            'uang_muka_persen' => 'required|numeric|min:10|max:90',
            'tenor_tahun'      => 'required|integer|in:1,2,3,4,5',
        ]);

        $hargaMobil = (int) $mobil->harga;
        $dpPersen = (float) $validated['uang_muka_persen'];
        $tenorTahun = (int) $validated['tenor_tahun'];
        $tenorBulan = $tenorTahun * 12;

        // Bunga flat tahunan standar leasing: 1 thn = 6%, 2 thn = 7%, 3 thn = 8%, 4 thn = 9%, 5 thn = 10%
        $sukuBungaTahunan = match ($tenorTahun) {
            1 => 6.0,
            2 => 7.0,
            3 => 8.0,
            4 => 9.0,
            default => 10.0,
        };

        $uangMuka = (int) round(($dpPersen / 100) * $hargaMobil);
        $pokokHutang = $hargaMobil - $uangMuka;
        $totalBunga = (int) round($pokokHutang * ($sukuBungaTahunan / 100) * $tenorTahun);
        $totalHutang = $pokokHutang + $totalBunga;
        $angsuranPerBulan = (int) round($totalHutang / $tenorBulan);

        return response()->json([
            'message' => 'Simulasi kredit mobil berhasil dihitung',
            'data'    => [
                'mobil' => [
                    'id'    => $mobil->id,
                    'nama'  => $mobil->nama,
                    'brand' => $mobil->brand,
                    'harga' => $hargaMobil,
                ],
                'simulasi' => [
                    'uang_muka_persen'     => $dpPersen,
                    'uang_muka_nominal'    => $uangMuka,
                    'pokok_hutang'         => $pokokHutang,
                    'tenor_tahun'          => $tenorTahun,
                    'tenor_bulan'          => $tenorBulan,
                    'bunga_tahunan_persen' => $sukuBungaTahunan,
                    'total_bunga'          => $totalBunga,
                    'total_pembayaran'     => $totalHutang + $uangMuka,
                    'angsuran_per_bulan'   => $angsuranPerBulan,
                ],
            ]
        ], 200);
    }
}
