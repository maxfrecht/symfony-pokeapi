<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AttackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=AttackRepository::class)
 */
#[ApiResource(
    normalizationContext: ['groups' => ['attack:get']]
)]
class Attack
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $pokeapiId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['attack:get'])]
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(['attack:get'])]
    private $pp;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(['attack:get'])]
    private $accuracy;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(['attack:get'])]
    private $power;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="attacks")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=PokemonAttack::class, mappedBy="attack")
     * @MaxDepth(1)
     */
    private $pokemonAttacks;

    public function __construct()
    {
        $this->pokemonAttacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokeapiId(): ?int
    {
        return $this->pokeapiId;
    }

    public function setPokeapiId(int $pokeapiId): self
    {
        $this->pokeapiId = $pokeapiId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPp(): ?int
    {
        return $this->pp;
    }

    public function setPp(int $pp): self
    {
        $this->pp = $pp;

        return $this;
    }

    public function getAccuracy(): ?int
    {
        return $this->accuracy;
    }

    public function setAccuracy(int $accuracy): self
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(int $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|PokemonAttack[]
     */
    public function getPokemonAttacks(): Collection
    {
        return $this->pokemonAttacks;
    }

    public function addPokemonAttack(PokemonAttack $pokemonAttack): self
    {
        if (!$this->pokemonAttacks->contains($pokemonAttack)) {
            $this->pokemonAttacks[] = $pokemonAttack;
            $pokemonAttack->setAttack($this);
        }

        return $this;
    }

    public function removePokemonAttack(PokemonAttack $pokemonAttack): self
    {
        if ($this->pokemonAttacks->removeElement($pokemonAttack)) {
            // set the owning side to null (unless already changed)
            if ($pokemonAttack->getAttack() === $this) {
                $pokemonAttack->setAttack(null);
            }
        }

        return $this;
    }


}
