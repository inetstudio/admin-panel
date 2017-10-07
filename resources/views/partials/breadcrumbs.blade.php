<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <h2>
            @yield('title')
        </h2>

        <ol class="breadcrumb">
            @if (isActiveRoute('back'))
                <li>
                    <strong>Главная</strong>
                </li>
            @else
                <li>
                    <a href="{{ route('back') }}">Главная</a>
                </li>

                @stack('breadcrumbs')

                <li class="active">
                    <strong>
                        {{ $title }}
                    </strong>
                </li>
            @endif

        </ol>
    </div>
</div>
