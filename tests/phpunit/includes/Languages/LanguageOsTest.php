<?php
declare( strict_types=1 );

namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 * @covers LanguageOs
 */
class LanguageOsTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideConvertGrammar
	 */
	public function testConvertGrammar( string $word, string $case, string $expected ): void {
		$this->assertSame( $expected, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function provideConvertGrammar(): iterable {
		$wordCaseMappings = [
			'бæстæ' => [
				'genitive' => 'бæсты',
				'allative' => 'бæстæм',
				'dative' => 'бæстæн',
				'ablative' => 'бæстæй',
				'inessive' => 'бæст',
				'superessive' => 'бæстыл',
			],

			'лæппу' => [
				'genitive' => 'лæппуйы',
				'allative' => 'лæппумæ',
				'dative' => 'лæппуйæн',
				'ablative' => 'лæппуйæ',
				'inessive' => 'лæппу',
				'superessive' => 'лæппуйыл',
			],

			'2011' => [
				'equative' => '2011-ау',
			],
		];

		foreach ( $wordCaseMappings as $word => $caseMappings ) {
			foreach ( $caseMappings as $case => $expected ) {
				yield "$word $case" => [ (string)$word, $case, $expected ];
			}
		}
	}
}
