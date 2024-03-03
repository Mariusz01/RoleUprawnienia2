@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Nuka - {{ $tytul }}</h2>
        </div>
        <div class="pull-right">
            {{-- <a class="btn btn-success" href="{{ route('slowka.create',['nrzestawu' => $wybrany_zestaw, 'robicdla' => 2]) }}"> Dodaj nowe słówko </a> --}}
        </div>
    </div>
</div>
<div class="pull-right">
    {{-- <a href="{{ URL::previous() }}" class="btn btn-primary">Wróć</a> --}}
    <a href="{{ route('nauka.index') }}" class="btn btn-primary">Wróć</a>
    {{-- <a class="dropdown-item" href="{{ route('slowka.index') }}">Tabele słowek</a> --}}
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

@php
    // foreach ($tab_slowka as $to){
        // echo "dane z n2_2:<br />";
        // echo '<pre>';
        // print_r($n2_1);
        // echo '</pre>';

        // echo "<br /><br />Dane z dane:<br />";
        // echo '<pre>';
        // print_r($dane);
        // echo '</pre>';
        // echo "<br /><br /><br />";

        // echo "wyświetlenie dane";
        // echo '<pre>';
        // print_r($dane);
        // echo '</pre>';


        // echo '<pre>';
        // print_r($n2_1);
        // echo '</pre>';

        // echo  $zmiennaTestowa;

        // echo "<br /><br /><br />";

        // foreach ($dane as $rekord) {
        //     echo "ID: {$rekord->id}, Slowo2: {$rekord->slowo2}, Znaczenie2: {$rekord->znaczenie2}<br>";
        // }
        // echo $dane->get(0)->slowo2;

        // echo "która strona: ".$jakaStrona;
    // }
@endphp
{{-- {!! $tab_slowka2->render() !!} --}}
{{-- {!! $tab_slowka2->links() !!} --}}
<div class="container text-center">
    <div class="row">
        <div class="col">

        </div>
        <div class="col">

        </div>
        <div class="col">
            Ilość punktów: {{ $sumaPunktów }}
        </div>
        <div class="col">

        </div>
        <div class="col">

        </div>
        <div class="col">

        </div>
    </div>
</div>

@if($jakaStrona == 1)

    @if (isset($dalej) && $dalej === 'stop')
        @php
            // echo '<pre>';
            //     print_r($dane);
            // echo '</pre>';
            // echo "czy OK: ".$czyOK;
        @endphp
        <table class="table table-borderless ">
            <tr>
                <td class='border border-success p-2 d-flex justify-content-center align-items-center mx-auto alert text-bg-info '>
                    {{ $n2_1->slowo2 }}
                </td>
                <td width="280px" class='p-0  align-items-center mx-auto alert '>
                    {!! Form::open(['method' => 'POST', 'route' => ['nauka.show', 1], 'style' => 'display:inline']) !!}
                        {!! Form::hidden('dalej', 'niezaznaczono') !!}
                        {!! Form::submit('Dalej', ['class' => 'btn btn-success']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
            @foreach ($dane as $to )
                <tr>
                    @if ($czyOK == 1 && $n2_1->znaczenie2 === $to['znaczenie2'])
                        <td class='border border-success p-3 d-flex justify-content-center align-items-center mx-auto alert text-bg-success' >
                    @else
                        @if($n2_1->znaczenie2 === $to['znaczenie2'])
                                <td class='border border-success p-3 d-flex justify-content-center align-items-center mx-auto alert text-bg-success' >
                        @elseif ($n2_1->zaznaczono2 === $to['znaczenie2'])
                            <td class='border border-success p-3 d-flex justify-content-center align-items-center mx-auto alert text-bg-warning' >
                        @else
                            <td class='border border-success p-3 d-flex justify-content-center align-items-center mx-auto alert alert-succes' >
                        @endif
                    @endif
                            {{ $to['znaczenie2'] }}</td>
                    </td>
                    <td  class='p-3  align-items-center mx-auto alert' >
                        @if ($czyOK == 1 && $n2_1->znaczenie2 === $to['znaczenie2'])
                            OK
                        @else
                            @if($n2_1->znaczenie2 === $to['znaczenie2'])
                                <- Ma być
                            @elseif ($n2_1->zaznaczono2 === $to['znaczenie2'])
                                <- Wybrałeś
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <table class="table table-borderless ">
            <tr>
                <td class='border border-success p-2 d-flex justify-content-center align-items-center mx-auto alert text-bg-info '>
                    {{ $n2_1->slowo2 }}
                </td>
                <td width="280px">
                    Wybierz znaczenie
                </td>
            </tr>
            @foreach ($dane as $to )
            <tr>
                <td class='border border-success p-3 d-flex justify-content-center align-items-center mx-auto alert alert-info' >
                    {{ $to->znaczenie2 }}</td>
                <td>
                    {!! Form::open(['method' => 'POST', 'route' => ['nauka.show', 1], 'style' => 'display:inline']) !!}
                        {!! Form::hidden('slowo0', $n2_1->slowo2) !!}
                        {!! Form::hidden('znaczenie0', $to->znaczenie2) !!}
                        {!! Form::hidden('dalej', 'zaznaczono') !!}
                        {{-- {!! Form::hidden('nauka', '1') !!} --}}
                        {!! Form::hidden('znaczenie1', $dane->get(0)->znaczenie2) !!}
                        {!! Form::hidden('znaczenie2', $dane->get(1)->znaczenie2) !!}
                        {!! Form::hidden('znaczenie3', $dane->get(2)->znaczenie2) !!}
                        {!! Form::hidden('znaczenie4', $dane->get(3)->znaczenie2) !!}
                        {!! Form::submit('Wybierz', ['class' => 'btn btn-success']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
            @endforeach
        </table>
    @endif

@elseif($jakaStrona == 2)

    @if (isset($dalej) && $dalej === 'stop')
        @php
            // echo '<pre>';
            //     print_r($dane);
            // echo '</pre>';
            // echo "czy OK: ".$czyOK;
        @endphp
        <table class="table table-borderless ">
            <tr>
                <td class='border border-success p-2 d-flex justify-content-center align-items-center mx-auto alert text-bg-info '>
                    {{ $n2_1->znaczenie2 }}
                </td>
                <td width="280px" class='p-0  align-items-center mx-auto alert '>
                    {!! Form::open(['method' => 'POST', 'route' => ['nauka.show', 2], 'style' => 'display:inline']) !!}
                        {!! Form::hidden('dalej', 'niezaznaczono') !!}
                        {!! Form::submit('Dalej', ['class' => 'btn btn-success']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
            @foreach ($dane as $to )
                <tr>
                    @if ($czyOK == 1 && $n2_1->slowo2 === $to['slowo2'])
                        <td class='border border-success p-3 d-flex justify-content-center align-items-center mx-auto alert text-bg-success' >
                    @else
                        @if($n2_1->slowo2 === $to['slowo2'])
                                <td class='border border-success p-3 d-flex justify-content-center align-items-center mx-auto alert text-bg-success' >
                        @elseif ($n2_1->zaznaczono2 === $to['slowo2'])
                            <td class='border border-success p-3 d-flex justify-content-center align-items-center mx-auto alert text-bg-warning' >
                        @else
                            <td class='border border-success p-3 d-flex justify-content-center align-items-center mx-auto alert alert-succes' >
                        @endif
                    @endif
                            {{ $to['slowo2'] }}</td>
                    </td>
                    <td  class='p-3  align-items-center mx-auto alert' >
                        @if ($czyOK == 1 && $n2_1->slowo2 === $to['slowo2'])
                            OK
                        @else
                            @if($n2_1->slowo2 === $to['slowo2'])
                                <- Ma być
                            @elseif ($n2_1->zaznaczono2 === $to['slowo2'])
                                <- Wybrałeś
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <table class="table table-borderless ">
            <tr>
                <td class='border border-success p-2 d-flex justify-content-center align-items-center mx-auto alert text-bg-info '>
                    {{ $n2_1->znaczenie2 }}
                </td>
                <td width="280px">
                    Wybierz słowo
                </td>
            </tr>
            @foreach ($dane as $to )
            <tr>
                <td class='border border-success p-3 d-flex justify-content-center align-items-center mx-auto alert alert-info' >
                    {{ $to->slowo2 }}</td>
                <td>
                    {!! Form::open(['method' => 'POST', 'route' => ['nauka.show', 2], 'style' => 'display:inline']) !!}
                        {!! Form::hidden('slowo0', $to->slowo2) !!}
                        {!! Form::hidden('znaczenie0', $n2_1->znaczenie2) !!}
                        {!! Form::hidden('dalej', 'zaznaczono') !!}
                        {{-- {!! Form::hidden('nauka', '2') !!} --}}
                        {!! Form::hidden('slowo1', $dane->get(0)->slowo2) !!}
                        {!! Form::hidden('slowo2', $dane->get(1)->slowo2) !!}
                        {!! Form::hidden('slowo3', $dane->get(2)->slowo2) !!}
                        {!! Form::hidden('slowo4', $dane->get(3)->slowo2) !!}
                        {!! Form::submit('Wybierz', ['class' => 'btn btn-success']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
            @endforeach
        </table>
    @endif

@endif

@endsection
