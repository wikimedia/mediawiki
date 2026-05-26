<?php
declare( strict_types = 1 );

use MediaWiki\Language\LanguageNameSearch;

/**
 * @group Language
 * @covers \MediaWiki\Language\LanguageNameSearch
 */
class LanguageNameSearchTest extends MediaWikiIntegrationTestCase {
	/**
	 * @dataProvider searchDataProvider
	 */
	public function testSearch( string $searchKey, array $expected ): void {
		$actual = LanguageNameSearch::search( $searchKey, 1, 'en' );
		// This is for better error messages
		$this->assertEquals( $expected, $actual );
		// This is for identical order
		$this->assertSame( $expected, $actual );
	}

	public function testSearchSorting(): void {
		// 'tonga' search returns multiple script groups and autonyms (e.g., to, toi, tog, ts, nr)
		$actual = LanguageNameSearch::search( 'tonga', 1, 'en' );
		$actualCodes = array_keys( $actual );

		// Expected order sorted by autonym and script group, with exact match 'to' prioritized at the top
		$expectedCodes = [
			'to', 'alt', 'toi', 'nr', 'st', 'ts', 'crj', 'hax', 'slh', 'tce', 'tog'
		];

		// Verify that the backend search natively returns sorted results
		$this->assertSame( $expectedCodes, $actualCodes );

		// 'hi' search exact match prioritized at the top
		$actualHi = LanguageNameSearch::search( 'hi', 1, 'en' );
		$actualHiCodes = array_keys( $actualHi );
		$this->assertSame( 'hi', $actualHiCodes[0] ?? null, 'Exact match "hi" should be prioritized at the top' );

		// 'ml' search exact match prioritized at the top and remaining sorted
		$actualMl = LanguageNameSearch::search( 'ml', 1, 'en' );
		$actualMlCodes = array_keys( $actualMl );
		$expectedMlCodes = [ 'ml', 'moe', 'crg', 'und' ];
		$this->assertSame( $expectedMlCodes, $actualMlCodes );
	}

	public static function searchDataProvider(): array {
		return [
			[ 'blargh', []
			],
			[ 'castellano', [
				'es' => 'castellano',
			]
			],
			[ 'chinese', [
				// Presence of CLDR extension affects the results
				'zh' => class_exists( \MediaWiki\Extension\CLDR\LanguageNames::class ) ? 'chinese' : 'chines',
				'zh-cn' => 'chinese (china)',
				'zh-sg' => 'chinese (singapore)',
				'zh-mo' => 'chinese (macau)',
				'zh-hans' => 'chinese salphifame',
				'zh-hant' => 'chinese durii',
				'zh-tw' => 'chinese (taiwan)',
				'zh-hk' => 'chinese (hong kong)',
				'zh-my' => 'chinese (malaysia)',
				'wuu' => 'chinese — isi-wu chinese',
				'hak' => 'chinese — hakka chinese',
				'zh-classical' => 'chinese — literary chinese',
				'lzh' => 'chinesesch — klassescht chinesesch',
				'hsn' => 'chinese — isi-xiang chinese',
				'gan' => 'chinese — isi-gan chinese',
				'zh-min-nan' => 'chinese min nan',
				'nan' => 'chinese — isi-min nan chinese',
				'cdo' => 'chinese min dong',
			]
			],
			[ 'finnisj', [
				'fi' => 'finnish'
			]
			],
			[ 'hayeren', [
				'hy' => 'hayeren',
			]
			],
			[ 'kartuli', [
				'ka' => 'kartuli',
			]
			],
			[ 'nihongo', [
				'ja' => 'nihongo',
			]
			],
			[ 'musi', [
				'mui' => class_exists( \MediaWiki\Extension\CLDR\LanguageNames::class ) ? 'musi' : 'musi palembang',
				'mos' => 'mosi',
			]
			],
			[ 'palembang', [
				'mui' => 'palembang',
			]
			],
			[ 'punja', [
				'pnb' => 'punjabi western',
				// Presence of CLDR extension affects the results
				'pa' => class_exists( \MediaWiki\Extension\CLDR\LanguageNames::class ) ? 'punjabi' : 'punjaabi sennii',
				'pa-guru' => 'punjabi (gurmukhi script)',
			]
			],
			[ 'qartuli', [
				'ka' => 'qartuli',
			]
			],
			[ 'tonga', [
				'to' => 'tonga',
				'alt' => 'tonga — ātai ki te tonga',
				'toi' => 'tonga (botatwe)',
				'nr' => 'tonga — enetepēra ki te tonga',
				'st' => 'tonga — hōto ki te tonga',
				'ts' => 'tsonga',
				'crj' => 'tonga-mā-rāwhiti — kirī tonga-mā-rāwhiti',
				'hax' => 'tonga — haira ki te tonga',
				'slh' => 'tonga — ratūti ki te tonga',
				'tce' => 'tonga — tatōne ki te tonga',
				'tog' => 'tonga (niasa)',
			]
			],
			[ 'valencia', [
				'ca' => 'valencia',
			]
			],
			[ 'Φινλαν', [
				'fi' => 'φινλανδικά',
			]
			],
			[ 'טגר', [
				'tig' => 'טגרה',
				'ti' => 'טגריניה',
			]
			],
			[ 'טיגר', [
				'tig' => 'טיגרה',
				'ti' => 'טיגריניה',
			]
			],
			[ 'תגר', [
				'tig' => 'תגרה',
				'ti' => 'תגריניה',
			]
			],
			[ 'תיגר', [
				'tig' => 'תיגרה',
				'ti' => 'תיגריניה',
			]
			],
			[ 'الفرنسية', [
				'fr' => 'الفرنسية',
				'frc' => 'الفرنسية الكاجونية',
				'crs' => 'الفرنسية الكريولية السيشيلية',
				'frm' => 'الفرنسية الوسطى',
				'fro' => 'الفرنسية القديمة',
			]
			],
			[ 'മല', [
				'ms' => 'മലെയ്',
				'mg' => 'മലഗാസി',
				'ml' => 'മലയാളം',
				'pqm' => 'മലിസീറ്റ്-പസാമക്വുഡി',
			]
			],
			[ 'മലയളം', [
				'ml' => 'മലയാളം',
			]
			],
			[ 'ഹിന്ദി', [
				'hi' => 'ഹിന്ദി',
			]
			],
			[ 'にほんご', [
				'ja' => 'にほんご',
			]
			],
		];
	}
}
