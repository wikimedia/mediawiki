<?php
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 */
class LanguageKkTest extends LanguageClassesTestCase {

	/**
	 * @dataProvider provideGrammar
	 * @covers \MediaWiki\Language\Language::convertGrammar
	 */
	public function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function provideGrammar() {
		yield 'Wikipedia ablative' => [
			'Уикипедияден',
			'Уикипедия',
			'ablative',
		];
		yield 'Wiktionary ablative' => [
			'Уикисөздіктен',
			'Уикисөздік',
			'ablative',
		];
		yield 'Wikibooks ablative' => [
			'Уикикітаптан',
			'Уикикітап',
			'ablative',
		];
	}
}
