<?php

namespace MakeItFly\CleanTalkBundle\Validator;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class PropertyResolver implements PropertyResolverInterface
{
    private PropertyAccessorInterface $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    public function resolveProperty(
        FormInterface $form,
        $propertyNameOrCallable
    ): string {
        $data = $form->getData();

        if (is_string($propertyNameOrCallable)) {
            return $this->resolveFromPropertyName($data, $propertyNameOrCallable);
        }
        if (is_callable($propertyNameOrCallable)) {
            return $this->resolveFromCallable($data, $propertyNameOrCallable);
        }

        throw new \InvalidArgumentException('Pass either a property name as a string or a callable');
    }

    private function resolveFromPropertyName($data, string $propertyName): string
    {
        return $this->propertyAccessor->getValue($data, $propertyName);
    }

    private function resolveFromCallable($data, callable $callable): string
    {
        return $callable($data);
    }
}
