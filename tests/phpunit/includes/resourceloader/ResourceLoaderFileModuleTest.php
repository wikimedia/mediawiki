<?php

/**
 * @group Database
 * @group ResourceLoader
 */
class ResourceLoaderFileModuleTest extends ResourceLoaderTestCase {

	protected function setUp() {
		parent::setUp();

		// The return value of the closure shouldn't matter since this test should
		// never call it
		SkinFactory::getDefaultInstance()->register(
			'fakeskin',
			'FakeSkin',
			function () {
			}
		);
	}

	private static function getModules() {
		$base = [
			'localBasePath' => realpath( __DIR__ ),
		];

		return [
			'noTemplateModule' => [],

			'htmlTemplateModule' => $base + [
				'templates' => [
					'templates/template.html',
					'templates/template2.html',
				]
			],

			'aliasedHtmlTemplateModule' => $base + [
				'templates' => [
					'foo.html' => 'templates/template.html',
					'bar.html' => 'templates/template2.html',
				]
			],

			'templateModuleHandlebars' => $base + [
				'templates' => [
					'templates/template_awesome.handlebars',
				],
			],

			'aliasFooFromBar' => $base + [
				'templates' => [
					'foo.foo' => 'templates/template.bar',
				],
			],
		];
	}

	public static function providerTemplateDependencies() {
		$modules = self::getModules();

		return [
			[
				$modules['noTemplateModule'],
				[],
			],
			[
				$modules['htmlTemplateModule'],
				[
					'mediawiki.template',
				],
			],
			[
				$modules['templateModuleHandlebars'],
				[
					'mediawiki.template',
					'mediawiki.template.handlebars',
				],
			],
			[
				$modules['aliasFooFromBar'],
				[
					'mediawiki.template',
					'mediawiki.template.foo',
				],
			],
		];
	}

	/**
	 * @dataProvider providerTemplateDependencies
	 * @covers ResourceLoaderFileModule::__construct
	 * @covers ResourceLoaderFileModule::getDependencies
	 */
	public function testTemplateDependencies( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );
		$this->assertEquals( $rl->getDependencies(), $expected );
	}

	/**
	 * @covers ResourceLoaderFileModule::getAllStyleFiles
	 * @covers ResourceLoaderFileModule::getAllSkinStyleFiles
	 * @covers ResourceLoaderFileModule::getSkinStyleFiles
	 */
	public function testGetAllSkinStyleFiles() {
		$baseParams = [
			'scripts' => [
				'foo.js',
				'bar.js',
			],
			'styles' => [
				'foo.css',
				'bar.css' => [ 'media' => 'print' ],
				'screen.less' => [ 'media' => 'screen' ],
				'screen-query.css' => [ 'media' => 'screen and (min-width: 400px)' ],
			],
			'skinStyles' => [
				'default' => 'quux-fallback.less',
				'fakeskin' => [
					'baz-vector.css',
					'quux-vector.less',
				],
			],
			'messages' => [
				'hello',
				'world',
			],
		];

		$module = new ResourceLoaderFileModule( $baseParams );

		$this->assertEquals(
			[
				'foo.css',
				'baz-vector.css',
				'quux-vector.less',
				'quux-fallback.less',
				'bar.css',
				'screen.less',
				'screen-query.css',
			],
			array_map( 'basename', $module->getAllStyleFiles() )
		);
	}

	/**
	 * Strip @noflip annotations from CSS code.
	 * @param string $css
	 * @return string
	 */
	private static function stripNoflip( $css ) {
		return str_replace( '/*@noflip*/ ', '', $css );
	}

	/**
	 * What happens when you mix @embed and @noflip?
	 * This really is an integration test, but oh well.
	 *
	 * @covers ResourceLoaderFileModule::getStyles
	 * @covers ResourceLoaderFileModule::getStyleFiles
	 */
	public function testMixedCssAnnotations() {
		$basePath = __DIR__ . '/../../data/css';
		$testModule = new ResourceLoaderFileModule( [
			'localBasePath' => $basePath,
			'styles' => [ 'test.css' ],
		] );
		$expectedModule = new ResourceLoaderFileModule( [
			'localBasePath' => $basePath,
			'styles' => [ 'expected.css' ],
		] );

		$contextLtr = $this->getResourceLoaderContext( 'en', 'ltr' );
		$contextRtl = $this->getResourceLoaderContext( 'he', 'rtl' );

		// Since we want to compare the effect of @noflip+@embed against the effect of just @embed, and
		// the @noflip annotations are always preserved, we need to strip them first.
		$this->assertEquals(
			$expectedModule->getStyles( $contextLtr ),
			self::stripNoflip( $testModule->getStyles( $contextLtr ) ),
			"/*@noflip*/ with /*@embed*/ gives correct results in LTR mode"
		);
		$this->assertEquals(
			$expectedModule->getStyles( $contextLtr ),
			self::stripNoflip( $testModule->getStyles( $contextRtl ) ),
			"/*@noflip*/ with /*@embed*/ gives correct results in RTL mode"
		);
	}

	public static function providerGetTemplates() {
		$modules = self::getModules();

		return [
			[
				$modules['noTemplateModule'],
				[],
			],
			[
				$modules['templateModuleHandlebars'],
				[
					'templates/template_awesome.handlebars' => "wow\n",
				],
			],
			[
				$modules['htmlTemplateModule'],
				[
					'templates/template.html' => "<strong>hello</strong>\n",
					'templates/template2.html' => "<div>goodbye</div>\n",
				],
			],
			[
				$modules['aliasedHtmlTemplateModule'],
				[
					'foo.html' => "<strong>hello</strong>\n",
					'bar.html' => "<div>goodbye</div>\n",
				],
			],
		];
	}

	/**
	 * @dataProvider providerGetTemplates
	 * @covers ResourceLoaderFileModule::getTemplates
	 */
	public function testGetTemplates( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );

		$this->assertEquals( $rl->getTemplates(), $expected );
	}

	public function testBomConcatenation() {
		$basePath = __DIR__ . '/../../data/css';
		$testModule = new ResourceLoaderFileModule( [
			'localBasePath' => $basePath,
			'styles' => [ 'bom.css' ],
			] );
		$this->assertEquals(
			substr( file_get_contents( "$basePath/bom.css" ), 0, 10 ),
			"\xef\xbb\xbf.efbbbf",
			'File has leading BOM'
		);

		$contextLtr = $this->getResourceLoaderContext( 'en', 'ltr' );
		$this->assertEquals(
			$testModule->getStyles( $contextLtr ),
			[ 'all' => ".efbbbf_bom_char_at_start_of_file {}\n" ],
			'Leading BOM removed when concatenating files'
		);
	}
}
