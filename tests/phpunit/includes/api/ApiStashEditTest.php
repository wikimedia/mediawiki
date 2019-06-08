<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers ApiStashEdit
 * @group API
 * @group medium
 * @group Database
 */
class ApiStashEditTest extends ApiTestCase {
	public function setUp() {
		parent::setUp();

		// We need caching here, but note that the cache gets cleared in between tests, so it
		// doesn't work with @depends
		$this->setMwGlobals( 'wgMainCacheType', 'hash' );
	}

	/**
	 * Make a stashedit API call with suitable default parameters
	 *
	 * @param array $params Query parameters for API request.  All are optional and will have
	 *   sensible defaults filled in.  To make a parameter actually not passed, set to null.
	 * @param User $user User to do the request
	 * @param string $expectedResult 'stashed', 'editconflict'
	 */
	protected function doStash(
		array $params = [], User $user = null, $expectedResult = 'stashed'
	) {
		$params = array_merge( [
			'action' => 'stashedit',
			'title' => __CLASS__,
			'contentmodel' => 'wikitext',
			'contentformat' => 'text/x-wiki',
			'baserevid' => 0,
		], $params );
		if ( !array_key_exists( 'text', $params ) &&
			!array_key_exists( 'stashedtexthash', $params )
		) {
			$params['text'] = 'Content';
		}
		foreach ( $params as $key => $val ) {
			if ( $val === null ) {
				unset( $params[$key] );
			}
		}

		if ( isset( $params['text'] ) ) {
			$expectedText = $params['text'];
		} elseif ( isset( $params['stashedtexthash'] ) ) {
			$expectedText = $this->getStashedText( $params['stashedtexthash'] );
		}
		if ( isset( $expectedText ) ) {
			$expectedText = rtrim( str_replace( "\r\n", "\n", $expectedText ) );
			$expectedHash = sha1( $expectedText );
			$origText = $this->getStashedText( $expectedHash );
		}

		$res = $this->doApiRequestWithToken( $params, null, $user );

		$this->assertSame( $expectedResult, $res[0]['stashedit']['status'] );
		$this->assertCount( $expectedResult === 'stashed' ? 2 : 1, $res[0]['stashedit'] );

		if ( $expectedResult === 'stashed' ) {
			$hash = $res[0]['stashedit']['texthash'];

			$this->assertSame( $expectedText, $this->getStashedText( $hash ) );

			$this->assertSame( $expectedHash, $hash );

			if ( isset( $params['stashedtexthash'] ) ) {
				$this->assertSame( $params['stashedtexthash'], $expectedHash, 'Sanity' );
			}
		} else {
			$this->assertSame( $origText, $this->getStashedText( $expectedHash ) );
		}

		$this->assertArrayNotHasKey( 'warnings', $res[0] );

		return $res;
	}

	/**
	 * Return the text stashed for $hash.
	 *
	 * @param string $hash
	 * @return string
	 */
	protected function getStashedText( $hash ) {
		$cache = ObjectCache::getLocalClusterInstance();
		$key = $cache->makeKey( 'stashedit', 'text', $hash );
		return $cache->get( $key );
	}

	/**
	 * Return a key that can be passed to the cache to obtain a PreparedEdit object.
	 *
	 * @param string $title Title of page
	 * @param string Content $text Content of edit
	 * @param User $user User who made edit
	 * @return string
	 */
	protected function getStashKey( $title = __CLASS__, $text = 'Content', User $user = null ) {
		$titleObj = Title::newFromText( $title );
		$content = new WikitextContent( $text );
		if ( !$user ) {
			$user = $this->getTestSysop()->getUser();
		}
		$wrapper = TestingAccessWrapper::newFromClass( ApiStashEdit::class );
		return $wrapper->getStashKey( $titleObj, $wrapper->getContentHash( $content ), $user );
	}

	public function testBasicEdit() {
		$this->doStash();
	}

	public function testBot() {
		// @todo This restriction seems arbitrary, is there any good reason to keep it?
		$this->setExpectedApiException( 'apierror-botsnotsupported' );

		$this->doStash( [], $this->getTestUser( [ 'bot' ] )->getUser() );
	}

	public function testUnrecognizedFormat() {
		$this->setExpectedApiException(
			[ 'apierror-badformat-generic', 'application/json', 'wikitext' ] );

		$this->doStash( [ 'contentformat' => 'application/json' ] );
	}

	public function testMissingTextAndStashedTextHash() {
		$this->setExpectedApiException( [
			'apierror-missingparam-one-of',
			Message::listParam( [ '<var>stashedtexthash</var>', '<var>text</var>' ] ),
			2
		] );
		$this->doStash( [ 'text' => null ] );
	}

	public function testStashedTextHash() {
		$res = $this->doStash();

		$this->doStash( [ 'stashedtexthash' => $res[0]['stashedit']['texthash'] ] );
	}

	public function testMalformedStashedTextHash() {
		$this->setExpectedApiException( 'apierror-stashedit-missingtext' );
		$this->doStash( [ 'stashedtexthash' => 'abc' ] );
	}

	public function testMissingStashedTextHash() {
		$this->setExpectedApiException( 'apierror-stashedit-missingtext' );
		$this->doStash( [ 'stashedtexthash' => str_repeat( '0', 40 ) ] );
	}

	public function testHashNormalization() {
		$res1 = $this->doStash( [ 'text' => "a\r\nb\rc\nd \t\n\r" ] );
		$res2 = $this->doStash( [ 'text' => "a\nb\rc\nd" ] );

		$this->assertSame( $res1[0]['stashedit']['texthash'], $res2[0]['stashedit']['texthash'] );
		$this->assertSame( "a\nb\rc\nd",
			$this->getStashedText( $res1[0]['stashedit']['texthash'] ) );
	}

	public function testNonexistentBaseRevId() {
		$this->setExpectedApiException( [ 'apierror-nosuchrevid', pow( 2, 31 ) - 1 ] );

		$name = ucfirst( __FUNCTION__ );
		$this->editPage( $name, '' );
		$this->doStash( [ 'title' => $name, 'baserevid' => pow( 2, 31 ) - 1 ] );
	}

	public function testPageWithNoRevisions() {
		$name = ucfirst( __FUNCTION__ );
		$rev = $this->editPage( $name, '' )->value['revision'];

		$this->setExpectedApiException( [ 'apierror-missingrev-pageid', $rev->getPage() ] );

		// Corrupt the database.  @todo Does the API really need to fail gracefully for this case?
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'page',
			[ 'page_latest' => 0 ],
			[ 'page_id' => $rev->getPage() ],
			__METHOD__
		);

		$this->doStash( [ 'title' => $name, 'baserevid' => $rev->getId() ] );
	}

	public function testExistingPage() {
		$name = ucfirst( __FUNCTION__ );
		$rev = $this->editPage( $name, '' )->value['revision'];

		$this->doStash( [ 'title' => $name, 'baserevid' => $rev->getId() ] );
	}

	public function testInterveningEdit() {
		$name = ucfirst( __FUNCTION__ );
		$oldRev = $this->editPage( $name, "A\n\nB" )->value['revision'];
		$this->editPage( $name, "A\n\nC" );

		$this->doStash( [
			'title' => $name,
			'baserevid' => $oldRev->getId(),
			'text' => "D\n\nB",
		] );
	}

	public function testEditConflict() {
		$name = ucfirst( __FUNCTION__ );
		$oldRev = $this->editPage( $name, 'A' )->value['revision'];
		$this->editPage( $name, 'B' );

		$this->doStash( [
			'title' => $name,
			'baserevid' => $oldRev->getId(),
			'text' => 'C',
		], null, 'editconflict' );
	}

	public function testDeletedRevision() {
		$name = ucfirst( __FUNCTION__ );
		$oldRev = $this->editPage( $name, 'A' )->value['revision'];
		$this->editPage( $name, 'B' );

		$this->setExpectedApiException( [ 'apierror-missingcontent-pageid', $oldRev->getPage() ] );

		$this->revisionDelete( $oldRev );

		$this->doStash( [
			'title' => $name,
			'baserevid' => $oldRev->getId(),
			'text' => 'C',
		] );
	}

	public function testDeletedRevisionSection() {
		$name = ucfirst( __FUNCTION__ );
		$oldRev = $this->editPage( $name, 'A' )->value['revision'];
		$this->editPage( $name, 'B' );

		$this->setExpectedApiException( 'apierror-sectionreplacefailed' );

		$this->revisionDelete( $oldRev );

		$this->doStash( [
			'title' => $name,
			'baserevid' => $oldRev->getId(),
			'text' => 'C',
			'section' => '1',
		] );
	}

	public function testPingLimiter() {
		$this->mergeMwGlobalArrayValue( 'wgRateLimits',
			[ 'stashedit' => [ '&can-bypass' => false, 'user' => [ 1, 60 ] ] ] );

		$this->doStash( [ 'text' => 'A' ] );

		$this->doStash( [ 'text' => 'B' ], null, 'ratelimited' );
	}

	/**
	 * Shortcut for calling ApiStashEdit::checkCache() without having to create Titles and Contents
	 * in every test.
	 *
	 * @param User $user
	 * @param string $text The text of the article
	 * @return stdClass|bool Return value of ApiStashEdit::checkCache(), false if not in cache
	 */
	protected function doCheckCache( User $user, $text = 'Content' ) {
		return ApiStashEdit::checkCache(
			Title::newFromText( __CLASS__ ),
			new WikitextContent( $text ),
			$user
		);
	}

	public function testCheckCache() {
		$user = $this->getMutableTestUser()->getUser();

		$this->doStash( [], $user );

		$this->assertInstanceOf( stdClass::class, $this->doCheckCache( $user ) );

		// Another user doesn't see the cache
		$this->assertFalse(
			$this->doCheckCache( $this->getTestUser()->getUser() ),
			'Cache is user-specific'
		);

		// Nor does the original one if they become a bot
		$user->addGroup( 'bot' );
		$this->assertFalse(
			$this->doCheckCache( $user ),
			"We assume bots don't have cache entries"
		);

		// But other groups are okay
		$user->removeGroup( 'bot' );
		$user->addGroup( 'sysop' );
		$this->assertInstanceOf( stdClass::class, $this->doCheckCache( $user ) );
	}

	public function testCheckCacheAnon() {
		$user = new User();

		$this->doStash( [], $user );

		$this->assertInstanceOf( stdClass::class, $this->docheckCache( $user ) );
	}

	/**
	 * Stash an edit some time in the past, for testing expiry and freshness logic.
	 *
	 * @param User $user Who's doing the editing
	 * @param string $text What text should be cached
	 * @param int $howOld How many seconds is "old" (we actually set it one second before this)
	 */
	protected function doStashOld(
		User $user, $text = 'Content', $howOld = ApiStashEdit::PRESUME_FRESH_TTL_SEC
	) {
		$this->doStash( [ 'text' => $text ], $user );

		// Monkey with the cache to make the edit look old.  @todo Is there a less fragile way to
		// fake the time?
		$key = $this->getStashKey( __CLASS__, $text, $user );

		$cache = ObjectCache::getLocalClusterInstance();

		$editInfo = $cache->get( $key );
		$outputKey = $cache->makeKey( 'stashed-edit-output', $editInfo->outputID );
		$editInfo->output = $cache->get( $outputKey );
		$editInfo->output->setCacheTime( wfTimestamp( TS_MW,
			wfTimestamp( TS_UNIX, $editInfo->output->getCacheTime() ) - $howOld - 1 ) );

		$cache->set( $key, $editInfo );
	}

	public function testCheckCacheOldNoEdits() {
		$user = $this->getTestSysop()->getUser();

		$this->doStashOld( $user );

		// Should still be good, because no intervening edits
		$this->assertInstanceOf( stdClass::class, $this->doCheckCache( $user ) );
	}

	public function testCheckCacheOldNoEditsAnon() {
		// Specify a made-up IP address to make sure no edits are lying around
		$user = User::newFromName( '192.0.2.77', false );

		$this->doStashOld( $user );

		// Should still be good, because no intervening edits
		$this->assertInstanceOf( stdClass::class, $this->doCheckCache( $user ) );
	}

	public function testCheckCacheInterveningEdits() {
		$user = $this->getTestSysop()->getUser();

		$this->doStashOld( $user );

		// Now let's also increment our editcount
		$this->editPage( ucfirst( __FUNCTION__ ), '' );

		$user->clearInstanceCache();
		$this->assertFalse( $this->doCheckCache( $user ),
			"Cache should be invalidated when it's old and the user has an intervening edit" );
	}

	/**
	 * @dataProvider signatureProvider
	 * @param string $text Which signature to test (~~~, ~~~~, or ~~~~~)
	 * @param int $ttl Expected TTL in seconds
	 */
	public function testSignatureTtl( $text, $ttl ) {
		$this->doStash( [ 'text' => $text ] );

		$cache = ObjectCache::getLocalClusterInstance();
		$key = $this->getStashKey( __CLASS__, $text );

		$wrapper = TestingAccessWrapper::newFromObject( $cache );

		$this->assertEquals( $ttl, $wrapper->bag[$key][HashBagOStuff::KEY_EXP] - time(), '', 1 );
	}

	public function signatureProvider() {
		return [
			'~~~' => [ '~~~', ApiStashEdit::MAX_SIGNATURE_TTL ],
			'~~~~' => [ '~~~~', ApiStashEdit::MAX_SIGNATURE_TTL ],
			'~~~~~' => [ '~~~~~', ApiStashEdit::MAX_SIGNATURE_TTL ],
		];
	}

	public function testIsInternal() {
		$res = $this->doApiRequest( [
			'action' => 'paraminfo',
			'modules' => 'stashedit',
		] );

		$this->assertCount( 1, $res[0]['paraminfo']['modules'] );
		$this->assertSame( true, $res[0]['paraminfo']['modules'][0]['internal'] );
	}

	public function testBusy() {
		// @todo This doesn't work because both lock acquisitions are in the same MySQL session, so
		// they don't conflict.  How do I open a different session?
		$this->markTestSkipped();

		$key = $this->getStashKey();
		$this->db->lock( $key, __METHOD__, 0 );
		try {
			$this->doStash( [], null, 'busy' );
		} finally {
			$this->db->unlock( $key, __METHOD__ );
		}
	}
}
