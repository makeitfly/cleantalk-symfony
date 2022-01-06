<?php

namespace MakeItFly\CleanTalkBundle\Validator;

use Symfony\Component\Form\FormInterface;

interface PropertyResolverInterface
{
    /**
     * @param FormInterface $form
     * @param string|callable $propertyNameOrCallable
     * @return string
     */
    public function resolveProperty(
        FormInterface $form,
        $propertyNameOrCallable
    ): string;
}
