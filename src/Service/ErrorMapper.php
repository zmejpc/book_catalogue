<?php

namespace App\Service;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormError;

class ErrorMapper
{
	public function mapErrors(FormErrorIterator $iterator): array
	{
		return array_map(function(FormError $error) {
            return [
                $error->getCause()->getPropertyPath() => $error->getCause()->getMessage(),
            ];
        }, iterator_to_array($iterator));
	}
}