<?php

declare(strict_types=1);

namespace Duyler\Aspect\State;

use Duyler\EventBus\Build\ActionHandlerSubstitution;
use Duyler\EventBus\Contract\State\MainBeforeStateHandlerInterface;
use Duyler\EventBus\Formatter\IdFormatter;
use Duyler\EventBus\State\Service\StateMainBeforeService;
use Duyler\EventBus\State\StateContext;
use Duyler\Aspect\AdviceStorage;

class AroundStateHandler implements MainBeforeStateHandlerInterface
{
    public function __construct(private AdviceStorage $adviceStorage) {}

    public function handle(StateMainBeforeService $stateService, StateContext $context): void
    {
        foreach ($this->adviceStorage->getAround(IdFormatter::toString($stateService->getActionId())) as $advice) {
            $stateService->substituteHandler(
                new ActionHandlerSubstitution(
                    actionId: $stateService->getActionId(),
                    handler: $advice->advice,
                    bind: $advice->bind,
                    providers: $advice->providers,
                ),
            );
        }
    }

    public function observed(StateContext $context): array
    {
        return [];
    }
}
