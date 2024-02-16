<?php

use MediaWiki\Shell\Command;
use MediaWiki\Shell\CommandFactory;
use MediaWiki\Shell\ShellboxClientFactory;
use Psr\Log\NullLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Shell
 */
class CommandFactoryTest extends MediaWikiUnitTestCase {

	/**
	 * @covers \MediaWiki\Shell\CommandFactory::create
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

		$clientFactory = new class extends ShellboxClientFactory {
			public function __construct() {
			}

			public function isEnabled( ?string $service = null ): bool {
				return false;
			}

			public function getClient( array $options = [] ) {
				throw new \LogicException( 'unreachable' );
			}
		};

		$factory = new CommandFactory( $clientFactory,
			$limits, $cgroup, false );
		$factory->setLogger( $logger );
		$factory->logStderr();
		$command = $factory->create();
		$this->assertInstanceOf( Command::class, $command );
		$wrapper = TestingAccessWrapper::newFromObject( $command );
		$this->assertSame( $logger, $wrapper->logger );
		$this->assertSame( $limits['filesize'] * 1024, $command->getFileSizeLimit() );
		$this->assertSame( $limits['memory'] * 1024, $command->getMemoryLimit() );
		$this->assertSame( $limits['time'], $command->getCpuTimeLimit() );
		$this->assertSame( $limits['walltime'], $command->getWallTimeLimit() );
		$this->assertTrue( $command->getLogStderr() );
	}
}
