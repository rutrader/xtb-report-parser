<?php

namespace App\Tests\Traits;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Purges and loads the fixtures before every tests.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
trait ReloadDatabaseTrait
{
    use BaseDatabaseTrait;

    protected static function bootKernel(array $options = []): KernelInterface
    {
        static::ensureKernelTestCase();
        $kernel = parent::bootKernel($options);
        static::populateDatabase();

        return $kernel;
    }
}