<?php

namespace MediaWiki\Tests\PageEdit;

use MediaWiki\Context\RequestContext;
use MediaWiki\Page\WikiPage;
use MediaWiki\PageEdit\PageEdit;
use MediaWiki\PageEdit\PageEditFactory;
use MediaWiki\PageEdit\PageEditInputs;
use MediaWikiIntegrationTestCase;
use TestUser;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\PageEdit\PageEdit
 * @group Database
 */
class PageEditIntegrationTest extends MediaWikiIntegrationTestCase {

	/**
	 * @return PageEdit
	 */
	private function newPageEdit(
		WikiPage $page,
		TestUser $user,
		string $textbox1 = 'Test',
		int $undidRev = 0,
		int $undoAfter = 0,
	) {
		$inputs = ( new PageEditInputs(
			authority: $user->getAuthority(),
			contentModel: $page->getTitle()->getContentModel(),
			context: RequestContext::getMain(),
			page: $page,
			summary: 'Edit summary',
			textbox1: $textbox1,
		) )->setUndidRev( $undidRev )
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

}
