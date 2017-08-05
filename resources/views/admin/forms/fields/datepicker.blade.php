@php
    $transformName = (array) str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);
    $names = (array) $name;
@endphp

<div class="form-group @if ($errors->hasAny($transformName)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']))
        {!! Form::label($names[0], $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif

    <div class="col-sm-10">

        @if (is_array($value))
            <div class="input-group">
                <div class="input-group m-b">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {!! Form::text($names[0], ($value[0]) ? date('d.m.Y', strtotime(trim(strip_tags($value[0])))) : '', (isset($attributes['field'])) ? $attributes['field'] : []) !!}
                    <span class="input-group-addon"> - </span>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {!! Form::text($names[1], ($value[1]) ? date('d.m.Y', strtotime(trim(strip_tags($value[1])))) : '', (isset($attributes['field'])) ? $attributes['field'] : []) !!}
                </div>
            </div>
        @else
            <div class="input-group m-b">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                {!! Form::text($names[0], $value, (isset($attributes['field'])) ? $attributes['field'] : []) !!}
            </div>
        @endif

        @foreach ($transformName as $field)
            @foreach ($errors->get($field) as $message)
                <span class="help-block m-b-none">{{ $message }}</span>
            @endforeach
        @endforeach

    </div>
</div>

<div class="hr-line-dashed"></div>
