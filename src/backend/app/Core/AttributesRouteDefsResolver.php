<?php

namespace Emkrawiec\CgrdAssessment\Core;

use Emkrawiec\CgrdAssessment\Framework\Middleware;
use Emkrawiec\CgrdAssessment\Framework\Route;
use Emkrawiec\CgrdAssessment\Framework\RouteDefinition;
use Emkrawiec\CgrdAssessment\Framework\RouteDefsResolver;

readonly class AttributesRouteDefsResolver implements RouteDefsResolver
{
    /**
     * @param Middleware[] $middlewaresRegistry
     */
    public function __construct(
        private array $middlewaresRegistry
    ) {
    }

    public function resolve(array $controllers): array
    {
        /** @var RouteDefinition[] $routeDefs */
        $routeDefs = [];

        foreach ($controllers as $controller) {
            try {
                $reflectedController = new \ReflectionClass($controller::class);
                $methods = $reflectedController->getMethods();

                foreach ($methods as $method) {
                    $routes = $method->getAttributes(Route::class);

                    foreach ($routes as $route) {
                        $route = $route->newInstance();

                        $routeDefs[] = new RouteDefinition(
                            array($controller, $method->getName()),
                            $route->getPath(),
                            $route->getMethod(),
                            $this->resolveMiddlewares($route->getMiddlewares())
                        );
                    }
                }

            } catch (\ReflectionException $e) {
                throw new \RuntimeException($e->getMessage());
            }
        }

        return $routeDefs;
    }

    /**
     * @param array<string> $middlewaresIds
     * @return Middleware[]
     */
    private function resolveMiddlewares(array $middlewaresIds): array
    {
        $middlewares = [];

        foreach ($middlewaresIds as $middlewareId) {
            $maybeMiddleware = $this->middlewaresRegistry[$middlewareId] ?? null;

            if (isset($maybeMiddleware)) {
                $middlewares[$middlewareId] = $maybeMiddleware;
            } else {
                throw new \UnexpectedValueException("Middleware with id: $middlewareId not found");
            }
        }

        return $middlewares;
    }
}
