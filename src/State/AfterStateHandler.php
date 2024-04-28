<?php

declare(strict_types=1);

namespace Duyler\Aspect\State;

use Closure;
use Duyler\Aspect\AdviceStorage;
use Duyler\ActionBus\Contract\State\ActionAfterStateHandlerInterface;
use Duyler\ActionBus\Formatter\IdFormatter;
use Duyler\ActionBus\State\Service\StateActionAfterService;
use Duyler\ActionBus\State\StateContext;

class AfterStateHandler implements ActionAfterStateHandlerInterface
{
    public function __construct(private AdviceStorage $adviceStorage) {}

    public function handle(StateActionAfterService $stateService, StateContext $context): void
    {
        foreach ($this->adviceStorage->getAfter(IdFormatter::format($stateService->getAction()->id)) as $advice) {
            if ($advice->advice instanceof Closure) {
                ($advice->advice)($stateService->getResultData(), $stateService->getAction());
                return;
            }
            $stateService->getContainer()->bind($advice->bind);
            $stateService->getContainer()->addProviders($advice->providers);
            $adviceHandler = $stateService->getContainer()->get($advice->advice);
            $adviceHandler($stateService->getResultData(), $stateService->getAction());
        }
    }

    public function observed(StateContext $context): array
    {
        return [];
    }
}
