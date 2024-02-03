@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Tabela słówek nr 1</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('words.create') }}"> Dodaj nowe słówko </a>
        </div>

        <form action="{{ route('processJson') }}" method="post" enctype="multipart/form-data">
            @csrf
            <label for="jsonFiles">Wybierz pliki JSON do przetworzenia:</label>
            <input type="file" name="jsonFiles[]" id="jsonFiles" accept=".json" multiple>
            <button class="" type="submit">Przetwórz</button>
        </form>

        <a class="btn btn-info" href="{{ route('downloadJson') }}" download>Pobierz tabelę</a>

    </div>
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif


<table class="table table-bordered">
    <tr>
        <th>Nr zestwau</th>
        <th>Ilość słówek</th>
    </tr>
    @foreach ($words as $zestaw)
    <tr>
        <td>{{ $zestaw->nrzestawu }}</td>
        <td>{{ $zestaw->occurrences }}</td>
        <td>
            <a class="btn btn-info" href="{{ route('words.show',$zestaw->nrzestawu) }}">Pokaż</a>
        </td>
    </tr>
 @endforeach
</table>

{!! $words->links() !!}

@endsection
