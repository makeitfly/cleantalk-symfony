<?php

namespace MakeItFly\CleanTalkBundle\Form\Type;

use MakeItFly\CleanTalkBundle\CleanTalkCheck;
use MakeItFly\CleanTalkBundle\Validator\Constraints\CleanTalk;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CleanTalkType extends AbstractType
{
    private CleanTalk $cleanTalkConstraint;

    public function __construct()
    {
        $this->cleanTalkConstraint = new CleanTalk();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mapped' => false,
            'label' => false,
            'constraints' => [$this->cleanTalkConstraint],
            'check_type' => CleanTalkCheck::MESSAGE,
            'sender_email_field' => null,
            'sender_nickname_field' => null,
            'message_field' => null,
            'phone_field' => null
        ]);

        $resolver->setAllowedTypes('sender_email_field', ['string', 'callable']);
        $resolver->setAllowedTypes('sender_nickname_field', ['null', 'string', 'callable']);
        $resolver->setAllowedTypes('message_field', ['null', 'string', 'callable']);
        $resolver->setAllowedTypes('phone_field', ['null', 'string', 'callable']);
        $resolver->setAllowedValues(
            'check_type',
            [CleanTalkCheck::MESSAGE, CleanTalkCheck::USER]
        );
        $resolver->setRequired('check_type');
        $resolver->setRequired('sender_email_field');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('cleantalk_js_on', JsOnType::class);
        $this->passOptionsToConstraint($options);
    }

    private function passOptionsToConstraint(array $options): void
    {
        $this->cleanTalkConstraint->senderEmailField = $options['sender_email_field'];

        $messageField = $options['message_field'] ?? null;
        if ($messageField !== null) {
            $this->cleanTalkConstraint->messageField = $messageField;
        }

        $senderNicknameField = $options['sender_nickname_field'] ?? null;
        if ($senderNicknameField !== null) {
            $this->cleanTalkConstraint->senderNicknameField = $senderNicknameField;
        }

        $phoneField = $options['phone_field'] ?? null;
        if ($phoneField !== null) {
            $this->cleanTalkConstraint->phoneField = $phoneField;
        }
    }
}
