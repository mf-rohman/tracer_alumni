<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class TracerKuesioner extends Model
{
    use HasFactory;
     protected $table = 'tracer_kuesioner';
    protected $guarded = ['id'];

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }
}
