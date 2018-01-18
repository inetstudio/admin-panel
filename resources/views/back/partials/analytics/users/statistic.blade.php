@php
    $colors = [
        0 => 'warning',
        1 => 'primary',
    ];

    $titles = [
        0 => 'Неактивные',
        1 => 'Активные',
    ];
@endphp

<div class="ibox float-e-margins">
    <div class="ibox-content">
        <h2>Регистрации</h2>
        <ul class="todo-list m-t">
            @foreach ($registrations as $registration)
                <li>
                    <small class="label label-{{ (isset($colors[$registration->activated])) ? $colors[$registration->activated] : 'info' }}">{{ $registration->total }}</small>
                    <span class="m-l-xs">{{ (isset($titles[$registration->activated])) ? $titles[$registration->activated] : 'Не удалось определить статус' }}</span>
                </li>
            @endforeach
            <hr>
            <li>
                <small class="label label-default">{{ $registrations->sum('total') }}</small>
                <span class="m-l-xs">Всего</span>
            </li>
        </ul>
    </div>
</div>
