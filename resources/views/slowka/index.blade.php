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



@php
    // // var_dump($wyniki)
    // echo '<pre>';
    // print_r($slowka2);
    // echo '</pre>';

    // echo '<pre>';
    // print_r($slowka2);
    // echo '</pre>';

@endphp

<table class="table table-bordered">
    <tr>
        <th>Nr zestwau</th>
        <th>Ilość słówek</th>
    </tr>
    @foreach ($slowka2 as $zestaw)
        <tr>
            <td>{{ $zestaw->word_nrzestawu2 }}</td>
            <td>{{ $zestaw->occurrences }}</td>
            <td>
                @php
                    // $parametr = 'wartosc_parametru';
                    // $url = url("/sciezka?parametr={$parametr}");
                    // $user_id = Auth::user()->id;
                @endphp

                <a class="btn btn-info" href="{{ route('slowka.show',$zestaw->word_nrzestawu2) }}">Pokaż</a>

                {{-- <form action="{{ route('slowka.show',$zestaw->word_nrzestawu2) }}" class="btn " method="GET">
                    @csrf
                    {{-- <input type="hidden" name="nrzestawu" value={{$zestaw->word_nrzestawu2}}> --}}
                    {{-- <input type="hidden" name="tab" value= {{$zestaw->tab}}> --}}
                    {{-- <button type="submit" class="btn btn-info" >Pokaż</button> --}}
                {{-- </form> --}}

                {{-- @if($zestaw->dodaj_dotab)
                    <form action="{{ route('slowka.create') }}" class="btn " method="GET">
                        @csrf
                        <input type="hidden" name="nrzestawu" value={{$zestaw->nrzestawu}}>
                        <input type="hidden" name="dodaj" value= 1>
                        <input type="hidden" name="tab" value= {{$zestaw->tab}}>
                        <button type="submit" class="btn btn-danger" >Usuń zestaw</button>
                    </form>
                @else
                    <form action="{{ route('slowka.create') }}" class="btn " method="GET">
                        @csrf
                        <input type="hidden" name="nrzestawu" value={{$zestaw->nrzestawu}}>
                        <input type="hidden" name="dodaj" value= 2>
                        <input type="hidden" name="tab" value={{$zestaw->tab}}>
                        <button type="submit" class="btn btn-success" >Dodaj zestaw</button>
                    </form>
                @endif --}}

                {{-- <a href="{{ url('twoja/sciezka') . '?' . csrf_field() }}">Link</a> --}}

                {{-- @if ($zestaw->dodana == 0)
                    <a class="btn btn-success" href="{{ route('slowka.create', ['nrzestawu' => $zestaw->nrzestawu,'dodaj'=>'1'] ) }}">Dodaj zestaw do nauki</a>
                @elseif ($zestaw->dodana == 1)
                    <a class="btn btn-warning" href=""> Dodany zestaw do nauki </a>
                    <a class="btn btn-danger" href="{{ route('slowka.usunzestaw', ['nrzestawu' => $zestaw->nrzestawu,'dodaj'=>'2'] ) }}"> Usuń </a>
                @endif --}}


                {{-- <a class="btn btn-primary" href="{{ route('slowka.edit',$word->id) }}">Edit</a> --}}
                {{-- {!! Form::open(['method' => 'DELETE','route' => ['slowka.destroy', $zestaw->nrzestawu],'style'=>'display:inline']) !!} --}}
                    {{-- {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!} --}}
                {{-- {!! Form::close() !!} --}}
            </td>
        </tr>
    @endforeach
</table>

<div class="pull-left">
    <h2>Zestawy żytkownika</h2>
</div>

{{-- <form action="{{ route('processJson') }}" method="post" enctype="multipart/form-data">
    @csrf
    <label for="jsonFiles">Wybierz pliki JSON do przetworzenia:</label>
    <input type="file" name="jsonFiles[]" id="jsonFiles" accept=".json" multiple>
    <button class="" type="submit">Przetwórz</button>
</form> --}}

@endsection



