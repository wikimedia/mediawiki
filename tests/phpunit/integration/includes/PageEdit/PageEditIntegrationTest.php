<?php

namespace MediaWiki\Tests\PageEdit;

use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Page\WikiPage;
use MediaWiki\PageEdit\PageEdit;
use MediaWiki\PageEdit\PageEditFactory;
use MediaWiki\PageEdit\PageEditInputs;
use MediaWiki\Revision\RevisionStore;
use MediaWikiIntegrationTestCase;
use TestUser;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\PageEdit\PageEdit
 * @covers \MediaWiki\PageEdit\PageEditInputs
 * @group Database
 */
class PageEditIntegrationTest extends MediaWikiIntegrationTestCase {

	/**
	 * @return PageEdit
	 */
	private function newPageEdit(
		WikiPage $page,
		TestUser $user,
		?string $edittime = null,
		?int $editRevId = null,
		string $section = '',
		string $summary = 'Edit summary',
		string $textbox1 = 'Test',
		int $undidRev = 0,
		int $undoAfter = 0,
	) {
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $user->getUser() );
		$inputs = ( new PageEditInputs(
			authority: $user->getAuthority(),
			contentModel: $page->getTitle()->getContentModel(),
			context: $context,
			page: $page,
			summary: $summary,
			textbox1: $textbox1,
		) )->setEdittime( $edittime )
			->setEditRevId( $editRevId )
			->setSection( $section )
			->setUndidRev( $undidRev )
			->setUndoAfter( $undoAfter );

		$factory = $this->getServiceContainer()->getService( '_PageEditFactory' );
		/** @var PageEditFactory $factory */
		return TestingAccessWrapper::newFromObject( $factory->newPageEdit( $inputs ) );
	}

	public function testIsUndoClean_Good() {
		$page = $this->getExistingTestPage();
		$initialRev = $this->editPage( $page, 'Initial revision' )->getNewRevision();
		$undoRev = $this->editPage( $page, 'Undo revision' )->getNewRevision();

		$pageEdit = $this->newPageEdit(
			$page,
			$this->getTestUser(),
			textbox1: 'Initial revision',
			undidRev: $undoRev->getId()
		);
		$this->assertTrue( $pageEdit->isUndoClean( $initialRev->getMainContentRaw(), $page ) );
	}

	public function testIsUndoClean_DifferentContent() {
		$page = $this->getExistingTestPage();
		$undoRev = $this->editPage( $page, 'Undo Revision' )->getNewRevision();

		$pageEdit = $this->newPageEdit( $page, $this->getTestUser(), undidRev: $undoRev->getId() );
		$this->assertFalse( $pageEdit->isUndoClean( $pageEdit->convertTextToContent( 'New content' ), $page ) );
	}

	public function testIsUndoClean_NoUndidRev() {
		$page = $this->getExistingTestPage();

		$pageEdit = $this->newPageEdit( $page, $this->getTestUser() );
		$this->assertFalse( $pageEdit->isUndoClean( $page->getContent(), $page ) );
	}

	public function testIsUndoClean_RevDeleted() {
		$page = $this->getExistingTestPage();
		$initialRev = $page->getRevisionRecord();
		$undoRev = $this->editPage( $page, 'Undo Revision' )->getNewRevision();
		$this->editPage( $page, 'Latest Revision' );
		$this->revisionDelete( $undoRev );

		$pageEdit = $this->newPageEdit( $page, $this->getTestUser(), undidRev: $undoRev->getId() );
		$this->assertFalse( $pageEdit->isUndoClean( $initialRev->getMainContentRaw(), $page ) );
	}

	public function testHasConflict_NoConflictWhenEditingLatestRevision() {
		$page = $this->getExistingTestPage();
		$user = $this->getTestUser();
		$pageEdit = $this->newPageEdit(
			$page,
			$user,
			edittime: $page->getTimestamp(),
			editRevId: $page->getLatest(),
		);
		$this->assertFalse( $pageEdit->hasConflict( $page ) );
	}

	public function testHasConflict_ConflictWhenEditingOutdatedRevision() {
		$page = $this->getExistingTestPage();
		$editRevId = $page->getLatest();
		$edittime = $page->getTimestamp();

		$this->editPage( $page, 'New revision' );

		// Mock the revision store so this test doesn't depend on the actual timestamps
		$mockRevisionStore = $this->createMock( RevisionStore::class );
		$mockRevisionStore->method( 'userWasLastToEdit' )->willReturn( false );
		$this->setService( 'RevisionStore', $mockRevisionStore );

		$user = $this->getTestUser();
		$pageEdit = $this->newPageEdit(
			$page,
			$user,
			edittime: $edittime,
			editRevId: $editRevId,
		);
		$this->assertTrue( $pageEdit->hasConflict( $page ) );
	}

	public function testHasConflict_NoConflictWithSelf() {
		$page = $this->getExistingTestPage();
		$user = $this->getTestUser();
		$edittime = $page->getTimestamp();

		$editRevId = $page->getLatest();

		$this->editPage( $page, 'New revision', performer: $user->getAuthority() );

		// Mock the revision store so we don't have to sleep for a second before making the
		// second edit; otherwise userWasLastToEdit() will return `false` if the initial revision
		// and the new revision were created at the same second
		$mockRevisionStore = $this->createMock( RevisionStore::class );
		$mockRevisionStore->method( 'userWasLastToEdit' )->willReturn( true );
		$this->setService( 'RevisionStore', $mockRevisionStore );

		$pageEdit = $this->newPageEdit(
			$page,
			$user,
			edittime: $edittime,
			editRevId: $editRevId,
		);
		$this->assertFalse( $pageEdit->hasConflict( $page ) );
	}

	public function testHasConflict_NoConflictForNewSection() {
		$page = $this->getExistingTestPage();
		$user = $this->getTestUser();
		$edittime = $page->getTimestamp();
		$editRevId = $page->getLatest();

		$this->editPage( $page, 'New revision' );

		$pageEdit = $this->newPageEdit(
			$page,
			$user,
			edittime: $edittime,
			editRevId: $editRevId,
			section: 'new'
		);
		$this->assertFalse( $pageEdit->hasConflict( $page ) );
	}

	public function testHasConflict_ConflictForDuplicateSectionSubmission() {
		$page = $this->getExistingTestPage();
		$edittime = $page->getTimestamp();
		$editRevId = $page->getLatest();

		$user = $this->getTestUser();
		$summary = '/* test */ new section';
		$this->editPage(
			$page,
			'New revision',
			summary: $summary,
			performer: $user->getAuthority(),
		);

		$pageEdit = $this->newPageEdit(
			$page,
			$user,
			edittime: $edittime,
			editRevId: $editRevId,
			section: 'new',
			summary: $summary,
		);
		$this->assertTrue( $pageEdit->hasConflict( $page ) );
	}

}
