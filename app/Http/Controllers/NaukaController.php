<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class NaukaController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:nauka-list|nauka-create|nauka-edit|nauka-delete', ['only' => ['index','show']]);
         $this->middleware('permission:nauka-create', ['only' => ['create','store']]);
         $this->middleware('permission:nauka-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:nauka-delete', ['only' => ['destroy']]);

         $this->middleware('permission:nauka-uaktualnij', ['only' => ['edit','update']]);
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
        $ilePowtorek = $user->ile_powtorek;

        //pobranie ile jest punktów
        $sumaPunktów = DB::table($userTabela)->sum('pkt_razem2');

        $slowka2 = DB::table($userTabela)
        ->where('n2_ile_powt', '>', 0)  // Poprawiłem warunek
        ->select(
            'slowo2',
            'znaczenie2'
        )
        ->get();

        $n2_1 = DB::table($userTabela)
        ->where('n2_1a', '>', 0)
        ->whereDate('n2_1c', '<=', now())
        ->count();
        $n2_2 = DB::table($userTabela)
        ->where('n2_2a', '>', 0)
        ->whereDate('n2_2c', '<=', Carbon::today())
        ->count();
        $n2_3 = DB::table($userTabela)
        ->where('n2_3a', '>', 0)
        ->whereDate('n2_3c', '<=', Carbon::today())
        ->count();
        $n2_4 = DB::table($userTabela)
        ->where('n2_4a', '>', 0)
        ->whereDate('n2_4c', '<=', Carbon::today())
        ->count();
        $n2_5 = DB::table($userTabela)
        ->where('n2_5a', '>', 0)
        ->whereDate('n2_5c', '<=', Carbon::today())
        ->count();
        $n2_6 = DB::table($userTabela)
        ->where('n2_6a', '>', 0)
        ->whereDate('n2_6c', '<=', Carbon::today())
        ->count();
        $n2_7 = DB::table($userTabela)
        ->where('n2_7a', '>', 0)
        ->whereDate('n2_7c', '<=', Carbon::today())
        ->count();
        $n2_8 = DB::table($userTabela)
        ->where('n2_8a', '>', 0)
        ->whereDate('n2_8c', '<=', Carbon::today())
        ->count();

        return view('nauka.index',compact('slowka2','ilePowtorek','n2_1','n2_2','n2_3','n2_4','n2_5','n2_6','n2_7','n2_8','userTabela','sumaPunktów'))
        ->with('success','z index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    public function show(Request $request, $nauka)
    {

        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userTabela = 'usertab2_'.$userId;
        $ilePowtorek = $user->ile_powtorek;
        $czyOK = null;
        // $zmiennaTestowa = 'nic | '; // do testów dlaczego nalicza podwójnie

        if($nauka == 1){ //wybrany zestaw 1 - wybranie właściwego tłumaczenia
            request()->validate([
                // 'nauka' => 'required|integer',
                'slowo' => 'string',
                'slowo0' => 'string',
                'dalej' => 'string',
                'znaczenie0' => 'string',
                'znaczenie1' => 'string',
                'znaczenie2' => 'string',
                'znaczenie3' => 'string',
                'znaczenie4' => 'string',
            ]);
            // $this->$id = $id;
            $daneReq = $request->all();
            $tytul = "1. Wybierz polskie znaczenie";
            // zmienna potrzebna do wybrania jak wyświetli się strona, w zależności od przekazanych danych
            $jakaStrona = 1;

            if(isset($daneReq['dalej']) && $daneReq['dalej'] === 'zaznaczono'){ // jeśli zostało wybrane znaczenie słówka
                //zostało wybrane, tu jest co potem, i w tym obsługa jeśli OK albo jeśli błędna
                $sprawdzenie = DB::table($userTabela)
                ->where('slowo2', $daneReq['slowo0'])
                ->select('slowo2','znaczenie2')
                ->first();

                //sprawdzenie czy dobrze zaznaczone
                $tekst1 = $sprawdzenie->znaczenie2;
                $tekst2 = $daneReq['znaczenie0'];
                $comparison = strcmp($tekst1, $tekst2);
                if($comparison == 0){ // dobre wskazanie
                    $czyOK = 1; // do potwierdzenia czy dobrze zaznaczone, czy nie, na stronie
                    //jeśli b == 0 to 2 dni, jeśli 1 tzn. był błąd i 1 dzień i wyzerowanie
                    $czyZero = DB::table($userTabela)
                    ->where('slowo2', $daneReq['slowo0'])
                    ->select(
                        'n2_1b',
                        'n2_1a',
                        'pkt_razem2',
                    )
                    ->first();

                    // teraz może by było ok bezpośrednio w zapytaniu uaktualniającym
                    // $liczbaPowtork = $czyZero->n2_1a - 1;//nie odejmowa jeśli było bezpośrenio w zapytaniu do bazy

                    if($czyZero->n2_1b == 0){ // jeśli 0 to dodać 2 dni, odjąć 1 powtórkę
                        DB::table($userTabela)
                        ->where('slowo2', $daneReq['slowo0'])
                        ->update([
                            'n2_1a' => $czyZero->n2_1a - 1,
                            'n2_1b' => 0,
                            'n2_1c' => now()->addDays(2),
                            'pkt_razem2' => $czyZero->pkt_razem2 + 1,
                        ]);
                        // $zmiennaTestowa = $zmiennaTestowa . 'było 0, dodało 2 dni';
                    }elseif($czyZero->n2_1b == 1){ // jeśli pierwsza odpowiedź była błędna to jest 1, dodać 1 dzień, jutro powtórka
                        DB::table($userTabela)
                        ->where('slowo2', $daneReq['slowo0'])
                        ->update([
                            'n2_1a' => $czyZero->n2_1a - 1, // nie odjeło tutaj, czyli wyżej też nie
                            'n2_1b' => 0,
                            'n2_1c' => now()->addDays(1),
                            'pkt_razem2' => $czyZero->pkt_razem2 + 0.5,
                        ]);
                        // $zmiennaTestowa = $zmiennaTestowa . 'było 1, dodało 1 dni';
                    }


                }else{// błedne wybranie tłumaczenia
                    $czyOK = 0;
                    //uaktualnienie n2_1b na 1 aby przy dobrej odpowiedzi dodało już tylko 1 dzień a nie 2
                    DB::table($userTabela)
                    ->where('slowo2', $daneReq['slowo0'])
                    ->update([
                        'n2_1b' => 1,
                    ]);
                }

                $n2_1['slowo2'] = $daneReq['slowo0'];
                $n2_1['znaczenie2'] = $sprawdzenie->znaczenie2;
                $n2_1['zaznaczono2'] = $daneReq['znaczenie0'];
                $n2_1 = (object)$n2_1; //konwersja na objekt

                $dane[]['znaczenie2'] = $daneReq['znaczenie1'];
                $dane[]['znaczenie2'] = $daneReq['znaczenie2'];
                $dane[]['znaczenie2'] = $daneReq['znaczenie3'];
                $dane[]['znaczenie2'] = $daneReq['znaczenie4'];
                $dalej = 'stop';

                //pobranie ile jest punktów
                $sumaPunktów = DB::table($userTabela)->sum('pkt_razem2');
                $ileSlowekZostalo = DB::table($userTabela)
                ->where('n2_1a', '>', 0)
                ->whereDate('n2_1c', '<=', Carbon::today())
                ->count();


                return view('nauka.show', compact('tytul','n2_1','dane','dalej','czyOK','sumaPunktów','jakaStrona','ileSlowekZostalo'));
            }else{// kiedy wyświetla się do wyboru, słówko a potem losuje 3 błędne odpowiedzi
                $n2_1 = DB::table($userTabela)
                ->where('n2_1a', '>', 0)
                ->whereDate('n2_1c', '<=', now())
                ->inRandomOrder()
                ->select('id','slowo2','znaczenie2')
                ->first();

                if(!isset($n2_1->id)){ //kiedy nie ma dodanego żadnego słówka do nauki, bo jest wtedy błąd
                    return redirect()->route('nauka.index')
                    ->with('success','Dodaj słówka do nauki');
                }else{// są słówka po przejściu do Nauka, wcześniej pobrało słówko do nauki, tutaj losuje błędne podpowiedzi

                    $wylosowaneDane = DB::table($userTabela)
                    ->where('n2_1a', '!=', $n2_1->id)
                    ->select('id','slowo2','znaczenie2')
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();

                    $dane = collect([$n2_1])->merge($wylosowaneDane);
                    // pomieszanie kolejności
                    $dane = $dane->shuffle();

                    //pobranie ile jest punktów
                    $sumaPunktów = DB::table($userTabela)->sum('pkt_razem2');
                    $ileSlowekZostalo = DB::table($userTabela)
                    ->where('n2_1a', '>', 0)
                    ->whereDate('n2_1c', '<=', Carbon::today())
                    ->count();

                    return view('nauka.show', compact('tytul','n2_1','dane','sumaPunktów','jakaStrona','ileSlowekZostalo'));
                    // return redirect()->route('nauka.show',[1,$tytul,$n2_1,$dane,$zmiennaTestowa]);
                }
            }
        }elseif(($nauka == 2)){
            request()->validate([
                // 'nauka' => 'required|integer',
                'slowo' => 'string',
                'dalej' => 'string',
                'wyswietlone' => 'string',
                'wybrane' => 'string',
                'slowo1' => 'string',
                'slowo2' => 'string',
                'slowo3' => 'string',
                'slowo4' => 'string',
            ]);
            // $this->$id = $id;
            $daneReq = $request->all();
            $tytul = "2. Wybierz angielskie znaczenie";
            // zmienna potrzebna do wybrania jak wyświetli się strona, w zależności od przekazanych danych
            $jakaStrona = 2;


            if(isset($daneReq['dalej']) && $daneReq['dalej'] === 'zaznaczono'){ // jeśli zostało wybrane znaczenie słówka
                //zostało wybrane, tu jest co potem, i w tym obsługa jeśli OK albo jeśli błędna
                $sprawdzenie = DB::table($userTabela)
                ->where('znaczenie2', $daneReq['znaczenie0'])
                ->select('slowo2','znaczenie2')
                ->first();

                //sprawdzenie czy dobrze zaznaczone
                $tekst1 = $sprawdzenie->slowo2;
                $tekst2 = $daneReq['slowo0'];
                $comparison = strcmp($tekst1, $tekst2);
                if($comparison == 0){ // dobre wskazanie
                    $czyOK = 1; // do potwierdzenia czy dobrze zaznaczone, czy nie, na stronie
                    //jeśli b == 0 to 2 dni, jeśli 1 tzn. był błąd i 1 dzień i wyzerowanie
                    $czyZero = DB::table($userTabela)
                    ->where('znaczenie2', $daneReq['znaczenie0'])
                    ->select(
                        'n2_1b',
                        'n2_1a',
                        'pkt_razem2',
                    )
                    ->first();

                    // teraz może by było ok bezpośrednio w zapytaniu uaktualniającym
                    // $liczbaPowtork = $czyZero->n2_1a - 1;//nie odejmowa jeśli było bezpośrenio w zapytaniu do bazy

                    if($czyZero->n2_1b == 0){ // jeśli 0 to dodać 2 dni, odjąć 1 powtórkę
                        DB::table($userTabela)
                        ->where('znaczenie2', $daneReq['znaczenie0'])
                        ->update([
                            'n2_1a' => $czyZero->n2_1a - 1,
                            'n2_1b' => 0,
                            'n2_1c' => now()->addDays(2),
                            'pkt_razem2' => $czyZero->pkt_razem2 + 1,
                        ]);
                        // $zmiennaTestowa = $zmiennaTestowa . 'było 0, dodało 2 dni';
                    }elseif($czyZero->n2_1b == 1){ // jeśli pierwsza odpowiedź była błędna to jest 1, dodać 1 dzień, jutro powtórka
                        DB::table($userTabela)
                        ->where('znaczenie2', $daneReq['znaczenie0'])
                        ->update([
                            'n2_1a' => $czyZero->n2_1a - 1, // nie odjeło tutaj, czyli wyżej też nie
                            'n2_1b' => 0,
                            'n2_1c' => now()->addDays(1),
                            'pkt_razem2' => $czyZero->pkt_razem2 + 0.5,
                        ]);
                        // $zmiennaTestowa = $zmiennaTestowa . 'było 1, dodało 1 dni';
                    }


                }else{// błedne wybranie tłumaczenia
                    $czyOK = 0;
                    //uaktualnienie n2_1b na 1 aby przy dobrej odpowiedzi dodało już tylko 1 dzień a nie 2
                    DB::table($userTabela)
                    ->where('znaczenie2', $daneReq['znaczenie0'])
                    ->update([
                        'n2_1b' => 1,
                    ]);
                }

                $n2_1['slowo2'] = $sprawdzenie->slowo2;
                $n2_1['znaczenie2'] = $sprawdzenie->znaczenie2;
                $n2_1['zaznaczono2'] = $daneReq['slowo0'];
                $n2_1 = (object)$n2_1; //konwersja na objekt

                $dane[]['slowo2'] = $daneReq['slowo1'];
                $dane[]['slowo2'] = $daneReq['slowo2'];
                $dane[]['slowo2'] = $daneReq['slowo3'];
                $dane[]['slowo2'] = $daneReq['slowo4'];
                $dalej = 'stop';

                //pobranie ile jest punktów
                $sumaPunktów = DB::table($userTabela)->sum('pkt_razem2');
                //pobranie ile słówek pozostało do nauki
                $ileSlowekZostalo = DB::table($userTabela)
                ->where('n2_2a', '>', 0)
                ->whereDate('n2_2c', '<=', Carbon::today())
                ->count();

                return view('nauka.show', compact('tytul','n2_1','dane','dalej','czyOK','sumaPunktów','jakaStrona','ileSlowekZostalo'));
            }else{// kiedy wyświetla się do wyboru, słówko a potem losuje 3 błędne odpowiedzi
                $n2_1 = DB::table($userTabela)
                ->where('n2_1a', '>', 0)
                ->whereDate('n2_1c', '<=', now())
                ->inRandomOrder()
                ->select('id','slowo2','znaczenie2')
                ->first();

                if(!isset($n2_1->id)){ //kiedy nie ma dodanego żadnego słówka do nauki, bo jest wtedy błąd
                    return redirect()->route('nauka.index')
                    ->with('success','Dodaj słówka do nauki');
                }else{// są słówka po przejściu do Nauka, wcześniej pobrało słówko do nauki, tutaj losuje błędne podpowiedzi

                    $wylosowaneDane = DB::table($userTabela)
                    ->where('n2_1a', '!=', $n2_1->id)
                    ->select('id','slowo2','znaczenie2')
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();

                    $dane = collect([$n2_1])->merge($wylosowaneDane);
                    // pomieszanie kolejności
                    $dane = $dane->shuffle();

                    //pobranie ile jest punktów
                    $sumaPunktów = DB::table($userTabela)->sum('pkt_razem2');
                    //pobranie ile słówek pozostało do nauki
                    $ileSlowekZostalo = DB::table($userTabela)
                    ->where('n2_2a', '>', 0)
                    ->whereDate('n2_2c', '<=', Carbon::today())
                    ->count();


                    return view('nauka.show', compact('tytul','n2_1','dane','sumaPunktów','jakaStrona',"ileSlowekZostalo"));
                    // return redirect()->route('nauka.show',[1,$tytul,$n2_1,$dane,$zmiennaTestowa]);
                }
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Uaktalnia ile powtórek słówka w nauce
     */
    public function uaktualnij (Request $request){

        request()->validate([

            'coupdate' => 'required|string',
            'ilePowtorek' => 'required|integer'
        ]);
        $dane = $request->all();

        // Pobierz zalogowanego użytkownika
        $user = Auth::user();

        if($dane['coupdate'] == 'ile_powtorek'){
            DB::table('users')
            ->where('id',$user->id)->update([
                'ile_powtorek' => $dane['ilePowtorek'],
            ]);
        }

        return redirect()->route('nauka.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
