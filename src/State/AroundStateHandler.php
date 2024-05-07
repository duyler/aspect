<?php

declare(strict_types=1);

namespace Duyler\Aspect\State;

use Duyler\Aspect\AdviceStorage;
use Duyler\ActionBus\Contract\State\MainBeforeStateHandlerInterface;
use Duyler\ActionBus\Dto\ActionHandlerSubstitution;
use Duyler\ActionBus\Formatter\ActionIdFormatter;
use Duyler\ActionBus\State\Service\StateMainBeforeService;
use Duyler\ActionBus\State\StateContext;

class AroundStateHandler implements MainBeforeStateHandlerInterface
{
    public function __construct(private AdviceStorage $adviceStorage) {}

    public function handle(StateMainBeforeService $stateService, StateContext $context): void
    {
        foreach ($this->adviceStorage->getAround(ActionIdFormatter::toString($stateService->getActionId())) as $advice) {
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
