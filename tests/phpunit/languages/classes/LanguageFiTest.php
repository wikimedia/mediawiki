<?php
/**
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageFi.php */
class LanguageFiTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providerGrammar
	 * @covers Language::convertGrammar
	 */
	public function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function providerGrammar() {
		return array(
			array(
				'Wikipedia',
				'Wikipedian',
				'genitive'
			),
			array(
				'Wikipedia',
				'Wikipediasta',
				'elative',
			),
			array(
				'Wikipedia',
				'Wikipediaa',
				'partitive',
			),
			array(
				'Wikipedia',
				'Wikipediaan',
				'illative',
			),
			array(
				'Wikipedia',
				'Wikipediassa',
				'inessive',
			),
			array(
				'Wikipedia',
				'Wikipedialle',
				'allative',
			),
			array(
				'Matti Meikäläinen',
				'Matti Meikäläiselle',
				'allative',
			),
			array(
				'Pekka',
				'Pekalle',
				'allative',
			),
			array(
				'Saara',
				'Saaralle',
				'allative',
			),
			array(
				'Jack Phoenix',
				'Jack Phoenixille',
				'allative',
			),
			array(
				'Test123',
				'Test123:lle',
				'allative',
			),
			array(
				'123',
				'123:lle',
				'allative',
			),
		);
	}
}
