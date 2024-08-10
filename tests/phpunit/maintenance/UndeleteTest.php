<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use Undelete;

/**
 * @covers \Undelete
 * @group Database
 * @author Dreamy Jazz
 */
class UndeleteTest extends MaintenanceBaseTestCase {
	use MockAuthorityTrait;

	protected function getMaintenanceClass() {
		return Undelete::class;
	}

	public function testExecute() {
		// Create a page and then delete it
		$testPage = $this->getExistingTestPage();
		$deleteStatus = $this->getServiceContainer()->getDeletePageFactory()
			->newDeletePage( $testPage, $this->mockRegisteredUltimateAuthority() )
			->deleteIfAllowed( 'test' );
		$this->assertStatusGood( $deleteStatus );
		$testPage->clear();
		$this->assertFalse( $testPage->exists() );
		// Call ::execute
		$this->maintenance->setArg( 'pagename', $testPage );
		$this->maintenance->execute();
		// Verify that the page was undeleted.
		$testPage->clear();
		$this->assertTrue( $testPage->exists() );
		$this->expectOutputString(
			"Undeleting " . $testPage->getTitle()->getPrefixedDBkey() . "...\n" .
			"done\n"
		);
	}

	public function testEmailResetOnInvalidTitle() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( "/Invalid title/" );
		$this->maintenance->setArg( 0, ':::' );
		$this->maintenance->execute();
	}

	public function testEmailResetOnInvalidUsername() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( "/Invalid username/" );
		$this->maintenance->setArg( 0, $this->getNonexistingTestPage() );
		$this->maintenance->setOption( 'user', 'Template:Testing#test' );
		$this->maintenance->execute();
	}

	public function testExecuteForPageWithNoDeletedRevisions() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/No matching pages found in the deletion archive/' );
		// Create a page and then delete it
		$testPage = $this->getExistingTestPage();
		// Call ::execute
		$this->maintenance->setArg( 'pagename', $testPage );
		$this->maintenance->execute();
	}
}
