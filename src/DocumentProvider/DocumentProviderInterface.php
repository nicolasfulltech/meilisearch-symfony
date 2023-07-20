<?php

declare(strict_types=1);

namespace Meilisearch\Bundle\DocumentProvider;

interface DocumentProviderInterface
{
    /**
     * @return non-empty-string
     */
    public function getIndex(): string;

    public function provide(int $offset, int $limit): iterable;
}
