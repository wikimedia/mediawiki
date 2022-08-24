<?php

namespace MediaWiki\Tests\ResourceLoader;

use LogicException;
use MediaWiki\MainConfigNames;
use MediaWiki\ResourceLoader\FileModule;
use MediaWiki\ResourceLoader\Module;
use MediaWiki\ResourceLoader\ResourceLoader;
use ReflectionMethod;
use ResourceLoaderFileModuleTestingSubclass;
use ResourceLoaderTestCase;
use ResourceLoaderTestModule;

/**
 * @covers \MediaWiki\ResourceLoader\Module
 */
class ModuleTest extends ResourceLoaderTestCase {

	public function testGetVersionHash() {
		$context = $this->getResourceLoaderContext( [ 'debug' => 'false' ] );

		$baseParams = [
			'scripts' => [ 'foo.js', 'bar.js' ],
			'dependencies' => [ 'jquery', 'mediawiki' ],
			'messages' => [ 'hello', 'world' ],
		];

		$module = new FileModule( $baseParams );
		$module->setName( "" );
		$version = json_encode( $module->getVersionHash( $context ) );

		// Exactly the same
		$module = new FileModule( $baseParams );
		$module->setName( "" );
		$this->assertEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Instance is insignificant'
		);

		// Re-order dependencies
		$module = new FileModule( [
			'dependencies' => [ 'mediawiki', 'jquery' ],
		] + $baseParams );
		$module->setName( "" );
		$this->assertEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Order of dependencies is insignificant'
		);

		// Re-order messages
		$module = new FileModule( [
			'messages' => [ 'world', 'hello' ],
		] + $baseParams );
		$module->setName( "" );
		$this->assertEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Order of messages is insignificant'
		);

		// Re-order scripts
		$module = new FileModule( [
			'scripts' => [ 'bar.js', 'foo.js' ],
		] + $baseParams );
		$module->setName( "" );
		$this->assertNotEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Order of scripts is significant'
		);

		// Subclass
		$module = new ResourceLoaderFileModuleTestingSubclass( $baseParams );
		$module->setName( "" );
		$this->assertNotEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Class is significant'
		);
	}

	public function testGetVersionHash_debug() {
		$module = new ResourceLoaderTestModule( [ 'script' => 'foo();' ] );
		$module->setName( "" );
		$context = $this->getResourceLoaderContext( [ 'debug' => 'true' ] );
		$this->assertSame( '', $module->getVersionHash( $context ) );
	}

	public function testGetVersionHash_length() {
		$context = $this->getResourceLoaderContext( [ 'debug' => 'false' ] );
		$module = new ResourceLoaderTestModule( [
			'script' => 'foo();'
		] );
		$module->setName( "" );
		$version = $module->getVersionHash( $context );
		$this->assertSame( ResourceLoader::HASH_LENGTH, strlen( $version ), 'Hash length' );
	}

	public function testGetVersionHash_parentDefinition() {
		$context = $this->getResourceLoaderContext( [ 'debug' => 'false' ] );
		$module = $this->getMockBuilder( Module::class )
			->onlyMethods( [ 'getDefinitionSummary' ] )->getMock();
		$module->method( 'getDefinitionSummary' )->willReturn( [ 'a' => 'summary' ] );
		$module->setName( "" );

		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( 'must call parent' );
		$module->getVersionHash( $context );
	}

	/**
	 * @covers \MediaWiki\ResourceLoader\Module
	 * @covers \MediaWiki\ResourceLoader\ResourceLoader
	 */
	public function testGetURLsForDebug() {
		$module = new ResourceLoaderTestModule( [
			'script' => 'foo();',
			'styles' => '.foo { color: blue; }',
		] );
		$context = $this->getResourceLoaderContext( [ 'debug' => 'true' ] );
		$module->setConfig( $context->getResourceLoader()->getConfig() );
		$module->setName( "" );

		$this->assertEquals(
			[
				'https://example.org/w/load.php?debug=1&lang=en&modules=&only=scripts'
			],
			$module->getScriptURLsForDebug( $context ),
			'script urls debug=true'
		);
		$this->assertEquals(
			[ 'all' => [
				'/w/load.php?debug=1&lang=en&modules=&only=styles'
			] ],
			$module->getStyleURLsForDebug( $context ),
			'style urls debug=true'
		);

		$context = $this->getResourceLoaderContext( [ 'debug' => '2' ] );
		$this->assertEquals(
			[
				'https://example.org/w/load.php?debug=2&lang=en&modules=&only=scripts'
			],
			$module->getScriptURLsForDebug( $context ),
			'script urls debug=2'
		);
		$this->assertEquals(
			[ 'all' => [
				'/w/load.php?debug=2&lang=en&modules=&only=styles'
			] ],
			$module->getStyleURLsForDebug( $context ),
			'style urls debug=2'
		);
	}

	public function testValidateScriptFile() {
		$this->overrideConfigValue( MainConfigNames::ResourceLoaderValidateJS, true );

		$context = $this->getResourceLoaderContext();

		$module = new ResourceLoaderTestModule( [
			'mayValidateScript' => true,
			'script' => "var a = 'this is';\n {\ninvalid"
		] );
		$module->setConfig( $context->getResourceLoader()->getConfig() );
		$this->assertEquals(
			'mw.log.error(' .
				'"JavaScript parse error (scripts need to be valid ECMAScript 5): ' .
				'Parse error: Unexpected token; token } expected in file \'input\' on line 3"' .
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

	public static function provideBuildContentScripts() {
		return [
			[
				"mw.foo()",
				"mw.foo()\n",
			],
			[
				"mw.foo();",
				"mw.foo();\n",
			],
			[
				"mw.foo();\n",
				"mw.foo();\n",
			],
			[
				"mw.foo()\n",
				"mw.foo()\n",
			],
			[
				"mw.foo()\n// mw.bar();",
				"mw.foo()\n// mw.bar();\n",
			],
			[
				"mw.foo()\n// mw.bar()",
				"mw.foo()\n// mw.bar()\n",
			],
			[
				"mw.foo()// mw.bar();",
				"mw.foo()// mw.bar();\n",
			],
		];
	}

	/**
	 * @dataProvider provideBuildContentScripts
	 */
	public function testBuildContentScripts( $raw, $build, $message = '' ) {
		$context = $this->getResourceLoaderContext();
		$module = new ResourceLoaderTestModule( [
			'script' => $raw
		] );
		$module->setName( "" );
		$this->assertEquals( $raw, $module->getScript( $context ), 'Raw script' );
		$this->assertEquals(
			$build,
			$module->getModuleContent( $context )[ 'scripts' ],
			$message
		);
	}

	public function testPlaceholderize() {
		$getRelativePaths = new ReflectionMethod( Module::class, 'getRelativePaths' );
		$getRelativePaths->setAccessible( true );
		$expandRelativePaths = new ReflectionMethod( Module::class, 'expandRelativePaths' );
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

	public function testGetHeaders() {
		$context = $this->getResourceLoaderContext();

		$module = new ResourceLoaderTestModule();
		$module->setName( "" );
		$this->assertSame( [], $module->getHeaders( $context ), 'Default' );

		$module = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->onlyMethods( [ 'getPreloadLinks' ] )->getMock();
		$module->method( 'getPreloadLinks' )->willReturn( [
			'https://example.org/script.js' => [ 'as' => 'script' ],
		] );
		$this->assertSame(
			[
				'Link: <https://example.org/script.js>;rel=preload;as=script'
			],
			$module->getHeaders( $context ),
			'Preload one resource'
		);

		$module = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->onlyMethods( [ 'getPreloadLinks' ] )->getMock();
		$module->method( 'getPreloadLinks' )->willReturn( [
			'https://example.org/script.js' => [ 'as' => 'script' ],
			'/example.png' => [ 'as' => 'image' ],
		] );
		$module->setName( "" );
		$this->assertSame(
			[
				'Link: <https://example.org/script.js>;rel=preload;as=script,' .
					'</example.png>;rel=preload;as=image'
			],
			$module->getHeaders( $context ),
			'Preload two resources'
		);
	}
}
