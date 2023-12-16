@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Tabela słówek</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('words.create') }}"> Dodaj nowe słówko </a>
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
   <th>Nr id</th>
   <th>Nr zestau</th>
   <th>Słówko</th>
   <th>Znaczenie</th>
   <th>Przykład</th>
   <th width="280px">Action</th>
 </tr>
 @foreach ($data as $key => $word)
  <tr>
    <td>{{ $word->id }}</td>
    <td>{{ $word->nrzestawu }}</td>
    <td>{{ $word->slowo }}</td>
    <td>{{ $word->znaczenie }}</td>
    {{-- <td>{{ $word->przyklad }}</td> --}}
    <td>
      @if(!empty( $word->przyklad  ))
        {{ $word->przyklad }}
      @endif
    </td>
    <td>
       <a class="btn btn-info" href="{{ route('words.show',$word->id) }}">Show</a>
       <a class="btn btn-primary" href="{{ route('words.edit',$word->id) }}">Edit</a>
        {!! Form::open(['method' => 'DELETE','route' => ['words.destroy', $word->id],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </td>
  </tr>
 @endforeach
</table>

{!! $data->render() !!}

@endsection
