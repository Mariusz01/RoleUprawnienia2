<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class JsonController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:json-list|json-create|json-edit|json-delete', ['only' => ['index','show']]);
         $this->middleware('permission:json-create', ['only' => ['create','store']]);
         $this->middleware('permission:json-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:json-delete', ['only' => ['destroy']]);

         //nie są uwzglęnione funkcje te poniżej, wpisać do
    }


    public function showUploadForm()
    {
        return view('words.index');
    }

    public function processJson(Request $request)
    {
        $request->validate([
            'jsonFiles.*' => 'required|mimes:json|max:2048',
        ]);

        // Pobierz zalogowanego użytkownika
        $user = Auth::user();
        // Pobierz ID zalogowanego użytkownika
        $userId = $user->id;
        $userTabela = 'usertab_'.$userId;

        $jsonFiles = $request->file('jsonFiles');

        foreach ($jsonFiles as $file) {
            $jsonData = file_get_contents($file->getPathname());
            $dataArray = json_decode($jsonData, true); // Dekodowanie JSON do tablicy

            foreach ($dataArray as $data) {
                // Zakładając, że masz model Eloquent o nazwie 'TwojModel'

                $existingRecord = DB::table($userTabela)->where('id', $data['id'])->first();

                if (!$existingRecord) {
                    // Możesz teraz wstawić nowy rekord
                    // $userTabela::create(['id' => $data->id, 'other_column' => 'value']);
                    DB::table($userTabela)->insert($data);
                } else {
                    // Identyfikator już istnieje, obsłuż błąd
                    // znajduje pierwsze wolne id w tabeli
                    // $id = DB::select("SELECT MIN(id+1) as next_id FROM $userTabela WHERE id+1 NOT IN (SELECT id FROM $userTabela)")[0];
                    $id = DB::select("SELECT MIN(id+1) as next_id FROM $userTabela WHERE id+1 NOT IN (SELECT id FROM $userTabela)")[0]->next_id ?? 1;
                    DB::table($userTabela)
                        ->insert(['word_id' => $id] + $data);
                }
            }
        }
        return redirect()->route('words.index')->with('success', 'Pliki JSON zostały przetworzone pomyślnie.');
    }

    public function downloadJson()
    {
        // Pobierz dane z bazy danych (zmień 'nazwa_tabeli' na nazwę rzeczywistej tabeli)
        $dataFromDatabase = DB::table('words')->get();

        // Konwersja danych do formatu JSON
        $jsonData = json_encode($dataFromDatabase, JSON_PRETTY_PRINT);

        // Utwórz odpowiedź HTTP z plikiem JSON do pobrania
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="dane.json"',
        ];

        return Response::make($jsonData, 200, $headers);
    }
}
