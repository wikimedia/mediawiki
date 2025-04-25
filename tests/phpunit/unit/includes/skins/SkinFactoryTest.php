<?php

use MediaWiki\Skin\Skin;
use MediaWiki\Skin\SkinException;
use MediaWiki\Skin\SkinFactory;
use MediaWiki\Skin\SkinFallback;
use MediaWiki\Tests\Unit\DummyServicesTrait;

/**
 * @covers \MediaWiki\Skin\SkinFactory
 */
class SkinFactoryTest extends \MediaWikiUnitTestCase {
	use DummyServicesTrait;

	private function createSkinFactory( $services = [], $options = [] ): SkinFactory {
		$objectFactory = $this->getDummyObjectFactory( $services );

		return new SkinFactory( $objectFactory, $options );
	}

	public function testRegisterWithInvalidCallable() {
		$factory = $this->createSkinFactory();

		$this->expectException( InvalidArgumentException::class );
		$factory->register( 'invalid', 'Invalid', 'Invalid callback' );
	}

	public function testRegisterWithCallable() {
		$factory = $this->createSkinFactory();
		$instance = new SkinFallback();

		$factory->register( 'fallback', 'Fallback', static function () use ( $instance ) {
			return $instance;
		}, true );

		$this->assertSame( $instance, $factory->makeSkin( 'fallback' ) );
	}

	public function testRegisterWithSpec() {
		$factory = $this->createSkinFactory();
		$factory->register( 'fallback', 'Fallback', [
			'class' => SkinFallback::class
		], true );

		$this->assertInstanceOf( SkinFallback::class, $factory->makeSkin( 'fallback' ) );
	}

	public function testMakeSkinWithNoBuilders() {
		$factory = $this->createSkinFactory();
		$this->expectException( SkinException::class );
		$factory->makeSkin( 'nobuilderregistered' );
	}

	public function testMakeSkinWithInvalidCallback() {
		$factory = $this->createSkinFactory();
		$factory->register( 'unittest', 'Unittest', static function () {
			// Not a Skin object
			return true;
		} );
		$this->expectException( UnexpectedValueException::class );
		$factory->makeSkin( 'unittest' );
	}

	public function testMakeSkinWithValidCallback() {
		$factory = $this->createSkinFactory();
		$factory->register( 'testfallback', 'TestFallback', static function () {
			return new SkinFallback();
		} );

		$skin = $factory->makeSkin( 'testfallback' );
		$this->assertInstanceOf( SkinFallback::class, $skin );
		$this->assertEquals( 'fallback', $skin->getSkinName() );
	}

	public function testMakeSkinWithValidSpec() {
		$serviceInstance = (object)[];
		$services = [ 'testservice' => $serviceInstance ];

		$args = [];
		$factory = $this->createSkinFactory( $services );
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

	public function testRegisterReplaces() {
		$factory = $this->createSkinFactory();

		$s1 = $this->createMock( Skin::class );
		$factory->register( 'foo', 'Skin 1',
			static function () use ( $s1 ) {
				return $s1;
			},
			true
		);
		$this->assertEquals( [ 'foo'  => 'Skin 1' ], $factory->getInstalledSkins() );
		$this->assertSame( $s1, $factory->makeSkin( 'foo' ) );
		$this->assertSame( [], $factory->getAllowedSkins(), 'skipped' );

		// Skippable state from previous register() call must not leak to replacement
		$s2 = $this->createMock( Skin::class );
		$factory->register( 'foo', 'Skin 2',
			static function () use ( $s2 ) {
				return $s2;
			}
		);
		$this->assertEquals( [ 'foo'  => 'Skin 2' ], $factory->getInstalledSkins() );
		$this->assertSame( $s2, $factory->makeSkin( 'foo' ) );
		$this->assertSame( [ 'foo'  => 'Skin 2' ], $factory->getAllowedSkins(), 'not skipped' );
	}

	public function testGetSkinNames() {
		$this->expectDeprecationAndContinue( '/SkinFactory::getSkinNames was deprecated in MediaWiki 1\.37/' );

		$factory = $this->createSkinFactory();
		$factory->register( 'skin1', 'Skin1', [] );
		$factory->register( 'skin2', 'Skin2', [] );

		$names = $factory->getSkinNames();
		$this->assertEquals( 'Skin1', $names['skin1'] );
		$this->assertEquals( 'Skin2', $names['skin2'] );
	}

	public function testGetAllowedSkins() {
		$sf = $this->createSkinFactory( [], [ 'quux' ] );
		$sf->register( 'foo', 'Foo', [] );
		$sf->register( 'apioutput', 'ApiOutput', [], true );

		// Skippable state is unspecified here and must inherit from site config,
		// which we seeded with 'quux', and thus skipped from allowed skins.
		$sf->register( 'quux', 'Quux', [] );

		$sf->register( 'fallback', 'Fallback', [], true );
		$sf->register( 'bar', 'Barbar', [] );

		$this->assertEquals(
			[ 'foo' => 'Foo', 'bar' => 'Barbar' ],
			$sf->getAllowedSkins()
		);
	}

	public function testGetAllowedSkinsEmpty() {
		$sf = $this->createSkinFactory();
		$sf->register( 'apioutput', 'ApiOutput', [], true );
		$sf->register( 'fallback', 'Fallback', [], true );

		$this->assertEquals( [], $sf->getAllowedSkins() );
	}
}
