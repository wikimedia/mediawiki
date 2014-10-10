<?php

/**
 * @group ResourceLoader
 */
class ResourceLoaderFileModuleTest extends MediaWikiTestCase {
	static function getModules() {
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

	public function providerGetTemplates() {
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

	public function providerGetModifiedTime() {
		$modules = self::getModules();

		return array(
			// Check the default value when no templates present in module is 1
			array( $modules['noTemplateModule'], 1 ),
		);
	}

	// tests
	/**
	 * @dataProvider providerGetTemplates
	 */
	public function testGetTemplates( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );

		$this->assertEquals( $rl->getTemplates(), $expected );
	}

	/**
	 * @dataProvider providerGetModifiedTime
	 */
	public function testGetModifiedTime( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );
		$ts = $rl->getModifiedTime( new ResourceLoaderContext(
			new ResourceLoader, new WebRequest() ) );
		$this->assertEquals( $ts, $expected );
	}
}
