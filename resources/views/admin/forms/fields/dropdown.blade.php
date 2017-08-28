@php
    use Illuminate\Support\Facades\Input;

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

        Input::merge(array($transformName => $correctValues));
        Input::flash();
    } elseif ($errors->count() > 0 && ! $oldValues) {
        $attributes['options'] = [];
        $value = [];
    }
@endphp

<div class="form-group @if ($errors->has($transformName)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif

    <div class="col-sm-10">

        {!! Form::select($name, (isset($attributes['options'])) ? $attributes['options'] : [], $value, (isset($attributes['field'])) ? $attributes['field'] : []) !!}

        @foreach ($errors->get($transformName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach

    </div>
</div>

<div class="hr-line-dashed"></div>

@pushonce('styles:select2')
    <!-- SELECT2 -->
    <link href="{!! asset('admin/css/plugins/select2/select2.min.css') !!}" rel="stylesheet">
@endpushonce

@pushonce('scripts:select2')
    <!-- SELECT2 -->
    <script src="{!! asset('admin/js/plugins/select2/select2.full.min.js') !!}"></script>
    <script src="{!! asset('admin/js/plugins/select2/i18n/ru.js') !!}"></script>
@endpushonce
