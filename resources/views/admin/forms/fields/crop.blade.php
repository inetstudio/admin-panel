@php
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);
@endphp

<div class="form-group @if ($errors->has($transformName)){!! "has-error" !!}@endif">
    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif
    <div class="col-sm-10">
        <div class="col-md-6">
            <div id="{{ $name }}_preview">
                <img src="{{ (old($name.'.base64')) ? old($name.'.base64') : $attributes['image']['src'] }}" @if (! $attributes['image']['src'] && ! old($name.'.base64'))data-src="holder.js/100px200?auto=yes&font=FontAwesome&text=&#xf1c5;"@endif class="m-b-md img-responsive placeholder">
            </div>
        </div>
        <div class="col-md-6">
            <div class="btn-group">
                <label title="Загрузить изображение" for="{{ $name }}_inputImage" class="btn btn-success">
                    <i class="fa fa-upload"></i>

                    {!! Form::file($name.'[file]', [
                        'accept' => 'image/*',
                        'class' => 'hide inputImage',
                        'id' => $name.'_inputImage',
                        'data-field' => $name,
                    ]) !!}

                    {!! Form::hidden($name.'[base64]', '', [
                        'id' => $name.'_base64',
                    ]) !!}

                    Загрузить изображение
                </label><br/>

                @if (isset($attributes['image']['type']))
                    {!! Form::hidden($name.'[type]', $attributes['image']['type']) !!}
                @endif

                <div id="{{ $name }}_crop_buttons" style="@if (! isset($value)) display:none @endif">
                    @if (isset($attributes['crops']))
                        @foreach ($attributes['crops'] as $crop)
                            <a href="#" class="btn btn-w-m {{ ($crop['value'] == '') ? 'btn-default' : 'btn-primary' }} start-cropper" data-ratio="{{ $crop['ratio'] }}" data-crop-field="{{ $name }}[crop][{{ $crop['name'] }}]"><i class="fa fa-crop"></i> {{ $crop['title'] }}</a><br/>

                            {!! Form::hidden($name.'[crop]'.'['.$crop['name'].']', $crop['value'], [
                                'class' => 'crop-data',
                            ]) !!}
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        @foreach ($errors->get($transformName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach
    </div>
</div>

@if (isset($attributes['additional']))
    <div id="{{ $name }}_additional" style="@if (! isset($value)) display:none @endif">
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
    </div>
@endif

<div class="hr-line-dashed"></div>