<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Alumni extends Model
{
    use HasFactory;
    protected $table = 'alumni';
    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function prodi() {
        return $this->belongsTo(Prodi::class);
    }
    public function tracerStudies() {
        return $this->hasMany(TracerAlumni::class);
    }
     // FUNGSI BARU: Relasi ke jawaban kuesioner
    public function kuesionerAnswer(): HasOne
    {
        return $this->hasOne(KuesionerAnswer::class, 'alumni_id');
    }
}
