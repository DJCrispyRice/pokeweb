<?php

declare(strict_types=1);

namespace App\Autowire\Repository;

use App\Exception\AutowiringException;
use App\Repository\AttackRepository;
use Symfony\Contracts\Service\Attribute\Required;

trait AutowireAttackRepositoryTrait
{
    private AttackRepository $_attackRepository;

    #[Required]
    public function autowireAttackRepository(AttackRepository $attackRepository): static
    {
        if (isset($this->_attackRepository)) {
            throw new AutowiringException('Setter ' . __METHOD__ . ' has already been called.');
        }

        $this->_attackRepository = $attackRepository;

        return $this;
    }

    protected function getAttackRepository(): AttackRepository
    {
        if (isset($this->_attackRepository) === false) {
            throw new AutowiringException('Setter ' . __TRAIT__ . '::autowireAttackRepository has never been called.');
        }

        return $this->_attackRepository;
    }
}
