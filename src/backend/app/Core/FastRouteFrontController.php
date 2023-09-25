<?php

namespace Emkrawiec\CgrdAssessment\Core;

use Emkrawiec\CgrdAssessment\Framework\FrontController;
use Emkrawiec\CgrdAssessment\Framework\Redirect;
use Emkrawiec\CgrdAssessment\Framework\RouteDefinition;
use FastRoute;

class FastRouteFrontController implements FrontController
{
    /**
     * @var RouteDefinition[]
     */
    private array $routeDefs = [];

    public function __construct(
        private readonly Redirect $notFoundRedirect
    ) {
    }

    public function addRoute(RouteDefinition $routeDef): void
    {
        $this->routeDefs[] = $routeDef;
    }

    public function dispatch(): void
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $collector) {
            foreach ($this->routeDefs as $routeDef) {
                $collector->addRoute($routeDef->getHttpMethod()->name, $routeDef->getPath(), $routeDef->resolveHandler());
            }
        });

        $this->initDispatcher($dispatcher);
    }

    private function initDispatcher(FastRoute\Dispatcher $dispatcher): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                $this->notFoundRedirect->redirect();
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                call_user_func($handler, $vars);
                break;
        }
    }
}
