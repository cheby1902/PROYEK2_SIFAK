<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fasilitas extends Model
{
    protected $table = 'fasilitas';

    protected $primaryKey = 'id_fasilitas';

    public $timestamps = false;

    protected $fillable = [
        'nama_fasilitas',
    ];

    public function pengaduan(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'id_fasilitas', 'id_fasilitas');
    }
}
