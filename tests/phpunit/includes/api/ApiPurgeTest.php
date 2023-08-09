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
		$existingPageTitle = 'TestPurgePageExists';
		$this->getExistingTestPage( $existingPageTitle );
		$nonexistingPageTitle = 'TestPurgePageDoesNotExists';
		$this->getNonexistingTestPage( $nonexistingPageTitle );

		[ $data ] = $this->doApiRequest( [
			'action' => 'purge',
			'titles' => "$existingPageTitle|$nonexistingPageTitle|%5D"
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
				$existingPageTitle => [ 'purged' => true, 'ns' => NS_MAIN ],
				$nonexistingPageTitle => [ 'missing' => true, 'ns' => NS_MAIN ],
				'%5D' => [ 'invalid' => true ],
			],
			$resultByTitle,
			'Result'
		);
	}
}
