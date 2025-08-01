<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Prodi extends Model
{
    use HasFactory;
    protected $table = 'prodi';
    protected $guarded = ['id'];
    public function fakultas() {
        return $this->belongsTo(Fakultas::class);
    }

    public function alumni(): HasMany
    {
        return $this->hasMany(Alumni::class);
    }
}
