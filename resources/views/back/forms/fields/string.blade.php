@php
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);
@endphp

<div class="form-group @if ($errors->has($transformName)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']) && $attributes['label']['title'] == '')
        <label for="{{ $name }}" class="col-sm-2 control-label"></label>
    @elseif (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif

    <div class="col-sm-10">

        {!! Form::text($name, $value, (isset($attributes['field'])) ? $attributes['field'] : ['class' => 'form-control']) !!}

        @foreach ($errors->get($transformName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach

    </div>
</div>

@if (!(isset($attributes['hr']) && $attributes['hr']['show'] == false))
    <div class="hr-line-dashed"></div>
@endif
