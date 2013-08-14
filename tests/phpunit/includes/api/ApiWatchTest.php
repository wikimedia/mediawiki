<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @todo This test suite is severly broken and need a full review
 */
class ApiWatchTest extends ApiTestCase {
	protected function setUp() {
		parent::setUp();
		$this->doLogin();
	}

	function getTokens() {
		return $this->getTokenList( self::$users['sysop'] );
	}

	/**
	 */
	public function testWatchEdit() {
		$tokens = $this->getTokens();

		$data = $this->doApiRequest( array(
			'action' => 'edit',
			'title' => 'Help:UTPage', // Help namespace is hopefully wikitext
			'text' => 'new text',
			'token' => $tokens['edittoken'],
			'watchlist' => 'watch' ) );
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

		$data = $this->doApiRequest( array(
			'action' => 'query',
			'list' => 'watchlist' ) );

		if ( isset( $data[0]['query']['watchlist'] ) ) {
			$wl = $data[0]['query']['watchlist'];

			foreach ( $wl as $page ) {
				$data = $this->doApiRequest( array(
					'action' => 'watch',
					'title' => $page['title'],
					'unwatch' => true,
					'token' => $tokens['watchtoken'] ) );
			}
		}
		$data = $this->doApiRequest( array(
			'action' => 'query',
			'list' => 'watchlist' ), $data );
		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'watchlist', $data[0]['query'] );
		$this->assertEquals( 0, count( $data[0]['query']['watchlist'] ) );

		return $data;
	}

	/**
	 */
	public function testWatchProtect() {
		$tokens = $this->getTokens();

		$data = $this->doApiRequest( array(
			'action' => 'protect',
			'token' => $tokens['protecttoken'],
			'title' => 'Help:UTPage',
			'protections' => 'edit=sysop',
			'watchlist' => 'unwatch' ) );

		$this->assertArrayHasKey( 'protect', $data[0] );
		$this->assertArrayHasKey( 'protections', $data[0]['protect'] );
		$this->assertEquals( 1, count( $data[0]['protect']['protections'] ) );
		$this->assertArrayHasKey( 'edit', $data[0]['protect']['protections'][0] );
	}

	/**
	 */
	public function testGetRollbackToken() {
		$this->getTokens();

		if ( !Title::newFromText( 'Help:UTPage' )->exists() ) {
			$this->markTestSkipped( "The article [[Help:UTPage]] does not exist" ); //TODO: just create it?
		}

		$data = $this->doApiRequest( array(
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => 'Help:UTPage',
			'rvtoken' => 'rollback' ) );

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
			$data = $this->doApiRequest( array(
				'action' => 'rollback',
				'title' => 'Help:UTPage',
				'user' => $revinfo['user'],
				'token' => $pageinfo['rollbacktoken'],
				'watchlist' => 'watch' ) );

			$this->assertArrayHasKey( 'rollback', $data[0] );
			$this->assertArrayHasKey( 'title', $data[0]['rollback'] );
		} catch ( UsageException $ue ) {
			if ( $ue->getCodeString() == 'onlyauthor' ) {
				$this->markTestIncomplete( "Only one author to 'Help:UTPage', cannot test rollback" );
			} else {
				$this->fail( "Received error '" . $ue->getCodeString() . "'" );
			}
		}
	}
}
