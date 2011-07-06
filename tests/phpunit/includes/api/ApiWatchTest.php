<?php

/**
 * @group Database
 * @todo This test suite is severly broken and need a full review 
 */
class ApiWatchTest extends ApiTestCase {

	function setUp() {
		parent::setUp();
		$this->doLogin();
	}
	
	function getTokens() {
		return $this->getTokenList( self::$users['sysop'] );
	}

	/**
	 * @group Broken
	 */
	function testWatchEdit() {
		
		$data = $this->getTokens();
		
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $data[0]['query']['pages'][$key];

		$data = $this->doApiRequest( array(
			'action' => 'edit',
			'title' => 'UTPage',
			'text' => 'new text',
			'token' => $pageinfo['edittoken'],
			'watchlist' => 'watch' ), $data );
		$this->assertArrayHasKey( 'edit', $data[0] );
		$this->assertArrayHasKey( 'result', $data[0]['edit'] );
		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		return $data;
	}

	/**
	 * @depends testWatchEdit
	 */
	function testWatchClear() {
	
		$data = $this->doApiRequest( array(
			'action' => 'query',
			'list' => 'watchlist' ), $data );

		if ( isset( $data[0]['query']['watchlist'] ) ) {
			$wl = $data[0]['query']['watchlist'];

			foreach ( $wl as $page ) {
				$data = $this->doApiRequest( array(
					'action' => 'watch',
					'title' => $page['title'],
					'unwatch' => true ), $data );
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
	 * @group Broken
	 */	 
	function testWatchProtect() {
		
		$data = $this->getTokens();
		
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $data[0]['query']['pages'][$key];

		$data = $this->doApiRequest( array(
			'action' => 'protect',
			'token' => $pageinfo['protecttoken'],
			'title' => 'UTPage',
			'protections' => 'edit=sysop',
			'watchlist' => 'unwatch' ), $data );

		$this->assertArrayHasKey( 'protect', $data[0] );
		$this->assertArrayHasKey( 'protections', $data[0]['protect'] );
		$this->assertEquals( 1, count( $data[0]['protect']['protections'] ) );
		$this->assertArrayHasKey( 'edit', $data[0]['protect']['protections'][0] );
	}

	
	function testGetRollbackToken() {
		
		$data = $this->getTokens();
		
		if ( !Title::newFromText( 'UTPage' )->exists() ) {
			$this->markTestIncomplete( "The article [[UTPage]] does not exist" );
		}

		$data = $this->doApiRequest( array(
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => 'UTPage',
			'rvtoken' => 'rollback' ), $data );

		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'pages', $data[0]['query'] );
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );

		if ( isset( $data[0]['query']['pages'][$key]['missing'] ) ) {
			$this->markTestIncomplete( "Target page (UTPage) doesn't exist" );
		}

		$this->assertArrayHasKey( 'pageid', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 'revisions', $data[0]['query']['pages'][$key] );
		$this->assertArrayHasKey( 0, $data[0]['query']['pages'][$key]['revisions'] );
		$this->assertArrayHasKey( 'rollbacktoken', $data[0]['query']['pages'][$key]['revisions'][0] );

		return $data;
	}

	/**
	 * @depends testGetRollbackToken
	 * @group Broken
	 */
	function testWatchRollback( $data ) {
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $data[0]['query']['pages'][$key]['revisions'][0];

		try {
			$data = $this->doApiRequest( array(
				'action' => 'rollback',
				'title' => 'UTPage',
				'user' => $pageinfo['user'],
				'token' => $pageinfo['rollbacktoken'],
				'watchlist' => 'watch' ), $data );
		} catch( UsageException $ue ) {
			if( $ue->getCodeString() == 'onlyauthor' ) {
				$this->markTestIncomplete( "Only one author to 'UTPage', cannot test rollback" );
			} else {
				$this->fail( "Received error '" . $ue->getCodeString() . "'" );
			}
		}

		$this->assertArrayHasKey( 'rollback', $data[0] );
		$this->assertArrayHasKey( 'title', $data[0]['rollback'] );
	}

	/**
	 * @group Broken
	 */
	function testWatchDelete() {
		
		$data = $this->getTokens();
		
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $data[0]['query']['pages'][$key];

		$data = $this->doApiRequest( array(
			'action' => 'delete',
			'token' => $pageinfo['deletetoken'],
			'title' => 'UTPage' ), $data );
		$this->assertArrayHasKey( 'delete', $data[0] );
		$this->assertArrayHasKey( 'title', $data[0]['delete'] );

		$data = $this->doApiRequest( array(
			'action' => 'query',
			'list' => 'watchlist' ), $data );

		$this->markTestIncomplete( 'This test needs to verify the deleted article was added to the users watchlist' );
	}
}
