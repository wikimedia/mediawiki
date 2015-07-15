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
	 * @dataProvider provideProcessTemplate
	 * @covers TemplateParser::processTemplate
	 * @covers TemplateParser::getTemplate
	 * @covers TemplateParser::getTemplateFilename
	 */
	public function testProcessTemplate( $name, $args, $result, $exception = false ) {
		if ( $exception ) {
			$this->setExpectedException( $exception );
		}
		$tp = new TemplateParser( $this->templateDir );
		$this->assertEquals( $result, $tp->processTemplate( $name, $args ) );
	}

	public static function provideProcessTemplate() {
		return array(
			array(
				'foobar',
				array(),
				"hello world!\n"
			),
			array(
				'foobar_args',
				array(
					'planet' => 'world',
				),
				"hello world!\n",
			),
			array(
				'../foobar',
				array(),
				false,
				'UnexpectedValueException'
			),
			array(
				'nonexistenttemplate',
				array(),
				false,
				'RuntimeException',
			),
			array(
				'has_partial',
				array(
					'planet' => 'world',
				),
				"Partial hello world!\n in here\n",
			),
			array(
				'bad_partial',
				array(),
				false,
				'Exception',
			),
		);
	}
}
