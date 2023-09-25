<?php

namespace Emkrawiec\CgrdAssessmentTests\Modules\News;

use Emkrawiec\CgrdAssessment\Modules\News\News;
use Emkrawiec\CgrdAssessment\Modules\News\NewsId;
use Emkrawiec\CgrdAssessment\Modules\News\NewsRepository;

class StubNewsRepository implements NewsRepository
{
    private static int $id = 1;
    private array $news = [];

    public function exitsById(NewsId $id): bool
    {
        return collect($this->news)
            ->contains(fn(News $newsEntry) => $newsEntry->getId() === $id->getPersistableId());
    }

    public function findById(NewsId $id): ?News
    {
        return collect($this->news)
            ->first(fn(News $newsEntry) => $newsEntry->getId() === $id->getPersistableId());
    }

    public function findAll(): array
    {
        return $this->news;
    }

    public function create(News $news): NewsId
    {
        $news = News::fromDb(
            array(
                'id' => ++self::$id,
                'owner_id' => $news->getOwnerId(),
                'title' => $news->getTitle(),
                'content' => $news->getContent(),
            )
        );

        $this->news[] = $news;

        return NewsId::from($news->getId());
    }

    public function edit(News $news): void
    {
        $newsCollection = collect($this->news);
        $newsIndex = $newsCollection
            ->search(fn(News $newsEntry) => $newsEntry->getId() === $news->getId());

        $news = $this->news[$newsIndex];
        $news->hydrateFromPrimitives($news->getTitle(), $news->getContent());

        $replaced = $newsCollection->replace([$newsIndex => $news]);

        $this->news = $replaced->toArray();
    }

    public function delete(NewsId $id): void
    {
        $newsCollection = collect($this->news);
        $newsIndex = $newsCollection
            ->search(fn(News $newsEntry) => $newsEntry->getId() === $id->getPersistableId());

        $newsCollection->forget($newsIndex);
        $this->news = $newsCollection->toArray();
    }
}