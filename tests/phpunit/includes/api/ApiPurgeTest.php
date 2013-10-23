<?php

/**
 * @group API
 * @group Database
 * @group medium
 */
class ApiPurgeTest extends ApiTestCase {

	protected function setUp() {
		parent::setUp();
		$this->doLogin();
	}

	/**
	 * @group Broken
	 */
	public function testPurgeMainPage() {
		if ( !Title::newFromText( 'UTPage' )->exists() ) {
			$this->markTestIncomplete( "The article [[UTPage]] does not exist" );
		}

		$somePage = mt_rand();

		$data = $this->doApiRequest( array(
			'action' => 'purge',
			'titles' => 'UTPage|' . $somePage . '|%5D' ) );

		$this->assertArrayHasKey( 'purge', $data[0],
			"Must receive a 'purge' result from API" );

		$this->assertEquals( 3, count( $data[0]['purge'] ),
			"Purge request for three articles should give back three results received: " . var_export( $data[0]['purge'], true ) );

		$pages = array( 'UTPage' => 'purged', $somePage => 'missing', '%5D' => 'invalid' );
		foreach ( $data[0]['purge'] as $v ) {
			$this->assertArrayHasKey( $pages[$v['title']], $v );
		}
	}
}
