@extends('layouts.app')
@section('title', 'Edit Recurring')
@section('page-title', 'Edit Recurring')
@section('content')
    @include('recurring.create', ['recurring' => $recurring, 'accounts' => $accounts, 'categories' => $categories])
@endsection
