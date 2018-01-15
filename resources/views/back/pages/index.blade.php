@extends('admin::back.layouts.app')

@php
    $title = 'Главная';
    $text = "тест {{ (true) ? 'hello' : 'bye' }}";
@endphp

@section('title', $title)

@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            {{ $this->getCompiler()->compileString($text) }}
        </div>
    </div>
@endsection
