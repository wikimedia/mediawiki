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
				'zh-hk' => 'chinese (hong kong)',
				'zh-mo' => 'chinese (macau)',
				'zh-my' => 'chinese (malaysia)',
				'zh-sg' => 'chinese (singapore)',
				'zh-tw' => 'chinese (taiwan)',
				'zh-hant' => 'chinese durii',
				'cdo' => 'chinese min dong',
				'zh-min-nan' => 'chinese min nan',
				'zh-hans' => 'chinese salphifame',
				'hak' => 'chinese — hakka chinese',
				'gan' => 'chinese — isi-gan chinese',
				'nan' => 'chinese — isi-min nan chinese',
				'wuu' => 'chinese — isi-wu chinese',
				'hsn' => 'chinese — isi-xiang chinese',
				'zh-classical' => 'chinese — literary chinese',
				'lzh' => 'chinesesch — klassescht chinesesch',
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
				'mos' => 'mosi',
				'mui' => class_exists( \MediaWiki\Extension\CLDR\LanguageNames::class ) ? 'musi' : 'musi palembang',
			]
			],
			[ 'palembang', [
				'mui' => 'palembang',
			]
			],
			[ 'punja', [
				// Presence of CLDR extension affects the results
				'pa' => class_exists( \MediaWiki\Extension\CLDR\LanguageNames::class ) ? 'punjabi' : 'punjaabi sennii',
				'pa-guru' => 'punjabi (gurmukhi-skrift)',
				'pnb' => 'punjabi western'
			]
			],
			[ 'qartuli', [
				'ka' => 'qartuli',
			]
			],
			[ 'tonga', [
				'to' => 'tonga',
				'toi' => 'tonga (botatwe)',
				'tog' => 'tonga (niasa)',
				'ts' => 'tsonga',
				'nr' => 'tonga — enetepēra ki te tonga',
				'hax' => 'tonga — haira ki te tonga',
				'st' => 'tonga — hōto ki te tonga',
				'es-419' => 'tonga — pāniora amerikana ki te tonga',
				'slh' => 'tonga — ratūti ki te tonga',
				'tce' => 'tonga — tatōne ki te tonga',
				'alt' => 'tonga — ātai ki te tonga',
				'crj' => 'tonga-mā-rāwhiti — kirī tonga-mā-rāwhiti',
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
				'fro' => 'الفرنسية القديمة',
				'frc' => 'الفرنسية الكاجونية',
				'crs' => 'الفرنسية الكريولية السيشيلية',
				'frm' => 'الفرنسية الوسطى',
			]
			],
			[ 'മല', [
				'mg' => 'മലഗാസി',
				'ml' => 'മലയാളം',
				'pqm' => 'മലിസീറ്റ്-പസാമക്വുഡി',
				'ms' => 'മലെയ്',
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
