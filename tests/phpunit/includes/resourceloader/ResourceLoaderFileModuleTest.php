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

	public function providerGetScriptPackageFiles() {
		$basePath = __DIR__ . '/../../data/resourceloader';
		$base = [ 'localBasePath' => $basePath ];
		$commentScript = file_get_contents( "$basePath/script-comment.js" );
		$nosemiScript = file_get_contents( "$basePath/script-nosemi.js" );
		$config = RequestContext::getMain()->getConfig();
		return [
			[
				$base + [
					'packageFiles' => [
						'script-comment.js',
						'script-nosemi.js'
					]
				],
				[
					'files' => [
						'script-comment.js' => [
							'type' => 'script',
							'content' => $commentScript,
						],
						'script-nosemi.js' => [
							'type' => 'script',
							'content' => $nosemiScript
						]
					],
					'main' => 'script-comment.js'
				]
			],
			[
				$base + [
					'packageFiles' => [
						'script-comment.js',
						[ 'name' => 'script-nosemi.js', 'main' => true ]
					],
					'deprecated' => 'Deprecation test',
					'name' => 'test-deprecated'
				],
				[
					'files' => [
						'script-comment.js' => [
							'type' => 'script',
							'content' => $commentScript,
						],
						'script-nosemi.js' => [
							'type' => 'script',
							'content' => 'mw.log.warn(' .
								'"This page is using the deprecated ResourceLoader module \"test-deprecated\".\\n' .
								"Deprecation test" .
								'");' .
								$nosemiScript
						]
					],
					'main' => 'script-nosemi.js'
				]
			],
			[
				$base + [
					'packageFiles' => [
						[ 'name' => 'init.js', 'file' => 'script-comment.js', 'main' => true ],
						[ 'name' => 'nosemi.js', 'file' => 'script-nosemi.js' ],
					]
				],
				[
					'files' => [
						'init.js' => [
							'type' => 'script',
							'content' => $commentScript,
						],
						'nosemi.js' => [
							'type' => 'script',
							'content' => $nosemiScript
						]
					],
					'main' => 'init.js'
				]
			],
			[
				$base + [
					'packageFiles' => [
						[ 'name' => 'foo.json', 'content' => [ 'Hello' => 'world' ] ],
						'sample.json',
						[ 'name' => 'bar.js', 'content' => "console.log('Hello');" ],
						[ 'name' => 'data.json', 'callback' => function ( $context ) {
							return [ 'langCode' => $context->getLanguage() ];
						} ],
						[ 'name' => 'config.json', 'config' => [
							'Sitename',
							'wgVersion' => 'Version',
						] ],
					]
				],
				[
					'files' => [
						'foo.json' => [
							'type' => 'data',
							'content' => [ 'Hello' => 'world' ],
						],
						'sample.json' => [
							'type' => 'data',
							'content' => (object)[ 'foo' => 'bar', 'answer' => 42 ],
						],
						'bar.js' => [
							'type' => 'script',
							'content' => "console.log('Hello');",
						],
						'data.json' => [
							'type' => 'data',
							'content' => [ 'langCode' => 'fy' ]
						],
						'config.json' => [
							'type' => 'data',
							'content' => [
								'Sitename' => $config->get( 'Sitename' ),
								'wgVersion' => $config->get( 'Version' ),
							]
						]
					],
					'main' => 'bar.js'
				],
				[
					'lang' => 'fy'
				]
			],
			[
				$base + [
					'packageFiles' => [
						[ 'file' => 'script-comment.js' ]
					]
				],
				false
			],
			[
				$base + [
					'packageFiles' => [
						[ 'name' => 'foo.json', 'callback' => 'functionThatDoesNotExist142857' ]
					]
				],
				false
			],
			[
				$base + [
					'packageFiles' => [
						'foo.json' => [ 'type' => 'script', 'config' => [ 'Sitename' ] ]
					]
				],
				false
			],
			[
				$base + [
					'packageFiles' => [
						[ 'name' => 'foo.js', 'config' => 'Sitename' ]
					]
				],
				false
			],
			[
				$base + [
					'packageFiles' => [
						'foo.js' => [ 'garbage' => 'data' ]
					]
				],
				false
			],
			[
				$base + [
					'packageFiles' => [
						'filethatdoesnotexist142857.js'
					]
				],
				false
			],
			[
				$base + [
					'packageFiles' => [
						'script-nosemi.js',
						[ 'name' => 'foo.json', 'content' => [ 'Hello' => 'world' ], 'main' => true ]
					]
				],
				false
			]
		];
	}

	/**
	 * @dataProvider providerGetScriptPackageFiles
	 * @covers ResourceLoaderFileModule::getScript
	 * @covers ResourceLoaderFileModule::getPackageFiles
	 * @covers ResourceLoaderFileModule::expandPackageFiles
	 */
	public function testGetScriptPackageFiles( $moduleDefinition, $expected, $contextOptions = [] ) {
		$module = new ResourceLoaderFileModule( $moduleDefinition );
		$context = $this->getResourceLoaderContext( $contextOptions );
		if ( isset( $moduleDefinition['name'] ) ) {
			$module->setName( $moduleDefinition['name'] );
		}
		if ( $expected === false ) {
			$this->setExpectedException( MWException::class );
			$module->getScript( $context );
		} else {
			$this->assertEquals( $expected, $module->getScript( $context ) );
		}
	}
}
