<?php

/**
 * @group Mantle
 */
class ResourceLoaderFileModuleTest extends MediaWikiTestCase {
	private $modules = array(
		'templateModule' => array(
			'templates' => array(
				'template.html', 'template2.html',
			)
		),

		'dependenciesTemplatesModule' => array(
			'templates' => array( 'foo' ),
			'dependencies' => array( 'dependency1', 'dependency2' )
		),

		'dependenciesModule' => array(
			'dependencies' => array( 'dependency1', 'dependency2' )
		),

		'templateModuleHandlebars' => array(
			'templates' => array(
				'template_awesome.handlebars',
			),
		),
	);

	public function providerGetTemplateNames() {
		return array(
			array(
				$this->modules[0], array(),
			),
			array(
				$this->modules['templateModule'],
				array(
					'template.html',
					'template2.html',
				),
			)
		);
	}

	/**
	 * @FIXME update template tests
	 */
	public function providerGetTemplateScript() {
		$module = $this->modules['templateModule'];
		$moduleHandlebars = $this->modules['templateModuleHandlebars'];
		$dir = realpath( dirname( __FILE__ ) . '/templates/' );
		$module['localTemplateBasePath'] = $dir;
		$moduleHandlebars['localTemplateBasePath'] = $dir;

		return array(
			array(
				$this->modules[0], ''
			),
			array(
				$moduleHandlebars,
				'mw.mantle.template.add("template_awesome.handlebars","wow\n");',
			),
			array(
				$module,
				'mw.mantle.template.add("template.html","hello\n");' .
				'mw.mantle.template.add("template2.html","goodbye\n");'
			)
		);
	}

	public function providerGetModifiedTimeTemplates() {
		$module = $this->modules['templateModule'];
		$module['localTemplateBasePath'] = '/tmp/templates';

		return array(
			// Check the default value when no templates present in module is 1
			array( $module, 1 ),
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
	 * @dataProvider providerGetTemplateScript
	 */
	public function testGetTemplateScript( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );
		$js = $rl->getTemplateScript();

		$this->assertEquals( $js, $expected );
	}

	/**
	 * @dataProvider providerGetModifiedTimeTemplates
	 */
	public function testGetModifiedTime( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );
		$ts = $rl->getModifiedTime( new ResourceLoaderContext(
			new ResourceLoader, new WebRequest() ) );
		$this->assertEquals( $ts, $expected );
	}
}
