<?php

namespace Emkrawiec\CgrdAssessment\Framework;

interface FrontController
{
    /**
     * @param RouteDefinition $routeDef
     * @return void
     */
    public function addRoute(RouteDefinition $routeDef): void;

    public function dispatch(): void;
}
