<?php

use MediaWiki\Skin\SkinComponentTableOfContents;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Skin\SkinComponentTableOfContents
 *
 * @group Output
 */
class SkinComponentTableOfContentsTest extends MediaWikiUnitTestCase {

	public function provideGetSectionsData(): array {
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
				// sections data
				[],
				[]
			],
			[
				[
					$SECTION_1,
					$SECTION_2
				],
				[
					$SECTION_1 + [
						'array-sections' => [],
						],
					$SECTION_2 + [
						'array-sections' => [],
						]
				]
			],
			[
				[
					$SECTION_1,
					$SECTION_1_1,
					$SECTION_2,
				],
				[
					$SECTION_1 + [
						'array-sections' => [
							$SECTION_1_1 + [
								'array-sections' => [],
							]
						]
					],
					$SECTION_2 + [
						'array-sections' => [],
					]
				]
			],
			[
				[
					$SECTION_1,
					$SECTION_1_1,
					$SECTION_1_2,
					$SECTION_1_2_1,
					$SECTION_1_3,
					$SECTION_2,
				],
				[
					$SECTION_1 + [
						'array-sections' => [
							$SECTION_1_1 + [
								'array-sections' => [],
							],
							$SECTION_1_2 + [
								'array-sections' => [
									$SECTION_1_2_1 + [
										'array-sections' => [],
									]
								]
							],
							$SECTION_1_3 + [
								'array-sections' => [],
							]
						]
					],
					$SECTION_2 + [
						'array-sections' => [],
					]
				]
			]
		];
	}

	/**
	 * @covers \MediaWiki\Skin\SkinComponentTableOfContents::getSectionsData
	 * @dataProvider provideGetSectionsData
	 *
	 * @param array $sectionsData
	 * @param array $expected
	 */
	public function testGetSectionsData( $sectionsData, $expected ) {
		$mockOutput = $this->createMock( OutputPage::class );
		$mockOutput->method( 'getSections' )->willReturn( $sectionsData );
		$skinComponent = new SkinComponentTableOfContents( $mockOutput );

		$data = TestingAccessWrapper::newFromObject( $skinComponent )->getSectionsData();
		$this->assertEquals( $expected, $data );
	}

	public function provideGetTOCData() {
		return [
			[
				false,
				[],
				'Data not provided when TOC is disabled'
			],
			[
				true,
				[
					'array-sections' => []
				],
				'Data not provided when TOC is enabled'
			],
		];
	}

	/**
	 * @covers \MediaWiki\Skin\SkinComponentTableOfContents::getTOCData
	 * @dataProvider provideGetTOCData
	 */
	public function testGetTOCData( $isTOCEnabled, $expected ) {
		$mockOutput = $this->createMock( OutputPage::class );
		$mockOutput->method( 'isTOCEnabled' )->willReturn( $isTOCEnabled );
		$skinComponent = new SkinComponentTableOfContents( $mockOutput );

		$data = TestingAccessWrapper::newFromObject( $skinComponent )->getTOCData();
		$this->assertEquals( $expected, $data );
	}

}
