<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_approved',
        'email_verified_at',
        'admin',
    ];


    protected static function booted()
    {
        static::created(function ($user) {
            // Tworzenie tabeli przy tworzeniu użytkownika
            self::createUserTable($user->id);
        });

        static::deleting(function ($user) {
            // Usuwanie tabeli przy usuwaniu użytkownika
            self::dropUserTable($user->id);
        });
    }

    private static function createUserTable($userId)
    {
        $tableName = 'usertab_' . $userId;

        // Sprawdź, czy tabela już istnieje
        if (!Schema::hasTable($tableName)) {
            // Tworzenie nowej tabeli dla użytkownika
            DB::statement("CREATE TABLE {$tableName} (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )");
        }
    }

    private static function dropUserTable($userId)
    {
        $tableName = 'usertab_' . $userId;

        // Sprawdź, czy tabela istnieje, a następnie usuń ją
        if (Schema::hasTable($tableName)) {
            DB::statement("DROP TABLE {$tableName}");
        }
    }
    // private static function dropUserTable($userId)
    // {
    //     $tableName = 'usertab_' . $userId;

    //     if (Schema::hasTable($tableName)) {
    //         Schema::dropIfExists($tableName);
    //     }
    // }
    // private static function dropUserTable($userId)
    // {
    //     $tableName = 'usertab_' . $userId;

    //     // Sprawdź, czy tabela istnieje, a następnie usuń ją
    //     if (Schema::hasTable($tableName)) {
    //         DB::statement("DROP TABLE {$tableName}");
    //     }
    // }




    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isApproved()
    {
        return $this->is_approved;
    }

    // public function usertab(){
    //     // return $this->belongsTo(User::class);
    //     return $this->belongsTo(User::class, 'user_id');
    // }

}
