<?php

namespace Emkrawiec\CgrdAssessment\Modules\News;

interface NewsRepository
{
    public function exitsById(NewsId $id): bool;

    public function findById(NewsId $id): ?News;

    /**
     * @return News[]
     */
    public function findAll(): array;

    public function create(News $news): NewsId;

    public function edit(News $news): void;

    public function delete(NewsId $id): void;

}
