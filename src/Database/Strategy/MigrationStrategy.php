<?php

declare(strict_types=1);

namespace Alms\Testing\Database\Strategy;

use Alms\Testing\Database\DatabaseState;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

class MigrationStrategy
{
    protected Application $console;

    public function __construct(
        protected KernelInterface $kernel
    )
    {
        $this->console = new Application($kernel);
    }

    public function migrate(): void
    {
        if (!DatabaseState::$migrated) {
            $commandTester = new CommandTester(
                command: $this->console->find('doctrine:migrations:migrate')
            );
            $commandTester->execute(['--force']);
        }
        DatabaseState::$migrated = true;
    }

    public function rollback(): void
    {
        $commandTester = new CommandTester(
            command: $this->console->find('doctrine:migrations:migrate')
        );
        $commandTester->execute(['--all']);

        DatabaseState::$migrated = false;
    }
}
