<?php

namespace MediaWiki\Tests\Integration\Context;

use LogicException;
use MediaWiki\Actions\ActionFactory;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\PHPSessionHandler;
use MediaWiki\Skin\Skin;
use MediaWiki\Skin\SkinFallback;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\StaticUserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserOptionsLookup;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Context\RequestContext
 * @group Database
 * @group RequestContext
 */
class RequestContextTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Context\RequestContext::sanitizeLangCode
	 *
	 * @dataProvider provideSanitizeLangCode
	 */
	public function testSanitizeLangCode(
		?string $input, string $expected
	): void {
		$this->assertSame(
			$expected,
			RequestContext::sanitizeLangCode( $input )
		);
	}

	public static function provideSanitizeLangCode() {
		global $wgLanguageCode;

		yield 'Null' => [ null, $wgLanguageCode ];
		yield 'Blank' => [ '', $wgLanguageCode ];

		yield 'Current' => [ $wgLanguageCode, $wgLanguageCode ];

		yield 'Non-English' => [ 'fr', 'fr' ];
		yield 'Documentation falls back to default' => [ 'qqq', $wgLanguageCode ];

		yield 'Sub-codes' => [ 'fr-fr', 'fr-fr' ];

		yield 'Lower-casing' => [ 'en-GB', 'en-gb' ];

		yield 'Valid codes unknown to MW' => [ 'zzz', 'zzz' ];
		yield 'Valid sub-codes unknown to MW' => [ 'en-IN', 'en-in' ];
		yield 'Extended codes' => [ 'en-US-aave', 'en-us-aave' ];

		yield 'Invalid code' => [ 'z!!z', 'z!!z' ];

		yield 'Attempted XSS code' => [ 'a&#0', $wgLanguageCode ];
	}

	/**
	 * Test the relationship between title and wikipage in RequestContext
	 * @covers \MediaWiki\Context\RequestContext::getWikiPage
	 * @covers \MediaWiki\Context\RequestContext::getTitle
	 */
	public function testWikiPageTitle() {
		$context = new RequestContext();

		$curTitle = Title::makeTitle( NS_MAIN, "A" );
		$context->setTitle( $curTitle );
		$this->assertTrue( $curTitle->equals( $context->getWikiPage()->getTitle() ),
			"When a title is first set WikiPage should be created on-demand for that title." );

		$curTitle = Title::makeTitle( NS_MAIN, "B" );
		$context->setWikiPage( $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $curTitle ) );
		$this->assertTrue( $curTitle->equals( $context->getTitle() ),
			"Title must be updated when a new WikiPage is provided." );

		$curTitle = Title::makeTitle( NS_MAIN, "C" );
		$context->setTitle( $curTitle );
		$this->assertTrue(
			$curTitle->equals( $context->getWikiPage()->getTitle() ),
			"When a title is updated the WikiPage should be purged "
				. "and recreated on-demand with the new title."
		);
	}

	/**
	 * @covers \MediaWiki\Context\RequestContext::importScopedSession
	 */
	public function testImportScopedSession() {
		// Make sure session handling is started
		if ( !PHPSessionHandler::isInstalled() ) {
			PHPSessionHandler::install(
				$this->getServiceContainer()->getSessionManager()
			);
		}
		$oldSessionId = session_id();

		$context = RequestContext::getMain();

		$oInfo = $context->exportSession();
		$this->assertEquals( '127.0.0.1', $oInfo['ip'], "Correct initial IP address." );
		$this->assertSame( 0, $oInfo['userId'], "Correct initial user ID." );
		$this->assertFalse( $context->getRequest()->getSession()->isPersistent(),
			'Global session isn\'t persistent to start' );

		$user = User::newFromName( 'UnitTestContextUser' );
		$user->addToDatabase();

		$sinfo = [
			'sessionId' => 'd612ee607c87e749ef14da4983a702cd',
			'userId' => $user->getId(),
			'ip' => '192.0.2.0',
			'headers' => [
				'USER-AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:18.0) Gecko/20100101 Firefox/18.0'
			]
		];
		// importScopedSession() sets these variables
		$this->setMwGlobals( [
			'wgRequest' => new FauxRequest,
		] );
		$sc = RequestContext::importScopedSession( $sinfo ); // load new context

		$context->getRequest()->getSession()->persist();
		$info = $context->exportSession();
		$this->assertEquals( $sinfo['ip'], $info['ip'], "Correct IP address." );
		$this->assertEquals( $sinfo['headers'], $info['headers'], "Correct headers." );
		$this->assertEquals( $sinfo['sessionId'], $info['sessionId'], "Correct session ID." );
		$this->assertEquals( $sinfo['userId'], $info['userId'], "Correct user ID." );
		$this->assertEquals(
			$sinfo['ip'],
			$context->getRequest()->getIP(),
			"Correct context IP address."
		);
		$this->assertEquals(
			$sinfo['headers'],
			$context->getRequest()->getAllHeaders(),
			"Correct context headers."
		);
		$this->assertEquals(
			$sinfo['sessionId'],
			$context->getRequest()->getSession()->getId(),
			"Correct context session ID."
		);
		if ( \MediaWiki\Session\PHPSessionHandler::isEnabled() ) {
			$this->assertEquals( $sinfo['sessionId'], session_id(), "Correct context session ID." );
		} else {
			$this->assertEquals( $oldSessionId, session_id(), "Unchanged PHP session ID." );
		}
		$this->assertTrue( $context->getUser()->isRegistered(), "Correct context user." );
		$this->assertEquals( $sinfo['userId'], $context->getUser()->getId(), "Correct context user ID." );
		$this->assertEquals(
			'UnitTestContextUser',
			$context->getUser()->getName(),
			"Correct context user name."
		);

		unset( $sc ); // restore previous context

		$info = $context->exportSession();
		$this->assertEquals( $oInfo['ip'], $info['ip'], "Correct restored IP address." );
		$this->assertEquals( $oInfo['headers'], $info['headers'], "Correct restored headers." );
		$this->assertEquals( $oInfo['sessionId'], $info['sessionId'], "Correct restored session ID." );
		$this->assertEquals( $oInfo['userId'], $info['userId'], "Correct restored user ID." );
		$this->assertFalse( $context->getRequest()->getSession()->isPersistent(),
			'Global session isn\'t persistent after restoring the context' );
	}

	/**
	 * @covers \MediaWiki\Context\RequestContext::getUser
	 * @covers \MediaWiki\Context\RequestContext::setUser
	 * @covers \MediaWiki\Context\RequestContext::getAuthority
	 * @covers \MediaWiki\Context\RequestContext::setAuthority
	 */
	public function testTestGetSetAuthority() {
		$context = new RequestContext();

		$user = $this->getTestUser()->getUser();

		$context->setUser( $user );
		$this->assertTrue( $user->equals( $context->getAuthority()->getUser() ) );
		$this->assertTrue( $user->equals( $context->getUser() ) );

		$authorityActor = new UserIdentityValue( 42, 'Test' );
		$authority = new UltimateAuthority( $authorityActor );

		$context->setAuthority( $authority );
		$this->assertTrue( $context->getUser()->equals( $authorityActor ) );
		$this->assertTrue( $context->getAuthority()->getUser()->equals( $authorityActor ) );
	}

	/**
	 * @covers \MediaWiki\Context\RequestContext
	 */
	public function testGetActionName() {
		$factory = $this->createMock( ActionFactory::class );
		$factory
			// Assert calling only once.
			// Determining the action name is an expensive operation that
			// must be cached by the context, as it involved database and hooks.
			->expects( $this->once() )
			->method( 'getActionName' )
			->willReturn( 'foo' );
		$this->setService( 'ActionFactory', $factory );

		$context = new RequestContext();
		$this->assertSame( 'foo', $context->getActionName(), 'value from factory' );
		$this->assertSame( 'foo', $context->getActionName(), 'cached' );
	}

	/**
	 * @covers \MediaWiki\Context\RequestContext
	 */
	public function testSetActionName() {
		$factory = $this->createMock( ActionFactory::class );
		$factory
			->expects( $this->never() )
			->method( 'getActionName' );
		$this->setService( 'ActionFactory', $factory );

		$context = new RequestContext();
		$context->setActionName( 'fixed' );
		$this->assertSame( 'fixed', $context->getActionName() );
	}

	/**
	 * @covers \MediaWiki\Context\RequestContext
	 */
	public function testOverideActionName() {
		$factory = $this->createMock( ActionFactory::class );
		$factory
			->method( 'getActionName' )
			->willReturnOnConsecutiveCalls( 'aaa', 'bbb' );
		$this->setService( 'ActionFactory', $factory );

		$context = new RequestContext();
		$this->assertSame( 'aaa', $context->getActionName(), 'first from factory' );
		$this->assertSame( 'aaa', $context->getActionName(), 'cached first' );

		// Ignore warning from clearActionName
		@$context->setTitle( $this->createMock( Title::class ) );
		$this->assertSame( 'bbb', $context->getActionName(), 'second from factory' );
		$this->assertSame( 'bbb', $context->getActionName(), 'cached second' );
	}

	private function registerTestSkin() {
		$skin = $this->createMock( Skin::class );

		$skinFactory = $this->getServiceContainer()->getSkinFactory();
		$skinFactory->register( 'test', 'test',
			static function () use ( $skin ) {
				return $skin;
			}
		);
		return $skin;
	}

	public function testGetSkinFromDefault() {
		$this->overrideConfigValue( MainConfigNames::DefaultSkin, 'test' );
		$skin = $this->registerTestSkin();
		$context = new RequestContext();
		$this->assertSame( $skin, $context->getSkin() );
	}

	public function testGetSkinFromPref() {
		$optionsLookup = new StaticUserOptionsLookup( [], [ 'skin' => 'test' ] );
		$this->setService( 'UserOptionsLookup', $optionsLookup );

		$skin = $this->registerTestSkin();

		$context = new RequestContext();
		$this->assertSame( $skin, $context->getSkin() );
	}

	public function testGetSkinFromStringHook() {
		$skin = $this->registerTestSkin();
		$this->setTemporaryHook(
			'RequestContextCreateSkin',
			static function ( $context, &$skin ) {
				$skin = 'test';
			}
		);
		$context = new RequestContext();
		$this->assertSame( $skin, $context->getSkin() );
	}

	public function testGetSkinFromObjectHook() {
		$skin = $this->createMock( Skin::class );
		$this->setTemporaryHook(
			'RequestContextCreateSkin',
			static function ( $context, &$skinRes ) use ( $skin ) {
				$skinRes = $skin;
			}
		);
		$context = new RequestContext();
		$this->assertSame( $skin, $context->getSkin() );
	}

	public function testGetSkinFromBadPrefs() {
		// T342733
		$this->overrideConfigValue( MainConfigNames::DefaultSkin, 'test' );
		$optionsLookup = new StaticUserOptionsLookup( [], [ 'skin' => '' ] );
		$this->setService( 'UserOptionsLookup', $optionsLookup );
		$skin = $this->registerTestSkin();

		$context = new RequestContext();
		$this->assertSame( $skin, $context->getSkin() );
	}

	public function testGetSkinFromBadDefault() {
		$this->overrideConfigValues( [
			MainConfigNames::DefaultSkin => 'nonexistent',
			MainConfigNames::HiddenPrefs => [ 'skin' ]
		] );
		$context = new RequestContext();
		$this->assertInstanceOf( SkinFallback::class, $context->getSkin() );
	}

	public function testGetLanguageWhenRecursionDetected() {
		$this->expectException( LogicException::class );

		$this->setTemporaryHook(
			'UserGetLanguageObject',
			static function ( $actualUser, $actualCode, IContextSource $actualContext ) {
				$actualContext->getLanguage();
			}
		);
		$context = new RequestContext();
		$context->getLanguage();
	}

	public function testGetLanguageUsesUserGetLanguageObjectHook() {
		// Set a handler for the UserGetLanguageObject hook that stores the
		// values passed to it
		$hookUser = null;
		$hookCode = null;
		$hookContext = null;
		$this->setTemporaryHook(
			'UserGetLanguageObject',
			static function ( User $user, string $code, IContextSource $context ) use (
				&$hookUser, &$hookCode, &$hookContext
			) {
				$hookUser = $user;
				$hookCode = $code;
				$hookContext = $context;
			}
		);

		// Set up a RequestContext with known values
		$context = new RequestContext();

		$request = new FauxRequest();
		$request->setVal( 'uselang', 'qqx' );
		$context->setRequest( $request );

		$mockUser = $this->createMock( User::class );
		$context->setUser( $mockUser );

		// Hook should not have been called until ::getLanguage is called
		$this->assertNull( $hookContext );

		$context->getLanguage();

		// Hook should have been called after a call to ::getLanguage
		$this->assertSame( 'qqx', $hookCode );
		$this->assertSame( $mockUser, $hookUser );
		$this->assertSame( $context, $hookContext );
	}

	public function testGetLanguageWhenUsingLanguagePreference() {
		// Get a user who's language preference is for Italian
		$user = $this->getTestUser()->getUser();
		$userOptionsLookup = new StaticUserOptionsLookup( [ $user->getName() => [ 'language' => 'it' ] ] );
		$this->setService( 'UserOptionsLookup', $userOptionsLookup );

		$context = new RequestContext();
		$context->setUser( $user );

		$actualLanguage = $context->getLanguage();

		$this->assertSame( 'it', $actualLanguage->getCode() );
	}

	public function testGetLanguageWhenUserNotSafeToLoad() {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'es' );

		// Get a user which has User::isSafeToLoad return false
		$user = $this->createMock( User::class );
		$user->method( 'isSafeToLoad' )
			->willReturn( false );

		// Expect no calls to use the UserOptionsLookup if the user isn't safe to load
		$this->setService( 'UserOptionsLookup', $this->createNoOpMock( UserOptionsLookup::class ) );

		$context = new RequestContext();
		$context->setUser( $user );

		$this->assertSame( 'es', $context->getLanguage()->getCode() );
	}

	public function testGetLanguageNeverOverridesSetLanguageValue() {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'es' );

		// Get a user which has User::isSafeToLoad return false at first
		// but later in the test return true
		$isUserSafeToLoad = false;
		$user = $this->createMock( User::class );
		$user->method( 'isSafeToLoad' )
			->willReturnCallback( static function () use ( &$isUserSafeToLoad ) {
				return $isUserSafeToLoad;
			} );

		// Set the language preference for the test user to something we will expect to not see,
		// as the UserOptionsLookup service should not be used in the test (but may be used
		// by other code so we cannot make it a no-op mock)
		$userOptionsLookup = new StaticUserOptionsLookup( [ $user->getName() => [ 'language' => 'nb' ] ] );
		$this->setService( 'UserOptionsLookup', $userOptionsLookup );

		$mockSkin = $this->createMock( Skin::class );
		$mockSkin->method( 'getSkinName' )
			->willReturn( 'test-skin' );
		$this->setTemporaryHook(
			'RequestContextCreateSkin',
			static function ( $context, &$skin ) use ( $mockSkin ) {
				$skin = $mockSkin;
			}
		);

		$context = new RequestContext();
		$context->setUser( $user );

		$this->assertSame(
			'es',
			$context->getLanguage()->getCode(),
			'First call to ::getLanguage should default to the content language'
		);

		$context->setLanguage( 'it' );
		$this->assertSame(
			'it',
			$context->getLanguage()->getCode(),
			'Language set via ::setLanguage should be returned by ::getLanguage'
		);

		// Make User::isSafeToLoad return true now, so that we can test that any
		// language set by ::setLanguage stops the code from generating the
		// language once User::isSafeToLoad returns true
		$isUserSafeToLoad = true;

		$this->assertSame(
			'it',
			$context->getLanguage()->getCode(),
			'Change in User::isSafeToLoad should not have changed the language'
		);
	}

	public function testGetLanguageRecachesLanguageWhenUserIsSafeToLoad() {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'es' );

		// Get a user which has User::isSafeToLoad return false at first
		// but later in the test return true
		$isUserSafeToLoad = false;
		$user = $this->createMock( User::class );
		$user->method( 'isSafeToLoad' )
			->willReturnCallback( static function () use ( &$isUserSafeToLoad ) {
				return $isUserSafeToLoad;
			} );

		// Expect no calls to use the UserOptionsLookup if the user isn't safe to load
		$getOptionCalled = false;
		$mockUserOptionsLookup = $this->createMock( UserOptionsLookup::class );
		$mockUserOptionsLookup->method( 'getOption' )
			->with( $user, 'language' )
			->willReturnCallback( static function () use ( &$getOptionCalled ) {
				$getOptionCalled = true;
				return 'it';
			} );
		$this->setService( 'UserOptionsLookup', $mockUserOptionsLookup );

		$context = new RequestContext();
		$context->setUser( $user );
		$this->assertSame(
			'es',
			$context->getLanguage()->getCode(),
			'First call to ::getLanguage should default to the content language'
		);

		// Make User::isSafeToLoad return now return true
		$isUserSafeToLoad = true;

		$this->assertSame(
			'it',
			$context->getLanguage()->getCode(),
			'Change in User::isSafeToLoad to true should cause language ' .
				'to be recached using the user options'
		);
		$this->assertTrue( $getOptionCalled );
	}

	public function testCloningNotAllowed() {
		$context = RequestContext::getMain();
		$this->expectException( LogicException::class );

		clone $context;
	}
}
