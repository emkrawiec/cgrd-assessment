<?php

namespace Emkrawiec\CgrdAssessment\Framework;

interface ViewRenderer
{
    /**
     * @param string $viewName
     * @param array<mixed, mixed> $viewContext
     * @return void
     */
    public function render(string $viewName, array $viewContext = array()): void;
}
