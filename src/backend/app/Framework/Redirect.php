<?php

namespace Emkrawiec\CgrdAssessment\Framework;

readonly class Redirect
{
    public function __construct(
        private string $url
    ) {
    }

    public function redirect(): void
    {
        header("Location: {$this->url}");
    }
}
