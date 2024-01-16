@extends('layouts.app')

@section('content')
@php

    // echo '<pre>';
    // print_r($word);
    // echo '</pre>';
    // dd($word)

@endphp

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edycja</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-info" href="{{ route('slowka.show', [$word->nrzestawu, 'page' => $strona]) }}">Pokaż</a>
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
@php
    echo '<pre>';
    print_r($word);
    echo '</pre>';

    echo '<pre>';
    print_r($strona);
    echo '</pre>';
    // echo '<br /><br />';
    // // echo $word->userKolumna;
    // echo '<br /><br />';
    // echo $word->$userKolumna;
    // echo '<br /><br />';

    // if (!empty($word->$userKolumna) && str_contains($word->$userKolumna, ";;ktora=c;;")) {
    //     echo '<br /><br />poszło tak<br /><br />';
    // }else{
    //     echo '<br /><br />nie poszło<br /><br />';
    // }
    // echo '<br />';
@endphp

    <form action="{{ route('slowka.update',$word->id, $iduser = Auth::user()->id) }}" method="POST">
    	@csrf
        @method('PUT')
        <input type="hidden" name="ipus" value="u{{ Auth::user()->id }}">
        <input type="hidden" name="coupdate" value="uzytkownika">
        <input type="hidden" name="word_nrzestawu" value="{{ $word->nrzestawu }}">
        <input type="hidden" name="word_id" value="{{ $word->id }}">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nr id:</strong>
                <p>&nbsp&nbsp {{ $word->id }}</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nr org. tab.:</strong>
                <p>&nbsp&nbsp {{ $word->nrzestawu }}</p>
            </div>
		</div>
        <div class="col-xs-12 col-sm-12 col-md-12">
		    <div class="form-group">
		        <strong>Dodano do tab.:</strong>
		        <input type="text" name="dodaj_tab" value="{{ $word->dodaj_tab}}" class="form-control" placeholder="Wpisz nr zestawu, tabeli">
		    </div>
		</div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Dodano do zest.:</strong>
                <input type="text" name="dodaj_slowo" value="{{ $word->dodaj_slowo}}" class="form-control" placeholder="Wpisz nr zestawu, tabeli">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Słowo:</strong>
                <input type="text" name="slowo" value="{{ $word->slowo }}" class="form-control" placeholder="Wpisz słowo">
            </div>
        </div>
		    <div class="col-xs-12 col-sm-12 col-md-12">
		        <div class="form-group">
		            <strong>Znaczenie:</strong>
                    <input type="text" name="znaczenie" value="{{ $word->znaczenie }}" class="form-control" placeholder="Wpisz znaczenie">

		        </div>
		    </div>
		<div class="col-xs-12 col-sm-12 col-md-12">
		    <div class="form-group">
		        <strong>Przykład:</strong>
		        <input type="text" name="przyklad" value="{{ $word->przyklad }}" class="form-control" placeholder="Wpisz przykład">
		    </div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 text-center">
		    <button type="submit" class="btn btn-primary">Zapisz</button>
		</div>


    </form>

@endsection
