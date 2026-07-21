@extends('layouts.dashboard')

@section('title', 'ویرایش کارت')
@section('page-title', 'ویرایش کارت')

@section('content')
<livewire:card-builder :cardId="$card->id" />
@endsection
