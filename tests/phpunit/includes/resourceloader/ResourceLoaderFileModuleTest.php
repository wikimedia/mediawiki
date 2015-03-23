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

	private static function getModules() {
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

			'aliasFooFromBar' => $base + array(
				'templates' => array(
					'foo.foo' => 'templates/template.bar',
				),
			),
		);
	}

	public static function providerTemplateDependencies() {
		$modules = self::getModules();

		return array(
			array(
				$modules['noTemplateModule'],
				array(),
			),
			array(
				$modules['htmlTemplateModule'],
				array(
					'mediawiki.template',
				),
			),
			array(
				$modules['templateModuleHandlebars'],
				array(
					'mediawiki.template',
					'mediawiki.template.handlebars',
				),
			),
			array(
				$modules['aliasFooFromBar'],
				array(
					'mediawiki.template',
					'mediawiki.template.foo',
				),
			),
		);
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
	public function testMixedCssAnnotations(  ) {
		$basePath = __DIR__ . '/../../data/css';
		$testModule = new ResourceLoaderFileModule( array(
			'localBasePath' => $basePath,
			'styles' => array( 'test.css' ),
		) );
		$expectedModule = new ResourceLoaderFileModule( array(
			'localBasePath' => $basePath,
			'styles' => array( 'expected.css' ),
		) );

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

	/**
	 * @dataProvider providerGetTemplates
	 * @covers ResourceLoaderFileModule::getTemplates
	 */
	public function testGetTemplates( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );

		$this->assertEquals( $rl->getTemplates(), $expected );
	}

	public static function providerGetModifiedTime() {
		$modules = self::getModules();

		return array(
			// Check the default value when no templates present in module is 1
			array( $modules['noTemplateModule'], 1 ),
		);
	}

	/**
	 * @dataProvider providerGetModifiedTime
	 * @covers ResourceLoaderFileModule::getModifiedTime
	 */
	public function testGetModifiedTime( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );
		$ts = $rl->getModifiedTime( $this->getResourceLoaderContext() );
		$this->assertEquals( $ts, $expected );
	}
}
