<?php

declare(strict_types=1);

namespace Meilisearch\Bundle\DocumentProvider;

use Doctrine\Persistence\ManagerRegistry;

final class OrmEntityProvider implements DocumentProviderInterface
{
    private ManagerRegistry $managerRegistry;

    /**
     * @var non-empty-string
     */
    private string $index;

    /**
     * @var class-string
     */
    private string $className;

    /**
     * @param non-empty-string $index
     */
    public function __construct(ManagerRegistry $managerRegistry, string $index, string $className)
    {
        $this->managerRegistry = $managerRegistry;
        $this->index = $index;
        $this->className = $className;
    }

    public function getIndex(): string
    {
        return $this->index;
    }

    public function provide(int $offset, int $limit): iterable
    {
        $manager = $this->managerRegistry->getManagerForClass($this->className);
        $repository = $manager->getRepository($this->className);
        $classMetadata = $manager->getClassMetadata($this->className);
        $entityIdentifiers = $classMetadata->getIdentifierFieldNames();
        $sortByAttrs = array_combine($entityIdentifiers, array_fill(0, count($entityIdentifiers), 'ASC'));

        return $repository->findBy([], $sortByAttrs, $limit, $offset);
    }
}
