@if (Session::has('success'))
    <div class="alert alert-success">
        {!! Session::get('success') !!}
    </div>
@elseif (count($errors) > 0)
    <div class="alert alert-danger">
        При сохранении произошли ошибки.
    </div>
@endif
