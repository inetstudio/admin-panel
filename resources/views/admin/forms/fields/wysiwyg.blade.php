@php
    $errName = str_replace(['[', ']'], ['.', ''], $name);
@endphp

<div class="form-group @if ($errors->has($errName)){!! "has-error" !!}@endif">
    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], ['class' => (isset($attributes['label']['class'])) ? $attributes['label']['class'] : '']) !!}
    @endif
    <div class="col-sm-10">
        {!! Form::textarea($name, $value, (isset($attributes['field'])) ? $attributes['field'] : []) !!}
        @foreach ($errors->get($errName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach
    </div>
</div>

<div class="hr-line-dashed"></div>
