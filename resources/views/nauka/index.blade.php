@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Nauka słówek</h2>
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
    // echo "tabela: ".$userTabela."<br /><br />";
    // // var_dump($wyniki)
    // echo '<pre>';
    // print_r($doNauki);
    // echo '</pre>';

    // echo '<pre>';
    // print_r($n2_1);
    // echo '</pre>';
    // echo $n2_1.'<br />';
    // echo $n2_2.'<br />';
    // echo $n2_3.'<br />';
    // echo $n2_4.'<br />';
    // echo $n2_5.'<br />';
    // echo $n2_6.'<br />';
    // echo $n2_7.'<br />';
    // echo $n2_8.'<br />';

@endphp

<div>
    <p style="display:inline">Ilość powtórek dla słówka</p>
    {!! Form::open(['method' => 'POST', 'route' => ['nauka.uaktualnij'], 'style' => 'display:inline']) !!}
        @csrf
        {!! Form::number('ilePowtorek', $ilePowtorek, ['max' => 99, 'oninput' => 'this.value = Math.min(this.value, 99)', 'style' => 'width: 50px;']) !!}
        {!! Form::hidden('coupdate','ile_powtorek') !!}
        {{-- {!! Form::hidden('currentPage', (empty(request('page'))? 1 : request('page'))) !!} --}}
        {!! Form::submit('Zapisz', ['class' => 'btn btn-success']) !!}
    {!! Form::close() !!}
</div>

<p style="display:inline">Ilość punktów: {{ $sumaPunktów}} </p>

{{-- <a class="btn btn-info" href="{{ route('slowka.show',$zestaw->word_nrzestawu2) }}"> --}}
<a class="text-decoration-none text-dark" href="{{ route('nauka.show',['nauka' => 1]) }}">
    <div class="border border-success p-2 d-flex justify-content-center align-items-center mx-auto" style="--bs-border-opacity: .9; max-width: 100%;margin: 10px;">
        1. wybór angielskiego znaczenia - liczba słówek: {{$n2_1}}
    </div>
</a>
<a class="text-decoration-none text-dark" href="{{ route('nauka.show',2) }}">
    <div class="border border-success p-2 d-flex justify-content-center align-items-center mx-auto" style="--bs-border-opacity: .9; max-width: 100%;margin: 10px;">
        2. wybór polskiego znaczenia - liczba słówek: {{$n2_2}}
    </div>
</a>
<a class="text-decoration-none text-dark" href="{{ route('slowka.show',2) }}">
    <div class="border border-success p-2 d-flex justify-content-center align-items-center mx-auto" style="--bs-border-opacity: .9; max-width: 100%;margin: 10px;">
        3. {{$n2_3}} słówek - wpisanie angielskiego znaczenia
    </div>
</a>
<a class="text-decoration-none text-dark" href="{{ route('nauka.index') }}">
    <div class="border border-success p-2 d-flex justify-content-center align-items-center mx-auto" style="--bs-border-opacity: .9; max-width: 100%;margin: 10px;">
        4. {{$n2_4}} słówek - wpisanie polskiego znaczenia
    </div>
</a>
<a class="text-decoration-none text-dark" href="{{ route('nauka.index') }}">
    <div class="border border-success p-2 d-flex justify-content-center align-items-center mx-auto" style="--bs-border-opacity: .9; max-width: 100%;margin: 10px;">
        5. {{$n2_5}} słówek - wybór angielskiego znaczenia ze słuchu
    </div>
</a>
<a class="text-decoration-none text-dark" href="{{ route('nauka.index') }}">
    <div class="border border-success p-2 d-flex justify-content-center align-items-center mx-auto" style="--bs-border-opacity: .9; max-width: 100%;margin: 10px;">
        6. {{$n2_6}} słówek - wybór polskiego znaczenia ze słuchu
    </div>
</a>
<a class="text-decoration-none text-dark" href="{{ route('nauka.index') }}">
    <div class="border border-success p-2 d-flex justify-content-center align-items-center mx-auto" style="--bs-border-opacity: .9; max-width: 100%;margin: 10px;">
        7. {{$n2_7}} słówek - wpisanie angielskiego znaczenia ze słuchu
    </div>
</a>
<a class="text-decoration-none text-dark" href="{{ route('nauka.index') }}">
    <div class="border border-success p-2 d-flex justify-content-center align-items-center mx-auto" style="--bs-border-opacity: .9; max-width: 100%;margin: 10px;">
        8. {{$n2_8}} słówek - wpisanie polskiego znaczenia ze słuchu
    </div>
</a>





<div class="text-bg-primary p-3">Primary with contrasting color</div>
<div class="bg p-2" style="--bs-bg-opacity: .5;">Primary with contrasting color</div>

<div class="d-grid gap-2 col-10 mx-auto">
    <button class="bg p-2" style="--bs-bg-opacity: .5;" type="button">{{$n2_1}} słówek - wybór angielskiego znaczenia</button>
    <button class="bg p-2" style="--bs-bg-opacity: .5;" type="button">{{$n2_2}} słówek - wybór polskiego znaczenia</button>
    <button class="bg p-2" style="--bs-bg-opacity: .5;" type="button">{{$n2_3}} słówek - wpisanie angielskiego znaczenia</button>
    <button class="bg p-2" style="--bs-bg-opacity: .5;" type="button">{{$n2_4}} słówek - wpisanie polskiego znaczenia</button>
    <button class="bg p-2" style="--bs-bg-opacity: .5;" type="button">{{$n2_5}} słówek - wybór angielskiego znaczenia ze słuchu</button>
    <button class="bg p-2" style="--bs-bg-opacity: .5;" type="button">{{$n2_6}} słówek - wybór polskiego znaczenia ze słuchu</button>
    <button class="bg p-2" style="--bs-bg-opacity: .5;" type="button">{{$n2_7}} słówek - wpisanie angielskiego znaczenia ze słuchu</button>
    <button class="bg p-2" style="--bs-bg-opacity: .5;" type="button">{{$n2_8}} słówek - wpisanie polskiego znaczenia ze słuchu</button>
    <a class="bg p-2" style="--bs-bg-opacity: .5;" type="button" href="https://www.przykladowy-link.com">Przejdź do linka</a>
</div>

<br />
<a class="text-decoration-none text-dark" href="{{ route('nauka.index') }}">
    <div class="border border-success p-2 d-flex justify-content-center align-items-center mx-auto" style="--bs-border-opacity: .9; max-width: 80%;margin: 10px;">
        Na środku
    </div>
</a>
<a class="text-decoration-none text-dark" href="{{ route('nauka.index') }}">
    <div class="border border-success p-2 d-flex justify-content-center align-items-center mx-auto" style="--bs-border-opacity: .9; max-width: 80%;margin: 10px;">
        Na środku
    </div>
</a>
<br />


<br /><br />
<div class="mb-4">
    <label for="exampleFormControlInput1" class="form-label">Email address</label>
    <input type="email" class="form-control border-success" id="exampleFormControlInput1" placeholder="name@example.com">
  </div>

  <div class="h4 pb-2 mb-4 text-danger border-bottom border-danger">
    Dangerous heading
  </div>

  <div class="p-3 bg-info bg-opacity-10 border border-info border-start-10 rounded-end">
    Changing border color and width
  </div>





<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
    <div class="btn-group me-2" role="group" aria-label="First group">
      <button type="button" class="btn btn-primary">1</button>
      <button type="button" class="btn btn-primary">2</button>
      <button type="button" class="btn btn-primary">3</button>
      <button type="button" class="btn btn-primary">4</button>
    </div>
    <div class="btn-group me-2" role="group" aria-label="Second group">
      <button type="button" class="btn btn-secondary">5</button>
      <button type="button" class="btn btn-secondary">6</button>
      <button type="button" class="btn btn-secondary">7</button>
    </div>
    <div class="btn-group" role="group" aria-label="Third group">
      <button type="button" class="btn btn-info">8</button>
    </div>
  </div>


<div class="container text-center ">
    <div class="row justify-content-md-center">
      <div class="col col-lg-2">
        1 of 3
      </div>
      <div class="col-md-auto">
        Variable width content
      </div>
      <div class="col col-lg-2">
        3 of 3
      </div>
    </div>
    <div class="row">
      <div class="col">
        1 of 3
      </div>
      <div class="col-md-auto">
        Variable width content
      </div>
      <div class="col col-lg-2">
        3 of 3
      </div>
    </div>
  </div>

  <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
    <div class="btn-group me-2" role="group" aria-label="First group">
      <button type="button" class="btn btn-primary">1</button>
      <button type="button" class="btn btn-primary">2</button>
      <button type="button" class="btn btn-primary">3</button>
      <button type="button" class="btn btn-primary">4</button>
    </div>
    <div class="btn-group me-2" role="group" aria-label="Second group">
      <button type="button" class="btn btn-secondary">5</button>
      <button type="button" class="btn btn-secondary">6</button>
      <button type="button" class="btn btn-secondary">7</button>
    </div>
    <div class="btn-group" role="group" aria-label="Third group">
      <button type="button" class="btn btn-info">8</button>
    </div>
  </div>







<table class="table table-bordered">
    <tr>
        <th>Nr zestwau</th>
        <th>Ilość słówek</th>
    </tr>
    @foreach ($slowka2 as $zestaw)
        <tr>
            {{-- <td>{{ $zestaw->slowo2 }}</td>
            <td>{{ $zestaw->znaczenie2 }}</td> --}}
            <td>
                @php
                    // $parametr = 'wartosc_parametru';
                    // $url = url("/sciezka?parametr={$parametr}");
                    // $user_id = Auth::user()->id;
                @endphp

                {{-- <a class="btn btn-info" href="{{ route('slowka.show',$zestaw->word_nrzestawu2) }}">Pokaż</a> --}}

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



{{-- <form action="{{ route('processJson') }}" method="post" enctype="multipart/form-data">
    @csrf
    <label for="jsonFiles">Wybierz pliki JSON do przetworzenia:</label>
    <input type="file" name="jsonFiles[]" id="jsonFiles" accept=".json" multiple>
    <button class="" type="submit">Przetwórz</button>
</form> --}}

@endsection



