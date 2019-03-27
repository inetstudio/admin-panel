<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>
            @yield('title')
        </h2>

        <ol class="breadcrumb">
            @if (isActiveRoute('back'))
                <li class="breadcrumb-item">
                    <strong>Главная</strong>
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ route('back') }}">Главная</a>
                </li>

                @stack('breadcrumbs')

                <li class="breadcrumb-item active">
                    <strong>
                        {{ $title }}
                    </strong>
                </li>
            @endif

        </ol>
    </div>
</div>
