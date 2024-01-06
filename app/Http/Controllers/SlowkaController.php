<?php

namespace App\Http\Controllers;

use App\Models\Slowka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Word;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use stdClass;




class SlowkaController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:slowka-list|slowka-create|slowka-edit|slowka-delete', ['only' => ['index','show']]);
         $this->middleware('permission:slowka-create', ['only' => ['create','store']]);
         $this->middleware('permission:slowka-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:slowka-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userKolumna = 'u'.$userId;

        // Wyliczanie i zliczanie wystąpień dla danej kolumny
        $slowka = DB::table('words')
        ->selectRaw('nrzestawu, COUNT(nrzestawu) as occurrences') //liczy ile wystąpień dla każdego zestawu
        ->groupBy('nrzestawu') //wyniki są grupowane według zestawu
        ->orderBy('nrzestawu','ASC') //kolejność
        ->get();

        //dodanie nowej kolumny do zapisania danej czy tabela jest dołączona do nauki czy nie
        $slowka->map(function($item){
            $item->dodana = null;
        });

        // $wyniki = Slowka::whereNotNull($userKolumna) //sprawdza aby nie była null
        // ->groupBy('word_nrzestawu')
        // ->select('word_nrzestawu', \DB::raw('MIN(id) as pierwszy_wiersz_id'), \DB::raw('MAX('.$userKolumna.') as user_column_value'))
        // ->get();

        $wyniki = Slowka::groupBy('word_nrzestawu')
        ->select('word_nrzestawu', DB::raw('MIN(id) as pierwszy_wiersz_id'), DB::raw('MAX('.$userKolumna.') as user_column_value'))
        ->get();

        //pobrać po jednym z każdego zestawu i prawdzić czy jest +
        foreach ($wyniki as $zestaw) {
            $slowko = $slowka->where('nrzestawu', $zestaw->word_nrzestawu)->first();
            if ($slowko) {
                $slowko->dodana = (isset($zestaw->user_column_value) && str_contains($zestaw->user_column_value, ";;dodac=+;;")) ? 1 : 0;
            }
            // if(str_contains($zestaw->user_column_value, ";;dodac=+;;")){
            //     $slowko->dodana = 1;
            // }elseif(str_contains($zestaw->user_column_value, ";;dodac=-;;") || $zestaw->user_column_value == null){
            //     $slowko->dodana = 0;
            // }
        }

        return view('slowka.index',compact('slowka','wyniki'));

        // // Wyświetlanie wyników
        // foreach ($results as $result) {
        // echo "Wartość: {$result->column_name}, Ilość wystąpień: {$result->occurrences} <br>";
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        request()->validate([
            'nrzestawu' => 'required|numeric',
            'dodaj' => 'required|numeric',
        ]);
        $nrzestawu = $request->nrzestawu;
        $dodaj = $request->dodaj;

        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userKolumna = 'u'.$userId;

        //uaktualnienie danych w tablicy profilers dla danego użytkownia (kolumny)
        // Slowka::where('word_nrzestawu', $nrzestawu)->update([$userKolumna => ";;b;;",]);

        if($dodaj === '1'){
            // Slowka::update(["$userKolumna" => 'test', ]);
            Slowka::where('word_nrzestawu',$nrzestawu)->update([$userKolumna => ";;dodac=+;;",]);
        }

        $tab_slowka = DB::table('words')
            ->where('words.nrzestawu', $nrzestawu)
            ->leftJoin('profilers', 'profilers.word_id', '=', 'words.id')
            // ->where('profilers.'.$userKolumna, '=', ';;ktora=c;;') // Poprawka warunku
            ->select(
                'words.id',
                'words.nrzestawu',
                'words.slowo',
                'words.znaczenie',
                'words.przyklad',
                'profilers.'.$userKolumna // Nadanie unikalnej nazwy kolumnie z profilers
            )
            ->get();


        foreach($tab_slowka as $to){
            if (!empty($to->$userKolumna) && str_contains($to->$userKolumna, ";;ktora=c;;")) {
                $tablica = explode(";;", $to->$userKolumna);
                //tworzenie tablicy asocjacyjnej/////////////////////
                $tablica2 = [];
                foreach($tablica as $ten){
                    if(!empty($ten) && $ten != null){
                        $exploded = explode("=", $ten);

                        // Sprawdzenie, czy explode zwróciło wystarczającą ilość elementów
                        if (count($exploded) === 2) {
                            list($key, $value) = $exploded;
                            $tablica2[$key] = $value;
                        }
                    }
                }
                // są wpisane więc zastępuje nowymi danymi
                $to->slowo = $tablica2['slowo'];
                $to->znaczenie = $tablica2['znaczenie'];
                $to->przyklad = $tablica2['przyklad'];

            }
        }

        return view('slowka.show', compact('tab_slowka', 'nrzestawu','userKolumna'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $nrzestawu)
    {
        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userKolumna = 'u'.$userId;

        $tab_slowka = DB::table('words')
            ->where('words.nrzestawu', $nrzestawu)
            ->leftJoin('profilers', 'profilers.word_id', '=', 'words.id')
            // ->where('profilers.'.$userKolumna, '=', ';;ktora=c;;') // Poprawka warunku
            ->select(
                'words.id',
                'words.nrzestawu',
                'words.slowo',
                'words.znaczenie',
                'words.przyklad',
                'profilers.'.$userKolumna, // Nadanie unikalnej nazwy kolumnie z profilers
                // 'profilers.word_id',
                // 'profilers.word_nrzestawu',
            )
            ->get();

        //dodanie nowych kolumn, bez zapisu do bazy
        $tab_slowka->map(function ($item) {
            $item->edytuj = null;
            $item->dodac = null;
            return $item;
        });

        foreach($tab_slowka as $to){
            if (!empty($to->$userKolumna)) {
                $tablica = explode(";;", $to->$userKolumna);
                //tworzenie tablicy asocjacyjnej/////////////////////
                $tablica2 = [];
                foreach($tablica as $ten){
                    if(!empty($ten) && $ten != null){
                        $exploded = explode("=", $ten);

                        // Sprawdzenie, czy explode zwróciło wystarczającą ilość elementów
                        if (count($exploded) === 2) {
                            list($key, $value) = $exploded;
                            $tablica2[$key] = $value;
                        }
                    }
                }
                // są wpisane więc zastępuje nowymi danymi
                if(!empty($tablica2['slowo'])) $to->slowo = $tablica2['slowo'];
                if(!empty($tablica2['znaczenie'])) $to->znaczenie = $tablica2['znaczenie'];
                if(!empty($tablica2['przyklad'])) $to->przyklad = $tablica2['przyklad'];
                // wypełnienie dodatkowych kolumn
                if(!empty($tablica2['ktora'])) $to->edytuj = $tablica2['ktora'];
                if(!empty($tablica2['dodac'])) $to->dodac = $tablica2['dodac'];
            }
            // elseif(!empty($to->$userKolumna) && str_contains($to->$userKolumna, ";;-;;")){
            //     // używasz DB::table, co oznacza, że otrzymujesz wyniki jako kolekcję, a nie model Eloquent.
            //     //poniższy kod używa metody filter, aby pozostawić tylko te elementy, których id nie jest równy id z bieżącej iteracji ($to->id).
            //     // $tab_slowka = $tab_slowka->filter

            //     $tablica = explode(";;", $to->$userKolumna);
            //     //tworzenie tablicy asocjacyjnej/////////////////////
            //     $tablica2 = [];
            //     foreach($tablica as $ten){
            //         if(!empty($ten) && $ten != null){
            //             $exploded = explode("=", $ten);

            //             // Sprawdzenie, czy explode zwróciło wystarczającą ilość elementów
            //             if (count($exploded) === 2) {
            //                 list($key, $value) = $exploded;
            //                 $tablica2[$key] = $value;
            //             }
            //         }
            //     }
            //     // Sprawdzamy, czy kolumna $nowaKolumna już istnieje w obiekcie
            //     if (property_exists($tablica2, $nowaKolumna)) {
            //         // Jeśli istnieje, zmieniamy jej zawartość
            //         $tablica2->$nowaKolumna = 'Nowa zawartość'; // Tutaj możesz podać właściwą nową zawartość
            //     } else {
            //         // Jeśli nie istnieje, dodajemy nową kolumnę do obiektu z danymi
            //         $tablica2->$nowaKolumna = 'Nowe dane'; // Tutaj możesz podać właściwe dane do dodania
            //     }
            // }

        }
        //dodanie nowego rekordu dla sprawdzenia czy słowo usunięte czy nie

        return view('slowka.show',compact('tab_slowka', 'nrzestawu', 'userKolumna'));
            // ->with('i', ($request->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userKolumna = 'u'.$userId;

        // $word = Slowka::where('word_id', $id)->select('id','word_id','word_nrzestawu',$userKolumna)->first();
        // $word = DB::table('words')
        //     ->leftJoin('profilers', 'profilers.word_id', '=', 'words.id')
        //     ->where('words.id', $id)
        //     ->where(function ($query) use ($userKolumna) {
        //         $query->whereNull('profilers.'.$userKolumna)
        //             ->orWhere('profilers.'.$userKolumna, 'LIKE', '%;;ktora=c;;%');
        //     })
        //     ->select(
        //         'words.id',
        //         'words.nrzestawu',
        //         'words.slowo',
        //         'words.znaczenie',
        //         'words.przyklad',
        //         'profilers.'.$userKolumna // Dołączenie kolumny z profilers
        //     )
        //     ->first();

            // $word = DB::table('words')
            // ->where('words.nrzestawu', $nrzestawu)
            // ->leftJoin('profilers', 'profilers.word_id', '=', 'words.id')
            // // ->where('profilers.'.$userKolumna, '=', ';;ktora=c;;') // Poprawka warunku
            // ->select(
            //     'words.id',
            //     'words.nrzestawu',
            //     'words.slowo',
            //     'words.znaczenie',
            //     'words.przyklad',
            //     'profilers.'.$userKolumna // Nadanie unikalnej nazwy kolumnie z profilers
            // )
            // ->first();

            $word = DB::table('words')
            ->where('words.id', $id)
            ->leftJoin('profilers', 'words.id', '=', 'profilers.word_id')
            ->select(
                'words.id',
                'words.nrzestawu',
                'words.slowo',
                'words.znaczenie',
                'words.przyklad',
                'profilers.'.$userKolumna // Nadanie unikalnej nazwy kolumnie z profilers
            )
            ->first();

            if (!empty($word->$userKolumna) && str_contains($word->$userKolumna, ";;ktora=c;;")) {
                $tablica = explode(";;", $word->$userKolumna);
                //tworzenie tablicy asocjacyjnej/////////////////////
                $tablica2 = [];
                foreach($tablica as $ten){
                    if(!empty($ten)){
                        $exploded = explode("=", $ten);

                        // Sprawdzenie, czy explode zwróciło wystarczającą ilość elementów
                        if (count($exploded) === 2) {
                            list($key, $value) = $exploded;
                            $tablica2[$key] = $value;
                        }
                    }
                }
                // są wpisane więc zastępuje nowymi danymi
                $word->slowo = $tablica2['slowo'];
                $word->znaczenie = $tablica2['znaczenie'];
                $word->przyklad = $tablica2['przyklad'];
            }
            $nrzestawu = $word->nrzestawu;

        return view('slowka.edit',compact('word','userKolumna'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'nrzestawu' => 'required|numeric',
            'slowo' => 'required|string',
            'znaczenie' => 'required|string',
            'przyklad' => 'string',
        ]);
        $dane = $request->all();
        $user = $dane['ipus'];

        $slowo1 = Slowka::where('word_id', $id)->select('id','word_id','word_nrzestawu',$user)->first();
        if($slowo1->$user === null || empty($slowo1->$user)){
            Slowka::where('id',$id)->update([
                'word_nrzestawu' => $dane['nrzestawu'],
                $user => ';;dodac=+;;ktora=c;;slowo='.$dane['slowo'].';;znaczenie='.$dane['znaczenie'].';;przyklad='.$dane['przyklad'].';;',
            ]);
        }else{
            if(str_contains($slowo1->$user, ";;dodac=+;;")){
                $tablica = explode(";;", $slowo1->$user);
                //tworzenie tablicy asocjacyjnej/////////////////////
                $tablica2 = array();
                foreach($tablica as $to){
                    if(!empty($to) && $to != null){
                        $exploded = explode("=", $to);

                        // Sprawdzenie, czy explode zwróciło wystarczającą ilość elementów
                        if (count($exploded) === 2) {
                            list($key, $value) = $exploded;
                            $tablica2[$key] = $value;
                        }
                    }
                }

                $tablica2['dodac'] = '+';
                $tablica2['ktora'] = 'c';
                $tablica2['slowo'] = $dane['slowo'];
                $tablica2['znaczenie'] = $dane['znaczenie'];
                $tablica2['przyklad'] = $dane['przyklad'];
                //konwersja na string/////////////////////////////
                $string_output = ";;".implode(";;", array_map(function($key, $value){
                    return "$key=$value";
                }, array_keys($tablica2), $tablica2)).";;";
                ////////////////////////////////////////////////////
                Slowka::where('id',$id)->update([
                    'word_nrzestawu' => $dane['nrzestawu'],
                    $user => $string_output,
                ]);
            }else{
                $tablica = explode(";;", $slowo1->$user);
                //tworzenie tablicy asocjacyjnej/////////////////////
                $tablica2 = array();
                foreach($tablica as $to){
                    if(!empty($to) && $to != null){
                        $exploded = explode("=", $to);

                        // Sprawdzenie, czy explode zwróciło wystarczającą ilość elementów
                        if (count($exploded) === 2) {
                            list($key, $value) = $exploded;
                            $tablica2[$key] = $value;
                        }
                    }
                }

                // $tablica2['dodac'] = '+';
                $tablica2['ktora'] = 'c';
                $tablica2['slowo'] = $dane['slowo'];
                $tablica2['znaczenie'] = $dane['znaczenie'];
                $tablica2['przyklad'] = $dane['przyklad'];
                //konwersja na string/////////////////////////////
                $string_output = implode(";;", array_map(function($key, $value){
                    return "$key=$value";
                }, array_keys($tablica2), $tablica2));
                ////////////////////////////////////////////////////
                Slowka::where('id',$id)->update([
                    'word_nrzestawu' => $dane['nrzestawu'],
                    $user => $string_output,
                ]);
            }
        }

        //konwersja na string/////////////////////////////
        // $string_output = implode(";;", array_map(function($key, $value){
        //     return "$key=$value";
        // }, array_keys($tablica2), $tablica2));
        ////////////////////////////////////////////////////



        return redirect()->route('slowka.edit', ['slowka' => $id])
                        ->with('success','Słowo zostało zaktualizowane');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userKolumna = 'u'.$userId;

        // $slowo = Slowka::where('id',$id);
        // $slowo->$userKolumna = ';;-;;';
        // $slowo->save();
        $nrzestawu1 = Slowka::where('word_id', $id)->select('word_nrzestawu')->first();
        $nrzestawu = $nrzestawu1->word_nrzestawu;
        // $nrzestawu = $nrzestawu->word_nrzestawu;
        // Przykład z użyciem updateOrInsert:
        Slowka::updateOrInsert(
            ['id' => $id],
            [$userKolumna => ';;dodac=-;;']
        );

        $tab_slowka = DB::table('words')
            ->where('words.nrzestawu', $nrzestawu)
            ->leftJoin('profilers', 'profilers.word_id', '=', 'words.id')
            // ->where('profilers.'.$userKolumna, '=', ';;ktora=c;;') // Poprawka warunku
            ->select(
                'words.id',
                'words.nrzestawu',
                'words.slowo',
                'words.znaczenie',
                'words.przyklad',
                'profilers.'.$userKolumna // Nadanie unikalnej nazwy kolumnie z profilers
            )
            ->get();

            //dodanie nowych kolumn, bez zapisu do bazy
        $tab_slowka->map(function ($item) {
            $item->edytuj = null;
            $item->dodac = null;
            return $item;
        });

        foreach($tab_slowka as $to){
            // if (!empty($to->$userKolumna) && str_contains($to->$userKolumna, ";;ktora=c;;")) {
                $tablica = explode(";;", $to->$userKolumna);
                //tworzenie tablicy asocjacyjnej/////////////////////
                $tablica2 = [];
                foreach($tablica as $ten){
                    if(!empty($ten) && $ten != null){
                        $exploded = explode("=", $ten);

                        // Sprawdzenie, czy explode zwróciło wystarczającą ilość elementów
                        if (count($exploded) === 2) {
                            list($key, $value) = $exploded;
                            $tablica2[$key] = $value;
                        }
                    }
                }
                // są wpisane więc zastępuje nowymi danymi
                if(!empty($tablica2['slowo'])) $to->slowo = $tablica2['slowo'];
                if(!empty($tablica2['znaczenie'])) $to->znaczenie = $tablica2['znaczenie'];
                if(!empty($tablica2['przyklad'])) $to->przyklad = $tablica2['przyklad'];
                // wypełnienie dodatkowych kolumn
                if(!empty($tablica2['ktora'])) $to->edytuj = $tablica2['ktora'];
                if(!empty($tablica2['dodac'])) $to->dodac = $tablica2['dodac'];

            // }elseif(!empty($to->$userKolumna) && str_contains($to->$userKolumna, ";;-;;")){
            //     // używasz DB::table, co oznacza, że otrzymujesz wyniki jako kolekcję, a nie model Eloquent.
            //     //poniższy kod używa metody filter, aby pozostawić tylko te elementy, których id nie jest równy id z bieżącej iteracji ($to->id).
            //     $tab_slowka = $tab_slowka->filter(function ($item) use ($to) {
            //         return $item->id !== $to->id;
            //     });
            // }
        }


        // ma być przekierowanie
        return view('slowka.show', compact('tab_slowka','nrzestawu'))
        ->with('success','Słowo zostało usunięte');
    }



    public function showslowko($id){
        $word = Word::find($id);
        return view('slowko.edit',compact('tab_slowka', 'nrzestawu'));
    }
}
