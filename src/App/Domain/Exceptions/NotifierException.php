<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use DomainException;

class NotifierException extends DomainException
{
    protected $message = 'Notificação falhou';
}
