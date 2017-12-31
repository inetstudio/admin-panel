<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">

            @loadFromModules(back.includes.navigation)

            @include('admin::back.includes.acl.navigation')
        </ul>
    </div>
</nav>