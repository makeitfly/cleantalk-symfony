<?php

namespace MakeItFly\CleanTalkBundle\Validator\Constraints;

use MakeItFly\CleanTalkBundle\CleanTalkCheck;
use Symfony\Component\Validator\Constraint;

// @todo: required parameters
// https://cleantalk.org/help/api-check-message
final class CleanTalk extends Constraint
{
    public string $message = 'Form submission detected as spam.';

    public string $checkType = CleanTalkCheck::MESSAGE;

    public $senderEmailField;
    public $senderNicknameField;
    public $messageField;
    public $phoneField;

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return 'makeitfly.validator.cleantalk';
    }

}
