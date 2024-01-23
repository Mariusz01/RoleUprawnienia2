<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'nrzestawu',
        'slowo',
        'znaczenie',
        'przyklad',
    ];

    protected static function booted()
    {
        static::created(function ($word) {
            $users = DB::table('users')
            ->select(
                'id',
            )
            ->get();

            foreach($users as $user){
                $tabela = 'usertab2_'.$user->id;
                DB::table($tabela)->insert([
                    'word_id2' => $word->id,
                    'word_nrzestawu2' => $word->nrzestawu,
                    'slowo2' => $word->slowo,
                    'znaczenie2' => $word->znaczenie,
                    'przyklad2' => $word->przyklad,
                ]);
            }
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class)->withTimestamps();
    // }
    // public function profiler()
    // {
    //     return $this->hasOne(Profiler::class);
    // }
}
