@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')
@section('content')
    @include('categories.create', ['category' => $category])
@endsection
