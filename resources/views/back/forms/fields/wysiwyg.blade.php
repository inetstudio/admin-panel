@php
    $formName = $name;
    $name = isset($attributes['field']['name']) ? $attributes['field']['name'] : $formName;
    $attributes['field']['name'] = $name.'[text]';

    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);

    $properties = (isset($attributes['images']['fields'])) ? $attributes['images']['fields'] : [];
    $properties = json_encode($properties);

    $attributes['field']['properties'] = $properties;
@endphp

<div class="form-group @if ($errors->has($transformName)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif

    <div class="col-sm-10">

        {!! Form::textarea('', $value, $attributes['field']) !!}

        @foreach ($errors->get($transformName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach

    </div>
</div>

@if (isset($attributes['images']['fields']))
    @php
        $media = [];

        if (old($transformName.'.images')) {
            $media = old($transformName.'.images');
        } else {
            foreach ($attributes['images']['media'] as $mediaItem) {
                $data = [
                    'id' => $mediaItem->id,
                    'src' => url($mediaItem->getUrl()),
                    'thumb' => ($mediaItem->getUrl($formName.'_admin')) ? url($mediaItem->getUrl($formName.'_admin')) : url($mediaItem->getUrl()),
                    'properties' => $mediaItem->custom_properties,
                ];

                $media[] = $data;
            }
        }
    @endphp
    <div class="row" id="{{ (isset($attributes['field']['id'])) ? $attributes['field']['id'] : $name }}_images" data-media="{{ json_encode($media) }}">
        <input name="{{ $name }}[has_images]" type="hidden" value="1">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <div class="file-box" style="width: 140px" v-for="(image, index) in images">
                <div class="file">
                    <span class="corner"></span>
                    <div class="image">
                        <img :src="image.thumb" class="img-responsive">

                        <input :name="'{{ $name }}[images][' + index + '][id]'" type="hidden" :value="image.id">
                        <input :name="'{{ $name }}[images][' + index + '][src]'" type="hidden" :value="image.src">
                        <input :name="'{{ $name }}[images][' + index + '][tempname]'" type="hidden" :value="image.tempname">
                        <input :name="'{{ $name }}[images][' + index + '][filename]'" type="hidden" :value="image.filename">
                        <input v-for="(value, key) in image.properties" :name="'{{ $name }}[images][' + index + '][properties][' + key + ']'" type="hidden" :value="value">
                    </div>
                    <div class="file-name">
                        <a class="btn btn-primary btn-xs add" @click.prevent="add(index)">
                            <i class="fa fa-plus"></i>
                        </a>
                        <a class="btn btn-white btn-xs edit" @click.prevent="edit(index)">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a class="btn btn-danger btn-xs delete" @click.prevent="remove(index)">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="hr-line-dashed"></div>

@pushonce('scripts:tinymce')
    <!-- TINYMCE -->
    <script src="{!! asset('admin/js/plugins/tinymce/tinymce.min.js') !!}"></script>
@endpushonce

@pushonce('scripts:plupload')
    <!-- PLUPLOAD -->
    <script src="{!! asset('admin/js/plugins/plupload/plupload.full.min.js') !!}"></script>
@endpushonce

@pushonce('modals:uploader')
    <div class="modal inmodal fade" id="uploader_modal" tabindex="-1" role="dialog" aria-hidden="true" ref="vuemodal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title">Загрузка изображений</h4>
                </div>
                <div class="modal-body">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div v-show="progress.state" class="progress progress-bar-default">
                                    <div :style="progress.style" aria-valuemax="100" aria-valuemin="0" :aria-valuenow="progress.percents" role="progressbar" class="progress-bar">
                                        <span>@{{ progress.text }}</span>
                                    </div>
                                </div>
                                <div v-show="upload" id="uploader-area" data-target="{{ route('back.upload') }}">Перенесите изображения в область</div>
                            </div>
                        </div>
                        <template v-for="image in images">
                            <div class="row upload-image m-t-md" :data-hash="image.hash">
                                <div class="col-md-3">
                                    <img :src="image.src" class="m-b-md img-responsive placeholder" :data-tempname="image.tempname" :data-filename="image.filename">
                                </div>
                                <div class="col-md-9 form-horizontal">
                                    <div class="form-group" v-for="input in inputs">
                                        <label :for="input.name" class="col-sm-2 control-label">@{{ input.title }}</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" v-model="image.properties[input.name]" :name="input.name" type="text" value="" :id="input.name">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </template>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                    <a href="#" class="btn btn-primary" @click.prevent="save">Сохранить</a>
                </div>
            </div>
        </div>
    </div>
@endpushonce

@pushonce('modals:edit_image')
    <div class="modal inmodal fade" id="edit_image_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title">Редактирование изображения</h4>
                </div>
                <div class="modal-body">
                    <div class="ibox-content form-horizontal">
                        <div class="row m-b-md">
                            <img :src="image.src" class="img-responsive" style="max-height: 400px; display: block; margin: auto" />
                            <div class="hr-line-dashed"></div>
                        </div>
                        <div class="row">
                            <template v-for="input in inputs">
                                <div class="form-group">
                                    <label :for="input.name" class="col-sm-2 control-label">@{{ input.title }}</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" :name="input.name" type="text" :value="image.properties[input.name]" :id="input.name">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                    <a href="#" class="btn btn-primary" @click.prevent="save">Сохранить</a>
                </div>
            </div>
        </div>
    </div>
@endpushonce
