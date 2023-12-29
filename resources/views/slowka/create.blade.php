@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Wybrana tabela nr {{ $nrzestawu }}</h2>
        </div>
        <div class="pull-right">
            {{-- <a class="btn btn-success" href="{{ route('slowka.create',['nrzestawu' => $wybrany_zestaw, 'robic' => 2]) }}"> Dodaj nowe słówko </a> --}}
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

{!! $tab_slowka->links() !!}

<table class="table table-bordered">
 <tr>
   <th>Nr id</th>
   <th>Nr tabeli</th>
   <th>Słówko</th>
   <th>Znaczenie</th>
   <th>Przykład</th>
   <th width="280px">Action</th>
 </tr>

 {{-- Zmienna pomocnicza do numeracji --}}
{{-- @php
$numeracja = ($wybrany_zestaw->currentPage() - 1) * $wybrany_zestaw->perPage();
@endphp --}}

    @for ($i=0;$i<count($tab_slowka);$i++)
        <tr>
            <td>{{ $tab_slowka[$i]->id }}</td>
            <td>{{ $tab_slowka[$i]->word_nrzestawu }}</td>

            @php
                $ztab_slowka = $tab_slowka[$i];
                $tablica = explode(";;", $ztab_slowka);
            @endphp

            @if($tablica[0]==='c')
                <td>{{ $tablica[1] }}</td>
                <td>{{ $tablica[2] }}</td>
                <td>
                @if(!empty( $tablica[3]  ))
                    {{ $tablica[3] }}
                @endif
            @else
                <td>{{ $tab_words[$i]->slowo }}</td>
                <td>{{ $tab_words[$i]->znaczenie }}</td>
                <td>
                @if(!empty( $tab_words[$i]->przyklad  ))
                    {{ $tab_words[$i]->przyklad }}
                @endif

            @endif
            </td>
            <td>
            {{-- <a class="btn btn-info" href="{{ route('slowka.show',$word->id) }}">Show</a> --}}
            <a class="btn btn-primary" href="{{ route('slowka.edit',$tab_slowka[$i]) }}">Edit</a>
                {!! Form::open(['method' => 'DELETE','route' => ['slowka.destroy', $tab_slowka[$i]],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            </td>
        </tr>
    @endfor
</table>

{{-- {!! $tab_slowka->render() !!} --}}
{!! $tab_slowka->links() !!}

@endsection
