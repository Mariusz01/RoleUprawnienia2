@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Wybrany nr zestawu: {{ $nrzestawu }}</h2>
        </div>
        <div class="pull-right">
            {{-- <a class="btn btn-success" href="{{ route('slowka.create',['nrzestawu' => $wybrany_zestaw, 'robicdla' => 2]) }}"> Dodaj nowe słówko </a> --}}
        </div>
    </div>
</div>
<div class="pull-right">
    {{-- <a href="{{ URL::previous() }}" class="btn btn-primary">Wróć</a> --}}
    <a href="{{ route('slowka.index') }}" class="btn btn-primary">Wróć</a>
    {{-- <a class="dropdown-item" href="{{ route('slowka.index') }}">Tabele słowek</a> --}}
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

@php
    // foreach ($tab_slowka as $to){
        // echo '<pre>';
        // print_r($tab_slowka2);
        // echo '</pre>';

    // }
@endphp
{{-- {!! $tab_slowka2->render() !!} --}}
{!! $tab_slowka2->links() !!}

<table class="table table-bordered">
    <tr>
        <th>Nr</th>
        <th>Nr<br />zestawu</th>
        <th>Dodana do<br />zestawu</th>
        <th>Słówko</th>
        <th>Znaczenie</th>
        <th>Przykład</th>
        {{-- <th>edyt?</th> --}}
        {{-- <th>usuń</th> --}}
        <th width="280px">Action</th>
    </tr>


    @foreach ($tab_slowka2 as $to)
        <tr>
            <td>{{ $to->id }}</td>
            <td>{{ $nrzestawu }}</td>
            <td>
                {{ $to->dodaj_dotab2 }}
            </td>
            <td>
                {{ $to->slowo2}}
            </td>
            <td>
                {{ $to->znaczenie2}}
            </td>
            <td>
                 {{ $to->przyklad2}}
            </td>
            <td>

                @if ($to->edytuj_slowo2)
                    {!! Form::open(['method' => 'GET', 'route' => ['slowka.edit', $to->id], 'style' => 'display:inline']) !!}
                        {!! Form::submit('Edit', ['class' => 'btn btn-success']) !!}
                        {!! Form::hidden('pageshow', 'show1') !!}
                        {{-- Dodaj ukryte pole dla numeru strony --}}
                        {{-- {!! Form::hidden('currentPage', request()->route()->parameter('page')) !!} --}}
                        {!! Form::hidden('currentPage', (empty(request('page'))? 1 : request('page'))) !!}
                    {!! Form::close() !!}
                @else
                    {!! Form::open(['method' => 'GET', 'route' => ['slowka.edit', $to->id], 'style' => 'display:inline']) !!}
                        {!! Form::submit('Edit', ['class' => 'btn btn-info']) !!}
                        {!! Form::hidden('pageshow', 'show1') !!}
                        {{-- Dodaj ukryte pole dla numeru strony --}}
                        {{-- {!! Form::hidden('currentPage', request()->route()->parameter('page')) !!} --}}
                        {!! Form::hidden('currentPage', (empty(request('page'))? 1 : request('page'))) !!}
                    {!! Form::close() !!}
                @endif

                @if ($to->edytuj_slowo2)
                    <form action="{{ action('App\Http\Controllers\SlowkaController@update',[$to->id] )}}" method="POST" role="form" style='display:inline'>
                        @csrf
                        <input type="hidden" name="coupdate" value="resetuj1">
                        <input type="hidden" name="page" value="{{ (empty(request('page'))? 1 : request('page')) }}"">
                        <input type="hidden" name="word_nrzestawu2" value="{{ $to->word_nrzestawu2 }}"">
                        <button type="submit" class="btn btn-primary">Resetuj</button>
                    </form>
                @else
                    <form action="{{ action('App\Http\Controllers\SlowkaController@update',[$to->id] )}}" method="POST" role="form" style='display:inline'>
                        @csrf
                        <input type="hidden" name="coupdate" value="resetuj1">
                        <input type="hidden" name="page" value="{{ (empty(request('page'))? 1 : request('page')) }}"">
                        <input type="hidden" name="word_nrzestawu2" value="{{ $to->word_nrzestawu2 }}"">
                        <button type="submit" class="btn btn-success">Resetuj</button>

                    </form>
                @endif

                @if (!$to->dodaj_slowo2)
                    <form action="{{ action('App\Http\Controllers\SlowkaController@update',[$to->id] )}}" method="POST" role="form" style='display:inline'>
                        @csrf
                        <input type="hidden" name="coupdate" value="dodaj1">
                        <input type="hidden" name="page" value="{{ (empty(request('page'))? 1 : request('page')) }}"">
                        <input type="hidden" name="word_nrzestawu2" value="{{ $to->word_nrzestawu2 }}"">
                        <button type="submit" class="btn btn-primary">Dodaj</button>
                    </form>
                @else
                    <form action="{{ action('App\Http\Controllers\SlowkaController@update',[$to->id] )}}" method="POST" role="form" style='display:inline'>
                        @csrf
                        <input type="hidden" name="coupdate" value="usun1">
                        <input type="hidden" name="page" value="{{ (empty(request('page'))? 1 : request('page')) }}"">
                        <input type="hidden" name="word_nrzestawu2" value="{{ $to->word_nrzestawu2 }}"">
                        <button type="submit" class="btn btn-danger">Usuń</button>

                    </form>
                @endif


                {{-- <div class="btn-group"> --}}
                    {{-- <button type="button" class="btn btn-outline-secondary"> --}}
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8m-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5"/>
                          </svg> --}}
                      {{-- Dodaj --}}
                    {{-- </button> --}}
                {{-- </div> --}}

                {{-- {!! Form::hidden('pageShow', $tab_slowka2->currentPage()) !!} --}}

                {{-- @if ($to->edytuj_slowo2) --}}
                    {{-- <a class="btn btn-success" href="{{ route('slowka.edit',[$to->id, 'page' => $tab_slowka2->currentPage()]) }}">Edytuj</a> --}}
                {{-- @else --}}
                    {{-- <a class="btn btn-info" href="{{ route('slowka.edit',[$to->id, 'page' => $tab_slowka2->currentPage()]) }}">Edytuj</a> --}}
                {{-- @endif --}}

                {{-- @if ($to->edytuj_slowo2) --}}
                    {{-- {!! Form::open(['method' => 'DELETE','route' => ['slowka.destroy', $to->id],'style'=>'display:inline']) !!} --}}
                        {{-- {!! Form::hidden('destroy', 'show1') !!} --}}
                        {{-- {!! Form::hidden('nrzestawu', $nrzestawu) !!} --}}
                        {{-- {!! Form::hidden('currentPage', request('page')) !!} --}}
                        {{-- {!! Form::submit('Resetuj', ['class' => 'btn btn-danger']) !!} --}}
                    {{-- {!! Form::close() !!} --}}
                {{-- @else --}}
                    {{-- {!! Form::open(['method' => 'DELETE','route' => ['slowka.destroy', $to->id],'style'=>'display:inline']) !!} --}}
                        {{-- {!! Form::hidden('destroy', 'show2') !!} --}}
                        {{-- {!! Form::hidden('nrzestawu', $nrzestawu) !!} --}}
                        {{-- {!! Form::hidden('currentPage', request('page')) !!} --}}
                        {{-- {!! Form::submit('Resetuj', ['class' => 'btn btn-success']) !!} --}}
                    {{-- {!! Form::close() !!} --}}
                {{-- @endif --}}

            </td>
        </tr>
    @endforeach
</table>

{!! $tab_slowka2->links() !!}
{{-- {!! $nrzestawu ->render() !!} --}}
@endsection
