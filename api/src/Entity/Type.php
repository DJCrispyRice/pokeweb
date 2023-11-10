<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\ManyToMany(targetEntity: self::class)]
    #[ORM\JoinTable(name: 'type_strengths')]
    private Collection $strength;

    #[ORM\ManyToMany(targetEntity: self::class)]
    #[ORM\JoinTable(name: 'type_weakness')]
    private Collection $weakness;

    #[ORM\ManyToMany(targetEntity: self::class)]
    #[ORM\JoinTable(name: 'type_uselessness')]
    private Collection $useless;

    #[ORM\ManyToMany(targetEntity: Pokemon::class, mappedBy: 'types')]
    private Collection $pokemons;

    public function __construct()
    {
        $this->strength = new ArrayCollection();
        $this->weakness = new ArrayCollection();
        $this->useless = new ArrayCollection();
        $this->pokemons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getStrength(): Collection
    {
        return $this->strength;
    }

    public function addStrength(self $strength): static
    {
        if (!$this->strength->contains($strength)) {
            $this->strength->add($strength);
        }

        return $this;
    }

    public function removeStrength(self $strength): static
    {
        $this->strength->removeElement($strength);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getWeakness(): Collection
    {
        return $this->weakness;
    }

    public function addWeakness(self $weakness): static
    {
        if (!$this->weakness->contains($weakness)) {
            $this->weakness->add($weakness);
        }

        return $this;
    }

    public function removeWeakness(self $weakness): static
    {
        $this->weakness->removeElement($weakness);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUseless(): Collection
    {
        return $this->useless;
    }

    public function addUseless(self $useless): static
    {
        if (!$this->useless->contains($useless)) {
            $this->useless->add($useless);
        }

        return $this;
    }

    public function removeUseless(self $useless): static
    {
        $this->useless->removeElement($useless);

        return $this;
    }

    /**
     * @return Collection<int, Pokemon>
     */
    public function getPokemons(): Collection
    {
        return $this->pokemons;
    }

    public function addPokemon(Pokemon $pokemon): static
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons->add($pokemon);
            $pokemon->addType($this);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): static
    {
        if ($this->pokemons->removeElement($pokemon)) {
            $pokemon->removeType($this);
        }

        return $this;
    }
}
