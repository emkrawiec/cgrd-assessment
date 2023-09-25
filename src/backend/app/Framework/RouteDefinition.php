<?php

namespace Emkrawiec\CgrdAssessment\Framework;

readonly class RouteDefinition
{
    private mixed $handler;
    private string $path;
    private AllowedHttpMethods $httpMethod;
    /**
     * @var Middleware[]
     */
    private array $middlewares;

    /**
     * @param mixed $handler
     * @param string $path
     * @param AllowedHttpMethods $httpMethod
     * @param Middleware[] $middlewares
     */
    public function __construct(
        mixed $handler,
        string $path,
        AllowedHttpMethods $httpMethod,
        array $middlewares
    ) {
        $this->handler = $handler;
        $this->path = $path;
        $this->httpMethod = $httpMethod;
        $this->middlewares = $middlewares;
    }

    /**
     * @return AllowedHttpMethods
     */
    public function getHttpMethod(): AllowedHttpMethods
    {
        return $this->httpMethod;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return callable
     */
    public function resolveHandler(): callable
    {
        /** @var callable $handler */
        $handler = $this->handler;
        foreach (array_reverse($this->middlewares) as $middleware) {
            $handler = fn () => $middleware->handle($handler);
        }

        return $handler;
    }
}
