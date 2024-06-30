<?php

declare(strict_types=1);

namespace Duyler\Aspect\Build;

use Duyler\ActionBus\Build\Action;
use Duyler\Aspect\AdviceStorage;
use Duyler\Aspect\Build\Attribute\After;
use Duyler\Aspect\Build\Attribute\Around;
use Duyler\Aspect\Build\Attribute\Before;
use Duyler\Aspect\Build\Attribute\Suspend;
use Duyler\Aspect\Build\Attribute\Throwing;
use Duyler\Framework\Build\AttributeHandlerInterface;
use InvalidArgumentException;

class AttributeHandler implements AttributeHandlerInterface
{
    public function __construct(private AdviceStorage $adviceStorage) {}

    public function getAttributeClasses(): array
    {
        return [
            Before::class,
            After::class,
            Around::class,
            Throwing::class,
        ];
    }

    public function handleBefore(Before $before, mixed $item): void
    {
        if (false === $item instanceof Action) {
            $this->throwInvalidTypeException($item);
        }

        $this->adviceStorage->addBefore($item->id, $before);
    }

    public function handleAfter(After $after, mixed $item): void
    {
        if (false === $item instanceof Action) {
            $this->throwInvalidTypeException($item);
        }

        $this->adviceStorage->addAfter($item->id, $after);
    }

    public function handleAround(Around $around, mixed $item): void
    {
        if (false === $item instanceof Action) {
            $this->throwInvalidTypeException($item);
        }

        $this->adviceStorage->addAround($item->id, $around);
    }

    public function handleThrowing(Throwing $throwing, mixed $item): void
    {
        if (false === $item instanceof Action) {
            $this->throwInvalidTypeException($item);
        }

        $this->adviceStorage->addThrowing($item->id, $throwing);
    }

    public function handleSuspend(Suspend $suspend, mixed $item): void
    {
        if (false === $item instanceof Action) {
            $this->throwInvalidTypeException($item);
        }

        $this->adviceStorage->addSuspend($item->id, $suspend);
    }

    private function throwInvalidTypeException(mixed $item): never
    {
        throw new InvalidArgumentException('Item must implement Action ' . gettype($item) . ' given');
    }
}
