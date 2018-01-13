<?php

use MediaWiki\MediaWikiServices;

class ConfigFactoryTest extends MediaWikiTestCase {

	/**
	 * @covers ConfigFactory::register
	 */
	public function testRegister() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', 'GlobalVarConfig::newInstance' );
		$this->assertInstanceOf( GlobalVarConfig::class, $factory->makeConfig( 'unittest' ) );
	}

	/**
	 * @covers ConfigFactory::register
	 */
	public function testRegisterInvalid() {
		$factory = new ConfigFactory();
		$this->setExpectedException( InvalidArgumentException::class );
		$factory->register( 'invalid', 'Invalid callback' );
	}

	/**
	 * @covers ConfigFactory::register
	 */
	public function testRegisterInvalidInstance() {
		$factory = new ConfigFactory();
		$this->setExpectedException( InvalidArgumentException::class );
		$factory->register( 'invalidInstance', new stdClass );
	}

	/**
	 * @covers ConfigFactory::register
	 */
	public function testRegisterInstance() {
		$config = GlobalVarConfig::newInstance();
		$factory = new ConfigFactory();
		$factory->register( 'unittest', $config );
		$this->assertSame( $config, $factory->makeConfig( 'unittest' ) );
	}

	/**
	 * @covers ConfigFactory::register
	 */
	public function testRegisterAgain() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', 'GlobalVarConfig::newInstance' );
		$config1 = $factory->makeConfig( 'unittest' );

		$factory->register( 'unittest', 'GlobalVarConfig::newInstance' );
		$config2 = $factory->makeConfig( 'unittest' );

		$this->assertNotSame( $config1, $config2 );
	}

	/**
	 * @covers ConfigFactory::salvage
	 */
	public function testSalvage() {
		$oldFactory = new ConfigFactory();
		$oldFactory->register( 'foo', 'GlobalVarConfig::newInstance' );
		$oldFactory->register( 'bar', 'GlobalVarConfig::newInstance' );
		$oldFactory->register( 'quux', 'GlobalVarConfig::newInstance' );

		// instantiate two of the three defined configurations
		$foo = $oldFactory->makeConfig( 'foo' );
		$bar = $oldFactory->makeConfig( 'bar' );
		$quux = $oldFactory->makeConfig( 'quux' );

		// define new config instance
		$newFactory = new ConfigFactory();
		$newFactory->register( 'foo', 'GlobalVarConfig::newInstance' );
		$newFactory->register( 'bar', function () {
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
		$this->setExpectedException( ConfigException::class );
		$newFactory->makeConfig( 'quux' );
	}

	/**
	 * @covers ConfigFactory::getConfigNames
	 */
	public function testGetConfigNames() {
		$factory = new ConfigFactory();
		$factory->register( 'foo', 'GlobalVarConfig::newInstance' );
		$factory->register( 'bar', new HashConfig() );

		$this->assertEquals( [ 'foo', 'bar' ], $factory->getConfigNames() );
	}

	/**
	 * @covers ConfigFactory::makeConfig
	 */
	public function testMakeConfigWithCallback() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', 'GlobalVarConfig::newInstance' );

		$conf = $factory->makeConfig( 'unittest' );
		$this->assertInstanceOf( Config::class, $conf );
		$this->assertSame( $conf, $factory->makeConfig( 'unittest' ) );
	}

	/**
	 * @covers ConfigFactory::makeConfig
	 */
	public function testMakeConfigWithObject() {
		$factory = new ConfigFactory();
		$conf = new HashConfig();
		$factory->register( 'test', $conf );
		$this->assertSame( $conf, $factory->makeConfig( 'test' ) );
	}

	/**
	 * @covers ConfigFactory::makeConfig
	 */
	public function testMakeConfigFallback() {
		$factory = new ConfigFactory();
		$factory->register( '*', 'GlobalVarConfig::newInstance' );
		$conf = $factory->makeConfig( 'unittest' );
		$this->assertInstanceOf( Config::class, $conf );
	}

	/**
	 * @covers ConfigFactory::makeConfig
	 */
	public function testMakeConfigWithNoBuilders() {
		$factory = new ConfigFactory();
		$this->setExpectedException( ConfigException::class );
		$factory->makeConfig( 'nobuilderregistered' );
	}

	/**
	 * @covers ConfigFactory::makeConfig
	 */
	public function testMakeConfigWithInvalidCallback() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', function () {
			return true; // Not a Config object
		} );
		$this->setExpectedException( UnexpectedValueException::class );
		$factory->makeConfig( 'unittest' );
	}

	/**
	 * @covers ConfigFactory::getDefaultInstance
	 */
	public function testGetDefaultInstance() {
		// NOTE: the global config factory returned here has been overwritten
		// for operation in test mode. It may not reflect LocalSettings.
		$factory = MediaWikiServices::getInstance()->getConfigFactory();
		$this->assertInstanceOf( Config::class, $factory->makeConfig( 'main' ) );
	}

}
