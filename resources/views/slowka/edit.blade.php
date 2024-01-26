@extends('layouts.app')

@section('content')
@php

    // echo '<pre>';
    // print_r($word);
    // echo '</pre>';
    // dd($word)
    // echo "to jest page: ".$page."<br />A to jest nrzestawu: ".$nrzestawu."<br />";

    // Odczytaj wartość 'page' z bieżącego URL-a
    // $currentPage = request()->query('page');
    // $currentPage = request()->input('page');
    // $currentPage = request()->get('page', 1);

    // Ustaw domyślną wartość, jeśli 'page' nie istnieje
    // $currentPage = $currentPage ?: 1;

    // echo "a to page po odczytaniu: $currentPage"

@endphp

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edycja</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-info" href="{{ route('slowka.show', [$nrzestawu, 'page' => $page]) }}">Pokaż</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Something went wrong.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- <form action="{{ route('slowka.update',$word->id) }}" method="PATCH"> --}}
    <form action="{{ action('App\Http\Controllers\SlowkaController@update',[$word->id] )}}" method="POST" role="form">
    	@csrf
        {{-- @method('PUT') --}}
        {{-- <input type="hidden" name="ipus" value="u{{ Auth::user()->id }}"> --}}
        <input type="hidden" name="coupdate" value="uzytkownika">
        <input type="hidden" name="page" value="{{ $page }}"">
        {{-- <input type="hidden" name="currentPage" value="{{ $currentPage }}"> --}}
        {{-- <input type="hidden" name="word_id" value="{{ $word->id }}"> --}}
        {{-- <input type="hidden" name="tab" value="{{ $word->tab }}"> --}}
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nr id:</strong>
                <p>&nbsp&nbsp {{ $word->id }}</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nr oryginalneego zestawu:</strong>
                <p>&nbsp&nbsp {{ $word->word_nrzestawu2 }}</p>
                <input type="hidden" name="word_nrzestawu2" value="{{ $word->word_nrzestawu2 }}">
            </div>
		</div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Słowo:</strong>
                <input type="text" name="slowo" value="{{ $word->slowo2 }}" class="form-control" placeholder="Wpisz słowo">
            </div>
        </div>
		    <div class="col-xs-12 col-sm-12 col-md-12">
		        <div class="form-group">
		            <strong>Znaczenie:</strong>
                    <input type="text" name="znaczenie" value="{{ $word->znaczenie2 }}" class="form-control" placeholder="Wpisz znaczenie">

		        </div>
		    </div>
		<div class="col-xs-12 col-sm-12 col-md-12">
		    <div class="form-group">
		        <strong>Przykład:</strong>
		        <input type="text" name="przyklad" value="{{ $word->przyklad2 }}" class="form-control" placeholder="Wpisz przykład">
		    </div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 text-center">
		    <button type="submit" class="btn btn-primary">Zapisz</button>
		</div>
    </form>

@endsection
