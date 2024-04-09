<?php

declare(strict_types=1);

namespace Duyler\Aspect\State;

use Closure;
use Duyler\Aspect\AdviceStorage;
use Duyler\EventBus\Contract\State\ActionAfterStateHandlerInterface;
use Duyler\EventBus\State\Service\StateActionAfterService;
use Duyler\EventBus\State\StateContext;

class AfterStateHandler implements ActionAfterStateHandlerInterface
{
    public function __construct(private AdviceStorage $adviceStorage) {}

    public function handle(StateActionAfterService $stateService, StateContext $context): void
    {
        foreach ($this->adviceStorage->getAfter($stateService->getAction()->id) as $advice) {
            if ($advice->advice instanceof Closure) {
                ($advice->advice)($stateService->getResultData(), $stateService->getAction());
                return;
            }
            $stateService->getContainer()->bind($advice->bind);
            $stateService->getContainer()->addProviders($advice->providers);
            $adviceHandler = $stateService->getContainer()->get($advice->advice);
            $adviceHandler->advice($stateService->getResultData(), $stateService->getAction());
        }
    }

    public function observed(StateContext $context): array
    {
        return [];
    }
}
