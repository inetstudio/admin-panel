<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">

            @loadFromModules(includes.navigation)

            @include('admin::includes.acl.navigation')
        </ul>
    </div>
</nav>