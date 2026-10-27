<?php

namespace App\Http\Controllers;

use App\Http\Resources\TestDriveResource;
use App\Models\Mobil;
use App\Models\TestDrive;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestDriveController extends Controller
{
    public function index(Request $request)
    {
        $query = TestDrive::with('mobil');

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $testDrives = $query->latest()->get();

        return TestDriveResource::collection($testDrives)
            ->additional(['message' => 'Daftar permohonan test drive berhasil diambil']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mobil_id'      => 'required|integer|exists:mobils,id',
            'nama_customer' => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'no_telp'       => 'required|string|max:30',
            'tanggal'       => 'required|date|after_or_equal:today',
            'catatan'       => 'nullable|string',
        ]);

        // Cek ketersediaan mobil: tidak boleh booking mobil yang sudah 'sold'
        $mobil = Mobil::findOrFail($validated['mobil_id']);
        if ($mobil->status === 'sold') {
            return response()->json([
                'message' => 'Mobil yang dipilih sudah berstatus terjual (sold) dan tidak dapat di-test drive.',
                'errors'  => [
                    'mobil_id' => ['Unit mobil ini sudah sold.']
                ]
            ], 422);
        }

        $validated['tanggal'] = Carbon::parse($validated['tanggal'])->toDateString();

        $validated['status'] = 'menunggu';
        $testDrive = TestDrive::create($validated);
        $testDrive->load('mobil');

        return (new TestDriveResource($testDrive))
            ->additional(['message' => 'Permohonan booking test drive berhasil diajukan'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $id)
    {
        $testDrive = TestDrive::with('mobil')->findOrFail($id);

        return (new TestDriveResource($testDrive))
            ->additional(['message' => 'Detail booking test drive berhasil diambil']);
    }

    public function updateStatus(Request $request, string $id)
    {
        $testDrive = TestDrive::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:menunggu,disetujui,selesai,dibatalkan',
        ]);

        $testDrive->update($validated);
        $testDrive->load('mobil');

        return (new TestDriveResource($testDrive))
            ->additional(['message' => 'Status booking test drive berhasil diperbarui']);
    }

    public function destroy(string $id)
    {
        $testDrive = TestDrive::findOrFail($id);
        $testDrive->delete();

        return response()->json([
            'message' => 'Data permohonan test drive berhasil dihapus',
            'data'    => null,
        ], 200);
    }
}
