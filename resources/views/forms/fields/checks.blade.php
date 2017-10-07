@php
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);
@endphp

<div class="form-group @if ($errors->has($transformName)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif

    <div class="col-sm-10">
        @foreach ($attributes['checks'] as $check)
            @php
                $checked = false;
                if (in_array($check['value'], (array) $value)) {
                    $checked = true;
                }
            @endphp

            <div class="i-checks"><label> {!! Form::checkbox($name, $check['value'], $checked, (isset($check['options'])) ? $check['options'] : []) !!} {{ isset($check['label']) ? $check['label'] : '' }} </label></div>
        @endforeach

        @foreach ($errors->get($transformName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach

    </div>
</div>

<div class="hr-line-dashed"></div>

@pushonce('styles:icheck')
    <!-- iCheck -->
    <link href="{!! asset('admin/css/plugins/iCheck/custom.css') !!}" rel="stylesheet">
@endpushonce

@pushonce('scripts:icheck')
    <!-- iCheck -->
    <script src="{!! asset('admin/js/plugins/iCheck/icheck.min.js') !!}"></script>
@endpushonce
