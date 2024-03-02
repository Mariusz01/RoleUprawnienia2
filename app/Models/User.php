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
        $userTabela = 'usertab2_' . $userId;
        $userTabela3 = 'usertab3_'.$userId;

        // Sprawdź, czy tabela już istnieje
        if (!Schema::hasTable($userTabela)) {
            // Tworzenie nowej tabeli dla użytkownika
            DB::statement("CREATE TABLE {$userTabela} (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                word_id2 INT DEFAULT 0,
                word_nrzestawu2 INT DEFAULT 0,
                nrzestawu2 INT DEFAULT 0,
                slowo2 TEXT,
                znaczenie2 TEXT,
                przyklad2 TEXT DEFAULT NULL,
                dodaj_donauki2 TINYINT DEFAULT 0,
                dodaj_dotab2 TINYINT DEFAULT 0,
                dodaj_slowo2 TINYINT DEFAULT 0,
                edytuj_slowo2 TINYINT DEFAULT 0,
                usun_slowo2 TINYINT DEFAULT 0,
                n2_dodano TIMESTAMP NULL,
                n2_nauka TIMESTAMP NULL,
                n2_ile_powt SMALLINT DEFAULT 0,
                n2_1a TINYINT UNSIGNED DEFAULT 0,
                n2_1b TINYINT UNSIGNED DEFAULT 0,
                n2_1c DATE NULL,
                n2_2a TINYINT UNSIGNED DEFAULT 0,
                n2_2b TINYINT UNSIGNED DEFAULT 0,
                n2_2c TIMESTAMP NULL,
                n2_3a TINYINT UNSIGNED DEFAULT 0,
                n2_3b TINYINT UNSIGNED DEFAULT 0,
                n2_3c TIMESTAMP NULL,
                n2_4a TINYINT UNSIGNED DEFAULT 0,
                n2_4b TINYINT UNSIGNED DEFAULT 0,
                n2_4c TIMESTAMP NULL,
                n2_5a TINYINT UNSIGNED DEFAULT 0,
                n2_5b TINYINT UNSIGNED DEFAULT 0,
                n2_5c TIMESTAMP NULL,
                n2_6a TINYINT UNSIGNED DEFAULT 0,
                n2_6b TINYINT UNSIGNED DEFAULT 0,
                n2_6c TIMESTAMP NULL,
                n2_7a TINYINT UNSIGNED DEFAULT 0,
                n2_7b TINYINT UNSIGNED DEFAULT 0,
                n2_7c TIMESTAMP NULL,
                n2_8a TINYINT UNSIGNED DEFAULT 0,
                n2_8b TINYINT UNSIGNED DEFAULT 0,
                n2_8c TIMESTAMP NULL,
                tab SMALLINT DEFAULT 2,
                page2 SMALLINT DEFAULT 0,
                pkt_dzis2 FLOAT DEFAULT 0,
                pkt_razem2 FLOAT DEFAULT 0,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )");
        }
        //sprawdzenie czy istnieje tabela
        if (!Schema::hasTable($userTabela3)) {
            // Tabela nie istnieje
            // Tworzenie nowej tabeli dla użytkownika
            DB::statement("CREATE TABLE {$userTabela3} (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                nrzestawu INT DEFAULT 0,
                slowo TEXT,
                znaczenie TEXT,
                przyklad TEXT DEFAULT NULL,
                dodaj_tab TINYINT DEFAULT 0,
                dodaj_slowo TINYINT DEFAULT 0,
                edytuj_slowo TINYINT DEFAULT 0,
                dalej1 TEXT DEFAULT NULL,
                dalej2 TEXT DEFAULT NULL,
                dalej3 TEXT DEFAULT NULL,
                nauka1 SMALLINT DEFAULT 0,
                nauka2 SMALLINT DEFAULT 0,
                nauka3 SMALLINT DEFAULT 0,
                nauka4 SMALLINT DEFAULT 0,
                tab SMALLINT DEFAULT 3,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )");
        }
    }

    private static function dropUserTable($userId)
    {
        $userTabela = 'usertab2_' . $userId;
        $userTabela3 = 'usertab3_'.$userId;

        // Sprawdź, czy tabela istnieje, a następnie usuń ją
        if (Schema::hasTable($userTabela)) {
            DB::statement("DROP TABLE {$userTabela}");
        }
        if (Schema::hasTable($userTabela3)) {
            DB::statement("DROP TABLE {$userTabela3}");
        }
    }
    public function words()
    {
        return $this->hasMany(Word::class);
    }



        // public function words()
        // {
        //     return $this->belongsToMany(Word::class)->withTimestamps();
        // }
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
