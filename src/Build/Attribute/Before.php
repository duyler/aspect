<?php

declare(strict_types=1);

namespace Duyler\Aspect\Build\Attribute;

use Closure;
use Duyler\Aspect\Build\AttributeHandler;
use Duyler\Framework\Build\AttributeHandlerInterface;
use Duyler\Framework\Build\AttributeInterface;

class Before implements AttributeInterface
{
    public function __construct(
        public readonly string|Closure $advice,
        public readonly array $bind = [],
        public readonly array $providers = [],
    ) {}

    /** @param AttributeHandler $handler */
    public function accept(AttributeHandlerInterface $handler, mixed $item): void
    {
        $handler->handleBefore($this, $item);
    }
}
