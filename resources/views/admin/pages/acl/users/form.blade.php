@php
    $title = ($item->id) ? 'Редактирование пользователя' : 'Добавление пользователя';
@endphp

@extends('admin::layouts.app')

@section('title', $title)

@section('styles')
    <!-- SELECT2 -->
    <link href="{!! asset('admin/css/plugins/select2/select2.min.css') !!}" rel="stylesheet">
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-12">
            <h2>
                {{ $title }}
            </h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ url('/back/') }}">Главная</a>
                </li>
                <li>
                    <span>ACL</span>
                </li>
                <li>
                    <a href="{{ route('back.acl.users.index') }}">Пользователи</a>
                </li>
                <li class="active">
                    <strong>
                        {{ $title }}
                    </strong>
                </li>
            </ol>
        </div>
    </div>

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
                                    'class' => 'col-sm-2 control-label',
                                ],
                                'field' => [
                                    'class' => 'form-control',
                                ],
                            ]) !!}

                            {!! Form::string('email', $item->email, [
                                'label' => [
                                    'title' => 'Email',
                                    'class' => 'col-sm-2 control-label',
                                ],
                                'field' => [
                                    'class' => 'form-control',
                                ],
                            ]) !!}

                            {!! Form::passwords('password', '', [
                                'label' => [
                                    'title' => 'Пароль',
                                    'class' => 'col-sm-2 control-label',
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

                            {!! Form::dropdown('roles_id[]', $item->roles->pluck('id')->toArray(), [
                                'label' => [
                                    'title' => 'Роли',
                                    'class' => 'col-sm-2 control-label',
                                ],
                                'field' => [
                                    'class' => 'select2 form-control',
                                    'data-placeholder' => 'Выберите роли',
                                    'style' => 'width: 100%',
                                    'multiple' => 'multiple'
                                ],
                                'options' => \App\Role::select('id', 'display_name')->pluck('display_name', 'id')->toArray(),
                            ]) !!}

                            {!! Form::dropdown('permissions_id[]', $item->permissions->pluck('id')->toArray(), [
                                'label' => [
                                    'title' => 'Права',
                                    'class' => 'col-sm-2 control-label',
                                ],
                                'field' => [
                                    'class' => 'select2 form-control',
                                    'data-placeholder' => 'Выберите права',
                                    'style' => 'width: 100%',
                                    'multiple' => 'multiple'
                                ],
                                'options' => \App\Permission::select('id', 'display_name')->pluck('display_name', 'id')->toArray(),
                            ]) !!}

                            {!! Form::buttons('', '', ['back' => 'back.acl.users.index']) !!}

                        {!! Form::close()!!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- SELECT2 -->
    <script src="{!! asset('admin/js/plugins/select2/select2.full.min.js') !!}"></script>
@endsection
