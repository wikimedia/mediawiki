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

			'deprecatedModule' => $base + [
				'deprecated' => true,
			],
			'deprecatedTomorrow' => $base + [
				'deprecated' => 'Will be removed tomorrow.'
			],

			'htmlTemplateModule' => $base + [
				'templates' => [
					'templates/template.html',
					'templates/template2.html',
				]
			],

			'htmlTemplateUnknown' => $base + [
				'templates' => [
					'templates/notfound.html',
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
		$rl->setName( 'testing' );
		$this->assertEquals( $rl->getDependencies(), $expected );
	}

	public static function providerDeprecatedModules() {
		return [
			[
				'deprecatedModule',
				'mw.log.warn("This page is using the deprecated ResourceLoader module \"deprecatedModule\".");',
			],
			[
				'deprecatedTomorrow',
				'mw.log.warn(' .
					'"This page is using the deprecated ResourceLoader module \"deprecatedTomorrow\".\\n' .
					"Will be removed tomorrow." .
					'");'
			]
		];
	}

	/**
	 * @dataProvider providerDeprecatedModules
	 * @covers ResourceLoaderFileModule::getScript
	 */
	public function testDeprecatedModules( $name, $expected ) {
		$modules = self::getModules();
		$module = new ResourceLoaderFileModule( $modules[$name] );
		$module->setName( $name );
		$ctx = $this->getResourceLoaderContext();
		$this->assertEquals( $module->getScript( $ctx ), $expected );
	}

	/**
	 * @covers ResourceLoaderFileModule::getScript
	 * @covers ResourceLoaderFileModule::getScriptFiles
	 * @covers ResourceLoaderFileModule::readScriptFiles
	 */
	public function testGetScript() {
		$module = new ResourceLoaderFileModule( [
			'localBasePath' => __DIR__ . '/../../data/resourceloader',
			'scripts' => [ 'script-nosemi.js', 'script-comment.js' ],
		] );
		$module->setName( 'testing' );
		$ctx = $this->getResourceLoaderContext();
		$this->assertEquals(
			"/* eslint-disable */\nmw.foo()\n" .
			"\n" .
			"/* eslint-disable */\nmw.foo()\n// mw.bar();\n" .
			"\n",
			$module->getScript( $ctx ),
			'scripts are concatenated with a new-line'
		);
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
		$module->setName( 'testing' );

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
	 * @covers ResourceLoaderFileModule::readStyleFiles
	 * @covers ResourceLoaderFileModule::readStyleFile
	 */
	public function testMixedCssAnnotations() {
		$basePath = __DIR__ . '/../../data/css';
		$testModule = new ResourceLoaderFileModule( [
			'localBasePath' => $basePath,
			'styles' => [ 'test.css' ],
		] );
		$testModule->setName( 'testing' );
		$expectedModule = new ResourceLoaderFileModule( [
			'localBasePath' => $basePath,
			'styles' => [ 'expected.css' ],
		] );
		$expectedModule->setName( 'testing' );

		$contextLtr = $this->getResourceLoaderContext( [
			'lang' => 'en',
			'dir' => 'ltr',
		] );
		$contextRtl = $this->getResourceLoaderContext( [
			'lang' => 'he',
			'dir' => 'rtl',
		] );

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
			[
				$modules['htmlTemplateUnknown'],
				false,
			],
		];
	}

	/**
	 * @dataProvider providerGetTemplates
	 * @covers ResourceLoaderFileModule::getTemplates
	 */
	public function testGetTemplates( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );
		$rl->setName( 'testing' );

		if ( $expected === false ) {
			$this->setExpectedException( MWException::class );
			$rl->getTemplates();
		} else {
			$this->assertEquals( $rl->getTemplates(), $expected );
		}
	}

	/**
	 * @covers ResourceLoaderFileModule::stripBom
	 */
	public function testBomConcatenation() {
		$basePath = __DIR__ . '/../../data/css';
		$testModule = new ResourceLoaderFileModule( [
			'localBasePath' => $basePath,
			'styles' => [ 'bom.css' ],
			] );
		$testModule->setName( 'testing' );
		$this->assertEquals(
			substr( file_get_contents( "$basePath/bom.css" ), 0, 10 ),
			"\xef\xbb\xbf.efbbbf",
			'File has leading BOM'
		);

		$context = $this->getResourceLoaderContext();
		$this->assertEquals(
			$testModule->getStyles( $context ),
			[ 'all' => ".efbbbf_bom_char_at_start_of_file {}\n" ],
			'Leading BOM removed when concatenating files'
		);
	}

	/**
	 * @covers ResourceLoaderFileModule::compileLessFile
	 */
	public function testLessFileCompilation() {
		$context = $this->getResourceLoaderContext();
		$basePath = __DIR__ . '/../../data/less/module';
		$module = new ResourceLoaderFileTestModule( [
			'localBasePath' => $basePath,
			'styles' => [ 'styles.less' ],
		], [
			'lessVars' => [ 'foo' => '2px', 'Foo' => '#eeeeee' ]
		] );
		$module->setName( 'test.less' );
		$styles = $module->getStyles( $context );
		$this->assertStringEqualsFile( $basePath . '/styles.css', $styles['all'] );
	}

	/**
	 * @covers ResourceLoaderFileModule::getDefinitionSummary
	 * @covers ResourceLoaderFileModule::getFileHashes
	 */
	public function testGetVersionHash() {
		$context = $this->getResourceLoaderContext();

		// Less variables
		$module = new ResourceLoaderFileTestModule();
		$version = $module->getVersionHash( $context );
		$module = new ResourceLoaderFileTestModule( [], [
			'lessVars' => [ 'key' => 'value' ],
		] );
		$this->assertNotEquals(
			$version,
			$module->getVersionHash( $context ),
			'Using less variables is significant'
		);
	}
}
