<?php

namespace MediaWiki\Tests\ResourceLoader;

use Exception;
use HashConfig;
use LogicException;
use MediaWiki\ResourceLoader\FileModule;
use MediaWiki\ResourceLoader\FilePath;
use MediaWiki\ResourceLoader\ResourceLoader;
use Psr\Container\ContainerInterface;
use ResourceLoaderFileTestModule;
use ResourceLoaderTestCase;
use RuntimeException;
use SkinFactory;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * @group ResourceLoader
 * @covers \MediaWiki\ResourceLoader\FileModule
 */
class FileModuleTest extends ResourceLoaderTestCase {

	protected function setUp(): void {
		parent::setUp();

		$skinFactory = new SkinFactory(
			new ObjectFactory( $this->createMock( ContainerInterface::class ) ), []
		);
		// The empty spec shouldn't matter since this test should never call it
		$skinFactory->register(
			'fakeskin',
			'FakeSkin',
			[]
		);
		$this->setService( 'SkinFactory', $skinFactory );

		// This test is not expected to query any database
		\MediaWiki\MediaWikiServices::disableStorageBackend();
	}

	private static function getModules() {
		$base = [
			'localBasePath' => __DIR__,
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
	 */
	public function testTemplateDependencies( $module, $expected ) {
		$rl = new FileModule( $module );
		$rl->setName( 'testing' );
		$this->assertEquals( $expected, $rl->getDependencies() );
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
	 */
	public function testDeprecatedModules( $name, $expected ) {
		$modules = self::getModules();
		$module = new FileModule( $modules[$name] );
		$module->setName( $name );
		$ctx = $this->getResourceLoaderContext();
		$this->assertEquals( $expected, $module->getScript( $ctx ) );
	}

	public function testGetScript() {
		$module = new FileModule( [
			'localBasePath' => __DIR__ . '/../../data/resourceloader',
			'scripts' => [ 'script-nosemi.js', 'script-comment.js' ],
		] );
		$module->setName( 'testing' );
		$ctx = $this->getResourceLoaderContext();
		$this->assertEquals(
			"/* eslint-disable */\nmw.foo()\n" .
			"/* eslint-disable */\nmw.foo()\n// mw.bar();\n",
			$module->getScript( $ctx ),
			'scripts with newline at the end are concatenated without a newline'
		);

		$module = new FileModule( [
			'localBasePath' => __DIR__ . '/../../data/resourceloader',
			'scripts' => [ 'script-nosemi-nonl.js', 'script-comment-nonl.js' ],
		] );
		$module->setName( 'testing' );
		$ctx = $this->getResourceLoaderContext();
		$this->assertEquals(
			"/* eslint-disable */\nmw.foo()" .
			"\n" .
			"/* eslint-disable */\nmw.foo()\n// mw.bar();" .
			"\n",
			$module->getScript( $ctx ),
			'scripts without newline at the end are concatenated with a newline'
		);
	}

	/**
	 * @covers \MediaWiki\ResourceLoader\FileModule
	 * @covers \MediaWiki\ResourceLoader\Module
	 * @covers \MediaWiki\ResourceLoader\ResourceLoader
	 */
	public function testGetURLsForDebug() {
		$ctx = $this->getResourceLoaderContext();
		$module = new FileModule( [
			'localBasePath' => __DIR__ . '/../../data/resourceloader',
			'remoteBasePath' => '/w/something',
			'styles' => [ 'simple.css' ],
			'scripts' => [ 'script-comment.js' ],
		] );
		$module->setName( 'testing' );
		$module->setConfig( $ctx->getResourceLoader()->getConfig() );

		$this->assertEquals(
			[
				'https://example.org/w/something/script-comment.js'
			],
			$module->getScriptURLsForDebug( $ctx ),
			'script urls'
		);
		$this->assertEquals(
			[ 'all' => [
				'/w/something/simple.css'
			] ],
			$module->getStyleURLsForDebug( $ctx ),
			'style urls'
		);
	}

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

		$module = new FileModule( $baseParams );
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
	 * Confirm that 'ResourceModuleSkinStyles' skin attributes get injected
	 * into the module, and have their file contents read correctly from their
	 * own (out-of-module) directories.
	 *
	 * @covers \MediaWiki\ResourceLoader\FileModule
	 * @covers \MediaWiki\ResourceLoader\ResourceLoader
	 */
	public function testInjectSkinStyles() {
		$moduleDir = __DIR__ . '/../../data/resourceloader';
		$skinDir = __DIR__ . '/../../data/resourceloader/myskin';
		$rl = new ResourceLoader( new HashConfig( self::getSettings() ) );
		$rl->setModuleSkinStyles( [
			'fakeskin' => [
				'localBasePath' => $skinDir,
				'testing' => [
					'override.css',
				],
			],
		] );
		$rl->register( 'testing', [
			'localBasePath' => $moduleDir,
			'styles' => [ 'simple.css' ],
		] );
		$ctx = $this->getResourceLoaderContext( [ 'skin' => 'fakeskin' ], $rl );

		$module = $rl->getModule( 'testing' );
		$this->assertInstanceOf( FileModule::class, $module );
		$this->assertEquals(
			[ 'all' => ".example { color: blue; }\n\n.override { line-height: 2; }\n" ],
			$module->getStyles( $ctx )
		);
	}

	/**
	 * Verify what happens when you mix @embed and @noflip.
	 */
	public function testMixedCssAnnotations() {
		$basePath = __DIR__ . '/../../data/css';
		$testModule = new ResourceLoaderFileTestModule( [
			'localBasePath' => $basePath,
			'styles' => [ 'test.css' ],
		] );
		$testModule->setName( 'testing' );
		$expectedModule = new ResourceLoaderFileTestModule( [
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

	public function testCssFlipping() {
		$plain = new ResourceLoaderFileTestModule( [
			'localBasePath' => __DIR__ . '/../../data/resourceloader',
			'styles' => [ 'direction.css' ],
		] );
		$plain->setName( 'test' );

		$context = $this->getResourceLoaderContext( [ 'lang' => 'en', 'dir' => 'ltr' ] );
		$this->assertEquals(
			[ 'all' => ".example { text-align: left; }\n" ],
			$plain->getStyles( $context ),
			'Unchanged styles in LTR mode'
		);
		$context = $this->getResourceLoaderContext( [ 'lang' => 'he', 'dir' => 'rtl' ] );
		$this->assertEquals(
			[ 'all' => ".example { text-align: right; }\n" ],
			$plain->getStyles( $context ),
			'Flipped styles in RTL mode'
		);

		$noflip = new ResourceLoaderFileTestModule( [
			'localBasePath' => __DIR__ . '/../../data/resourceloader',
			'styles' => [ 'direction.css' ],
			'noflip' => true,
		] );
		$noflip->setName( 'test' );
		$this->assertEquals(
			[ 'all' => ".example { text-align: right; }\n" ],
			$plain->getStyles( $context ),
			'Unchanged styles in RTL mode with noflip at module level'
		);
	}

	/**
	 * Test reading files from elsewhere than localBasePath using ResourceLoaderFilePath.
	 *
	 * The use of ResourceLoaderFilePath objects resembles the way that ResourceLoader::getModule()
	 * injects additional files when 'ResourceModuleSkinStyles' or 'OOUIThemePaths' skin attributes
	 * apply to a given module.
	 */
	public function testResourceLoaderFilePath() {
		$basePath = __DIR__ . '/../../data/blahblah';
		$filePath = __DIR__ . '/../../data/rlfilepath';
		$testModule = new FileModule( [
			'localBasePath' => $basePath,
			'remoteBasePath' => 'blahblah',
			'styles' => new FilePath( 'style.css', $filePath, 'rlfilepath' ),
			'skinStyles' => [
				'vector' => new FilePath( 'skinStyle.css', $filePath, 'rlfilepath' ),
			],
			'scripts' => new FilePath( 'script.js', $filePath, 'rlfilepath' ),
			'templates' => new FilePath( 'template.html', $filePath, 'rlfilepath' ),
		] );
		$testModule->setName( 'testModule' );
		$expectedModule = new FileModule( [
			'localBasePath' => $filePath,
			'remoteBasePath' => 'rlfilepath',
			'styles' => 'style.css',
			'skinStyles' => [
				'vector' => 'skinStyle.css',
			],
			'scripts' => 'script.js',
			'templates' => 'template.html',
		] );
		$expectedModule->setName( 'expectedModule' );

		$context = $this->getResourceLoaderContext();
		$this->assertEquals(
			$expectedModule->getModuleContent( $context ),
			$testModule->getModuleContent( $context ),
			"Using ResourceLoaderFilePath works correctly"
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
	 */
	public function testGetTemplates( $module, $expected ) {
		$rl = new FileModule( $module );
		$rl->setName( 'testing' );

		if ( $expected === false ) {
			$this->expectException( RuntimeException::class );
			$rl->getTemplates();
		} else {
			$this->assertEquals( $expected, $rl->getTemplates() );
		}
	}

	public function testBomConcatenation() {
		$basePath = __DIR__ . '/../../data/css';
		$testModule = new ResourceLoaderFileTestModule( [
			'localBasePath' => $basePath,
			'styles' => [ 'bom.css' ],
		] );
		$testModule->setName( 'testing' );
		$this->assertEquals(
			"\xef\xbb\xbf.efbbbf",
			substr( file_get_contents( "$basePath/bom.css" ), 0, 10 ),
			'File has leading BOM'
		);

		$context = $this->getResourceLoaderContext();
		$this->assertEquals(
			[ 'all' => ".efbbbf_bom_char_at_start_of_file {}\n" ],
			$testModule->getStyles( $context ),
			'Leading BOM removed when concatenating files'
		);
	}

	public function testLessFileCompilation() {
		$context = $this->getResourceLoaderContext();
		$basePath = __DIR__ . '/../../data/less/module';
		$module = new ResourceLoaderFileTestModule( [
			'localBasePath' => $basePath,
			'styles' => [ 'styles.less' ],
			'lessVars' => [ 'foo' => '2px', 'Foo' => '#eeeeee' ]
		] );
		$module->setName( 'test.less' );
		$styles = $module->getStyles( $context );
		$this->assertStringEqualsFile( $basePath . '/styles.css', $styles['all'] );
	}

	public function provideGetVersionHash() {
		$a = [];
		$b = [
			'lessVars' => [ 'key' => 'value' ],
		];
		yield 'with and without Less variables' => [ $a, $b, false ];

		$a = [
			'lessVars' => [ 'key' => 'value1' ],
		];
		$b = [
			'lessVars' => [ 'key' => 'value2' ],
		];
		yield 'different Less variables' => [ $a, $b, false ];

		$x = [
			'lessVars' => [ 'key' => 'value' ],
		];
		yield 'identical Less variables' => [ $x, $x, true ];

		$a = [
			'packageFiles' => [ [ 'name' => 'data.json', 'callback' => static function () {
				return [ 'aaa' ];
			} ] ]
		];
		$b = [
			'packageFiles' => [ [ 'name' => 'data.json', 'callback' => static function () {
				return [ 'bbb' ];
			} ] ]
		];
		yield 'packageFiles with different callback' => [ $a, $b, false ];

		$a = [
			'packageFiles' => [ [ 'name' => 'aaa.json', 'callback' => static function () {
				return [ 'x' ];
			} ] ]
		];
		$b = [
			'packageFiles' => [ [ 'name' => 'bbb.json', 'callback' => static function () {
				return [ 'x' ];
			} ] ]
		];
		yield 'packageFiles with different file name and a callback' => [ $a, $b, false ];

		$a = [
			'packageFiles' => [ [ 'name' => 'data.json', 'versionCallback' => static function () {
				return [ 'A-version' ];
			}, 'callback' => static function () {
				throw new Exception( 'Unexpected computation' );
			} ] ]
		];
		$b = [
			'packageFiles' => [ [ 'name' => 'data.json', 'versionCallback' => static function () {
				return [ 'B-version' ];
			}, 'callback' => static function () {
				throw new Exception( 'Unexpected computation' );
			} ] ]
		];
		yield 'packageFiles with different versionCallback' => [ $a, $b, false ];

		$a = [
			'packageFiles' => [ [ 'name' => 'aaa.json',
				'versionCallback' => static function () {
					return [ 'X-version' ];
				},
				'callback' => static function () {
					throw new Exception( 'Unexpected computation' );
				}
			] ]
		];
		$b = [
			'packageFiles' => [ [ 'name' => 'bbb.json',
				'versionCallback' => static function () {
					return [ 'X-version' ];
				},
				'callback' => static function () {
					throw new Exception( 'Unexpected computation' );
				}
			] ]
		];
		yield 'packageFiles with different file name and a versionCallback' => [ $a, $b, false ];
	}

	/**
	 * @dataProvider provideGetVersionHash
	 */
	public function testGetVersionHash( $a, $b, $isEqual ) {
		$context = $this->getResourceLoaderContext( [ 'debug' => 'false' ] );

		$moduleA = new ResourceLoaderFileTestModule( $a );
		$moduleA->setConfig( $context->getResourceLoader()->getConfig() );
		$versionA = $moduleA->getVersionHash( $context );
		$moduleB = new ResourceLoaderFileTestModule( $b );
		$moduleB->setConfig( $context->getResourceLoader()->getConfig() );
		$versionB = $moduleB->getVersionHash( $context );

		$this->assertSame(
			$isEqual,
			( $versionA === $versionB ),
			'Whether versions hashes are equal'
		);
	}

	public function provideGetScriptPackageFiles() {
		$basePath = __DIR__ . '/../../data/resourceloader';
		$basePathB = __DIR__ . '/../../data/resourceloader-b';
		$base = [ 'localBasePath' => $basePath ];
		$commentScript = file_get_contents( "$basePath/script-comment.js" );
		$nosemiScript = file_get_contents( "$basePath/script-nosemi.js" );
		$nosemiBScript = file_get_contents( "$basePathB/script-nosemi.js" );
		$vueComponentDebug = trim( file_get_contents( "$basePath/vue-component-output-debug.js.txt" ) );
		$vueComponentNonDebug = trim( file_get_contents( "$basePath/vue-component-output-nondebug.js.txt" ) );
		$config = \MediaWiki\MediaWikiServices::getInstance()->getMainConfig();
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
			'package file with callback' => [
				$base + [
					'packageFiles' => [
						[ 'name' => 'foo.json', 'content' => [ 'Hello' => 'world' ] ],
						'sample.json',
						[ 'name' => 'bar.js', 'content' => "console.log('Hello');" ],
						[
							'name' => 'data.json',
							'callback' => static function ( $context, $config, $extra ) {
								return [ 'langCode' => $context->getLanguage(), 'extra' => $extra ];
							},
							'callbackParam' => [ 'a' => 'b' ],
						],
						[ 'name' => 'config.json', 'config' => [
							'Sitename',
							'server' => 'ServerName',
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
							'content' => [ 'langCode' => 'fy', 'extra' => [ 'a' => 'b' ] ],
						],
						'config.json' => [
							'type' => 'data',
							'content' => [
								'Sitename' => $config->get( 'Sitename' ),
								'server' => $config->get( 'ServerName' ),
							]
						]
					],
					'main' => 'bar.js'
				],
				[
					'lang' => 'fy'
				]
			],
			'package file with callback and versionCallback' => [
				$base + [
					'packageFiles' => [
						[ 'name' => 'bar.js', 'content' => "console.log('Hello');" ],
						[
							'name' => 'data.json',
							'versionCallback' => static function ( $context ) {
								return 'x';
							},
							'callback' => static function ( $context, $config, $extra ) {
								return [ 'langCode' => $context->getLanguage(), 'extra' => $extra ];
							},
							'callbackParam' => [ 'A', 'B' ]
						],
					]
				],
				[
					'files' => [
						'bar.js' => [
							'type' => 'script',
							'content' => "console.log('Hello');",
						],
						'data.json' => [
							'type' => 'data',
							'content' => [ 'langCode' => 'fy', 'extra' => [ 'A', 'B' ] ],
						],
					],
					'main' => 'bar.js'
				],
				[
					'lang' => 'fy'
				]
			],
			'package file with callback that returns a file (1)' => [
				$base + [
					'packageFiles' => [
						[ 'name' => 'dynamic.js', 'callback' => static function ( $context ) {
							$file = $context->getLanguage() === 'fy' ? 'script-comment.js' : 'script-nosemi.js';
							return new FilePath( $file );
						} ]
					]
				],
				[
					'files' => [
						'dynamic.js' => [
							'type' => 'script',
							'content' => $commentScript,
						]
					],
					'main' => 'dynamic.js'
				],
				[
					'lang' => 'fy'
				]
			],
			'package file with callback that returns a file (2)' => [
				$base + [
					'packageFiles' => [
						[ 'name' => 'dynamic.js', 'callback' => static function ( $context ) {
							$file = $context->getLanguage() === 'fy' ? 'script-comment.js' : 'script-nosemi.js';
							return new FilePath( $file );
						} ]
					]
				],
				[
					'files' => [
						'dynamic.js' => [
							'type' => 'script',
							'content' => $nosemiScript,
						]
					],
					'main' => 'dynamic.js'
				],
				[
					'lang' => 'nl'
				]
			],
			'package file with callback that returns a file with base path' => [
				$base + [
					'packageFiles' => [
						[ 'name' => 'dynamic.js', 'callback' => static function () use ( $basePathB ) {
							return new FilePath( 'script-nosemi.js', $basePathB );
						} ]
					]
				],
				[
					'files' => [
						'dynamic.js' => [
							'type' => 'script',
							'content' => $nosemiBScript,
						]
					],
					'main' => 'dynamic.js'
				]
			],
			'.vue file in debug mode' => [
				$base + [
					'packageFiles' => [
						'vue-component.vue'
					]
				],
				[
					'files' => [
						'vue-component.vue' => [
							'type' => 'script',
							'content' => $vueComponentDebug
						]
					],
					'main' => 'vue-component.vue',
				],
				[
					'debug' => 'true'
				]
			],
			'.vue file in non-debug mode' => [
				$base + [
					'packageFiles' => [
						'vue-component.vue'
					],
					'name' => 'nondebug',
				],
				[
					'files' => [
						'vue-component.vue' => [
							'type' => 'script',
							'content' => $vueComponentNonDebug
						]
					],
					'main' => 'vue-component.vue'
				],
				[
					'debug' => 'false'
				]
			],
			[
				$base + [
					'packageFiles' => [
						[ 'file' => 'script-comment.js' ]
					]
				],
				LogicException::class
			],
			'package file with invalid callback' => [
				$base + [
					'packageFiles' => [
						[ 'name' => 'foo.json', 'callback' => 'functionThatDoesNotExist142857' ]
					]
				],
				LogicException::class
			],
			[
				// 'config' not valid for 'script' type
				$base + [
					'packageFiles' => [
						'foo.json' => [ 'type' => 'script', 'config' => [ 'Sitename' ] ]
					]
				],
				LogicException::class
			],
			[
				// 'config' not valid for '*.js' file
				$base + [
					'packageFiles' => [
						[ 'name' => 'foo.js', 'config' => 'Sitename' ]
					]
				],
				LogicException::class
			],
			[
				// missing type/name/file.
				$base + [
					'packageFiles' => [
						'foo.js' => [ 'garbage' => 'data' ]
					]
				],
				LogicException::class
			],
			[
				$base + [
					'packageFiles' => [
						'filethatdoesnotexist142857.js'
					]
				],
				RuntimeException::class
			],
			[
				// JSON can't be a main file
				$base + [
					'packageFiles' => [
						'script-nosemi.js',
						[ 'name' => 'foo.json', 'content' => [ 'Hello' => 'world' ], 'main' => true ]
					]
				],
				LogicException::class
			]
		];
	}

	/**
	 * @dataProvider provideGetScriptPackageFiles
	 */
	public function testGetScriptPackageFiles( $moduleDefinition, $expected, $contextOptions = [] ) {
		$module = new FileModule( $moduleDefinition );
		$context = $this->getResourceLoaderContext( $contextOptions );
		$module->setConfig( $context->getResourceLoader()->getConfig() );
		if ( isset( $moduleDefinition['name'] ) ) {
			$module->setName( $moduleDefinition['name'] );
		}
		if ( is_string( $expected ) ) {
			// Class name of expected exception
			$this->expectException( $expected );
			$module->getScript( $context );
		} else {
			// Array of expected return value
			$this->assertEquals( $expected, $module->getScript( $context ) );
		}
	}

	public function testRequiresES6() {
		$module = new FileModule();
		$this->assertFalse( $module->requiresES6(), 'requiresES6 defaults to false' );
		$module = new FileModule( [ 'es6' => false ] );
		$this->assertFalse( $module->requiresES6(), 'requiresES6 is false when set to false' );
		$module = new FileModule( [ 'es6' => true ] );
		$this->assertTrue( $module->requiresES6(), 'requiresES6 is true when set to true' );
	}
}
