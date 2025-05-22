<?php

use MediaWiki\Actions\WatchAction;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Page\Article;
use MediaWiki\Page\WikiPage;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Request\WebRequest;
use MediaWiki\Status\Status;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\WatchedItem;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Message\MessageValue;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Actions\WatchAction
 *
 * @group Action
 * @group Database
 */
class WatchActionTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;
	use MockAuthorityTrait;

	private WatchAction $watchAction;
	private WikiPage $testWikiPage;
	private IContextSource $context;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'en',
		] );

		$this->setService( 'ReadOnlyMode', $this->getDummyReadOnlyMode( false ) );
		$testTitle = Title::makeTitle( NS_MAIN, 'UTTest' );
		$this->testWikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $testTitle );
		$testContext = new DerivativeContext( RequestContext::getMain() );
		$testContext->setTitle( $testTitle );
		$this->context = $testContext;
		$this->watchAction = $this->getWatchAction(
			Article::newFromWikiPage( $this->testWikiPage, $testContext ),
			$testContext
		);
	}

	private function getWatchAction( Article $article, IContextSource $context ) {
		$mwServices = $this->getServiceContainer();
		return new WatchAction(
			$article,
			$context,
			$mwServices->getWatchlistManager(),
			$mwServices->getWatchedItemStore(),
			$mwServices->getUserOptionsLookup()
		);
	}

	/**
	 * @covers \MediaWiki\Actions\WatchAction::getName()
	 */
	public function testGetName() {
		$this->assertEquals( 'watch', $this->watchAction->getName() );
	}

	/**
	 * @covers \MediaWiki\Actions\WatchAction::requiresUnblock()
	 */
	public function testRequiresUnlock() {
		$this->assertFalse( $this->watchAction->requiresUnblock() );
	}

	/**
	 * @covers \MediaWiki\Actions\WatchAction::doesWrites()
	 */
	public function testDoesWrites() {
		$this->assertTrue( $this->watchAction->doesWrites() );
	}

	/**
	 * @covers \MediaWiki\Actions\WatchAction::onSubmit()
	 */
	public function testOnSubmit() {
		/** @var Status $actual */
		$actual = $this->watchAction->onSubmit( [] );

		$this->assertStatusGood( $actual );
	}

	/**
	 * @covers \MediaWiki\Actions\WatchAction::onSubmit()
	 */
	public function testOnSubmitHookAborted() {
		// WatchlistExpiry feature flag.
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );

		$testContext = $this->getMockBuilder( DerivativeContext::class )
			->onlyMethods( [ 'getRequest' ] )
			->setConstructorArgs( [ $this->watchAction->getContext() ] )
			->getMock();

		// Change the context to have a registered user with correct permission.
		$user = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $user, [ 'editmywatchlist' ] );
		$testContext->setAuthority( $performer );
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromUserIdentity' )->willReturn( $this->createMock( User::class ) );
		$this->setService( 'UserFactory', $userFactory );

		/** @var MockObject|WebRequest $testRequest */
		$testRequest = $this->createMock( WebRequest::class );
		$testRequest->expects( $this->once() )
			->method( 'getVal' )
			->willReturn( '6 months' );
		$testContext->method( 'getRequest' )->willReturn( $testRequest );

		$this->setService( 'WatchedItemStore', $this->getDummyWatchedItemStore() );

		$this->watchAction = $this->getWatchAction(
			Article::newFromWikiPage( $this->testWikiPage, $testContext ),
			$testContext
		);

		$this->setTemporaryHook( 'WatchArticle', static function () {
			return false;
		} );

		/** @var Status $actual */
		$actual = $this->watchAction->onSubmit( [] );

		$this->assertInstanceOf( Status::class, $actual );
		$this->assertStatusError( 'hookaborted', $actual );
	}

	/**
	 * @covers \MediaWiki\Actions\WatchAction::checkCanExecute()
	 */
	public function testShowUserNotLoggedIn() {
		$notLoggedInUser = new User();
		$testContext = new DerivativeContext( $this->watchAction->getContext() );
		$testContext->setUser( $notLoggedInUser );
		$watchAction = $this->getWatchAction(
			Article::newFromWikiPage( $this->testWikiPage, $testContext ),
			$testContext
		);
		$this->expectException( UserNotLoggedIn::class );

		$watchAction->show();
	}

	/**
	 * @covers \MediaWiki\Actions\WatchAction::checkCanExecute()
	 */
	public function testShowUserLoggedInNoException() {
		$this->setService( 'PermissionManager', $this->createMock( PermissionManager::class ) );
		$registeredUser = $this->createMock( User::class );
		$registeredUser->method( 'isRegistered' )->willReturn( true );
		$registeredUser->method( 'isNamed' )->willReturn( true );
		$testContext = new DerivativeContext( $this->watchAction->getContext() );
		$testContext->setUser( $registeredUser );
		$watchAction = $this->getWatchAction(
			Article::newFromWikiPage( $this->testWikiPage, $testContext ),
			$testContext
		);

		$exception = null;
		try {
			$watchAction->show();
		} catch ( UserNotLoggedIn $e ) {
			$exception = $e;
		}
		$this->assertNull( $exception,
			'UserNotLoggedIn exception should not be thrown if user is a registered one.' );
	}

	/**
	 * @covers \MediaWiki\Actions\WatchAction::onSuccess()
	 * @covers \MediaWiki\Actions\WatchAction::makeSuccessMessage()
	 */
	public function testOnSuccessMainNamespaceTitle() {
		$testContext = $this->watchAction->getContext();

		/** @var WatchAction $watchAction */
		$watchAction = TestingAccessWrapper::newFromObject(
			$watchAction = $this->getWatchAction(
				Article::newFromWikiPage( $this->testWikiPage, $testContext ),
				$testContext
			)
		);

		$this->assertEquals( 'addedwatchtext', $watchAction->makeSuccessMessage( '' )->getKey() );
	}

	/**
	 * @covers \MediaWiki\Actions\WatchAction::onSuccess()
	 * @covers \MediaWiki\Actions\WatchAction::makeSuccessMessage()
	 */
	public function testOnSuccessTalkPage() {
		$testContext = new DerivativeContext( $this->watchAction->getContext() );
		$talkPageTitle = Title::makeTitle( NS_TALK, 'UTTest' );
		$testContext->setTitle( $talkPageTitle );

		/** @var WatchAction $watchAction */
		$watchAction = TestingAccessWrapper::newFromObject(
			$watchAction = $this->getWatchAction(
				Article::newFromTitle( $talkPageTitle, $testContext ),
				$testContext
			)
		);

		$this->assertEquals( 'addedwatchtext-talk', $watchAction->makeSuccessMessage( '' )->getKey() );
	}

	/**
	 * @dataProvider provideOnSuccessDifferentMessages
	 * @covers \MediaWiki\Actions\WatchAction::onSuccess()
	 * @covers \MediaWiki\Actions\WatchAction::makeSuccessMessage()
	 */
	public function testOnSuccessDifferentMessages(
		$watchlistExpiry, $expectedMessage, $prefixedTitle, $submittedExpiry
	) {
		// Fake current time to be 2020-09-17 12:00:00 UTC.
		ConvertibleTimestamp::setFakeTime( '20200917120000' );

		// WatchlistExpiry feature flag.
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, $watchlistExpiry );

		// Set up context
		/** @var MockObject|IContextSource $testContext */
		$testContext = $this->getMockBuilder( DerivativeContext::class )
			->onlyMethods( [ 'getLanguage' ] )
			->setConstructorArgs( [ $this->watchAction->getContext() ] )
			->getMock();
		// Set language to anything non-English/default, to catch assumptions.
		$langDe = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'de' );
		$testContext->method( 'getLanguage' )->willReturn( $langDe );

		/** @var WatchAction $watchAction */
		$watchAction = TestingAccessWrapper::newFromObject(
			$this->getWatchAction(
				Article::newFromTitle( Title::newFromText( $prefixedTitle ), $testContext ),
				$testContext
			)
		);

		$this->assertEquals( $expectedMessage, $watchAction->makeSuccessMessage( $submittedExpiry ) );
	}

	public static function provideOnSuccessDifferentMessages() {
		return [
			[
				'wgWatchlistExpiry' => false,
				'msg' => MessageValue::new( 'addedwatchtext' )
					->params( 'Foo' ),
				'prefixedTitle' => 'Foo',
				'submittedExpiry' => '',
			],
			[
				'wgWatchlistExpiry' => false,
				'msg' => MessageValue::new( 'addedwatchtext-talk' )
					->params( 'Talk:Foo' ),
				'prefixedTitle' => 'Talk:Foo',
				'submittedExpiry' => '',
			],
			[
				'wgWatchlistExpiry' => true,
				'msg' => MessageValue::new( 'addedwatchindefinitelytext' )
					->params( 'Foo' ),
				'prefixedTitle' => 'Foo',
				'submittedExpiry' => 'infinite',
			],
			[
				'wgWatchlistExpiry' => true,
				'msg' => MessageValue::new( 'addedwatchindefinitelytext-talk' )
					->params( 'Talk:Foo' ),
				'prefixedTitle' => 'Talk:Foo',
				'submittedExpiry' => 'infinite',
			],
			[
				'wgWatchlistExpiry' => true,
				'msg' => MessageValue::new( 'addedwatchexpirytext' )
					->params( 'Foo' )
					->params( '1 Woche' ),
				'prefixedTitle' => 'Foo',
				'submittedExpiry' => '1 week',
			],
			[
				'wgWatchlistExpiry' => true,
				'msg' => MessageValue::new( 'addedwatchexpirytext-talk' )
					->params( 'Talk:Foo' )
					->params( '1 Woche' ),
				'prefixedTitle' => 'Talk:Foo',
				'submittedExpiry' => '1 week',
			],
			[
				'wgWatchlistExpiry' => true,
				'msg' => MessageValue::new( 'addedwatchexpiryhours' )
					->params( 'Foo' ),
				'prefixedTitle' => 'Foo',
				'submittedExpiry' => '2020-09-17T14:00:00Z',
			],
			[
				'wgWatchlistExpiry' => true,
				'msg' => MessageValue::new( 'addedwatchexpiryhours-talk' )
					->params( 'Talk:Foo' ),
				'prefixedTitle' => 'Talk:Foo',
				'submittedExpiry' => '2020-09-17T14:00:00Z',
			],
		];
	}

	/**
	 * @dataProvider provideGetExpiryOptions
	 * @covers \MediaWiki\Actions\WatchAction::getExpiryOptions()
	 */
	public function testGetExpiryOptions(
		?string $expiry,
		string $defaultExpiry,
		array $expectedOptions,
		string $expectedDefault,
		bool $createWatchedItem = true
	): void {
		// Fake current time to be 2020-06-10T00:00:00Z
		ConvertibleTimestamp::setFakeTime( '20200610000000' );
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$target = new TitleValue( 0, 'SomeDbKey' );
		$watchedItem = $createWatchedItem
			? new WatchedItem( $userIdentity, $target, null, $expiry )
			: false;
		$res = WatchAction::getExpiryOptions( $this->context, $watchedItem, $defaultExpiry );
		$this->assertSame( [
			'options' => $expectedOptions,
			'default' => $expectedDefault,
		], $res );
	}

	/**
	 * @return Generator
	 */
	public static function provideGetExpiryOptions(): Generator {
		$originalOptions = [
			'Permanent' => 'infinite',
			'1 week' => '1 week',
			'1 month' => '1 month',
			'3 months' => '3 months',
			'6 months' => '6 months',
			'1 year' => '1 year',
		];
		$originalDefaultExpiry = 'infinite';
		$expectedDefault = 'infinite';
		yield 'Page is not currently watched at all' => [
			'expiry' => null,
			'defaultExpiry' => $originalDefaultExpiry,
			'expectedOptions' => $originalOptions,
			'expectedDefault' => $expectedDefault,
			'watchedItem' => false,
		];
		yield 'No expiry' => [
			'expiry' => null,
			'defaultExpiry' => $originalDefaultExpiry,
			'expectedOptions' => $originalOptions,
			'expectedDefault' => $expectedDefault,
		];
		yield 'Adding a watched item with an expiry a month from the frozen time' => [
			'expiry' => '20200710000000',
			'defaultExpiry' => $originalDefaultExpiry,
			'expectedOptions' => array_merge( [ '30 days left' => '2020-07-10T00:00:00Z' ], $originalOptions ),
			'expectedDefault' => '2020-07-10T00:00:00Z',
		];
		yield 'Adding a watched item with an expiry 7 days from the frozen time' => [
			'expiry' => '20200617000000',
			'defaultExpiry' => $originalDefaultExpiry,
			'expectedOptions' => array_merge( [ '7 days left' => '2020-06-17T00:00:00Z' ], $originalOptions ),
			'expectedDefault' => '2020-06-17T00:00:00Z',
		];
		yield 'Adding a watched item with an expiry 7 days from the frozen time, but default of 1 month.' => [
			'expiry' => '20200617000000',
			'defaultExpiry' => '1 month',
			'expectedOptions' => array_merge( [ '7 days left' => '2020-06-17T00:00:00Z' ], $originalOptions ),
			'expectedDefault' => '2020-06-17T00:00:00Z',
		];
	}

	/**
	 * @covers \MediaWiki\Actions\WatchAction::getExpiryOptions()
	 */
	public function testGetExpiryOptionsWithInvalidTranslations() {
		$mockMessageLocalizer = $this->createMock( MockMessageLocalizer::class );
		$mockLanguage = $this->createMock( Language::class );
		$mockLanguage->method( 'getCode' )->willReturn( 'not-english' );
		$mockMessage = $this->getMockMessage( 'invalid:invalid, foo:bar, thing' );
		$mockMessage->method( 'getLanguage' )->willReturn( $mockLanguage );

		$mockMessageLocalizer->expects( $this->exactly( 2 ) )
			->method( 'msg' )
			->willReturnOnConsecutiveCalls(
					$mockMessage,
					new Message( 'watchlist-expiry-options' )
				);

		$expected = WatchAction::getExpiryOptions( new MockMessageLocalizer( 'en' ), false );
		$expiryOptions = WatchAction::getExpiryOptions( $mockMessageLocalizer, false );
		$this->assertSame( $expected, $expiryOptions );
	}

	/**
	 * @covers \MediaWiki\Actions\WatchAction::getExpiryOptions()
	 */
	public function testGetExpiryOptionsWithPartialInvalidTranslations() {
		$mockMessageLocalizer = $this->createMock( MockMessageLocalizer::class );
		$mockMessageLocalizer->expects( $this->once() )
			->method( 'msg' )
			->with( 'watchlist-expiry-options' )
			->willReturn( $this->getMockMessage( 'invalid:invalid, thing, 1 week: 1 week,3 days:3 days' ) );

		$expected = [
			'options' => [
				'infinite' => 'infinite',
				'1 week' => '1 week',
				'3 days' => '3 days',
			],
			'default' => 'infinite'
		];
		$expiryOptions = WatchAction::getExpiryOptions( $mockMessageLocalizer, false );
		$this->assertSame( $expected, $expiryOptions );
	}
}
