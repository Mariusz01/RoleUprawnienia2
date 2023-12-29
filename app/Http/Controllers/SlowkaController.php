<?php

namespace App\Http\Controllers;

use App\Models\Slowka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Word;
use Illuminate\Support\Arr;
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
        // Wyliczanie i zliczanie wystąpień dla danej kolumny
        $slowka = DB::table('words')
        ->selectRaw('nrzestawu, COUNT(*) as occurrences') //liczy ile wystąpień dla każdego zestawu
        ->groupBy('nrzestawu') //wyniki są grupowane według zestawu
        ->orderBy('nrzestawu','ASC') //kolejność
        ->get();


        return view('slowka.index',compact('slowka'));

        // // Wyświetlanie wyników
        // foreach ($results as $result) {
        // echo "Wartość: {$result->column_name}, Ilość wystąpień: {$result->occurrences} <br>";
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($nrzestawu, $robicdla)
    {
        //uaktualnienie danych w tablicy profilers dla danego użytkownia (kolumny)
        Slowka::where('word_nrzestawu', $nrzestawu)->update(['u'.$robicdla => ';;b;;']);

        //pobranie słowek dla danego zestawu czyli tablicy z words
        $tab_words = DB::table('words')
            ->where('nrzestawu', $nrzestawu) // Dodanie warunku wyboru zestawu
            ->select('id','nrzestawu','slowo','znaczenie','przyklad')
            // ->orderBy('slowo','ASC')//kolejność
            ->get();

        $tab_slowka = Slowka::where('word_nrzestawu', $nrzestawu)->select('id','word_id','word_nrzestawu','u'.$robicdla)->paginate(25);

        // $i = ($tab_slowka->first()->id ?? 1) - 1 * 15;
        // $tab_slowka = Slowka::paginate(25);

        return view('slowka.create', compact('tab_words', 'tab_slowka', 'nrzestawu'));

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
        //pobranie słowek dla danego zestawu czyli tablicy z words
        $tab_words = DB::table('words')
            ->where('nrzestawu', $nrzestawu) // Dodanie warunku wyboru zestawu
            ->select('id','nrzestawu','slowo','znaczenie','przyklad')
            // ->orderBy('slowo','ASC')//kolejność
            ->get();

        $tab_slowka = DB::table('profilers')
            ->where('word_nrzestawu', $nrzestawu)
            ->select('id','word_id','word_nrzestawu','u'.$userId)
            ->paginate(15);
            
        // $tab_slowka = Slowka::where('word_nrzestawu', $nrzestawu)->select('id','word_id','word_nrzestawu','u'.$userId)->paginate(25);

        return view('slowka.show',compact('tab_words', 'tab_slowka', 'nrzestawu'))
            ->with('i', ($request->input('page', 1) - 1) * 15);
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

        $slowo1 = Slowka::where('word_id', $id)->select('id','word_id','word_nrzestawu','u'.$userId)->get();
        // $tablica = explode(";;", $slowo1->kolumna_z_tekstem_slowa); // Zmień 'kolumna_z_tekstem_slowa' na rzeczywistą nazwę kolumny
        $tablica = explode(";;", $slowo1);
        if($tablica[1]==='c'){
            $word = array(
                'id' => $slowo1[0]->id,
                'nrzestawu' => $slowo1[0]->word_nrzestawu,
                'slowo' => $tablica[2],
                'znaczenie' => $tablica[3],
                'przyklad' => $tablica[4]
            );
        }else{
            $word = Word::find($id);
        }

        return view('slowka.edit',compact('word'));

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
        //dla lepszego zabezpieczenia
        $coupdate = 'u'.$dane['coupdate'];
        if($coupdate === 'utabela'){
            // to nie powinno zadziałać bo usera jest w Word kontrolerze
            $word = Word::find($id);
            // $word->update($request->all());
            $id = $word->id;
            //muszę dopisać uaktualnienie
            // $data = Arr::except($data, ['element_do_usuniecia']);
            Word::where('id'.$id)->update([
                'nrzestawu' => $dane['nrzestawu'],
                'slowo' => $dane['slowo'],
                'znaczenie'
            ]);
            ////////////////////////////
            // Znajdź rekord o podanym ID
            // $slowo = Slowka::findOrFail($id);
        }elseif($coupdate === 'uuzytkownika'){
            $user = $dane['ipus'];

            Slowka::where('id',$id)->update([
                'word_nrzestawu' => $dane['nrzestawu'],
                $user => ';;c;;'.$dane['slowo'].';;'.$dane['znaczenie'].';;'.$dane['przyklad'].';;',
            ]);
        }
/////////////////////////////////////////////////
        return redirect()->route('slowka.edit', ['slowka' => $id])
                        ->with('success','Słowo zostało zaktualizowane');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function showslowko($id){
        $word = Word::find($id);
        return view('slowko.edit',compact('word'));
    }
}
