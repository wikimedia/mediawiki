<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\StatsdClient;
use Liuggio\StatsdClient\Factory\StatsdDataFactory;
//use Liuggio\StatsdClient\Sender\SocketSender;


class ReadmeTest extends \PHPUnit_Framework_TestCase
{
    public function testFullUsageWithObject() {

        $sender = $this->mockSender();
        // $sender = new Sender();

        // StatsdClient(SenderInterface $sender, $host = 'udp://localhost', $port = 8126, $reducePacket = true, $fail_silently = true)
        $client = new StatsdClient($sender);
        $factory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');

        // create the data with the factory
        $data[] = $factory->timing('usageTime', 100);
        $data[] = $factory->increment('visitor');
        $data[] = $factory->decrement('click');
        $data[] = $factory->gauge('gaugor', 333);
        $data[] = $factory->set('uniques', 765);

        // send the data as array or directly as object
        $client->send($data);
    }



    public function testFullUsageArray() {
        
        $sender = $this->mockSender();
        // $sender = new Sender();

        // StatsdClient(SenderInterface $sender, $host = 'localhost', $port = 8126, $protocol='udp', $reducePacket = true, $fail_silently = true)
        $client = new StatsdClient($sender, $host = 'localhost', $port = 8126, 'udp', $reducePacket = true, $fail_silently = true);
 
        $data[] ="increment:1|c";
        $data[] ="set:value|s";
        $data[] ="gauge:value|g";
        $data[] = "timing:10|ms";
        $data[] = "decrement:-1|c";
        $data[] ="key:1|c";         

        // send the data as array or directly as object
        $client->send($data);
    }


    private function mockSender() {
        $sender =  $this->getMock('\Liuggio\StatsdClient\Sender\SenderInterface', array('open', 'write', 'close'));
        $sender->expects($this->once())
            ->method('open')
            ->will($this->returnValue(true));

        $sender->expects($this->any())  //If you set the reduce = true into the StatsdClient the write will be called once
            ->method('write')
            ->will($this->returnCallBack(function($fp, $message) {
             //  echo PHP_EOL . "- " . $message;
        }));

        $sender->expects($this->once())
            ->method('close')
            ->will($this->returnValue(true));

        return $sender;
    }
}