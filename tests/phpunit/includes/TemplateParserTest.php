<?php

/**
 * @group Templates
 */
class TemplateParserTest extends MediaWikiTestCase {

	protected $templateDir;

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgSecretKey' => 'foo',
			'wgMemc' => new EmptyBagOStuff(),
		) );

		$this->templateDir = dirname( __DIR__ ) . '/data/templates/';
	}

	/**
	 * @covers TemplateParser::getTemplateFilename
	 * @dataProvider provideGetTemplateFilename
	 */
	public function testGetTemplateFilename( $dir, $name, $result, $exception = false ) {
		if ( $exception ) {
			$this->setExpectedException( $exception );
		}

		$tp = new TemplateParser( $dir );
		$path = $tp->getTemplateFilename( $name );
		$this->assertEquals( $result, $path );
	}

	public static function provideGetTemplateFilename() {
		return array(
			array(
				'dir/templates',
				'foobar',
				'dir/templates/foobar.mustache',
			),
			array(
				'dir/templates',
				'../foobar',
				'',
				'UnexpectedValueException'
			),
		);
	}

	/**
	 * @covers TemplateParser::getTemplate
	 */
	public function testGetTemplate() {
		$tp = new TemplateParser( $this->templateDir );
		$this->assertTrue( is_callable( $tp->getTemplate( 'foobar' ) ) );
	}

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
