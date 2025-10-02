<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Alumni extends Model
{
    use HasFactory;
    protected $table = 'alumni';
    protected $guarded = ['id'];
    public $incrementing = false; 
    protected $keyType = 'string'; 

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
    public function kuesionerAnswers()
    {
        return $this->hasMany(KuesionerAnswer::class, 'alumni_id');
    }


    public function getRouteKeyName()
    {
        return 'uuid';
    }


    public function penilaianInstansi()
    {
        return $this->hasMany(PenilaianInstansi::class);
    }


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
