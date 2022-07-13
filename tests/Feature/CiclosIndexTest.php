<?php

namespace Tests\Feature;

use App\Models\Ciclo;
use Tests\FeatureTestCase;


class CiclosIndexTest extends FeatureTestCase
{
    const PETITION = 'api/ciclos';

    public function testIndexReturnAll()
    {
        $this->seed();
        $items = $this->getDataFromJson('GET',self::PETITION);
        $this->assertEquals(count($items),count(Ciclo::all()));
    }
}
