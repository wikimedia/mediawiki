<?php

use MediaWiki\Config\Config;
use MediaWiki\Config\ConfigException;
use MediaWiki\Config\ConfigFactory;
use MediaWiki\Config\GlobalVarConfig;
use MediaWiki\Config\HashConfig;

/**
 * @covers \MediaWiki\Config\ConfigFactory
 */
class ConfigFactoryTest extends \MediaWikiIntegrationTestCase {

	public function testRegister() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', 'MediaWiki\\Config\\GlobalVarConfig::newInstance' );
		$this->assertInstanceOf( GlobalVarConfig::class, $factory->makeConfig( 'unittest' ) );
	}

	public function testRegisterInvalid() {
		$factory = new ConfigFactory();
		$this->expectException( InvalidArgumentException::class );
		$factory->register( 'invalid', 'Invalid callback' );
	}

	public function testRegisterInvalidInstance() {
		$factory = new ConfigFactory();
		$this->expectException( InvalidArgumentException::class );
		$factory->register( 'invalidInstance', (object)[] );
	}

	public function testRegisterInstance() {
		$config = GlobalVarConfig::newInstance();
		$factory = new ConfigFactory();
		$factory->register( 'unittest', $config );
		$this->assertSame( $config, $factory->makeConfig( 'unittest' ) );
	}

	public function testRegisterAgain() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', 'MediaWiki\\Config\\GlobalVarConfig::newInstance' );
		$config1 = $factory->makeConfig( 'unittest' );

		$factory->register( 'unittest', 'MediaWiki\\Config\\GlobalVarConfig::newInstance' );
		$config2 = $factory->makeConfig( 'unittest' );

		$this->assertNotSame( $config1, $config2 );
	}

	public function testSalvage() {
		$oldFactory = new ConfigFactory();
		$oldFactory->register( 'foo', 'MediaWiki\\Config\\GlobalVarConfig::newInstance' );
		$oldFactory->register( 'bar', 'MediaWiki\\Config\\GlobalVarConfig::newInstance' );
		$oldFactory->register( 'quux', 'MediaWiki\\Config\\GlobalVarConfig::newInstance' );

		// instantiate two of the three defined configurations
		$foo = $oldFactory->makeConfig( 'foo' );
		$bar = $oldFactory->makeConfig( 'bar' );
		$quux = $oldFactory->makeConfig( 'quux' );

		// define new config instance
		$newFactory = new ConfigFactory();
		$newFactory->register( 'foo', 'MediaWiki\\Config\\GlobalVarConfig::newInstance' );
		$newFactory->register( 'bar', static function () {
			return new HashConfig();
		} );

		// "foo" and "quux" are defined in the old and the new factory.
		// The old factory has instances for "foo" and "bar", but not "quux".
		$newFactory->salvage( $oldFactory );

		$newFoo = $newFactory->makeConfig( 'foo' );
		$this->assertSame( $foo, $newFoo, 'existing instance should be salvaged' );

		$newBar = $newFactory->makeConfig( 'bar' );
		$this->assertNotSame( $bar, $newBar, 'don\'t salvage if callbacks differ' );

		// the new factory doesn't have quux defined, so the quux instance should not be salvaged
		$this->expectException( ConfigException::class );
		$newFactory->makeConfig( 'quux' );
	}

	public function testGetConfigNames() {
		$factory = new ConfigFactory();
		$factory->register( 'foo', 'MediaWiki\\Config\\GlobalVarConfig::newInstance' );
		$factory->register( 'bar', new HashConfig() );

		$this->assertEquals( [ 'foo', 'bar' ], $factory->getConfigNames() );
	}

	public function testMakeConfigWithCallback() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', 'MediaWiki\\Config\\GlobalVarConfig::newInstance' );

		$conf = $factory->makeConfig( 'unittest' );
		$this->assertInstanceOf( Config::class, $conf );
		$this->assertSame( $conf, $factory->makeConfig( 'unittest' ) );
	}

	public function testMakeConfigWithObject() {
		$factory = new ConfigFactory();
		$conf = new HashConfig();
		$factory->register( 'test', $conf );
		$this->assertSame( $conf, $factory->makeConfig( 'test' ) );
	}

	public function testMakeConfigFallback() {
		$factory = new ConfigFactory();
		$factory->register( '*', 'MediaWiki\\Config\\GlobalVarConfig::newInstance' );
		$conf = $factory->makeConfig( 'unittest' );
		$this->assertInstanceOf( Config::class, $conf );
	}

	public function testMakeConfigWithNoBuilders() {
		$factory = new ConfigFactory();
		$this->expectException( ConfigException::class );
		$factory->makeConfig( 'nobuilderregistered' );
	}

	public function testMakeConfigWithInvalidCallback() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', static function () {
			return true; // Not a Config object
		} );
		$this->expectException( UnexpectedValueException::class );
		$factory->makeConfig( 'unittest' );
	}

	public function testGetDefaultInstance() {
		// NOTE: the global config factory returned here has been overwritten
		// for operation in test mode. It may not reflect LocalSettings.
		$factory = $this->getServiceContainer()->getConfigFactory();
		$this->assertInstanceOf( Config::class, $factory->makeConfig( 'main' ) );
	}

}
