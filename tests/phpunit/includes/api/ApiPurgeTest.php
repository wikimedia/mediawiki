<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiPurge
 */
class ApiPurgeTest extends ApiTestCase {

	/**
	 * @group Broken
	 */
	public function testPurgeMainPage() {
		if ( !Title::newFromText( 'UTPage' )->exists() ) {
			$this->markTestIncomplete( "The article [[UTPage]] does not exist" );
		}

		$somePage = mt_rand();

		$data = $this->doApiRequest( [
			'action' => 'purge',
			'titles' => 'UTPage|' . $somePage . '|%5D' ] );

		$this->assertArrayHasKey( 'purge', $data[0],
			"Must receive a 'purge' result from API" );

		$this->assertCount( 3, $data[0]['purge'],
			"Purge request for three articles should give back three results received: "
				. var_export( $data[0]['purge'], true ) );

		$pages = [ 'UTPage' => 'purged', $somePage => 'missing', '%5D' => 'invalid' ];
		foreach ( $data[0]['purge'] as $v ) {
			$this->assertArrayHasKey( $pages[$v['title']], $v );
		}
	}
}
