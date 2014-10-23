<?php

/**
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

	public static function getModules() {
		$base = array(
			'localBasePath' => realpath( dirname( __FILE__ ) ),
		);

		return array(
			'noTemplateModule' => array(),

			'htmlTemplateModule' => $base + array(
				'templates' => array(
					'templates/template.html',
					'templates/template2.html',
				)
			),

			'aliasedHtmlTemplateModule' => $base + array(
				'templates' => array(
					'foo.html' => 'templates/template.html',
					'bar.html' => 'templates/template2.html',
				)
			),

			'templateModuleHandlebars' => $base + array(
				'templates' => array(
					'templates/template_awesome.handlebars',
				),
			),
		);
	}

	public static function providerGetTemplates() {
		$modules = self::getModules();

		return array(
			array(
				$modules['noTemplateModule'],
				array(),
			),
			array(
				$modules['templateModuleHandlebars'],
				array(
					'templates/template_awesome.handlebars' => "wow\n",
				),
			),
			array(
				$modules['htmlTemplateModule'],
				array(
					'templates/template.html' => "<strong>hello</strong>\n",
					'templates/template2.html' => "<div>goodbye</div>\n",
				),
			),
			array(
				$modules['aliasedHtmlTemplateModule'],
				array(
					'foo.html' => "<strong>hello</strong>\n",
					'bar.html' => "<div>goodbye</div>\n",
				),
			),
		);
	}

	public static function providerGetModifiedTime() {
		$modules = self::getModules();

		return array(
			// Check the default value when no templates present in module is 1
			array( $modules['noTemplateModule'], 1 ),
		);
	}

	/**
	 * @covers ResourceLoaderFileModule::getAllSkinStyleFiles
	 */
	public function testGetAllSkinStyleFiles() {
		$baseParams = array(
			'scripts' => array(
				'foo.js',
				'bar.js',
			),
			'styles' => array(
				'foo.css',
				'bar.css' => array( 'media' => 'print' ),
				'screen.less' => array( 'media' => 'screen' ),
				'screen-query.css' => array( 'media' => 'screen and (min-width: 400px)' ),
			),
			'skinStyles' => array(
				'default' => 'quux-fallback.less',
				'fakeskin' => array(
					'baz-vector.css',
					'quux-vector.less',
				),
			),
			'messages' => array(
				'hello',
				'world',
			),
		);

		$module = new ResourceLoaderFileModule( $baseParams );

		$this->assertEquals(
			array(
				'foo.css',
				'baz-vector.css',
				'quux-vector.less',
				'quux-fallback.less',
				'bar.css',
				'screen.less',
				'screen-query.css',
			),
			array_map( 'basename', $module->getAllStyleFiles() )
		);
	}

	/**
	 * @dataProvider providerGetTemplates
	 * @covers ResourceLoaderFileModule::getTemplates
	 */
	public function testGetTemplates( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );

		$this->assertEquals( $rl->getTemplates(), $expected );
	}

	/**
	 * @dataProvider providerGetModifiedTime
	 * @covers ResourceLoaderFileModule::getModifiedTime
	 */
	public function testGetModifiedTime( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );
		$ts = $rl->getModifiedTime( new ResourceLoaderContext(
			new ResourceLoader, new FauxRequest() ) );
		$this->assertEquals( $ts, $expected );
	}
}
