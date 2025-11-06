<?php
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 */
class LanguageTyvTest extends LanguageClassesTestCase {

	/**
	 * @dataProvider provideGrammar
	 * @covers \MediaWiki\Language\Language::convertGrammar
	 */
	public function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function provideGrammar() {
		yield 'Wikipedia genitive' => [
			'Википедияның',
			'Википедия',
			'genitive',
		];
	}
}
