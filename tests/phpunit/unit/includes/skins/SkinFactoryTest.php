<?php

use Psr\Container\ContainerInterface;
use Wikimedia\ObjectFactory;

class SkinFactoryTest extends \MediaWikiUnitTestCase {

	private function createSkinFactory( $service = null, $options = [] ) : SkinFactory {
		$objectFactory = $service
			? new ObjectFactory( $service )
			: new ObjectFactory( $this->createMock( ContainerInterface::class ) );

		return new SkinFactory( $objectFactory, $options );
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

		$factory->register( 'fallback', 'Fallback', static function () use ( $instance ) {
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
		$factory->register( 'unittest', 'Unittest', static function () {
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
		$factory->register( 'testfallback', 'TestFallback', static function () {
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
		$factory = $this->createSkinFactory( $serviceContainer );
		$factory->register( 'testfallback', 'TestFallback', [
			'factory' => static function ( $service, $options ) use ( &$args ) {
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

	/**
	 * @covers SkinFactory::getAllowedSkins
	 */
	public function testGetAllowedSkins() {
		$sf = $this->createSkinFactory( null, [ 'quux' ] );
		$sf->register( 'foo', 'Foo', [] );
		$sf->register( 'apioutput', 'ApiOutput', [] );
		$sf->register( 'quux', 'Quux', [] );
		$sf->register( 'fallback', 'Fallback', [] );
		$sf->register( 'bar', 'Barbar', [] );

		$this->assertEquals(
			[ 'foo' => 'Foo', 'bar' => 'Barbar' ],
			$sf->getAllowedSkins()
		);
	}

	/**
	 * @covers SkinFactory::getAllowedSkins
	 */
	public function testGetAllowedSkinsEmpty() {
		$sf = $this->createSkinFactory();
		$sf->register( 'apioutput', 'ApiOutput', [] );
		$sf->register( 'fallback', 'Fallback', [] );

		$this->assertEquals( [], $sf->getAllowedSkins() );
	}
}
