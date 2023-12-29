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


    {!! $tab_slowka->links() !!}

<table class="table table-bordered">
    <tr>
        <th>Nr</th>
        <th>Nr tabeli</th>
        <th>Słówko</th>
        <th>Znaczenie</th>
        <th>Przykład</th>
        <th width="280px">Action</th>
    </tr>


    @for ($i=0;$i<count($tab_slowka);$i++)
        <tr>
            <td>{{ $tab_slowka[$i]->id }}</td>
            <td>{{ $tab_slowka[$i]->word_nrzestawu }}</td>

            @php
                $user = Auth::user();
                $userId = 'u'.$user->id;
                $ztab_slowka = $tab_slowka[$i]->$userId;
                $tablica = explode(";;", $ztab_slowka);
            @endphp

                {{-- {{ $tablica[1] }} --}}


            @if($tablica[1]==='c')
                <td>{{ $tablica[2] }}</td>
                <td>{{ $tablica[3] }}</td>
                <td>
                @if(!empty( $tablica[4] ))
                    {{ $tablica[4] }}
                @endif
                @else
                <td>{{ $tab_words[$i]->slowo }}</td>
                <td>{{ $tab_words[$i]->znaczenie }}</td>
                <td>
                @if(!empty( $tab_words[$i]->przyklad  ))
                    {{ $tab_words[$i]->przyklad }}
                @endif
                </td>
            @endif



            <td>
                {{-- <a class="btn btn-info" href="{{ route('slowka.show',$word->id) }}">Show</a> --}}
                <a class="btn btn-primary" href="{{ route('slowka.edit',$tab_slowka[$i]->id) }}">Edit</a>
                    {!! Form::open(['method' => 'DELETE','route' => ['slowka.destroy', $tab_slowka[$i]->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>











        </tr>
    @endfor


</table>







{{-- {!! $nrzestawu ->render() !!} --}}
{!! $tab_slowka->links() !!}

@endsection
