<?php

declare(strict_types=1);

namespace Duyler\Aspect;

use Duyler\ActionBus\Build\Context;
use Duyler\Aspect\Build\AttributeHandler;
use Duyler\Aspect\State\AfterStateHandler;
use Duyler\Aspect\State\AroundStateHandler;
use Duyler\Aspect\State\BeforeStateHandler;
use Duyler\Aspect\State\SuspendStateHandler;
use Duyler\Aspect\State\ThrowingStateHandler;
use Duyler\DependencyInjection\ContainerInterface;
use Duyler\Builder\Loader\LoaderServiceInterface;
use Duyler\Builder\Loader\PackageLoaderInterface;

class Loader implements PackageLoaderInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {}

    public function load(LoaderServiceInterface $loaderService): void
    {
        $beforeStateHandler = $this->container->get(BeforeStateHandler::class);
        $afterStateHandler = $this->container->get(AfterStateHandler::class);
        $aroundStateHandler = $this->container->get(AroundStateHandler::class);
        $throwingStateHandler = $this->container->get(ThrowingStateHandler::class);
        $suspendStateHandler = $this->container->get(SuspendStateHandler::class);

        $loaderService->addStateHandler($beforeStateHandler);
        $loaderService->addStateHandler($afterStateHandler);
        $loaderService->addStateHandler($aroundStateHandler);
        $loaderService->addStateHandler($throwingStateHandler);
        $loaderService->addStateHandler($suspendStateHandler);
        $loaderService->addStateContext(
            new Context(
                [
                    BeforeStateHandler::class,
                    AfterStateHandler::class,
                    AroundStateHandler::class,
                    ThrowingStateHandler::class,
                    SuspendStateHandler::class,
                ],
            ),
        );

        $loaderService->addAttributeHandler($this->container->get(AttributeHandler::class));
    }
}
