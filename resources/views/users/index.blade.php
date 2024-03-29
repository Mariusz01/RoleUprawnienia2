@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Users Management</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User </a>
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

{!! $data->render() !!}
<table class="table table-bordered">
 <tr>
   <th>Nr id</th>
   <th>Name</th>
   <th>Email</th>
   <th>Roles</th>
   <th>Zarejestrowany</th>
   <th>Zatwierdź</th>
   <th>Action</th>
 </tr>
 @foreach ($data as $key => $user)
  <tr>
    <td>{{ $user->id }}</td>
    <td>{{ $user->name }}</td>
    <td>
        <div class="btn-group">
        @if ($user->email_verified_at)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckCheckedDisabled" checked disabled>
                <label class="form-check-label" for="flexCheckCheckedDisabled"></label>
            </div>
        @endif
        {{ $user->email }}
        </div>
    </td>
    <td>
      @if(!empty($user->getRoleNames()))
        @foreach($user->getRoleNames() as $v)
           <label>{{ $v }}</label>
        @endforeach
      @endif
    </td>
    <td>{{ $user->created_at }}</td>
    <td>

        @if (!$user->approved_at)
            {{-- <a href="{{ route('admin.users.approve', $user->id) }}"
                class="btn btn-primary btn-sm">Zatwierdź</a> --}}

            <form action="{{ action('App\Http\Controllers\UserController@approve',[$user->id] )}}" method="POST" role="form" style='display:inline'>
                @csrf
                <input type="hidden" name="page" value="{{ (empty(request('page'))? 1 : request('page')) }}"">
                <button type="submit" class="btn btn-primary">Zatwierdź</button>
            </form>
        @else
            {{-- <a href="{{ route('admin.users.notapprove', $user->id) }}" --}}

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckCheckedDisabled" checked disabled>
                <label class="form-check-label" for="flexCheckCheckedDisabled">

                </label>
            </div>
        @endif
    </td>
    <td>
       {{-- <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a> --}}

       {!! Form::open(['method' => 'GET', 'route' => ['users.show', $user->id], 'style' => 'display:inline']) !!}
            {!! Form::submit('Show', ['class' => 'btn btn-info']) !!}
            {!! Form::hidden('currentPage', (empty(request('page'))? 1 : request('page'))) !!}
        {!! Form::close() !!}

       {{-- <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a> --}}

       {!! Form::open(['method' => 'GET', 'route' => ['users.edit', $user->id], 'style' => 'display:inline']) !!}
            {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
            {!! Form::hidden('currentPage', (empty(request('page'))? 1 : request('page'))) !!}
        {!! Form::close() !!}

        {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </td>
  </tr>
 @endforeach
</table>

{!! $data->render() !!}

@endsection
