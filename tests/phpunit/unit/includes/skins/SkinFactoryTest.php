<?php

use Psr\Container\ContainerInterface;
use Wikimedia\ObjectFactory;

class SkinFactoryTest extends \MediaWikiUnitTestCase {

	private function createSkinFactory() : SkinFactory {
		return new SkinFactory( new ObjectFactory( $this->createMock( ContainerInterface::class ) ) );
	}

	/**
	 * @covers SkinFactory::register
	 */
	public function testRegisterWithInvalidCallable() {
		$factory = $this->createSkinFactory();

		$this->expectException( InvalidArgumentException::class );
		$factory->register( 'invalid', 'Invalid', 'Invalid callback' );
	}

	/**
	 * @covers SkinFactory::register
	 */
	public function testRegisterWithCallable() {
		$factory = $this->createSkinFactory();
		$instance = new SkinFallback();

		$factory->register( 'fallback', 'Fallback', function () use ( $instance ) {
			return $instance;
		} );

		$this->assertSame( $instance, $factory->makeSkin( 'fallback' ) );
	}

	/**
	 * @covers SkinFactory::register
	 */
	public function testRegisterWithSpec() {
		$factory = $this->createSkinFactory();
		$factory->register( 'fallback', 'Fallback', [
			'class' => SkinFallback::class
		] );

		$this->assertInstanceOf( SkinFallback::class, $factory->makeSkin( 'fallback' ) );
	}

	/**
	 * @covers SkinFactory::makeSkin
	 */
	public function testMakeSkinWithNoBuilders() {
		$factory = $this->createSkinFactory();
		$this->expectException( SkinException::class );
		$factory->makeSkin( 'nobuilderregistered' );
	}

	/**
	 * @covers SkinFactory::makeSkin
	 */
	public function testMakeSkinWithInvalidCallback() {
		$factory = $this->createSkinFactory();
		$factory->register( 'unittest', 'Unittest', function () {
			// Not a Skin object
			return true;
		} );
		$this->expectException( UnexpectedValueException::class );
		$factory->makeSkin( 'unittest' );
	}

	/**
	 * @covers SkinFactory::makeSkin
	 */
	public function testMakeSkinWithValidCallback() {
		$factory = $this->createSkinFactory();
		$factory->register( 'testfallback', 'TestFallback', function () {
			return new SkinFallback();
		} );

		$skin = $factory->makeSkin( 'testfallback' );
		$this->assertInstanceOf( SkinFallback::class, $skin );
		$this->assertEquals( 'fallback', $skin->getSkinName() );
	}

	/**
	 * @covers SkinFactory::__construct
	 * @covers SkinFactory::makeSkin
	 */
	public function testMakeSkinWithValidSpec() {
		$serviceInstance = new stdClass();

		$serviceContainer = $this->createMock( ContainerInterface::class );
		$serviceContainer->method( 'has' )->willReturn( true );
		$serviceContainer->method( 'get' )->willReturn( $serviceInstance );

		$args = [];
		$factory = new SkinFactory( new ObjectFactory( $serviceContainer ) );
		$factory->register( 'testfallback', 'TestFallback', [
			'factory' => function ( $service, $options ) use ( &$args ) {
				$args = [ $service, $options ];
				return new SkinFallback();
			},
			'services' => [
				'testservice'
			]
		] );

		$skin = $factory->makeSkin( 'testfallback' );
		$this->assertInstanceOf( SkinFallback::class, $skin );
		$this->assertEquals( 'fallback', $skin->getSkinName() );
		$this->assertSame( 'testfallback', $args[1]['name'] );
		$this->assertSame( $serviceInstance, $args[0] );
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
		$factory = $this->createSkinFactory();
		$factory->register( 'skin1', 'Skin1', [] );
		$factory->register( 'skin2', 'Skin2', [] );

		$names = $factory->getSkinNames();
		$this->assertEquals( 'Skin1', $names['skin1'] );
		$this->assertEquals( 'Skin2', $names['skin2'] );
	}
}
