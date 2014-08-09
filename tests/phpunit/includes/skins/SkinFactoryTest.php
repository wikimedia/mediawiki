<?php

class SkinFactoryTest extends MediaWikiTestCase {

	/**
	 * @covers SkinFactory::register
	 */
	public function testRegister() {
		$factory = new SkinFactory();
		$factory->register( 'fallback', 'Fallback', function() {
			return new SkinFallback();
		} );
		$this->assertTrue( true ); // No exception thrown
		$this->setExpectedException( 'InvalidArgumentException' );
		$factory->register( 'invalid', 'Invalid', 'Invalid callback' );
	}

	/**
	 * @covers SkinFactory::makeSkin
	 */
	public function testMakeSkinWithNoBuilders() {
		$factory = new SkinFactory();
		$this->setExpectedException( 'SkinException' );
		$factory->makeSkin( 'nobuilderregistered' );
	}

	/**
	 * @covers SkinFactory::makeSkin
	 */
	public function testMakeSkinWithInvalidCallback() {
		$factory = new SkinFactory();
		$factory->register( 'unittest', 'Unittest', function () {
			return true; // Not a Config object
		} );
		$this->setExpectedException( 'UnexpectedValueException' );
		$factory->makeSkin( 'unittest' );
	}
}
