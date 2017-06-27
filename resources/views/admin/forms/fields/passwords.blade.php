@php
    $errName = str_replace(['[', ']'], ['.', ''], $name);
@endphp

<div class="form-group @if ($errors->has($errName)){!! "has-error" !!}@endif">
    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], ['class' => (isset($attributes['label']['class'])) ? $attributes['label']['class'] : '']) !!}
    @endif
    <div class="col-sm-10">
        {!! Form::password($name, (isset($attributes['fields'][0])) ? $attributes['fields'][0] : []) !!}
        {!! Form::password($name.'_confirmation', (isset($attributes['fields'][1])) ? $attributes['fields'][1] : []) !!}
        @foreach ($errors->get($errName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach
    </div>
</div>

<div class="hr-line-dashed"></div>
