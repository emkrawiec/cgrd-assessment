<?php

namespace Emkrawiec\CgrdAssessment\Framework;

interface RouteDefsResolver
{
    /**
     * @param Controller[] $controllers
     * @return RouteDefinition[]
     */
    public function resolve(array $controllers): array;
}
