<?php

/**
 * @group Templates
 */
class TemplateParserTest extends MediaWikiTestCase {

	protected $templateDir;

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgSecretKey' => 'foo',
		] );

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
		return [
			[
				'foobar',
				[],
				"hello world!\n"
			],
			[
				'foobar_args',
				[
					'planet' => 'world',
				],
				"hello world!\n",
			],
			[
				'../foobar',
				[],
				false,
				'UnexpectedValueException'
			],
			[
				"\000../foobar",
				[],
				false,
				'UnexpectedValueException'
			],
			[
				'/',
				[],
				false,
				'UnexpectedValueException'
			],
			[
				// Allegedly this can strip ext in windows.
				'baz<',
				[],
				false,
				'UnexpectedValueException'
			],
			[
				'\\foo',
				[],
				false,
				'UnexpectedValueException'
			],
			[
				'C:\bar',
				[],
				false,
				'UnexpectedValueException'
			],
			[
				"foo\000bar",
				[],
				false,
				'UnexpectedValueException'
			],
			[
				'nonexistenttemplate',
				[],
				false,
				'RuntimeException',
			],
			[
				'has_partial',
				[
					'planet' => 'world',
				],
				"Partial hello world!\n in here\n",
			],
			[
				'bad_partial',
				[],
				false,
				'Exception',
			],
		];
	}
}
