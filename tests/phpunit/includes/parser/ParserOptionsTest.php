<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers ParserOptions
 */
class ParserOptionsTest extends MediaWikiIntegrationTestCase {

	private static function clearCache() {
		$wrap = TestingAccessWrapper::newFromClass( ParserOptions::class );
		$wrap->defaults = null;
		$wrap->lazyOptions = [
			'dateformat' => [ ParserOptions::class, 'initDateFormat' ],
			'speculativeRevId' => [ ParserOptions::class, 'initSpeculativeRevId' ],
		];
		$wrap->inCacheKey = [
			'dateformat' => true,
			'numberheadings' => true,
			'thumbsize' => true,
			'stubthreshold' => true,
			'printable' => true,
			'userlang' => true,
		];
	}

	protected function setUp() : void {
		if ( PHP_VERSION_ID >= 70400 && PHP_VERSION_ID <= 70408 ) {
			$this->markTestSkipped( 'Tests broken on PHP 7.4.0 - 7.4.8. See T270228' );
		}

		parent::setUp();
		self::clearCache();

		$this->setMwGlobals( [
			'wgRenderHashAppend' => '',
		] );

		// This is crazy, but registering false, null, or other falsey values
		// as a hook callback "works".
		$this->setTemporaryHook( 'PageRenderingHash', null );
	}

	protected function tearDown() : void {
		self::clearCache();
		parent::tearDown();
	}

	public function testNewCanonical() {
		$this->hideDeprecated( 'ParserOptions::newCanonical with no user' );

		$user = $this->getMutableTestUser()->getUser();
		$userLang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'fr' );
		$contLang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'qqx' );

		$this->setContentLang( $contLang );
		$this->setMwGlobals( [
			'wgUser' => $user,
			'wgLang' => $userLang,
		] );

		$lang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'de' );
		$lang2 = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'bug' );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $user );
		$context->setLanguage( $lang );

		// No parameters picks up $wgUser and $wgLang
		$popt = ParserOptions::newCanonical();
		$this->assertSame( $user, $popt->getUser() );
		$this->assertSame( $userLang, $popt->getUserLangObj() );

		// Just a user uses $wgLang
		$popt = ParserOptions::newCanonical( $user );
		$this->assertSame( $user, $popt->getUser() );
		$this->assertSame( $userLang, $popt->getUserLangObj() );

		// Just a language uses $wgUser
		$popt = ParserOptions::newCanonical( null, $lang );
		$this->assertSame( $user, $popt->getUser() );
		$this->assertSame( $lang, $popt->getUserLangObj() );

		// Passing both works
		$popt = ParserOptions::newCanonical( $user, $lang );
		$this->assertSame( $user, $popt->getUser() );
		$this->assertSame( $lang, $popt->getUserLangObj() );

		// Passing 'canonical' uses an anon and $contLang, and ignores any passed $userLang
		$popt = ParserOptions::newCanonical( 'canonical' );
		$this->assertTrue( $popt->getUser()->isAnon() );
		$this->assertSame( $contLang, $popt->getUserLangObj() );
		$popt = ParserOptions::newCanonical( 'canonical', $lang2 );
		$this->assertSame( $contLang, $popt->getUserLangObj() );

		// Passing an IContextSource uses the user and lang from it, and ignores
		// any passed $userLang
		$popt = ParserOptions::newCanonical( $context );
		$this->assertSame( $user, $popt->getUser() );
		$this->assertSame( $lang, $popt->getUserLangObj() );
		$popt = ParserOptions::newCanonical( $context, $lang2 );
		$this->assertSame( $lang, $popt->getUserLangObj() );

		// Passing something else raises an exception
		try {
			$popt = ParserOptions::newCanonical( 'bogus' );
			$this->fail( 'Excpected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
		}
	}

	/**
	 * @dataProvider provideIsSafeToCache
	 * @param bool $expect Expected value
	 * @param array $options Options to set
	 */
	public function testIsSafeToCache( $expect, $options ) {
		$popt = ParserOptions::newCanonical( 'canonical' );
		foreach ( $options as $name => $value ) {
			$popt->setOption( $name, $value );
		}
		$this->assertSame( $expect, $popt->isSafeToCache() );
	}

	public static function provideIsSafeToCache() {
		global $wgEnableParserLimitReporting;
		return [
			'No overrides' => [ true, [] ],
			'In-key options are ok' => [ true, [
				'thumbsize' => 1e100,
				'printable' => false,
			] ],
			'Non-in-key options are not ok' => [ false, [
				'removeComments' => false,
			] ],
			'Non-in-key options are not ok (2)' => [ false, [
				'wrapclass' => 'foobar',
			] ],
			'Canonical override, not default (1)' => [ true, [
				'enableLimitReport' => $wgEnableParserLimitReporting,
			] ],
			'Canonical override, not default (2)' => [ false, [
				'enableLimitReport' => !$wgEnableParserLimitReporting,
			] ],
		];
	}

	/**
	 * @dataProvider provideOptionsHash
	 * @param array $usedOptions Used options
	 * @param string $expect Expected value
	 * @param array $options Options to set
	 * @param array $globals Globals to set
	 * @param callable|null $hookFunc PageRenderingHash hook function
	 */
	public function testOptionsHash(
		$usedOptions, $expect, $options, $globals = [], $hookFunc = null
	) {
		$this->setMwGlobals( $globals );
		$this->setTemporaryHook( 'PageRenderingHash', $hookFunc );

		$popt = ParserOptions::newCanonical( 'canonical' );
		foreach ( $options as $name => $value ) {
			$popt->setOption( $name, $value );
		}
		$this->assertSame( $expect, $popt->optionsHash( $usedOptions ) );
	}

	public static function provideOptionsHash() {
		$used = [ 'thumbsize', 'printable' ];

		$classWrapper = TestingAccessWrapper::newFromClass( ParserOptions::class );
		$classWrapper->getDefaults();
		$allUsableOptions = array_diff(
			array_keys( $classWrapper->inCacheKey ),
			array_keys( $classWrapper->lazyOptions )
		);

		return [
			'Canonical options, nothing used' => [ [], 'canonical', [] ],
			'Canonical options, used some options' => [ $used, 'canonical', [] ],
			'Used some options, non-default values' => [
				$used,
				'printable=1!thumbsize=200',
				[
					'thumbsize' => 200,
					'printable' => true,
				]
			],
			'Canonical options, used all non-lazy options' => [ $allUsableOptions, 'canonical', [] ],
			'Canonical options, nothing used, but with hooks and $wgRenderHashAppend' => [
				[],
				'canonical!wgRenderHashAppend!onPageRenderingHash',
				[],
				[ 'wgRenderHashAppend' => '!wgRenderHashAppend' ],
				[ __CLASS__ . '::onPageRenderingHash' ],
			],
		];
	}

	public function testUsedLazyOptionsInHash() {
		$this->setTemporaryHook( 'ParserOptionsRegister',
			function ( &$defaults, &$inCacheKey, &$lazyOptions ) {
				$lazyFuncs = $this->getMockBuilder( stdClass::class )
					->setMethods( [ 'neverCalled', 'calledOnce' ] )
					->getMock();
				$lazyFuncs->expects( $this->never() )->method( 'neverCalled' );
				$lazyFuncs->expects( $this->once() )->method( 'calledOnce' )->willReturn( 'value' );

				$defaults += [
					'opt1' => null,
					'opt2' => null,
					'opt3' => null,
				];
				$inCacheKey += [
					'opt1' => true,
					'opt2' => true,
				];
				$lazyOptions += [
					'opt1' => [ $lazyFuncs, 'calledOnce' ],
					'opt2' => [ $lazyFuncs, 'neverCalled' ],
					'opt3' => [ $lazyFuncs, 'neverCalled' ],
				];
			}
		);

		self::clearCache();

		$popt = ParserOptions::newCanonical( 'canonical' );
		$popt->registerWatcher( function () {
			$this->fail( 'Watcher should not have been called' );
		} );
		$this->assertSame( 'opt1=value', $popt->optionsHash( [ 'opt1', 'opt3' ] ) );

		// Second call to see that opt1 isn't resolved a second time
		$this->assertSame( 'opt1=value', $popt->optionsHash( [ 'opt1', 'opt3' ] ) );
	}

	public static function onPageRenderingHash( &$confstr ) {
		$confstr .= '!onPageRenderingHash';
	}

	public function testGetInvalidOption() {
		$popt = ParserOptions::newCanonical( 'canonical' );
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Unknown parser option bogus" );
		$popt->getOption( 'bogus' );
	}

	public function testSetInvalidOption() {
		$popt = ParserOptions::newCanonical( 'canonical' );
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Unknown parser option bogus" );
		$popt->setOption( 'bogus', true );
	}

	public function testMatches() {
		$classWrapper = TestingAccessWrapper::newFromClass( ParserOptions::class );
		$oldDefaults = $classWrapper->defaults;
		$oldLazy = $classWrapper->lazyOptions;
		$reset = new ScopedCallback( function () use ( $classWrapper, $oldDefaults, $oldLazy ) {
			$classWrapper->defaults = $oldDefaults;
			$classWrapper->lazyOptions = $oldLazy;
		} );

		$popt1 = ParserOptions::newCanonical( 'canonical' );
		$popt2 = ParserOptions::newCanonical( 'canonical' );
		$this->assertTrue( $popt1->matches( $popt2 ) );

		$popt1->enableLimitReport( true );
		$popt2->enableLimitReport( false );
		$this->assertTrue( $popt1->matches( $popt2 ) );

		$popt2->setInterfaceMessage( !$popt2->getInterfaceMessage() );
		$this->assertFalse( $popt1->matches( $popt2 ) );

		$ctr = 0;
		$classWrapper->defaults += [ __METHOD__ => null ];
		$classWrapper->lazyOptions += [ __METHOD__ => function () use ( &$ctr ) {
			return ++$ctr;
		} ];
		$popt1 = ParserOptions::newCanonical( 'canonical' );
		$popt2 = ParserOptions::newCanonical( 'canonical' );
		$this->assertFalse( $popt1->matches( $popt2 ) );

		ScopedCallback::consume( $reset );
	}

	public function testMatchesForCacheKey() {
		$this->hideDeprecated( 'ParserOptions::newCanonical with no user' );
		$cOpts = ParserOptions::newCanonical( null, 'en' );

		$uOpts = ParserOptions::newFromAnon();
		$this->assertTrue( $cOpts->matchesForCacheKey( $uOpts ) );

		$user = new User();
		$uOpts = ParserOptions::newFromUser( $user );
		$this->assertTrue( $cOpts->matchesForCacheKey( $uOpts ) );

		$user = new User();
		$user->setOption( 'thumbsize', 251 );
		$uOpts = ParserOptions::newFromUser( $user );
		$this->assertFalse( $cOpts->matchesForCacheKey( $uOpts ) );

		$user = new User();
		$user->setOption( 'stubthreshold', 800 );
		$uOpts = ParserOptions::newFromUser( $user );
		$this->assertFalse( $cOpts->matchesForCacheKey( $uOpts ) );

		$user = new User();
		$uOpts = ParserOptions::newFromUserAndLang( $user,
			MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'zh' ) );
		$this->assertFalse( $cOpts->matchesForCacheKey( $uOpts ) );
	}

	public function testAllCacheVaryingOptions() {
		$this->setTemporaryHook( 'ParserOptionsRegister', null );
		$this->assertSame( [
			'dateformat', 'numberheadings', 'printable', 'stubthreshold',
			'thumbsize', 'userlang'
		], ParserOptions::allCacheVaryingOptions() );

		self::clearCache();

		$this->setTemporaryHook( 'ParserOptionsRegister', function ( &$defaults, &$inCacheKey ) {
			$defaults += [
				'foo' => 'foo',
				'bar' => 'bar',
				'baz' => 'baz',
			];
			$inCacheKey += [
				'foo' => true,
				'bar' => false,
			];
		} );
		$this->assertSame( [
			'dateformat', 'foo', 'numberheadings', 'printable', 'stubthreshold',
			'thumbsize', 'userlang'
		], ParserOptions::allCacheVaryingOptions() );
	}

	public function testGetSpeculativeRevid() {
		$options = new ParserOptions();

		$this->assertFalse( $options->getSpeculativeRevId() );

		$counter = 0;
		$options->setSpeculativeRevIdCallback( function () use( &$counter ) {
			return ++$counter;
		} );

		// make sure the same value is re-used once it is determined
		$this->assertSame( 1, $options->getSpeculativeRevId() );
		$this->assertSame( 1, $options->getSpeculativeRevId() );
	}

}
