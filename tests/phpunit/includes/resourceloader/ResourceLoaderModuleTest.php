<?php

class ResourceLoaderModuleTest extends ResourceLoaderTestCase {

	/**
	 * @covers ResourceLoaderModule::getVersionHash
	 */
	public function testGetVersionHash() {
		$context = $this->getResourceLoaderContext();

		$baseParams = [
			'scripts' => [ 'foo.js', 'bar.js' ],
			'dependencies' => [ 'jquery', 'mediawiki' ],
			'messages' => [ 'hello', 'world' ],
		];

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
		$module = new ResourceLoaderFileModule( [
			'dependencies' => [ 'mediawiki', 'jquery' ],
		] + $baseParams );
		$this->assertEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Order of dependencies is insignificant'
		);

		// Re-order messages
		$module = new ResourceLoaderFileModule( [
			'messages' => [ 'world', 'hello' ],
		] + $baseParams );
		$this->assertEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Order of messages is insignificant'
		);

		// Re-order scripts
		$module = new ResourceLoaderFileModule( [
			'scripts' => [ 'bar.js', 'foo.js' ],
		] + $baseParams );
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
		$this->setMwGlobals( 'wgResourceLoaderValidateJS', true );

		$context = $this->getResourceLoaderContext();

		$module = new ResourceLoaderTestModule( [
			'script' => "var a = 'this is';\n {\ninvalid"
		] );
		$this->assertEquals(
			'mw.log.error(' .
				'"JavaScript parse error: Parse error: Unexpected token; ' .
				'token } expected in file \'input\' on line 3"' .
			');',
			$module->getScript( $context ),
			'Replace invalid syntax with error logging'
		);

		$module = new ResourceLoaderTestModule( [
			'script' => "\n'valid';"
		] );
		$this->assertEquals(
			"\n'valid';",
			$module->getScript( $context ),
			'Leave valid scripts as-is'
		);
	}

	/**
	 * @covers ResourceLoaderModule::getRelativePaths
	 * @covers ResourceLoaderModule::expandRelativePaths
	 */
	public function testPlaceholderize() {
		$getRelativePaths = new ReflectionMethod( 'ResourceLoaderModule', 'getRelativePaths' );
		$getRelativePaths->setAccessible( true );
		$expandRelativePaths = new ReflectionMethod( 'ResourceLoaderModule', 'expandRelativePaths' );
		$expandRelativePaths->setAccessible( true );

		$this->setMwGlobals( [
			'IP' => '/srv/example/mediawiki/core',
		] );
		$raw = [
				'/srv/example/mediawiki/core/resources/foo.js',
				'/srv/example/mediawiki/core/extensions/Example/modules/bar.js',
				'/srv/example/mediawiki/skins/Example/baz.css',
				'/srv/example/mediawiki/skins/Example/images/quux.png',
		];
		$canonical = [
				'resources/foo.js',
				'extensions/Example/modules/bar.js',
				'../skins/Example/baz.css',
				'../skins/Example/images/quux.png',
		];
		$this->assertEquals(
			$canonical,
			$getRelativePaths->invoke( null, $raw ),
			'Insert placeholders'
		);
		$this->assertEquals(
			$raw,
			$expandRelativePaths->invoke( null, $canonical ),
			'Substitute placeholders'
		);
	}
}
