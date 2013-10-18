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

	public function testDefinitionSummary() {
		$context = self::getResourceLoaderContext();

		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
		) );

		$hash = json_encode( $module->getDefinitionSummary( $context ) );

		// Exactly the same
		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
		) );

		$this->assertEquals(
			$hash,
			json_encode( $module->getDefinitionSummary( $context ) ),
			'Instance is insignificant'
		);

		// Re-order dependencies
		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
		) );

		$this->assertEquals(
			$hash,
			json_encode( $module->getDefinitionSummary( $context ) ),
			'Order of dependencies is insignificant'
		);

		// Re-order messages
		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'world', 'hello' ),
		) );

		$this->assertEquals(
			$hash,
			json_encode( $module->getDefinitionSummary( $context ) ),
			'Order of messages is insignificant'
		);

		// Re-order scripts
		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'bar.js', 'foo.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
		) );

		$this->assertNotEquals(
			$hash,
			json_encode( $module->getDefinitionSummary( $context ) ),
			'Order of scripts is significant'
		);

		// Subclass
		$module = new ResourceLoaderFileModuleTestModule( array(
			'scripts' => array( 'bar.js', 'foo.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
		) );

		$this->assertNotEquals(
			$hash,
			json_encode( $module->getDefinitionSummary( $context ) ),
			'Class is significant'
		);
	}
}

class ResourceLoaderFileModuleTestModule extends ResourceLoaderFileModule {}
