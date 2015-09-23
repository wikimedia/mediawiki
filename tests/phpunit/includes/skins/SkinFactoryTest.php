<?php

class SkinFactoryTest extends MediaWikiTestCase {

	/**
	 * @covers SkinFactory::register
	 */
	public function testRegister() {
		$factory = new SkinFactory();
		$factory->register( 'fallback', 'Fallback', function () {
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
			return true; // Not a Skin object
		} );
		$this->setExpectedException( 'UnexpectedValueException' );
		$factory->makeSkin( 'unittest' );
	}

	/**
	 * @covers SkinFactory::makeSkin
	 */
	public function testMakeSkinWithValidCallback() {
		$factory = new SkinFactory();
		$factory->register( 'testfallback', 'TestFallback', function () {
			return new SkinFallback();
		} );

		$skin = $factory->makeSkin( 'testfallback' );
		$this->assertInstanceOf( 'Skin', $skin );
		$this->assertInstanceOf( 'SkinFallback', $skin );
	}

	/**
	 * @covers SkinFactory::getSkinNames
	 */
	public function testGetSkinNames() {
		$factory = new SkinFactory();
		// A fake callback we can use that will never be called
		$callback = function () {
			// NOP
		};
		$factory->register( 'skin1', 'Skin1', $callback );
		$factory->register( 'skin2', 'Skin2', $callback );
		$names = $factory->getSkinNames();
		$this->assertArrayHasKey( 'skin1', $names );
		$this->assertArrayHasKey( 'skin2', $names );
		$this->assertEquals( 'Skin1', $names['skin1'] );
		$this->assertEquals( 'Skin2', $names['skin2'] );
	}
}
