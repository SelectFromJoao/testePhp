<?php

namespace App\Domain\Utils;

use DateTime;

class SlytherinDate {
    public function currentTime() {
        $date = new DateTime();
        return $date;
    }
}