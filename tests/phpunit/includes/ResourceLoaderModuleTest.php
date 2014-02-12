<?php

class ResourceLoaderModuleTest extends MediaWikiTestCase {

	protected static function getResourceLoaderContext() {
		$resourceLoader = new ResourceLoader();
		$request = new FauxRequest( array(
				'debug' => 'false',
				'lang' => 'en',
				'modules' => 'startup',
				'only' => 'scripts',
				'skin' => 'vector',
		) );
		return new ResourceLoaderContext( $resourceLoader, $request );
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

	/**
	 */
	public function testF() {
		$context = self::getResourceLoaderContext();

		$params = array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
			'styles' => array(
				'foo.css',
			),
			'skinStyles' => array(
				'vector' => array(
					'foo-vector.css',
					'foo-vector-hd.css' => array(
						'media' => 'screen and (min-width: 982px)'
					),
				),
				'monobook' => array(
					'foo-monobook.css',
				),
				'default' => 'foo-other.css'
			),
		);
		$module = new ResourceLoaderFileModuleTestModule( $params );


		$this->assertEquals(
			$module->test_getStyleFiles( $context ),
			array(
				'all' => array(
					'foo.css',
					'foo-vector.css',
				),
				'screen and (min-width: 982px)' => array(
					'foo-vector-hd.css',
				),
			),
			'Style files'
		);
		$module->getModifiedTime( $context );
		$this->assertEquals(
			$module->test_getModifiedTime_files( $context ),
			array(
				'foo.css',
				'foo-vector.css',
				'foo-vector-hd.css',
				'foo.js',
				'bar.js',
			),
			'Files'
		);
	}
}

class ResourceLoaderFileModuleTestModule extends ResourceLoaderFileModule {

	// Make public
	public function test_getStyleFiles( $context ) {
		return $this->getStyleFiles( $context );
	}

	public function test_getModifiedTime_files() {
		return $this->_modifiedTime_files;
	}
}
