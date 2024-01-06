@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Wybrana tabela nr {{ $nrzestawu }}</h2>
        </div>
        <div class="pull-right">
            {{-- <a class="btn btn-success" href="{{ route('slowka.create',['nrzestawu' => $wybrany_zestaw, 'robicdla' => 2]) }}"> Dodaj nowe słówko </a> --}}
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif





{{-- {{ $tab_slowka[1] }} --}}
{{-- @foreach($tab_slowka[0] as $klucz => $wartosc)
        {{ $klucz }}: {{ $wartosc }} <br>
@endforeach --}}


    {{-- {!! $tab_slowka->links() !!} --}}
    {{-- {{ $tab_slowka[2]}} --}}
@php
    // foreach ($tab_slowka as $to){
        // echo '<pre>';
        // print_r($tab_slowka);
        // echo '</pre>';
    // }
    // Pobierz zalogowanego użytkownika
    // $user = Auth::user();
    // // Pobierz ID zalogowanego użytkownika
    // $userId = $user->id;
    // $userKolumna = 'u'.$userId;
    // echo 'numer zestawu = '.$nrzestawu.'<br />';
    // echo 'test = '.$userKolumna.'<br />';
    // dd($tab_slowka);
    // var_dump($tab_slowka);
@endphp


<table class="table table-bordered">
    <tr>
        <th>Nr</th>
        <th>Nr tabeli</th>
        <th>Słówko</th>
        <th>Znaczenie</th>
        <th>Przykład</th>
        {{-- <th>edyt?</th> --}}
        {{-- <th>usuń</th> --}}
        <th width="280px">Action</th>
    </tr>


    @foreach ($tab_slowka as $to)
        <tr>
            <td>{{ $to->id }}</td>
            <td>{{ $nrzestawu }}</td>
            <td>{{ $to->slowo }}</td>
            <td>{{ $to->znaczenie }}</td>
            <td>{{ $to->przyklad }}</td>
            {{-- <td>{{ $to->edytuj }}</td> --}}
            {{-- <td>{{ $to->dodac }}</td> --}}


            <td>
                {{-- <a class="btn btn-info" href="{{ route('slowka.show',$word->id) }}">Show</a> --}}
                @if (!empty($to->edytuj) && $to->edytuj == 'c')
                    <a class="btn btn-success" href="{{ route('slowka.edit',$to->id) }}">Edytuj</a>
                @else
                    <a class="btn btn-primary" href="{{ route('slowka.edit',$to->id) }}">Edytuj</a>
                @endif

                @if (empty($to->dodac) || $to->dodac == '+')
                    {!! Form::open(['method' => 'DELETE','route' => ['slowka.destroy', $to->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Usuń', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                @else
                {{-- musisz obsługę tego przycisku --}}
                    <a class="btn btn-success" href="{{ route('slowka.edit',[$to->id]) }}">Dodaj</a>
                @endif

            </td>

        </tr>
    @endforeach


</table>







{{-- {!! $nrzestawu ->render() !!} --}}
{{-- {!! $tab_slowka->links() !!} --}}

@endsection
