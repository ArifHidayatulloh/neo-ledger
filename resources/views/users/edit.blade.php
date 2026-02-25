@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('content')
    @include('users.create', ['user' => $user, 'roles' => $roles])
@endsection
