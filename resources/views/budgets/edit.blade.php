@extends('layouts.app')
@section('title', 'Edit Anggaran')
@section('page-title', 'Edit Anggaran')
@section('content')
    @include('budgets.create', ['budget' => $budget, 'categories' => $categories])
@endsection
