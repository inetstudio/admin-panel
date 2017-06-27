@extends('admin::layouts.app')

@php
    $title = 'Права';
@endphp

@section('title', $title)

@section('content')

    @include('admin::partials.breadcrumb_index', ['title' => $title])

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <a href="{{ route('back.acl.permissions.create') }}" class="btn btn-primary btn-lg">Добавить</a>
                    </div>
                    <div class="ibox-content">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th>Алиас</th>
                                <th>Описание</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($items))
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->display_name }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('back.acl.permissions.edit', [$item->id]) }}" class="btn btn-default m-r"><i class="fa fa-pencil"></i></a>
                                                <a href="#" class="btn btn-danger delete" data-url="{{ route('back.acl.permissions.destroy', [$item->id]) }}"><i class="fa fa-times"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
