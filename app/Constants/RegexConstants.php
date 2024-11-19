<?php

namespace App\Constants;

class RegexConstants
{
    /**
     * accept all character, except ";:<>", to prevent sql injection
     */
    const INJECTION_PROTECTED_WITH_SYMBOLS = '/^[^;:：；<>《》=]*$/u';
}
