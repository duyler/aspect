<?php

declare(strict_types=1);

namespace Duyler\Aspect\State;

use Closure;
use Duyler\Aspect\AdviceStorage;
use Duyler\ActionBus\Contract\State\ActionThrowingStateHandlerInterface;
use Duyler\ActionBus\Formatter\IdFormatter;
use Duyler\ActionBus\State\Service\StateActionThrowingService;
use Duyler\ActionBus\State\StateContext;
use Override;

class ThrowingStateHandler implements ActionThrowingStateHandlerInterface
{
    public function __construct(private AdviceStorage $adviceStorage) {}

    #[Override]
    public function handle(StateActionThrowingService $stateService, StateContext $context): void
    {
        foreach ($this->adviceStorage->getThrowing(IdFormatter::format($stateService->getAction()->id)) as $advice) {
            if ($advice->advice instanceof Closure) {
                ($advice->advice)($stateService->getException(), $stateService->getAction());
                return;
            }
            $stateService->getContainer()->bind($advice->bind);
            $stateService->getContainer()->addProviders($advice->providers);
            $adviceHandler = $stateService->getContainer()->get($advice->advice);
            $adviceHandler($stateService->getException(), $stateService->getAction());
        }
    }

    #[Override]
    public function observed(StateContext $context): array
    {
        return [];
    }
}
