@php
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);

    $oldValues = old($transformName);

    if ($errors->count() > 0 && $oldValues) {
        if (is_array($oldValues)) {
            $correctValues = [];
            foreach ($oldValues as $key => $value) {
                $correctValues[$key] = (filter_var($value, FILTER_VALIDATE_INT)) ? intval($value) : $value;
            }
        } else {
            $correctValues = (filter_var($oldValues, FILTER_VALIDATE_INT)) ? intval($oldValues) : $oldValues;
        }

        session(['_old_input.'.$transformName => $correctValues]);
    } elseif ($errors->count() > 0 && ! $oldValues) {
        $value = [];
    }
@endphp

<div class="form-group @if ($errors->has($transformName)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif

    <div class="col-sm-10">

        @foreach ($attributes['radios'] as $radio)
            <div><label> {!! Form::radio($name, $radio['value'], (! $value && $loop->first || $radio['value'] == $value || $radio['value'] == old($transformName)) ? true : false, (isset($radio['options'])) ? $radio['options'] : []) !!} {{ $radio['label'] }} </label></div>
        @endforeach

        @foreach ($errors->get($transformName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach

    </div>
</div>

<div class="hr-line-dashed"></div>
