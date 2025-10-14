<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi';
    protected $guarded = [];

    // PERBAIKAN: Beritahu Laravel tentang Primary Key yang baru
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'kode_prodi';

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function alumni()
    {
        // Menghubungkan 'kode_prodi' di tabel ini ke 'prodi_id' di tabel alumni.
        return $this->hasMany(Alumni::class, 'prodi_id', 'kode_prodi');
    }
}

