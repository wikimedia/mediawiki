<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Content\CssContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Storage\PageEditStash;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserRigorOptions;
use Psr\Log\NullLogger;
use stdClass;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Api\ApiStashEdit
 * @covers \MediaWiki\Storage\PageEditStash
 * @group API
 * @group medium
 * @group Database
 * @todo Expand tests for temporary users
 */
class ApiStashEditTest extends ApiTestCase {
	use TempUserTestTrait;

	private const CLASS_NAME = 'ApiStashEditTest';

	protected function setUp(): void {
		parent::setUp();
		// Hack to make user edit tracker survive service reset.
		// We want it's cache to persist within tests run, otherwise
		// incorrect in-process cache is being reset, and we get outdated
		// edit counts.
		$this->setService( 'UserEditTracker', $this->getServiceContainer()
			->getUserEditTracker() );
		$this->setService( 'PageEditStash', new PageEditStash(
			new HashBagOStuff( [] ),
			$this->getServiceContainer()->getConnectionProvider(),
			new NullLogger(),
			StatsFactory::newNull(),
			$this->getServiceContainer()->getUserEditTracker(),
			$this->getServiceContainer()->getUserFactory(),
			$this->getServiceContainer()->getWikiPageFactory(),
			$this->getServiceContainer()->getHookContainer(),
			PageEditStash::INITIATOR_USER
		) );
	}

	/**
	 * Make a stashedit API call with suitable default parameters
	 *
	 * @param array $params Query parameters for API request.  All are optional and will have
	 *   sensible defaults filled in.  To make a parameter actually not passed, set to null.
	 * @param User|null $user User to do the request
	 * @param string $expectedResult 'stashed', 'editconflict'
	 * @return array
	 */
	protected function doStash(
		array $params = [], ?User $user = null, $expectedResult = 'stashed'
	) {
		$params = array_merge( [
			'action' => 'stashedit',
			'title' => self::CLASS_NAME,
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
				$this->assertSame( $expectedHash, $params['stashedtexthash'] );
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
		return $this->getServiceContainer()->getPageEditStash()->fetchInputText( $hash );
	}

	/**
	 * Return a key that can be passed to the cache to obtain a stashed edit object.
	 *
	 * @param string $title Title of page
	 * @param string $text Content of edit
	 * @param User|null $user User who made edit
	 * @return string
	 */
	protected function getStashKey( $title = self::CLASS_NAME, $text = 'Content', ?User $user = null ) {
		$titleObj = Title::newFromText( $title );
		$content = new WikitextContent( $text );
		if ( !$user ) {
			$user = $this->getTestSysop()->getUser();
		}
		$editStash = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getPageEditStash() );

		return $editStash->getStashKey( $titleObj, $editStash->getContentHash( $content ), $user );
	}

	public function testBasicEdit() {
		$this->doStash();
	}

	public function testBot() {
		// @todo This restriction seems arbitrary, is there any good reason to keep it?
		$this->expectApiErrorCode( 'botsnotsupported' );

		$this->doStash( [], $this->getTestUser( [ 'bot' ] )->getUser() );
	}

	public function testUnrecognizedFormat() {
		$this->expectApiErrorCode( 'badmodelformat' );

		$this->doStash( [ 'contentformat' => 'application/json' ] );
	}

	public function testMissingTextAndStashedTextHash() {
		$this->expectApiErrorCode( 'missingparam' );
		$this->doStash( [ 'text' => null ] );
	}

	public function testStashedTextHash() {
		$res = $this->doStash();

		$this->doStash( [ 'stashedtexthash' => $res[0]['stashedit']['texthash'] ] );
	}

	public function testMalformedStashedTextHash() {
		$this->expectApiErrorCode( 'missingtext' );
		$this->doStash( [ 'stashedtexthash' => 'abc' ] );
	}

	public function testMissingStashedTextHash() {
		$this->expectApiErrorCode( 'missingtext' );
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
		$this->expectApiErrorCode( 'nosuchrevid' );

		$name = ucfirst( __FUNCTION__ );
		$this->editPage( $name, '' );
		$this->doStash( [ 'title' => $name, 'baserevid' => pow( 2, 31 ) - 1 ] );
	}

	public function testPageWithNoRevisions() {
		$name = ucfirst( __FUNCTION__ );
		$revRecord = $this->editPage( $name, '' )->getNewRevision();

		$this->expectApiErrorCode( 'missingrev' );

		// Corrupt the database.  @todo Does the API really need to fail gracefully for this case?
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'page' )
			->set( [ 'page_latest' => 0 ] )
			->where( [ 'page_id' => $revRecord->getPageId() ] )
			->caller( __METHOD__ )->execute();

		$this->doStash( [ 'title' => $name, 'baserevid' => $revRecord->getId() ] );
	}

	public function testExistingPage() {
		$name = ucfirst( __FUNCTION__ );
		$revRecord = $this->editPage( $name, '' )->getNewRevision();

		$this->doStash( [ 'title' => $name, 'baserevid' => $revRecord->getId() ] );
	}

	public function testInterveningEdit() {
		$this->markTestSkippedIfNoDiff3();

		$name = ucfirst( __FUNCTION__ );
		$oldRevRecord = $this->editPage( $name, "A\n\nB" )->getNewRevision();
		$this->editPage( $name, "A\n\nC" );

		$this->doStash( [
			'title' => $name,
			'baserevid' => $oldRevRecord->getId(),
			'text' => "D\n\nB",
		] );
	}

	public function testEditConflict() {
		$name = ucfirst( __FUNCTION__ );
		$oldRevRecord = $this->editPage( $name, 'A' )->getNewRevision();
		$this->editPage( $name, 'B' );

		$this->doStash( [
			'title' => $name,
			'baserevid' => $oldRevRecord->getId(),
			'text' => 'C',
		], null, 'editconflict' );
	}

	public function testMidEditContentModelMismatch() {
		$name = ucfirst( __FUNCTION__ );
		$title = Title::makeTitle( NS_MAIN, $name );
		$content = new CssContent( 'Css' );
		$performer = $this->getTestSysop()->getAuthority();
		$revRecord = $this->editPage(
			$title,
			$content,
			'',
			NS_MAIN,
			$performer
		)->getNewRevision();
		$this->editPage(
			$title,
			new WikitextContent( 'Text' ),
			'',
			NS_MAIN,
			$performer
		);

		$this->expectApiErrorCode( 'contentmodel-mismatch' );
		$this->doStash( [ 'title' => $title->getPrefixedText(), 'baserevid' => $revRecord->getId() ] );
	}

	public function testDeletedRevision() {
		$name = ucfirst( __FUNCTION__ );
		$oldRevRecord = $this->editPage( $name, 'A' )->getNewRevision();
		$this->editPage( $name, 'B' );

		$this->expectApiErrorCode( 'missingrev' );

		$this->revisionDelete( $oldRevRecord );

		$this->doStash( [
			'title' => $name,
			'baserevid' => $oldRevRecord->getId(),
			'text' => 'C',
		] );
	}

	public function testDeletedRevisionSection() {
		$name = ucfirst( __FUNCTION__ );
		$oldRevRecord = $this->editPage( $name, 'A' )->getNewRevision();
		$this->editPage( $name, 'B' );

		$this->expectApiErrorCode( 'replacefailed' );

		$this->revisionDelete( $oldRevRecord );

		$this->doStash( [
			'title' => $name,
			'baserevid' => $oldRevRecord->getId(),
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
	 * Shortcut for calling PageStashEdit::checkCache() without
	 * having to create Titles and Contents in every test.
	 *
	 * @param UserIdentity $user
	 * @param string $text The text of the article
	 * @return stdClass|bool Return value of PageStashEdit::checkCache(), false if not in cache
	 */
	protected function doCheckCache( UserIdentity $user, $text = 'Content' ) {
		return $this->getServiceContainer()->getPageEditStash()->checkCache(
			Title::makeTitle( NS_MAIN, 'ApiStashEditTest' ),
			new WikitextContent( $text ),
			$user
		);
	}

	public function testCheckCache() {
		$user = $this->getMutableTestUser()->getUser();
		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		$userGroupManager = $this->getServiceContainer()->getUserGroupManager();

		$this->doStash( [], $user );

		$this->assertInstanceOf( stdClass::class, $this->doCheckCache( $user ) );

		// Another user doesn't see the cache
		$this->assertFalse(
			$this->doCheckCache( $this->getTestUser()->getUser() ),
			'Cache is user-specific'
		);

		// Nor does the original one if they become a bot
		$userGroupManager->addUserToGroup( $user, 'bot' );
		$permissionManager->invalidateUsersRightsCache();
		$this->assertFalse(
			$this->doCheckCache( $user ),
			"We assume bots don't have cache entries"
		);

		// But other groups are okay
		$userGroupManager->removeUserFromGroup( $user, 'bot' );
		$userGroupManager->addUserToGroup( $user, 'sysop' );
		$permissionManager->invalidateUsersRightsCache();
		$this->assertInstanceOf( stdClass::class, $this->doCheckCache( $user ) );
	}

	public function testCheckCacheAnon() {
		$this->disableAutoCreateTempUser();
		$user = $this->getServiceContainer()->getUserFactory()->newFromName( '174.5.4.6', UserRigorOptions::RIGOR_NONE );

		$this->doStash( [], $user );

		$this->assertInstanceOf( stdClass::class, $this->doCheckCache( $user ) );
	}

	/**
	 * Stash an edit some time in the past, for testing expiry and freshness logic.
	 *
	 * @param User $user Who's doing the editing
	 * @param string $text What text should be cached
	 * @param int $howOld How many seconds is "old" (we actually set it one second before this)
	 */
	protected function doStashOld(
		User $user, $text = 'Content', $howOld = PageEditStash::PRESUME_FRESH_TTL_SEC
	) {
		ConvertibleTimestamp::setFakeTime( ConvertibleTimestamp::now( TS_UNIX ) - $howOld - 1 );
		$this->doStash( [ 'text' => $text ], $user );
	}

	public function testCheckCacheOldNoEdits() {
		$user = $this->getTestSysop()->getUser();

		$this->doStashOld( $user );

		// Should still be good, because no intervening edits
		$this->assertInstanceOf( stdClass::class, $this->doCheckCache( $user ) );
	}

	public function testCheckCacheOldNoEditsAnon() {
		$this->disableAutoCreateTempUser();
		// Specify a made-up IP address to make sure no edits are lying around
		$user = $this->getServiceContainer()->getUserFactory()->newFromName( '172.0.2.77', UserRigorOptions::RIGOR_NONE );

		$this->doStashOld( $user );

		// Should still be good, because no intervening edits
		$this->assertInstanceOf( stdClass::class, $this->doCheckCache( $user ) );
	}

	public function testCheckCacheInterveningEdits() {
		$user = $this->getTestSysop()->getUser();

		$this->doStashOld( $user );

		// Now let's also increment our editcount
		$this->editPage( ucfirst( __FUNCTION__ ), '', '', NS_MAIN, $user );

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

		$editStash = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getPageEditStash() );
		$cache = $editStash->cache;
		$key = $this->getStashKey( self::CLASS_NAME, $text );

		$wrapper = TestingAccessWrapper::newFromObject( $cache );

		$this->assertEqualsWithDelta( $ttl, $wrapper->bag[$key][HashBagOStuff::KEY_EXP] - time(), 1 );
	}

	public static function signatureProvider() {
		return [
			'~~~' => [ '~~~', PageEditStash::MAX_SIGNATURE_TTL ],
			'~~~~' => [ '~~~~', PageEditStash::MAX_SIGNATURE_TTL ],
			'~~~~~' => [ '~~~~~', PageEditStash::MAX_SIGNATURE_TTL ],
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
		$this->getDb()->lock( $key, __METHOD__, 0 );
		try {
			$this->doStash( [], null, 'busy' );
		} finally {
			$this->getDb()->unlock( $key, __METHOD__ );
		}
	}
}
