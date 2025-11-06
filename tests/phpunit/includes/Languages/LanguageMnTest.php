<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 */
class LanguageMnTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providerGrammar
	 * @covers \MediaWiki\Language\Language::convertGrammar
	 */
	public function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function providerGrammar() {
		yield 'Wikipedia genitive' => [
			'Википедиагийн',
			'Википедиа',
			'genitive',
		];
		yield 'Wiktionary genitive' => [
			'Викитолийн',
			'Викитоль',
			'genitive',
		];
	}
}
