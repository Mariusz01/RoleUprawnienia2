@extends('layouts.app')

@php
    // echo "nr zestawu: ".$nrzestawu."<br />page: ".$page;
@endphp

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edycja słowa</h2>
            </div>
            <div class="pull-right">
                {{-- <a href="{{ URL::previous() }}" class="btn btn-primary">Wróć</a> --}}
                <a class="btn btn-info" href="{{ route('words.show', [$nrzestawu, 'page' => $page]) }}">Pokaż</a>
            </div>
        </div>
    </div>


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
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <form action="{{ route('words.update',$word->id) }}" method="POST">
    	@csrf
        @method('PUT')
        <input type="hidden" name="page" value="{{ $page }}">
        <input type="hidden" name="coupdate" value="edytuj1">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nr id:</strong>
                <p>{{ $word->id }}</p>
            </div>
        </div>
         <div class="row">
		    <div class="col-xs-12 col-sm-12 col-md-12">
		        <div class="form-group">
		            <strong>Nr zestawu:</strong>
		            <input type="text" name="nrzestawu" value="{{ $word->nrzestawu }}" class="form-control" placeholder="Wpisz nr zestawu, tabeli">
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
		</div>
    </form>

@endsection
