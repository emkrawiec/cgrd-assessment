<?php

namespace Emkrawiec\CgrdAssessmentTests\Modules\News;

use Emkrawiec\CgrdAssessment\Modules\News\NewsDeleteResult;
use Emkrawiec\CgrdAssessment\Modules\News\NewsEditResult;
use Emkrawiec\CgrdAssessment\Modules\News\NewsId;
use Emkrawiec\CgrdAssessment\Modules\News\NewsRequest;
use Emkrawiec\CgrdAssessment\Modules\News\NewsService;
use PHPUnit\Framework\TestCase;

class NewsServiceTest extends TestCase
{
    /**
     * @test
     */
    public function creatingNewsWorksIfProvidedCorrectInput(): void
    {
        // given
        $repository = new StubNewsRepository();
        $service = new NewsService($repository);

        // when
        $request = NewsRequest::fromArray([
            'title' => 'title1',
            'content' => 'content1',
        ]);
        $id = $service->createNews(1, $request);

        // then
        $news = $repository->findById($id);
        $this->assertNotNull($news);
        $this->assertEquals('title1', $news->getTitle());
        $this->assertEquals('content1', $news->getContent());
    }

    /**
     * @test
     */
    public function editingNewsWorksIfNewsExistsAndProvidedCorrectInput(): void
    {
        // given
        $repository = new StubNewsRepository();
        $service = new NewsService($repository);
        $request = NewsRequest::fromArray([
            'title' => 'title1',
            'content' => 'content1',
        ]);
        $id = $service->createNews(1, $request);

        // when
        $requestToEdit = NewsRequest::fromArray([
            'title' => 'title2',
            'content' => 'content2',
        ]);
        $newsEditResult = $service->editNews($id, $requestToEdit);

        // then
        $news = $repository->findById($id);
        $this->assertEquals(NewsEditResult::OK, $newsEditResult);
        $this->assertNotNull($news);
        $this->assertEquals('title2', $news->getTitle());
        $this->assertEquals('content2', $news->getContent());
    }

    /**
     * @test
     */
    public function editingNewsFailsIfNewsDoesNotExist(): void
    {
        // given
        $repository = new StubNewsRepository();
        $service = new NewsService($repository);

        // when
        $nonExistingId = 1;
        $requestToEdit = NewsRequest::fromArray([
            'title' => 'title2',
            'content' => 'content2',
        ]);
        $newsEditResult = $service->editNews(NewsId::from($nonExistingId), $requestToEdit);

        // then
        $this->assertEquals(NewsEditResult::NOT_FOUND, $newsEditResult);
    }

    /**
     * @test
     */
    public function deletingNewsWorksIfNewsExists(): void
    {
        // given
        $repository = new StubNewsRepository();
        $service = new NewsService($repository);
        $request = NewsRequest::fromArray([
            'title' => 'title1',
            'content' => 'content1',
        ]);
        $id = $service->createNews(1, $request);

        // when
        $newsDeleteResult = $service->deleteNews($id);

        // then
        $this->assertEquals(NewsDeleteResult::OK, $newsDeleteResult);
        $news = $repository->findById($id);
        $this->assertNull($news);
    }

    /**
     * @test
     */
    public function deletingNewsFailsIfNewsDoesNotExist(): void
    {
        // given
        $repository = new StubNewsRepository();
        $service = new NewsService($repository);

        // when
        $nonExistingId = 1;
        $newsDeleteResult = $service->deleteNews(NewsId::from($nonExistingId));

        // then
        $this->assertEquals(NewsDeleteResult::NOT_FOUND, $newsDeleteResult);
    }
}
