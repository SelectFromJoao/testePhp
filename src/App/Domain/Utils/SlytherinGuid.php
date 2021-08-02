<?php

namespace App\Domain\Utils;

class SlytherinGuid {
    public function Guid(int $bytes) {
        $guid = bin2hex(random_bytes($bytes));
        return $guid;
    }
}