<?php

namespace MediaWiki\Tests\Language;

use EnConverter;
use MediaWiki\Language\ConverterRule;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 * @covers \MediaWiki\Language\ConverterRule
 */
class ConverterRuleTest extends MediaWikiIntegrationTestCase {

	public function testParseEmpty() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$converter = new EnConverter( $lang );
		$rule = new ConverterRule( '', $converter );
		$rule->parse();

		$this->assertSame( false, $rule->getTitle(), 'title' );
		$this->assertSame( [], $rule->getConvTable(), 'conversion table' );
		$this->assertSame( 'none', $rule->getRulesAction(), 'rules action' );
	}

}
