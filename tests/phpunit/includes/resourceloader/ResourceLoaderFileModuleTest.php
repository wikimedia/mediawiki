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

			'badFilePathModule' => array(
				'localBasePath' => '\/tmp\/',
				'templates' => array(
					'templates/template.html',
				),
			),

			'htmlTemplateModule' => $base + array(
				'templates' => array(
					'templates/template.html',
					'templates/template2.html',
				)
			),

			'templateModuleHandlebars' => $base + array(
				'templates' => array(
					'templates/template_awesome.handlebars',
				),
			),
		);
	}

	public function providerGetTemplateNames() {
		$modules = self::getModules();
		return array(
			array(
				$modules['noTemplateModule'], array(),
			),
			array(
				$modules['htmlTemplateModule'],
				array(
					'templates/template.html',
					'templates/template2.html',
				),
			)
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
					'templates/template_awesome.handlebars' => 'wow\n',
				),
			),
			array(
				$modules['htmlTemplateModule'],
				array(
					'templates/template.html' => 'hello\n',
					'templates/template2.html' => 'goodbye\n',
				),
			)
		);
	}

	public function providerGetModifiedTimeTemplates() {
		$modules = self::getModules();

		return array(
			// Check the default value when no templates present in module is 1
			array( $modules['noTemplateModule'], 1 ),
			// and also when the file paths are bad
			array( $modules['badFilePathModule'], 1 ),
		);
	}

	// tests

	/**
	 * @dataProvider providerGetTemplateNames
	 */
	public function testGetTemplateNames( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );
		$names = $rl->getTemplateNames();

		$this->assertEquals( $names, $expected );
	}

	/**
	 * @dataProvider providerGetTemplates
	 */
	public function testGetTemplates( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );
		$js = $rl->getTemplateScript();

		$this->assertEquals( $js, $expected );
	}

	/**
	 * @dataProvider providerGetModifiedTimeTemplates
	 */
	public function testGetModifiedTimeTemplates( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );
		$ts = $rl->getModifiedTimeTemplates( new ResourceLoaderContext(
			new ResourceLoader, new WebRequest() ) );
		$this->assertEquals( $ts, $expected );
	}
}
