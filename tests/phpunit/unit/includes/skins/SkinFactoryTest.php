<?php

class SkinFactoryTest extends \MediaWikiUnitTestCase {

	/**
	 * @covers SkinFactory::register
	 */
	public function testRegister() {
		$factory = new SkinFactory();
		$factory->register( 'fallback', 'Fallback', function () {
			return new SkinFallback();
		} );
		$this->assertTrue( true ); // No exception thrown
		$this->setExpectedException( InvalidArgumentException::class );
		$factory->register( 'invalid', 'Invalid', 'Invalid callback' );
	}

	/**
	 * @covers SkinFactory::makeSkin
	 */
	public function testMakeSkinWithNoBuilders() {
		$factory = new SkinFactory();
		$this->setExpectedException( SkinException::class );
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
		$this->setExpectedException( UnexpectedValueException::class );
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
		$this->assertInstanceOf( Skin::class, $skin );
		$this->assertInstanceOf( SkinFallback::class, $skin );
		$this->assertEquals( 'fallback', $skin->getSkinName() );
	}

	/**
	 * @covers Skin::__construct
	 * @covers Skin::getSkinName
	 */
	public function testGetSkinName() {
		$skin = new SkinFallback();
		$this->assertEquals( 'fallback', $skin->getSkinName(), 'Default' );
		$skin = new SkinFallback( 'testname' );
		$this->assertEquals( 'testname', $skin->getSkinName(), 'Constructor argument' );
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
