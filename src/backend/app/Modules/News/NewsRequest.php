<?php

namespace Emkrawiec\CgrdAssessment\Modules\News;

readonly class NewsRequest
{
    private function __construct(
        private string $title,
        private string $content,
    ) {
    }

    /**
     * @param array{title: string, content: string} $news
     */
    public static function fromArray(array $news): NewsRequest
    {
        return new self(
            $news['title'],
            $news['content'],
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
