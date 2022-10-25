<?php

use MediaWiki\Search\Entity\SearchResultThumbnail;

class SearchResultThumbnailTest extends MediaWikiIntegrationTestCase {
	public static function sizeProvider(): array {
		return [
			// null size
			[
				new SearchResultThumbnail(
					'image/jpeg',
					null,
					90,
					90,
					null,
					'https://media.example.com/static/Null_size.jpg',
					'Null_size.jpg'
				),
				null
			],
			// integer size
			[
				new SearchResultThumbnail(
					'image/jpeg',
					200,
					90,
					90,
					null,
					'https://media.example.com/static/Null_size.jpg',
					'Null_size.jpg'
				),
				200
			],
			// callback size
			[
				new SearchResultThumbnail(
					'image/jpeg',
					static function () {
						return 400;
					},
					90,
					90,
					null,
					'https://media.example.com/static/Null_size.jpg',
					'Null_size.jpg'
				),
				400
			],
		];
	}

	/**
	 * @dataProvider sizeProvider
	 * @covers \MediaWiki\Search\Entity\SearchResultThumbnail::getSize
	 * @param SearchResultThumbnail $thumbnail
	 * @param null|int $expect
	 */
	public function testGetSize( SearchResultThumbnail $thumbnail, ?int $expect ) {
		// confirm size matches
		$this->assertEquals( $expect, $thumbnail->getSize() );
		// confirm size continues to match in consecutive calls
		$this->assertEquals( $expect, $thumbnail->getSize() );
	}
}
