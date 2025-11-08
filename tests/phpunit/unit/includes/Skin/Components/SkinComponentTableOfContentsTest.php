<?php

use MediaWiki\Output\OutputPage;
use MediaWiki\Skin\SkinComponentTableOfContents;
use Wikimedia\Parsoid\Core\SectionMetadata;
use Wikimedia\Parsoid\Core\TOCData;

/**
 * @covers \MediaWiki\Skin\SkinComponentTableOfContents
 * @group Skin
 */
class SkinComponentTableOfContentsTest extends MediaWikiUnitTestCase {

	private static function addDefaults( array $sectionData ): array {
		return SectionMetadata::fromLegacy( $sectionData )->toLegacy();
	}

	public static function provideGetSectionsData(): array {
		// byteoffset and fromtitle are redacted from this test.
		$SECTION_1 = [
			'toclevel' => 1,
			'line' => 'Section 1',
			'anchor' => 'section_1',
		];
		$SECTION_1_1 = [
			'toclevel' => 2,
			'line' => 'Section 1.1',
			'anchor' => 'section_1_1',
		];
		$SECTION_1_2 = [
			'toclevel' => 2,
			'line' => 'Section 1.2',
			'anchor' => 'section_1_2',
		];
		$SECTION_1_2_1 = [
			'toclevel' => 3,
			'line' => 'Section 1.2.1',
			'anchor' => 'section_1_2_1',
		];
		$SECTION_1_3 = [
			'toclevel' => 2,
			'line' => 'Section 1.3',
			'anchor' => 'section_1_3',
		];
		$SECTION_2 = [
			'toclevel' => 1,
			'line' => 'Section 2',
			'anchor' => 'section_2',
		];

		return [
			[
				// isTocEnabled
				false,
				// sections data
				[],
				// expected
				[]
			],
			[
				// isTocEnabled
				true,
				// sections data
				[
					$SECTION_1,
					$SECTION_2
				],
				// expected
				[
					'number-section-count' => 2,
					'array-sections' => [
						self::addDefaults( $SECTION_1 ) + [
							'array-sections' => [],
							'is-top-level-section' => true,
							'is-parent-section' => false,
						],
						self::addDefaults( $SECTION_2 ) + [
							'array-sections' => [],
							'is-top-level-section' => true,
							'is-parent-section' => false,
						]
					]
				]
			],
			[
				// isTocEnabled
				true,
				// sections data
				[
					$SECTION_1,
					$SECTION_1_1,
					$SECTION_2,
				],
				// expected
				[
					'number-section-count' => 3,
					'array-sections' => [
						self::addDefaults( $SECTION_1 ) + [
							'array-sections' => [
								self::addDefaults( $SECTION_1_1 ) + [
									'array-sections' => [],
									'is-top-level-section' => false,
									'is-parent-section' => false,
								]
							],
							'is-top-level-section' => true,
							'is-parent-section' => true,
						],
						self::addDefaults( $SECTION_2 ) + [
							'array-sections' => [],
							'is-top-level-section' => true,
							'is-parent-section' => false,
						]
					]
				]
			],
			[
				// isTocEnabled
				true,
				// sections data
				[
					$SECTION_1,
					$SECTION_1_1,
					$SECTION_1_2,
					$SECTION_1_2_1,
					$SECTION_1_3,
					$SECTION_2,
				],
				// expected
				[
					'number-section-count' => 6,
					'array-sections' => [
						self::addDefaults( $SECTION_1 ) + [
							'array-sections' => [
								self::addDefaults( $SECTION_1_1 ) + [
									'array-sections' => [],
									'is-top-level-section' => false,
									'is-parent-section' => false,
								],
								self::addDefaults( $SECTION_1_2 ) + [
									'array-sections' => [
										self::addDefaults( $SECTION_1_2_1 ) + [
											'array-sections' => [],
											'is-top-level-section' => false,
											'is-parent-section' => false,
										],
									],
									'is-top-level-section' => false,
									'is-parent-section' => true,
								],
								self::addDefaults( $SECTION_1_3 ) + [
									'array-sections' => [],
									'is-top-level-section' => false,
									'is-parent-section' => false,
								]
							],
							'is-top-level-section' => true,
							'is-parent-section' => true,
						],
						self::addDefaults( $SECTION_2 ) + [
							'array-sections' => [],
							'is-top-level-section' => true,
							'is-parent-section' => false,
						]
					]
				]
			]
		];
	}

	/**
	 * @dataProvider provideGetSectionsData
	 * @param bool $isTocEnabled
	 * @param array $sectionsData
	 * @param array $expected
	 */
	public function testGetTemplateData( $isTocEnabled, $sectionsData, $expected ) {
		// Convert to first class objects
		$tocData = new TOCData;
		array_map(
			static function ( $s ) use ( $tocData ) {
				$tocData->addSection( SectionMetadata::fromLegacy( $s ) );
			},
			$sectionsData
		);
		$mockOutput = $this->createMock( OutputPage::class );
		$mockOutput->method( 'isTOCEnabled' )->willReturn( $isTocEnabled );
		$mockOutput->method( 'getTOCData' )->willReturn( $tocData );
		$skinComponent = new SkinComponentTableOfContents( $mockOutput );

		$data = $skinComponent->getTemplateData();

		$this->assertEquals( $expected, $data );
	}
}
