<?php

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

	protected function setUp() {
		parent::setUp();

		$testTitle = Title::newFromText( 'UTTest' );
		$this->testWikiPage = new WikiPage( $testTitle );
		$testContext = new DerivativeContext( RequestContext::getMain() );
		$testContext->setTitle( $testTitle );
		$this->watchAction = new WatchAction( $this->testWikiPage, $testContext );
	}

	/**
	 * @throws MWException
	 */
	protected function tearDown() {
		parent::tearDown();

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
		$watchAction = new WatchAction( $this->testWikiPage, $testContext );
		$this->setExpectedException( UserNotLoggedIn::class );

		$watchAction->show();
	}

	/**
	 * @covers WatchAction::checkCanExecute()
	 */
	public function testShowUserLoggedInNoException() {
		$loggedInUser = $this->getMock( User::class );
		$loggedInUser->method( 'isLoggedIn' )->willReturn( true );
		$testContext = new DerivativeContext( $this->watchAction->getContext() );
		$testContext->setUser( $loggedInUser );
		$watchAction = new WatchAction( $this->testWikiPage, $testContext );

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
		$testContext = $this->getMock(
			DerivativeContext::class,
			[ 'msg' ],
			[ $this->watchAction->getContext() ]
		);
		$testOutput = new OutputPage( $testContext );
		$testContext->setOutput( $testOutput );
		$testContext->method( 'msg' )->willReturnCallback( function ( $msgKey ) {
			return new RawMessage( $msgKey );
		} );
		$watchAction = new WatchAction( $this->testWikiPage, $testContext );

		$watchAction->onSuccess();

		$this->assertEquals( '<p>addedwatchtext
</p>', $testOutput->getHTML() );
	}

	/**
	 * @covers WatchAction::onSuccess()
	 */
	public function testOnSuccessTalkPage() {
		$testContext = $this->getMock(
			DerivativeContext::class,
			[],
			[ $this->watchAction->getContext() ]
		);
		$testOutput = new OutputPage( $testContext );
		$testContext->method( 'getOutput' )->willReturn( $testOutput );
		$testContext->method( 'msg' )->willReturnCallback( function ( $msgKey ) {
			return new RawMessage( $msgKey );
		} );
		$talkPageTitle = Title::newFromText( 'Talk:UTTest' );
		$testContext->setTitle( $talkPageTitle );
		$watchAction = new WatchAction( new WikiPage( $talkPageTitle ), $testContext );

		$watchAction->onSuccess();

		$this->assertEquals( '<p>addedwatchtext-talk
</p>', $testOutput->getHTML() );
	}

	/**
	 * @covers WatchAction::doWatch()
	 */
	public function testDoWatchNoCheckRights() {
		$notPermittedUser = $this->getMock( User::class );
		$notPermittedUser->method( 'isAllowed' )->willReturn( false );

		$actual = WatchAction::doWatch( $this->testWikiPage->getTitle(), $notPermittedUser, false );

		$this->assertTrue( $actual->isGood() );
	}

	/**
	 * @covers WatchAction::doWatch()
	 */
	public function testDoWatchUserNotPermittedStatusNotGood() {
		$notPermittedUser = $this->getMock( User::class );
		$notPermittedUser->method( 'isAllowed' )->willReturn( false );

		$actual = WatchAction::doWatch( $this->testWikiPage->getTitle(), $notPermittedUser, true );

		$this->assertFalse( $actual->isGood() );
	}

	/**
	 * @covers WatchAction::doWatch()
	 */
	public function testDoWatchCallsUserAddWatch() {
		$permittedUser = $this->getMock( User::class );
		$permittedUser->method( 'isAllowed' )->willReturn( true );
		$permittedUser->expects( $this->once() )
			->method( 'addWatch' )
			->with( $this->equalTo( $this->testWikiPage->getTitle() ), $this->equalTo( true ) );

		$actual = WatchAction::doWatch( $this->testWikiPage->getTitle(), $permittedUser );

		$this->assertTrue( $actual->isGood() );
	}

	/**
	 * @covers WatchAction::doUnWatch()
	 */
	public function testDoUnWatchWithoutRights() {
		$notPermittedUser = $this->getMock( User::class );
		$notPermittedUser->method( 'isAllowed' )->willReturn( false );

		$actual = WatchAction::doUnWatch( $this->testWikiPage->getTitle(), $notPermittedUser );

		$this->assertFalse( $actual->isGood() );
	}

	/**
	 * @covers WatchAction::doUnWatch()
	 */
	public function testDoUnWatchUserHookAborted() {
		$permittedUser = $this->getMock( User::class );
		$permittedUser->method( 'isAllowed' )->willReturn( true );
		Hooks::register( 'UnwatchArticle', function () {
			return false;
		} );

		$status = WatchAction::doUnWatch( $this->testWikiPage->getTitle(), $permittedUser );

		$this->assertFalse( $status->isGood() );
		$errors = $status->getErrors();
		$this->assertEquals( 1, count( $errors ) );
		$this->assertEquals( 'hookaborted', $errors[0]['message'] );
	}

	/**
	 * @covers WatchAction::doUnWatch()
	 */
	public function testDoUnWatchCallsUserRemoveWatch() {
		$permittedUser = $this->getMock( User::class );
		$permittedUser->method( 'isAllowed' )->willReturn( true );
		$permittedUser->expects( $this->once() )
			->method( 'removeWatch' )
			->with( $this->equalTo( $this->testWikiPage->getTitle() ) );

		$actual = WatchAction::doUnWatch( $this->testWikiPage->getTitle(), $permittedUser );

		$this->assertTrue( $actual->isGood() );
	}

	/**
	 * @covers WatchAction::getWatchToken()
	 */
	public function testGetWatchTokenNormalizesToWatch() {
		$user = $this->getMock( User::class );
		$user->expects( $this->once() )
			->method( 'getEditToken' )
			->with( $this->equalTo( 'watch' ) );

		WatchAction::getWatchToken( $this->watchAction->getTitle(), $user, 'INVALID_ACTION' );
	}

	/**
	 * @covers WatchAction::getWatchToken()
	 */
	public function testGetWatchTokenProxiesUserGetEditToken() {
		$user = $this->getMock( User::class );
		$user->expects( $this->once() )->method( 'getEditToken' );

		WatchAction::getWatchToken( $this->watchAction->getTitle(), $user );
	}

	/**
	 * @covers WatchAction::getUnwatchToken()
	 */
	public function testGetUnwatchToken() {
		$user = $this->getMock( User::class );
		$user->expects( $this->once() )->method( 'getEditToken' );
		$this->hideDeprecated( 'WatchAction::getUnwatchToken' );

		WatchAction::getUnWatchToken( $this->watchAction->getTitle(), $user );
	}

	/**
	 * @covers WatchAction::doWatchOrUnwatch()
	 */
	public function testDoWatchOrUnwatchUserNotLoggedIn() {
		$user = $this->getLoggedInIsWatchedUser( false );
		$user->expects( $this->never() )->method( 'removeWatch' );
		$user->expects( $this->never() )->method( 'addWatch' );

		$status = WatchAction::doWatchOrUnwatch( true, $this->watchAction->getTitle(), $user );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers WatchAction::doWatchOrUnwatch()
	 */
	public function testDoWatchOrUnwatchSkipsIfAlreadyWatched() {
		$user = $this->getLoggedInIsWatchedUser();
		$user->expects( $this->never() )->method( 'removeWatch' );
		$user->expects( $this->never() )->method( 'addWatch' );

		$status = WatchAction::doWatchOrUnwatch( true, $this->watchAction->getTitle(), $user );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers WatchAction::doWatchOrUnwatch()
	 */
	public function testDoWatchOrUnwatchSkipsIfAlreadyUnWatched() {
		$user = $this->getLoggedInIsWatchedUser( true, false );
		$user->expects( $this->never() )->method( 'removeWatch' );
		$user->expects( $this->never() )->method( 'addWatch' );

		$status = WatchAction::doWatchOrUnwatch( false, $this->watchAction->getTitle(), $user );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers WatchAction::doWatchOrUnwatch()
	 */
	public function testDoWatchOrUnwatchWatchesIfWatch() {
		$user = $this->getLoggedInIsWatchedUser( true, false );
		$user->expects( $this->never() )->method( 'removeWatch' );
		$user->expects( $this->once() )
			->method( 'addWatch' )
			->with( $this->equalTo( $this->testWikiPage->getTitle() ), $this->equalTo( false ) );

		$status = WatchAction::doWatchOrUnwatch( true, $this->watchAction->getTitle(), $user );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers WatchAction::doWatchOrUnwatch()
	 */
	public function testDoWatchOrUnwatchUnwatchesIfUnwatch() {
		$user = $this->getLoggedInIsWatchedUser();
		$user->method( 'isAllowed' )->willReturn( true );
		$user->expects( $this->never() )->method( 'addWatch' );
		$user->expects( $this->once() )
			->method( 'removeWatch' )
			->with( $this->equalTo( $this->testWikiPage->getTitle() ) );

		$status = WatchAction::doWatchOrUnwatch( false, $this->watchAction->getTitle(), $user );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @param bool $isLoggedIn Whether the user should be "marked" as logged in
	 * @param bool $isWatched The value any call to isWatched should return
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	private function getLoggedInIsWatchedUser( $isLoggedIn = true, $isWatched = true ) {
		$user = $this->getMock( User::class );
		$user->method( 'isLoggedIn' )->willReturn( $isLoggedIn );
		$user->method( 'isWatched' )->willReturn( $isWatched );

		return $user;
	}

}
