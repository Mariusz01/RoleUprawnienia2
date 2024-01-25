@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edycja użytkownika</h2>
        </div>
        <div class="pull-right">
            {{-- <a href="{{ URL::previous() }}" class="btn btn-primary">Wróć</a> --}}
            <a class="btn btn-primary" href="{{ route('admin.users.index', ['page' => $page]) }}">Wróć</a>
        </div>
    </div>
</div>


@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> Something went wrong.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif


{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
@csrf
<input type="hidden" name="page" value="{{ $page }}"">
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            @if(!empty($user->email_verified_at))
                {!! Form::checkbox('email_verified_at', 1, true, ['class' => 'form-check-input']) !!}
            @else
                {!! Form::checkbox('email_verified_at', 1, false, ['class' => 'form-check-input']) !!}
            @endif
                <strong>Zweryfikowany mail</strong>
                @if(!empty($user->email_verified_at)){
                    {{ $user->email_verified_at }}
                }
                @endif
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            @if(!empty($user->approved_at))
                {!! Form::checkbox('approved_at', 1, true, ['class' => 'form-check-input']) !!}
            @else
                {!! Form::checkbox('approved_at', 1, false, ['class' => 'form-check-input']) !!}
            @endif
                <strong>Zatwierdzony</strong>
                @if(!empty($user->approved_at)){
                    {{ $user->approved_at }}
                }
                @endif
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Password:</strong>
            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Confirm Password:</strong>
            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Role:</strong>
            {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control','multiple')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Zapisz</button>
    </div>
</div>
{!! Form::close() !!}


@endsection
