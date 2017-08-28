<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <h2>
            @yield('title')
        </h2>

        <ol class="breadcrumb">
            <li>
                @if (isActiveRoute('back'))
                    <strong>Главная</strong>
                @else
                    <a href="{{ url('/back/') }}">Главная</a>
                @endif
            </li>

            @stack('breadcrumbs')

            <li class="active">
                <strong>
                    {{ $title }}
                </strong>
            </li>

        </ol>
    </div>
</div>