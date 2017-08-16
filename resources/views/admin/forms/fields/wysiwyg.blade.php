@php
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

        {!! Form::textarea($name.'[text]', $value, $attributes['field']) !!}

        @foreach ($errors->get($transformName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach

    </div>
</div>

@if (isset($attributes['images']['fields']))
    @php
        $media = [];

        if (old($name.'.images')) {
            $media = old($name.'.images');
        } else {
            foreach ($attributes['images']['media'] as $mediaItem) {
                $data = [
                    'id' => $mediaItem->id,
                    'src' => url($mediaItem->getUrl()),
                    'properties' => $mediaItem->custom_properties,
                ];

                $media[] = $data;
            }
        }
    @endphp
    <div class="row" id="{{ $name }}_images" data-media="{{ json_encode($media) }}">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <div class="file-box" style="width: 140px" v-for="(image, index) in images">
                <div class="file">
                    <span class="corner"></span>
                    <div class="image">
                        <img :src="image.src" class="img-responsive">

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
