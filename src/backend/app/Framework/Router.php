<?php

declare(strict_types=1);

namespace Emkrawiec\CgrdAssessment\Framework;

readonly class Router
{
    public function __construct(
        private RouteDefsResolver $routeDefsResolver,
        private FrontController $frontController,
    ) {
    }

    /**
     * @param Controller[] $controllers
     * @return void
     */
    public function mapRoutes(array $controllers): void
    {
        $routeDefs = $this->routeDefsResolver->resolve($controllers);
        foreach ($routeDefs as $def) {
            $this->frontController->addRoute($def);
        }
    }

    public function dispatch(): void
    {
        $this->frontController->dispatch();
    }
}
