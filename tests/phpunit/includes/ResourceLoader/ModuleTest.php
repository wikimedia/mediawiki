<?php

namespace MediaWiki\Tests\ResourceLoader;

use LogicException;
use MediaWiki\MainConfigNames;
use MediaWiki\ResourceLoader\FileModule;
use MediaWiki\ResourceLoader\MessageBlobStore;
use MediaWiki\ResourceLoader\Module;
use MediaWiki\ResourceLoader\ResourceLoader;
use ReflectionMethod;

/**
 * @group ResourceLoader
 * @covers \MediaWiki\ResourceLoader\Module
 */
class ModuleTest extends ResourceLoaderTestCase {

	public function testGetVersionHash() {
		$context = $this->getResourceLoaderContext( [ 'debug' => 'false' ] );
		$msgBlobStore = $this->createMock( MessageBlobStore::class );
		$msgBlobStore->method( 'getBlob' )->willReturn( '{}' );
		$context->getResourceLoader()->setMessageBlobStore( $msgBlobStore );

		$baseParams = [
			'scripts' => [ 'foo.js', 'bar.js' ],
			'dependencies' => [ 'jquery', 'mediawiki' ],
			'messages' => [ 'hello', 'world' ],
		];

		$module = new FileModule( $baseParams );
		$module->setName( 'test' );
		$version = json_encode( $module->getVersionHash( $context ) );

		// Exactly the same
		$module = new FileModule( $baseParams );
		$module->setName( 'test' );
		$this->assertEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Instance is insignificant'
		);

		// Re-order dependencies
		$module = new FileModule( [
			'dependencies' => [ 'mediawiki', 'jquery' ],
		] + $baseParams );
		$module->setName( 'test' );
		$this->assertEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Order of dependencies is insignificant'
		);

		// Re-order messages
		$module = new FileModule( [
			'messages' => [ 'world', 'hello' ],
		] + $baseParams );
		$module->setName( 'test' );
		$this->assertEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Order of messages is insignificant'
		);

		// Re-order scripts
		$module = new FileModule( [
			'scripts' => [ 'bar.js', 'foo.js' ],
		] + $baseParams );
		$module->setName( 'test' );
		$this->assertNotEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Order of scripts is significant'
		);

		// Subclass
		$module = new ResourceLoaderFileModuleTestingSubclass( $baseParams );
		$module->setName( 'test' );
		$this->assertNotEquals(
			$version,
			json_encode( $module->getVersionHash( $context ) ),
			'Class is significant'
		);
	}

	public function testGetVersionHash_debug() {
		$module = new ResourceLoaderTestModule( [ 'script' => 'foo();' ] );
		$module->setName( 'test' );
		$context = $this->getResourceLoaderContext( [ 'debug' => 'true' ] );
		$this->assertSame( '', $module->getVersionHash( $context ) );
	}

	public function testGetVersionHash_length() {
		$context = $this->getResourceLoaderContext( [ 'debug' => 'false' ] );
		$module = new ResourceLoaderTestModule( [
			'script' => 'foo();'
		] );
		$module->setName( 'test' );
		$version = $module->getVersionHash( $context );
		$this->assertSame( ResourceLoader::HASH_LENGTH, strlen( $version ), 'Hash length' );
	}

	public function testGetVersionHash_parentDefinition() {
		$context = $this->getResourceLoaderContext( [ 'debug' => 'false' ] );
		$module = $this->getMockBuilder( Module::class )
			->onlyMethods( [ 'getDefinitionSummary' ] )->getMock();
		$module->method( 'getDefinitionSummary' )->willReturn( [ 'a' => 'summary' ] );
		$module->setName( 'test' );

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
			'styles' => '.foo { color: blue; }',
		] );
		$context = $this->getResourceLoaderContext( [ 'debug' => 'true' ] );
		$module->setConfig( $context->getResourceLoader()->getConfig() );
		$module->setName( 'test' );

		$this->assertEquals(
			[ 'all' => [
				'/w/load.php?debug=2&lang=en&modules=test&only=styles'
			] ],
			$module->getStyleURLsForDebug( $context ),
			'style urls debug=true'
		);

		$context = $this->getResourceLoaderContext( [ 'debug' => '2' ] );
		$this->assertEquals(
			[ 'all' => [
				'/w/load.php?debug=2&lang=en&modules=test&only=styles'
			] ],
			$module->getStyleURLsForDebug( $context ),
			'style urls debug=2'
		);
	}

	public static function provideValidateScripts() {
		yield 'valid ES5' => [ "\n'valid';" ];

		yield 'valid ES6/ES2015 for-of' => [
			"var x = ['a', 'b']; for (var key of x) { console.log(key); }"
		];

		yield 'valid ES2016 exponentiation' => [
			"var x = 2; var y = 3; console.log(x ** y);"
		];

		yield 'valid ES2017 async-await' => [
			"var foo = async function(x) { return await x.fetch(); }"
		];

		yield 'valid ES2018 spread in object literal' => [
			"var x = {b: 2, c: 3}; var y = {a: 1, ...x};",
			'Parse error: Unexpected: ... on line 1 in input.js'
		];

		yield 'SyntaxError' => [
			"var a = 'this is';\n {\ninvalid",
			'Parse error: Unclosed { on line 3 in input.js'
		];

		// If an implementation matches inputs using a regex with runaway backtracking,
		// then inputs with more than ~3072 repetitions are likely to fail (T299537).
		$input = '"' . str_repeat( 'x', 10000 ) . '";';
		yield 'double quote string 10K' => [ $input, ];
		$input = '\'' . str_repeat( 'x', 10000 ) . '\';';
		yield 'single quote string 10K' => [ $input ];
		$input = '"' . str_repeat( '\u0021', 100 ) . '";';
		yield 'escaping string 100' => [ $input ];
		$input = '"' . str_repeat( '\u0021', 10000 ) . '";';
		yield 'escaping string 10K' => [ $input ];
		$input = '/' . str_repeat( 'x', 1000 ) . '/;';
		yield 'regex 1K' => [ $input ];
		$input = '/' . str_repeat( 'x', 10000 ) . '/;';
		yield 'regex 10K' => [ $input ];
		$input = '/' . str_repeat( '\u0021', 100 ) . '/;';
		yield 'escaping regex 100' => [ $input ];
		$input = '/' . str_repeat( '\u0021', 10000 ) . '/;';
		yield 'escaping regex 10K' => [ $input ];
	}

	/**
	 * @dataProvider provideValidateScripts
	 */
	public function testValidateScriptFile( $input, $error = null ) {
		$this->overrideConfigValue( MainConfigNames::ResourceLoaderValidateJS, true );

		$context = $this->getResourceLoaderContext();

		$module = new ResourceLoaderTestModule( [
			'mayValidateScript' => true,
			'script' => $input
		] );
		$module->setConfig( $context->getResourceLoader()->getConfig() );

		$result = $module->getScript( $context );
		if ( $error ) {
			$this->assertStringContainsString( 'mw.log.error(', $result, 'log error' );
			$this->assertStringContainsString( $error, $result, 'error message' );
		} else {
			$this->assertEquals(
				$input,
				$module->getScript( $context ),
				'Leave valid scripts as-is'
			);
		}
	}

	public static function provideBuildContentScripts() {
		return [
			[
				"mw.foo()",
			],
			[
				"mw.foo();",
			],
			[
				"mw.foo();\n",
			],
			[
				"mw.foo()\n",
			],
			[
				"mw.foo()\n// mw.bar();",
			],
			[
				"mw.foo()\n// mw.bar()",
			],
			[
				"mw.foo()// mw.bar();",
			],
		];
	}

	/**
	 * @dataProvider provideBuildContentScripts
	 */
	public function testBuildContentScripts( $raw, $message = '' ) {
		$context = $this->getResourceLoaderContext();
		$module = new ResourceLoaderTestModule( [
			'script' => $raw
		] );
		$module->setName( 'test' );
		$this->assertEquals( $raw, $module->getScript( $context ), 'Raw script' );
		$this->assertEquals(
			[ 'plainScripts' => [ [ 'content' => $raw ] ] ],
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
		$module->setName( 'test' );
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
		$module->setName( 'test' );
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
