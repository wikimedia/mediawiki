<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\ChangeTags\ChangeTagsFormatter;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\ChangeTags\RestrictedTagTestTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @group API
 * @covers \MediaWiki\Api\ApiQueryTags
 */
class ApiQueryTagsTest extends ApiTestCase {

	use RestrictedTagTestTrait;
	use MockAuthorityTrait;

	/** @dataProvider provideQueryTagsForDisplayName */
	public function testQueryTagsForDisplayName( array $authorityRights, array $expectedTags ): void {
		$this->setRestrictedTags( [ 'mw-private-test' => 'patrol' ] );
		$realChangeTagsStore = $this->getServiceContainer()->getChangeTagsStore();

		// Mock the tags defined on the wiki to avoid needing to query the DB
		$mockChangeTagsStore = $this->createMock( ChangeTagsStore::class );
		$mockChangeTagsStore->method( 'listSoftwareDefinedTags' )
			->willReturn( [ 'mw-tag-test', 'mw-tag-test-2', 'mw-private-test' ] );
		$mockChangeTagsStore->method( 'listExplicitlyDefinedTags' )
			->willReturn( [ 'mw-tag-test-3' ] );
		$mockChangeTagsStore->method( 'listSoftwareActivatedTags' )
			->willReturn( [] );
		$mockChangeTagsStore->method( 'tagUsageStatistics' )
			->willReturn( [] );
		$mockChangeTagsStore->method( 'filterViewableTags' )
			->willReturnCallback( $realChangeTagsStore->filterViewableTags( ... ) );
		$this->setService( 'ChangeTagsStore', $mockChangeTagsStore );

		// Mocking is done so we don't need to create fake messages for the descriptions (which would
		// need the database)
		$mockChangeTagsFormatter = $this->createMock( ChangeTagsFormatter::class );
		$mockChangeTagsFormatter->method( 'getTagDescription' )
			->willReturnCallback( static fn ( $tag ) => "$tag description" );
		$this->setService( 'ChangeTagsFormatter', $mockChangeTagsFormatter );

		[ $data ] = $this->doApiRequest(
			[
				'action' => 'query',
				'list' => 'tags',
				'tgprop' => 'displayname',
				'tglimit' => 7,
			],
			null,
			false,
			$this->mockRegisteredAuthorityWithPermissions( $authorityRights )
		);

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'tags', $data['query'] );
		$this->assertArrayEquals(
			array_map( static fn ( $tag ) => [
				'name' => $tag,
				'displayname' => "$tag description",
			], $expectedTags ),
			$data['query']['tags'],
			false,
			true
		);
	}

	public static function provideQueryTagsForDisplayName(): array {
		return [
			'User can see the private tag' => [
				'authorityRights' => [ 'patrol' ],
				'expectedTags' => [ 'mw-private-test', 'mw-tag-test', 'mw-tag-test-2', 'mw-tag-test-3' ],
			],
			'User cannot see the private tag' => [
				'authorityRights' => [],
				'expectedTags' => [ 'mw-tag-test', 'mw-tag-test-2', 'mw-tag-test-3' ],
			],
		];
	}
}
