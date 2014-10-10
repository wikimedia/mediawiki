<?php

/**
 * @group Mantle
 */
class ResourceLoaderFileModuleTest extends MediaWikiTestCase {
	private $modules = array(
	

		'templateModule' => array(
			'templates' => array(
				'templates/template.html', 'templates/template2.html',
			)
		),

		'dependenciesTemplatesModule' => array(
			'templates' => array( 'templates/foo' ),
			'dependencies' => array( 'dependency1', 'dependency2' )
		),

		'dependenciesModule' => array(
			'dependencies' => array( 'dependency1', 'dependency2' )
		),

		'templateModuleHandlebars' => array(
			'templates' => array(
				'templates/template_awesome.handlebars',
			),
		),
	);

	// providers
	public function providerGetMessages() {
		return array(
			array(
				$this->modules[0],
				array( 'foo', 'bar' ),
			),
			array(
				$this->modules[1],
				array( 'foo' ),
			),
			array(
				$this->modules[2],
				array( 'foo' ),
			),
		);
	}

	public function providerAddParsedMessages() {
		$msg = wfMessage( 'mobile-frontend-photo-license' )->parse();
		$expected = "\n" . Xml::encodeJsCall( 'mw.messages.set',
				array( 'mobile-frontend-photo-license', $msg ) );

		return array(
			// test case 1
			array(
				$this->modules[0],
				// expected value
				"\n"
			),
			// test case 2
			array(
				$this->modules[1],
				// expected value 2
				$expected
			),
			// test case 3
			array(
				$this->modules[2],
				// expected value 2
				"\n"
			),
		);
	}

	public function providerGetTemplateNames() {
		return array(
			array(
				$this->modules[0], array(),
			),
			array(
				$this->modules['templateModule'],
				array(
					'templates/template.html',
					'templates/template2.html',
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
		$module['localBasePath'] = $dir;
		$moduleHandlebars['localBasePath'] = $dir;

		return array(
			array(
				$this->modules[0], ''
			),
			array(
				$moduleHandlebars,
				'mw.mantle.template.add("templates/template_awesome.handlebars","wow\n");',
			),
			array(
				$module,
				'mw.mantle.template.add("templates/template.html","hello\n");' .
				'mw.mantle.template.add("templates/template2.html","goodbye\n");'
			)
		);
	}

	public function providerGetModifiedTimeTemplates() {
		$module = $this->modules['templateModule'];
		$module['localBasePath'] = '/tmp/templates';

		return array(
			// Check the default value when no templates present in module is 1
			array( $module, 1 ),
		);
	}

	// tests

	/**
	 * @dataProvider providerAddParsedMessages
	 */
	public function testAddParsedMessages( $module, $expectedJavascript ) {
		$rl = new ResourceLoaderFileModule( $module );
		$js = $rl->addParsedMessages();

		$this->assertEquals( $js, $expectedJavascript );
	}

	/**
	 * @dataProvider providerGetMessages
	 */
	public function testGetMessages( $module, $expectedMessages ) {
		$rl = new ResourceLoaderFileModule( $module );
		$msgs = $rl->getMessages();

		$this->assertEquals( $msgs, $expectedMessages );
	}

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
	public function testGetModifiedTimeTemplates( $module, $expected ) {
		$rl = new ResourceLoaderFileModule( $module );
		$ts = $rl->getModifiedTimeTemplates( new ResourceLoaderContext(
			new ResourceLoader, new WebRequest() ) );
		$this->assertEquals( $ts, $expected );
	}
}
