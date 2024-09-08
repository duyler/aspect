<?php

declare(strict_types=1);

namespace Duyler\Aspect\State;

use Closure;
use Duyler\EventBus\Contract\State\MainSuspendStateHandlerInterface;
use Duyler\EventBus\State\Service\StateMainSuspendService;
use Duyler\EventBus\State\Suspend;
use Duyler\Aspect\AdviceStorage;
use Duyler\EventBus\Formatter\IdFormatter;
use Duyler\EventBus\State\StateContext;
use Override;

class SuspendStateHandler implements MainSuspendStateHandlerInterface
{
    public function __construct(private AdviceStorage $adviceStorage) {}

    #[Override]
    public function handle(StateMainSuspendService $stateService, StateContext $context): void
    {
        foreach ($this->adviceStorage->getSuspend(IdFormatter::toString($stateService->getActionId())) as $advice) {
            if ($advice->advice instanceof Closure) {
                ($advice->advice)($stateService->getValue(), $stateService->getActionId());
                return;
            }
            $stateService->getActionContainer()->bind($advice->bind);
            $stateService->getActionContainer()->addProviders($advice->providers);
            $adviceHandler = $stateService->getActionContainer()->get($advice->advice);
            $adviceHandler($stateService->getValue(), $stateService->getActionId());
        }
    }

    public function observed(Suspend $suspend, StateContext $context): bool
    {
        return true;
    }
}
