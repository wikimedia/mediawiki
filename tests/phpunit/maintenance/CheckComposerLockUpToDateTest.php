<?php

namespace MediaWiki\Tests\Maintenance;

use CheckComposerLockUpToDate;

/**
 * @covers \CheckComposerLockUpToDate
 * @author Dreamy Jazz
 */
class CheckComposerLockUpToDateTest extends MaintenanceBaseTestCase {

	private const FIXTURE_DIRECTORY = MW_INSTALL_PATH . '/tests/phpunit/data/LockFileChecker';

	public function getMaintenanceClass() {
		return SemiMockedCheckComposerLockUpToDate::class;
	}

	public function testCanExecuteWithoutLocalSettings() {
		$this->assertTrue( $this->maintenance->canExecuteWithoutLocalSettings() );
	}

	public function testWhenNoLockFileFound() {
		// Test that an empty directory as the mediawiki/core install path results in an error.
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Could not find composer.lock file/' );
		$testPath = $this->getNewTempDirectory();
		$this->maintenance->setMockMwInstallPath( $testPath );
		$this->maintenance->execute();
	}

	public function testWhenLockFileOkay() {
		// Get example composer.json and composer.lock files and add them to a fake install directory.
		$testPath = $this->getNewTempDirectory();
		copy( self::FIXTURE_DIRECTORY . '/composer-testcase1.json', $testPath . '/composer.json' );
		copy( self::FIXTURE_DIRECTORY . '/composer-testcase1.lock', $testPath . '/composer.lock' );
		$this->maintenance->setMockMwInstallPath( $testPath );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Your composer.lock file is up to date with current dependencies/' );
	}

	public function testWhenLockFileOutdated() {
		// Get example composer.json and composer.lock files and add them to a fake install directory.
		$testPath = $this->getNewTempDirectory();
		copy( self::FIXTURE_DIRECTORY . '/composer-testcase2.json', $testPath . '/composer.json' );
		copy( self::FIXTURE_DIRECTORY . '/composer-testcase2.lock', $testPath . '/composer.lock' );
		$this->maintenance->setMockMwInstallPath( $testPath );
		// Verify that the maintenance script errors out both indicating what is out of date and also
		// how to fix this.
		$this->expectCallToFatalError();
		$this->expectOutputRegex(
			'/wikimedia\/relpath: 2\.9\.9 installed, 3\.0\.0 required[\s\S]*' .
			'Error: your composer.lock file is not up to date.*' .
			'Run "composer update" to install newer dependencies/'
		);
		$this->maintenance->execute();
	}
}

/**
 * @internal Only for use by CheckComposerLockUpToDateTest
 */
class SemiMockedCheckComposerLockUpToDate extends CheckComposerLockUpToDate {

	private string $mockMwInstallPath;

	/**
	 * Set the a mock MW_INSTALL_PATH value for the test.
	 */
	public function setMockMwInstallPath( string $mockMwInstallPath ) {
		$this->mockMwInstallPath = $mockMwInstallPath;
	}

	protected function getMwInstallPath(): string {
		return $this->mockMwInstallPath;
	}
}
