<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Word;
use Validator;

class WordController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:word-list|word-create|word-edit|word-delete', ['only' => ['index','show']]);
         $this->middleware('permission:word-create', ['only' => ['create','store']]);
         $this->middleware('permission:word-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:word-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Word::orderBy('nrzestawu','ASC')->orderBy('slowo','ASC')->paginate(15);
        return view('words.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 15);
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
    public function show($id)
    {
        $word = Word::find($id);
        return view('words.show',compact('word'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Word $word)
    {
        // $word = Word::find($id);
        return view('words.edit',compact('word'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Word $word)
    {
        request()->validate([
            'nrzestawu' => 'required|numeric',
            'slowo' => 'required|string',
            'znaczenie' => 'required|string',
            'przyklad' => 'string',
        ]);

        $word->update($request->all());

        return redirect()->route('words.index')
                        ->with('success','Słowo zostało zaktualizowane');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Word $word)
    {
        $word->delete();

        return redirect()->route('words.index')
                        ->with('success','Słówko zostało usunięte');
    }
}
