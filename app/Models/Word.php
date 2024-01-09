<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'nrzestawu',
        'slowo',
        'znaczenie',
        'przyklad',
    ];


    // public function profiler()
    // {
    //     return $this->hasOne(Profiler::class);
    // }
}
