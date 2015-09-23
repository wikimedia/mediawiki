<?php

namespace Liuggio\StatsdClient\Tests\Monolog\Handler;

use Monolog\Logger;
use Liuggio\StatsdClient\Monolog\Handler\StatsDHandler;


class StatsDHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array Record
     */
    protected function getRecord($level = Logger::WARNING, $message = 'test', $context = array())
    {
        return array(
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'level_name' => Logger::getLevelName($level),
            'channel' => 'test',
            'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
            'extra' => array(),
        );
    }

    /**
     * @return array
     */
    protected function getMultipleRecords()
    {
        return array(
            $this->getRecord(Logger::DEBUG, 'debug message 1'),
            $this->getRecord(Logger::DEBUG, 'debug message 2'),
            $this->getRecord(Logger::INFO, 'information'),
            $this->getRecord(Logger::WARNING, 'warning'),
            $this->getRecord(Logger::ERROR, 'error')
        );
    }

    /**
     * @return Monolog\Formatter\FormatterInterface
     */
    protected function getIdentityFormatter()
    {
        $formatter = $this->getMock('Monolog\\Formatter\\FormatterInterface');
        $formatter->expects($this->any())
            ->method('format')
            ->will($this->returnCallback(function($record) { return $record['message']; }));

        return $formatter;
    }


    protected function setup()
    {
        if (!interface_exists('Liuggio\StatsdClient\StatsdClientInterface')) {
            $this->markTestSkipped('The "liuggio/statsd-php-client" package is not installed');
        }
    }

    public function testHandle()
    {
        $client = $this->getMock('Liuggio\StatsdClient\StatsdClientInterface');
        $factory = $this->getMock('Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface');

        $factory->expects($this->any())
            ->method('increment')
            ->will($this->returnCallback(function ($input){
                return sprintf('%s|c|1', $input);
            }));

        $prefixToAssert = 'prefix';
        $messageToAssert = 'test-msg';

        $record = $this->getRecord(Logger::WARNING, $messageToAssert, array('data' => new \stdClass, 'foo' => 34));

        $assert = array(sprintf('%s.test.WARNING.%s|c|1',$prefixToAssert, $messageToAssert),
            sprintf('%s.test.WARNING.%s.context.data.[object] (stdClass: {})|c|1',$prefixToAssert, $messageToAssert),
            sprintf('%s.test.WARNING.%s.context.foo.34|c|1',$prefixToAssert, $messageToAssert));

        $client->expects($this->once())
            ->method('send')
            ->with($assert);

        $handler = new StatsDHandler($client, $factory, $prefixToAssert);
        $handler->handle($record);
    }
}