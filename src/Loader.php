<?php

declare(strict_types=1);

namespace Duyler\Aspect;

use Duyler\Aspect\Build\AttributeHandler;
use Duyler\Aspect\State\AfterStateHandler;
use Duyler\Aspect\State\AroundStateHandler;
use Duyler\Aspect\State\BeforeStateHandler;
use Duyler\Aspect\State\ThrowingStateHandler;
use Duyler\DependencyInjection\ContainerInterface;
use Duyler\ActionBus\Dto\Context;
use Duyler\Framework\Loader\LoaderServiceInterface;
use Duyler\Framework\Loader\PackageLoaderInterface;

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

        $loaderService->addStateHandler($beforeStateHandler);
        $loaderService->addStateHandler($afterStateHandler);
        $loaderService->addStateHandler($aroundStateHandler);
        $loaderService->addStateHandler($throwingStateHandler);
        $loaderService->addStateContext(
            new Context(
                [
                    BeforeStateHandler::class,
                    AfterStateHandler::class,
                    AroundStateHandler::class,
                    ThrowingStateHandler::class,
                ],
            ),
        );

        $loaderService->addAttributeHandler($this->container->get(AttributeHandler::class));
    }
}
