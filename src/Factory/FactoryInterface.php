<?php

declare(strict_types=1);

namespace Alms\Testing\Factory;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @template TEntity of object
 */
interface FactoryInterface
{
    /**
     * Get the entity class name.
     *
     * @psalm-return class-string<TEntity>
     */
    public function entity(): string;

    public static function new(): static;

    /**
     * Entity data definition. This data will be used to create an entity.
     */
    public function definition(): array;

    /**
     * How many entities should be created.
     *
     * @psalm-param positive-int $amount
     */
    public function times(int $amount): self;

    /**
     * Create many entities with persisting them to the database.
     *
     * @return TEntity[]
     */
    public function create(EntityManagerInterface $entityManager = null): array;

    /**
     * Create an entity with persisting it to the database.
     *
     * @return TEntity
     */
    public function createOne(EntityManagerInterface $entityManager = null): object;
}
