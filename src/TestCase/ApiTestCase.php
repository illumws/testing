<?php

namespace Alms\Testing\TestCase;

use PHPUnit\Framework\Attributes\AfterClass;
use PHPUnit\Framework\Attributes\BeforeClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Service\ResetInterface;


class ApiTestCase extends WebTestCase
{
    protected KernelBrowser|null $client = null;

    protected static KernelInterface|null $sharedKernel = null;

    #[BeforeClass]
    public static function createSharedKernel(): void
    {
        static::$sharedKernel = static::createKernel(['debug' => false]);
        static::$sharedKernel->boot();
    }

    #[AfterClass]
    public static function ensureSharedKernelShutdown(): void
    {
        if (null !== static::$sharedKernel)
        {
            $container = static::$sharedKernel->getContainer();
            static::$sharedKernel->shutdown();
            if ($container instanceof ResetInterface)
            {
                $container->reset();
            }
        }
    }

    public function setUp(): void
    {
        $this->client = static::createClient(['debug' => false]);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->client = null;

        parent::tearDown();
    }
}