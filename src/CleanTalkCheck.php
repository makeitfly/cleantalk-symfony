<?php

namespace MakeItFly\CleanTalkBundle;

/**
 * CleanTalk allows two different types of spam checks:
 * - Message: when sending a message
 * - User: when creating a new account
 */
final class CleanTalkCheck
{
    public const MESSAGE = 'message';
    public const USER = 'user';
}
