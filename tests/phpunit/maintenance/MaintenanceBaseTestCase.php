<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWikiTestCase;

abstract class MaintenanceBaseTestCase extends MediaWikiTestCase {

	/**
	 * The main Maintenance instance that is used for testing, wrapped and mockable.
	 *
	 * @var Maintenance
	 */
	protected $maintenance;

	/**
	 * Do a little stream cleanup to prevent output in case the child class
	 * hasn't tested the capture buffer.
	 */
	protected function tearDown() {
		if ( $this->maintenance ) {
			$this->maintenance->outputChanneled( false );
		}
		parent::tearDown();
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
		$this->assertEquals( $preShutdownOutput, $this->getActualOutput(),
				"Output before shutdown simulation" );

		$this->maintenance->outputChanneled( false );

		$postShutdownOutput = $preShutdownOutput . ( $expectNLAppending ? "\n" : "" );
		$this->expectOutputString( $postShutdownOutput );
	}

}
