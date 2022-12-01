<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Storage\PageUpdateStatus;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Storage\PageUpdateStatus
 */
class PageUpdateStatusTest extends MediaWikiUnitTestCase {

	public function testNewRevision() {
		$status = PageUpdateStatus::newEmpty( false );

		$this->assertTrue( $status->isOK() );
		$this->assertFalse( $status->wasRevisionCreated() );
		$this->assertFalse( $status->wasPageCreated() );

		$page = PageIdentityValue::localIdentity( 7, NS_MAIN, 'Test' );
		$rev = new MutableRevisionRecord( $page );

		$status->setNewRevision( $rev );
		$this->assertTrue( $status->wasRevisionCreated() );
		$this->assertSame( $rev, $status->getNewRevision() );
	}

	public function testWasPageCreated() {
		$status = PageUpdateStatus::newEmpty( true );

		$this->assertTrue( $status->isOK() );
		$this->assertFalse( $status->wasPageCreated() );

		$page = PageIdentityValue::localIdentity( 7, NS_MAIN, 'Test' );
		$rev = new MutableRevisionRecord( $page );

		$status->setNewRevision( $rev );
		$this->assertTrue( $status->wasPageCreated() );
	}

	public function testFailedBecausePageExists() {
		$status = PageUpdateStatus::newEmpty( true );
		$this->assertFalse( $status->failedBecausePageExists() );

		$status->fatal( 'blabla' );
		$this->assertFalse( $status->failedBecausePageExists() );

		$status->fatal( 'edit-already-exists' );
		$this->assertTrue( $status->failedBecausePageExists() );
	}

	public function testFailedBecausePageMissing() {
		$status = PageUpdateStatus::newEmpty( false );
		$this->assertFalse( $status->failedBecausePageMissing() );

		$status->fatal( 'blabla' );
		$this->assertFalse( $status->failedBecausePageMissing() );

		$status->fatal( 'edit-gone-missing' );
		$this->assertTrue( $status->failedBecausePageMissing() );
	}

	public function testFailedBecauseOfConflict() {
		$status = PageUpdateStatus::newEmpty( false );
		$this->assertFalse( $status->failedBecauseOfConflict() );

		$status->fatal( 'blabla' );
		$this->assertFalse( $status->failedBecauseOfConflict() );

		$status->fatal( 'edit-conflict' );
		$this->assertTrue( $status->failedBecauseOfConflict() );
	}

}
