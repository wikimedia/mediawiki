<?php
/**
 * @license GPL-2.0-or-later
 * @author Amir E. Aharoni
 * @copyright Copyright © 2022, Amir E. Aharoni
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 */
class LanguageKaTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providerGrammar
	 * @covers \MediaWiki\Language\Language::convertGrammar
	 */
	public function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function providerGrammar() {
		yield 'Wikipedia genitive' => [
			'ვიკიპედიის',
			'ვიკიპედია',
			'ნათესაობითი',
		];
		yield 'Wiktionary genitive' => [
			'ვიქსიკონის',
			'ვიქსიკონი',
			'ნათესაობითი',
		];
		yield 'Wikibooks genitive' => [
			'ვიკიწიგნების',
			'ვიკიწიგნები',
			'ნათესაობითი',
		];
		yield 'Wikiquote genitive' => [
			'ვიკიციტატის',
			'ვიკიციტატა',
			'ნათესაობითი',
		];
		yield 'Wikinews genitive' => [
			'ვიკისიახლეების',
			'ვიკისიახლეები',
			'ნათესაობითი',
		];
		yield 'Wikispecies genitive' => [
			'ვიკისახეობების',
			'ვიკისახეობები',
			'ნათესაობითი',
		];
		yield 'Wikidata genitive' => [
			'ვიკიმონაცემების',
			'ვიკიმონაცემები',
			'ნათესაობითი',
		];
		yield 'Commons genitive' => [
			'ვიკისაწყობის',
			'ვიკისაწყობი',
			'ნათესაობითი',
		];
		yield 'Wikivoyage genitive' => [
			'ვიკივოიაჟის',
			'ვიკივოიაჟი',
			'ნათესაობითი',
		];
		yield 'Meta-Wiki genitive' => [
			'მეტა-ვიკის',
			'მეტა-ვიკი',
			'ნათესაობითი',
		];
		yield 'MediaWiki genitive' => [
			'მედიავიკის',
			'მედიავიკი',
			'ნათესაობითი',
		];
		yield 'Wikiversity genitive' => [
			'ვიკივერსიტეტის',
			'ვიკივერსიტეტი',
			'ნათესაობითი',
		];
		yield 'Freedom genitive' => [
			'თავისუფლების',
			'თავისუფლება',
			'ნათესაობითი',
		];
	}
}
