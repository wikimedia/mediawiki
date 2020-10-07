<?php

/**
 * @covers ConverterRule
 */
class ConverterRuleTest extends MediaWikiIntegrationTestCase {

	public function testParseEmpty() {
		$converter = new EnConverter( new Language() );
		$rule = new ConverterRule( '', $converter );
		$rule->parse();

		$this->assertSame( false, $rule->getTitle(), 'title' );
		$this->assertSame( [], $rule->getConvTable(), 'conversion table' );
		$this->assertSame( 'none', $rule->getRulesAction(), 'rules action' );
	}

}
