<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $userTabela = 'usertab2_'.$userId;

        // Wyliczanie i zliczanie wystąpień dla danej kolumny
        // $slowka = DB::table('words')
        // ->selectRaw('nrzestawu, COUNT(nrzestawu) as occurrences, 1 as tab') //liczy ile wystąpień dla każdego zestawu, dodanie tab=1
        // ->groupBy('nrzestawu') //wyniki są grupowane według zestawu
        // ->orderBy('nrzestawu','ASC') //kolejność
        // ->get();

        $slowka2 = DB::table($userTabela)
        ->selectRaw('word_nrzestawu2, COUNT(word_nrzestawu2) as occurrences, 2 as tab') //liczy ile wystąpień dla każdego zestawu, dodanie tab=1
        ->groupBy('word_nrzestawu2') //wyniki są grupowane według zestawu
        ->orderBy('word_nrzestawu2','ASC') //kolejność
        ->get();


        return view('slowka.index',compact('slowka2'));
    }

    /**
     * Show the form for creating a new resource.
     * index - dodanie i usunięcie tabeli
     */
    public function create(Request $request)
    {
        //

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
    public function show($nrzestawu)
    {
        // request()->validate([
        //     'nrzestawu' => 'numeric',
        // ]);
        // $nrzestawu = $nrzestawu2;

        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userTabela = 'usertab2_'.$userId;

        $tab_slowka2 = DB::table($userTabela)
        ->where('word_nrzestawu2', $nrzestawu)
            ->select(
                'id',
                'word_nrzestawu2',
                'slowo2',
                'znaczenie2',
                'przyklad2',
                'dodaj_donauki2',
                'dodaj_dotab2',
                'dodaj_slowo2',
                'edytuj_slowo2',
                'usun_slowo2',
                'tab',
            )
        ->paginate(15);
        // ->get();

        return view('slowka.show',compact('tab_slowka2', 'nrzestawu')); //tab_slowka2 - tylko dla testu
        // ->with('i', ($request->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for editing the specified resource.
     * z index
     */
    public function edit(Request $request,$id)
    {
        request()->validate([
            'currentPage' => 'integer',
            'page' => 'integer',
        ]);
        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userTabela = 'usertab2_'.$userId;

        $word = DB::table($userTabela)
        ->where('id', $id)
            ->select(
                'id',
                'word_nrzestawu2',
                'nrzestawu2',
                'slowo2',
                'znaczenie2',
                'przyklad2',
                'dodaj_donauki2',
                'dodaj_dotab2',
                'dodaj_slowo2',
                'edytuj_slowo2',
                'usun_slowo2',
                'tab',
            )
        ->first();

        if(request('currentPage')){
            $page = request('currentPage');
        }elseif(request('page')){
            $page = request('page');
        }else{
            $page = 1;
        }
        // $tab = $word->tab;
        $nrzestawu = $word->word_nrzestawu2;

        return view('slowka.edit',compact('word','nrzestawu','page'));

    }

    /**
     * Update the specified resource in storage.
     * edit
     *
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'coupdate' => 'string',
            // 'nrzestawu' =>'integer',
            'slowo' => 'string',
            'znaczenie' => 'string',
            'przyklad' => 'string|nullable',
            'word_nrzestawu2',
            'page',
            // 'currentPage' => 'required|integer'
            // 'edytuj_slowo' => 'integer',
        ]);
        // $this->$id = $id;
        $dane = $request->all();
        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;

        $userTabela = 'usertab2_'.$userId;
        $page = $dane['page'];
        // $tab = $dane['tab'];
        // $nrzestawu = $dane['nrzestawu'];
        // $page = $dane['page'];
        // $page = request('page');
        // $nrzestawu = $dane['word_nrzestawu2'];
        // $currentPage =$dane['currentPage'];

        if($dane['coupdate'] === 'uzytkownika'){
            DB::table($userTabela)->where('id', $id)->update([
                // 'word_id' => $id,
                // 'word_nrzestawu2' => $dane['word_nrzestawu'],
                // 'dodaj_dotab2' => $dane['dodaj_dotab'],
                // 'dodaj_slowo2' => $dane['dodaj_slowo'],
                'slowo2' => $dane['slowo'],
                'znaczenie2' => $dane['znaczenie'],
                'przyklad2' => $dane['przyklad'],
                'edytuj_slowo2' => true,
            ]);
            return redirect()->route('slowka.edit', [$id,'page'=>$page]) //page musi być tak zapisane aby przekazało wartość
                        ->with('success','Słowo zostało zaktualizowane ');
        }elseif($dane['coupdate'] === 'resetuj1'){
            $word = DB::table('words')
            ->where('id', $id)
            ->select(
                'slowo',
                'znaczenie',
                'przyklad',
                'nrzestawu'
            )
            ->first();

            DB::table($userTabela)
            ->where('id', $id)->update([
                'nrzestawu2' => 0,
                'slowo2' => $word->slowo,
                'znaczenie2' => $word->znaczenie,
                'przyklad2' => $word->przyklad,
                'dodaj_donauki2' => 0,
                'dodaj_dotab2' => 0,
                'dodaj_slowo2' => 0,
                'edytuj_slowo2' => 0,
                'n2_dodano' => NULL,
                'n2_nauka' => NULL,
                'n2_ile_powt' => 0,
                'n2_1a' => 0,
                'n2_1b' => 0,
                'n2_1c' => null,
                'n2_2a' => 0,
                'n2_2b' => 0,
                'n2_2c' => null,
                'n2_3a' => 0,
                'n2_3b' => 0,
                'n2_3c' => null,
                'n2_4a' => 0,
                'n2_4b' => 0,
                'n2_4c' => null,
                'n2_5a' => 0,
                'n2_5b' => 0,
                'n2_5c' => null,
                'n2_6a' => 0,
                'n2_6b' => 0,
                'n2_6c' => null,
                'n2_7a' => 0,
                'n2_7b' => 0,
                'n2_7c' => null,
                'n2_8a' => 0,
                'n2_8b' => 0,
                'n2_8c' => null,
            ]);
            $nrzestawu = $word->nrzestawu;
            return redirect()->route('slowka.show', [$nrzestawu, 'page' => $page]);
        }elseif($dane['coupdate'] === 'dodaj1'){
            DB::table($userTabela)
            ->where('id', $id)->update([
                'dodaj_slowo2' => true,
                'nrzestawu2' => $dane['word_nrzestawu2'],
                'n2_dodano' => now(),
                'n2_nauka' => now(),
                'n2_ile_powt' => $user->ile_powtorek,
                'n2_1a' => $user->ile_powtorek,
                'n2_1c' => now(),
                'n2_2a' => $user->ile_powtorek,
                'n2_2c' => now(),
                'n2_3a' => $user->ile_powtorek,
                'n2_3c' => now(),
                'n2_4a' => $user->ile_powtorek,
                'n2_4c' => now(),
                'n2_5a' => $user->ile_powtorek,
                'n2_5c' => now(),
                'n2_6a' => $user->ile_powtorek,
                'n2_6c' => now(),
                'n2_7a' => $user->ile_powtorek,
                'n2_7c' => now(),
                'n2_8a' => $user->ile_powtorek,
                'n2_8c' => now(),
            ]);
            return redirect()->route('slowka.show', [$dane['word_nrzestawu2'], 'page' => $page]) //page musi być tak zapisane aby przekazało wartość
                        ->with('success','Dodałeś słowo do nauki');
        }elseif($dane['coupdate'] === 'usun1'){
            DB::table($userTabela)
            ->where('id', $id)->update([
                'dodaj_slowo2' => false,
                'nrzestawu2' => 0,
                'n2_dodano' => null,
                'n2_nauka' => null,
                'n2_ile_powt' => 0,
                'n2_1a' => 0,
                'n2_2a' => 0,
                'n2_3a' => 0,
                'n2_4a' => 0,
                'n2_5a' => 0,
                'n2_6a' => 0,
                'n2_7a' => 0,
                'n2_8a' => 0,
            ]);
            return redirect()->route('slowka.show', [$dane['word_nrzestawu2'], 'page' => $page]) //page musi być tak zapisane aby przekazało wartość
                        ->with('success','Usunąłeś słowo z listy do nauki');
        }
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
        $userTabela = 'usertab2_'.$userId;
        $nrzestawu = $dane['nrzestawu'];

        $word = DB::table('words')
        ->where('id', $id)
        ->select(
            'slowo',
            'znaczenie',
            'przyklad',
        )
        ->first();

        if($dane['destroy'] === 'show1'){
            DB::table($userTabela)
            ->where('id', $id)->update([
                'nrzestawu2' => 0,
                'slowo2' => $word->slowo,
                'znaczenie2' => $word->znaczenie,
                'przyklad2' => $word->przyklad,
                'dodaj_donauki2' => 0,
                'dodaj_dotab2' => 0,
                'dodaj_slowo2' => 0,
                'edytuj_slowo2' => 0,
                'n2_dodano' => NULL,
                'n2_nauka' => NULL,
                'n2_ile_powt' => 0,
                'n2_1a' => 0,
                'n2_1b' => 0,
                'n2_1c' => null,
                'n2_2a' => 0,
                'n2_2b' => 0,
                'n2_2c' => null,
                'n2_3a' => 0,
                'n2_3b' => 0,
                'n2_3c' => null,
                'n2_4a' => 0,
                'n2_4b' => 0,
                'n2_4c' => null,
                'n2_5a' => 0,
                'n2_5b' => 0,
                'n2_5c' => null,
                'n2_6a' => 0,
                'n2_6b' => 0,
                'n2_6c' => null,
                'n2_7a' => 0,
                'n2_7b' => 0,
                'n2_7c' => null,
                'n2_8a' => 0,
                'n2_8b' => 0,
                'n2_8c' => null,
            ]);
        }

        $tab_slowka2 = DB::table($userTabela)
            ->where('word_nrzestawu2', $nrzestawu)
                ->select(
                    'id',
                    'word_nrzestawu2',
                    'slowo2',
                    'znaczenie2',
                    'przyklad2',
                    'dodaj_donauki2',
                    'dodaj_dotab2',
                    'dodaj_slowo2',
                    'edytuj_slowo2',
                    'usun_slowo2',
                    'tab',
                )
            ->paginate(15);
            // ->get();

            $page = $dane['currentPage'];
            return view('slowka.show', [$nrzestawu]);
    }



    public function usunzestaw(Request $request){

    }
}
