@php
    $errName = str_replace(['[', ']'], ['.', ''], $name);

    if ($errors->has($errName)) {
        $error = implode(', ', $errors->get($errName));
        $class = 'error-field';
    } else {
        $error = '';
        $class = '';
    }
@endphp

<div class="form-group @if ($errors->has($errName)){!! "has-error" !!}@endif">
    <label class="col-sm-2 control-label">Описание</label>
    <div class="col-sm-10">
        <textarea name="description" class="summernote">
            @if(isset($item)){!! (old('description')) ? old('description') : $item->description !!}@else{{ old('description') }}@endif
        </textarea>
        @foreach ($errors->get($errName) as $message)
            <span class="help-block m-b-none">{{ $message }}</span>
        @endforeach
    </div>
</div>

<div class="hr-line-dashed"></div>