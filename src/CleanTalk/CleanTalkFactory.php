<?php

namespace MakeItFly\CleanTalkBundle\CleanTalk;

use Cleantalk\Cleantalk;

final class CleanTalkFactory
{
    public static function create(string $serverUrl): Cleantalk
    {
        $cleanTalk = new Cleantalk();

        // @todo:
        // Optionally allow for more configuration. For now we simply follow the
        // php-antispam README.
        $cleanTalk->server_url = $serverUrl;

        return $cleanTalk;
    }
}
