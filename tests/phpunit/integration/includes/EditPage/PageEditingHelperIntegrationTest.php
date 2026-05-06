<?php

namespace MediaWiki\Tests\Integration\EditPage;

use MediaWiki\EditPage\PageEditingHelper;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\EditPage\PageEditingHelper
 * @group Database
 */
class PageEditingHelperIntegrationTest extends MediaWikiIntegrationTestCase {

	private function getPageEditingHelper(): PageEditingHelper {
		return $this->getServiceContainer()->getService( '_PageEditingHelper' );
	}

	public function testGuessSectionName() {
		$helper = $this->getPageEditingHelper();
		$this->assertEquals(
			'#Test',
			$helper->guessSectionName( 'Test' ),
		);
		$this->assertEquals(
			'#Test_Section',
			$helper->guessSectionName( 'Test Section' ),
		);
		$this->assertEquals(
			'#Test_Section',
			$helper->guessSectionName( '  Test Section  ' ),
			'Whitespace should be trimmed around the guessed section name.'
		);
		$this->assertEquals(
			'#%F0%9F%98%80',
			$helper->guessSectionName( '😀' ),
			'Special characters should be URL-encoded in the guessed section name (T216029).'
		);
	}

	public function testGetUndoContent_SingleEdit() {
		$page = $this->getExistingTestPage();
		$oldContent = $page->getContent();
		$oldRev = $page->getRevisionRecord();
		$undoRev = $this->editPage( $page, 'test' )->getNewRevision();

		$content = $this->getPageEditingHelper()->getUndoContent( $page, $undoRev, $oldRev, $error );
		$this->assertTrue(
			$oldContent->equals( $content ),
			'When undoing a single edit, the undo content should be identical to the old content.'
		);
	}

	public function testGetUndoContent_MultipleEdits() {
		$page = $this->getExistingTestPage();
		$oldContent = $page->getContent();
		$oldRev = $page->getRevisionRecord();
		$this->editPage( $page, 'test1' );
		$undoRev = $this->editPage( $page, 'test2' )->getNewRevision();

		$content = $this->getPageEditingHelper()->getUndoContent( $page, $undoRev, $oldRev, $error );
		$this->assertTrue(
			$oldContent->equals( $content ),
			'When undoing multiple edits, the undo content should be identical to the old content.'
		);
	}

	public function testGetUndoContent_Conflict() {
		$page = $this->getExistingTestPage();
		$oldRev = $page->getRevisionRecord();
		$undoRev = $this->editPage( $page, 'test1' )->getNewRevision();
		$this->editPage( $page, 'test2' );

		$content = $this->getPageEditingHelper()->getUndoContent( $page, $undoRev, $oldRev, $error );
		$this->assertFalse(
			$content,
			'When undoing multiple edits with a conflict, getUndoContent should return false.'
		);
		$this->assertEquals(
			'failure',
			$error,
			'When undoing multiple edits with a conflict, getUndoContent should set the error to "failure".'
		);
	}

	public function testGetOriginalContent() {
		$page = $this->getExistingTestPage();

		$this->assertTrue(
			$page->getContent()->equals( $this->getPageEditingHelper()->getOriginalContent(
				$this->getTestUser()->getAuthority(),
				$page,
				$page->getRevisionRecord(),
				$page->getContentModel(),
				''
			) ),
			'The original content should be the same as the current content for an existing page.'
		);

		$this->deletePage( $page );

		$this->assertTrue(
			$this->getPageEditingHelper()->getOriginalContent(
				$this->getTestUser()->getAuthority(),
				$page,
				$page->getRevisionRecord(),
				$page->getContentModel(),
				''
			)->isEmpty(),
			'The original content should be empty for a page that was deleted and does no longer exist.'
		);
	}

	public function testGetCurrentContent() {
		$page = $this->getExistingTestPage();
		$this->assertTrue(
			$page->getContent()->equals(
				$this->getPageEditingHelper()->getCurrentContent( $page->getContentModel(), $page )
			),
			'The current content should be returned for an existing page.'
		);
	}

	public function testGetExpectedParentRevision() {
		$page = $this->getExistingTestPage();
		$rev = $page->getRevisionRecord();

		$this->editPage( $page, 'test' );

		$this->assertEquals(
			$rev->getId(),
			$this->getPageEditingHelper()->getExpectedParentRevision( $rev->getId(), null, $page )->getId(),
			'When a rev ID is provided, getExpectedParentRevision should return the revision with that ID.'
		);
		$this->assertEquals(
			$rev->getId(),
			$this->getPageEditingHelper()->getExpectedParentRevision( null, $rev->getTimestamp(), $page )->getId(),
			'When a timestamp is provided, getExpectedParentRevision should return the revision with that timestamp.'
		);
	}

}
