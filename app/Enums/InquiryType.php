<?php

namespace App\Enums;

enum InquiryType: string
{
    case Request = 'request';
    case Issues = 'issues';
    case General = 'general';

    public function getLabel(): string
    {
        return match ($this) {
            self::Request => 'Request',
            self::Issues => 'Issues',
            self::General => 'General',
        };
    }
}
