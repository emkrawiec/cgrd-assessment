<?php

namespace Emkrawiec\CgrdAssessment\Core;

use Emkrawiec\CgrdAssessment\Framework\AuthStrategy;
use Emkrawiec\CgrdAssessment\Framework\AuthResult;
use Emkrawiec\CgrdAssessment\Framework\NotAuthenticatedException;

class UserRegistryWithNativePhpSessionsAuthStrategy implements AuthStrategy
{
    /**
     * @param array{id: int, username: string, password: string}[] $users
     */
    public function __construct(
        private array $users
    ) {
    }


    public function isAuthed(): bool
    {
        $user = $this->findUserBySessionId();
        return isset($user);
    }

    public function resolveUserId(): int
    {
        $maybeUser = $this->findUserBySessionId();
        if (empty($maybeUser)) {
            throw new NotAuthenticatedException();
        }

        return $maybeUser['id'];
    }

    public function login(string $username, string $password): AuthResult
    {
        $maybeUser = $this->findUserByUsername($username);
        if (empty($maybeUser)) {
            return AuthResult::USER_NOT_FOUND;
        }

        if ($maybeUser['password'] !== $password) {
            return AuthResult::BAD_CREDENTIALS;
        }

        $this->setSession($maybeUser);
        return AuthResult::OK;
    }

    public function logout(): void
    {
        $this->safeStartSession();
        session_destroy();
    }

    /**
     * @return array{id: int, username: string, password: string}|null
     */
    private function findUserByUsername(string $username): ?array
    {
        return collect($this->users)->first(fn ($user) => $user['username'] === $username);
    }

    /**
     * @return array{id: int, username: string, password: string}|null
     */
    private function findUserBySessionId(): ?array
    {
        $this->safeStartSession();
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return collect($this->users)->first(fn ($user) => $_SESSION['user_id'] === $user['id']);
    }

    /**
     * @param array{id: int, username: string, password: string} $maybeUser
     * @return void
     */
    private function setSession(array $maybeUser): void
    {
        $this->safeStartSession();
        $_SESSION['user_id'] = $maybeUser['id'];
    }

    private function safeStartSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
