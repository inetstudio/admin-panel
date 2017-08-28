<div class="form-group">
    <div class="col-sm-12">

        {!! Form::submit('Сохранить', ['class' => 'btn btn-primary']) !!}

        @if (isset($attributes['back']))
            <a href="{{ url(route($attributes['back'])) }}" class="btn btn-w-m btn-warning m-l-xs">Отменить</a>
        @endif
    </div>
</div>
