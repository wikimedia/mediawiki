<?php

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
		$this->setExpectedException( 'InvalidArgumentException' );
		$factory->register( 'invalid', 'Invalid callback' );
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
	 * @covers ConfigFactory::register
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
		$newFactory->register( 'bar', function() {
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
		$this->setExpectedException( 'ConfigException' );
		$newFactory->makeConfig( 'quux' );
	}

	/**
	 * @covers ConfigFactory::register
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
	public function testMakeConfig() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', 'GlobalVarConfig::newInstance' );

		$conf = $factory->makeConfig( 'unittest' );
		$this->assertInstanceOf( 'Config', $conf );
		$this->assertSame( $conf, $factory->makeConfig( 'unittest' ) );
	}

	/**
	 * @covers ConfigFactory::makeConfig
	 */
	public function testMakeConfigFallback() {
		$factory = new ConfigFactory();
		$factory->register( '*', 'GlobalVarConfig::newInstance' );
		$conf = $factory->makeConfig( 'unittest' );
		$this->assertInstanceOf( 'Config', $conf );
	}

	/**
	 * @covers ConfigFactory::makeConfig
	 */
	public function testMakeConfigWithNoBuilders() {
		$factory = new ConfigFactory();
		$this->setExpectedException( 'ConfigException' );
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
		$this->setExpectedException( 'UnexpectedValueException' );
		$factory->makeConfig( 'unittest' );
	}

	/**
	 * @covers ConfigFactory::getDefaultInstance
	 */
	public function testGetDefaultInstance() {
		// NOTE: the global config factory returned here has been overwritten
		// for operation in test mode. It may not reflect LocalSettings.
		$factory = ConfigFactory::getDefaultInstance();
		$this->assertInstanceOf( 'Config', $factory->makeConfig( 'main' ) );
	}

}
