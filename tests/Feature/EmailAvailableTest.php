<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;


class EmailAvailableTest extends FeatureTestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testEmailAvailable()
    {
        $this->seed();
        $result = $this->getDataFromJson('GET','api/users/igomis@cipfpbatoi.com/available');
        $this->assertEquals(true,$result);
    }
    public function testEmailNotAvailable()
    {
        $this->seed();
        $result = $this->getDataFromJson('GET','api/users/igomis@cipfpbatoi.es/available');
        $this->assertEquals(false,$result);
    }
}
