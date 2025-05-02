<?php

namespace MediaWiki\Tests\ResourceLoader;

use Exception;
use MediaWiki\ResourceLoader\Module;
use MediaWiki\ResourceLoader\StartUpModule;
use Psr\Log\NullLogger;

/**
 * @group ResourceLoader
 * @covers \MediaWiki\ResourceLoader\StartUpModule
 */
class StartUpModuleTest extends ResourceLoaderTestCase {

	protected static function expandPlaceholders( $text ) {
		return strtr( $text, [
			'{blankVer}' => self::BLANK_VERSION
		] );
	}

	public static function provideGetModuleRegistrations() {
		return [
			[ [
				'msg' => 'Empty registry',
				'modules' => [],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([]);'
			] ],
			[ [
				'msg' => 'Basic registry',
				'modules' => [
					'test.blank' => [ 'class' => ResourceLoaderTestModule::class ],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        ""
    ]
]);',
			] ],
			[ [
				'msg' => 'Optimise the dependency tree (basic case)',
				'modules' => [
					'a' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [ 'b', 'c', 'd' ],
					],
					'b' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [ 'c' ],
					],
					'c' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [],
					],
					'd' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [],
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "a",
        "",
        [
            1,
            3
        ]
    ],
    [
        "b",
        "",
        [
            2
        ]
    ],
    [
        "c",
        ""
    ],
    [
        "d",
        ""
    ]
]);',
			] ],
			[ [
				'msg' => 'Optimise the dependency tree (tolerate unknown deps)',
				'modules' => [
					'a' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [ 'b', 'c', 'x' ]
					],
					'b' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [ 'c', 'x' ]
					],
					'c' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => []
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "a",
        "",
        [
            1,
            "x"
        ]
    ],
    [
        "b",
        "",
        [
            2,
            "x"
        ]
    ],
    [
        "c",
        ""
    ]
]);',
			] ],
			[ [
				// Regression test for T223402.
				'msg' => 'Optimise the dependency tree (indirect circular dependency)',
				'modules' => [
					'top' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [ 'middle1', 'util' ],
					],
					'middle1' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [ 'middle2', 'util' ],
					],
					'middle2' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [ 'bottom' ],
					],
					'bottom' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [ 'top' ],
					],
					'util' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [],
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "top",
        "",
        [
            1,
            4
        ]
    ],
    [
        "middle1",
        "",
        [
            2,
            4
        ]
    ],
    [
        "middle2",
        "",
        [
            3
        ]
    ],
    [
        "bottom",
        "",
        [
            0
        ]
    ],
    [
        "util",
        ""
    ]
]);',
			] ],
			[ [
				// Regression test for T223402.
				'msg' => 'Optimise the dependency tree (direct circular dependency)',
				'modules' => [
					'top' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [ 'util', 'top' ],
					],
					'util' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [],
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "top",
        "",
        [
            1,
            0
        ]
    ],
    [
        "util",
        ""
    ]
]);',
			] ],
			[ [
				'msg' => 'Group signature',
				'modules' => [
					'test.blank' => [ 'class' => ResourceLoaderTestModule::class ],
					'test.group.foo' => [
						'class' => ResourceLoaderTestModule::class,
						'group' => 'x-foo',
					],
					'test.group.bar' => [
						'class' => ResourceLoaderTestModule::class,
						'group' => 'x-bar',
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        ""
    ],
    [
        "test.group.foo",
        "",
        [],
        2
    ],
    [
        "test.group.bar",
        "",
        [],
        3
    ]
]);'
			] ],
			[ [
				'msg' => 'Different skin (irrelevant skin modules should not be registered)',
				'modules' => [
					'test.blank' => [ 'class' => ResourceLoaderTestModule::class ],
					'test.skin.fallback' => [
						'class' => ResourceLoaderTestModule::class,
						'skins' => [ 'fallback' ],
					],
					'test.skin.foo' => [
						'class' => ResourceLoaderTestModule::class,
						'skins' => [ 'foo' ],
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        ""
    ],
    [
        "test.skin.fallback",
        ""
    ]
]);'
			] ],
			[ [
				'msg' => 'Safemode disabled (default; register all modules)',
				'modules' => [
					// Default origin: ORIGIN_CORE_SITEWIDE
					'test.blank' => [ 'class' => ResourceLoaderTestModule::class ],
					'test.core-generated' => [
						'class' => ResourceLoaderTestModule::class,
						'origin' => Module::ORIGIN_CORE_INDIVIDUAL
					],
					'test.sitewide' => [
						'class' => ResourceLoaderTestModule::class,
						'origin' => Module::ORIGIN_USER_SITEWIDE
					],
					'test.user' => [
						'class' => ResourceLoaderTestModule::class,
						'origin' => Module::ORIGIN_USER_INDIVIDUAL
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        ""
    ],
    [
        "test.core-generated",
        ""
    ],
    [
        "test.sitewide",
        ""
    ],
    [
        "test.user",
        ""
    ]
]);'
			] ],
			[ [
				'msg' => 'Safemode enabled (filter modules with user/site origin)',
				'extraQuery' => [ 'safemode' => '1' ],
				'modules' => [
					// Default origin: ORIGIN_CORE_SITEWIDE
					'test.blank' => [ 'class' => ResourceLoaderTestModule::class ],
					'test.core-generated' => [
						'class' => ResourceLoaderTestModule::class,
						'origin' => Module::ORIGIN_CORE_INDIVIDUAL
					],
					'test.sitewide' => [
						'class' => ResourceLoaderTestModule::class,
						'origin' => Module::ORIGIN_USER_SITEWIDE
					],
					'test.user' => [
						'class' => ResourceLoaderTestModule::class,
						'origin' => Module::ORIGIN_USER_INDIVIDUAL
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        ""
    ],
    [
        "test.core-generated",
        ""
    ]
]);'
			] ],
			[ [
				'msg' => 'Foreign source',
				'sources' => [
					'example' => [
						'loadScript' => 'http://example.org/w/load.php',
						'apiScript' => 'http://example.org/w/api.php',
					],
				],
				'modules' => [
					'test.blank' => [
						'class' => ResourceLoaderTestModule::class,
						'source' => 'example'
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php",
    "example": "http://example.org/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        "",
        [],
        null,
        "example"
    ]
]);'
			] ],
			[ [
				'msg' => 'Conditional dependency function',
				'modules' => [
					'test.x.core' => [ 'class' => ResourceLoaderTestModule::class ],
					'test.x.polyfill' => [
						'class' => ResourceLoaderTestModule::class,
						'skipFunction' => 'return true;'
					],
					'test.y.polyfill' => [
						'class' => ResourceLoaderTestModule::class,
						'skipFunction' =>
							'return !!(' .
							'    window.JSON &&' .
							'    JSON.parse &&' .
							'    JSON.stringify' .
							');'
					],
					'test.z.foo' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [
							'test.x.core',
							'test.x.polyfill',
							'test.y.polyfill',
						],
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.x.core",
        ""
    ],
    [
        "test.x.polyfill",
        "",
        [],
        null,
        null,
        "return true;"
    ],
    [
        "test.y.polyfill",
        "",
        [],
        null,
        null,
        "return !!(    window.JSON \u0026\u0026    JSON.parse \u0026\u0026    JSON.stringify);"
    ],
    [
        "test.z.foo",
        "",
        [
            0,
            1,
            2
        ]
    ]
]);',
			] ],
			[ [
				'msg' => 'ES6-only module',
				'modules' => [
					'test.es6' => [
						'class' => ResourceLoaderTestModule::class,
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.es6",
        ""
    ]
]);',
			] ],
			[ [
				'msg' => 'noscript group omitted (T291735)',
				'modules' => [
					'test.not-noscript' => [
						'class' => ResourceLoaderTestModule::class,
					],
					'test.noscript' => [
						'class' => ResourceLoaderTestModule::class,
						'group' => 'noscript',
					],
					'test.also-noscript' => [
						'class' => ResourceLoaderTestModule::class,
						'group' => 'noscript',
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.not-noscript",
        ""
    ]
]);',
			] ],
			[ [
				// This may seem like an edge case, but a plain MediaWiki core install
				// with a few extensions installed is likely far more complex than this
				// even, not to mention an install like Wikipedia.
				// TODO: Make this even more realistic.
				'msg' => 'Advanced (everything combined)',
				'sources' => [
					'example' => [
						'loadScript' => 'http://example.org/w/load.php',
						'apiScript' => 'http://example.org/w/api.php',
					],
				],
				'modules' => [
					'test.blank' => [ 'class' => ResourceLoaderTestModule::class ],
					'test.x.core' => [ 'class' => ResourceLoaderTestModule::class ],
					'test.x.util' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [
							'test.x.core',
						],
					],
					'test.x.foo' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [
							'test.x.core',
						],
					],
					'test.x.bar' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [
							'test.x.core',
							'test.x.util',
						],
					],
					'test.x.quux' => [
						'class' => ResourceLoaderTestModule::class,
						'dependencies' => [
							'test.x.foo',
							'test.x.bar',
							'test.x.util',
							'test.x.unknown',
						],
					],
					'test.group.foo.1' => [
						'class' => ResourceLoaderTestModule::class,
						'group' => 'x-foo',
					],
					'test.group.foo.2' => [
						'class' => ResourceLoaderTestModule::class,
						'group' => 'x-foo',
					],
					'test.group.bar.1' => [
						'class' => ResourceLoaderTestModule::class,
						'group' => 'x-bar',
					],
					'test.group.bar.2' => [
						'class' => ResourceLoaderTestModule::class,
						'group' => 'x-bar',
						'source' => 'example',
					],
					'test.es6' => [
						'class' => ResourceLoaderTestModule::class,
					]
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php",
    "example": "http://example.org/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        ""
    ],
    [
        "test.x.core",
        ""
    ],
    [
        "test.x.util",
        "",
        [
            1
        ]
    ],
    [
        "test.x.foo",
        "",
        [
            1
        ]
    ],
    [
        "test.x.bar",
        "",
        [
            2
        ]
    ],
    [
        "test.x.quux",
        "",
        [
            3,
            4,
            "test.x.unknown"
        ]
    ],
    [
        "test.group.foo.1",
        "",
        [],
        2
    ],
    [
        "test.group.foo.2",
        "",
        [],
        2
    ],
    [
        "test.group.bar.1",
        "",
        [],
        3
    ],
    [
        "test.group.bar.2",
        "",
        [],
        3,
        "example"
    ],
    [
        "test.es6",
        ""
    ]
]);'
			] ],
		];
	}

	/**
	 * @dataProvider provideGetModuleRegistrations
	 */
	public function testGetModuleRegistrations( $case ) {
		$this->clearHook( 'ResourceLoaderModifyEmbeddedSourceUrls' );

		$extraQuery = $case['extraQuery'] ?? [];
		$context = $this->getResourceLoaderContext( $extraQuery );
		$rl = $context->getResourceLoader();
		if ( isset( $case['sources'] ) ) {
			$rl->addSource( $case['sources'] );
		}
		$rl->register( $case['modules'] );
		$module = new StartUpModule();
		$module->setConfig( $rl->getConfig() );
		$module->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$out = ltrim( $case['out'], "\n" );

		// Disable log from getModuleRegistrations via MWExceptionHandler
		// for case where getVersionHash() is expected to throw.
		$this->setLogger( 'exception', new NullLogger() );

		$this->assertEquals(
			self::expandPlaceholders( $out ),
			$module->getModuleRegistrations( $context ),
			$case['msg']
		);
	}

	/**
	 * These test cases test behaviour that are specific to production mode.
	 *
	 * @see provideGetModuleRegistrations
	 */
	public static function provideGetModuleRegistrationsProduction() {
		yield 'Version falls back gracefully if getModuleContent throws' => [ [
			'modules' => [
				'test.fail' => [
					'moduleContent' => 'throw',
				]
			],
			'out' => 'mw.loader.addSource({"local":"/w/load.php"});' . "\n"
				. 'mw.loader.register([["test.fail",""]]);' . "\n"
				. 'mw.loader.state({"test.fail":"error"});',
		] ];
		yield 'Version falls back gracefully if getDefinitionSummary throws' => [ [
			'modules' => [
				'test.fail' => [
					'definitionSummary' => 'throw',
				]
			],
			'out' => 'mw.loader.addSource({"local":"/w/load.php"});' . "\n"
				. 'mw.loader.register([["test.fail",""]]);' . "\n"
				. 'mw.loader.state({"test.fail":"error"});',
		] ];
	}

	/**
	 * @dataProvider provideGetModuleRegistrationsProduction
	 */
	public function testGetModuleRegistrationsProduction( array $case ) {
		$this->clearHook( 'ResourceLoaderModifyEmbeddedSourceUrls' );

		foreach ( $case['modules'] as $module => &$definition ) {
			$mockBuilder = $this->getMockBuilder( ResourceLoaderTestModule::class );
			if ( isset( $definition['moduleContent'] ) ) {
				$mock = $mockBuilder->onlyMethods( [ 'getModuleContent' ] )->getMock();
				$mock->method( 'getModuleContent' )->willThrowException( new Exception );
			} elseif ( isset( $definition['definitionSummary'] ) ) {
				$mock = $mockBuilder->onlyMethods( [
						'enableModuleContentVersion',
						'getDefinitionSummary'
					] )
					->getMock();
				$mock->method( 'enableModuleContentVersion' )->willReturn( false );
				$mock->method( 'getDefinitionSummary' )->willThrowException( new Exception );
			}
			$definition = [
				'factory' => static function () use ( $mock ) {
					return $mock;
				},
			];
		}

		$context = $this->getResourceLoaderContext( [ 'debug' => 'false' ] );
		$rl = $context->getResourceLoader();
		$rl->register( $case['modules'] );
		$module = new StartUpModule();
		$module->setConfig( $rl->getConfig() );
		$module->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$out = ltrim( $case['out'], "\n" );

		// Tolerate exception logs for cases that expect getVersionHash() to throw.
		$this->setLogger( 'exception', new NullLogger() );

		$this->assertEquals(
			self::expandPlaceholders( $out ),
			$module->getModuleRegistrations( $context )
		);
	}

	public function testGetModuleRegistrations_hook() {
		$this->clearHook( 'ResourceLoaderModifyEmbeddedSourceUrls' );
		$this->setTemporaryHook( 'ResourceLoaderModifyEmbeddedSourceUrls', function ( &$urls ) {
			$urlUtils = $this->getServiceContainer()->getUrlUtils();
			$urls['local'] = $urlUtils->expand( $urls['local'] );
		} );

		$context = $this->getResourceLoaderContext();
		$rl = $context->getResourceLoader();
		$module = new StartUpModule();
		$module->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$module->setConfig( $rl->getConfig() );
		$out = 'mw.loader.addSource({
    "local": "https://example.org/w/load.php"
});
mw.loader.register([]);';
		$this->assertEquals(
			$out,
			$module->getModuleRegistrations( $context )
		);
	}

	public static function provideRegistrations() {
		return [
			[ [
				'test.blank' => [ 'class' => ResourceLoaderTestModule::class ],
				'test.min' => [
					'class' => ResourceLoaderTestModule::class,
					'skipFunction' =>
						'return !!(' .
						'    window.JSON &&' .
						'    JSON.parse &&' .
						'    JSON.stringify' .
						');',
					'dependencies' => [
						'test.blank',
					],
				],
			] ]
		];
	}

	/**
	 * @dataProvider provideRegistrations
	 */
	public function testRegistrationsMinified( $modules ) {
		$this->clearHook( 'ResourceLoaderModifyEmbeddedSourceUrls' );

		$context = $this->getResourceLoaderContext( [
			'debug' => 'false',
		] );
		$rl = $context->getResourceLoader();
		$rl->register( $modules );
		$module = new StartUpModule();
		$module->setConfig( $rl->getConfig() );
		$module->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$out = 'mw.loader.addSource({"local":"/w/load.php"});' . "\n"
		. 'mw.loader.register(['
		. '["test.blank","{blankVer}"],'
		. '["test.min","{blankVer}",[0],null,null,'
		. '"return!!(window.JSON\u0026\u0026JSON.parse\u0026\u0026JSON.stringify);"'
		. ']]);';

		$this->assertEquals(
			self::expandPlaceholders( $out ),
			$module->getModuleRegistrations( $context ),
			'Minified output'
		);
	}

	/**
	 * @dataProvider provideRegistrations
	 */
	public function testRegistrationsUnminified( $modules ) {
		$this->clearHook( 'ResourceLoaderModifyEmbeddedSourceUrls' );

		$context = $this->getResourceLoaderContext( [
			'debug' => 'true',
		] );
		$rl = $context->getResourceLoader();
		$rl->register( $modules );
		$module = new StartUpModule();
		$module->setConfig( $rl->getConfig() );
		$module->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$out =
'mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        ""
    ],
    [
        "test.min",
        "",
        [
            0
        ],
        null,
        null,
        "return !!(    window.JSON \u0026\u0026    JSON.parse \u0026\u0026    JSON.stringify);"
    ]
]);';

		$this->assertEquals(
			self::expandPlaceholders( $out ),
			$module->getModuleRegistrations( $context ),
			'Unminified output'
		);
	}

	public function testGetVersionHash_varyConfig() {
		$this->clearHook( 'ResourceLoaderModifyEmbeddedSourceUrls' );
		$context = $this->getResourceLoaderContext();

		$module = new StartUpModule();
		$module->setConfig( $context->getResourceLoader()->getConfig() );
		$version1 = $module->getVersionHash( $context );

		$module = new StartUpModule();
		$module->setConfig( $context->getResourceLoader()->getConfig() );
		$module->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$version2 = $module->getVersionHash( $context );

		$this->assertEquals(
			$version1,
			$version2,
			'Deterministic version hash'
		);
	}

	public function testGetVersionHash_varyModule() {
		$this->clearHook( 'ResourceLoaderModifyEmbeddedSourceUrls' );

		$context1 = $this->getResourceLoaderContext( [
			'debug' => 'false',
		] );
		$rl1 = $context1->getResourceLoader();
		$rl1->register( [
			'test.a' => [ 'class' => ResourceLoaderTestModule::class ],
			'test.b' => [ 'class' => ResourceLoaderTestModule::class ],
		] );
		$module = new StartUpModule();
		$module->setConfig( $rl1->getConfig() );
		$module->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$module->setName( 'test' );
		$version1 = $module->getVersionHash( $context1 );

		$context2 = $this->getResourceLoaderContext();
		$rl2 = $context2->getResourceLoader();
		$rl2->register( [
			'test.b' => [ 'class' => ResourceLoaderTestModule::class ],
			'test.c' => [ 'class' => ResourceLoaderTestModule::class ],
		] );
		$module = new StartUpModule();
		$module->setConfig( $rl2->getConfig() );
		$module->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$module->setName( 'test' );
		$version2 = $module->getVersionHash( $context2 );

		$context3 = $this->getResourceLoaderContext();
		$rl3 = $context3->getResourceLoader();
		$rl3->register( [
			'test.a' => [ 'class' => ResourceLoaderTestModule::class ],
			'test.b' => [
				'class' => ResourceLoaderTestModule::class,
				'script' => 'different',
			],
		] );
		$module = new StartUpModule();
		$module->setConfig( $rl3->getConfig() );
		$module->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$module->setName( 'test' );
		$version3 = $module->getVersionHash( $context3 );

		// Module name *is* significant (T201686)
		$this->assertNotEquals(
			$version1,
			$version2,
			'Module name is significant'
		);

		$this->assertNotEquals(
			$version1,
			$version3,
			'Hash change of any module impacts startup hash'
		);
	}

	public function testGetVersionHash_varyDeps() {
		$this->clearHook( 'ResourceLoaderModifyEmbeddedSourceUrls' );

		$context = $this->getResourceLoaderContext( [ 'debug' => 'false' ] );
		$rl = $context->getResourceLoader();
		$rl->register( [
			'test.a' => [
				'class' => ResourceLoaderTestModule::class,
				'dependencies' => [ 'x', 'y' ],
			],
		] );
		$module = new StartUpModule();
		$module->setConfig( $rl->getConfig() );
		$module->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$module->setName( 'test' );
		$version1 = $module->getVersionHash( $context );

		$context = $this->getResourceLoaderContext();
		$rl = $context->getResourceLoader();
		$rl->register( [
			'test.a' => [
				'class' => ResourceLoaderTestModule::class,
				'dependencies' => [ 'x', 'z' ],
			],
		] );
		$module = new StartUpModule();
		$module->setConfig( $rl->getConfig() );
		$module->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$module->setName( 'test' );
		$version2 = $module->getVersionHash( $context );

		// Dependencies *are* significant (T201686)
		$this->assertNotEquals(
			$version1,
			$version2,
			'Dependencies are significant'
		);
	}

}
