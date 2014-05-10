<?php

class ConfigFactoryTest extends MediaWikiTestCase {

	/**
	 * @covers ConfigFactory::register
	 */
	public function testRegister() {
		$factory = new ConfigFactory();
		$factory->register( 'unittest', 'GlobalVarConfig::newInstance' );
		$this->assertTrue( True ); // No exception thrown
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
		$factory->register( 'unittest', function() {
			return true; // Not a Config object
		});
		$this->setExpectedException( 'UnexpectedValueException' );
		$factory->makeConfig( 'unittest' );
	}
}
