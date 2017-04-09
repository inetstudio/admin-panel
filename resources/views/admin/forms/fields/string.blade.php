@php
    $errName = str_replace(['[', ']'], ['.', ''], $name);

    if ($errors->has($errName)) {
        $error = implode(', ', $errors->get($errName));
        $class = 'error-field';
    } else {
        $error = '';
        $class = '';
    }
@endphp

<div class="form-group @if ($errors->has($errName)){!! "has-error" !!}@endif">
    {!! Form::label($name, $attributes['label'], ['class' => $class]) !!}
    <div class="col-sm-10">
        {!! Form::text($name, $value, $attributes) !!}
        @foreach ($errors->get($errName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach
    </div>
</div>

<div class="hr-line-dashed"></div>