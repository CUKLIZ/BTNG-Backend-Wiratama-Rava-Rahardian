<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestDrive extends Model
{
    protected $fillable = [
        'mobil_id',
        'nama_customer',
        'email',
        'no_telp',
        'tanggal',
        'catatan',
        'status',
    ];

    protected $casts = [
        'mobil_id' => 'integer',
        'tanggal'  => 'date:Y-m-d',
    ];

    public function mobil(): BelongsTo
    {
        return $this->belongsTo(Mobil::class);
    }
}
