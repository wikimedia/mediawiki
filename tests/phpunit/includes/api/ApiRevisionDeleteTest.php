<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * Tests for action=revisiondelete
 * @covers APIRevisionDelete
 * @group API
 * @group medium
 * @group Database
 */
class ApiRevisionDeleteTest extends ApiTestCase {
	use MockAuthorityTrait;

	public static $page = 'Help:ApiRevDel_test';
	public $revs = [];

	protected function setUp(): void {
		parent::setUp();
		// Make a few edits for us to play with
		for ( $i = 1; $i <= 5; $i++ ) {
			$this->editPage( self::$page, MWCryptRand::generateHex( 10 ), 'summary' );
			$this->revs[] = Title::newFromText( self::$page )->getLatestRevID( Title::READ_LATEST );
		}
	}

	public function testHidingRevisions() {
		$performer = $this->mockAnonAuthorityWithPermissions( [ 'writeapi', 'deleterevision' ] );
		$revid = array_shift( $this->revs );
		$out = $this->doApiRequestWithToken( [
			'action' => 'revisiondelete',
			'type' => 'revision',
			'target' => self::$page,
			'ids' => $revid,
			'hide' => 'content|user|comment',
		], null, $performer );
		// Check the output
		$out = $out[0]['revisiondelete'];
		$this->assertEquals( 'Success', $out['status'] );
		$this->assertArrayHasKey( 'items', $out );
		$item = $out['items'][0];
		$this->assertTrue( $item['userhidden'], 'userhidden' );
		$this->assertTrue( $item['commenthidden'], 'commenthidden' );
		$this->assertTrue( $item['texthidden'], 'texthidden' );
		$this->assertEquals( $revid, $item['id'] );

		// Now check that that revision was actually hidden
		$revRecord = $this->getServiceContainer()
			->getRevisionLookup()
			->getRevisionById( $revid );
		$this->assertNull( $revRecord->getContent( SlotRecord::MAIN, RevisionRecord::FOR_PUBLIC ) );
		$this->assertNull( $revRecord->getComment( RevisionRecord::FOR_PUBLIC ) );
		$this->assertNull( $revRecord->getUser( RevisionRecord::FOR_PUBLIC ) );

		// Now test unhiding!
		$out2 = $this->doApiRequestWithToken( [
			'action' => 'revisiondelete',
			'type' => 'revision',
			'target' => self::$page,
			'ids' => $revid,
			'show' => 'content|user|comment',
		], null, $performer );

		// Check the output
		$out2 = $out2[0]['revisiondelete'];
		$this->assertEquals( 'Success', $out2['status'] );
		$this->assertArrayHasKey( 'items', $out2 );
		$item = $out2['items'][0];

		$this->assertFalse( $item['userhidden'], 'userhidden' );
		$this->assertFalse( $item['commenthidden'], 'commenthidden' );
		$this->assertFalse( $item['texthidden'], 'texthidden' );

		$this->assertEquals( $revid, $item['id'] );

		// Now check that that revision was actually unhidden
		$revRecord = $this->getServiceContainer()
			->getRevisionLookup()
			->getRevisionById( $revid );
		$this->assertNotNull( $revRecord->getContent( SlotRecord::MAIN, RevisionRecord::FOR_PUBLIC ) );
		$this->assertNotNull( $revRecord->getComment( RevisionRecord::FOR_PUBLIC ) );
		$this->assertNotNull( $revRecord->getUser( RevisionRecord::FOR_PUBLIC ) );
	}

	public function testUnhidingOutput() {
		$performer = $this->mockAnonAuthorityWithPermissions( [ 'writeapi', 'deleterevision' ] );
		$revid = array_shift( $this->revs );
		// Hide revisions
		$this->doApiRequestWithToken( [
			'action' => 'revisiondelete',
			'type' => 'revision',
			'target' => self::$page,
			'ids' => $revid,
			'hide' => 'content|user|comment',
		], null, $performer );

		$out = $this->doApiRequestWithToken( [
			'action' => 'revisiondelete',
			'type' => 'revision',
			'target' => self::$page,
			'ids' => $revid,
			'show' => 'comment',
		], null, $performer );
		$out = $out[0]['revisiondelete'];
		$this->assertEquals( 'Success', $out['status'] );
		$this->assertArrayHasKey( 'items', $out );
		$item = $out['items'][0];
		// Check it has userhidden & texthidden
		// but not commenthidden
		$this->assertTrue( $item['userhidden'], 'userhidden' );
		$this->assertFalse( $item['commenthidden'], 'commenthidden' );
		$this->assertTrue( $item['texthidden'], 'texthidden' );
		$this->assertEquals( $revid, $item['id'] );
	}

	public function testPartiallyBlockedPage() {
		$this->setExpectedApiException( 'apierror-blocked-partial' );
		$performer = $this->mockAnonAuthorityWithPermissions( [ 'writeapi', 'deleterevision' ] );

		$block = new DatabaseBlock( [
			'address' => $performer->getUser(),
			'by' => static::getTestSysop()->getUser(),
			'sitewide' => false,
		] );

		$block->setRestrictions( [
			new PageRestriction( 0, Title::newFromText( self::$page )->getArticleID() )
		] );
		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );

		$revid = array_shift( $this->revs );

		$this->doApiRequestWithToken( [
			'action' => 'revisiondelete',
			'type' => 'revision',
			'target' => self::$page,
			'ids' => $revid,
			'hide' => 'content|user|comment',
		], null, $performer );
	}
}
