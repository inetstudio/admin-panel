<div class="top-navigation row border-bottom white-bg">
    <nav class="navbar navbar-expand-lg navbar-static-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i> </a>
        </div>

        <div class="navbar-collapse collapse" id="navbar">
            <ul class="nav navbar-nav mr-auto">

                @loadFromModules(back.includes.topnavbar.left)

                <li>
                    <a href="{{ url('/') }}" target="_blank">
                        <i class="fa fa-lg fa-home"></i> Перейти на сайт
                    </a>
                </li>
            </ul>

            <ul class="nav navbar-top-links navbar-right">

                @loadFromModules(back.includes.topnavbar.right)

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-lg fa-user"></i> {{ auth()->user()->name }} <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <a href="{{ url(route('back.acl.users.edit', auth()->user()->id)) }}">
                                <i class="fa fa-lg fa-pen-square"></i> Редактировать профиль
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(route('back.acl.users.logout')) }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-lg fa-sign-out-alt"></i> Выйти
                            </a>
                            <form id="logout-form" action="{{ url(route('back.acl.users.logout')) }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
