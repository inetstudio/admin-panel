@php
    $title = ($item->id) ? 'Редактирование пользователя' : 'Добавление пользователя';
@endphp

@extends('admin::layouts.app')

@section('title', $title)

@section('content')

    @push('breadcrumbs')
        <li>
            <span>ACL</span>
        </li>
        <li>
            <a href="{{ route('back.acl.users.index') }}">Пользователи</a>
        </li>
    @endpush

    <div class="wrapper wrapper-content">

        {!! Form::info() !!}

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">

                        {!! Form::open(['url' => (!$item->id) ? route('back.acl.users.store') : route('back.acl.users.update', [$item->id]), 'id' => 'mainForm', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) !!}

                            @if ($item->id)
                                {{ method_field('PUT') }}
                            @endif

                            {!! Form::hidden('user_id', (!$item->id) ? "" : $item->id) !!}

                            <p>Общая информация</p>

                            {!! Form::string('name', $item->name, [
                                'label' => [
                                    'title' => 'Имя',
                                ],
                            ]) !!}

                            {!! Form::string('email', $item->email, [
                                'label' => [
                                    'title' => 'Email',
                                ],
                            ]) !!}

                            {!! Form::passwords('password', '', [
                                'label' => [
                                    'title' => 'Пароль',
                                ],
                                'fields' => [
                                    [
                                        'class' => 'form-control m-b-xs',
                                        'placeholder' => 'Введите пароль',
                                    ],
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => 'Повторите пароль',
                                    ],
                                ],
                            ]) !!}

                            <p>Доступ</p>

                            {!! Form::dropdown('roles_id[]', $item->roles()->pluck('id')->toArray(), [
                                'label' => [
                                    'title' => 'Роли',
                                ],
                                'field' => [
                                    'class' => 'select2 form-control',
                                    'data-placeholder' => 'Выберите роли',
                                    'style' => 'width: 100%',
                                    'multiple' => 'multiple',
                                    'data-source' => route('back.acl.roles.getSuggestions'),
                                ],
                                'options' => (old('roles_id')) ? \App\Role::whereIn('id', old('roles_id'))->pluck('display_name', 'id')->toArray() : $item->roles()->pluck('display_name', 'id')->toArray(),
                            ]) !!}

                            {!! Form::dropdown('permissions_id[]', $item->permissions()->pluck('id')->toArray(), [
                                'label' => [
                                    'title' => 'Права',
                                ],
                                'field' => [
                                    'class' => 'select2 form-control',
                                    'data-placeholder' => 'Выберите права',
                                    'style' => 'width: 100%',
                                    'multiple' => 'multiple',
                                    'data-source' => route('back.acl.permissions.getSuggestions'),
                                ],
                                'options' => (old('permissions_id')) ? \App\Permission::whereIn('id', old('permissions_id'))->pluck('display_name', 'id')->toArray() : $item->permissions()->pluck('display_name', 'id')->toArray(),
                            ]) !!}

                            {!! Form::buttons('', '', ['back' => 'back.acl.users.index']) !!}

                        {!! Form::close()!!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
