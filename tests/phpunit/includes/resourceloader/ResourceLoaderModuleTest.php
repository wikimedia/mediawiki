<?php

class ResourceLoaderModuleTest extends ResourceLoaderTestCase {

	/**
	 * @covers ResourceLoaderModule::getVersionHash
	 */
	public function testGetVersionHash() {
		$context = $this->getResourceLoaderContext();

		$baseParams = array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
		);

		$module = new ResourceLoaderFileModule( $baseParams );
		$version = json_encode( $module->getVersionHash( $context ) );

		// Exactly the same
		$module = new ResourceLoaderFileModule( $baseParams );
		$this->assertEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Instance is insignificant'
		);

		// Re-order dependencies
		$module = new ResourceLoaderFileModule( array(
			'dependencies' => array( 'mediawiki', 'jquery' ),
		) + $baseParams );
		$this->assertEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Order of dependencies is insignificant'
		);

		// Re-order messages
		$module = new ResourceLoaderFileModule( array(
			'messages' => array( 'world', 'hello' ),
		) + $baseParams );
		$this->assertEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Order of messages is insignificant'
		);

		// Re-order scripts
		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'bar.js', 'foo.js' ),
		) + $baseParams );
		$this->assertNotEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Order of scripts is significant'
		);

		// Subclass
		$module = new ResourceLoaderFileModuleTestModule( $baseParams );
		$this->assertNotEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Class is significant'
		);
	}

	/**
	 * @covers ResourceLoaderModule::validateScriptFile
	 */
	public function testValidateScriptFile() {
		$context = $this->getResourceLoaderContext();

		$module = new ResourceLoaderTestModule( array(
			'script' => "var a = 'this is';\n {\ninvalid"
		) );
		$this->assertEquals(
			$module->getScript( $context ),
			'mw.log.error("JavaScript parse error: Parse error: Unexpected token; token } expected in file \'input\' on line 3");',
			'Replace invalid syntax with error logging'
		);

		$module = new ResourceLoaderTestModule( array(
			'script' => "\n'valid';"
		) );
		$this->assertEquals(
			$module->getScript( $context ),
			"\n'valid';",
			'Leave valid scripts as-is'
		);
	}
}
