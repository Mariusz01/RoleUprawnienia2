@extends('layouts.app')

@php
    // echo "nr zestawu: ".$nrzestawu."<br />page: ";
    // echo '<pre>';
    // print_r($word);
    // echo '</pre>';
@endphp

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
    <a href="{{ route('words.index') }}" class="btn btn-primary">Wróć</a>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

{!! $word->links() !!}

<table class="table table-bordered">
    <tr>
        <th>Nr</th>
        <th>Nr<br />zestawu</th>
        <th>Słówko</th>
        <th>Znaczenie</th>
        <th>Przykład</th>
        {{-- <th>edyt?</th> --}}
        {{-- <th>usuń</th> --}}
        <th width="280px">Action</th>
    </tr>

    @foreach ($word as $to)
        <tr>
            <td>{{ $to->id }}</td>
            <td>{{ $to->nrzestawu }}</td>
            <td>
                {{ $to->slowo}}
            </td>
            <td>
            {{ $to->znaczenie}}
            </td>
            <td>
                {{ $to->przyklad}}
            </td>
            <td>
                @if ($to->edytuj_slowo)
                    {!! Form::open(['method' => 'GET', 'route' => ['words.edit', $to->id], 'style' => 'display:inline']) !!}
                        {{-- {!! Form::hidden('pageshow', 'show1') !!} --}}
                        {!! Form::hidden('nrzestawu', $nrzestawu) !!}
                        {!! Form::hidden('currentPage', (empty(request('page'))? 1 : request('page'))) !!}
                        {!! Form::submit('Edit', ['class' => 'btn btn-success']) !!}
                    {!! Form::close() !!}
                @else
                    {!! Form::open(['method' => 'GET', 'route' => ['words.edit', $to->id], 'style' => 'display:inline']) !!}
                        {{-- {!! Form::hidden('pageshow', 'show1') !!} --}}
                        {!! Form::hidden('nrzestawu', $nrzestawu) !!}
                        {!! Form::hidden('currentPage', (empty(request('page'))? 1 : request('page'))) !!}
                        {!! Form::submit('Edit', ['class' => 'btn btn-info']) !!}
                    {!! Form::close() !!}
                @endif

                @if ($to->usun_slowo > 0)
                    {!! Form::open(['method' => 'POST', 'route' => ['words.destroy', $to->id], 'style' => 'display:inline']) !!}
                        @csrf
                        {{-- @method('PUT') --}}
                        {!! Form::hidden('nrzestawu', $nrzestawu) !!}
                        {!! Form::hidden('coupdate','upShow1') !!}
                        {!! Form::hidden('id',$to->id) !!}
                        {!! Form::hidden('currentPage', (empty(request('page'))? 1 : request('page'))) !!}
                        {!! Form::submit('Dodaj', ['class' => 'btn btn-success']) !!}
                    {!! Form::close() !!}
                @else
                    {!! Form::open(['method' => 'POST', 'route' => ['words.destroy', $to->id], 'style' => 'display:inline']) !!}
                        @csrf
                        {{-- @method('PUT') --}}
                        {!! Form::hidden('nrzestawu', $nrzestawu) !!}
                        {!! Form::hidden('coupdate','upShow2') !!}
                        {!! Form::hidden('id',$to->id) !!}
                        {!! Form::hidden('currentPage', (empty(request('page'))? 1 : request('page'))) !!}
                        {!! Form::submit('Usuń', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                @endif

            </td>
        </tr>
    @endforeach
</table>

{!! $word->links() !!}
@endsection
