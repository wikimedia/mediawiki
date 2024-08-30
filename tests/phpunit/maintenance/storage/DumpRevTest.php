<?php

namespace MediaWiki\Tests\Maintenance;

use DumpRev;

/**
 * @covers \DumpRev
 * @group Database
 * @author Dreamy Jazz
 */
class DumpRevTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return DumpRev::class;
	}

	public function testExecuteForMissingRevisionId() {
		$this->maintenance->setArg( 'rev-id', 123 );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Row not found/' );
		$this->maintenance->execute();
	}

	public function testExecute() {
		$revId = $this->editPage( $this->getNonexistingTestPage(), 'testing1234' )->getNewRevision()->getId();
		$this->maintenance->setArg( 'rev-id', $revId );
		$this->maintenance->execute();
		$this->expectOutputString( "Text length: 11\ntesting1234\n" );
	}
}
