<?php

declare(strict_types=1);

namespace Duyler\Aspect\Build\Attribute;

use Closure;
use Duyler\Aspect\Build\AttributeHandler;
use Duyler\Builder\Build\AttributeHandlerInterface;
use Duyler\Builder\Build\AttributeInterface;

class Throwing implements AttributeInterface
{
    public function __construct(
        public readonly string|Closure $advice,
        public readonly array $bind = [],
        public readonly array $providers = [],
    ) {}

    /** @param AttributeHandler $handler */
    public function accept(AttributeHandlerInterface $handler, mixed $item): void
    {
        $handler->handleThrowing($this, $item);
    }
}
