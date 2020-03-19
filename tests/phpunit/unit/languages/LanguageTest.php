<?php

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;

/**
 * @coversDefaultClass Language
 */
class LanguageTest extends MediaWikiUnitTestCase {
	/**
	 * @param array $options Valid keys:
	 *   'code'
	 *   'grammarTransformCache'
	 */
	private function getObj( array $options = [] ) {
		return new Language(
			$options['code'] ?? 'en',
			$this->createNoOpMock( LocalisationCache::class ),
			$this->createNoOpMock( LanguageNameUtils::class ),
			$this->createNoOpMock( LanguageFallback::class ),
			$this->createNoOpMock( LanguageConverterFactory::class ),
			$this->createHookContainer()

		);
	}

	/**
	 * @covers ::getGrammarTransformations
	 * @todo Test the exception case
	 */
	public function testGetGrammarTransformations() {
		global $IP;

		// XXX Inject a value here instead of reading the filesystem (T231159)
		$expected = FormatJson::decode(
			file_get_contents( "$IP/languages/data/grammarTransformations/he.json" ), true );

		$lang = $this->getObj( [ 'code' => 'he' ] );

		$this->assertSame( $expected, $lang->getGrammarTransformations() );
		$this->assertSame( $expected, $lang->getGrammarTransformations() );
	}

	/**
	 * @covers ::getGrammarTransformations
	 */
	public function testGetGrammarTransformations_empty() {
		$lang = $this->getObj();

		$this->assertSame( [], $lang->getGrammarTransformations() );
		$this->assertSame( [], $lang->getGrammarTransformations() );
	}

	/**
	 * @covers ::ucwords
	 * @dataProvider provideUcwords
	 */
	public function testUcwords( string $input, string $expected ) {
		$lang = $this->getObj();
		$this->assertSame( $expected, $lang->ucwords( $input ) );
	}

	public function provideUcwords() {
		return [
			'Empty string' => [ '', '' ],
			'Non-alpha only' => [ '3212-353', '3212-353' ],
			'Non-alpha only, multiple words' => [ '@%#, #@$%!', '@%#, #@$%!' ],
			'Single ASCII word' => [ 'teSt', 'Test' ],
			'ASCII words' => [ 'TeSt 123 test foo-bar', 'Test 123 Test Foo-bar' ],
			'Single multibyte word' => [ 'теСт', 'Тест' ],
			'Multibyte words' => [ 'ТесТ 123, тест 测试 test раз-два', 'Тест 123, Тест 测试 Test Раз-два' ],
		];
	}

	/**
	 * @covers ::ucwordbreaks
	 * @dataProvider provideUcwordbreaks
	 */
	public function testUcwordbreaks( string $input, string $expected ) {
		$lang = $this->getObj();
		$this->assertSame( $expected, $lang->ucwordbreaks( $input ) );
	}

	public function provideUcwordbreaks() {
		return [
			'Empty string' => [ '', '' ],
			'Non-alpha only' => [ '3212-353', '3212-353' ],
			'Non-alpha only, multiple words' => [ '@%#, #@$%!', '@%#, #@$%!' ],
			'Single ASCII word' => [ 'teSt', 'TeSt' ],
			'Single ASCII word, prefixed' => [ '-teSt', '-TeSt' ],
			'ASCII words' => [ 'TeSt 123 test foo-bar', 'TeSt 123 Test Foo-Bar' ],
			'Single multibyte word' => [ 'теСт', 'Тест' ],
			'Single multibyte word, prefixed' => [ '-теСт', '-Тест' ],
			'Multibyte words' => [ 'ТесТ 123, тест 测试 test раз-два', 'Тест 123, Тест 测试 Test Раз-Два' ],
		];
	}

	/**
	 * @covers ::firstChar
	 * @dataProvider provideFirstChar
	 */
	public function testFirstChar( string $input, string $expected ) {
		$lang = $this->getObj();
		$this->assertSame( $expected, $lang->firstChar( $input ) );
	}

	public function provideFirstChar() {
		return [
			'Empty string' => [ '', '' ],
			'Single Latin' => [ 'T', 'T' ],
			'Latin' => [ 'TEST', 'T' ],
			'Digit' => [ '123', '1' ],
			'Russian' => [ 'Ёпт', 'Ё' ],
			'Emoji' => [ '😂💀☢️', '😂' ],
			// Korean is special-cased to remove single letters from syllables
			'Korean' => [ '위키백과', 'ㅇ' ],
		];
	}
}
