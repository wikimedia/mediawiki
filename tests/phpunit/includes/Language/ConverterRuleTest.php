<?php

namespace MediaWiki\Tests\Language;

use MediaWiki\Language\ConverterRule;
use MediaWiki\Language\Converters\EnConverter;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Utils\ContentUtils;

/**
 * @group Language
 * @covers \MediaWiki\Language\ConverterRule
 */
class ConverterRuleTest extends MediaWikiIntegrationTestCase {

	/** @dataProvider provideRules */
	public function testParseText( $expected ) {
		$ownerDoc = ContentUtils::createAndLoadDocument( '' );
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$converter = new EnConverter( $lang );
		$rule = new ConverterRule( $converter );
		$rule->parse( $expected['text'] );

		$this->assertSame( $expected['title'] ?? false, $rule->getTitle(), 'title' );
		$this->assertSame( $expected['title'] ?? null, $rule->getTitleFragment( $ownerDoc )?->textContent, 'title 2' );
		$this->assertSame( $expected['convTable'] ?? [], $rule->getConvTable(), 'conversion table' );
		$this->assertSame( $expected['action'] ?? 'none', $rule->getRulesAction(), 'rules action' );
		$this->assertSame( $expected['display'] ?? '', $rule->getDisplay(), 'display' );
		$this->assertSame( $expected['display'] ?? '', $rule->getDisplayFragment( $ownerDoc )?->textContent, 'display 2' );
	}

	public function provideRules() {
		yield 'Empty' => [ [
			'text' => '',
		] ];
		yield 'Raw' => [ [
			'text' => 'raw',
			'display' => 'raw',
		] ];
		yield 'Bidirectional' => [ [
			'text' => 'en:elevator;en-x-piglatin:iftlay',
			'display' => 'elevator',
			'convTable' => [
				'en-x-piglatin' => [ 'elevator' => 'iftlay' ],
				'en' => [ 'iftlay' => 'elevator' ],
			],
		] ];
		yield 'Unidirectional' => [ [
			'text' => 'H|elevator=>en-x-piglatin:elevatorway',
			'display' => '',
			'convTable' => [
				'en-x-piglatin' => [ 'elevator' => 'elevatorway' ],
			],
			'action' => 'add',
		] ];
		yield 'Title' => [ [
			'text' => 'T|en:title',
			'title' => 'title',
			'convTable' => [
				'en-x-piglatin' => [ 'title' => 'title' ],
				'en' => [ 'title' => 'title' ],
			],
		] ];
		yield 'Name' => [ [
			'text' => 'N|en',
			'display' => 'English',
		] ];
		yield 'Description (bidir)' => [ [
			'text' => 'D|en:elevator;en-x-piglatin:iftlay',
			'display' => 'English:elevator;Igpay Atinlay:iftlay;',
			'convTable' => [
				'en-x-piglatin' => [ 'elevator' => 'iftlay' ],
				'en' => [ 'iftlay' => 'elevator' ],
			],
		] ];
		yield 'Description (unidir)' => [ [
			'text' => 'D|elevator=>en-x-piglatin:elevatorway',
			'display' => 'elevator⇒Igpay Atinlay:elevatorway;',
			'convTable' => [
				'en-x-piglatin' => [ 'elevator' => 'elevatorway' ],
			],
		] ];
	}
}
