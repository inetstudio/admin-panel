<li class="{{ isActiveRoute('back.acl.*') }}">
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