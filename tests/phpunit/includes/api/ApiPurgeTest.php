<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiPurge
 */
class ApiPurgeTest extends ApiTestCase {

	public function testPurgePage() {
		// Ensure 'UTPage' existence.
		$this->getExistingTestPage( 'UTPage' );

		$somePage = mt_rand();

		$data = $this->doApiRequest( [
			'action' => 'purge',
			'titles' => 'UTPage|' . $somePage . '|%5D'
		] );

		$purgeData = $data[0]['purge'];

		$this->assertArrayHasKey( 'purge', $data[0],
			"Must receive a 'purge' result from API" );

		$this->assertCount( 3, $purgeData,
			'Purge request for three articles should give back three '
			. 'results; received: ' . var_export( $purgeData, true ) );

		$pages = [ 'UTPage' => 'purged', $somePage => 'missing', '%5D' => 'invalid' ];
		foreach ( $purgeData as $v ) {
			$this->assertArrayHasKey( $pages[$v['title']], $v );
		}
	}
}
