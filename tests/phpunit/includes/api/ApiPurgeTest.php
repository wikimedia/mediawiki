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
		$this->getExistingTestPage( 'UTPage' );
		$this->getNonexistingTestPage( 'UTPage-NotFound' );

		list( $data ) = $this->doApiRequest( [
			'action' => 'purge',
			'titles' => 'UTPage|UTPage-NotFound|%5D'
		] );

		$resultByTitle = [];
		foreach ( $data['purge'] as $entry ) {
			$key = $entry['title'];
			// Ignore localised or redundant field
			unset( $entry['invalidreason'] );
			unset( $entry['title'] );
			$resultByTitle[$key] = $entry;
		}

		$this->assertEquals(
			[
				'UTPage' => [ 'purged' => true, 'ns' => 0 ],
				'UTPage-NotFound' => [ 'missing' => true, 'ns' => 0 ],
				'%5D' => [ 'invalid' => true ],
			],
			$resultByTitle,
			'Result'
		);
	}
}
