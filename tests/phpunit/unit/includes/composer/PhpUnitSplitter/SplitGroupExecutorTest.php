<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\composer\PhpUnitSplitter;

use MediaWiki\Composer\ComposerSystemInterface;
use MediaWiki\Composer\PhpUnitSplitter\SplitGroupExecutor;
use PHPUnit\Framework\TestCase;
use Shellbox\Command\UnboxedCommand;
use Shellbox\Command\UnboxedExecutor;
use Shellbox\Command\UnboxedResult;

/**
 * @license GPL-2.0-or-later
 * @covers \MediaWiki\Composer\ComposerLaunchParallel
 */
class SplitGroupExecutorTest extends TestCase {

	private SplitGroupExecutor $splitGroupExecutor;
	private array $collectedArgs = [];

	public function setUp(): void {
		parent::setUp();
		$commandMock = $this->createMock( UnboxedCommand::class );
		$executor = $this->createMock( UnboxedExecutor::class );
		$executor->expects( $this->once() )
			->method( 'createCommand' )
			->willReturn( $commandMock );
		$commandMock
			->method( 'params' )
			->willReturnCallback( function ( ...$args ) use ( $commandMock ) {
				$this->collectedArgs = array_merge( $this->collectedArgs, $args );
				return $commandMock;
			} );
		$commandMock->method( 'execute' )
			->willReturn( $this->createMock( UnboxedResult::class ) );
		$interface = $this->createMock( ComposerSystemInterface::class );
		$this->splitGroupExecutor = new SplitGroupExecutor( $executor, null, $interface );
	}

	public function testExecuteDatabaseSuite() {
		$this->splitGroupExecutor->executeSplitGroup(
			"extensions",
			[ "Database" ],
			[ "Broken" ]
		);
		$this->assertEquals( [
			"composer", "run", "--timeout=0", "phpunit:entrypoint", "--",
			"--testsuite", "extensions", "--exclude-group", "Broken",
			"--group", "Database" ],
			$this->collectedArgs );
	}

	public function testExecuteDatabaselessSuite() {
		$this->splitGroupExecutor->executeSplitGroup(
			"extensions",
			[],
			[ "Broken", "Standalone" ]
		);
		$this->assertEquals( [
			"composer", "run", "--timeout=0", "phpunit:entrypoint", "--",
			"--testsuite", "extensions", "--exclude-group", "Broken,Standalone" ],
			$this->collectedArgs );
	}
}
