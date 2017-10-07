@php
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);
@endphp

<div class="form-group @if ($errors->filled($transformName)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif

    <div class="col-sm-10">

        {!! Form::password($name, (isset($attributes['fields'][0])) ? $attributes['fields'][0] : []) !!}

        {!! Form::password($name.'_confirmation', (isset($attributes['fields'][1])) ? $attributes['fields'][1] : []) !!}

        @foreach ($errors->get($transformName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach

    </div>
</div>

<div class="hr-line-dashed"></div>
