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
