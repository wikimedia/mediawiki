<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\RevisionList\RevisionItem;
use MediaWiki\RevisionList\RevisionList;

/**
 * @covers \MediaWiki\RevisionList\RevisionList
 * @covers \MediaWiki\RevisionList\RevisionListBase
 * @covers \MediaWiki\RevisionList\RevisionItem
 * @covers \MediaWiki\RevisionList\RevisionItemBase
 * @group Database
 *
 * @author DannyS712
 */
class RevisionListTest extends MediaWikiIntegrationTestCase {

	public function testGetType() {
		$context = new RequestContext();
		$page = PageIdentityValue::localIdentity( 123, NS_MAIN, __METHOD__ );
		$revisionList = new RevisionList( $context, $page );

		$this->assertSame(
			'revision',
			$revisionList->getType()
		);
	}

	public function testNewItem() {
		// Need a row that is valid for RevisionFactory::newRevisionFromRow
		$wikiPage = $this->getExistingTestPage( __METHOD__ );
		$currentRevId = $wikiPage->getRevisionRecord()->getId();

		$queryBuilder = $this->getServiceContainer()->getRevisionStore()->newSelectQueryBuilder( $this->getDb() )
			->joinComment()
			->joinPage()
			->joinUser()
			->where( [ 'rev_id' => $currentRevId ] );
		$row = $queryBuilder->caller( __METHOD__ )->fetchRow();

		$context = new RequestContext();
		$context->setUser( $this->getTestSysop()->getUser() );

		$page = PageIdentityValue::localIdentity( 123, NS_MAIN, __METHOD__ );
		$revisionList = new RevisionList( $context, $page );

		$revisionItem = $revisionList->newItem( $row );
		$this->assertInstanceOf( RevisionItem::class, $revisionItem );

		// Tests for RevisionItem getters
		$this->assertSame( 'rev_id', $revisionItem->getIdField() );
		$this->assertSame( 'rev_timestamp', $revisionItem->getTimestampField() );
		$this->assertSame( 'rev_user', $revisionItem->getAuthorIdField() );
		$this->assertSame( 'rev_user_text', $revisionItem->getAuthorNameField() );

		// Tests for RevisionItemBase getters that are not overridden
		$this->assertSame( $currentRevId, $revisionItem->getId() );
		$this->assertSame( intval( $row->rev_user ), $revisionItem->getAuthorId() );
		$this->assertSame( strval( $row->rev_user_text ), $revisionItem->getAuthorName() );
		$this->assertSame(
			wfTimestamp( TS_MW, $row->rev_timestamp ),
			$revisionItem->getTimestamp()
		);

		// Text of the latest revision cannot be deleted, so it is always viewable
		$this->assertTrue( $revisionItem->canView() );
		$this->assertTrue( $revisionItem->canViewContent() );
		$this->assertFalse( $revisionItem->isDeleted() );
	}

}
