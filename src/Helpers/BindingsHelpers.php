<?php

namespace InetStudio\AdminPanel\Helpers;

use Symfony\Component\Finder\Finder;
use Illuminate\Support\Facades\Cache;

/**
 * Class BindingsHelpers.
 */
class BindingsHelpers
{
    /**
     * Возвращаем соответствия контрактов с их реализациями.
     *
     * @param string $pathToContracts
     *
     * @return array
     */
    public static function getPackageBindings(string $pathToContracts): array
    {
        $cacheKey = 'packageBindings_'.md5($pathToContracts);

        return Cache::rememberForever($cacheKey, function () use ($pathToContracts) {
            $files = Finder::create()->files()->in($pathToContracts)->name('*.php');

            $bindings = [];

            foreach ($files as $file) {
                $contents = $file->getContents();
                $className = $file->getBasename('.php');

                preg_match('#^namespace\s+(.+?);$#sm', $contents, $matches);

                $contract = $matches[1].'\\'.$className;
                $implementation = str_replace(['Contracts\\', 'Contract'], ['', ''], $contract);

                $bindings[$contract] = $implementation;
            }

            return $bindings;
        });
    }
}
