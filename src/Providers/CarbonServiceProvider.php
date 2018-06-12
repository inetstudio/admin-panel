<?php

namespace InetStudio\AdminPanel\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

/**
 * Class CarbonServiceProvider.
 */
class CarbonServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        Carbon::macro('formatTime', function (string $strTime) {
            $monthsNames = [1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

            $carbonTime = self::parse($strTime);

            $day = $carbonTime->day;
            $month = (isset($monthsNames[$carbonTime->month])) ? $monthsNames[$carbonTime->month] : '';
            $time = sprintf('%02d', $carbonTime->hour).':'.sprintf('%02d', $carbonTime->minute);

            return trim($day.' '.$month).', '.$time;
        });
    }
}
