<?php

class ResourceLoaderModuleTest extends MediaWikiTestCase {

	public function testDefinitionSummary() {
		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
		) );

		$hash = json_encode( $module->getDefinitionSummary() );

		// Exactly the same
		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
		) );

		$this->assertEquals(
			$hash,
			json_encode( $module->getDefinitionSummary() ),
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
			json_encode( $module->getDefinitionSummary() ),
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
			json_encode( $module->getDefinitionSummary() ),
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
			json_encode( $module->getDefinitionSummary() ),
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
			json_encode( $module->getDefinitionSummary() ),
			'Class is significant'
		);
	}
}

class ResourceLoaderFileModuleTestModule extends ResourceLoaderFileModule {}
