<?php

namespace Emkrawiec\CgrdAssessment\Modules\News;

use Psr\Log\LoggerInterface;

readonly class NewsService
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @return News[]
     */
    public function getNews(): array
    {
        return $this->newsRepository->findAll();
    }

    public function createNews(int $ownerId, NewsRequest $request): NewsId
    {
        $news = News::createNew($ownerId, $request->getTitle(), $request->getContent());
        $id = $this->newsRepository->create($news);

        $this->logger->info('News created with.', ['id' => $id]);
        return $id;
    }

    public function editNews(NewsId $id, NewsRequest $request): NewsEditResult
    {
        $maybeNews = $this->newsRepository->findById($id);
        if (empty($maybeNews)) {
            return NewsEditResult::NOT_FOUND;
        }

        $news = $maybeNews;
        $news->hydrateFromPrimitives($request->getTitle(), $request->getContent());
        $this->newsRepository->edit($news);

        $this->logger->info('News edited with.', ['id' => $id]);

        return NewsEditResult::OK;
    }

    public function deleteNews(NewsId $id): NewsDeleteResult
    {
        if (!$this->newsRepository->exitsById($id)) {
            return NewsDeleteResult::NOT_FOUND;
        }

        $this->newsRepository->delete($id);
        $this->logger->info('News deleted.', ['id' => $id]);

        return NewsDeleteResult::OK;
    }
}
