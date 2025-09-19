<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'instansi';

    /**
     * Tentukan kolom mana yang boleh diisi secara massal.
     */
    protected $guarded = ['id'];

    /**
     * Relasi ke user (akun login) instansi.
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function penilaian()
    {
        return $this->hasMany(PenilaianInstansi::class);
    }
}
