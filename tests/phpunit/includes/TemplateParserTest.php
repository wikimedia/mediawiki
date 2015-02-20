<?php

/**
 * @group Templates
 */
class TemplateParserTest extends MediaWikiTestCase {
	/**
	 * @covers TemplateParser::compile
	 */
	public function testTemplateCompilation() {
		$this->assertRegExp(
			'/^<\?php return function/',
			TemplateParser::compile( "test" ),
			'compile a simple mustache template'
		);
	}

	/**
	 * @covers TemplateParser::compile
	 */
	public function testTemplateCompilationWithVariable() {
		$this->assertRegExp(
			'/return \'\'\.htmlentities\(\(string\)\(\(isset\(\$in\[\'value\'\]\) && '
				. 'is_array\(\$in\)\) \? \$in\[\'value\'\] : null\), ENT_QUOTES, '
				. '\'UTF-8\'\)\.\'\';/',
			TemplateParser::compile( "{{value}}" ),
			'compile a mustache template with an escaped variable'
		);
	}
}
