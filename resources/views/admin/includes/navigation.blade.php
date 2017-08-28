<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">

            @loadFromModules(includes.navigation)

            <li class="{{ areActiveRoutes(['back.acl.roles.*', 'back.acl.permissions.*', 'back.acl.users.*']) }}">
                <a href="#"><i class="fa fa-users"></i> <span class="nav-label">ACL </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li class="{{ isActiveRoute('back.acl.roles.*') }}">
                        <a href="{{ route('back.acl.roles.index') }}">Роли</a>
                    </li>
                    <li class="{{ isActiveRoute('back.acl.permissions.*') }}">
                        <a href="{{ route('back.acl.permissions.index') }}">Права</a>
                    </li>
                    <li class="{{ isActiveRoute('back.acl.users.*') }}">
                        <a href="{{ route('back.acl.users.index') }}">Пользователи</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>