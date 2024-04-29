<?php

declare(strict_types=1);

namespace Alms\Testing\Database;

use Closure;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use function sprintf;

class EntityAssertion
{
    protected \Doctrine\ORM\QueryBuilder $select;

    /**
     * @param class-string $entity
     */
    public function __construct(
        string $alias,
        protected readonly string $entity,
        protected EntityManagerInterface $orm,
    )
    {
        $repository = $orm->getRepository($entity);

        $this->select = $repository->createQueryBuilder($alias);
    }


    /**
     * Use query builder for the next query.
     * @param Closure(QueryBuilder):void $closure
     */
    public function select(Closure $closure): self
    {
        $self = clone $this;
        $closure($self->select);

        return $self;
    }

    public function where(string $condition): self
    {
        $self = clone $this;
        $self->select->where($condition);

        return $self;
    }

    /**
     * Assert that the number of entities in the table for the current query is equal to the expected number.
     */
    public function assertCount(int $total): void
    {
        $actual = $this->count();

        TestCase::assertSame(
            $total,
            $actual,
            sprintf('Expected %s entities in the table, actual are %s.', $total, $actual),
        );
    }

    /**
     * Assert that at least one entity is present in the table for the current query.
     */
    public function assertExists(): void
    {
        TestCase::assertTrue($this->count() > 0, sprintf('Entity [%s] not found.', $this->entity));
    }

    /**
     * Assert that no entities are present in the table for the current query.
     */
    public function assertMissing(): void
    {
        TestCase::assertSame(0, $this->count(), sprintf('Entity [%s] found.', $this->entity));
    }

    /**
     * Assert that no entities are present in the table for the current query.
     */
    public function assertEmpty(): void
    {
        $this->assertCount(0);
    }

    /**
     * Count entities in the table for the current query.
     */
    public function count(): int
    {
        return count($this->select->getQuery()->getArrayResult());
    }

    public function __clone()
    {
        $this->select = clone $this->select;
    }
}
