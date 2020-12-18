<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group API
 * @group Database
 * @group medium
 * @todo This test suite is severely broken and need a full review
 *
 * @covers ApiWatch
 */
class ApiWatchTest extends ApiTestCase {
	protected function setUp(): void {
		parent::setUp();

		// Fake current time to be 2019-06-05T19:50:42Z
		ConvertibleTimestamp::setFakeTime( 1559764242 );

		$this->setMwGlobals( [
			'wgWatchlistExpiry' => true,
			'wgWatchlistExpiryMaxDuration' => '6 months',
		] );
	}

	protected function tearDown(): void {
		parent::tearDown();

		ConvertibleTimestamp::setFakeTime( false );
	}

	protected function getTokens() {
		return $this->getTokenList( self::$users['sysop'] );
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
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$user = $this->getTestUser()->getUser();

		// First watch without expiry (indefinite).
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'UTPage',
		], null, $user );

		// Ensure page was added to the user's watchlist, and expiry is null (not set).
		[ $item ] = $store->getWatchedItemsForUser( $user );
		$this->assertSame( 'UTPage', $item->getLinkTarget()->getDBkey() );
		$this->assertNull( $item->getExpiry() );

		// Re-watch, setting an expiry.
		$expiry = '2 weeks';
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'UTPage',
			'expiry' => $expiry,
		], null, $user );
		[ $item ] = $store->getWatchedItemsForUser( $user );
		$this->assertSame( '20190619195042', $item->getExpiry() );

		// Re-watch again, providing no expiry parameter, so expiry should remain unchanged.
		$oldExpiry = $item->getExpiry();
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'UTPage',
		], null, $user );
		[ $item ] = $store->getWatchedItemsForUser( $user );
		$this->assertSame( $oldExpiry, $item->getExpiry() );
	}

	public function testWatchInvalidExpiry() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage(
			'Invalid value "invalid expiry" for expiry parameter "expiry".'
		);
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Talk:Test page',
			'expiry' => 'invalid expiry',
			'formatversion' => 2
		] );
	}

	public function testWatchExpiryInPast() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage(
			'Value "20010101000000" for expiry parameter "expiry" is in the past.'
		);
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Talk:Test page',
			'expiry' => '20010101000000',
			'formatversion' => 2
		] );
	}

	public function testWatchEdit() {
		$tokens = $this->getTokens();

		$data = $this->doApiRequest( [
			'action' => 'edit',
			'title' => 'Help:UTPage', // Help namespace is hopefully wikitext
			'text' => 'new text',
			'token' => $tokens['edittoken'],
			'watchlist' => 'watch' ] );
		$this->assertArrayHasKey( 'edit', $data[0] );
		$this->assertArrayHasKey( 'result', $data[0]['edit'] );
		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		return $data;
	}

	/**
	 * @depends testWatchEdit
	 */
	public function testWatchClear() {
		$tokens = $this->getTokens();

		$data = $this->doApiRequest( [
			'action' => 'query',
			'wllimit' => 'max',
			'list' => 'watchlist' ] );

		if ( isset( $data[0]['query']['watchlist'] ) ) {
			$wl = $data[0]['query']['watchlist'];

			foreach ( $wl as $page ) {
				$data = $this->doApiRequest( [
					'action' => 'watch',
					'title' => $page['title'],
					'unwatch' => true,
					'token' => $tokens['watchtoken'] ] );
			}
		}
		$data = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlist' ], $data );
		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'watchlist', $data[0]['query'] );
		foreach ( $data[0]['query']['watchlist'] as $index => $item ) {
			// Previous tests may insert an invalid title
			// like ":ApiEditPageTest testNonTextEdit", which
			// can't be cleared.
			if ( strpos( $item['title'], ':' ) === 0 ) {
				unset( $data[0]['query']['watchlist'][$index] );
			}
		}
		$this->assertSame( [], $data[0]['query']['watchlist'] );

		return $data;
	}

	public function testWatchProtect() {
		$tokens = $this->getTokens();

		$data = $this->doApiRequest( [
			'action' => 'protect',
			'token' => $tokens['protecttoken'],
			'title' => 'Help:UTPage',
			'protections' => 'edit=sysop',
			'watchlist' => 'unwatch' ] );

		$this->assertArrayHasKey( 'protect', $data[0] );
		$this->assertArrayHasKey( 'protections', $data[0]['protect'] );
		$this->assertCount( 1, $data[0]['protect']['protections'] );
		$this->assertArrayHasKey( 'edit', $data[0]['protect']['protections'][0] );
	}

	public function testGetRollbackToken() {
		$this->getTokens();

		if ( !Title::newFromText( 'Help:UTPage' )->exists() ) {
			$this->markTestSkipped( "The article [[Help:UTPage]] does not exist" ); // TODO: just create it?
		}

		$data = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => 'Help:UTPage',
			'rvtoken' => 'rollback' ] );

		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'pages', $data[0]['query'] );
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );

		if ( isset( $data[0]['query']['pages'][$key]['missing'] ) ) {
			$this->markTestSkipped( "Target page (Help:UTPage) doesn't exist" );
		}

		$this->assertArrayHasKey( 'pageid', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 'revisions', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 0, $data[0]['query']['pages'][$key]['revisions'] );
		$this->assertArrayHasKey( 'rollbacktoken', $data[0]['query']['pages'][$key]['revisions'][0] );

		return $data;
	}

	/**
	 * @group Broken
	 * Broken because there is currently no revision info in the $pageinfo
	 *
	 * @depends testGetRollbackToken
	 */
	public function testWatchRollback( $data ) {
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $data[0]['query']['pages'][$key];
		$revinfo = $pageinfo['revisions'][0];

		try {
			$data = $this->doApiRequest( [
				'action' => 'rollback',
				'title' => 'Help:UTPage',
				'user' => $revinfo['user'],
				'token' => $pageinfo['rollbacktoken'],
				'watchlist' => 'watch' ] );

			$this->assertArrayHasKey( 'rollback', $data[0] );
			$this->assertArrayHasKey( 'title', $data[0]['rollback'] );
		} catch ( ApiUsageException $ue ) {
			if ( self::apiExceptionHasCode( $ue, 'onlyauthor' ) ) {
				$this->markTestIncomplete( "Only one author to 'Help:UTPage', cannot test rollback" );
			} else {
				$this->fail( "Received error '" . $ue->getMessage() . "'" );
			}
		}
	}
}
