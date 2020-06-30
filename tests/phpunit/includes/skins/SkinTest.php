<?php

use Psr\Container\ContainerInterface;
use Wikimedia\ObjectFactory;

class SkinTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers Skin::getDefaultModules
	 */
	public function testGetDefaultModules() {
		$skin = $this->getMockBuilder( Skin::class )
			->setMethods( [ 'outputPage', 'setupSkinUserCss' ] )
			->getMock();

		$modules = $skin->getDefaultModules();
		$this->assertTrue( isset( $modules['core'] ), 'core key is set by default' );
		$this->assertTrue( isset( $modules['styles'] ), 'style key is set by default' );
	}

	/**
	 * @covers Skin::getAllowedSkins
	 */
	public function testGetAllowedSkinsEmpty() {
		$skin = $this->getMockBuilder( Skin::class )
			->setMethods( [ 'outputPage' ] )
			->getMock();

		$this->setService( 'SkinFactory', new SkinFactory(
			new ObjectFactory( $this->createMock( ContainerInterface::class ) )
		) );
		$this->setMwGlobals( 'wgSkipSkins', [] );

		$this->assertEquals( [], $skin->getAllowedSkins() );
	}

	/**
	 * @covers Skin::getAllowedSkins
	 */
	public function testGetAllowedSkins() {
		$skin = $this->getMockBuilder( Skin::class )
			->setMethods( [ 'outputPage' ] )
			->getMock();
		$noop = function () {
		};

		$sf = new SkinFactory(
			new ObjectFactory( $this->createMock( ContainerInterface::class ) )
		);
		$sf->register( 'foo', 'Foo', $noop );
		$sf->register( 'apioutput', 'ApiOutput', $noop );
		$sf->register( 'quux', 'Quux', $noop );
		$sf->register( 'fallback', 'Fallback', $noop );
		$sf->register( 'bar', 'Barbar', $noop );

		$this->setService( 'SkinFactory', $sf );
		$this->setMwGlobals( 'wgSkipSkins', [ 'quux' ] );

		$this->assertEquals(
			[ 'foo' => 'Foo', 'bar' => 'Barbar' ],
			$skin->getAllowedSkins()
		);
	}
}
