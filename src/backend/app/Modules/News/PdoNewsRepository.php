<?php

namespace Emkrawiec\CgrdAssessment\Modules\News;

readonly class PdoNewsRepository implements NewsRepository
{
    public function __construct(
        private \PDO $pdo
    ) {
    }

    public function exitsById(NewsId $id): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM news WHERE id = :id');
        $stmt->execute(array(
            'id' => $id->getPersistableId()
        ));

        return $stmt->rowCount() > 0;
    }

    public function findById(NewsId $id): ?News
    {
        $stmt = $this->pdo->prepare('SELECT * FROM news WHERE id = :id');
        $stmt->execute(array(
            'id' => $id->getPersistableId()
        ));

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        /** @var array{id: int, owner_id: int, title: string, content: string} $row */
        $typed_row = $row;

        return News::fromDb($typed_row);
    }

    /**
     * @return News[]
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM news');
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (!$rows) {
            return array();
        }

        return array_map(function(array $row) {
            /** @var array{id: int, owner_id: int, title: string, content: string} $typed_row */
            $typed_row = $row;
            return News::fromDb($typed_row);
        }, $rows);
    }

    public function create(News $news): NewsId
    {
        $stmt = $this->pdo->prepare('INSERT INTO news (title, content, owner_id) VALUES (:title, :content, :owner_id)');
        $stmt->execute(array(
            'title' => $news->getTitle(),
            'content' => $news->getContent(),
            'owner_id' => $news->getOwnerId()
        ));

        return NewsId::from(intval($this->pdo->lastInsertId()));
    }

    public function edit(News $news): void
    {
        $stmt = $this->pdo->prepare('UPDATE news SET title = :title, content = :content WHERE id = :id');
        $stmt->execute(array(
            'id' => $news->getId(),
            'title' => $news->getTitle(),
            'content' => $news->getContent()
        ));
    }

    public function delete(NewsId $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM news WHERE id = :id');
        $stmt->execute(array(
            'id' => $id->getPersistableId()
        ));
    }

}
