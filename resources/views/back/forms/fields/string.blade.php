@php
    $name =  isset($attributes['field']['name']) ? $attributes['field']['name'] : $name;
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);
@endphp

<div class="form-group row @if ($errors->has($transformName)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']) && $attributes['label']['title'] == '')
        <label for="{{ $name }}" class="col-sm-2 col-form-label font-bold"></label>
    @elseif (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 col-form-label font-bold font-bold']) !!}
    @endif

    <div class="col-sm-10">

        {!! Form::text($name, $value, (isset($attributes['field'])) ? $attributes['field'] : ['class' => 'form-control']) !!}

        @if (isset($attributes['field']['class']) && strpos($attributes['field']['class'], 'countable') !== false)
            <span class="form-text m-b-none {{ $name }}-counter counter"></span>
        @endif

        @foreach ($errors->get($transformName) as $message)
            <span class="form-text m-b-none">{{ $message }}</span>
        @endforeach

    </div>
</div>

@if (!(isset($attributes['hr']) && $attributes['hr']['show'] == false))
    <div class="hr-line-dashed"></div>
@endif
