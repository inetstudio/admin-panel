@php
    $title = ($item->id) ? 'Редактирование права' : 'Добавление права';
@endphp

@extends('admin::layouts.app')

@section('title', $title)

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
                    <a href="{{ route('back.acl.permissions.index') }}">Права</a>
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

                        {!! Form::open(['url' => (!$item->id) ? route('back.acl.permissions.store') : route('back.acl.permissions.update', [$item->id]), 'id' => 'mainForm', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) !!}

                        @if ($item->id)
                            {{ method_field('PUT') }}
                        @endif

                        {!! Form::hidden('permission_id', (!$item->id) ? "" : $item->id) !!}

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

                        {!! Form::buttons('', '', ['back' => 'back.acl.permissions.index']) !!}

                        {!! Form::close()!!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- TINYMCE -->
    <script src="{!! asset('admin/js/plugins/tinymce/tinymce.min.js') !!}"></script>
@endsection
