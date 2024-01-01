@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Tabele słówek</h2>
        </div>
        <div class="pull-right">
            {{-- <a class="btn btn-success" href="{{ route('slowka.create',['nrzestawu' => 0, 'robicdla' => 1]) }}"> Dodaj swoją tabelę słowek </a> --}}
        </div>
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
 @foreach ($slowka as $zestaw)
  <tr>
    <td>{{ $zestaw->nrzestawu }}</td>
    <td>{{ $zestaw->occurrences }}</td>
    <td>
        @php
            // $parametr = 'wartosc_parametru';
            // $url = url("/sciezka?parametr={$parametr}");
            // $user_id = Auth::user()->id;
        @endphp

        <a class="btn btn-info" href="{{ route('slowka.show',$zestaw->nrzestawu) }}">Pokaż</a>

        <form action="{{ route('slowka.create') }}" method="GET">
            @csrf
            <input type="hidden" name="nrzestawu" value={{$zestaw->nrzestawu}}>
            <button type="submit">Dodaj zestaw do nauki</button>
        </form>

        <a class="btn btn-success" href="{{ route('slowka.create', ['nrzestawu' => $zestaw->nrzestawu] ) }}">Dodaj zestaw do nauki</a>
        {{-- <a class="btn btn-primary" href="{{ route('slowka.edit',$word->id) }}">Edit</a> --}}
        {{-- {!! Form::open(['method' => 'DELETE','route' => ['slowka.destroy', $zestaw->nrzestawu],'style'=>'display:inline']) !!} --}}
            {{-- {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!} --}}
        {{-- {!! Form::close() !!} --}}
    </td>
  </tr>
 @endforeach
</table>



@endsection



