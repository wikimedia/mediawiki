<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\Composer;

use MediaWiki\Composer\ComposerLaunchParallel;
use MediaWiki\Composer\ComposerSystemInterface;
use MediaWiki\Composer\PhpUnitSplitter\SplitGroupExecutor;
use PHPUnit\Framework\TestCase;
use Shellbox\Command\UnboxedResult;
use Wikimedia\TestingAccessWrapper;

/**
 * @license GPL-2.0-or-later
 * @covers \MediaWiki\Composer\ComposerLaunchParallel
 */
class ComposerLaunchParallelTest extends TestCase {

	public function setUp(): void {
		if ( !extension_loaded( 'pcntl' ) && !extension_loaded( 'posix' ) ) {
			$this->markTestSkipped( 'need extension pcntl or posix' );
		}
	}

	private function getMockCommandResult(): UnboxedResult {
		$result = $this->createMock( UnboxedResult::class );
		$result->method( 'getStdout' )
			->willReturn( 'logs from test' );
		$result->method( 'getExitCode' )
			->willReturn( 1 );
		return $result;
	}

	private function getMockSystemInterface( string $logFileName ): ComposerSystemInterface {
		$systemInterface = $this->createMock( ComposerSystemInterface::class );
		$systemInterface->expects( $this->once() )
			->method( 'exit' )
			->with( 1 );
		$systemInterface->expects( $this->once() )
			->method( 'putFileContents' )
			->with( $logFileName, 'logs from test' );
		return $systemInterface;
	}

	public function testExecuteDatabaseSuite() {
		$logDir = getenv( 'MW_LOG_DIR' ) ?? '.';
		$executor = $this->createMock( SplitGroupExecutor::class );
		$systemInterface = $this->getMockSystemInterface( 'phpunit_output_1_database.log' );
		$composerLaunchParallel = new ComposerLaunchParallel(
			"phpunit-database.xml",
			[ 'Database' ],
			[ 'Broken' ],
			null,
			$executor,
			$systemInterface
		);
		$result = $this->getMockCommandResult();
		$executor->expects( $this->once() )
			->method( 'executeSplitGroup' )
			->with( 'split_group_1',
				[ 'Database' ],
				[ 'Broken' ],
				$logDir . "/phpunit_group_1_database.result.cache",
				1
			)
			->willReturn( $result );
		$wrapper = TestingAccessWrapper::newFromObject( $composerLaunchParallel );
		$wrapper->runTestSuite( 1 );
	}

	public function testExecuteDatabaselessSuite() {
		$logDir = getenv( 'MW_LOG_DIR' ) ?? '.';
		$executor = $this->createMock( SplitGroupExecutor::class );
		$systemInterface = $this->getMockSystemInterface( 'phpunit_output_1_databaseless.log' );
		$composerLaunchParallel = new ComposerLaunchParallel(
			"phpunit-databaseless.xml",
			[],
			[ 'Broken', 'Standalone', 'Database' ],
			null,
			$executor,
			$systemInterface
		);
		$result = $this->getMockCommandResult();
		$executor->expects( $this->once() )
			->method( 'executeSplitGroup' )
			->with( 'split_group_1',
				[],
				[ 'Broken', 'Standalone', 'Database' ],
				$logDir . "/phpunit_group_1_databaseless.result.cache",
				1
			)
			->willReturn( $result );
		$wrapper = TestingAccessWrapper::newFromObject( $composerLaunchParallel );
		$wrapper->runTestSuite( 1 );
	}
}
