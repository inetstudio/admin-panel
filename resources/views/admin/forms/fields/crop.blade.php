@php
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);

    $errorFields = [$name];
    if (isset($attributes['crops'])) {
        foreach ($attributes['crops'] as $crop) {
            $errorFields[] = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name.'[crop]'.'['.$crop['name'].']');
        }
    }
@endphp

<div class="form-group @if (count(array_intersect($errors->getBag('default')->keys(), $errorFields)) > 0){!! "has-error" !!}@endif">
    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif
    <div class="col-sm-10">
        <div class="col-md-6">
            <div class="ibox">
                <div class="progress progress-bar-default pace-inactive" id="{{ $name }}_progress">
                    <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar"></div>
                </div>
                <div class="ibox-content">
                    <div class="sk-spinner sk-spinner-double-bounce">
                        <div class="sk-double-bounce1"></div>
                        <div class="sk-double-bounce2"></div>
                    </div>
                    <div id="{{ $name }}_preview">
                        <img src="{{ (old($name.'.temppath')) ? old($name.'.temppath') : $attributes['image']['src'] }}" @if (! $attributes['image']['src'] && ! old($name.'.temppath'))data-src="holder.js/100px200?auto=yes&font=FontAwesome&text=&#xf1c5;"@endif class="m-b-md img-responsive placeholder">
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
                    <i class="fa fa-upload"></i>

                    {!! Form::hidden($name.'[temppath]', '', [
                        'id' => $name.'_temppath',
                    ]) !!}

                    {!! Form::hidden($name.'[tempname]', '', [
                        'id' => $name.'_tempname',
                    ]) !!}

                    {!! Form::hidden($name.'[filename]', '', [
                        'id' => $name.'_filename',
                    ]) !!}

                    Загрузить изображение
                </a><br/>

                <div id="{{ $name }}_crop_buttons" style="@if (! isset($value) and ! old($name.'.temppath')) display:none @endif">
                    @if (isset($attributes['crops']))
                        @foreach ($attributes['crops'] as $crop)
                            <a href="#" class="btn btn-w-m {{ (($crop['value'] == '' and ! old($transformName.'.crop.'.$crop['name'])) or $errors->has($transformName.'.crop.'.$crop['name'])) ? 'btn-default' : 'btn-primary' }} start-cropper" data-ratio="{{ $crop['ratio'] }}" data-crop-field="{{ $name }}[crop][{{ $crop['name'] }}]" data-crop-settings="{{ json_encode($crop['size']) }}"><i class="fa fa-crop"></i> {{ $crop['title'] }}</a><br/>

                            {!! Form::hidden($name.'[crop]'.'['.$crop['name'].']', ($errors->has($transformName.'.crop.'.$crop['name'])) ? '' : $crop['value'], [
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
    <div id="{{ $name }}_additional" style="@if (! isset($value) and ! old($name.'.temppath')) display:none @endif">
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
