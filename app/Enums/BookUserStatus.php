<?php

namespace App\Enums;

enum BookUserStatus: string
{
    case COMPLETED = 'Completed';
    case DID_NOT_FINISH = 'Did Not Finish';
    case WANT_TO_READ = 'Want to Read';
    case READING = 'Reading';
}
