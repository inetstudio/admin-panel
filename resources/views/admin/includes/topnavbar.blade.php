<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-left">
            <li>
                <a href="{{ url('/') }}" target="_blank">
                    Перейти на сайт
                </a>
            </li>
        </ul>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user"></i> {{ \Auth::user()->name }} <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-messages">
                    <li>
                        <a href="{{ url(route('back.acl.users.edit', \Auth::user()->id)) }}">
                            <i class="fa fa-pencil-square-o"></i> Редактировать профиль
                        </a>
                    </li>
                    <li>
                        <a href="{{ url(route('back.logout')) }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out"></i> Выйти
                        </a>
                        <form id="logout-form" action="{{ url(route('back.logout')) }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>