<?php

namespace Emkrawiec\CgrdAssessment\Modules\News;

class News
{
    public function __construct(
        private readonly ?int $id,
        private readonly int  $ownerId,
        private string        $title,
        private string        $content,
    ) {
    }

    public static function createNew(
        int    $ownerId,
        string $title,
        string $content,
    ): self {
        return new self(
            null,
            $ownerId,
            $title,
            $content,
        );
    }

    /**
     * @param array{id: int, owner_id: int, title: string, content: string} $row
     * @return News
     */
    public static function fromDb(array $row): News
    {
        return new self(
            $row['id'],
            $row['owner_id'],
            $row['title'],
            $row['content'],
        );
    }

    public function hydrateFromPrimitives(string $title, string $content): void
    {
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * @return int|null
     */
    public function getId(): int|null
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
