<?php

use MediaWiki\Block\DatabaseBlock;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiMove
 */
class ApiMoveTest extends ApiTestCase {
	/**
	 * @param string $from Prefixed name of source
	 * @param string $to Prefixed name of destination
	 * @param string $id Page id of the page to move
	 * @param array|string|null $opts Options: 'noredirect' to expect no redirect
	 */
	protected function assertMoved( $from, $to, $id, $opts = null ) {
		$opts = (array)$opts;

		Title::clearCaches();
		$fromTitle = Title::newFromText( $from );
		$toTitle = Title::newFromText( $to );

		$this->assertTrue( $toTitle->exists(),
			"Destination {$toTitle->getPrefixedText()} does not exist" );

		if ( in_array( 'noredirect', $opts ) ) {
			$this->assertFalse( $fromTitle->exists(),
				"Source {$fromTitle->getPrefixedText()} exists" );
		} else {
			$this->assertTrue( $fromTitle->exists(),
				"Source {$fromTitle->getPrefixedText()} does not exist" );
			$this->assertTrue( $fromTitle->isRedirect(),
				"Source {$fromTitle->getPrefixedText()} is not a redirect" );

			$target = Revision::newFromTitle( $fromTitle )->getContent()->getRedirectTarget();
			$this->assertSame( $toTitle->getPrefixedText(), $target->getPrefixedText() );
		}

		$this->assertSame( $id, $toTitle->getArticleID() );
	}

	/**
	 * Shortcut function to create a page and return its id.
	 *
	 * @param string $name Page to create
	 * @return int ID of created page
	 */
	protected function createPage( $name ) {
		return $this->editPage( $name, 'Content' )->value['revision']->getPage();
	}

	public function testFromWithFromid() {
		$this->setExpectedException( ApiUsageException::class,
			'The parameters "from" and "fromid" can not be used together.' );

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => 'Some page',
			'fromid' => 123,
			'to' => 'Some other page',
		] );
	}

	public function testMove() {
		$name = ucfirst( __FUNCTION__ );

		$id = $this->createPage( $name );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $name,
			'to' => "$name 2",
		] );

		$this->assertMoved( $name, "$name 2", $id );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveById() {
		$name = ucfirst( __FUNCTION__ );

		$id = $this->createPage( $name );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'fromid' => $id,
			'to' => "$name 2",
		] );

		$this->assertMoved( $name, "$name 2", $id );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveNonexistent() {
		$this->setExpectedException( ApiUsageException::class,
			"The page you specified doesn't exist." );

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => 'Nonexistent page',
			'to' => 'Different page'
		] );
	}

	public function testMoveNonexistentId() {
		$this->setExpectedException( ApiUsageException::class,
			'There is no page with ID 2147483647.' );

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'fromid' => pow( 2, 31 ) - 1,
			'to' => 'Different page',
		] );
	}

	public function testMoveToInvalidPageName() {
		$this->setExpectedException( ApiUsageException::class, 'Bad title "[".' );

		$name = ucfirst( __FUNCTION__ );
		$id = $this->createPage( $name );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'move',
				'from' => $name,
				'to' => '[',
			] );
		} finally {
			$this->assertSame( $id, Title::newFromText( $name )->getArticleID() );
		}
	}

	public function testMoveWhileBlocked() {
		$this->assertNull( DatabaseBlock::newFromTarget( '127.0.0.1' ), 'Sanity check' );

		$block = new DatabaseBlock( [
			'address' => self::$users['sysop']->getUser()->getName(),
			'by' => self::$users['sysop']->getUser()->getId(),
			'reason' => 'Capriciousness',
			'timestamp' => '19370101000000',
			'expiry' => 'infinity',
			'enableAutoblock' => true,
		] );
		$block->insert();

		$name = ucfirst( __FUNCTION__ );
		$id = $this->createPage( $name );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'move',
				'from' => $name,
				'to' => "$name 2",
			] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			$this->assertSame( 'You have been blocked from editing.', $ex->getMessage() );
			$this->assertNotNull( DatabaseBlock::newFromTarget( '127.0.0.1' ), 'Autoblock spread' );
		} finally {
			$block->delete();
			self::$users['sysop']->getUser()->clearInstanceCache();
			$this->assertSame( $id, Title::newFromText( $name )->getArticleID() );
		}
	}

	// @todo File moving

	public function testPingLimiter() {
		$this->setExpectedException( ApiUsageException::class,
			"You've exceeded your rate limit. Please wait some time and try again." );

		$name = ucfirst( __FUNCTION__ );

		$this->setMwGlobals( 'wgMainCacheType', 'hash' );

		$this->mergeMwGlobalArrayValue( 'wgRateLimits',
			[ 'move' => [ '&can-bypass' => false, 'user' => [ 1, 60 ] ] ] );

		$id = $this->createPage( $name );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $name,
			'to' => "$name 2",
		] );

		$this->assertMoved( $name, "$name 2", $id );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'move',
				'from' => "$name 2",
				'to' => "$name 3",
			] );
		} finally {
			$this->assertSame( $id, Title::newFromText( "$name 2" )->getArticleID() );
			$this->assertFalse( Title::newFromText( "$name 3" )->exists(),
				"\"$name 3\" should not exist" );
		}
	}

	public function testTagsNoPermission() {
		$this->setExpectedException( ApiUsageException::class,
			'You do not have permission to apply change tags along with your changes.' );

		$name = ucfirst( __FUNCTION__ );

		ChangeTags::defineTag( 'custom tag' );

		$this->setGroupPermissions( 'user', 'applychangetags', false );

		$id = $this->createPage( $name );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'move',
				'from' => $name,
				'to' => "$name 2",
				'tags' => 'custom tag',
			] );
		} finally {
			$this->assertSame( $id, Title::newFromText( $name )->getArticleID() );
			$this->assertFalse( Title::newFromText( "$name 2" )->exists(),
				"\"$name 2\" should not exist" );
		}
	}

	public function testSelfMove() {
		$this->setExpectedException( ApiUsageException::class,
			'The title is the same; cannot move a page over itself.' );

		$name = ucfirst( __FUNCTION__ );
		$this->createPage( $name );

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $name,
			'to' => $name,
		] );
	}

	public function testMoveTalk() {
		$name = ucfirst( __FUNCTION__ );

		$id = $this->createPage( $name );
		$talkId = $this->createPage( "Talk:$name" );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $name,
			'to' => "$name 2",
			'movetalk' => '',
		] );

		$this->assertMoved( $name, "$name 2", $id );
		$this->assertMoved( "Talk:$name", "Talk:$name 2", $talkId );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveTalkFailed() {
		$name = ucfirst( __FUNCTION__ );

		$id = $this->createPage( $name );
		$talkId = $this->createPage( "Talk:$name" );
		$talkDestinationId = $this->createPage( "Talk:$name 2" );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $name,
			'to' => "$name 2",
			'movetalk' => '',
		] );

		$this->assertMoved( $name, "$name 2", $id );
		$this->assertSame( $talkId, Title::newFromText( "Talk:$name" )->getArticleID() );
		$this->assertSame( $talkDestinationId,
			Title::newFromText( "Talk:$name 2" )->getArticleID() );
		$this->assertSame( [ [
			'message' => 'articleexists',
			'params' => [],
			'code' => 'articleexists',
			'type' => 'error',
		] ], $res[0]['move']['talkmove-errors'] );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveSubpages() {
		$name = ucfirst( __FUNCTION__ );

		$this->mergeMwGlobalArrayValue( 'wgNamespacesWithSubpages', [ NS_MAIN => true ] );

		$pages = [ $name, "$name/1", "$name/2", "Talk:$name", "Talk:$name/1", "Talk:$name/3" ];
		$ids = [];
		foreach ( array_merge( $pages, [ "$name/error", "$name 2/error" ] ) as $page ) {
			$ids[$page] = $this->createPage( $page );
		}

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $name,
			'to' => "$name 2",
			'movetalk' => '',
			'movesubpages' => '',
		] );

		foreach ( $pages as $page ) {
			$this->assertMoved( $page, str_replace( $name, "$name 2", $page ), $ids[$page] );
		}

		$this->assertSame( $ids["$name/error"],
			Title::newFromText( "$name/error" )->getArticleID() );
		$this->assertSame( $ids["$name 2/error"],
			Title::newFromText( "$name 2/error" )->getArticleID() );

		$results = array_merge( $res[0]['move']['subpages'], $res[0]['move']['subpages-talk'] );
		foreach ( $results as $arr ) {
			if ( $arr['from'] === "$name/error" ) {
				$this->assertSame( [ [
					'message' => 'articleexists',
					'params' => [],
					'code' => 'articleexists',
					'type' => 'error'
				] ], $arr['errors'] );
			} else {
				$this->assertSame( str_replace( $name, "$name 2", $arr['from'] ), $arr['to'] );
			}
			$this->assertCount( 2, $arr );
		}

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveNoPermission() {
		$this->setExpectedException( ApiUsageException::class,
			'You must be a registered user and [[Special:UserLogin|logged in]] to move a page.' );

		$name = ucfirst( __FUNCTION__ );

		$id = $this->createPage( $name );

		$user = new User();

		try {
			$this->doApiRequestWithToken( [
				'action' => 'move',
				'from' => $name,
				'to' => "$name 2",
			], null, $user );
		} finally {
			$this->assertSame( $id, Title::newFromText( "$name" )->getArticleID() );
			$this->assertFalse( Title::newFromText( "$name 2" )->exists(),
				"\"$name 2\" should not exist" );
		}
	}

	public function testSuppressRedirect() {
		$name = ucfirst( __FUNCTION__ );

		$id = $this->createPage( $name );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $name,
			'to' => "$name 2",
			'noredirect' => '',
		] );

		$this->assertMoved( $name, "$name 2", $id, 'noredirect' );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testSuppressRedirectNoPermission() {
		$name = ucfirst( __FUNCTION__ );

		$this->setGroupPermissions( 'sysop', 'suppressredirect', false );
		$id = $this->createPage( $name );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $name,
			'to' => "$name 2",
			'noredirect' => '',
		] );

		$this->assertMoved( $name, "$name 2", $id );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveSubpagesError() {
		$name = ucfirst( __FUNCTION__ );

		// Subpages are allowed in talk but not main
		$idBase = $this->createPage( "Talk:$name" );
		$idSub = $this->createPage( "Talk:$name/1" );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => "Talk:$name",
			'to' => $name,
			'movesubpages' => '',
		] );

		$this->assertMoved( "Talk:$name", $name, $idBase );
		$this->assertSame( $idSub, Title::newFromText( "Talk:$name/1" )->getArticleID() );
		$this->assertFalse( Title::newFromText( "$name/1" )->exists(),
			"\"$name/1\" should not exist" );

		$this->assertSame( [ 'errors' => [ [
			'message' => 'namespace-nosubpages',
			'params' => [ '' ],
			'code' => 'namespace-nosubpages',
			'type' => 'error',
		] ] ], $res[0]['move']['subpages'] );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}
}
