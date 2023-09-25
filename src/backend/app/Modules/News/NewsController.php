<?php

declare(strict_types=1);

namespace Emkrawiec\CgrdAssessment\Modules\News;

use Emkrawiec\CgrdAssessment\Core\BaseController;
use Emkrawiec\CgrdAssessment\Framework\AllowedHttpMethods;
use Emkrawiec\CgrdAssessment\Framework\AuthMiddleware;
use Emkrawiec\CgrdAssessment\Framework\AuthStrategy;
use Emkrawiec\CgrdAssessment\Framework\Route;
use Emkrawiec\CgrdAssessment\Framework\ViewRenderer;
use Emkrawiec\CgrdAssessment\Views\Notification;

readonly class NewsController extends BaseController
{
    private NewsService $newsService;
    private AuthStrategy $authStrategy;

    public function __construct(ViewRenderer $viewRenderer, NewsService $newsService, AuthStrategy $authStrategy)
    {
        parent::__construct($viewRenderer);
        $this->newsService = $newsService;
        $this->authStrategy = $authStrategy;
    }

    #[Route(path: "/news", method: AllowedHttpMethods::GET, middlewares: [AuthMiddleware::class])]
    public function newsList(): void
    {
        $this->render('@views/news.twig', $this->resolveNewsListViewContext());
    }

    #[Route(path: "/news", method: AllowedHttpMethods::POST, middlewares: [AuthMiddleware::class])]
    public function handleNewsMutation(): void
    {
        $method = $_POST['_method'] ?? null;

        match ($method) {
            'PUT' => $this->editNews(),
            'DELETE' => $this->deleteNews(),
            default => $this->createNews()
        };
    }

    private function createNews(): void
    {
        $ownerId = $this->authStrategy->resolveUserId();
        $news = array(
            'title' => $_POST['title'] ?? null,
            'content' => $_POST['content'] ?? null,
        );

        if (!$this->isNewsPayloadValid($news)) {
            $this->renderInvalidPayloadView();
            return;
        }

        $this->newsService->createNews($ownerId, NewsRequest::fromArray($news));

        $this->renderViewWithNotification('@views/news.twig', $this->resolveNewsListViewContext(), Notification::success('News was successfully created!'));
    }

    private function editNews(): void
    {
        $id = $_POST['id'] ?? null;
        $news = array(
            'title' => $_POST['title'] ?? null,
            'content' => $_POST['content'] ?? null,
        );

        if (!$this->isNewsPayloadValid($news) && !empty($id)) {
            $this->renderInvalidPayloadView();
            return;
        }

        $newsId = NewsId::from(intval($id));
        $newsRequest = NewsRequest::fromArray($news);
        $this->newsService->editNews($newsId, $newsRequest);

        $this->renderViewWithNotification('@views/news.twig', $this->resolveNewsListViewContext(), Notification::success('News was successfully changed!'));
    }

    private function deleteNews(): void
    {
        $id = $_POST['id'] ?? null;
        $newsId = NewsId::from(intval($id));

        $this->newsService->deleteNews($newsId);

        $this->renderViewWithNotification('@views/news.twig', $this->resolveNewsListViewContext(), Notification::success('News was deleted!'));
    }

    private function renderInvalidPayloadView(): void
    {
        $this->renderViewWithNotification('@views/news.twig', array(), Notification::error('Invalid news data!'));
    }

    /**
     * @return array{page_name: string, news_list: array{id: int|null, title: string, content: string}[]}
     */
    private function resolveNewsListViewContext(): array
    {
        $news = $this->newsService->getNews();

        return array(
            'page_name' => 'news',
            'news_list' => array_map(fn ($news) => array(
                'id' => $news->getId(),
                'title' => $news->getTitle(),
                'content' => $news->getContent(),
            ), $news)
        );
    }

    /**
     * @param array{title?: string, content?: string} $news
     */
    private function isNewsPayloadValid(array $news): bool
    {
        return !empty($news['title']) && !empty($news['content']);
    }
}
