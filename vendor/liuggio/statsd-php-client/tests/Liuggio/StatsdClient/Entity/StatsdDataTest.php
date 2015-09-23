<?php

namespace Liuggio\StatsdClient\Entity;

use Liuggio\StatsdClient\Entity\StatsdData;


class StatsdDataTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMessage()
    {
        $statsdData = new  StatsdData();
        $statsdData->setKey('key');
        $statsdData->setValue('value');
        $statsdData->setMetric('c');

        $this->assertEquals($statsdData->getMessage(), 'key:value|c');

        $statsdData = new  StatsdData();
        $statsdData->setKey('key');
        $statsdData->setValue(-1);
        $statsdData->setMetric('c');

        $this->assertEquals($statsdData->getMessage(), 'key:-1|c');

    }
}
