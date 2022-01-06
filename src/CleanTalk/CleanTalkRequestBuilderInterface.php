<?php

namespace MakeItFly\CleanTalkBundle\CleanTalk;

use Cleantalk\CleantalkRequest;

/**
 * Configurable parameters as per the php-antispam README. There are more
 * available on the request object, but for now we limit the scope to the
 * code example.
 */
interface CleanTalkRequestBuilderInterface
{
    public function build(): self;

    public function setSenderEmail(string $email): self;

    public function setSenderIp(string $ip): self;

    public function setSenderNickname(string $nickname): self;

    public function setPhone(string $phone): self;

    public function setJsOn(bool $jsOn): self;

    public function setMessage(string $message): self;

    public function getRequest(): CleantalkRequest;
}
