<?php

namespace MediaWiki\Tests\Maintenance;

use FindClasses;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @covers \FindClasses
 * @group Database
 * @author Dreamy Jazz
 */
class FindClassesTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return SemiMockedFindClasses::class;
	}

	public function testExecute() {
		$this->maintenance->mockStdin( "MediaWiki\Maintenance\Version\n" );
		$this->maintenance->execute();
		$this->expectOutputString( MW_INSTALL_PATH . "/maintenance/Version.php\n" );
	}
}

/**
 * Mock for the input/output of FindClasses
 *
 * FindClasses internally tries to access stdin and stdout. We mock those aspects
 * for testing.
 */
class SemiMockedFindClasses extends FindClasses {

	/**
	 * @var string|null Text to pass as stdin
	 */
	private ?string $mockStdinText = null;

	/**
	 * Data for the fake stdin
	 *
	 * @param string $stdin The string to be used instead of stdin
	 */
	public function mockStdin( $stdin ) {
		$this->mockStdinText = $stdin;
	}

	public function getStdin( $len = null ) {
		if ( $len !== null ) {
			throw new ExpectationFailedException( "Tried to get stdin with non null parameter" );
		}

		return fopen( 'data://text/plain,' . $this->mockStdinText, 'r' );
	}
}
