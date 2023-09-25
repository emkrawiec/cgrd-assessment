<?php

namespace Emkrawiec\CgrdAssessment\Framework;

use Emkrawiec\CgrdAssessment\Modules\Auth\UserId;

interface AuthStrategy
{
    public function isAuthed(): bool;

    /**
     * @return int
     */
    public function resolveUserId(): int;

    /**
     * @param string $username
     * @param string $password
     */
    public function login(string $username, string $password): AuthResult;

    public function logout(): void;
}
