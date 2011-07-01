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
		
		if ( !Title::newFromText( 'UTPage' )->exists() ) {
			$this->markTestIncomplete( "The article [[UTPage]] does not exist" );
		}
		
		$somePage = mt_rand();

		$data = $this->doApiRequest( array(
			'action' => 'purge',
			'titles' => 'UTPage|' . $somePage . '|%5D' ) );
	
		$this->assertArrayHasKey( 'purge', $data[0] );
		
		$this->assertArrayHasKey( 0, $data[0]['purge'] );
		$this->assertArrayHasKey( 'purged', $data[0]['purge'][0] );
		$this->assertEquals( 'UTPage', $data[0]['purge'][0]['title'] );
		
		$this->assertArrayHasKey( 1, $data[0]['purge'] );
		$this->assertArrayHasKey( 'missing', $data[0]['purge'][1] );
		$this->assertEquals( $somePage, $data[0]['purge'][1]['title'] );
		
		$this->assertArrayHasKey( 2, $data[0]['purge'] );
		$this->assertArrayHasKey( 'invalid', $data[0]['purge'][2] );
		$this->assertEquals( '%5D', $data[0]['purge'][2]['title'] );
		
	}

}
