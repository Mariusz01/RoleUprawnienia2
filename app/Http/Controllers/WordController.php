<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Word;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class WordController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:word-list|word-create|word-edit|word-delete', ['only' => ['index','show']]);
         $this->middleware('permission:word-create', ['only' => ['create','store']]);
         $this->middleware('permission:word-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:word-delete', ['only' => ['destroy']]);

         $this->middleware('permission:word-delete', ['only' => ['usun']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $data = Word::orderBy('nrzestawu','ASC')->orderBy('slowo','ASC')->paginate(15);
        // return view('words.index',compact('data'))
        //     ->with('i', ($request->input('page', 1) - 1) * 15);

        $words = DB::table('words')
        ->selectRaw('nrzestawu, COUNT(nrzestawu) as occurrences, 1 as tab') //liczy ile wystąpień dla każdego zestawu, dodanie tab=1
        ->groupBy('nrzestawu') //wyniki są grupowane według zestawu
        ->orderBy('nrzestawu','ASC') //kolejność
        ->paginate(15);


        return view('words.index',compact('words'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('words.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'nrzestawu' => 'required|numeric',
            'slowo' => 'required|string',
            'znaczenie' => 'required|string',
            'przyklad' => 'string',
        ]);

        Word::create($request->all());

        return redirect()->route('words.index')
                        ->with('success','Słowo zostało wpisane.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $nrzestawu)
    {
        request()->validate([
            'currentPage' => 'integer',
            'page' => 'integer',
            'coupdate' => 'text',
            'id' => 'integer',
        ]);
        $dane  = $request->all();
        // $id = $request['id'];
        // $coupdate = $request['coupdate'];
        // $coupdate = 'upShow1';
        // $id = 1;
        $word = DB::table('words')
        ->where('nrzestawu', $nrzestawu)
        ->select(
            'id',
            'nrzestawu',
            'slowo',
            'znaczenie',
            'przyklad',
            'edytuj_slowo',
            'usun_slowo',
            'tab',
        )
        ->paginate(15);

        return view('words.show',compact('word', 'nrzestawu', 'dane'))
        ->with('success','Poszło show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        request()->validate([
            'currentPage' => 'integer',
            'page' => 'integer',
        ]);
        $dane  = $request->all();
        $word = DB::table('words')
        ->where('id', $id)
            ->select(
                'id',
                'nrzestawu',
                'slowo',
                'znaczenie',
                'przyklad',
                'edytuj_slowo',
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
        $nrzestawu = $word->nrzestawu;

        return view('words.edit',compact('word','nrzestawu','page'));
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
            'coupdate' => 'string',
            'page' => 'numeric',
        ]);
        $dane = $request->all();
        $page = $dane['page'];
        $nrzestawu = $dane['nrzestawu'];

        if($dane['coupdate'] === 'edytuj1'){
            DB::table('words')->where('id', $id)->update([
                'slowo' => $dane['slowo'],
                'znaczenie' => $dane['znaczenie'],
                'przyklad' => $dane['przyklad'],
                'edytuj_slowo' => true,
                'updated_at' => now(),
            ]);
            return redirect()->route('words.edit', [$id,'page'=>$page]) //page musi być tak zapisane aby przekazało wartość
            ->with('success','Słowo zostało zaktualizowane');
        }if($dane['coupdate'] === 'upShow2'){
            DB::table('words')
            ->where('id', $id)->update([
                'usun_slowo' => true,
                'updated_at' => now(),
            ]);
            return redirect()->route('words.show', [$nrzestawu,'page'=>$page]) //page musi być tak zapisane aby przekazało wartość
            ->with('success','Słowo id: '.$id.' zostało oznaczone jako usunięte');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id){
        //
    }
    public function usun(Request $request, $id)
    {
        request()->validate([
            'nrzestawu' => 'required|numeric',
            'currentPage' => 'required|numeric',
        ]);
        $dane = $request->all();
        $nrzestawu = $dane['nrzestawu'];
        // $nrzestawu = $word->nrzestawu;
        $page = $dane['currentPage'];

        if($dane['coupdate'] === 'upShow1'){// DODAJ dodana w words i dodaj w tablicach użytkowników

            DB::table('words')
            ->where('id', $id)->update([
                'usun_slowo' => false,
                'updated_at' => now(),
            ]);

            //pobranie danych z words
            $word = Word::find($id)
            ->select(
                'id',
                'nrzestawu',
                'slowo',
                'znaczenie',
                'przyklad'
            )
            ->first();

            $userIds = User::pluck('id');//wyłuskanie wszystkich userów
            foreach ($userIds as $userId) {
                $tableName = 'usertab2_' . $userId;
                // Sprawdź, czy tabela istnieje, zanim spróbujesz usunąć wiersz
                if (Schema::hasTable($tableName)) {//sprawdzenie czy istnieje tablica
                    DB::table($tableName)->where('id', $id)->insert([
                        'id' => $id,
                        'word_id2' => $id,
                        'word_nrzestawu2' => $word->nrzestawu,
                        'slowo2' => $word->slowo,
                        'znaczenie2' => $word->znaczenie,
                        'przyklad2' => $word->przyklad,
                    ]);
                }
            }
            return redirect()->route('words.show', [$nrzestawu,'page'=>$page]) //page musi być tak zapisane aby przekazało wartość
            ->with('success','Słowo id: '.$id.' zostało dodane');

        }elseif($dane['coupdate'] === 'upShow2'){ // USUN zaznacza w words że usunięte, usuwa z tablicy userów

            // $result = DB::table('words')->where('id', $id)->first();
            Word::where('id', 1)->update(['usun_slowo' => 1]);
            DB::update('UPDATE words SET updated_at = ?, usun_slowo = ? WHERE id = ?', [now(), true, $id]);
            $userIds = User::pluck('id');//wyłuskanie wszystkich userów
            foreach ($userIds as $userId) {
                $tableName = 'usertab2_' . $userId;
                // Sprawdź, czy tabela istnieje, zanim spróbujesz usunąć wiersz
                if (Schema::hasTable($tableName)) {//sprawdzenie czy istnieje tablica
                    DB::table($tableName)->where('id', $id)->delete();//usunięcie wiersza
                }
            }

            return redirect()->route('words.show', [$nrzestawu,'page'=>$page]) //page musi być tak zapisane aby przekazało wartość
            ->with('success','Słowo id: '.$id.' zostało oznaczone jako usunięte ');
        }
    }

    public function aktualizuj(Request $request, $id){

        request()->validate([
            'nrzestawu' => 'required|numeric',
            'slowo' => 'required|string',
            'znaczenie' => 'required|string',
            'przyklad' => 'string',
            'coupdate' => 'string',
            'page' => 'numeric',
        ]);
        $dane = $request->all();
        $page = $dane['page'];
        $nrzestawu = $dane['nrzestawu'];

        DB::table('words')
            ->where('id', $id)->update([
                'usun_slowo' => true,
                'updated_at' => now(),
            ]);
            return redirect()->route('words.show', [$nrzestawu,'page'=>$page]) //page musi być tak zapisane aby przekazało wartość
            ->with('success','Słowo id: '.$id.' zostało oznaczone jako usunięte');
    }
}
