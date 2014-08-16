<?php

/**
 * Tests for action=revisiondelete
 * @covers APIRevisionDelete
 * @group API
 * @group medium
 * @group Database
 */
class ApiRevisionDeleteTest extends ApiTestCase {

	public static $page = 'Help:ApiRevDel_test';
	public $revs = array();

	protected function setUp() {
		// Needs to be before setup since this gets cached
		$this->mergeMwGlobalArrayValue( 'wgGroupPermissions', array( 'sysop' => array( 'deleterevision' => true ) ) );
		parent::setUp();
		// Make a few edits for us to play with
		for ( $i = 1; $i <= 5; $i++ ) {
			self::editPage( self::$page, MWCryptRand::generateHex( 10 ), 'summary' );
			$this->revs[] = Title::newFromText( self::$page )->getLatestRevID( Title::GAID_FOR_UPDATE );
		}

	}

	public function testHidingRevisions() {
		$user = self::$users['sysop']->user;
		$revid = array_shift( $this->revs );
		$out = $this->doApiRequest( array(
			'action' => 'revisiondelete',
			'type' => 'revision',
			'target' => self::$page,
			'ids' => $revid,
			'hide' => 'content|user|comment',
			'token' => $user->getEditToken(),
		) );
		// Check the output
		$out = $out[0]['revisiondelete'];
		$this->assertEquals( $out['status'], 'Success' );
		$this->assertArrayHasKey( 'items', $out );
		$item = $out['items'][0];
		$this->assertArrayHasKey( 'userhidden', $item );
		$this->assertArrayHasKey( 'commenthidden', $item );
		$this->assertArrayHasKey( 'texthidden', $item );
		$this->assertEquals( $item['id'], $revid );

		// Now check that that revision was actually hidden
		$rev = Revision::newFromId( $revid );
		$this->assertEquals( $rev->getContent( Revision::FOR_PUBLIC ), null );
		$this->assertEquals( $rev->getComment( Revision::FOR_PUBLIC ), '' );
		$this->assertEquals( $rev->getUser( Revision::FOR_PUBLIC ), 0 );

		// Now test unhiding!
		$out2 = $this->doApiRequest( array(
			'action' => 'revisiondelete',
			'type' => 'revision',
			'target' => self::$page,
			'ids' => $revid,
			'show' => 'content|user|comment',
			'token' => $user->getEditToken(),
		) );

		// Check the output
		$out2 = $out2[0]['revisiondelete'];
		$this->assertEquals( $out2['status'], 'Success' );
		$this->assertArrayHasKey( 'items', $out2 );
		$item = $out2['items'][0];

		$this->assertArrayNotHasKey( 'userhidden', $item );
		$this->assertArrayNotHasKey( 'commenthidden', $item );
		$this->assertArrayNotHasKey( 'texthidden', $item );

		$this->assertEquals( $item['id'], $revid );

		$rev = Revision::newFromId( $revid );
		$this->assertNotEquals( $rev->getContent( Revision::FOR_PUBLIC ), null );
		$this->assertNotEquals( $rev->getComment( Revision::FOR_PUBLIC ), '' );
		$this->assertNotEquals( $rev->getUser( Revision::FOR_PUBLIC ), 0 );
	}

	public function testUnhidingOutput() {
		$user = self::$users['sysop']->user;
		$revid = array_shift( $this->revs );
		// Hide revisions
		$this->doApiRequest( array(
			'action' => 'revisiondelete',
			'type' => 'revision',
			'target' => self::$page,
			'ids' => $revid,
			'hide' => 'content|user|comment',
			'token' => $user->getEditToken(),
		) );

		$out = $this->doApiRequest( array(
			'action' => 'revisiondelete',
			'type' => 'revision',
			'target' => self::$page,
			'ids' => $revid,
			'show' => 'comment',
			'token' => $user->getEditToken(),
		) );
		$out = $out[0]['revisiondelete'];
		$this->assertEquals( $out['status'], 'Success' );
		$this->assertArrayHasKey( 'items', $out );
		$item = $out['items'][0];
		// Check it has userhidden & texthidden keys
		// but no commenthidden key
		$this->assertArrayHasKey( 'userhidden', $item );
		$this->assertArrayNotHasKey( 'commenthidden', $item );
		$this->assertArrayHasKey( 'texthidden', $item );
		$this->assertEquals( $item['id'], $revid );
	}
}
