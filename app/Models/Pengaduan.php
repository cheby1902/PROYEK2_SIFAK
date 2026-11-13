<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';

    protected $primaryKey = 'id_pengaduan';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_fasilitas',
        'lokasi',
        'tanggal_pengaduan',
        'deskripsi',
        'foto',
        'status',
        'alasan_penolakan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengaduan' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function fasilitas(): BelongsTo
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }

    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatus::class, 'id_pengaduan', 'id_pengaduan');
    }
}
