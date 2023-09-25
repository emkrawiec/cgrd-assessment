<?php

declare(strict_types=1);

namespace Emkrawiec\CgrdAssessment\App;

use Emkrawiec\CgrdAssessment\Core\PureDiCompositionRoot;

class App
{
    public static function bootstrap(): void
    {
        $compositionRoot = new PureDiCompositionRoot();
        $compositionRoot->bootstrap();
    }
}
