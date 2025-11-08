<?php

use MediaWiki\Search\SearchResultThumbnailProvider;
use MediaWiki\Tests\Rest\Handler\MediaTestTrait;
use MediaWiki\Title\Title;

class SearchResultThumbnailProviderTest extends MediaWikiIntegrationTestCase {
	use MediaTestTrait;

	/**
	 * List of titles to create.
	 */
	protected const TITLES = [
		'file' => [
			'id' => 1,
			'text' => 'File_1.jpg',
			'namespace' => NS_FILE,
		],
		'article_with_thumb' => [
			'id' => 2,
			'text' => 'Title_2',
			'namespace' => NS_MAIN,
		],
		'article_without_thumb' => [
			'id' => 3,
			'text' => 'Title_3',
			'namespace' => NS_MAIN,
		],
	];

	/**
	 * Map of page id to thumbnail page id, both of which are expected to be present in self::TITLES.
	 * This map will be used to build a mock response for the SearchResultProvideThumbnail hook.
	 */
	protected const HOOK_PROVIDED_THUMBNAILS_BY_ID = [
		self::TITLES['article_with_thumb']['id'] => self::TITLES['file']['id'],
	];

	private SearchResultThumbnailProvider $thumbnailProvider;
	/** @var Title[] */
	private $titles = [];

	public static function articleThumbnailsProvider(): array {
		return [
			// assert that NS_FILE pages provide their own file
			[
				// page ids
				[ self::TITLES['file']['id'] ],
				// thumbnail ids
				[ self::TITLES['file']['id'] ],
				// size
				500
			],
			// assert that hook provides thumbnails for non-file pages
			[
				[ self::TITLES['article_with_thumb']['id'] ],
				[ self::TITLES['file']['id'] ],
				500
			],
			// assert thumbnail is missing when not NS_FILE & hook doesn't provide
			[
				[ self::TITLES['article_without_thumb']['id'] ],
				[],
				500
			],
			// assert that size is optional and defaults to something functional
			[
				[ self::TITLES['file']['id'] ],
				[ self::TITLES['file']['id'] ],
				null
			]
		];
	}

	public function setUp(): void {
		parent::setUp();

		// build mock titles based on descriptions in self::TITLES
		$this->titles = [];
		foreach ( self::TITLES as $data ) {
			$this->titles[$data['id']] = $this->makeMockTitle(
				$data['text'],
				[ 'id' => $data['id'], 'namespace' => $data['namespace'] ]
			);
		}

		// compile a mock repo with all NS_FILE pages known in self::TITLES
		$thumbnails = array_map(
			static fn ( Title $title ) => $title->getDBkey(),
			array_filter(
				$this->titles,
				static fn ( Title $title ) => $title->inNamespace( NS_FILE )
			)
		);
		$mockRepoGroup = $this->makeMockRepoGroup( $thumbnails );

		// create a hook that provides all thumbnails defined in HOOK_PROVIDED_THUMBNAILS_BY_ID
		$hookContainer = $this->createHookContainer( [
			'SearchResultProvideThumbnail' => function ( $pageIdentities, &$results, $size ) use ( $mockRepoGroup ) {
				foreach ( self::HOOK_PROVIDED_THUMBNAILS_BY_ID as $pageId => $thumbnailPageId ) {
					if ( !isset( $pageIdentities[$pageId] ) ) {
						// skip this thumbnail, it was not requested
						continue;
					}
					$articleTitle = $this->titles[$pageId];
					if ( !$articleTitle ) {
						throw new InvalidArgumentException(
							'self::HOOK_PROVIDED_THUMBNAILS_BY_ID key references a page missing from in self::TITLES'
						);
					}
					$thumbnailTitle = $this->titles[$thumbnailPageId];
					if ( !$thumbnailTitle ) {
						throw new InvalidArgumentException(
							'self::HOOK_PROVIDED_THUMBNAILS_BY_ID value references a page missing from in self::TITLES'
						);
					}
					$results[$pageId] = $this->thumbnailProvider->buildSearchResultThumbnailFromFile(
						$mockRepoGroup->findFile( $thumbnailTitle ),
						$size
					);
				}
			},
		] );
		$this->thumbnailProvider = new SearchResultThumbnailProvider( $mockRepoGroup, $hookContainer );
	}

	/**
	 * @dataProvider articleThumbnailsProvider
	 * @covers \MediaWiki\Search\SearchResultThumbnailProvider::getThumbnails
	 * @covers \MediaWiki\Search\SearchResultThumbnailProvider::buildSearchResultThumbnailFromFile
	 * @param int[] $pageIds
	 * @param int[] $thumbnailIds
	 * @param int|null $size
	 */
	public function testGetThumbnails( array $pageIds, array $thumbnailIds, ?int $size = null ) {
		$pageIdentities = array_intersect_key( $this->titles, array_fill_keys( $pageIds, null ) );

		$thumbnails = $this->thumbnailProvider->getThumbnails( $pageIdentities, $size );

		// confirm that titles for which there is no thumbnail are missing
		$missingThumbnails = array_diff_key( $pageIdentities, $thumbnails );
		foreach ( $missingThumbnails as $pageId => $pageIdentity ) {
			$this->assertArrayNotHasKey( $pageId, $thumbnails );
		}

		foreach ( $thumbnails as $pageId => $thumbnail ) {
			// confirm thumbnail matches what we expect
			$expectedName = $this->titles[$pageId]->inNamespace( NS_FILE )
				? $this->titles[$pageId]->getDBkey()
				: $this->titles[self::HOOK_PROVIDED_THUMBNAILS_BY_ID[$pageId]]->getDBkey();
			$this->assertEquals( $expectedName, $thumbnail->getName() );

			// confirm thumbnail dimensions
			$expectedSize = $size ?? SearchResultThumbnailProvider::THUMBNAIL_SIZE;
			$this->assertLessThanOrEqual( $expectedSize, $thumbnail->getWidth() );
		}
	}
}
