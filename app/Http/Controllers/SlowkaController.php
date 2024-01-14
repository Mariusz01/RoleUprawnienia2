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
        $userTabela = 'usertab_'.$userId;

        // Wyliczanie i zliczanie wystąpień dla danej kolumny
        $slowka = DB::table('words')
        ->selectRaw('nrzestawu, COUNT(nrzestawu) as occurrences') //liczy ile wystąpień dla każdego zestawu
        ->groupBy('nrzestawu') //wyniki są grupowane według zestawu
        ->orderBy('nrzestawu','ASC') //kolejność
        ->get();

        // $slowka->map(function ($item) {
        //     $item->dodaj_tab = false;
        //     return $item;
        // });

        foreach($slowka as $slowo){
            $to = DB::table($userTabela)
            ->where('word_nrzestawu', $slowo->nrzestawu)
            ->where('dodaj_tab', $slowo->nrzestawu)
            ->select('dodaj_tab')
            ->first();

            // to musi być kiedy przechodzi z innego to tego widoku czyli np. z create
            if(empty($to) || $to === false){
                $slowo->dodaj_tab = false;
            }else{
                $slowo->dodaj_tab = true;
            }
        }

        return view('slowka.index',compact('slowka'));
    }

    /**
     * Show the form for creating a new resource.
     * index - dodanie i usunięcie tabeli
     */
    public function create(Request $request)
    {
        request()->validate([
            'nrzestawu' => 'required|numeric',
            'dodaj' => 'required|numeric', // 1 i 2 jest z index
        ]);
        $nrzestawu = $request->nrzestawu;
        $dodaj = $request->dodaj;

        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userTabela = 'usertab_'.$userId;
/////////////////////////////////////////////////////
        if($dodaj == 2){//dodaj zestaw do nauki
            //pobranie tablicy words
            $slowka2 = DB::table('words')
            ->where('nrzestawu', $nrzestawu)
            ->select(
                'id',
                'nrzestawu',
                'slowo',
                'znaczenie',
                'przyklad',
            )
            ->get();

            foreach($slowka2 as $word1){
                $edytowane = DB::table($userTabela)
                ->where('word_id', $word1->nrzestawu)
                ->select('edytuj_slowo')
                ->first();

                if(!$edytowane){//jeśli było edytowane przed dodaniem zestawu do nauki
                    DB::table($userTabela)->updateOrInsert(
                        ['word_id' => $word1->id], // Warunki filtrujące
                        ['word_nrzestawu' => $word1->nrzestawu,'word_id' => $word1->id, 'dodaj_tab' => $word1->nrzestawu]
                    );
                }else{
                    DB::table($userTabela)->updateOrInsert(
                        ['word_id' => $word1->id], // Warunki filtrujące
                        ['word_nrzestawu' => $word1->nrzestawu,'word_id' => $word1->id, 'slowo2' => null,'znaczenie2'=>null,
                            'przyklad2' => null, 'dodaj_tab' => $word1->nrzestawu]
                    );
                }


            }
            $slowka = DB::table('words')
            ->selectRaw('nrzestawu, COUNT(nrzestawu) as occurrences') //liczy ile wystąpień dla każdego zestawu
            ->groupBy('nrzestawu') //wyniki są grupowane według zestawu
            ->orderBy('nrzestawu','ASC') //kolejność
            ->get();

            //to z index
            foreach($slowka as $slowo){
                $to = DB::table($userTabela)
                ->where('dodaj_tab', $slowo->nrzestawu)
                ->select('dodaj_tab')
                ->first();

                // to musi być kiedy przechodzi z innego to tego widoku czyli np. z create
                if(empty($to) || $to === false){
                    $slowo->dodaj_tab = 0;
                }else{
                    $slowo->dodaj_tab = $to;
                }
            }
        }elseif($dodaj == 1){
            //pobranie tablicy words
            $slowka2 = DB::table('words')
            ->where('nrzestawu', $nrzestawu)
            ->select(
                'id',
                'nrzestawu',
                'slowo',
                'znaczenie',
                'przyklad',
            )
            ->get();
            //to jest raczej do usunięcia wszystkich wierdszy które mają word_nrzestawu
            foreach($slowka2 as $word1){
                $raz = DB::table($userTabela)
                ->where('word_id', $word1->id)
                ->first();
                if(!empty($raz->dodaj_slowo) && $raz->dodaj_slowo != null){// jeśli jest dodany to tylko zaktualizować, albo nic
                    DB::table($userTabela)->updateOrInsert(
                        ['word_id' => $word1->id], // Warunki filtrujące
                        ['word_nrzestawu' => $word1->nrzestawu,'word_id' => $word1->id, 'slowo2' => null,'znaczenie2'=>null,
                            'przyklad2' => null, 'dodaj_tab' => null]
                    );
                }else{
                    // usunięcie wiersza
                    DB::table($userTabela)->where('word_id', $word1->id)->delete();
                }
            }



            $slowka = DB::table('words')
            ->selectRaw('nrzestawu, COUNT(nrzestawu) as occurrences') //liczy ile wystąpień dla każdego zestawu
            ->groupBy('nrzestawu') //wyniki są grupowane według zestawu
            ->orderBy('nrzestawu','ASC') //kolejność
            ->get();

            //to z index
            foreach($slowka as $slowo){
                $to = DB::table($userTabela)
                ->where('dodaj_tab', $slowo->nrzestawu)
                ->select('dodaj_tab')
                ->first();

                // to musi być kiedy przechodzi z innego to tego widoku czyli np. z create
                if(empty($to) || $to === false){
                    $slowo->dodaj_tab = 0;
                }else{
                    $slowo->dodaj_tab = $to;
                }
            }
        }
/////////////////////////////////////////////////////

        return view('slowka.index', compact('slowka'));

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
        $userTabela = 'usertab_'.$userId;

        // $liczbaRekordow = DB::table($userTabela)->count();

        $tab_slowka = DB::table('words')
        ->where('words.nrzestawu', $nrzestawu)
        ->leftJoin($userTabela, $userTabela.'.word_id', '=', 'words.id')
        ->select(
            'words.id',
            'words.nrzestawu',
            'words.slowo',
            'words.znaczenie',
            'words.przyklad',
            $userTabela.'.dodaj_tab',
            $userTabela.'.slowo2',
            $userTabela.'.znaczenie2',
            $userTabela.'.przyklad2',
            $userTabela.'.edytuj_slowo',
        )
        ->paginate(15);


        return view('slowka.show',compact('tab_slowka', 'nrzestawu', 'userTabela'));
        // ->with('i', ($request->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for editing the specified resource.
     * z index
     */
    public function edit($id)
    {

        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userTabela = 'usertab_'.$userId;

        $word = DB::table('words')
        ->where('words.id', $id)
        ->leftJoin($userTabela, 'words.id', '=', $userTabela.'.word_id')
        ->select(
            'words.id',
            'words.nrzestawu',
            'words.slowo',
            'words.znaczenie',
            'words.przyklad',
            $userTabela.'.word_nrzestawu',
            $userTabela.'.slowo2',
            $userTabela.'.znaczenie2',
            $userTabela.'.przyklad2',
            $userTabela.'.edytuj_slowo',
            $userTabela.'.dodaj_tab',
            $userTabela.'.dodaj_slowo',
        )
        ->first();

        if($word->edytuj_slowo){
            $word->slowo = $word->slowo2;
            $word->znaczenie = $word->znaczenie2;
            $word->przyklad = $word->przyklad2;
        }

        return view('slowka.edit',compact('word'));

    }

    /**
     * Update the specified resource in storage.
     * edit
     *
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'coupdate' => 'required|string',
            'nrzestawu',
            'slowo' => 'required|string',
            'znaczenie' => 'required|string',
            'przyklad' => 'string',
            'edytuj_slowo',
            'dodaj_tab',
        ]);
        $dane = $request->all();
        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userTabela = 'usertab_'.$userId;

        if($dane['coupdate'] === 'uzytkownika'){
            $existingRecord = DB::table($userTabela)->where('id', $id)->first();
            if ($existingRecord) {
                // Rekord istnieje, wykonaj aktualizację zamiast wstawiania
                DB::table($userTabela)->where('id', $id)->update([
                    'word_id' => $dane['word_id'],
                    'word_nrzestawu' => $dane['word_nrzestawu'],
                    'dodaj_tab' => $dane['dodaj_tab'],
                    'dodaj_slowo' => $dane['dodaj_slowo'],
                    'slowo2' => $dane['slowo'],
                    'znaczenie2' => $dane['znaczenie'],
                    'przyklad2' => $dane['przyklad'],
                    'edytuj_slowo' => true,
                ]);
            } else {
                // Rekord nie istnieje, wykonaj wstawianie
                DB::table($userTabela)->insert([
                    'id' => $id,
                    'word_id' => $dane['word_id'],
                    'word_nrzestawu' => $dane['word_nrzestawu'],
                    'dodaj_tab' => $dane['dodaj_tab'],
                    'dodaj_slowo' => $dane['dodaj_slowo'],
                    'slowo2' => $dane['slowo'],
                    'znaczenie2' => $dane['znaczenie'],
                    'przyklad2' => $dane['przyklad'],
                    'edytuj_slowo' => true,
                ]);
            }
        }

        return redirect()->route('slowka.edit', [$id])
                        ->with('success','Słowo zostało zaktualizowane');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {

        $dane = $request->all();
        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userTabela = 'usertab_'.$userId;
        $nrzestawu = $dane['nrzestawu'];

        if($dane['destroy'] === 'show1'){
            DB::table($userTabela)
            ->where('word_id', $id)->update([
                'slowo2' => null,
                'znaczenie2' => null,
                'przyklad2' => null,
                'dodaj_tab' => null,
                'dodaj_slowo' => null,
                'edytuj_slowo' => null,
                'dalej1' => null,
                'dalej2' => null,
                'dalej3' => null,
                'nauka1' => null,
                'nauka2' => null,
                'nauka3' => null,
                'nauka4' => null,
            ]);

            $tab_slowka = DB::table('words')
            ->where('words.nrzestawu', $nrzestawu)
            ->leftJoin($userTabela, $userTabela.'.word_id', '=', 'words.id')
            ->select(
                'words.id',
                'words.nrzestawu',
                'words.slowo',
                'words.znaczenie',
                'words.przyklad',
                $userTabela.'.dodaj_tab',
                $userTabela.'.slowo2',
                $userTabela.'.znaczenie2',
                $userTabela.'.przyklad2',
                $userTabela.'.edytuj_slowo',
            )
            ->paginate(15);
        }elseif($dane['destroy'] === 'show2'){// na razie nic nie robi, jest zielony w widoku show
            $tab_slowka = DB::table('words')
            ->where('words.nrzestawu', $nrzestawu)
            ->leftJoin($userTabela, $userTabela.'.word_id', '=', 'words.id')
            ->select(
                'words.id',
                'words.nrzestawu',
                'words.slowo',
                'words.znaczenie',
                'words.przyklad',
                $userTabela.'.dodaj_tab',
                $userTabela.'.slowo2',
                $userTabela.'.znaczenie2',
                $userTabela.'.przyklad2',
                $userTabela.'.edytuj_slowo',
                )
            ->paginate(15);
        }

        // ma być przekierowanie
        return view('slowka.show', compact('tab_slowka','nrzestawu'))
        ->with('success','Słowo zostało usunięte');
    }



    public function usunzestaw(Request $request){
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

        if($dodaj === '2'){
            // Slowka::update(["$userKolumna" => 'test', ]);
            Slowka::where('word_nrzestawu',$nrzestawu)->update([$userKolumna => null,]);
        }

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

        return view('slowka.index', compact('slowka','wyniki'));
    }
}
