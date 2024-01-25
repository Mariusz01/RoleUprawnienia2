@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Show User</h2>
        </div>
        <div class="pull-right">
            {{-- <a href="{{ URL::previous() }}" class="btn btn-primary">Wróć</a> --}}
            <a class="btn btn-primary" href="{{ route('admin.users.index', ['page' => $page]) }}">Wróć</a>
            {{-- <a class="btn btn-primary" href="{{ route('/users.index') }}"> Back </a> --}}
        </div>
    </div>
</div>
@php
    // echo '<pre>';
    // print_r($user2);
    // echo '</pre>';
@endphp

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {{ $user->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {{ $user->email }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Weryfikacja email:</strong>
            {{ $user->email_verified_at	 }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Utworzony:</strong>
            {{ $user->created_at }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Aktualizowany:</strong>
            {{ $user->updated_at }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Zatwierdzony:</strong>
            {{ $user->approved_at }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Roles:</strong>
            @if(!empty($user->getRoleNames()))
            <br />
                @foreach($user->getRoleNames() as $v)
                    <label class="badge-success">{{ $v }}</label>
                @endforeach
            @endif
        </div>

       <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
    </div>
</div>
@endsection
