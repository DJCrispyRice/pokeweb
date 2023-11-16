<?php

declare(strict_types=1);

namespace App\Autowire\Repository;

use App\Exception\AutowiringException;
use App\Repository\TypeRepository;
use Symfony\Contracts\Service\Attribute\Required;

trait AutowireTypeRepositoryTrait
{
    private TypeRepository $_typeRepository;

    #[Required]
    public function autowireTypeRepository(TypeRepository $typeRepository): static
    {
        if (isset($this->_typeRepository)) {
            throw new AutowiringException('Setter ' . __METHOD__ . ' has already been called.');
        }

        $this->_typeRepository = $typeRepository;

        return $this;
    }

    protected function getTypeRepository(): TypeRepository
    {
        if (isset($this->_typeRepository) === false) {
            throw new AutowiringException('Setter ' . __TRAIT__ . '::autowireTypeRepository has never been called.');
        }

        return $this->_typeRepository;
    }
}
