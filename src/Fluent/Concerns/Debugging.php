<?php

namespace Alms\Testing\Fluent\Concerns;

use Alms\Testing\Fluent\AssertableJson;

trait Debugging
{
    /**
     * Dumps the given props.
     *
     * @param string|null $prop
     * @return Debugging|AssertableJson
     */
    public function dump(string $prop = null): self
    {
        dump($this->prop($prop));

        return $this;
    }

    /**
     * Dumps the given props and exits.
     *
     * @param string|null $prop
     * @return void
     */
    public function dd(string $prop = null): void
    {
        dd($this->prop($prop));
    }

    /**
     * Retrieve a prop within the current scope using "dot" notation.
     *
     * @param  string|null  $key
     * @return mixed
     */
    abstract protected function prop(string $key = null): mixed;
}
