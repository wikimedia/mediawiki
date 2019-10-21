<?php

class ResourceLoaderStartUpModuleTest extends ResourceLoaderTestCase {

	protected static function expandPlaceholders( $text ) {
		return strtr( $text, [
			'{blankVer}' => self::BLANK_VERSION
		] );
	}

	public function provideGetModuleRegistrations() {
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
        "{blankVer}"
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
        "{blankVer}",
        [
            1,
            3
        ]
    ],
    [
        "b",
        "{blankVer}",
        [
            2
        ]
    ],
    [
        "c",
        "{blankVer}"
    ],
    [
        "d",
        "{blankVer}"
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
        "{blankVer}",
        [
            1,
            "x"
        ]
    ],
    [
        "b",
        "{blankVer}",
        [
            2,
            "x"
        ]
    ],
    [
        "c",
        "{blankVer}"
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
        "{blankVer}",
        [
            1,
            4
        ]
    ],
    [
        "middle1",
        "{blankVer}",
        [
            2,
            4
        ]
    ],
    [
        "middle2",
        "{blankVer}",
        [
            3
        ]
    ],
    [
        "bottom",
        "{blankVer}",
        [
            0
        ]
    ],
    [
        "util",
        "{blankVer}"
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
        "{blankVer}",
        [
            1,
            0
        ]
    ],
    [
        "util",
        "{blankVer}"
    ]
]);',
			] ],
			[ [
				'msg' => 'Version falls back gracefully if getVersionHash throws',
				'modules' => [
					'test.fail' => [
						'factory' => function () {
							$mock = $this->getMockBuilder( ResourceLoaderTestModule::class )
								->setMethods( [ 'getVersionHash' ] )->getMock();
							$mock->method( 'getVersionHash' )->will(
								$this->throwException( new Exception )
							);
							return $mock;
						}
					]
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.fail",
        ""
    ]
]);
mw.loader.state({
    "test.fail": "error"
});',
			] ],
			[ [
				'msg' => 'Use version from getVersionHash',
				'modules' => [
					'test.version' => [
						'factory' => function () {
							$mock = $this->getMockBuilder( ResourceLoaderTestModule::class )
								->setMethods( [ 'getVersionHash' ] )->getMock();
							$mock->method( 'getVersionHash' )->willReturn( '12345' );
							return $mock;
						}
					]
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.version",
        "12345"
    ]
]);',
			] ],
			[ [
				'msg' => 'Re-hash version from getVersionHash if too long',
				'modules' => [
					'test.version' => [
						'factory' => function () {
							$mock = $this->getMockBuilder( ResourceLoaderTestModule::class )
								->setMethods( [ 'getVersionHash' ] )->getMock();
							$mock->method( 'getVersionHash' )->willReturn( '12345678' );
							return $mock;
						}
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.version",
        "16es8"
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
        "{blankVer}"
    ],
    [
        "test.group.foo",
        "{blankVer}",
        [],
        2
    ],
    [
        "test.group.bar",
        "{blankVer}",
        [],
        3
    ]
]);'
			] ],
			[ [
				'msg' => 'Different target (non-test should not be registered)',
				'modules' => [
					'test.blank' => [ 'class' => ResourceLoaderTestModule::class ],
					'test.target.foo' => [
						'class' => ResourceLoaderTestModule::class,
						'targets' => [ 'x-foo' ],
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        "{blankVer}"
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
						'origin' => ResourceLoaderModule::ORIGIN_CORE_INDIVIDUAL
					],
					'test.sitewide' => [
						'class' => ResourceLoaderTestModule::class,
						'origin' => ResourceLoaderModule::ORIGIN_USER_SITEWIDE
					],
					'test.user' => [
						'class' => ResourceLoaderTestModule::class,
						'origin' => ResourceLoaderModule::ORIGIN_USER_INDIVIDUAL
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        "{blankVer}"
    ],
    [
        "test.core-generated",
        "{blankVer}"
    ],
    [
        "test.sitewide",
        "{blankVer}"
    ],
    [
        "test.user",
        "{blankVer}"
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
						'origin' => ResourceLoaderModule::ORIGIN_CORE_INDIVIDUAL
					],
					'test.sitewide' => [
						'class' => ResourceLoaderTestModule::class,
						'origin' => ResourceLoaderModule::ORIGIN_USER_SITEWIDE
					],
					'test.user' => [
						'class' => ResourceLoaderTestModule::class,
						'origin' => ResourceLoaderModule::ORIGIN_USER_INDIVIDUAL
					],
				],
				'out' => '
mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        "{blankVer}"
    ],
    [
        "test.core-generated",
        "{blankVer}"
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
        "{blankVer}",
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
        "{blankVer}"
    ],
    [
        "test.x.polyfill",
        "{blankVer}",
        [],
        null,
        null,
        "return true;"
    ],
    [
        "test.y.polyfill",
        "{blankVer}",
        [],
        null,
        null,
        "return !!(    window.JSON \u0026\u0026    JSON.parse \u0026\u0026    JSON.stringify);"
    ],
    [
        "test.z.foo",
        "{blankVer}",
        [
            0,
            1,
            2
        ]
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
					'test.target.foo' => [
						'class' => ResourceLoaderTestModule::class,
						'targets' => [ 'x-foo' ],
					],
					'test.target.bar' => [
						'class' => ResourceLoaderTestModule::class,
						'source' => 'example',
						'targets' => [ 'x-foo' ],
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
        "{blankVer}"
    ],
    [
        "test.x.core",
        "{blankVer}"
    ],
    [
        "test.x.util",
        "{blankVer}",
        [
            1
        ]
    ],
    [
        "test.x.foo",
        "{blankVer}",
        [
            1
        ]
    ],
    [
        "test.x.bar",
        "{blankVer}",
        [
            2
        ]
    ],
    [
        "test.x.quux",
        "{blankVer}",
        [
            3,
            4,
            "test.x.unknown"
        ]
    ],
    [
        "test.group.foo.1",
        "{blankVer}",
        [],
        2
    ],
    [
        "test.group.foo.2",
        "{blankVer}",
        [],
        2
    ],
    [
        "test.group.bar.1",
        "{blankVer}",
        [],
        3
    ],
    [
        "test.group.bar.2",
        "{blankVer}",
        [],
        3,
        "example"
    ]
]);'
			] ],
		];
	}

	/**
	 * @dataProvider provideGetModuleRegistrations
	 * @covers ResourceLoaderStartUpModule
	 * @covers ResourceLoader::makeLoaderRegisterScript
	 */
	public function testGetModuleRegistrations( $case ) {
		$extraQuery = $case['extraQuery'] ?? [];
		$context = $this->getResourceLoaderContext( $extraQuery );
		$rl = $context->getResourceLoader();
		if ( isset( $case['sources'] ) ) {
			$rl->addSource( $case['sources'] );
		}
		$rl->register( $case['modules'] );
		$module = new ResourceLoaderStartUpModule();
		$out = ltrim( $case['out'], "\n" );

		// Disable log from getModuleRegistrations via MWExceptionHandler
		// for case where getVersionHash() is expected to throw.
		$this->setLogger( 'exception', new Psr\Log\NullLogger() );

		$this->assertEquals(
			self::expandPlaceholders( $out ),
			$module->getModuleRegistrations( $context ),
			$case['msg']
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
	 * @covers ResourceLoaderStartUpModule::getModuleRegistrations
	 * @dataProvider provideRegistrations
	 */
	public function testRegistrationsMinified( $modules ) {
		$context = $this->getResourceLoaderContext( [
			'debug' => 'false',
		] );
		$rl = $context->getResourceLoader();
		$rl->register( $modules );
		$module = new ResourceLoaderStartUpModule();
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
	 * @covers ResourceLoaderStartUpModule::getModuleRegistrations
	 * @dataProvider provideRegistrations
	 */
	public function testRegistrationsUnminified( $modules ) {
		$context = $this->getResourceLoaderContext( [
			'debug' => 'true',
		] );
		$rl = $context->getResourceLoader();
		$rl->register( $modules );
		$module = new ResourceLoaderStartUpModule();
		$out =
'mw.loader.addSource({
    "local": "/w/load.php"
});
mw.loader.register([
    [
        "test.blank",
        "{blankVer}"
    ],
    [
        "test.min",
        "{blankVer}",
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

	/**
	 * @covers ResourceLoaderStartupModule::getDefinitionSummary
	 */
	public function testGetVersionHash_varyConfig() {
		$context = $this->getResourceLoaderContext();

		$this->setMwGlobals( 'wgArticlePath', '/w1' );
		$module = new ResourceLoaderStartupModule();
		$version1 = $module->getVersionHash( $context );
		$module = new ResourceLoaderStartupModule();
		$version2 = $module->getVersionHash( $context );

		$this->setMwGlobals( 'wgArticlePath', '/w3' );
		$module = new ResourceLoaderStartupModule();
		$version3 = $module->getVersionHash( $context );

		$this->assertEquals(
			$version1,
			$version2,
			'Deterministic version hash'
		);

		$this->assertNotEquals(
			$version1,
			$version3,
			'Config change impacts version hash'
		);
	}

	/**
	 * @covers ResourceLoaderStartupModule
	 */
	public function testGetVersionHash_varyModule() {
		$context1 = $this->getResourceLoaderContext();
		$rl1 = $context1->getResourceLoader();
		$rl1->register( [
			'test.a' => [ 'class' => ResourceLoaderTestModule::class ],
			'test.b' => [ 'class' => ResourceLoaderTestModule::class ],
		] );
		$module = new ResourceLoaderStartupModule();
		$version1 = $module->getVersionHash( $context1 );

		$context2 = $this->getResourceLoaderContext();
		$rl2 = $context2->getResourceLoader();
		$rl2->register( [
			'test.b' => [ 'class' => ResourceLoaderTestModule::class ],
			'test.c' => [ 'class' => ResourceLoaderTestModule::class ],
		] );
		$module = new ResourceLoaderStartupModule();
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
		$module = new ResourceLoaderStartupModule();
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

	/**
	 * @covers ResourceLoaderStartupModule
	 */
	public function testGetVersionHash_varyDeps() {
		$context = $this->getResourceLoaderContext();
		$rl = $context->getResourceLoader();
		$rl->register( [
			'test.a' => [
				'class' => ResourceLoaderTestModule::class,
				'dependencies' => [ 'x', 'y' ],
			],
		] );
		$module = new ResourceLoaderStartupModule();
		$version1 = $module->getVersionHash( $context );

		$context = $this->getResourceLoaderContext();
		$rl = $context->getResourceLoader();
		$rl->register( [
			'test.a' => [
				'class' => ResourceLoaderTestModule::class,
				'dependencies' => [ 'x', 'z' ],
			],
		] );
		$module = new ResourceLoaderStartupModule();
		$version2 = $module->getVersionHash( $context );

		// Dependencies *are* significant (T201686)
		$this->assertNotEquals(
			$version1,
			$version2,
			'Dependencies are significant'
		);
	}

}
