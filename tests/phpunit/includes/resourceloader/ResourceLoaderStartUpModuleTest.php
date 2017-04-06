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
mw.loader.addSource( {
    "local": "/w/load.php"
} );
mw.loader.register( [] );'
			] ],
			[ [
				'msg' => 'Basic registry',
				'modules' => [
					'test.blank' => new ResourceLoaderTestModule(),
				],
				'out' => '
mw.loader.addSource( {
    "local": "/w/load.php"
} );
mw.loader.register( [
    [
        "test.blank",
        "{blankVer}"
    ]
] );',
			] ],
			[ [
				'msg' => 'Omit raw modules from registry',
				'modules' => [
					'test.raw' => new ResourceLoaderTestModule( [ 'isRaw' => true ] ),
					'test.blank' => new ResourceLoaderTestModule(),
				],
				'out' => '
mw.loader.addSource( {
    "local": "/w/load.php"
} );
mw.loader.register( [
    [
        "test.blank",
        "{blankVer}"
    ]
] );',
			] ],
			[ [
				'msg' => 'Version falls back gracefully if getVersionHash throws',
				'modules' => [
					'test.fail' => (
						( $mock = $this->getMockBuilder( 'ResourceLoaderTestModule' )
							->setMethods( [ 'getVersionHash' ] )->getMock() )
						&& $mock->method( 'getVersionHash' )->will(
							$this->throwException( new Exception )
						)
					) ? $mock : $mock
				],
				'out' => '
mw.loader.addSource( {
    "local": "/w/load.php"
} );
mw.loader.register( [
    [
        "test.fail",
        ""
    ]
] );
mw.loader.state( {
    "test.fail": "error"
} );',
			] ],
			[ [
				'msg' => 'Use version from getVersionHash',
				'modules' => [
					'test.version' => (
						( $mock = $this->getMockBuilder( 'ResourceLoaderTestModule' )
							->setMethods( [ 'getVersionHash' ] )->getMock() )
						&& $mock->method( 'getVersionHash' )->willReturn( '1234567' )
					) ? $mock : $mock
				],
				'out' => '
mw.loader.addSource( {
    "local": "/w/load.php"
} );
mw.loader.register( [
    [
        "test.version",
        "1234567"
    ]
] );',
			] ],
			[ [
				'msg' => 'Re-hash version from getVersionHash if too long',
				'modules' => [
					'test.version' => (
						( $mock = $this->getMockBuilder( 'ResourceLoaderTestModule' )
							->setMethods( [ 'getVersionHash' ] )->getMock() )
						&& $mock->method( 'getVersionHash' )->willReturn( '12345678' )
					) ? $mock : $mock
				],
				'out' => '
mw.loader.addSource( {
    "local": "/w/load.php"
} );
mw.loader.register( [
    [
        "test.version",
        "016es8l"
    ]
] );',
			] ],
			[ [
				'msg' => 'Group signature',
				'modules' => [
					'test.blank' => new ResourceLoaderTestModule(),
					'test.group.foo' => new ResourceLoaderTestModule( [ 'group' => 'x-foo' ] ),
					'test.group.bar' => new ResourceLoaderTestModule( [ 'group' => 'x-bar' ] ),
				],
				'out' => '
mw.loader.addSource( {
    "local": "/w/load.php"
} );
mw.loader.register( [
    [
        "test.blank",
        "{blankVer}"
    ],
    [
        "test.group.foo",
        "{blankVer}",
        [],
        "x-foo"
    ],
    [
        "test.group.bar",
        "{blankVer}",
        [],
        "x-bar"
    ]
] );'
			] ],
			[ [
				'msg' => 'Different target (non-test should not be registered)',
				'modules' => [
					'test.blank' => new ResourceLoaderTestModule(),
					'test.target.foo' => new ResourceLoaderTestModule( [ 'targets' => [ 'x-foo' ] ] ),
				],
				'out' => '
mw.loader.addSource( {
    "local": "/w/load.php"
} );
mw.loader.register( [
    [
        "test.blank",
        "{blankVer}"
    ]
] );'
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
					'test.blank' => new ResourceLoaderTestModule( [ 'source' => 'example' ] ),
				],
				'out' => '
mw.loader.addSource( {
    "local": "/w/load.php",
    "example": "http://example.org/w/load.php"
} );
mw.loader.register( [
    [
        "test.blank",
        "{blankVer}",
        [],
        null,
        "example"
    ]
] );'
			] ],
			[ [
				'msg' => 'Conditional dependency function',
				'modules' => [
					'test.x.core' => new ResourceLoaderTestModule(),
					'test.x.polyfill' => new ResourceLoaderTestModule( [
						'skipFunction' => 'return true;'
					] ),
					'test.y.polyfill' => new ResourceLoaderTestModule( [
						'skipFunction' =>
							'return !!(' .
							'    window.JSON &&' .
							'    JSON.parse &&' .
							'    JSON.stringify' .
							');'
					] ),
					'test.z.foo' => new ResourceLoaderTestModule( [
						'dependencies' => [
							'test.x.core',
							'test.x.polyfill',
							'test.y.polyfill',
						],
					] ),
				],
				'out' => '
mw.loader.addSource( {
    "local": "/w/load.php"
} );
mw.loader.register( [
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
] );',
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
					'test.blank' => new ResourceLoaderTestModule(),
					'test.x.core' => new ResourceLoaderTestModule(),
					'test.x.util' => new ResourceLoaderTestModule( [
						'dependencies' => [
							'test.x.core',
						],
					] ),
					'test.x.foo' => new ResourceLoaderTestModule( [
						'dependencies' => [
							'test.x.core',
						],
					] ),
					'test.x.bar' => new ResourceLoaderTestModule( [
						'dependencies' => [
							'test.x.core',
							'test.x.util',
						],
					] ),
					'test.x.quux' => new ResourceLoaderTestModule( [
						'dependencies' => [
							'test.x.foo',
							'test.x.bar',
							'test.x.util',
							'test.x.unknown',
						],
					] ),
					'test.group.foo.1' => new ResourceLoaderTestModule( [
						'group' => 'x-foo',
					] ),
					'test.group.foo.2' => new ResourceLoaderTestModule( [
						'group' => 'x-foo',
					] ),
					'test.group.bar.1' => new ResourceLoaderTestModule( [
						'group' => 'x-bar',
					] ),
					'test.group.bar.2' => new ResourceLoaderTestModule( [
						'group' => 'x-bar',
						'source' => 'example',
					] ),
					'test.target.foo' => new ResourceLoaderTestModule( [
						'targets' => [ 'x-foo' ],
					] ),
					'test.target.bar' => new ResourceLoaderTestModule( [
						'source' => 'example',
						'targets' => [ 'x-foo' ],
					] ),
				],
				'out' => '
mw.loader.addSource( {
    "local": "/w/load.php",
    "example": "http://example.org/w/load.php"
} );
mw.loader.register( [
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
        "x-foo"
    ],
    [
        "test.group.foo.2",
        "{blankVer}",
        [],
        "x-foo"
    ],
    [
        "test.group.bar.1",
        "{blankVer}",
        [],
        "x-bar"
    ],
    [
        "test.group.bar.2",
        "{blankVer}",
        [],
        "x-bar",
        "example"
    ]
] );'
			] ],
		];
	}

	/**
	 * @dataProvider provideGetModuleRegistrations
	 * @covers ResourceLoaderStartUpModule::getModuleRegistrations
	 * @covers ResourceLoaderStartUpModule::compileUnresolvedDependencies
	 * @covers ResourceLoader::makeLoaderRegisterScript
	 */
	public function testGetModuleRegistrations( $case ) {
		if ( isset( $case['sources'] ) ) {
			$this->setMwGlobals( 'wgResourceLoaderSources', $case['sources'] );
		}

		$context = $this->getResourceLoaderContext();
		$rl = $context->getResourceLoader();
		$rl->register( $case['modules'] );
		$module = new ResourceLoaderStartUpModule();
		$out = ltrim( $case['out'], "\n" );

		$this->assertEquals(
			self::expandPlaceholders( $out ),
			$module->getModuleRegistrations( $context ),
			$case['msg']
		);
	}

	public static function provideRegistrations() {
		return [
			[ [
				'test.blank' => new ResourceLoaderTestModule(),
				'test.min' => new ResourceLoaderTestModule( [
					'skipFunction' =>
						'return !!(' .
						'    window.JSON &&' .
						'    JSON.parse &&' .
						'    JSON.stringify' .
						');',
					'dependencies' => [
						'test.blank',
					],
				] ),
			] ]
		];
	}
	/**
	 * @covers ResourceLoaderStartUpModule::getModuleRegistrations
	 * @dataProvider provideRegistrations
	 */
	public function testRegistrationsMinified( $modules ) {
		$this->setMwGlobals( 'wgResourceLoaderDebug', false );

		$context = $this->getResourceLoaderContext();
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
		$context = $this->getResourceLoaderContext();
		$rl = $context->getResourceLoader();
		$rl->register( $modules );
		$module = new ResourceLoaderStartUpModule();
		$out =
'mw.loader.addSource( {
    "local": "/w/load.php"
} );
mw.loader.register( [
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
] );';

		$this->assertEquals(
			self::expandPlaceholders( $out ),
			$module->getModuleRegistrations( $context ),
			'Unminified output'
		);
	}

}
