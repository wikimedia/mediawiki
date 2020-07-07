<?php

use MediaWiki\Shell\Command;
use MediaWiki\Shell\CommandFactory;
use MediaWiki\Shell\FirejailCommand;
use Psr\Log\NullLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Shell
 */
class CommandFactoryTest extends MediaWikiUnitTestCase {

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

		$factory = new CommandFactory( $limits, $cgroup, false );
		$factory->setLogger( $logger );
		$factory->logStderr();
		$command = $factory->create();
		$this->assertInstanceOf( Command::class, $command );

		$wrapper = TestingAccessWrapper::newFromObject( $command );
		$this->assertSame( $logger, $wrapper->logger );
		$this->assertSame( $cgroup, $wrapper->cgroup );
		$this->assertSame( $limits, $wrapper->limits );
		$this->assertTrue( $wrapper->doLogStderr );
	}

	/**
	 * @covers MediaWiki\Shell\CommandFactory::create
	 */
	public function testFirejailCreate() {
		$mock = $this->getMockBuilder( CommandFactory::class )
			->setConstructorArgs( [ [], false, 'firejail' ] )
			->setMethods( [ 'findFirejail' ] )
			->getMock();
		$mock->method( 'findFirejail' )->willReturn( '/usr/bin/firejail' );
		$this->assertInstanceOf( FirejailCommand::class, $mock->create() );
	}
}
