<?php


namespace App\DataProvider;


use ApiPlatform\Core\Bridge\Doctrine\Orm\CollectionDataProvider;

class PokemonCollectionProvider extends CollectionDataProvider
{

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Pokemon::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {

        return parent::getCollection($resourceClass, $operationName, $context);
    }
}