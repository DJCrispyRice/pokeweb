<?php

declare(strict_types=1);

namespace App\Autowire\Repository;

use App\Exception\AutowiringException;
use App\Repository\PokemonRepository;
use Symfony\Contracts\Service\Attribute\Required;

trait AutowirePokemonRepositoryTrait
{
    private PokemonRepository $_pokemonRepository;

    #[Required]
    public function autowirePokemonRepository(PokemonRepository $pokemonRepository): static
    {
        if (isset($this->_pokemonRepository)) {
            throw new AutowiringException('Setter ' . __METHOD__ . ' has already been called.');
        }

        $this->_pokemonRepository = $pokemonRepository;

        return $this;
    }

    protected function getPokemonRepository(): PokemonRepository
    {
        if (isset($this->_pokemonRepository) === false) {
            throw new AutowiringException('Setter ' . __TRAIT__ . '::autowirePokemonRepository has never been called.');
        }

        return $this->_pokemonRepository;
    }
}
