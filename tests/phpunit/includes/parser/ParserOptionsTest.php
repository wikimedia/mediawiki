<?php

namespace MediaWiki\Tests\Parser;

use DummyContentForTesting;
use InvalidArgumentException;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiLangTestCase;
use stdClass;
use Wikimedia\ScopedCallback;

/**
 * @covers \MediaWiki\Parser\ParserOptions
 * @group Database
 */
class ParserOptionsTest extends MediaWikiLangTestCase {

	use TempUserTestTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::RenderHashAppend => '',
			MainConfigNames::UsePigLatinVariant => false,
		] );
		$this->setTemporaryHook( 'PageRenderingHash', HookContainer::NOOP );
	}

	protected function tearDown(): void {
		ParserOptions::clearStaticCache();
		parent::tearDown();
	}

	public function testNewCanonical() {
		$user = $this->createMock( User::class );
		$userLang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'fr' );
		$contLang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'qqx' );

		$this->setContentLang( $contLang );
		$this->setUserLang( $userLang );

		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'de' );
		$lang2 = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'bug' );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $user );
		$context->setLanguage( $lang );

		// Just a user uses $wgLang
		$popt = ParserOptions::newCanonical( $user );
		$this->assertSame( $user, $popt->getUserIdentity() );
		$this->assertSame( $userLang, $popt->getUserLangObj() );

		// Passing both works
		$popt = ParserOptions::newCanonical( $user, $lang );
		$this->assertSame( $user, $popt->getUserIdentity() );
		$this->assertSame( $lang, $popt->getUserLangObj() );

		// Passing 'canonical' uses an anon and $contLang, and ignores any passed $userLang
		$popt = ParserOptions::newFromAnon();
		$this->assertTrue( $popt->getUserIdentity()->isAnon() );
		$this->assertSame( $contLang, $popt->getUserLangObj() );
		$popt = ParserOptions::newCanonical( 'canonical', $lang2 );
		$this->assertSame( $contLang, $popt->getUserLangObj() );

		// Passing an IContextSource uses the user and lang from it, and ignores
		// any passed $userLang
		$popt = ParserOptions::newCanonical( $context );
		$this->assertSame( $user, $popt->getUserIdentity() );
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

	private function commonTestNewFromContext( IContextSource $context, UserIdentity $expectedUser ) {
		$popt = ParserOptions::newFromContext( $context );
		$this->assertTrue( $expectedUser->equals( $popt->getUserIdentity() ) );
		$this->assertSame( $context->getLanguage(), $popt->getUserLangObj() );
	}

	/** @dataProvider provideNewFromContext */
	public function testNewFromContext( $contextUserIdentity, $contextLanguage ) {
		$this->enableAutoCreateTempUser();
		// Get a context which has our provided user and language set, then call ::newFromContext with it.
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser(
			$this->getServiceContainer()->getUserFactory()->newFromUserIdentity( $contextUserIdentity )
		);
		$context->setLanguage( $contextLanguage );
		$this->commonTestNewFromContext( $context, $context->getUser() );
	}

	public static function provideNewFromContext() {
		return [
			'Username does not exist and user lang as en' => [ UserIdentityValue::newAnonymous( 'Testabc' ), 'en' ],
			'Username is IP address, no stashed temporary username, and user lang as qqx' => [
				UserIdentityValue::newAnonymous( '1.2.3.4' ), 'qqx',
			],
		];
	}

	public function testNewFromContextForNamedAccount() {
		$this->testNewFromContext( $this->getTestUser()->getUser(), 'qqx' );
	}

	public function testNewFromContextForTemporaryAccount() {
		$this->testNewFromContext(
			$this->getServiceContainer()->getTempUserCreator()
				->create( null, new FauxRequest() )->getUser(),
			'de'
		);
	}

	public function testNewFromContextForAnonWhenTempNameStashed() {
		$this->enableAutoCreateTempUser();
		// Get a context which uses an anon user as the user.
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser(
			$this->getServiceContainer()->getUserFactory()
				->newFromUserIdentity( UserIdentityValue::newAnonymous( '1.2.3.4' ) )
		);
		// Create a temporary account name and stash it in associated Session for the $context
		$stashedName = $this->getServiceContainer()->getTempUserCreator()
			->acquireAndStashName( $context->getRequest()->getSession() );
		// Call ::newFromContext and expect that that stashed name is used
		$this->commonTestNewFromContext( $context, UserIdentityValue::newAnonymous( $stashedName ) );
	}

	public function testNewFromContextForAnonWhenTempNameStashedButFeatureSinceDisabled() {
		$this->enableAutoCreateTempUser();
		// Get a context which uses an anon user as the user.
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser(
			$this->getServiceContainer()->getUserFactory()
				->newFromUserIdentity( UserIdentityValue::newAnonymous( '1.2.3.4' ) )
		);
		// Create a temporary account name and stash it in associated Session for the $context
		$this->getServiceContainer()->getTempUserCreator()
			->acquireAndStashName( $context->getRequest()->getSession() );
		// Simulate that in the interim the temporary accounts system has been disabled, and check that an IP
		// address is used in this case
		$this->disableAutoCreateTempUser( [ 'known' => true ] );
		// Call ::newFromContext and expect that that stashed name is used
		$this->commonTestNewFromContext( $context, UserIdentityValue::newAnonymous( '1.2.3.4' ) );
	}

	/**
	 * @dataProvider provideIsSafeToCache
	 * @param bool $expect Expected value
	 * @param array $options Options to set
	 * @param array|null $usedOptions
	 */
	public function testIsSafeToCache( bool $expect, array $options, ?array $usedOptions = null ) {
		$popt = ParserOptions::newFromAnon();
		foreach ( $options as $name => $value ) {
			$popt->setOption( $name, $value );
		}
		$this->assertSame( $expect, $popt->isSafeToCache( $usedOptions ) );
	}

	public static function provideIsSafeToCache() {
		$seven = static function () {
			return 7;
		};

		return [
			'No overrides' => [ true, [] ],
			'No overrides, some used' => [ true, [], [ 'thumbsize', 'removeComments' ] ],
			'In-key options are ok' => [ true, [
				'thumbsize' => 1e100,
				'printable' => false,
			] ],
			'In-key options are ok, some used' => [ true, [
				'thumbsize' => 1e100,
				'printable' => false,
			], [ 'thumbsize', 'removeComments' ] ],
			'Non-in-key options are not ok' => [ false, [
				'removeComments' => false,
			] ],
			'Non-in-key options are not ok, used' => [ false, [
				'removeComments' => false,
			], [ 'removeComments' ] ],
			'Non-in-key options are ok if other used' => [ true, [
				'removeComments' => false,
			], [ 'thumbsize' ] ],
			'Non-in-key options are ok if nothing used' => [ true, [
				'removeComments' => false,
			], [] ],
			'Unknown used options do not crash' => [ true, [
			], [ 'unknown' ] ],
			'Non-in-key options are not ok (2)' => [ false, [
				'wrapclass' => 'foobar',
			] ],
			'Callback not default' => [ true, [
				'speculativeRevIdCallback' => $seven,
			] ],
		];
	}

	/**
	 * @dataProvider provideOptionsHash
	 * @param array $usedOptions
	 * @param string $expect Expected value
	 * @param array $options Options to set
	 * @param array $globals Globals to set
	 * @param callable|null $hookFunc PageRenderingHash hook function
	 */
	public function testOptionsHash(
		$usedOptions, $expect, $options, $globals = [], $hookFunc = null
	) {
		$this->overrideConfigValues( $globals );
		$this->setTemporaryHook( 'PageRenderingHash', $hookFunc ?: HookContainer::NOOP );

		$popt = ParserOptions::newFromAnon();
		foreach ( $options as $name => $value ) {
			$popt->setOption( $name, $value );
		}
		$this->assertSame( $expect, $popt->optionsHash( $usedOptions ) );
	}

	public static function provideOptionsHash() {
		$used = [ 'thumbsize', 'printable' ];

		$allUsableOptions = array_diff(
			ParserOptions::allCacheVaryingOptions(),
			array_keys( ParserOptions::getLazyOptions() )
		);

		return [
			'Canonical options, nothing used' => [ [], 'canonical', [] ],
			'Canonical options, used some options' => [ $used, 'canonical', [] ],
			'Canonical options, used some more options' => [ array_merge( $used, [ 'wrapclass' ] ), 'canonical', [] ],
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
				[ MainConfigNames::RenderHashAppend => '!wgRenderHashAppend' ],
				[ self::class, 'onPageRenderingHash' ],
			],
		];
	}

	public function testUsedLazyOptionsInHash() {
		$this->setTemporaryHook( 'ParserOptionsRegister',
			function ( &$defaults, &$inCacheKey, &$lazyOptions ) {
				$lazyFuncs = $this->getMockBuilder( stdClass::class )
					->addMethods( [ 'neverCalled', 'calledOnce' ] )
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

		ParserOptions::clearStaticCache();

		$popt = ParserOptions::newFromAnon();
		$popt->registerWatcher( function () {
			$this->fail( 'Watcher should not have been called' );
		} );
		$this->assertSame( 'opt1=value', $popt->optionsHash( [ 'opt1', 'opt3' ] ) );

		// Second call to see that opt1 isn't resolved a second time
		$this->assertSame( 'opt1=value', $popt->optionsHash( [ 'opt1', 'opt3' ] ) );
	}

	public function testLazyOptionWithDefault() {
		$loaded = false;
		$this->setTemporaryHook(
			'ParserOptionsRegister',
			static function ( &$defaults, &$inCacheKey, &$lazyLoad ) use ( &$loaded ) {
				$defaults['test_option'] = 'default!';
				$inCacheKey['test_option'] = true;
				$lazyLoad['test_option'] = static function () use ( &$loaded ) {
					$loaded = true;
					return 'default!';
				};
			}
		);

		$po = ParserOptions::newFromAnon();
		$this->assertSame( 'default!', $po->getOption( 'test_option' ) );
		$this->assertTrue( $loaded );
		$this->assertSame(
			'canonical',
			$po->optionsHash( [ 'test_option' ], Title::makeTitle( NS_MAIN, 'Test' ) )
		);
	}

	public static function onPageRenderingHash( &$confstr ) {
		$confstr .= '!onPageRenderingHash';
	}

	public function testGetInvalidOption() {
		$popt = ParserOptions::newFromAnon();
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Unknown parser option bogus" );
		$popt->getOption( 'bogus' );
	}

	public function testSetInvalidOption() {
		$popt = ParserOptions::newFromAnon();
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Unknown parser option bogus" );
		$popt->setOption( 'bogus', true );
	}

	public function testMatches() {
		$popt1 = ParserOptions::newFromAnon();
		$popt2 = ParserOptions::newFromAnon();
		$this->assertTrue( $popt1->matches( $popt2 ) );

		$popt2->setInterfaceMessage( !$popt2->getInterfaceMessage() );
		$this->assertFalse( $popt1->matches( $popt2 ) );

		$ctr = 0;
		$this->setTemporaryHook( 'ParserOptionsRegister',
			static function ( &$defaults, &$inCacheKey, &$lazyOptions ) use ( &$ctr ) {
				$defaults['testMatches'] = null;
				$lazyOptions['testMatches'] = static function () use ( &$ctr ) {
					return ++$ctr;
				};
			}
		);
		ParserOptions::clearStaticCache();

		$popt1 = ParserOptions::newFromAnon();
		$popt2 = ParserOptions::newFromAnon();
		$this->assertFalse( $popt1->matches( $popt2 ) );

		ScopedCallback::consume( $reset );
	}

	/**
	 * This test fails if tearDown() does not call ParserOptions::clearStaticCache(),
	 * because the lazy option from the hook in the previous test remains active.
	 */
	public function testTeardownClearedCache() {
		$popt1 = ParserOptions::newFromAnon();
		$popt2 = ParserOptions::newFromAnon();
		$this->assertTrue( $popt1->matches( $popt2 ) );
	}

	public function testMatchesForCacheKey() {
		$user = new UserIdentityValue( 0, '127.0.0.1' );
		$cOpts = ParserOptions::newCanonical(
			$user,
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' )
		);

		$uOpts = ParserOptions::newFromAnon();
		$this->assertTrue( $cOpts->matchesForCacheKey( $uOpts ) );

		$uOpts = ParserOptions::newFromUser( $user );
		$this->assertTrue( $cOpts->matchesForCacheKey( $uOpts ) );

		$this->getServiceContainer()
			->getUserOptionsManager()
			->setOption( $user, 'thumbsize', 251 );
		$uOpts = ParserOptions::newFromUser( $user );
		$this->assertFalse( $cOpts->matchesForCacheKey( $uOpts ) );

		$this->getServiceContainer()
			->getUserOptionsManager()
			->setOption( $user, 'stubthreshold', 800 );
		$uOpts = ParserOptions::newFromUser( $user );
		$this->assertFalse( $cOpts->matchesForCacheKey( $uOpts ) );

		$uOpts = ParserOptions::newFromUserAndLang(
			$user,
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( 'zh' )
		);
		$this->assertFalse( $cOpts->matchesForCacheKey( $uOpts ) );
	}

	public function testAllCacheVaryingOptions() {
		$this->setTemporaryHook( 'ParserOptionsRegister', HookContainer::NOOP );
		$this->assertSame( [
			'collapsibleSections',
			'dateformat', 'printable', 'suppressSectionEditLinks',
			'thumbsize', 'useParsoid', 'userlang',
		], ParserOptions::allCacheVaryingOptions() );

		ParserOptions::clearStaticCache();

		$this->setTemporaryHook( 'ParserOptionsRegister', static function ( &$defaults, &$inCacheKey ) {
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
			'collapsibleSections',
			'dateformat', 'foo', 'printable', 'suppressSectionEditLinks',
			'thumbsize', 'useParsoid', 'userlang',
		], ParserOptions::allCacheVaryingOptions() );
	}

	public function testGetSpeculativeRevid() {
		$options = ParserOptions::newFromAnon();

		$this->assertFalse( $options->getSpeculativeRevId() );

		$counter = 0;
		$options->setSpeculativeRevIdCallback( static function () use( &$counter ) {
			return ++$counter;
		} );

		// make sure the same value is re-used once it is determined
		$this->assertSame( 1, $options->getSpeculativeRevId() );
		$this->assertSame( 1, $options->getSpeculativeRevId() );
	}

	public function testSetupFakeRevision_new() {
		$options = ParserOptions::newFromAnon();
		$options->setIsPreview( true );
		$options->setSpeculativePageIdCallback( static fn () => 105 );

		$page = PageIdentityValue::localIdentity( 0, NS_MAIN, __METHOD__ );
		$content = new DummyContentForTesting( '12345' );
		$user = UserIdentityValue::newRegistered( 123, 'TestTest' );
		$fakeRevisionScope = $options->setupFakeRevision( $page, $content, $user );

		/** @var RevisionRecord $fakeRevision */
		$fakeRevision = $options->getCurrentRevisionRecordCallback()( $page );
		$this->assertNotNull( $fakeRevision );
		$this->assertSame( '12345', $fakeRevision->getContent( SlotRecord::MAIN )->serialize() );
		$this->assertSame( $user, $fakeRevision->getUser() );
		$this->assertSame( 0, $fakeRevision->getId() );
		$this->assertSame( 0, $fakeRevision->getParentId() );
		$this->assertSame( 105, $fakeRevision->getPageId() );
		$this->assertSame( 105, $fakeRevision->getPage()->getId() );
		$this->assertTrue( $fakeRevision->getPage()->exists() );

		ScopedCallback::consume( $fakeRevisionScope );
		$this->assertFalse(
			$options->getCurrentRevisionRecordCallback()(
				TitleValue::newFromPage( $page )
			)
		);
	}

	public function testSetupFakeRevision_existing() {
		$options = ParserOptions::newFromAnon();
		$options->setIsPreview( true );

		$page = PageIdentityValue::localIdentity( 105, NS_MAIN, __METHOD__ );
		$content = new DummyContentForTesting( '12345' );
		$user = UserIdentityValue::newRegistered( 123, 'TestTest' );
		$fakeRevisionScope = $options->setupFakeRevision( $page, $content, $user, 1002 );

		/** @var RevisionRecord $fakeRevision */
		$fakeRevision = $options->getCurrentRevisionRecordCallback()( $page );
		$this->assertNotNull( $fakeRevision );
		$this->assertSame( '12345', $fakeRevision->getContent( SlotRecord::MAIN )->serialize() );
		$this->assertSame( $user, $fakeRevision->getUser() );
		$this->assertSame( 0, $fakeRevision->getId() );
		$this->assertSame( 1002, $fakeRevision->getParentId() );
		$this->assertSame( 105, $fakeRevision->getPageId() );
		$this->assertSame( 105, $fakeRevision->getPage()->getId() );
		$this->assertTrue( $fakeRevision->getPage()->exists() );

		ScopedCallback::consume( $fakeRevisionScope );
		$this->assertFalse(
			$options->getCurrentRevisionRecordCallback()(
				TitleValue::newFromPage( $page )
			)
		);
	}

	public function testRenderReason() {
		$options = ParserOptions::newFromAnon();

		$this->assertIsString( $options->getRenderReason() );

		$options->setRenderReason( 'just a test' );
		$this->assertIsString( 'just a test', $options->getRenderReason() );
	}

	public function testSuppressSectionEditLinks() {
		$options = ParserOptions::newFromAnon();

		$this->assertFalse( $options->getSuppressSectionEditLinks() );

		$options->setSuppressSectionEditLinks();
		$this->assertTrue( $options->getSuppressSectionEditLinks() );
	}

	public function testCollapsibleSections() {
		$options = ParserOptions::newFromAnon();

		$this->assertFalse( $options->getCollapsibleSections() );

		$options->setCollapsibleSections();
		$this->assertTrue( $options->getCollapsibleSections() );
	}
}
