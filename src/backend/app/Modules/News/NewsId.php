<?php

namespace Emkrawiec\CgrdAssessment\Modules\News;

use Webmozart\Assert\Assert;

readonly class NewsId implements \Stringable
{
    private function __construct(
        private int $id
    ) {
    }

    public static function from(int $id): NewsId
    {
        Assert::integer($id);

        return new self($id);
    }

    public function getPersistableId(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
