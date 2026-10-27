<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mobil extends Model
{
    protected $fillable = [
        'nama',
        'brand',
        'harga',
        'tahun',
        'transmisi',
        'bahan_bakar',
        'status',
        'foto',
    ];

    protected $casts = [
        'harga' => 'integer',
        'tahun' => 'integer',
    ];

    public function testDrives(): HasMany
    {
        return $this->hasMany(TestDrive::class);
    }
}
