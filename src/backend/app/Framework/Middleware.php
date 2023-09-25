<?php

namespace Emkrawiec\CgrdAssessment\Framework;

interface Middleware
{
    public function handle(callable $next): void;
}
