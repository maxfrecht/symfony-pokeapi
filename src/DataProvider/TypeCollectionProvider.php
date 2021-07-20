<?php


namespace App\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\CollectionDataProvider;
use App\Entity\Type;
use App\Pokedex\TypeApi;
use Doctrine\Persistence\ManagerRegistry;

class TypeCollectionProvider extends CollectionDataProvider
{
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Type::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $this->typeApi->getAllObjectTypes();
        return parent::getCollection($resourceClass, $operationName, $context);
    }

    public function __construct(ManagerRegistry $managerRegistry, iterable $collectionExtensions = [], private TypeApi $typeApi)
    {
        parent::__construct($managerRegistry, $collectionExtensions);
    }
}