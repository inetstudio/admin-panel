<?php echo '<?php' ?>


namespace {{ $namespace }};

use Illuminate\Support\ServiceProvider;

/**
 * Class {{ $className }}.
 */
class {{ $className }} extends ServiceProvider
{
    /**
    * @var bool
    */
    protected $defer = true;

    /**
    * @var array
    */
    public $bindings = [
@foreach ($bindings as $contract => $implementation)
        '{{ $contract }}' => '{{ $implementation }}',
@endforeach
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
