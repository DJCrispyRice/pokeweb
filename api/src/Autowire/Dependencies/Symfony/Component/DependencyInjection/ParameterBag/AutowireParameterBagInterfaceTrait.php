<?php

declare(strict_types=1);

namespace App\Autowire\Dependencies\Symfony\Component\DependencyInjection\ParameterBag;

use App\Exception\AutowiringException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait AutowireParameterBagInterfaceTrait
{
    private ParameterBagInterface $_parameterBag;

    #[Required]
    public function autowireParameterBag(ParameterBagInterface $parameterBag): static
    {
        if (isset($this->_parameterBag)) {
            throw new AutowiringException('Setter ' . __METHOD__ . ' has already been called.');
        }

        $this->_parameterBag = $parameterBag;

        return $this;
    }

    protected function getParameterBag(): ParameterBagInterface
    {
        if (isset($this->_parameterBag) === false) {
            throw new AutowiringException('Setter ' . __TRAIT__ . '::autowireParameterBag has never been called.');
        }

        return $this->_parameterBag;
    }
}
