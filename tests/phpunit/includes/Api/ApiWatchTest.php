<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group API
 * @group Database
 * @group medium
 * @covers \MediaWiki\Api\ApiWatch
 */
class ApiWatchTest extends ApiTestCase {
	protected function setUp(): void {
		parent::setUp();

		// Fake current time to be 2019-06-05T19:50:42Z
		ConvertibleTimestamp::setFakeTime( 1559764242 );

		$this->overrideConfigValues( [
			MainConfigNames::WatchlistExpiry => true,
			MainConfigNames::WatchlistExpiryMaxDuration => '6 months',
		] );
	}

	public function testWatch() {
		// Watch for a duration greater than the max ($wgWatchlistExpiryMaxDuration),
		// which should get changed to the max
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Talk:Test page',
			'expiry' => '99990101000000',
			'formatversion' => 2
		] );

		$res = $data[0]['watch'][0];
		$this->assertSame( 'Talk:Test page', $res['title'] );
		$this->assertSame( 1, $res['ns'] );
		$this->assertTrue( $res['watched'] );
		$this->assertSame( '2019-12-05T19:50:42Z', $res['expiry'] );

		// Re-watch, changing the expiry to indefinite.
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Talk:Test page',
			'expiry' => 'indefinite',
			'formatversion' => 2
		] );
		$res = $data[0]['watch'][0];
		$this->assertSame( 'infinity', $res['expiry'] );
	}

	public function testWatchWithExpiry() {
		$store = $this->getServiceContainer()->getWatchedItemStore();
		$user = $this->getTestUser()->getUser();
		$pageTitle = 'TestWatchWithExpiry';

		// First watch without expiry (indefinite).
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => $pageTitle,
		], null, $user );

		// Ensure page was added to the user's watchlist, and expiry is null (not set).
		[ $item ] = $store->getWatchedItemsForUser( $user );
		$this->assertSame( $pageTitle, $item->getTarget()->getDBkey() );
		$this->assertNull( $item->getExpiry() );

		// Re-watch, setting an expiry.
		$expiry = '2 weeks';
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => $pageTitle,
			'expiry' => $expiry,
		], null, $user );
		[ $item ] = $store->getWatchedItemsForUser( $user );
		$this->assertSame( '20190619195042', $item->getExpiry() );

		// Re-watch again, providing no expiry parameter, so expiry should remain unchanged.
		$oldExpiry = $item->getExpiry();
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => $pageTitle,
		], null, $user );
		[ $item ] = $store->getWatchedItemsForUser( $user );
		$this->assertSame( $oldExpiry, $item->getExpiry() );
	}

	public function testWatchInvalidExpiry() {
		$this->expectApiErrorCode( 'badexpiry' );

		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Talk:Test page',
			'expiry' => 'invalid expiry',
			'formatversion' => 2
		] );
	}

	public function testWatchExpiryInPast() {
		$this->expectApiErrorCode( 'badexpiry-past' );

		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Talk:Test page',
			'expiry' => '20010101000000',
			'formatversion' => 2
		] );
	}

	public function testWatchEdit() {
		$data = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => 'Help:TestWatchEdit', // Help namespace is hopefully wikitext
			'text' => 'new text',
			'watchlist' => 'watch'
		] );

		$this->assertArrayHasKey( 'edit', $data[0] );
		$this->assertArrayHasKey( 'result', $data[0]['edit'] );
		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		return $data;
	}

	/**
	 * @depends testWatchEdit
	 */
	public function testWatchClear() {
		$data = $this->doApiRequest( [
			'action' => 'query',
			'wllimit' => 'max',
			'list' => 'watchlist' ] );

		if ( isset( $data[0]['query']['watchlist'] ) ) {
			$wl = $data[0]['query']['watchlist'];

			foreach ( $wl as $page ) {
				$data = $this->doApiRequestWithToken( [
					'action' => 'watch',
					'title' => $page['title'],
					'unwatch' => true,
				] );
			}
		}
		$data = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlist' ] );
		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'watchlist', $data[0]['query'] );
		foreach ( $data[0]['query']['watchlist'] as $index => $item ) {
			// Previous tests may insert an invalid title
			// like ":ApiEditPageTest testNonTextEdit", which
			// can't be cleared.
			if ( str_starts_with( $item['title'], ':' ) ) {
				unset( $data[0]['query']['watchlist'][$index] );
			}
		}
		$this->assertSame( [], $data[0]['query']['watchlist'] );

		return $data;
	}

	public function testWatchProtect() {
		$pageTitle = 'Help:TestWatchProtect';
		$this->getExistingTestPage( $pageTitle );
		$data = $this->doApiRequestWithToken( [
			'action' => 'protect',
			'title' => $pageTitle,
			'protections' => 'edit=sysop',
			'watchlist' => 'unwatch'
		] );

		$this->assertArrayHasKey( 'protect', $data[0] );
		$this->assertArrayHasKey( 'protections', $data[0]['protect'] );
		$this->assertCount( 1, $data[0]['protect']['protections'] );
		$this->assertArrayHasKey( 'edit', $data[0]['protect']['protections'][0] );
	}

	public function testWatchRollback() {
		$titleText = 'Help:TestWatchRollback';
		$title = Title::makeTitle( NS_HELP, 'TestWatchRollback' );
		$revertingUser = $this->getTestSysop()->getUser();
		$revertedUser = $this->getTestUser()->getUser();
		$this->editPage( $title, 'Edit 1', '', NS_MAIN, $revertingUser );
		$this->editPage( $title, 'Edit 2', '', NS_MAIN, $revertedUser );

		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();

		// This (and assertTrue below) are mostly for completeness.
		$this->assertFalse( $watchlistManager->isWatched( $revertingUser, $title ) );

		$data = $this->doApiRequestWithToken( [
			'action' => 'rollback',
			'title' => $titleText,
			'user' => $revertedUser,
			'watchlist' => 'watch'
		] );

		$this->assertArrayHasKey( 'rollback', $data[0] );
		$this->assertArrayHasKey( 'title', $data[0]['rollback'] );
		$this->assertTrue( $watchlistManager->isWatched( $revertingUser, $title ) );
	}
}
