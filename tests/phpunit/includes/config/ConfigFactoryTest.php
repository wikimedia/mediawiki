<?php

class ConfigFactoryTest extends MediaWikiTestCase {

	public function tearDown() {
		// Reset this since we mess with it a bit
		ConfigFactory::destroyDefaultInstance();
		parent::tearDown();
	}

	/**
	 * @covers ConfigFactory::register
	 */
	public function testRegister() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', 'GlobalVarConfig::newInstance' );
		$this->assertTrue( true ); // No exception thrown
		$this->setExpectedException( 'InvalidArgumentException' );
		$factory->register( 'invalid', 'Invalid callback' );
	}

	/**
	 * @covers ConfigFactory::makeConfig
	 */
	public function testMakeConfig() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', 'GlobalVarConfig::newInstance' );
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
		// Set $wgConfigRegistry, and check the default
		// instance read from it
		$this->setMwGlobals( 'wgConfigRegistry', array(
			'conf1' => 'GlobalVarConfig::newInstance',
			'conf2' => 'GlobalVarConfig::newInstance',
		) );
		ConfigFactory::destroyDefaultInstance();
		$factory = ConfigFactory::getDefaultInstance();
		$this->assertInstanceOf( 'Config', $factory->makeConfig( 'conf1' ) );
		$this->assertInstanceOf( 'Config', $factory->makeConfig( 'conf2' ) );
		$this->setExpectedException( 'ConfigException' );
		$factory->makeConfig( 'conf3' );
	}
}
