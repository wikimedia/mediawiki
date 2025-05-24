<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Maintenance\MaintenanceFatalError;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

abstract class MaintenanceBaseTestCase extends MediaWikiIntegrationTestCase {

	/**
	 * The main Maintenance instance that is used for testing, wrapped and mockable.
	 *
	 * @var Maintenance
	 */
	protected $maintenance;

	protected function setUp(): void {
		parent::setUp();

		$this->maintenance = $this->createMaintenance();
		// This is smelly, but maintenance scripts usually produce output, so
		// we anticipate and ignore with a regex that will catch everything.
		//
		// If you call $this->expectOutputRegex in your subclass, this guard
		// is overridden, and your specific pattern will be respected.
		$this->expectOutputRegex( '/.*/' );
	}

	/**
	 * Do a little stream cleanup to prevent output in case the child class
	 * hasn't tested the capture buffer.
	 */
	protected function tearDown(): void {
		if ( $this->maintenance ) {
			$this->maintenance->cleanupChanneled();
		}

		parent::tearDown();
	}

	/**
	 * Subclasses must implement this in order to use the $this->maintenance
	 * variable.  Normally, it will be set like:
	 *     return PopulateDatabaseMaintenance::class;
	 *
	 * If you need to change the way your maintenance class is constructed,
	 * override createMaintenance.
	 *
	 * @return string Class name
	 */
	abstract protected function getMaintenanceClass();

	/**
	 * Called by setUp to initialize $this->maintenance.
	 *
	 * @return Maintenance The Maintenance instance to test.
	 */
	protected function createMaintenance() {
		$className = $this->getMaintenanceClass();
		$obj = new $className();

		// We use TestingAccessWrapper in order to access protected internals
		// such as `output()`.
		return TestingAccessWrapper::newFromObject( $obj );
	}

	/**
	 * Asserts the output before and after simulating shutdown
	 *
	 * This function simulates shutdown of self::maintenance.
	 *
	 * @param string $preShutdownOutput Expected output before simulating shutdown
	 * @param bool $expectNLAppending Whether or not shutdown simulation is expected
	 *   to add a newline to the output. If false, $preShutdownOutput is the
	 *   expected output after shutdown simulation. Otherwise,
	 *   $preShutdownOutput with an appended newline is the expected output
	 *   after shutdown simulation.
	 */
	protected function assertOutputPrePostShutdown( $preShutdownOutput, $expectNLAppending ) {
		$this->assertEquals( $preShutdownOutput, $this->getActualOutputForAssertion(),
				"Output before shutdown simulation" );

		$this->maintenance->cleanupChanneled();

		$postShutdownOutput = $preShutdownOutput . ( $expectNLAppending ? "\n" : "" );
		$this->expectOutputString( $postShutdownOutput );
	}

	/**
	 * Expects that a call to Maintenance::fatalError occurs. When Maintenance::fatalError
	 * is called, an exception is thrown which is marked as expected through this method.
	 *
	 * If you wish to assert on the error message provided to Maintenance::fatalError,
	 * then use ::expectOutputString or ::expectOutputRegex.
	 *
	 * @param ?int $expectedCode The expected error code provided to Maintenance::fatalError
	 * @since 1.43
	 */
	protected function expectCallToFatalError( ?int $expectedCode = null ) {
		$this->expectException( MaintenanceFatalError::class );
		if ( $expectedCode !== null ) {
			$this->expectExceptionCode( $expectedCode );
		}
	}

}
