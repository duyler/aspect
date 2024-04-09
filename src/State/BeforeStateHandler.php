<?php

declare(strict_types=1);

namespace Duyler\Aspect\State;

use Closure;
use Duyler\Aspect\AdviceStorage;
use Duyler\EventBus\Contract\State\ActionBeforeStateHandlerInterface;
use Duyler\EventBus\State\Service\StateActionBeforeService;
use Duyler\EventBus\State\StateContext;

class BeforeStateHandler implements ActionBeforeStateHandlerInterface
{
    public function __construct(private AdviceStorage $adviceStorage) {}

    public function handle(StateActionBeforeService $stateService, StateContext $context): void
    {
        foreach ($this->adviceStorage->getBefore($stateService->getAction()->id) as $advice) {
            if ($advice->advice instanceof Closure) {
                ($advice->advice)($stateService->getArgument(), $stateService->getAction());
                return;
            }
            $stateService->getContainer()->bind($advice->bind);
            $stateService->getContainer()->addProviders($advice->providers);
            $adviceHandler = $stateService->getContainer()->get($advice->advice);
            $adviceHandler->advice($stateService->getArgument(), $stateService->getAction());
        }
    }

    public function observed(StateContext $context): array
    {
        return [];
    }
}
