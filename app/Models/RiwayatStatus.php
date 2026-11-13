<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStatus extends Model
{
    protected $table = 'riwayat_status';

    protected $primaryKey = 'id_riwayat';

    public $timestamps = false;

    protected $fillable = [
        'id_pengaduan',
        'id_user',
        'status',
        'keterangan',
        'tanggal_perubahan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_perubahan' => 'datetime',
        ];
    }

    public function pengaduan(): BelongsTo
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan', 'id_pengaduan');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
