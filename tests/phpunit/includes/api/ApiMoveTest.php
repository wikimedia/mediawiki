<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiMove
 */
class ApiMoveTest extends ApiTestCase {
	/** @var int $pageId ID of test page */
	protected static $pageId;

	public function addDBDataOnce() {
		$status = $this->editPage( __CLASS__, 'Move me!' );
		self::$pageId = $status->value['revision']->getPage();
	}

	/**
	 * @param string $from Prefixed name of source
	 * @param string $to Prefixed name of destination
	 * @param string $id Expected page id of the moved page
	 */
	protected function assertMoved( $from, $to, $id ) {
		$fromTitle = Title::newFromText( $from );
		$toTitle = Title::newFromText( $to );

		$this->assertTrue( $fromTitle->exists(),
			"Source {$fromTitle->getPrefixedText()} does not exist" );
		$this->assertTrue( $toTitle->exists(),
			"Destination {$toTitle->getPrefixedText()} does not exist" );

		$this->assertTrue( $fromTitle->isRedirect(),
			"Source {$fromTitle->getPrefixedText()} is not a redirect" );

		$target = Revision::newFromTitle( $fromTitle )->getContent()->getRedirectTarget();
		$this->assertSame( $toTitle->getPrefixedText(), $target->getPrefixedText() );

		$this->assertSame( $id, $toTitle->getArticleId() );
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

	public function testMoveNonexistentById() {
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

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => __CLASS__,
			'to' => '[',
		] );
	}

	// @todo File moving

	public function testPingLimiter() {
		global $wgRateLimits;

		$this->setExpectedException( ApiUsageException::class,
			"You've exceeded your rate limit. Please wait some time and try again." );

		$name = ucfirst( __FUNCTION__ );

		$this->setMwGlobals( 'wgMainCacheType', 'hash' );

		$this->stashMwGlobals( 'wgRateLimits' );
		$wgRateLimits['move'] = [ '&can-bypass' => false, 'user' => [ 1, 60 ] ];

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
			$this->assertSame( $id, Title::newFromText( "$name 2" )->getArticleId() );
			$this->assertFalse( Title::newFromText( "$name 3" )->exists(),
				"\"$name 3\" should not exist" );
		}
	}

	public function testTagsNoPermission() {
		$this->setExpectedException( ApiUsageException::class,
			'You do not have permission to apply change tags along with your changes.' );

		ChangeTags::defineTag( 'custom tag' );

		$this->setGroupPermissions( 'user', 'applychangetags', false );

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => __CLASS__,
			'to' => 'Different page',
			'tags' => 'custom tag',
		] );
	}

	public function testSelfMove() {
		$this->setExpectedException( ApiUsageException::class,
			'The title is the same; cannot move a page over itself.' );

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => __CLASS__,
			'to' => __CLASS__,
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
		$this->assertSame( $talkId, Title::newFromText( "Talk:$name" )->getArticleId() );
		$this->assertSame( $talkDestinationId,
			Title::newFromText( "Talk:$name 2" )->getArticleId() );
		$this->assertSame( [ [
			'message' => 'articleexists',
			'params' => [],
			'code' => 'articleexists',
			'type' => 'error',
		] ], $res[0]['move']['talkmove-errors'] );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveSubpages() {
		global $wgNamespacesWithSubpages;

		$name = ucfirst( __FUNCTION__ );

		$this->stashMwGlobals( 'wgNamespacesWithSubpages' );
		$wgNamespacesWithSubpages[NS_MAIN] = true;

		$pages = [ $name, "$name/1", "$name/2", "Talk:$name", "Talk:$name/1", "Talk:$name/3" ];
		$ids = [];
		foreach ( $pages as $page ) {
			$ids[$page] = $this->createPage( $page );
		}

		$this->createPage( "$name/error" );
		$this->createPage( "$name 2/error" );

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
}
