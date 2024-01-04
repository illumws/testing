<?php

namespace Alms\Testing\Constraints;

use ArrayObject;
use PHPUnit\Framework\Constraint\Constraint;
use SebastianBergmann\Comparator\ComparisonFailure;
use Traversable;

/**
 * @internal This class is not meant to be used or overwritten outside the framework itself.
 */
final class ArraySubset extends Constraint
{
    /**
     * Create a new array subset constraint instance.
     *
     * @param iterable $subset
     * @param bool $strict
     * @return void
     */
    public function __construct(
        protected iterable $subset,
        protected bool $strict = false
    )
    {
    }

    /**
     * Evaluates the constraint for parameter $other.
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @param mixed $other
     * @param string $description
     * @param bool $returnResult
     * @return bool|null
     */
    public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        // type cast $other & $this->subset as an array to allow
        // support in standard array functions.
        $other = $this->toArray($other);
        $this->subset = $this->toArray($this->subset);

        $patched = array_replace_recursive($other, $this->subset);

        if ($this->strict)
        {
            $result = $other === $patched;
        } else
        {
            $result = $other == $patched;
        }

        if ($returnResult)
        {
            return $result;
        }

        if (!$result)
        {
            $f = new ComparisonFailure(
                $patched,
                $other,
                var_export($patched, true),
                var_export($other, true)
            );

            $this->fail($other, $description, $f);
        }

        return null;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString(): string
    {
        return 'has the subset ' . $this->exporter()->export($this->subset);
    }

    /**
     * Returns the description of the failure.
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param mixed $other
     * @return string
     */
    protected function failureDescription(mixed $other): string
    {
        return 'an array ' . $this->toString();
    }

    /**
     * Returns the description of the failure.
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param iterable $other
     * @return array
     */
    private function toArray(iterable $other): array
    {
        if (is_array($other))
        {
            return $other;
        }

        if ($other instanceof ArrayObject)
        {
            return $other->getArrayCopy();
        }

        if ($other instanceof Traversable)
        {
            return iterator_to_array($other);
        }

        // Keep BC even if we know that array would not be the expected one
        return (array)$other;
    }
}
