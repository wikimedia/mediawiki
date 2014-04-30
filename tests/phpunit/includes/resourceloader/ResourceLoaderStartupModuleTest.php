<?php

class ResourceLoaderStartupModuleTest extends ResourceLoaderTestCase {

	public static function provideGetModuleRegistrations() {
		return array(
			array( array(
				'msg' => 'Empty registry',
				'modules' => array(),
				'out' => '
mw.loader.addSource( {
    "local": {
        "loadScript": "/w/load.php",
        "apiScript": "/w/api.php"
    }
} );mw.loader.register( [] );'
			) ),
			array( array(
				'msg' => 'Basic registry',
				'modules' => array(
					'test.blank' => new ResourceLoaderTestModule(),
				),
				'out' => '
mw.loader.addSource( {
    "local": {
        "loadScript": "/w/load.php",
        "apiScript": "/w/api.php"
    }
} );mw.loader.register( [
    [
        "test.blank",
        "1388534400"
    ]
] );',
			) ),
			array( array(
				'msg' => 'Group signature',
				'modules' => array(
					'test.blank' => new ResourceLoaderTestModule(),
					'test.group.foo' => new ResourceLoaderTestModule( array( 'group' => 'x-foo' ) ),
					'test.group.bar' => new ResourceLoaderTestModule( array( 'group' => 'x-bar' ) ),
				),
				'out' => '
mw.loader.addSource( {
    "local": {
        "loadScript": "/w/load.php",
        "apiScript": "/w/api.php"
    }
} );mw.loader.register( [
    [
        "test.blank",
        "1388534400"
    ],
    [
        "test.group.foo",
        "1388534400",
        [],
        "x-foo"
    ],
    [
        "test.group.bar",
        "1388534400",
        [],
        "x-bar"
    ]
] );'
			) ),
			array( array(
				'msg' => 'Different target (non-test should not be registered)',
				'modules' => array(
					'test.blank' => new ResourceLoaderTestModule(),
					'test.target.foo' => new ResourceLoaderTestModule( array( 'targets' => array( 'x-foo' ) ) ),
				),
				'out' => '
mw.loader.addSource( {
    "local": {
        "loadScript": "/w/load.php",
        "apiScript": "/w/api.php"
    }
} );mw.loader.register( [
    [
        "test.blank",
        "1388534400"
    ]
] );'
			) ),
			array( array(
				'msg' => 'Foreign source',
				'sources' => array(
					'example' => array(
						'loadScript' => 'http://example.org/w/load.php',
						'apiScript' => 'http://example.org/w/api.php',
					),
				),
				'modules' => array(
					'test.blank' => new ResourceLoaderTestModule( array( 'source' => 'example' ) ),
				),
				'out' => '
mw.loader.addSource( {
    "local": {
        "loadScript": "/w/load.php",
        "apiScript": "/w/api.php"
    },
    "example": {
        "loadScript": "http://example.org/w/load.php",
        "apiScript": "http://example.org/w/api.php"
    }
} );mw.loader.register( [
    [
        "test.blank",
        "1388534400",
        [],
        null,
        "example"
    ]
] );'
			) ),
			array( array(
				'msg' => 'Conditional dependency function',
				'modules' => array(
					'test.x.core' => new ResourceLoaderTestModule(),
					'test.x.polyfill' => new ResourceLoaderTestModule( array(
						'skipFunction' => 'return true;'
					) ),
					'test.y.polyfill' => new ResourceLoaderTestModule( array(
						'skipFunction' =>
							'return !!(' .
							'    window.JSON &&' .
							'    JSON.parse &&' .
							'    JSON.stringify' .
							');'
					) ),
					'test.z.foo' => new ResourceLoaderTestModule( array(
						'dependencies' => array(
							'test.x.core',
							'test.x.polyfil',
							'test.y.polyfil',
						),
					) ),
				),
				'out' => '
mw.loader.addSource( {
    "local": {
        "loadScript": "/w/load.php",
        "apiScript": "/w/api.php"
    }
} );mw.loader.register( [
    [
        "test.x.core",
        "1388534400"
    ],
    [
        "test.x.polyfill",
        "1388534400",
        [],
        null,
        "local",
        "return true;"
    ],
    [
        "test.y.polyfill",
        "1388534400",
        [],
        null,
        "local",
        "return !!(    window.JSON \u0026\u0026    JSON.parse \u0026\u0026    JSON.stringify);"
    ],
    [
        "test.z.foo",
        "1388534400",
        [
            "test.x.core",
            "test.x.polyfil",
            "test.y.polyfil"
        ]
    ]
] );',
			) ),
			array( array(
				// This may seem like an edge case, but a plain MediaWiki core install
				// with a few extensions installed is likely far more complex than this
				// even, not to mention an install like Wikipedia.
				// TODO: Make this even more realistic.
				'msg' => 'Advanced (everything combined)',
				'sources' => array(
					'example' => array(
						'loadScript' => 'http://example.org/w/load.php',
						'apiScript' => 'http://example.org/w/api.php',
					),
				),
				'modules' => array(
					'test.blank' => new ResourceLoaderTestModule(),
					'test.x.core' => new ResourceLoaderTestModule(),
					'test.x.util' => new ResourceLoaderTestModule( array(
						'dependencies' => array(
							'test.x.core',
						),
					) ),
					'test.x.foo' => new ResourceLoaderTestModule( array(
						'dependencies' => array(
							'test.x.core',
						),
					) ),
					'test.x.bar' => new ResourceLoaderTestModule( array(
						'dependencies' => array(
							'test.x.core',
							'test.x.util',
						),
					) ),
					'test.x.quux' => new ResourceLoaderTestModule( array(
						'dependencies' => array(
							'test.x.foo',
							'test.x.bar',
							'test.x.util',
							'test.x.unknown',
						),
					) ),
					'test.group.foo.1' => new ResourceLoaderTestModule( array(
						'group' => 'x-foo',
					) ),
					'test.group.foo.2' => new ResourceLoaderTestModule( array(
						'group' => 'x-foo',
					) ),
					'test.group.bar.1' => new ResourceLoaderTestModule( array(
						'group' => 'x-bar',
					) ),
					'test.group.bar.2' => new ResourceLoaderTestModule( array(
						'group' => 'x-bar',
						'source' => 'example',
					) ),
					'test.target.foo' => new ResourceLoaderTestModule( array(
						'targets' => array( 'x-foo' ),
					) ),
					'test.target.bar' => new ResourceLoaderTestModule( array(
						'source' => 'example',
						'targets' => array( 'x-foo' ),
					) ),
				),
				'out' => '
mw.loader.addSource( {
    "local": {
        "loadScript": "/w/load.php",
        "apiScript": "/w/api.php"
    },
    "example": {
        "loadScript": "http://example.org/w/load.php",
        "apiScript": "http://example.org/w/api.php"
    }
} );mw.loader.register( [
    [
        "test.blank",
        "1388534400"
    ],
    [
        "test.x.core",
        "1388534400"
    ],
    [
        "test.x.util",
        "1388534400",
        [
            "test.x.core"
        ]
    ],
    [
        "test.x.foo",
        "1388534400",
        [
            "test.x.core"
        ]
    ],
    [
        "test.x.bar",
        "1388534400",
        [
            "test.x.util"
        ]
    ],
    [
        "test.x.quux",
        "1388534400",
        [
            "test.x.foo",
            "test.x.bar",
            "test.x.unknown"
        ]
    ],
    [
        "test.group.foo.1",
        "1388534400",
        [],
        "x-foo"
    ],
    [
        "test.group.foo.2",
        "1388534400",
        [],
        "x-foo"
    ],
    [
        "test.group.bar.1",
        "1388534400",
        [],
        "x-bar"
    ],
    [
        "test.group.bar.2",
        "1388534400",
        [],
        "x-bar",
        "example"
    ]
] );'
			) ),
		);
	}

	/**
	 * @dataProvider provideGetModuleRegistrations
	 * @covers ResourceLoaderStartupModule::optimizeDependencies
	 * @covers ResourceLoaderStartUpModule::getModuleRegistrations
	 * @covers ResourceLoader::makeLoaderSourcesScript
	 * @covers ResourceLoader::makeLoaderRegisterScript
	 */
	public function testGetModuleRegistrations( $case ) {
		if ( isset( $case['sources'] ) ) {
			$this->setMwGlobals( 'wgResourceLoaderSources', $case['sources'] );
		}

		$context = self::getResourceLoaderContext();
		$rl = $context->getResourceLoader();

		$rl->register( $case['modules'] );

		$this->assertEquals(
			ltrim( $case['out'], "\n" ),
			ResourceLoaderStartUpModule::getModuleRegistrations( $context ),
			$case['msg']
		);
	}

    public static function provideRegistrations() {
        return array(
            array( array(
                'test.blank' => new ResourceLoaderTestModule(),
                'test.min' => new ResourceLoaderTestModule( array(
                    'skipFunction' =>
                        'return !!(' .
                        '    window.JSON &&' .
                        '    JSON.parse &&' .
                        '    JSON.stringify' .
                        ');',
                    'dependencies' => array(
                        'test.blank',
                    ),
                ) ),
            ) )
        );
    }
    /**
     * @dataProvider provideRegistrations
     */
    public function testRegistrationsMinified( $modules ) {
        $this->setMwGlobals( 'wgResourceLoaderDebug', false );

        $context = self::getResourceLoaderContext();
        $rl = $context->getResourceLoader();
        $rl->register( $modules );
        $this->assertEquals(
'mw.loader.addSource({"local":{"loadScript":"/w/load.php","apiScript":"/w/api.php"}});'
. 'mw.loader.register(['
. '["test.blank","1388534400"],'
. '["test.min","1388534400",["test.blank"],null,"local",'
. '"return!!(window.JSON\u0026\u0026JSON.parse\u0026\u0026JSON.stringify);"'
. ']]);',
            ResourceLoaderStartUpModule::getModuleRegistrations( $context ),
            'Minified output'
        );
    }

    /**
     * @dataProvider provideRegistrations
     */
    public function testRegistrationsUnminified( $modules ) {
        $context = self::getResourceLoaderContext();
        $rl = $context->getResourceLoader();
        $rl->register( $modules );
        $this->assertEquals(
'mw.loader.addSource( {
    "local": {
        "loadScript": "/w/load.php",
        "apiScript": "/w/api.php"
    }
} );mw.loader.register( [
    [
        "test.blank",
        "1388534400"
    ],
    [
        "test.min",
        "1388534400",
        [
            "test.blank"
        ],
        null,
        "local",
        "return !!(    window.JSON \u0026\u0026    JSON.parse \u0026\u0026    JSON.stringify);"
    ]
] );',
            ResourceLoaderStartUpModule::getModuleRegistrations( $context ),
            'Unminified output'
        );
    }

}
