<?php

use MediaWiki\Shell\CommandFactory;
use Psr\Log\NullLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Shell
 */
class CommandFactoryTest extends PHPUnit_Framework_TestCase {
	/**
	 * @covers MediaWiki\Shell\CommandFactory::create
	 */
	public function testCreate() {
		$logger = new NullLogger();
		$cgroup = '/sys/fs/cgroup/memory/mygroup';
		$limits = [
			'filesize' => 1000,
			'memory' => 1000,
			'time' => 30,
			'walltime' => 40,
		];

		$factory = new CommandFactory( $limits, $cgroup );
		$factory->setLogger( $logger );
		$command = $factory->create();

		$wrapper = TestingAccessWrapper::newFromObject( $command );
		$this->assertSame( $logger, $wrapper->logger );
		$this->assertSame( $cgroup, $wrapper->cgroup );
		$this->assertSame( $limits, $wrapper->limits );
	}
}
