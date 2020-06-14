<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * @covers RevisionList
 * @covers RevisionListBase
 * @covers RevisionItem
 * @covers RevisionItemBase
 * @group Database
 *
 * @author DannyS712
 */
class RevisionListTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		parent::setUp();

		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'comment';
		$this->tablesUsed[] = 'content';
		$this->tablesUsed[] = 'user';
	}

	public function testGetType() {
		$context = new RequestContext();
		$title = Title::newFromText( __METHOD__ );
		$revisionList = new RevisionList( $context, $title );

		$this->assertSame(
			'revision',
			$revisionList->getType()
		);
	}

	/**
	 * @dataProvider provideTestDoQuery
	 */
	public function testDoQuery( $filterIds ) {
		$context = new RequestContext();

		$title = $this->createMock( Title::class );
		$title->expects( $this->once() )
			->method( 'getArticleID' )
			->willReturn( 123 );

		$revisionList = new RevisionList( $context, $title );

		$conds = [ 'rev_page' => 123 ];
		if ( $filterIds !== false ) {
			$revisionList->filterByIds( $filterIds );
			$conds['rev_id'] = $filterIds;
		}

		$revQuery = MediaWikiServices::getInstance()
			->getRevisionStore()
			->getQueryInfo( [ 'page', 'user' ] );

		$db = $this->createMock( IDatabase::class );
		$db->expects( $this->once() )
			->method( 'select' )
			->with(
				$this->equalTo( $revQuery['tables'] ),
				$this->equalTo( $revQuery['fields'] ),
				$this->equalTo( $conds ),
				$this->equalTo( 'RevisionList::doQuery' ),
				$this->equalTo( [ 'ORDER BY' => 'rev_id DESC' ] ),
				$this->equalTo( $revQuery['joins'] )
			)
			->willReturn(
				new FakeResultWrapper( [] )
			);

		$revisionList->doQuery( $db );
	}

	public function provideTestDoQuery() {
		return [
			'no filter' => [ false ],
			'with filter' => [ [ 1, 2, 91 ] ],
		];
	}

	public function testNewItem() {
		// Need a row that is valid for RevisionFactory::newRevisionFromRow
		$wikiPage = $this->getExistingTestPage( __METHOD__ );
		$currentRevId = $wikiPage->getRevisionRecord()->getId();

		$revQuery = MediaWikiServices::getInstance()
			->getRevisionStore()
			->getQueryInfo( [ 'page', 'user' ] );
		$row = $this->db->selectRow(
			$revQuery['tables'],
			$revQuery['fields'],
			[ 'rev_id' => $currentRevId ],
			__METHOD__,
			[],
			$revQuery['joins']
		);

		$context = new RequestContext();
		$context->setUser( $this->getTestSysop()->getUser() );

		$title = Title::newFromText( __METHOD__ );
		$revisionList = new RevisionList( $context, $title );

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
