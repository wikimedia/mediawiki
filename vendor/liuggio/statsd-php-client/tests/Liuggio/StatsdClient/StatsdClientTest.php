<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\StatsdClient;
use Liuggio\StatsdClient\Entity\StatsdData;

class StatsdClientTest extends \PHPUnit_Framework_TestCase
{

    public function mockSenderWithAssertionOnWrite($messageToAssert=null) {

        $mock = $this->getMockBuilder('\Liuggio\StatsdClient\Sender\SocketSender') ->disableOriginalConstructor() ->getMock();

        $phpUnit = $this;
        $mock->expects($this->any())
            ->method('open')
            ->will($this->returnValue(true));
        // if the input is an array expects a call foreach item
        if (is_array($messageToAssert)) {
            $index = 0;
            foreach ($messageToAssert as $oneMessage) {
                $index++;
                $mock->expects($this->at($index))
                    ->method('write')
                    ->will($this->returnCallBack(function($fp, $message) use ($phpUnit, $oneMessage) {
                      $phpUnit->assertEquals($message, $oneMessage);
                }));
            }
        } else if (null !== $messageToAssert){
            // if the input is a string expects only once
            $mock->expects($this->once())
                ->method('write')
                ->will($this->returnCallBack(function($fp, $message) use ($phpUnit, $messageToAssert) {
                 $phpUnit->assertEquals($message, $messageToAssert);
            }));
        }
        return $mock;
    }

    public function mockStatsdClientWithAssertionOnWrite($messageToAssert) {

        $mockSender = $this->mockSenderWithAssertionOnWrite($messageToAssert);

        return new StatsdClient($mockSender, false, false);
    }

    public function mockFactory() {

        $mock =  $this->getMock('\Liuggio\StatsdClient\Factory\StatsdDataFactory', array('timing'));

        $statsData = new StatsdData();
        $statsData->setKey('key');
        $statsData->setValue('1');
        $statsData->setMetric('ms');

        $phpUnit = $this;
        $mock->expects($this->any())
            ->method('timing')
            ->will($this->returnValue($statsData));

        return $mock;
    }

    public static function provider()
    {
        /**
         * First
         */
        $statsData0 = new StatsdData();
        $statsData0->setKey('keyTiming');
        $statsData0->setValue('1');
        $statsData0->setMetric('ms');
        /**
         * Second
         */
        $stats1 = array();
        $statsData1 = new StatsdData();
        $statsData1->setKey('keyTiming');
        $statsData1->setValue('1');
        $statsData1->setMetric('ms');
        $stats1[] = $statsData1;

        $statsData1 = new StatsdData();
        $statsData1->setKey('keyIncrement');
        $statsData1->setValue('1');
        $statsData1->setMetric('c');
        $stats1[] = $statsData1;

        return array(
            array($statsData0, "keyTiming:1|ms"),
            array($stats1, array("keyTiming:1|ms", "keyIncrement:1|c")),
        );
    }
    public static function providerSend()
    {
        return array(
            array(array('gauge:value|g'), 'gauge:value|g'),
            array(array("keyTiming:1|ms", "keyIncrement:1|c"), array("keyTiming:1|ms", "keyIncrement:1|c")),
        );
    }

    /**
     * @dataProvider provider
     */
    public function testPrepareAndSend($statsdInput, $assertion) {

        $statsdMock = $this->mockStatsdClientWithAssertionOnWrite($assertion);
        $statsdMock->send($statsdInput);
    }

    /**
     * @dataProvider providerSend
     */
    public function testSend($array, $assertion) {

        $statsdMock = $this->mockStatsdClientWithAssertionOnWrite($assertion);
        $statsdMock->send($array);
    }

    public function testReduceCount()
    {
        $statsd = $this->mockStatsdClientWithAssertionOnWrite(null);

        $entity0 = new StatsdData();
        $entity0->setKey('key1');
        $entity0->setValue('1');
        $entity0->setMetric('c');
        $array0[] = $entity0;

        $entity0 = new StatsdData();
        $entity0->setKey('key2');
        $entity0->setValue('2');
        $entity0->setMetric('ms');
        $array0[] = $entity0;

        $reducedMessage = array('key1:1|c' . PHP_EOL . 'key2:2|ms');

        $this->assertEquals($statsd->reduceCount($array0), $reducedMessage);

    }

    public function testReduceWithString()
    {
        $statsd = $this->mockStatsdClientWithAssertionOnWrite(null);

        $msg = 'A3456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789:';
        $msg .= '123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789|c';
        $array0[] = $msg;

        $msg = 'B3456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789:';
        $msg .= '123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789|c';
        $array0[] = $msg;
        $reduced = $statsd->reduceCount($array0);
        $combined = $array0[0] . PHP_EOL . $array0[1];
        $this->assertEquals($combined, $reduced[0]);
    }


    public function testReduceWithMaxUdpPacketSplitInTwoPacket()
    {
        $statsd = $this->mockStatsdClientWithAssertionOnWrite(null);

        $msg = 'A3456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789';    //1
        $msg .= '123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 '; //2
        $msg .= '123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 '; //3
        $msg .= '123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 '; //4
        $msg .= '123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789|c'; //500
        $array0[] = $msg;

        $msg = 'Bkey:';
        $msg .= '123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789|c';
        $array0[] = $msg;

        $reduced = $statsd->reduceCount($array0);

        $this->assertEquals($array0[0], $reduced[0]);
        $this->assertEquals($array0[1], $reduced[1]);
    }



    public function testMultiplePacketsWithReducing()
    {

        $msg = 'A23456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789';
        $array0[] = $msg;
        $array0[] = $msg;
        $array0[] = $msg;
        $array0[] = $msg;
        $array0[] = $msg;
        $array0[] = $msg;
        $array0[] = $msg;
        $array0[] = $msg;

        $total = count($array0) * strlen($msg);

        $reducedPacketsAssertion = (int) ceil($total / StatsdClientInterface::MAX_UDP_SIZE_STR);


        $mockSender = $this->mockSenderWithAssertionOnWrite();
        $statsd = new  StatsdClient($mockSender, true, false);
        $reduced = $statsd->reduceCount($array0);

        $this->assertEquals($reducedPacketsAssertion, count($reduced));
    }

    public function testSampleRate()
    {
        $senderMock = $this->getMock('Liuggio\StatsdClient\Sender\SenderInterface');
        $senderMock
            ->expects($this->once())
            ->method('open')
            ->will($this->returnValue(true))
        ;
        $senderMock
            ->expects($this->once())
            ->method('write')
            ->with($this->anything(), 'foo|@0.2')
        ;
        $client = new StatsdClient($senderMock, false, false);

        $client->send(
            'foo',
            0.2
        );
    }
}
