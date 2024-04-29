<?php

declare(strict_types=1);

namespace Alms\Testing\Database\Traits;

use Alms\Testing\Database\EntityAssertion;
use Doctrine\ORM\EntityManagerInterface;
use function is_object;

trait DatabaseAsserts
{
    /**
     * Build entity assertion.
     *
     * @param class-string|object $entity
     */
    public function assertEntity(string|object $entity, string $alias): EntityAssertion
    {
        if (is_object($entity)) {
            $entity = $entity::class;
        }

        return new EntityAssertion($alias, $entity, self::$sharedKernel->get->get('doctrine.orm.entity_manager'));
    }
}
