<?php


namespace App\Pokedex;


use App\Entity\Type;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TypeApi
{
    private HttpClientInterface $client;

    public function __construct(private TypeRepository $typeRepository, private EntityManagerInterface $em)
    {
        $this->client = HttpClient::createForBaseUri('https://pokeapi.co/api/v2/');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getTypes(): ?array
    {
        $response = $this->client->request('GET', 'type');
        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException('Error from Pokeapi.co');
        }
        try {
            $data = $response->toArray();
            $types = [];
            foreach ($data["results"] as $type) {
                if (!preg_match('/([0-9]+)\/?$/', $type['url'], $matches)) {
                    throw new \RuntimeException('Cannot match given url for type ' . $type['name']);
                }
                $types[] = ["name" => $type["name"], "pokeapiId" => intval($matches[0])];
            }
            return $types;
        } catch (ClientExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function convertPokeapiToType(array $a): Type {
        $type = $this->typeRepository->findOneBy([
           'pokeapiId' => $a['pokeapiId'],
        ]);
        if(null === $type) {
            $type= new Type();
            $type->setName($a['name']);
            $type->setPokeapiId($a['pokeapiId']);

            $this->em->persist($type);
            $this->em->flush();
        }
        return $type;
    }

    public function getAllObjectTypes():array {
        $types = $this->getTypes();
        $cleanTypes = [];
        foreach($types as $type) {
            $cleanTypes[] = $this->convertPokeapiToType($type);
        }
        return $cleanTypes;
    }
}