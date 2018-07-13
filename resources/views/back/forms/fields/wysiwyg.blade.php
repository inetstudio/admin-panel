@php
    $name = isset($attributes['field']['name']) ? $attributes['field']['name'] : $name;
    $attributes['field']['name'] = $name.'[text]';

    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);
    $transformNameText = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $attributes['field']['name']);
@endphp

<div class="form-group @if ($errors->has($transformNameText)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif

    <div class="col-sm-10">

        {!! Form::textarea('', old($transformNameText) ? old($transformNameText) : $value, $attributes['field']) !!}

        @foreach ($errors->get($transformNameText) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach

    </div>
</div>

@if (isset($attributes['images']))
    {!! Form::imagesStack($name, (isset($attributes['field']['id'])) ? $attributes['field']['id'] : $name,
        array_merge(
            $attributes['images'],
            [
                'controls' => ['add', 'edit', 'remove']
            ]
        )
    ) !!}
@endif

<div class="hr-line-dashed"></div>

@include('admin.module.uploads::back.widgets.gallery_widget')
