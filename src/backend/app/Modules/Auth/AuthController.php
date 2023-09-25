<?php

namespace Emkrawiec\CgrdAssessment\Modules\Auth;

use Emkrawiec\CgrdAssessment\Core\BaseController;
use Emkrawiec\CgrdAssessment\Framework\AllowedHttpMethods;
use Emkrawiec\CgrdAssessment\Framework\AuthResult;
use Emkrawiec\CgrdAssessment\Framework\AuthStrategy;
use Emkrawiec\CgrdAssessment\Framework\Redirect;
use Emkrawiec\CgrdAssessment\Framework\Route;
use Emkrawiec\CgrdAssessment\Framework\ViewRenderer;
use Emkrawiec\CgrdAssessment\Views\Notification;

readonly class AuthController extends BaseController
{
    private AuthStrategy $authStrategy;
    private Redirect $loggedInRedirect;

    public function __construct(AuthStrategy $authStrategy, ViewRenderer $viewRenderer, Redirect $loggedInRedirect)
    {
        parent::__construct($viewRenderer);
        $this->authStrategy = $authStrategy;
        $this->loggedInRedirect = $loggedInRedirect;
    }

    #[Route(path: "/login", method: AllowedHttpMethods::GET)]
    public function loginPage(): void
    {
        if ($this->authStrategy->isAuthed()) {
            $this->loggedInRedirect->redirect();
            return;
        }

        $this->render("@views/login.twig", array());
    }

    #[Route(path: "/login", method: AllowedHttpMethods::POST)]
    public function authorize(): void
    {
        $authResult = $this->authStrategy->login($_POST['username'], $_POST['password']);

        match ($authResult) {
            AuthResult::OK => $this->loggedInRedirect->redirect(),
            AuthResult::USER_NOT_FOUND, AuthResult::BAD_CREDENTIALS => $this->renderViewWithNotification(
                "@views/login.twig",
                array(
                    'page_name' => 'login'
                ),
                Notification::error("Invalid credentials")
            ),
        };
    }

    #[Route(path: "/logout", method: AllowedHttpMethods::GET)]
    public function logout(): void
    {
        $this->authStrategy->logout();
        $redirect = new Redirect('/login');
        $redirect->redirect();
    }

    /**
     * @return AuthStrategy
     */
    public function getAuthStrategy(): AuthStrategy
    {
        return $this->authStrategy;
    }
}
