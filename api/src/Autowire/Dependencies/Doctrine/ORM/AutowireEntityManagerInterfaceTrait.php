<?php

declare(strict_types=1);

namespace App\Autowire\Dependencies\Doctrine\ORM;

use App\Exception\AutowiringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait AutowireEntityManagerInterfaceTrait
{
    private EntityManagerInterface $_entityManager;

    #[Required]
    public function autowireEntityManager(EntityManagerInterface $entityManager): static
    {
        if (isset($this->_entityManager)) {
            throw new AutowiringException('Setter ' . __METHOD__ . ' has already been called.');
        }

        $this->_entityManager = $entityManager;

        return $this;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        if (isset($this->_entityManager) === false) {
            throw new AutowiringException('Setter ' . __TRAIT__ . '::autowireEntityManager has never been called.');
        }

        return $this->_entityManager;
    }
}
