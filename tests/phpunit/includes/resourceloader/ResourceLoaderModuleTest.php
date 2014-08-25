<?php

class ResourceLoaderModuleTest extends ResourceLoaderTestCase {

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

	/**
	 * @covers ResourceLoaderFileModule::getAllSkinStyleFiles
	 */
	public function testGetAllSkinStyleFiles() {
		$context = self::getResourceLoaderContext();

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
	 * @covers ResourceLoaderModule::getDefinitionSummary
	 * @covers ResourceLoaderFileModule::getDefinitionSummary
	 */
	public function testDefinitionSummary() {
		$context = self::getResourceLoaderContext();

		$baseParams = array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
		);

		$module = new ResourceLoaderFileModule( $baseParams );

		$jsonSummary = json_encode( $module->getDefinitionSummary( $context ) );

		// Exactly the same
		$module = new ResourceLoaderFileModule( $baseParams );

		$this->assertEquals(
			$jsonSummary,
			json_encode( $module->getDefinitionSummary( $context ) ),
			'Instance is insignificant'
		);

		// Re-order dependencies
		$module = new ResourceLoaderFileModule( array(
			'dependencies' => array( 'mediawiki', 'jquery' ),
		) + $baseParams );

		$this->assertEquals(
			$jsonSummary,
			json_encode( $module->getDefinitionSummary( $context ) ),
			'Order of dependencies is insignificant'
		);

		// Re-order messages
		$module = new ResourceLoaderFileModule( array(
			'messages' => array( 'world', 'hello' ),
		) + $baseParams );

		$this->assertEquals(
			$jsonSummary,
			json_encode( $module->getDefinitionSummary( $context ) ),
			'Order of messages is insignificant'
		);

		// Re-order scripts
		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'bar.js', 'foo.js' ),
		) + $baseParams );

		$this->assertNotEquals(
			$jsonSummary,
			json_encode( $module->getDefinitionSummary( $context ) ),
			'Order of scripts is significant'
		);

		// Subclass
		$module = new ResourceLoaderFileModuleTestModule( $baseParams );

		$this->assertNotEquals(
			$jsonSummary,
			json_encode( $module->getDefinitionSummary( $context ) ),
			'Class is significant'
		);
	}
}
