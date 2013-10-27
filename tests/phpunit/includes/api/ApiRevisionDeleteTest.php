<?php

/**
 * Tests for action=revisiondelete
 *
 * @group API
 * @group medium
 */
class ApiRevisionDeleteTest extends ApiTestCase {

	static $page = 'Help:ApiRevDel_test';
	var $revs = array();

	function setUp() {
		parent::setUp();
		// Make a few edits for us to play with
		for ( $i = 1; $i <= 5; $i++ ) {
			self::editPage( self::$page, MWCryptRand::generateHex( 10 ), 'summary' );
			$this->revs[] = Title::newFromText( self::$page )->getLatestRevID( Title::GAID_FOR_UPDATE );
		}

		// Make sure we have the right permissions
		$wgGroupPermissions['sysop']['deleterevision'] = true;
		// Apparently that isn't working for jenkins so...
		$wgHooks['UserGetRights'][] = function( User $user, $rights ) {
			if ( $user->getName() == self::$users['sysop']->user->getName() ) {
				$rights[] = 'deleterevision';
			}
		};
	}

	function testHidingRevisions() {
		$user = self::$users['sysop'];
		$revid = array_shift( $this->revs );
		$out = $this->doApiRequest( array(
			'action' => 'revisiondelete',
			'type' => 'revision',
			'target' => self::$page,
			'ids' => $revid,
			'hide' => 'content|user|comment',
			'token' => $user->user->getEditToken(),
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
			'token' => $user->user->getEditToken(),
		) );

		// Check the output
		$out2 = $out2[0]['revisiondelete'];
		$this->assertEquals( $out2['status'], 'Success' );
		$this->assertArrayHasKey( 'items', $out2 );
		$item = $out2['items'][0];
		/*
		$this->assertArrayHasKey( 'userunhidden', $item );
		$this->assertArrayHasKey( 'commentunhidden', $item );
		$this->assertArrayHasKey( 'textunhidden', $item );
		*/
		$this->assertEquals( $item['id'], $revid );

		$rev = Revision::newFromId( $revid );
		$this->assertNotEquals( $rev->getContent( Revision::FOR_PUBLIC ), null );
		$this->assertNotEquals( $rev->getComment( Revision::FOR_PUBLIC ), '' );
		$this->assertNotEquals( $rev->getUser( Revision::FOR_PUBLIC ), 0 );
	}


}