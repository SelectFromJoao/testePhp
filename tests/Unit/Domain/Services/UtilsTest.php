<?php

declare(strict_types=1);

namespace Unit\Domain\Services;

use PHPUnit\Framework\TestCase;
use App\Domain\Utils\SlytherinDate;
use App\Domain\Utils\SlytherinGuid;
use DateTime;

class UtilsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testUtilsFunction(): void
    {
         $slytherinDate = new SlytherinDate();
         $date = $slytherinDate->currentTime();

         $slytherinGuid = new SlytherinGuid();
         $guid = $slytherinGuid->Guid(12);

        $this->assertEquals($date, $date);
        $this->assertEquals($guid, $guid);
    }

}
