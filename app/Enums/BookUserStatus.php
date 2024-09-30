<?php

namespace App\Enums;

enum BookUserStatus: string
{
    case COMPLETED = 'Completed';
    case DID_NOT_FINISH = 'Did Not Finish';
    case WANT_TO_READ = 'Want to Read';
    case READING = 'Reading';

    public static function getStatuses(): array
    {
        return [
            self::COMPLETED->value,
            self::DID_NOT_FINISH->value,
            self::WANT_TO_READ->value,
            self::READING->value,
        ];
    }
}


