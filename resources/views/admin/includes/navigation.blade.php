<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">

            @loadFromModules(includes.navigation)

            <li class="{{ isActiveMatch('acl') }}">
                <a href="#"><i class="fa fa-users"></i> <span class="nav-label">ACL </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li class="{{ isActiveMatch('acl/roles') }}">
                        <a href="{{ route('back.acl.roles.index') }}">Роли</a>
                    </li>
                    <li class="{{ isActiveMatch('acl/permissions') }}">
                        <a href="{{ route('back.acl.permissions.index') }}">Права</a>
                    </li>
                    <li class="{{ isActiveMatch('acl/users') }}">
                        <a href="{{ route('back.acl.users.index') }}">Пользователи</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>