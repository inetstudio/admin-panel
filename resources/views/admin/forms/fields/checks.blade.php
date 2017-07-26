@php
    $parseName = str_replace(['[', ']'], ['.', ''], $name);
@endphp

<div class="form-group @if ($errors->has($parseName)){!! "has-error" !!}@endif">
    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], ['class' => (isset($attributes['label']['class'])) ? $attributes['label']['class'] : '']) !!}
    @endif
    <div class="col-sm-10">
        @foreach ($attributes['checks'] as $check)
            @php
                $checked = false;
                if (old($parseName) and in_array($check['value'], old($parseName))) {
                    $checked = true;
                } elseif (! old($parseName) and in_array($check['value'], (array)$value)) {
                    $checked = true;
                }
            @endphp
            <div class="i-checks"><label> {!! Form::checkbox($name.'[]', $check['value'], $checked) !!} {{ isset($check['label']) ? $check['label'] : '' }} </label></div>
        @endforeach

        @foreach ($errors->get($parseName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach
    </div>
</div>

<div class="hr-line-dashed"></div>