@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Słówka</h2>
        </div>
        <div class="pull-right">
            <a href="{{ URL::previous() }}" class="btn btn-primary">Wróć</a>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Id:</strong><br />
            {{ $word->id }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Nr tabeli:</strong><br />
            {{ $word->nrzestawu }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Słowo:</strong><br />
            {{ $word->slowo }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Znaczenie:</strong><br />
            {{ $word->znaczenie }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">

        <strong>Przykład:</strong>
        @if(!empty($word->przyklad))
        <br />

            {{ $word->przyklad }}

        @endif

    </div>
</div>
@endsection
