<?php

namespace Emkrawiec\CgrdAssessment\Core;

use Emkrawiec\CgrdAssessment\Framework\Controller;
use Emkrawiec\CgrdAssessment\Framework\ViewRenderer;
use Emkrawiec\CgrdAssessment\Views\Notification;

readonly class BaseController implements Controller
{
    public function __construct(
        private ViewRenderer $viewRenderer
    ) {
    }

    /**
     * @param array<mixed,mixed> $viewContext
     */
    protected function render(string $view, array $viewContext): void
    {
        $this->viewRenderer->render($view, $viewContext);
    }

    /**
     * @param array<mixed,mixed> $viewContext
     */
    protected function renderViewWithNotification(string $view, array $viewContext, Notification $notification): void
    {
        $view_context = array_merge($viewContext, array(
            'notification' => $notification->toViewContext()
        ));

        $this->viewRenderer->render($view, $view_context);
    }
}
