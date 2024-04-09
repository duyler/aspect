<?php

declare(strict_types=1);

namespace Duyler\Aspect;

use Duyler\Aspect\Build\Attribute\After;
use Duyler\Aspect\Build\Attribute\Around;
use Duyler\Aspect\Build\Attribute\Before;
use Duyler\Aspect\Build\Attribute\Throwing;

class AdviceStorage
{
    /** @var array<string, Before[]> */
    private array $before = [];
    /** @var array<string, After[]> */
    private array $after = [];
    /** @var array<string, Around[]> */
    private array $around = [];
    /** @var array<string, Throwing[]> */
    private array $throwing = [];

    public function addBefore(string $actionId, Before $before): void
    {
        $this->before[$actionId][] = $before;
    }

    public function addAfter(string $actionId, After $after): void
    {
        $this->after[$actionId][] = $after;
    }

    public function addAround(string $actionId, Around $around): void
    {
        $this->around[$actionId][] = $around;
    }

    public function addThrowing(string $actionId, Throwing $throwing): void
    {
        $this->throwing[$actionId][] = $throwing;
    }

    public function getBefore(string $actionId): array
    {
        return $this->before[$actionId] ?? [];
    }

    public function getAfter(string $actionId): array
    {
        return $this->after[$actionId] ?? [];
    }

    public function getAround(string $actionId): array
    {
        return $this->around[$actionId] ?? [];
    }

    public function getThrowing(string $actionId): array
    {
        return $this->throwing[$actionId] ?? [];
    }
}
