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
		// Ensure that fatalError() doesn't die, so we can test this
		// maintenance class. (This is redundant with ::createMaintenance
		// but is present to ensure isTesting is set even if subclass
		// overwrites ::createMaintenance.)
		$this->maintenance->isTesting = true;
	}

	protected function assertPostConditions(): void {
		// This is smelly, but maintenance scripts usually produce output, so
		// we anticipate and ignore with a regex that will catch everything.
		//
		// If you call $this->expectOutputRegex in your subclass, this guard
		// is overridden, and your specific pattern will be respected.
		if ( !$this->hasExpectationOnOutput() ) {
			$this->expectOutputRegex( '/.*/' );
		}
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
	 * @return class-string<Maintenance> Class name
	 */
	abstract protected function getMaintenanceClass();

	/**
	 * Called by setUp to initialize $this->maintenance.
	 *
	 * @return Maintenance The Maintenance instance to test.
	 */
	protected function createMaintenance() {
		return $this->createMaintenanceInternal( $this->getMaintenanceClass() );
	}

	/**
	 * Called by setUp to initialize $this->maintenance.
	 *
	 * @param class-string $className
	 * @return Maintenance The Maintenance instance to test.
	 */
	protected function createMaintenanceInternal( string $className ) {
		$obj = new $className();

		// We use TestingAccessWrapper in order to access protected internals
		// such as `output()`.
		$wrapper = TestingAccessWrapper::newFromObject( $obj );
		// Ensure that fatalError() doesn't die, so we can test this
		// maintenance class.
		$wrapper->isTesting = true;
		return $wrapper;
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
