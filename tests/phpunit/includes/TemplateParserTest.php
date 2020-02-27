<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Templates
 * @covers TemplateParser
 */
class TemplateParserTest extends MediaWikiTestCase {

	protected $templateDir;

	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgSecretKey' => 'foo',
		] );

		$this->templateDir = dirname( __DIR__ ) . '/data/templates/';
	}

	/**
	 * @dataProvider provideProcessTemplate
	 */
	public function testProcessTemplate( $name, $args, $result, $exception = false ) {
		if ( $exception ) {
			$this->expectException( $exception );
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
			[
				'invalid_syntax',
				[],
				false,
				'Exception'
			],
			[
				'parentvars',
				[
					'foo' => 'f',
					'bar' => [
						[ 'baz' => 'x' ],
						[ 'baz' => 'y' ]
					]
				],
				"f\n\tf x\n\tf y\n"
			]
		];
	}

	public function testEnableRecursivePartials() {
		$tp = new TemplateParser( $this->templateDir );
		$data = [ 'r' => [ 'r' => [ 'r' => [] ] ] ];

		$tp->enableRecursivePartials( true );
		$this->assertEquals( 'rrr', $tp->processTemplate( 'recurse', $data ) );

		$tp->enableRecursivePartials( false );
		$this->expectException( Exception::class );
		$tp->processTemplate( 'recurse', $data );
	}

	/**
	 * @covers TemplateParser::compile
	 */
	public function testCompileReturnsPHPCodeAndMetadata() {
		// Code Under Test
		$templateParser = TestingAccessWrapper::newFromObject(
			new TemplateParser( $this->templateDir )
		);

		$compiledTemplate = $templateParser->compile( 'has_partial' );

		$this->assertArrayHasKey( 'phpCode', $compiledTemplate );

		// ---
		$expectedFiles = [
			"{$this->templateDir}/has_partial.mustache",
			"{$this->templateDir}/foobar_args.mustache",
		];

		$this->assertEquals(
			$expectedFiles,
			$compiledTemplate['files'],
			'::compile returns all files read during the compilation of the template.'
		);

		// ---
		$this->assertEquals(
			FileContentsHasher::getFileContentsHash( $expectedFiles ),
			$compiledTemplate[ 'filesHash' ],
			'::compile returns the hash of all files read during the compilation of the template.'
		);
	}
}
