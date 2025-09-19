<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianInstansi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'penilaian_instansi';

    /**
     * Tentukan kolom mana yang boleh diisi secara massal.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Relasi ke model Alumni.
     */
    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * Relasi ke model Instansi.
     */
    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }
}
