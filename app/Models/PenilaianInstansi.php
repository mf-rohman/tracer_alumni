<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function alumni() : BelongsTo
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
    
    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_user_id');
    }
}
