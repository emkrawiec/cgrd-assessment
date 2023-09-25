<?php

namespace Emkrawiec\CgrdAssessment\Framework;

final readonly class AuthMiddleware implements Middleware
{
    /**
     * @param AuthStrategy $authStrategy
     * @param Redirect $unauthorizedRedirect
     */
    public function __construct(
        private AuthStrategy $authStrategy,
        private Redirect $unauthorizedRedirect
    ) {
    }

    public function handle(callable $next): void
    {
        $isUserAuthed = $this->authStrategy->isAuthed();

        if ($isUserAuthed) {
            $next();
        } else {
            $this->unauthorizedRedirect->redirect();
        }
    }
}
