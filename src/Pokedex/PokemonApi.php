<?php


namespace App\Pokedex;


use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonApi
{
    private HttpClientInterface $client;

    /**
     * PokemonApi constructor.
     * @param HttpClientInterface $client
     */
    public function __construct(private PokemonRepository $pokemonRepository, private EntityManagerInterface $em)
    {
        $this->client = HttpClient::createForBaseUri('https://pokeapi.co/api/v2/');
    }

    public function getPokemons(int $offset = 0, int $limit = 300): ?array
    {
        try {
            $response = $this->client->request('GET', 'pokemon/', [
                'query' => [
                    'offset' => $offset,
                    'limit' => $limit
                ]
            ]);
        } catch (TransportExceptionInterface $e) {
            echo $e->getMessage();
        }

        try {
            $data = $response->toArray();
            $pokemons = [];
            foreach ($data['results'] as $pokemon) {
                if (!preg_match('/([0-9]+)\/?$/', $pokemon['url'], $matches)) {
                    throw new \RuntimeException('Cannot match given url for pokemon ' . $pokemon['name']);
                }
                $pokemonId = intval($matches[0]);
                $responsePoke = $this->client->request('GET', 'pokemon/' . $pokemonId);
                $dataPoke = $responsePoke->toArray();

                $pokemon = [
                    'pokemonId' => $dataPoke['id'],
                    'weight' => $dataPoke['weight'],
                    'height' => $dataPoke['height'],
                    'base_exp' => $dataPoke['base_experience'],
                    'pokedex_order' => $dataPoke['order']
                ];

                $pokemons[] = $this->convertPokeapiToPokemon($pokemon);
            }
        } catch (ClientExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            echo $e->getMessage();
        }
    }

    public function convertPokeapiToPokemon(array $data): Pokemon {
        $pokemon = $this->pokemonRepository->findOneBy([
           'pokeapiId' => $data['pokemonId']
        ]);

        if(null === $pokemon) {
            $pokemon = new Pokemon($data['pokemonId']);
            $pokemon->setName($data['name']);
            $pokemon->setPokeapiId($data['pokemonId']);
            $pokemon->setWeight($data['weight']);
            $pokemon->setHeight($data['height']);
            $pokemon->setBaseExperience($data['base_experience']);
            $pokemon->setPokedexOrder($data['pokedex_order']);

            $this->em->persist($pokemon);
            $this->em->flush();
        }
        return $pokemon;
    }
}