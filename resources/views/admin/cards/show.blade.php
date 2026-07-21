@extends('layouts.admin')

@section('title', 'جزئیات کارت')
@section('page-title', 'جزئیات کارت')

@section('content')
<livewire:admin.card-detail :id="$card->id" />
@endsection
