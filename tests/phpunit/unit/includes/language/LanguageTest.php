<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Json\FormatJson;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Bcp47Code\Bcp47CodeValue;

/**
 * @group Language
 * @covers \Language
 */
class LanguageTest extends MediaWikiUnitTestCase {
	/**
	 * @param array $options Valid keys:
	 *   'code'
	 *   'grammarTransformCache'
	 * @return Language
	 */
	private function getObj( array $options = [] ) {
		return new Language(
			$options['code'] ?? 'en',
			$this->createNoOpMock( NamespaceInfo::class ),
			$this->createNoOpMock( LocalisationCache::class ),
			$this->createNoOpMock( LanguageNameUtils::class ),
			$this->createNoOpMock( LanguageFallback::class ),
			$this->createNoOpMock( LanguageConverterFactory::class ),
			$this->createHookContainer(),
			new HashConfig( [] )
		);
	}

	/**
	 * @dataProvider provideCodes
	 */
	public function testGetCode( $code, $bcp47code ) {
		$lang = $this->getObj( [ 'code' => $code ] );
		$this->assertSame( $code, $lang->getCode() );
	}

	/**
	 * @dataProvider provideCodes
	 */
	public function testGetHtmlCode( $code, $bcp47code ) {
		$lang = $this->getObj( [ 'code' => $code ] );
		$this->assertSame( $bcp47code, $lang->getHtmlCode() );
	}

	/**
	 * @dataProvider provideCodes
	 */
	public function testToBcp47Code( $code, $bcp47code ) {
		$lang = $this->getObj( [ 'code' => $code ] );
		$this->assertSame( $bcp47code, $lang->toBcp47Code() );
	}

	/**
	 * @dataProvider provideCodes
	 */
	public function testIsSameCodeAs( $code, $bcp47code ) {
		// Commented out symmetric tests below can be enabled once
		// we require wikimedia/bcp47-code ^2.0.0
		$code1 = $this->getObj( [ 'code' => $code ] );
		$code2 = new Bcp47CodeValue( $bcp47code );
		$this->assertTrue( $code1->isSameCodeAs( $code2 ) );
		// $this->assertTrue( $code2->isSameCodeAs( $code1 ) ); // ^2.0.0

		// Should be case-insensitive.
		$code2 = new Bcp47CodeValue( strtoupper( $bcp47code ) );
		$this->assertTrue( $code1->isSameCodeAs( $code2 ) );
		// $this->assertTrue( $code2->isSameCodeAs( $code1 ) ); // ^2.0.0

		$code2 = new Bcp47CodeValue( strtolower( $bcp47code ) );
		$this->assertTrue( $code1->isSameCodeAs( $code2 ) );
		// $this->assertTrue( $code2->isSameCodeAs( $code1 ) ); // ^2.0.0
	}

	/**
	 * @dataProvider provideCodes
	 */
	public function testIsNotSameCodeAs( $code, $bcp47code ) {
		// Commented out symmetric tests below can be enabled once
		// we require wikimedia/bcp47-code ^2.0.0
		$code1 = $this->getObj( [ 'code' => $code ] );
		$code2 = new Bcp47CodeValue( 'x-not-the-same-code' );
		$this->assertFalse( $code1->isSameCodeAs( $code2 ) );
		// $this->assertFalse( $code2->isSameCodeAs( $code1 ) ); // ^2.0.0
	}

	public static function provideCodes() {
		return LanguageCodeTest::provideLanguageCodes();
	}

	/**
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

	public function testGetGrammarTransformations_empty() {
		$lang = $this->getObj();

		$this->assertSame( [], $lang->getGrammarTransformations() );
		$this->assertSame( [], $lang->getGrammarTransformations() );
	}

	/**
	 * @dataProvider provideUcwords
	 */
	public function testUcwords( string $input, string $expected ) {
		$lang = $this->getObj();
		$this->assertSame( $expected, $lang->ucwords( $input ) );
	}

	public static function provideUcwords() {
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
	 * @dataProvider provideUcwordbreaks
	 */
	public function testUcwordbreaks( string $input, string $expected ) {
		$lang = $this->getObj();
		$this->assertSame( $expected, $lang->ucwordbreaks( $input ) );
	}

	public static function provideUcwordbreaks() {
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
	 * @dataProvider provideFirstChar
	 */
	public function testFirstChar( string $input, string $expected ) {
		$lang = $this->getObj();
		$this->assertSame( $expected, $lang->firstChar( $input ) );
	}

	public static function provideFirstChar() {
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
