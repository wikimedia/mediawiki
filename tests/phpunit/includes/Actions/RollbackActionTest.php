<?php

namespace MediaWiki\Tests\Actions;

use MediaWiki\Actions\RollbackAction;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Article;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\Title\Title;
use MediaWiki\User\StaticUserOptionsLookup;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Actions\RollbackAction
 * @group Database
 */
class RollbackActionTest extends MediaWikiIntegrationTestCase {

	private User $vandal;
	private User $sysop;
	private Title $testPage;

	protected function setUp(): void {
		parent::setUp();
		$this->testPage = Title::makeTitle( NS_MAIN, 'RollbackActionTest' );

		$this->vandal = $this->getTestUser()->getUser();
		$this->sysop = $this->getTestSysop()->getUser();
		$this->editPage( $this->testPage, 'Some text', '', NS_MAIN, $this->sysop );
		$this->editPage( $this->testPage, 'Vandalism', '', NS_MAIN, $this->vandal );
	}

	private function getRollbackAction( WebRequest $request ) {
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setTitle( $this->testPage );
		$context->setRequest( $request );
		$context->setUser( $this->sysop );
		$mwServices = $this->getServiceContainer();
		return new RollbackAction(
			Article::newFromTitle( $this->testPage, $context ),
			$context,
			$mwServices->getContentHandlerFactory(),
			$mwServices->getRollbackPageFactory(),
			$mwServices->getUserOptionsLookup(),
			$mwServices->getWatchlistManager(),
			$mwServices->getCommentFormatter()
		);
	}

	public static function provideRollbackParamFail() {
		yield 'No from parameter' => [
			'requestParams' => [],
		];
		yield 'Non existent user' => [
			'requestParams' => [
				'from' => 'abirvalg',
			],
		];
		yield 'User mismatch' => [
			'requestParams' => [
				'from' => 'UTSysop',
			],
		];
	}

	/**
	 * @dataProvider provideRollbackParamFail
	 */
	public function testRollbackParamFail( array $requestParams ) {
		$request = new FauxRequest( $requestParams );
		$rollbackAction = $this->getRollbackAction( $request );
		$this->expectException( ErrorPageError::class );
		$rollbackAction->handleRollbackRequest();
	}

	public function testRollbackTokenMismatch() {
		$request = new FauxRequest( [
			'from' => $this->vandal->getName(),
			'token' => 'abrvalg',
		] );
		$rollbackAction = $this->getRollbackAction( $request );
		$this->expectException( ErrorPageError::class );
		$rollbackAction->handleRollbackRequest();
	}

	public function testRollback() {
		$editTracker = $this->getServiceContainer()->getUserEditTracker();
		$editCount = $editTracker->getUserEditCount( $this->sysop );

		// Setup preferences to auto-watch the page temporarily.
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
		$mockUserOptionsLookup = new StaticUserOptionsLookup( [
			$this->sysop->getName() => [
				'watchrollback' => '1',
				'watchrollback-expiry' => '1 week'
			],
		] );
		$this->setService( 'UserOptionsLookup', $mockUserOptionsLookup );
		// Assert the page isn't already watched.
		$this->assertFalse( $this->getServiceContainer()->getWatchlistManager()
			->isTempWatchedIgnoringRights( $this->sysop, $this->testPage ) );

		// Create the mock request and do the rollback.
		$request = new FauxRequest( [
			'from' => $this->vandal->getName(),
			'token' => $this->sysop->getEditToken( 'rollback' ),
		] );
		$rollbackAction = $this->getRollbackAction( $request );
		$rollbackAction->handleRollbackRequest();

		$revisionStore = $this->getServiceContainer()->getRevisionStore();
		// Content of latest revision should match the initial.
		$latestRev = $revisionStore->getRevisionByTitle( $this->testPage );
		$initialRev = $revisionStore->getFirstRevision( $this->testPage );
		$this->assertTrue( $latestRev->hasSameContent( $initialRev ) );
		// ...but have different rev IDs.
		$this->assertNotSame( $latestRev->getId(), $initialRev->getId() );

		$recentChange = $revisionStore->getRecentChange( $latestRev );
		$this->assertSame( '0', $recentChange->getAttribute( 'rc_bot' ) );
		$this->assertSame( $this->sysop->getName(), $recentChange->getAttribute( 'rc_user_text' ) );

		// T382592
		$editTracker->clearUserEditCache( $this->sysop );
		$this->runDeferredUpdates();
		$this->assertSame( $editCount + 1, $editTracker->getUserEditCount( $this->sysop ) );

		// Assert the auto-watch preference feature is working.
		$this->assertTrue( $this->getServiceContainer()->getWatchlistManager()
			->isTempWatchedIgnoringRights( $this->sysop, $this->testPage ) );
	}

	public function testRollbackMarkBot() {
		$request = new FauxRequest( [
			'from' => $this->vandal->getName(),
			'token' => $this->sysop->getEditToken( 'rollback' ),
			'bot' => true,
		] );
		$rollbackAction = $this->getRollbackAction( $request );
		$rollbackAction->handleRollbackRequest();

		$revisionStore = $this->getServiceContainer()->getRevisionStore();
		$latestRev = $revisionStore->getRevisionByTitle( $this->testPage );
		$recentChange = $revisionStore->getRecentChange( $latestRev );
		$this->assertSame( '1', $recentChange->getAttribute( 'rc_bot' ) );
	}
}
