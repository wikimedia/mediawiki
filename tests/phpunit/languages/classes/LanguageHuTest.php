<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageHu.php */
class LanguageHuTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = array( 'one', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providePlural
	 * @covers Language::getPluralRuleType
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function providePlural() {
		return array(
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'other', 2 ),
			array( 'other', 200 ),
		);
	}

	/**
	 * @dataProvider provideArticle
	 * @covers LanguageHu::getArticle
	 */
	public function testGetArticle( $expectedArticle, $word ) {
		$lang = TestingAccessWrapper::newFromObject( $this->getLang() );
		$actualArticle = $lang->getArticle( $word );
		$this->assertEquals( $expectedArticle, $actualArticle );
	}

	public function provideArticle() {
		return array(
			array( 'a', 'ház' ),
			array( 'az', 'ajtó' ),
		);
	}

	/**
	 * @dataProvider provideSuffix
	 * @covers LanguageHu::addSuffix
	 */
	public function testAddSuffix( $expectedWord, $word, $backSuffix, $frontSuffix, $labialSuffix ) {
		$lang = TestingAccessWrapper::newFromObject( $this->getLang() );
		$actualWord = $lang->addSuffix( $word, $backSuffix, $frontSuffix, $labialSuffix );
		$this->assertEquals( $expectedWord, $actualWord );
	}

	public function provideSuffix() {
		return array(
			array( 'fát', 'fa', 't', null, null ),
			array( 'oldalnak', 'oldal', 'nak', 'nek', null ),
			array( 'embernek', 'ember', 'nak', 'nek', null ),
			array( 'sofőrnek', 'sofőr', 'nak', 'nek', null ),
			array( 'oldalhoz', 'oldal', 'hoz', 'hez', 'höz' ),
			array( 'emberhez', 'ember', 'hoz', 'hez', 'höz' ),
			array( 'sofőrhöz', 'sofőr', 'hoz', 'hez', 'höz' ),
			array( 'főnökhöz', 'főnök', 'hoz', 'hez', 'höz' ),
			array( 'haverhoz', 'haver', 'hoz', 'hez', 'höz' ),
			array( 'oldallal', 'oldal', 'val', 'vel', null ),
			array( 'sakkal', 'sakk', 'val', 'vel', null ),
			array( 'kéménnyel', 'kémény', 'val', 'vel', null ),
			array( 'passzal', 'passz', 'val', 'vel', null ),
			array( 'csévével', 'cséve', 'val', 'vel', null ),
			array( 'tevén', 'teve', 'on', 'en', 'ön' ),
			array( 'ValamilyenWikivel', 'ValamilyenWiki', 'val', 'vel', null ),
		);
	}

	/**
	 * @dataProvider provideConvertGrammar
	 * @covers LanguageHu::convertGrammar
	 */
	public function testConvertGrammar( $expected, $type, $param1, $param2, $param3, $param4 ) {
		$actual = $this->getLang()->convertGrammar( $type, $param1, $param2, $param3, $param4 );
		$this->assertEquals( $expected, $actual );
	}

	public function provideConvertGrammar() {
		return array(
			array( 'sofőrről', 'suffix', 'sofőr', 'ról', 'ről', null ),
			array( 'a', 'article', 'sofőr', null, null, null ),
			array( 'sofőrről', 'rol', 'sofőr', null, null, null ),
			array( 'sofőrbe', 'ba', 'sofőr', null, null, null ),
			array( 'kaszák', 'k', 'kasza', null, null, null ),
		);
	}
}

