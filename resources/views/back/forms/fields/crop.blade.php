@php
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);

    $errorFields = [$name.'.filename'];
    if (isset($attributes['crops'])) {
        $errorFields[] = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name.'[crop]');
        foreach ($attributes['crops'] as $crop) {
            $errorFields[] = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name.'[crop]'.'['.$crop['name'].']');
        }
    }
@endphp

<div class="image_upload">
    <div class="form-group @if (count(array_intersect($errors->getBag('default')->keys(), $errorFields)) > 0){!! "has-error" !!}@endif">
        @if (isset($attributes['label']['title']))
            {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
        @endif
        <div class="col-sm-10">
            <div class="col-md-6">
                <div class="ibox">
                    <div class="progress progress-bar-default pace-inactive" style="display: none;">
                        <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar">
                            <span></span>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="sk-spinner sk-spinner-double-bounce">
                            <div class="sk-double-bounce1"></div>
                            <div class="sk-double-bounce2"></div>
                        </div>
                        <div class="preview">
                            <img src="{{ (old($transformName.'.filepath')) ? old($transformName.'.filepath') : $attributes['image']['filepath'] }}" @if (! $attributes['image']['filepath'] && ! old($transformName.'.filepath'))data-src="holder.js/100px200?auto=yes&font=FontAwesome&text=&#xf1c5;"@endif class="m-b-md img-responsive placeholder">
                        </div>
                    </div>
                </div>
                @foreach ($errorFields as $errField)
                    @foreach ($errors->get($errField) as $message)
                        <span class="help-block m-b-xs">{{ $message }}</span>
                    @endforeach
                @endforeach
            </div>
            <div class="col-md-6">
                <div class="btn-group">
                    <a href="#" class="btn btn-success upload-btn" data-target="{{ route('back.upload') }}" data-field="{{ $name }}">
                        <i class="fa fa-upload m-r-xs" style="margin-right: 10px;"></i>Загрузить изображение

                        {!! Form::hidden('', (old($transformName.'.temppath')) ? old($transformName.'.temppath') : '', [
                            'name' => $name.'[temppath]',
                            'class' => 'image_temppath',
                        ]) !!}

                        {!! Form::hidden('', (old($transformName.'.tempname')) ? old($transformName.'.tempname') : '', [
                            'name' => $name.'[tempname]',
                            'class' => 'image_tempname',
                        ]) !!}

                        {!! Form::hidden('', (old($transformName.'.filepath')) ? old($transformName.'.filepath') : (isset($attributes['image']['filepath']) ? $attributes['image']['filepath'] : ''), [
                            'name' => $name.'[filepath]',
                            'class' => 'image_filepath',
                        ]) !!}

                        {!! Form::hidden('', (old($transformName.'.filename')) ? old($transformName.'.filename') : (isset($attributes['image']['filename']) ? $attributes['image']['filename'] : ''), [
                            'name' => $name.'[filename]',
                            'class' => 'image_filename',
                        ]) !!}
                    </a><br/>

                    <div class="crop_buttons m-t-lg" style="@if (! isset($value) and ! old($transformName.'.filepath')) display:none @endif">
                        @if (isset($attributes['crops']))
                            @foreach ($attributes['crops'] as $crop)
                                <a href="#" style="display: block;" class="btn m-b-xs btn-w-m {{ (($crop['value'] == '' and ! old($transformName.'.crop.'.$crop['name'])) or $errors->has($transformName.'.crop.'.$crop['name'])) ? 'btn-default' : 'btn-primary' }} start-cropper" data-ratio="{{ $crop['ratio'] }}" data-crop-button="" data-crop-settings="{{ json_encode($crop['size']) }}"><i class="fa fa-crop"></i> {{ $crop['title'] }}</a>

                                {!! Form::hidden('', (old($transformName.'.crop.'.$crop['name'])) ? old($transformName.'.crop.'.$crop['name']) : $crop['value'], [
                                    'name' => $name.'[crop]'.'['.$crop['name'].']',
                                    'class' => 'crop-data',
                                ]) !!}
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($attributes['additional']))
        <div class="additional_fields" style="@if (! isset($value) and ! old($name.'.temppath')) display:none @endif">
            @foreach ($attributes['additional'] as $field)
                {!! Form::string($name.'['.$field["name"].']', $field['value'], [
                    'label' => [
                        'title' => $field['title'],
                    ],
                    'hr' => [
                        'show' => false,
                    ],
                ]) !!}
            @endforeach
        </div>
    @endif

    <div class="hr-line-dashed"></div>
</div>

@pushonce('styles:cropper')
    <!-- CROPPER -->
    <link href="{!! asset('admin/css/plugins/cropper/cropper.min.css') !!}" rel="stylesheet">
    <style>
        .img-container, .img-preview {
            overflow: hidden;
            text-align: center;
            width: 100%;
        }
    </style>
@endpushonce

@pushonce('scripts:cropper')
    <!-- CROPPER -->
    <script src="{!! asset('admin/js/plugins/cropper/cropper.min.js') !!}"></script>
@endpushonce

@pushonce('scripts:plupload')
    <!-- PLUPLOAD -->
    <script src="{!! asset('admin/js/plugins/plupload/plupload.full.min.js') !!}"></script>
@endpushonce

@pushonce('modals:crop')
    <div id="crop_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title"></h4>
                    <small class="font-bold">Выберите область изображения</small>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="label label-warning description"></small>
                            <p class="m-t-lg">Размер выбранной области: <span class="label crop-size"></span></p>

                            <div class="m-b-xs">
                                <img src="" class="m-b-md img-responsive center-block" id="crop_image">
                            </div>

                            <div class="btn-group m-b-xs" style="margin: 10px 10px 0 0;">
                                <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" title="Переместить" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить">
                                      <span class="fa fa-arrows"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="Выбрать область" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Выбрать область">
                                      <span class="fa fa-crop"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group m-b-xs" style="margin: 10px 10px 0 0;">
                                <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Увеличить" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Увеличить">
                                      <span class="fa fa-search-plus"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Уменьшить" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Уменьшить">
                                      <span class="fa fa-search-minus"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group m-b-xs" style="margin: 10px 10px 0 0;">
                                <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Переместить влево" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить влево">
                                      <span class="fa fa-arrow-left"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Переместить вправо" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вправо">
                                      <span class="fa fa-arrow-right"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Переместить вверх" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вверх">
                                      <span class="fa fa-arrow-up"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Переместить вниз" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вниз">
                                      <span class="fa fa-arrow-down"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group m-b-xs" style="margin: 10px 10px 0 0;">
                                <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Повернуть влево" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Повернуть влево">
                                      <span class="fa fa-rotate-left"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Повернуть вправо" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Повернуть вправо">
                                      <span class="fa fa-rotate-right"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group m-b-xs" style="margin: 10px 10px 0 0;">
                                <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Отразить горизонтально" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Отразить горизонтально">
                                      <span class="fa fa-arrows-h"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Отразить вертикально" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Отразить вертикально">
                                      <span class="fa fa-arrows-v"></span>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="img-preview">
                                <img src="" id="crop_preview" />
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                    <a href="#" class="btn btn-primary save">Сохранить</a>
                </div>
            </div>
        </div>
    </div>
@endpushonce
