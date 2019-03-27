<div class="form-group row">
    <div class="col-sm-12">

        {!! Form::submit('Сохранить', ['class' => 'btn btn-sm btn-primary']) !!}

        @if (isset($attributes['back']))
            <a href="{{ url(route($attributes['back'])) }}" class="btn btn-sm btn-w-m btn-warning m-l-xs">Отменить</a>
        @endif
    </div>
</div>
