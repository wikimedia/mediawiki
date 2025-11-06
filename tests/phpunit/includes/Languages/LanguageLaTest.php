<?php
declare( strict_types=1 );
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 * @covers LanguageLa
 */
class LanguageLaTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideConvertGrammar
	 */
	public function testConvertGrammar( string $word, string $case, string $expected ): void {
		$this->assertSame( $expected, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function provideConvertGrammar(): iterable {
		$wordCaseMappings = [
			'translatio' => [
				'genitive' => 'translationis',
				'accusative' => 'translationem',
				'ablative' => 'translatione',
			],
			'ursus' => [
				'genitive' => 'ursi',
				'accusative' => 'ursum',
				'ablative' => 'urso',
			],
			'gens' => [
				'genitive' => 'gentis',
				'accusative' => 'gentem',
				'ablative' => 'gente',
			],
			'bellum' => [
				'genitive' => 'belli',
				'accusative' => 'bellum',
				'ablative' => 'bello',
			],
			'communia' => [
				'genitive' => 'communium',
				'accusative' => 'communia',
				'ablative' => 'communibus',
			],
			'libri' => [
				'genitive' => 'librorum',
				'accusative' => 'libros',
				'ablative' => 'libris',
			],
			'dies' => [
				'genitive' => 'diei',
				'accusative' => 'diem',
				'ablative' => 'die',
			],
			'declinatio' => [
				'genitive' => 'declinationis',
				'accusative' => 'declinationem',
				'ablative' => 'declinatione',
			],
			'vanitas' => [
				'genitive' => 'vanitatis',
				'accusative' => 'vanitatem',
				'ablative' => 'vanitate',
			],
		];

		foreach ( $wordCaseMappings as $word => $caseMappings ) {
			foreach ( $caseMappings as $case => $expected ) {
				yield "$word $case" => [ (string)$word, $case, $expected ];
			}
		}
	}
}
