<?php

namespace MediaWiki\Tests\Maintenance;

use DeleteTag;

/**
 * @covers DeleteTag
 * @group Database
 * @author Dreamy Jazz
 */
class DeleteTagTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return DeleteTag::class;
	}

	public function testExecuteForNonExistingTag() {
		$this->maintenance->setArg( 'tag name', 'abcedef' );
		$this->expectOutputRegex( "/Tag 'abcedef' not found/" );
		$this->expectCallToFatalError();
		$this->maintenance->execute();
	}

	public function testExecuteForSoftwareDefinedTag() {
		// Get a page which has a mw-blank tag
		$testPage = $this->getExistingTestPage();
		$this->editPage( $testPage, '' );
		// Run the maintenance script and try to remove the mw-blank tag
		$this->maintenance->setArg( 'tag name', 'mw-blank' );
		$this->expectOutputRegex(
			"/Tags defined by an extension cannot be deleted unless the extension specifically allows it/"
		);
		$this->expectCallToFatalError();
		$this->maintenance->execute();
	}
}
