<?php

namespace App\Entity;

use App\Repository\PokemonAttackRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=PokemonAttackRepository::class)
 */
class PokemonAttack
{
    /**
     *
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="attack")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $pokemon;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Attack::class, inversedBy="pokemonAttacks")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $attack;

    /**
     * PokemonAttack constructor.
     * @param $pokemon
     * @param $attack
     */
    public function __construct(Pokemon $pokemon, Attack $attack)
    {
        $this->pokemon = $pokemon;
        $this->attack = $attack;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function getAttack(): ?Attack
    {
        return $this->attack;
    }
}
