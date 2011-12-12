<?php

/**
 * @group Database
 */
class ApiPurgeTest extends ApiTestCase {

	function setUp() {
		parent::setUp();
		$this->doLogin();
	}

	function testPurgeMainPage() {
		$this->markTestIncomplete( "Broken" );
		if ( !Title::newFromText( 'UTPage' )->exists() ) {
			$this->markTestIncomplete( "The article [[UTPage]] does not exist" );
		}

		$somePage = mt_rand();

		$data = $this->doApiRequest( array(
			'action' => 'purge',
			'titles' => 'UTPage|' . $somePage . '|%5D' ) );

		$this->assertArrayHasKey( 'purge', $data[0] );
		$this->assertEquals( 3, count( $data[0]['purge'] ) );

		$pages = array( 'UTPage' => 'purged', $somePage => 'missing', '%5D' => 'invalid' );
		foreach( $data[0]['purge'] as $v ) {
			$this->assertArrayHasKey( $pages[$v['title']], $v );
		}
	}

}
