<?php

declare(strict_types=1);

namespace Emkrawiec\CgrdAssessment\Core;

use Dotenv\Dotenv;
use Emkrawiec\CgrdAssessment\Framework\AuthMiddleware;
use Emkrawiec\CgrdAssessment\Framework\AuthStrategy;
use Emkrawiec\CgrdAssessment\Framework\Controller;
use Emkrawiec\CgrdAssessment\Framework\Redirect;
use Emkrawiec\CgrdAssessment\Framework\Router;
use Emkrawiec\CgrdAssessment\Framework\TwigViewRenderer;
use Emkrawiec\CgrdAssessment\Framework\ViewRenderer;
use Emkrawiec\CgrdAssessment\Modules\Auth\AuthController;
use Emkrawiec\CgrdAssessment\Modules\News\NewsController;
use Emkrawiec\CgrdAssessment\Modules\News\NewsRepository;
use Emkrawiec\CgrdAssessment\Modules\News\NewsService;
use Emkrawiec\CgrdAssessment\Modules\News\PdoNewsRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class PureDiCompositionRoot implements CompositionRoot
{
    public function bootstrap(): void
    {
        $this->bootstrapEnvVarsFromDotEnv();
        $router = $this->bootstrapRouter();
        $controllers = $this->bootstrapControllers();

        $router->mapRoutes($controllers);
        $router->dispatch();
    }

    /**
     * @return Controller[]
     */
    private function bootstrapControllers(): array
    {
        $authStrategy = $this->resolveAuthStrategy();
        $newsService = $this->resolveNewsService();
        $viewRenderer = $this->resolveViewRenderer();

        return [
            new NewsController(
                $viewRenderer,
                $newsService,
                $authStrategy
            ),
            new AuthController(
                $authStrategy,
                $viewRenderer,
                new Redirect('/news')
            )
        ];
    }

    private function bootstrapRouter(): Router
    {
        return new Router(
            new AttributesRouteDefsResolver($this->resolveMiddlewareRegistry()),
            new FastRouteFrontController(
                new Redirect('/login')
            )
        );
    }

    /**
     * @return AuthMiddleware[]
     */
    private function resolveMiddlewareRegistry(): array
    {
        return array(
            AuthMiddleware::class => new AuthMiddleware(
                $this->resolveAuthStrategy(),
                new Redirect('/login')
            ),
        );
    }

    private function resolveNewsRepository(): NewsRepository
    {
        return new PdoNewsRepository(
            $this->resolvePdo()
        );
    }

    private function resolvePdo(): \PDO
    {
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new \PDO(
            $_ENV['DB_DSN'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            $options
        );
    }

    private function resolveAuthStrategy(): AuthStrategy
    {
        return new UserRegistryWithNativePhpSessionsAuthStrategy(
            [
                [
                    'id' => 1,
                    'username' => 'admin',
                    'password' => 'admin'
                ]
            ]
        );
    }

    private function resolveViewRenderer(): ViewRenderer
    {
        return new TwigViewRenderer();
    }

    private function resolveNewsService(): NewsService
    {
        return new NewsService(
            $this->resolveNewsRepository(),
            $this->resolveLogger()
        );
    }

    private function resolveLogger(): LoggerInterface
    {
        $logger = new Logger('logger');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../../../../log/log.log', Level::Info));
        return $logger;
    }

    private function bootstrapEnvVarsFromDotEnv(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../..');
        $dotenv->load();
    }
}
