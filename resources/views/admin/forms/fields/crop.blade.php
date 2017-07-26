@php
    $errName = str_replace(['[', ']'], ['.', ''], $name);
@endphp

<div class="form-group ">
    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], ['class' => (isset($attributes['label']['class'])) ? $attributes['label']['class'] : '']) !!}
    @endif
    <div class="col-sm-10">
        <div class="col-md-6">
            <div id="{{ $name }}_preview">
                <img src="{{ $attributes['image']['src'] }}" @if (! $attributes['image']['src'])data-src="holder.js/100px200?auto=yes&font=FontAwesome&text=&#xf1c5;"@endif class="m-b-md img-responsive placeholder">
            </div>
        </div>
        <div class="col-md-6">
            <div class="btn-group">
                <label title="Загрузить изображение" for="{{ $name }}_inputImage" class="btn btn-success">
                    <i class="fa fa-upload"></i>
                    <input type="file" accept="image/*" name="{{ $name }}[file]" class="hide inputImage" id="{{ $name }}_inputImage" data-target="{{ $name }}_preview">
                    Загрузить изображение
                </label><br/>

                @if (isset($attributes['crops']))
                    @foreach ($attributes['crops'] as $crop)
                        <a href="#" class="btn btn-w-m {{ ($crop['value'] == '') ? 'btn-default' : 'btn-primary' }} start-cropper" data-ratio="{{ $crop['ratio'] }}" data-crop-field="{{ $name }}[crop][{{ $crop['name'] }}]"><i class="fa fa-crop"></i> {{ $crop['title'] }}</a><br/>
                        <input type="hidden" name="{{ $name }}[crop][{{ $crop['name'] }}]" class="crop-data" value="{{ $crop['value'] }}" />
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@if (isset($attributes['additional']))
    @foreach ($attributes['additional'] as $field)
        {!! Form::string($name.'['.$field["name"].']', $field['value'], [
            'label' => [
                'title' => $field['title'],
                'class' => 'col-sm-2 control-label',
            ],
            'field' => [
                'class' => 'form-control',
            ],
        ]) !!}
    @endforeach
@endif

<div class="hr-line-dashed"></div>