<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MWCryptRand;

/**
 * Tests for action=revisiondelete
 * @covers \MediaWiki\Api\ApiRevisionDelete
 * @group API
 * @group medium
 * @group Database
 */
class ApiRevisionDeleteTest extends ApiTestCase {
	use MockAuthorityTrait;

	/** @var int[] */
	public $revs = [];

	protected function setUp(): void {
		parent::setUp();
		// Make a few edits for us to play with
		$title = Title::makeTitle( NS_HELP, 'ApiRevDel_test' );
		for ( $i = 1; $i <= 5; $i++ ) {
			$status = $this->editPage( $title, MWCryptRand::generateHex( 10 ), 'summary' );
			$this->revs[] = $status->getNewRevision()->getId();
		}
	}

	public function testHidingRevisions() {
		$performer = $this->mockRegisteredAuthorityWithPermissions( [ 'deleterevision' ] );
		$revid = array_shift( $this->revs );
		$out = $this->doApiRequestWithToken( [
			'action' => 'revisiondelete',
			'reason' => __METHOD__,
			'type' => 'revision',
			'target' => 'Help:ApiRevDel_test',
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
			'reason' => __METHOD__,
			'type' => 'revision',
			'target' => 'Help:ApiRevDel_test',
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
		$performer = $this->mockRegisteredAuthorityWithPermissions( [ 'deleterevision' ] );
		$revid = array_shift( $this->revs );
		// Hide revisions
		$this->doApiRequestWithToken( [
			'action' => 'revisiondelete',
			'reason' => __METHOD__,
			'type' => 'revision',
			'target' => 'Help:ApiRevDel_test',
			'ids' => $revid,
			'hide' => 'content|user|comment',
		], null, $performer );

		$out = $this->doApiRequestWithToken( [
			'action' => 'revisiondelete',
			'reason' => __METHOD__,
			'type' => 'revision',
			'target' => 'Help:ApiRevDel_test',
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
		$this->expectApiErrorCode( 'blocked' );
		$performer = $this->mockAnonAuthorityWithPermissions( [ 'deleterevision' ] );

		$title = Title::makeTitle( NS_HELP, 'ApiRevDel_test' );
		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'address' => $performer->getUser(),
				'by' => static::getTestSysop()->getUser(),
				'sitewide' => false,
				'restrictions' => [
					new PageRestriction( 0, $title->getArticleID() )
				]
			] );

		$revid = array_shift( $this->revs );

		$this->doApiRequestWithToken( [
			'action' => 'revisiondelete',
			'reason' => __METHOD__,
			'type' => 'revision',
			'target' => $title->getPrefixedText(),
			'ids' => $revid,
			'hide' => 'content|user|comment',
		], null, $performer );
	}
}
