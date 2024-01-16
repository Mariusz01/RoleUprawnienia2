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
<div class="pull-right">
    <a href="{{ URL::previous() }}" class="btn btn-primary">Wróć</a>
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
{{-- {!! $tab_slowka->render() !!} --}}
{!! $tab_slowka->links() !!}

<table class="table table-bordered">
    <tr>
        <th>Nr</th>
        <th>Nr tab.</th>
        <th>Dodana<br />do tab.</th>
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
            <td>
                {{ $to->dodaj_tab }}
            </td>
            <td>
                @if (empty($to->slowo2))
                    {{ $to->slowo }}
                @else
                    {{ $to->slowo2}}
                @endif
            </td>
            <td>
                @if (empty($to->znaczenie2))
                    {{ $to->znaczenie }}
                @else
                    {{ $to->znaczenie2}}
                @endif
            </td>
            <td>
                @if (empty($to->przyklad2))
                    {{ $to->przyklad }}
                @else
                    {{ $to->przyklad2}}
                @endif
            </td>
            {{-- <td>{{ $to->edytuj }}</td> --}}
            {{-- <td>{{ $to->dodac }}</td> --}}


            <td>
                {{-- <a class="btn btn-info" href="{{ route('slowka.show',$word->id) }}">Show</a> --}}
                {{-- @if (!empty($to->edytuj) && $to->edytuj == 'c') --}}
                @if($to->edytuj_slowo)

                    {!! Form::open(['method' => 'GET','route' => ['slowka.edit', $to->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Edit', ['class' => 'btn btn-success']) !!}
                        {!! Form::hidden('currentPage', request()->route('page')) !!}
                    {!! Form::close() !!}

                @else

                    {!! Form::open(['method' => 'GET','route' => ['slowka.edit', $to->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
                        {!! Form::hidden('pageShow', $tab_slowka->currentPage()) !!}
                    {!! Form::close() !!}

                @endif

                    {{-- {!! Form::open(['method' => 'GET','route' => ['slowka.edit', $to->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Edytuj', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!} --}}
                {{-- @else --}}
                    {{-- <a class="btn btn-primary" href="{{ route('slowka.edit','2') }}">Edytuj</a>
                    <a class="btn btn-primary" href="{{ route('users.edit',$to->id) }}">Edit</a> --}}
                {{-- @endif --}}

                @if ($to->edytuj_slowo)
                    {!! Form::open(['method' => 'DELETE','route' => ['slowka.destroy', $to->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Resetuj', ['class' => 'btn btn-danger']) !!}
                        {!! Form::hidden('destroy', 'show1') !!}
                        {!! Form::hidden('nrzestawu', $nrzestawu) !!}
                    {!! Form::close() !!}
                @else
                    {!! Form::open(['method' => 'DELETE','route' => ['slowka.destroy', $to->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Resetuj', ['class' => 'btn btn-success']) !!}
                        {!! Form::hidden('destroy', 'show2') !!}
                        {!! Form::hidden('nrzestawu', $nrzestawu) !!}
                    {!! Form::close() !!}
                @endif

            </td>

        </tr>
    @endforeach


</table>

{!! $tab_slowka->links() !!}





{{-- {!! $nrzestawu ->render() !!} --}}
{{-- {!! $tab_slowka->links() !!} --}}

@endsection
