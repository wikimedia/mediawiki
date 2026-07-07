<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\ChangeTags\ChangeTagsFormatter;
use MediaWiki\ChangeTags\ChangeTagsStore;

/**
 * @group API
 * @covers \MediaWiki\Api\ApiQueryTags
 */
class ApiQueryTagsTest extends ApiTestCase {

	public function testQueryTagsForDisplayName(): void {
		// Mock the tags defined on the wiki to avoid needing to query the DB
		$mockChangeTagsStore = $this->createMock( ChangeTagsStore::class );
		$mockChangeTagsStore->method( 'listSoftwareDefinedTags' )
			->willReturn( [ 'mw-tag-test', 'mw-tag-test-2' ] );
		$mockChangeTagsStore->method( 'listExplicitlyDefinedTags' )
			->willReturn( [ 'mw-tag-test-3' ] );
		$mockChangeTagsStore->method( 'listSoftwareActivatedTags' )
			->willReturn( [] );
		$mockChangeTagsStore->method( 'tagUsageStatistics' )
			->willReturn( [] );
		$this->setService( 'ChangeTagsStore', $mockChangeTagsStore );

		// Mocking is done so we don't need to create fake messages for the descriptions (which would
		// need the database)
		$mockChangeTagsFormatter = $this->createMock( ChangeTagsFormatter::class );
		$mockChangeTagsFormatter->method( 'getTagDescription' )
			->willReturnCallback( static fn ( $tag ) => "$tag description" );
		$this->setService( 'ChangeTagsFormatter', $mockChangeTagsFormatter );

		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'tags',
			'tgprop' => 'displayname',
			'tglimit' => 7,
		] );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'tags', $data['query'] );
		$this->assertArrayEquals(
			[
				[
					'name' => 'mw-tag-test',
					'displayname' => 'mw-tag-test description',
				],
				[
					'name' => 'mw-tag-test-2',
					'displayname' => 'mw-tag-test-2 description',
				],
				[
					'name' => 'mw-tag-test-3',
					'displayname' => 'mw-tag-test-3 description',
				],
			],
			$data['query']['tags'],
			false,
			true
		);
	}
}
