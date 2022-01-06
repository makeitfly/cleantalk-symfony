<?php

namespace MakeItFly\CleanTalkBundle\Validator\Constraints;

use Cleantalk\CleantalkRequest;
use MakeItFly\CleanTalkBundle\CleanTalk\CleanTalkRequestBuilderInterface;
use MakeItFly\CleanTalkBundle\CleanTalkCheck;
use MakeItFly\CleanTalkBundle\Validator\PropertyResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Cleantalk\Cleantalk;
use MakeItFly\CleanTalkBundle\Validator\Constraints\CleanTalk as CleanTalkConstraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class CleanTalkValidator extends ConstraintValidator
{
    private bool $enabled;

    private Cleantalk $cleanTalkApi;

    private CleanTalkRequestBuilderInterface $cleanTalkRequestBuilder;

    private PropertyResolverInterface $propertyResolver;

    public function __construct(
        bool $enabled,
        Cleantalk $cleanTalkApi,
        CleanTalkRequestBuilderInterface $cleanTalkRequestBuilder,
        PropertyResolverInterface $propertyResolver
    ) {
        $this->enabled = $enabled;
        $this->cleanTalkApi = $cleanTalkApi;
        $this->cleanTalkRequestBuilder = $cleanTalkRequestBuilder;
        $this->propertyResolver = $propertyResolver;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$this->enabled) {
            return;
        }

        if (!$constraint instanceof CleanTalkConstraint) {
            throw new UnexpectedTypeException(
                $constraint,
                CleanTalkConstraint::class
            );
        }

        // If any other violations exist, we don't want to send a request to the
        // CleanTalk API since we know the form won't be submitted anyway.
        if ($this->context->getViolations()->count() > 0) {
            return;
        }

        $request = $this->buildCleanTalkRequest($constraint);

        $result = $constraint->checkType === CleanTalkCheck::MESSAGE
            ? $this->cleanTalkApi->isAllowMessage($request)
            : $this->cleanTalkApi->isAllowUser($request);

        if ($result->allow !== 1) {
            $this->context->addViolation($constraint->message);
        }
    }

    /**
     * CleanTalk requires some of the submitted form fields to be submitted to
     * the API. We fetch them from the form here.
     */
    private function buildCleanTalkRequest(CleanTalkConstraint $constraint): CleantalkRequest
    {
        $requestBuilder = $this->cleanTalkRequestBuilder->build();

        /** @var FormInterface $rootForm */
        $rootForm = $this->context->getRoot();
        $senderEmail = $this->propertyResolver->resolveProperty(
            $rootForm,
            $constraint->senderEmailField
        );
        $requestBuilder->setSenderEmail($senderEmail);

        if ($constraint->senderNicknameField) {
            $senderNickname = $this->propertyResolver->resolveProperty(
                $rootForm,
                $constraint->senderNicknameField
            );
            $requestBuilder->setSenderNickname($senderNickname);
        }
        if ($constraint->messageField) {
            $message = $this->propertyResolver->resolveProperty(
                $rootForm,
                $constraint->messageField
            );
            $requestBuilder->setMessage($message);
        }
        if ($constraint->phoneField) {
            $phone = $this->propertyResolver->resolveProperty(
                $rootForm,
                $constraint->phoneField
            );
            $requestBuilder->setPhone($phone);
        }

        // The JsOn field is in our control so just fetch it directly.
        $jsOn = $this->context->getValue()['cleantalk_js_on'] ?? false;
        $requestBuilder->setJsOn($jsOn);

        return $requestBuilder->getRequest();
    }
}
