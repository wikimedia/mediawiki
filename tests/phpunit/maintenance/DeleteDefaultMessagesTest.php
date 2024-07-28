<?php

namespace MediaWiki\Tests\Maintenance;

use DeleteDefaultMessages;
use IDBAccessObject;
use TestUser;

/**
 * @covers \DeleteDefaultMessages
 * @group Database
 * @author Dreamy Jazz
 */
class DeleteDefaultMessagesTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return DeleteDefaultMessages::class;
	}

	private function performMediaWikiDefaultEdit( $title, $content ) {
		$this->editPage(
			$title, $content, 'test', NS_MEDIAWIKI,
			$this->getServiceContainer()->getUserFactory()->newFromName( 'MediaWiki default' )
		);
	}

	public function testExecuteForNoRelevantPages() {
		$this->maintenance->execute();
		$this->expectOutputString( "Checking existence of old default messages...done.\n" );
	}

	public function testExecuteWhenNoMessagesLastEditedByMediaWikiDefault() {
		// Create MediaWiki:Edit with two revisions, with the latest not edited by MediaWiki default.
		$this->performMediaWikiDefaultEdit( 'MediaWiki:Edit', 'test-1234' );
		$this->editPage(
			'MediaWiki:Edit', 'testing-content', 'test', NS_MEDIAWIKI,
			$this->getTestUser()->getAuthority()
		);
		$this->testExecuteForNoRelevantPages();
		$this->assertTrue(
			$this->getServiceContainer()->getTitleFactory()->newFromText( 'MediaWiki:Edit' )->exists(),
			'MediaWiki:Edit was deleted when it should not have been.'
		);
	}

	public function testExecuteForDryRun() {
		// Create MediaWiki:Edit with two revisions by MediaWiki default, and MediaWiki:Hist with one revision by
		// MediaWiki default.
		$this->performMediaWikiDefaultEdit( 'MediaWiki:Edit', 'test-1234' );
		$this->performMediaWikiDefaultEdit( 'MediaWiki:Edit', 'test-12345' );
		$this->performMediaWikiDefaultEdit( 'MediaWiki:Hist', 'testing-1234' );
		// Run with 'dry-run' set.
		$this->maintenance->setOption( 'dry-run', 1 );
		$this->maintenance->execute();
		$this->assertTrue(
			$this->getServiceContainer()->getTitleFactory()->newFromText( 'MediaWiki:Edit' )->exists(),
			'MediaWiki:Edit was deleted on a dry run.'
		);
		$this->assertTrue(
			$this->getServiceContainer()->getTitleFactory()->newFromText( 'MediaWiki:Hist' )->exists(),
			'MediaWiki:Hist was deleted on a dry run.'
		);
		$this->expectOutputRegex(
			"/.*\n.*MediaWiki:Edit.*\n.*MediaWiki:Hist.*\n\nRun again without --dry-run to delete these pages.\n/"
		);
	}

	public function testExecute() {
		// Create MediaWiki:Edit with two revisions by MediaWiki default, and MediaWiki:Hist with one revision by
		// MediaWiki default.
		$editMessagePage = $this->getServiceContainer()->getTitleFactory()->newFromText( 'MediaWiki:Edit' );
		$histMessagePage = $this->getServiceContainer()->getTitleFactory()->newFromText( 'MediaWiki:Hist' );
		$diffMessagePage = $this->getServiceContainer()->getTitleFactory()->newFromText( 'MediaWiki:Diff' );
		$this->performMediaWikiDefaultEdit( $editMessagePage, 'test-1234' );
		$this->performMediaWikiDefaultEdit( $editMessagePage, 'test-12345' );
		$this->performMediaWikiDefaultEdit( $histMessagePage, 'testing-1234' );
		// Create a MediaWiki page which should not be deleted.
		$this->editPage(
			$diffMessagePage, 'testing-content', 'test', NS_MEDIAWIKI,
			$this->getTestUser()->getAuthority()
		);
		// Run ::execute to actually delete pages.
		$this->maintenance->execute();
		// Verify that only the correct pages have been deleted.
		$this->assertFalse(
			$editMessagePage->exists( IDBAccessObject::READ_LATEST ), 'MediaWiki:Edit should have been deleted.'
		);
		$this->assertFalse(
			$histMessagePage->exists( IDBAccessObject::READ_LATEST ), 'MediaWiki:Hist should have been deleted.'
		);
		$this->assertTrue(
			$diffMessagePage->exists( IDBAccessObject::READ_LATEST ), 'MediaWiki:Diff should not have been deleted.'
		);
		$this->expectOutputRegex(
			"/Checking existence of old default messages\.\.\.\n" .
			"\.\.\.deleting old default messages.*done!\n/"
		);
	}

	public function addDBData() {
		// Create the MediaWiki default user for each test.
		new TestUser( 'MediaWiki default' );
	}
}
