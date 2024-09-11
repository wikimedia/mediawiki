<?php

namespace MediaWiki\Tests\Maintenance;

use GetTextMaint;
use MediaWiki\Context\RequestContext;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use RevisionDeleter;

/**
 * @covers \GetTextMaint
 * @group Database
 * @author Dreamy Jazz
 */
class GetTextMaintTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return GetTextMaint::class;
	}

	public function testExecute() {
		$testPage = $this->getExistingTestPage();
		// Call ::execute
		$this->maintenance->setArg( 'title', $testPage );
		$this->maintenance->execute();
		// Verify that the content of the last revision of the page was outputted.
		$expectedOutput = $testPage->getContent( RevisionRecord::RAW )->serialize();
		if ( stream_isatty( STDOUT ) ) {
			$expectedOutput .= "\n";
		}
		$this->expectOutputString( $expectedOutput );
	}

	public function testExecuteForOldRevision() {
		// Get a test page with two revisions
		$testPage = $this->getExistingTestPage();
		$firstRevContent = $testPage->getContent( RevisionRecord::RAW )->serialize();
		$this->editPage( $testPage, 'testing1234' );
		// Call ::execute
		$this->maintenance->setArg( 'title', $testPage );
		$this->maintenance->setOption(
			'revision', $this->getServiceContainer()->getRevisionLookup()->getFirstRevision( $testPage )->getId()
		);
		$this->maintenance->execute();
		// Verify that the content of the first revision of the page was outputted.
		$expectedOutput = $firstRevContent;
		if ( stream_isatty( STDOUT ) ) {
			$expectedOutput .= "\n";
		}
		$this->expectOutputString( $expectedOutput );
	}

	/** @dataProvider provideInvalidTitleArgumentValues */
	public function testExecuteForInvalidTitleArgument( $title, $expectedOutputRegex ) {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->setArg( 'title', $title );
		$this->maintenance->execute();
	}

	public static function provideInvalidTitleArgumentValues() {
		return [
			'Invalid title' => [ ':::', '/::: is not a valid title/' ],
			'Title for a non-existent page' => [
				'Non-existing-test-page-1234', '/Non-existing-test-page-1234 does not exist/',
			],
		];
	}

	public function testExecuteForMissingRevId() {
		$testPage = $this->getExistingTestPage();
		// Call ::execute with an existing test page, but for a revision ID which does not exist.
		$this->maintenance->setArg( 'title', $testPage );
		$this->maintenance->setOption( 'revision', 123456 );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( "/Could not load revision 123456 of $testPage/" );
		$this->maintenance->execute();
	}

	private function getPageWithSuppressedFirstRevision() {
		// Get a page with two revisions
		$testPage = $this->getExistingTestPage();
		$firstRevId = $testPage->getRevisionRecord()->getId();
		$this->editPage( $testPage, 'testing123456' );
		// Hide the first revision associated with the test page
		RequestContext::getMain()->setUser( $this->getTestUser()->getUser() );
		$list = RevisionDeleter::createList( 'revision', RequestContext::getMain(), $testPage, [ $firstRevId ] );
		$list->setVisibility( [ 'value' => [ RevisionRecord::DELETED_TEXT => 1 ], 'comment' => 'Bye-bye' ] );
		return [ 'page' => $testPage, 'revId' => $firstRevId ];
	}

	public function testExecuteForSuppressedContent() {
		[ 'page' => $testPage, 'revId' => $firstRevId ] = $this->getPageWithSuppressedFirstRevision();
		// Call ::execute for the suppressed revision, expecting that the script will fail to find the content.
		$this->maintenance->setArg( 'title', $testPage );
		$this->maintenance->setOption( 'revision', $firstRevId );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( "/Couldn't extract the text from $testPage/" );
		$this->maintenance->execute();
	}

	public function testExecuteForSuppressedContentWithShowPrivateSet() {
		[ 'page' => $testPage, 'revId' => $firstRevId ] = $this->getPageWithSuppressedFirstRevision();
		// Call ::execute for the suppressed revision and the --show-private option, expecting that the script
		// find the content
		$this->maintenance->setArg( 'title', $testPage );
		$this->maintenance->setOption( 'revision', $firstRevId );
		$this->maintenance->setOption( 'show-private', 1 );
		$this->maintenance->execute();
		// Verify that the content of the first revision of the page was outputted.
		$expectedOutput = $this->getServiceContainer()->getRevisionStore()
			->getRevisionById( $firstRevId )
			->getContent( SlotRecord::MAIN, RevisionRecord::RAW )
			->getWikitextForTransclusion();
		if ( stream_isatty( STDOUT ) ) {
			$expectedOutput .= "\n";
		}
		$this->expectOutputString( $expectedOutput );
	}
}
