<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Admin | Вход</title>

        <link href="{!! asset('/admin/css/bootstrap.min.css') !!}" rel="stylesheet">
        <link href="{!! asset('/admin/font-awesome/css/font-awesome.css') !!}" rel="stylesheet">
        <link href="{!! asset('/admin/css/plugins/iCheck/custom.css') !!}" rel="stylesheet">
        <link href="{!! asset('/admin/css/animate.css') !!}" rel="stylesheet">
        <link href="{!! asset('/admin/css/style.css') !!}" rel="stylesheet">

    </head>

    <body class="gray-bg">

        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div>
                <div>
                    <h1 class="logo-name"><i class="fa fa-user-o"></i></h1>
                </div>
                <form class="m-t" role="form" action="{{ url(route('admin.login')) }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group @if(isset($errors) and $errors->has('email')) has-error @endif">
                        <input type="text" name="name" class="form-control" placeholder="Логин" required="">
                    </div>
                    <div class="form-group @if(isset($errors) and $errors->has('password')) has-error @endif">
                        <input type="password" name="password" class="form-control" placeholder="Пароль" required="">
                    </div>
                    <div class="form-group">
                        <div class="i-checks pull-right m-b-sm">
                            <label> <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}> <i></i> Запомнить меня </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary block full-width">Войти</button>
                </form>
            </div>
        </div>

        <!-- Mainly scripts -->
        <script src="{!! asset('/admin/js/jquery-3.1.1.min.js') !!}"></script>
        <script src="{!! asset('/admin/js/bootstrap.min.js') !!}"></script>

        <!-- iCheck -->
        <script src="{!! asset('/admin/js/plugins/iCheck/icheck.min.js') !!}"></script>
        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
            });
        </script>

    </body>
</html>

