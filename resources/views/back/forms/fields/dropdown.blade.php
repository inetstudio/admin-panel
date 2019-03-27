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

<div class="form-group row @if ($errors->has($transformName)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 col-form-label font-bold']) !!}
    @endif

    <div class="col-sm-10">

        {!! Form::select(
            $name,
            (isset($attributes['options']['values'])) ? $attributes['options']['values'] : [],
            $value,
            (isset($attributes['field'])) ? $attributes['field'] : [],
            (isset($attributes['options']['attributes'])) ? $attributes['options']['attributes'] : []
        ) !!}

        @foreach ($errors->get($transformName) as $message)
            <span class="form-text m-b-none">{{ $message }}</span>
        @endforeach

    </div>
</div>

@if (!(isset($attributes['hr']) && $attributes['hr']['show'] == false))
    <div class="hr-line-dashed"></div>
@endif
