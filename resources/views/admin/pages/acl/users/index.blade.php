@extends('admin::layouts.app')

@php
    $title = 'Пользователи';
@endphp

@section('title', $title)

@section('content')

    @include('admin::partials.breadcrumb_index', ['title' => $title])

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <a href="{{ route('back.acl.users.create') }}" class="btn btn-primary btn-lg">Добавить</a>
                    </div>
                    <div class="ibox-content">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Роли</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($items))
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>
                                            @foreach($item->roles->pluck('display_name')->toArray() as $role)
                                                <p>{{ $role }}</p>
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('back.acl.users.edit', [$item->id]) }}" class="btn btn-default m-r"><i class="fa fa-pencil"></i></a>
                                                <a href="#" class="btn btn-danger delete" data-url="{{ route('back.acl.users.destroy', [$item->id]) }}"><i class="fa fa-times"></i></a>
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
