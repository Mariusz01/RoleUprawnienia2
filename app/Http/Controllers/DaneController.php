<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Word;

class DaneController extends Controller
{
    public function pobierzDaneZPliku()
    {
        $sciezkaDoPliku = base_path('storage/app/lista.txt'); // Ustaw odpowiednią ścieżkę do pliku

        // Odczytaj dane z pliku
        $daneZPliku = file($sciezkaDoPliku, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Przetwórz dane
        $przetworzoneDane = [];
        foreach ($daneZPliku as $linia) {
            $kolumny = explode('|', $linia);
            // Usunięcie spacji z początku i końca każdej wartości w tablicy
            $kolumny = array_map('trim', $kolumny);
            //przetworzy dane:
            // $przetworzoneDane[] = [
            //     'slowko2' => $kolumny[0],
            //     'znaczenie2' => $kolumny[1],
            // ];

            //zapisać do bazy
            Word::create([
                'slowo' => $kolumny[0],
                'znaczenie' => $kolumny[1],
                'nrzestawu' => 4,
            ]);

        }

        // Zwróć przetworzone dane
        // return response()->json($przetworzoneDane);

        return response()->json(['message' => 'Dane zapisane do bazy']);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
