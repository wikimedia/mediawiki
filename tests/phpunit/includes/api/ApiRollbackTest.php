<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiUsageException;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;

/**
 * Tests for Rollback API.
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers MediaWiki\Api\ApiRollback
 */
class ApiRollbackTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
	}

	public function testProtectWithWatch(): void {
		$title = Title::makeTitle( NS_MAIN, 'TestProtectWithWatch' );
		$revisionStore = $this->getServiceContainer()->getRevisionStore();

		$user = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		// Create page as sysop.
		$this->editPage( $title, 'Some text', '', NS_MAIN, $sysop );

		// Edit as non-sysop.
		$this->editPage( $title, 'Vandalism', '', NS_MAIN, $user );

		// Rollback as sysop.
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'rollback',
			'title' => $title->getPrefixedText(),
			'user' => $user->getName(),
			'watchlist' => 'watch',
			'watchlistexpiry' => '99990123000000',
		] )[0];

		// Content of latest revision should match the initial.
		$latestRev = $revisionStore->getRevisionByTitle( $title );
		$initialRev = $revisionStore->getFirstRevision( $title );
		$this->assertTrue( $latestRev->hasSameContent( $initialRev ) );
		// ...but have different rev IDs.
		$this->assertNotSame( $latestRev->getId(), $initialRev->getId() );

		// Make sure the API response looks good.
		$this->assertArrayHasKey( 'rollback', $apiResult );
		$this->assertSame( $title->getPrefixedText(), $apiResult['rollback']['title'] );

		// And that the page was temporarily watched.
		$this->assertTrue( $this->getServiceContainer()->getWatchlistManager()->isTempWatched( $sysop, $title ) );

		$recentChange = $revisionStore->getRecentChange( $latestRev );
		$this->assertSame( '0', $recentChange->getAttribute( 'rc_bot' ) );
		$this->assertSame( $sysop->getName(), $recentChange->getAttribute( 'rc_user_text' ) );
	}

	public function testRollbackMarkAsBot() {
		$revisionStore = $this->getServiceContainer()->getRevisionStore();
		$title = Title::makeTitle( NS_MAIN, 'ApiRollbackTest::testRollbackMarkAsBot' );

		$user = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		// Create page as sysop.
		$this->editPage( $title, 'Some text', '', NS_MAIN, $sysop );

		// Edit as non-sysop.
		$this->editPage( $title, 'Vandalism', '', NS_MAIN, $user );

		// Rollback as sysop.
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'rollback',
			'title' => $title->getPrefixedText(),
			'user' => $user->getName(),
			'markbot' => true
		] )[0];
		// Make sure the API response looks good.
		$this->assertArrayHasKey( 'rollback', $apiResult );
		$this->assertSame( $title->getPrefixedText(), $apiResult['rollback']['title'] );

		$recentChange = $revisionStore->getRecentChange( $revisionStore->getRevisionByTitle( $title ) );
		$this->assertSame( '1', $recentChange->getAttribute( 'rc_bot' ) );
	}

	public function testRollbackNoToken() {
		$user = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$title = Title::makeTitle( NS_MAIN, 'ApiRollbackTest::testRollbackNoToken' );
		// Create page as sysop.
		$this->editPage( $title, 'Some text', '', NS_MAIN, $sysop );

		// Edit as non-sysop.
		$this->editPage( $title, 'Vandalism', '', NS_MAIN, $user );

		$this->expectException( ApiUsageException::class );
		$this->doApiRequest( [
			'action' => 'rollback',
			'title' => $title->getPrefixedText(),
			'user' => $user->getName(),
		] )[0];
	}
}
