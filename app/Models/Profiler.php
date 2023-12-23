<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profiler extends Model
{
    use HasFactory;

    protected $fillable = [
        'word_id',
        'slowka',
    ];



    // public function word()
    // {
    //     return $this->belongsTo(Word::class);
    // }

    // public function words()
    // {
    //     return $this->hasOne(Word::class);
    // }

    // public function user()
    // {
    //     // return $this->belongsTo(User::class);
    //     return $this->belongsTo(User::class, 'user_id');
    // }
}
