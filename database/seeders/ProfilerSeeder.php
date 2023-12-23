<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Word;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Illuminate\Support\Facades\Schema;

class ProfilerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dodaj przykładowe dane dla powiązanej tabeli dla każdego użytkownika
         $users = User::all();
        //  $words = Word::count();

        //  foreach ($users as $user) {
        //     DB::table('profilers')->insert([
        //         //  'user_id' => $user->id,
        //         //  'slowka' => 'Numer usera to: '.$user->id,
        //          DB::statement('ALTER TABLE profilers ADD u'.$user->id.' text'),
        //      ]);
        //  }
        $ile = 0;

         foreach ($users as $user) {
            $columnName = 'u'.$user->id;
            ++$ile;

            // Sprawdzamy, czy kolumna już istnieje
            if (!Schema::hasColumn('profilers', $columnName)) {
                // Jeśli nie istnieje, dodajemy nową kolumnę
                Schema::table('profilers', function ($table) use ($columnName) {
                    $table->text($columnName)->nullable();
                });
            }

// //wstawia przykładowe dane w pierwszym wierszu tablicy/////////////////////
//             if($ile == 1){
//                 // Teraz możemy wstawić dane do tej kolumny
//                 DB::table('profilers')->insert([
//                     'id' => 1,  // Ustaw ID na 1
//                     $columnName => 'wartosc_dla_'.$columnName,
//                     // Dodaj inne kolumny, jeśli są potrzebne
//                 ]);
//             }else{
//                 DB::table('profilers')->where('id', 1)->update([
//                     $columnName => 'słowo_' . $words,
//                     // Dodaj inne kolumny, jeśli są potrzebne
//                 ]);
//             }
// /////////////////////////////////////////////////////////////////////////////
            // DB::table('profilers')->insertOrIgnore([
            //     'id' => 1,
            //     $columnName => 'wartosc_dla_' . $columnName,
            //     // Dodaj inne kolumny, jeśli są potrzebne
            // ]);
        }
// //to niżej dodaje wiersze do tablicy, tyle ile wierszy w tablicy words//////////////
//         // Pętla dodająca dane do bazy
//         for ($i = 1; $i <= $words; $i++) {
//             DB::table('profilers')->insert([
//                 'word_id' => $i,
//             ]);
//         }
            // $words = Word::all();
            // foreach ($words as $word) {
                // DB::table('profilers')->insert([
                //     // 'word_id' => $word->id,
                //     'word_nrzestawu' => 3,
                // ]);
            // }

            // Iteruj po danych źródłowych i wstaw do docelowej tabeli

        // Pobierz dane z źródłowej tabeli
        $daneZrodlowe = DB::table('words')->select('id', 'nrzestawu')->get();
        // Iteruj po danych źródłowych i wstaw do docelowej tabeli
        foreach ($daneZrodlowe as $dane) {
            DB::table('profilers')->insert([
                'word_id' => $dane->id,
                'word_nrzestawu' => $dane->nrzestawu,
                // Dodaj pozostałe kolumny, jeśli są
            ]);
        }
// ////////////////////////////////////////////////////////////////////////////////////
        // DB::table('profilers')->insert([
        //     'user_id' => 2,
        //     'slowka' => 'coś tam',
        // ]);

        // DB::statement('ALTER TABLE profilers ADD test text');

        // \App\Models\Profiler::factory(5)->create();
    }
}
