<?php

namespace Liuggio\StatsdClient\Entity;

use Liuggio\StatsdClient\Service\StatsdService;

class StatsdServiceTest extends \PHPUnit_Framework_TestCase
{
    private $clientMock;
    private $factoryMock;

    public function setUp()
    {
        $this->clientMock = $this->getMockBuilder('Liuggio\StatsdClient\StatsdClient')
            ->disableOriginalConstructor()
            ->getMock();
        $this->factoryMock = $this->getMockBuilder('Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testConstructorWithoutFactory()
    {
        $dut = new StatsdService($this->clientMock);
        $dut->timing('foo.bar', 123);
    }

    public function testFactoryImplementation()
    {
        $data = new StatsdData();

        // Configure the client mock.
        $this->factoryMock->expects($this->once())->method('timing')->willReturn($data);
        $this->factoryMock->expects($this->once())->method('gauge')->willReturn($data);
        $this->factoryMock->expects($this->once())->method('set')->willReturn($data);
        $this->factoryMock->expects($this->once())->method('increment')->willReturn($data);
        $this->factoryMock->expects($this->once())->method('decrement')->willReturn($data);
        $this->factoryMock->expects($this->once())->method('updateCount')->willReturn($data);

        // Actual test
        $dut = new StatsdService($this->clientMock, $this->factoryMock);
        $dut->timing('foo.bar', 123);
        $dut->gauge('foo.bar', 123);
        $dut->set('foo.bar', 123);
        $dut->increment('foo.bar');
        $dut->decrement('foo.bar');
        $dut->updateCount('foo.bar', 123);
    }

    public function testFlush()
    {
        $data = new StatsdData();
        $this->factoryMock->expects($this->once())->method('timing')->willReturn($data);
        $this->clientMock->expects($this->once())->method('send')
            ->with($this->equalTo(array($data)));

        // Actual test
        $dut = new StatsdService($this->clientMock, $this->factoryMock);
        $dut->timing('foobar', 123);
        $dut->flush();
    }

    public function testSampling()
    {
        $tries = false;
        $closure = function($a, $b) use (&$tries) {
            $tries = !$tries;
            return $tries ? 1 : 0;
        };

        $data = new StatsdData();
        $this->factoryMock->expects($this->exactly(2))->method('timing')->willReturn($data);
        $this->clientMock->expects($this->once())->method('send')
            ->with($this->equalTo(array($data)));

        // Actual test
        $dut = new StatsdService($this->clientMock, $this->factoryMock);
        $dut->setSamplingRate(0.1);
        $dut->setSamplingFunction($closure);
        $dut->timing('foo', 123);
        $dut->timing('bar', 123);
        $dut->flush();

        $this->assertSame(0.1, $data->getSampleRate());
    }
}
