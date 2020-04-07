<?php

use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers WatchAction
 *
 * @group Action
 */
class WatchActionTest extends MediaWikiTestCase {

	/**
	 * @var WatchAction
	 */
	private $watchAction;

	/**
	 * @var WikiPage
	 */
	private $testWikiPage;

	/**
	 * @var IContextSource
	 */
	private $context;

	protected function setUp() : void {
		parent::setUp();

		$testTitle = Title::newFromText( 'UTTest' );
		$this->testWikiPage = new WikiPage( $testTitle );
		$testContext = new DerivativeContext( RequestContext::getMain() );
		$testContext->setTitle( $testTitle );
		$this->context = $testContext;
		$this->watchAction = new WatchAction(
			Article::newFromWikiPage( $this->testWikiPage, $testContext ),
			$testContext
		);
	}

	/**
	 * @throws MWException
	 */
	protected function tearDown() : void {
		parent::tearDown();

		$this->hideDeprecated( 'Hooks::clear' );
		Hooks::clear( 'WatchArticle' );
		Hooks::clear( 'UnwatchArticle' );
	}

	/**
	 * @covers WatchAction::getName()
	 */
	public function testGetName() {
		$this->assertEquals( 'watch', $this->watchAction->getName() );
	}

	/**
	 * @covers WatchAction::requiresUnblock()
	 */
	public function testRequiresUnlock() {
		$this->assertFalse( $this->watchAction->requiresUnblock() );
	}

	/**
	 * @covers WatchAction::doesWrites()
	 */
	public function testDoesWrites() {
		$this->assertTrue( $this->watchAction->doesWrites() );
	}

	/**
	 * @covers WatchAction::onSubmit()
	 * @covers WatchAction::doWatch()
	 */
	public function testOnSubmit() {
		/** @var Status $actual */
		$actual = $this->watchAction->onSubmit( [] );

		$this->assertTrue( $actual->isGood() );
	}

	/**
	 * @covers WatchAction::onSubmit()
	 * @covers WatchAction::doWatch()
	 */
	public function testOnSubmitHookAborted() {
		Hooks::register( 'WatchArticle', function () {
			return false;
		} );

		/** @var Status $actual */
		$actual = $this->watchAction->onSubmit( [] );

		$this->assertInstanceOf( Status::class, $actual );
		$this->assertTrue( $actual->hasMessage( 'hookaborted' ) );
	}

	/**
	 * @covers WatchAction::checkCanExecute()
	 */
	public function testShowUserNotLoggedIn() {
		$notLoggedInUser = new User();
		$testContext = new DerivativeContext( $this->watchAction->getContext() );
		$testContext->setUser( $notLoggedInUser );
		$watchAction = new WatchAction(
			Article::newFromWikiPage( $this->testWikiPage, $testContext ),
			$testContext
		);
		$this->expectException( UserNotLoggedIn::class );

		$watchAction->show();
	}

	/**
	 * @covers WatchAction::checkCanExecute()
	 */
	public function testShowUserLoggedInNoException() {
		$loggedInUser = $this->createMock( User::class );
		$loggedInUser->method( 'isLoggedIn' )->willReturn( true );
		$testContext = new DerivativeContext( $this->watchAction->getContext() );
		$testContext->setUser( $loggedInUser );
		$watchAction = new WatchAction(
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
			'UserNotLoggedIn exception should not be thrown if user is logged in.' );
	}

	/**
	 * @covers WatchAction::onSuccess()
	 */
	public function testOnSuccessMainNamespaceTitle() {
		$testContext = $this->getMockBuilder( DerivativeContext::class )
			->setMethods( [ 'msg' ] )
			->setConstructorArgs( [ $this->watchAction->getContext() ] )
			->getMock();
		$testOutput = new OutputPage( $testContext );
		$testContext->setOutput( $testOutput );
		$testContext->method( 'msg' )->willReturnCallback( function ( $msgKey ) {
			return new RawMessage( $msgKey );
		} );
		$watchAction = new WatchAction(
			Article::newFromWikiPage( $this->testWikiPage, $testContext ),
			$testContext
		);

		$watchAction->onSuccess();

		$this->assertEquals( '<p>addedwatchtext
</p>', $testOutput->getHTML() );
	}

	/**
	 * @covers WatchAction::onSuccess()
	 */
	public function testOnSuccessTalkPage() {
		$testContext = $this->getMockBuilder( DerivativeContext::class )
			->setMethods( [ 'getOutput', 'msg' ] )
			->setConstructorArgs( [ $this->watchAction->getContext() ] )
			->getMock();
		$testOutput = new OutputPage( $testContext );
		$testContext->method( 'getOutput' )->willReturn( $testOutput );
		$testContext->method( 'msg' )->willReturnCallback( function ( $msgKey ) {
			return new RawMessage( $msgKey );
		} );
		$talkPageTitle = Title::newFromText( 'Talk:UTTest' );
		$testContext->setTitle( $talkPageTitle );
		$watchAction = new WatchAction(
			Article::newFromTitle( $talkPageTitle, $testContext ),
			$testContext
		);

		$watchAction->onSuccess();

		$this->assertEquals( '<p>addedwatchtext-talk
</p>', $testOutput->getHTML() );
	}

	/**
	 * @covers WatchAction::doWatch()
	 * @throws Exception
	 */
	public function testDoWatchNoCheckRights() {
		$notPermittedUser = $this->getUser( null, null, [] );
		$actual = WatchAction::doWatch( $this->testWikiPage->getTitle(), $notPermittedUser, false );
		$this->assertTrue( $actual->isGood() );
	}

	/**
	 * @covers WatchAction::doWatch()
	 * @throws Exception
	 */
	public function testDoWatchUserNotPermittedStatusNotGood() {
		$notPermittedUser = $this->getUser( null, null, [] );
		$actual = WatchAction::doWatch( $this->testWikiPage->getTitle(), $notPermittedUser, true );
		$this->assertFalse( $actual->isGood() );
	}

	/**
	 * @covers WatchAction::doWatch()
	 * @throws Exception
	 */
	public function testDoWatchCallsUserAddWatch() {
		$permittedUser = $this->getUser( null, null, [ 'editmywatchlist' ] );
		$permittedUser->expects( $this->once() )
			->method( 'addWatch' )
			->with( $this->equalTo( $this->testWikiPage->getTitle() ), $this->equalTo( true ) );

		$actual = WatchAction::doWatch( $this->testWikiPage->getTitle(), $permittedUser );

		$this->assertTrue( $actual->isGood() );
	}

	/**
	 * @covers WatchAction::doUnWatch()
	 * @throws Exception
	 */
	public function testDoUnWatchWithoutRights() {
		$notPermittedUser = $this->getUser( null, null, [] );
		$actual = WatchAction::doUnwatch( $this->testWikiPage->getTitle(), $notPermittedUser );

		$this->assertFalse( $actual->isGood() );
	}

	/**
	 * @covers WatchAction::doUnWatch()
	 */
	public function testDoUnWatchUserHookAborted() {
		$permittedUser = $this->getUser( null, null, [ 'editmywatchlist' ] );
		Hooks::register( 'UnwatchArticle', function () {
			return false;
		} );

		$status = WatchAction::doUnwatch( $this->testWikiPage->getTitle(), $permittedUser );

		$this->assertFalse( $status->isGood() );
		$errors = $status->getErrors();
		$this->assertCount( 1, $errors );
		$this->assertEquals( 'hookaborted', $errors[0]['message'] );
	}

	/**
	 * @covers WatchAction::doUnWatch()
	 * @throws Exception
	 */
	public function testDoUnWatchCallsUserRemoveWatch() {
		$permittedUser = $this->getUser( null, null,  [ 'editmywatchlist' ] );
		$permittedUser->expects( $this->once() )
			->method( 'removeWatch' )
			->with( $this->equalTo( $this->testWikiPage->getTitle() ) );

		$actual = WatchAction::doUnwatch( $this->testWikiPage->getTitle(), $permittedUser );

		$this->assertTrue( $actual->isGood() );
	}

	/**
	 * @covers WatchAction::getWatchToken()
	 * @throws Exception
	 */
	public function testGetWatchTokenNormalizesToWatch() {
		$user = $this->getUser( null, null );
		$user->expects( $this->once() )
			->method( 'getEditToken' )
			->with( $this->equalTo( 'watch' ) );

		WatchAction::getWatchToken( $this->watchAction->getTitle(), $user, 'INVALID_ACTION' );
	}

	/**
	 * @covers WatchAction::getWatchToken()
	 * @throws Exception
	 */
	public function testGetWatchTokenProxiesUserGetEditToken() {
		$user = $this->getUser( null, null );
		$user->expects( $this->once() )->method( 'getEditToken' );

		WatchAction::getWatchToken( $this->watchAction->getTitle(), $user );
	}

	/**
	 * @covers WatchAction::doWatchOrUnwatch()
	 * @throws Exception
	 */
	public function testDoWatchOrUnwatchUserNotLoggedIn() {
		$user = $this->getUser( false );
		$user->expects( $this->never() )->method( 'removeWatch' );
		$user->expects( $this->never() )->method( 'addWatch' );

		$status = WatchAction::doWatchOrUnwatch( true, $this->watchAction->getTitle(), $user );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers WatchAction::doWatchOrUnwatch()
	 * @throws Exception
	 */
	public function testDoWatchOrUnwatchSkipsIfAlreadyWatched() {
		$user = $this->getUser();
		$user->expects( $this->never() )->method( 'removeWatch' );
		$user->expects( $this->never() )->method( 'addWatch' );

		$status = WatchAction::doWatchOrUnwatch( true, $this->watchAction->getTitle(), $user );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers WatchAction::doWatchOrUnwatch()
	 * @throws Exception
	 */
	public function testDoWatchOrUnwatchSkipsIfAlreadyUnWatched() {
		$user = $this->getUser( true, false );
		$user->expects( $this->never() )->method( 'removeWatch' );
		$user->expects( $this->never() )->method( 'addWatch' );

		$status = WatchAction::doWatchOrUnwatch( false, $this->watchAction->getTitle(), $user );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers WatchAction::doWatchOrUnwatch()
	 * @throws Exception
	 */
	public function testDoWatchOrUnwatchWatchesIfWatch() {
		$user = $this->getUser( true, false );
		$user->expects( $this->never() )->method( 'removeWatch' );
		$user->expects( $this->once() )
			->method( 'addWatch' )
			->with( $this->equalTo( $this->testWikiPage->getTitle() ), $this->equalTo( false ) );

		$status = WatchAction::doWatchOrUnwatch( true, $this->watchAction->getTitle(), $user );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers WatchAction::doWatchOrUnwatch()
	 * @throws Exception
	 */
	public function testDoWatchOrUnwatchUnwatchesIfUnwatch() {
		$user = $this->getUser( true, true, [ 'editmywatchlist' ] );
		$user->expects( $this->never() )->method( 'addWatch' );
		$user->expects( $this->once() )
			->method( 'removeWatch' )
			->with( $this->equalTo( $this->testWikiPage->getTitle() ) );

		$status = WatchAction::doWatchOrUnwatch( false, $this->watchAction->getTitle(), $user );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers WatchAction::getExpiryOptions()
	 */
	public function testGetExpiryOptions() {
		// Fake current time to be 2020-06-10T00:00:00Z
		$fakeTime = ConvertibleTimestamp::setFakeTime( '20200610000000' );
		$user = $this->getUser();
		$target = new TitleValue( 0, 'SomeDbKey' );

		$optionsNoExpiry = WatchAction::getExpiryOptions( $this->context, false );
		$expectedNoExpiry = [
			'options' => [
				'Permanently' => 'infinite',
				'1 week' => '1 week',
				'1 month' => '1 month',
				'3 months' => '3 months',
				'6 months' => '6 months'
			],
			'default' => 'infinite'
		];

		$this->assertSame( $expectedNoExpiry, $optionsNoExpiry );

		// Adding a watched item with an expiry a month from the frozen time
		$watchedItemMonth = new WatchedItem( $user, $target, null, '20200710000000' );
		$optionsExpiryOneMonth = WatchAction::getExpiryOptions( $this->context, $watchedItemMonth );
		$expectedExpiryOneMonth = [
			'options' => [
				'30 days left' => '20200710000000',
				'Permanently' => 'infinite',
				'1 week' => '1 week',
				'1 month' => '1 month',
				'3 months' => '3 months',
				'6 months' => '6 months'
			],
			'default' => '20200710000000'
		];

		$this->assertSame( $expectedExpiryOneMonth, $optionsExpiryOneMonth );

		// Adding a watched item with an expiry 7 days from the frozen time
		$watchedItemWeek = new WatchedItem( $user, $target, null, '20200617000000' );
		$optionsExpiryOneWeek = WatchAction::getExpiryOptions( $this->context, $watchedItemWeek );
		$expectedOneWeek = [
			'options' => [
				'7 days left' => '20200617000000',
				'Permanently' => 'infinite',
				'1 week' => '1 week',
				'1 month' => '1 month',
				'3 months' => '3 months',
				'6 months' => '6 months'
			],
			'default' => '20200617000000'
		];

		$this->assertSame( $expectedOneWeek, $optionsExpiryOneWeek );

		// Case for when WatchedItem is true
		$optionsNoExpiryWIFalse = WatchAction::getExpiryOptions( $this->context, true );
		$expectedNoExpiryWIFalse = [
			'options' => [
				'Permanently' => 'infinite',
				'1 week' => '1 week',
				'1 month' => '1 month',
				'3 months' => '3 months',
				'6 months' => '6 months'
			],
			'default' => 'infinite'
		];

		$this->assertSame( $expectedNoExpiryWIFalse, $optionsNoExpiryWIFalse );
	}

	/**
	 * @covers WatchAction::doWatchOrUnwatch()
	 */
	public function testDoWatchOrUnwatchWithExpiry() {
		// Already watched, but we're adding an expiry so 'addWatch' should be called.
		$user = $this->getUser( true, true, [ 'editmywatchlist' ] );
		$user->expects( $this->once() )->method( 'addWatch' );
		$status = WatchAction::doWatchOrUnwatch( true, $this->watchAction->getTitle(), $user, '1 week' );
		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @param bool $isLoggedIn Whether the user should be "marked" as logged in
	 * @param bool $isWatched The value any call to isWatched should return
	 * @param array $permissions The permissions of the user
	 * @return MockObject
	 * @throws Exception
	 */
	private function getUser(
		$isLoggedIn = true,
		$isWatched = true,
		$permissions = []
	) {
		$user = $this->createMock( User::class );
		$user->method( 'getId' )->willReturn( 42 );
		$user->method( 'isLoggedIn' )->willReturn( $isLoggedIn );
		$user->method( 'isWatched' )->willReturn( $isWatched );
		$this->overrideUserPermissions( $user, $permissions );
		return $user;
	}

}
