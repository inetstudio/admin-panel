@php
    $title = ($item->id) ? 'Редактирование роли' : 'Добавление роли';
@endphp

@extends('admin::layouts.app')

@section('title', $title)

@section('content')

    @push('breadcrumbs')
        <li>
            <span>ACL</span>
        </li>
        <li>
            <a href="{{ route('back.acl.roles.index') }}">Роли</a>
        </li>
    @endpush

    <div class="wrapper wrapper-content">

        {!! Form::info() !!}

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">

                        {!! Form::open(['url' => (!$item->id) ? route('back.acl.roles.store') : route('back.acl.roles.update', [$item->id]), 'id' => 'mainForm', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) !!}

                            @if ($item->id)
                                {{ method_field('PUT') }}
                            @endif

                            {!! Form::hidden('role_id', (!$item->id) ? "" : $item->id) !!}

                            <p>Общая информация</p>

                            {!! Form::string('display_name', $item->display_name, [
                                'label' => [
                                    'title' => 'Название',
                                ],
                            ]) !!}

                            {!! Form::string('name', $item->name, [
                                'label' => [
                                    'title' => 'Алиас',
                                ],
                            ]) !!}

                            {!! Form::wysiwyg('description', $item->description, [
                                'label' => [
                                    'title' => 'Описание',
                                    'class' => 'col-sm-2 control-label',
                                ],
                                'field' => [
                                    'class' => 'tinymce',
                                ],
                            ]) !!}

                            <p>Доступ</p>

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

                            {!! Form::buttons('', '', ['back' => 'back.acl.roles.index']) !!}

                        {!! Form::close()!!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
