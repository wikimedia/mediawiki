<?php
declare( strict_types=1 );
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 * @covers LanguageFi
 */
class LanguageFiTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideConvertGrammar
	 */
	public function testConvertGrammar( string $word, string $case, string $expected ): void {
		$this->assertSame( $expected, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function provideConvertGrammar(): iterable {
		$wordCaseMappings = [
			'talo' => [
				'genitive' => 'talon',
				'elative' => 'talosta',
				'partitive' => 'taloa',
				'illative' => 'taloon',
				'inessive' => 'talossa',
			],
			'pastöroitu' => [
				'partitive' => 'pastöroitua',
			],
			'Wikipedia' => [
				'elative' => 'Wikipediasta',
				'partitive' => 'Wikipediaa',
			],
		];

		foreach ( $wordCaseMappings as $word => $caseMappings ) {
			foreach ( $caseMappings as $case => $expected ) {
				yield "$word $case" => [ (string)$word, $case, $expected ];
			}
		}
	}
}
