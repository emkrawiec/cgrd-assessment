<?php

namespace Emkrawiec\CgrdAssessment\Framework;

#[\Attribute]
readonly class Route
{
    /**
     * @param string $path
     * @param AllowedHttpMethods $method
     * @param string[] $middlewares
     */
    public function __construct(
        private string $path,
        private AllowedHttpMethods $method,
        private array $middlewares = []
    ) {
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return AllowedHttpMethods
     */
    public function getMethod(): AllowedHttpMethods
    {
        return $this->method;
    }

    /**
     * @return string[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
