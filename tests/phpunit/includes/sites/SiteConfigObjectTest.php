<?php

namespace MW\Test;
use MW\SiteConfigObject as SiteConfigObject;

/**
 * Tests for the MW\SiteConfigObject class.
 *
 * @file
 * @since 1.20
 *
 * @ingroup Sites
 * @ingroup Test
 *
 * @group Sites
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SiteConfigObjectTest extends \MediaWikiTestCase {

	/**
	 * @return array
	 */
	public function constructorProvider() {
		$args = array(
			array( 'en', false, false, false ),
			array( 'en', true, true, true ),
			array( 'nl', true, false, true ),
			array( 'nl', true, false, true, array() ),
			array( 'dewiktionary', false, true, false, array( 'foo' => 'bar', 'baz' => 42 ) ),
		);

		foreach ( $args as &$arg ) {
			$arg = array( $arg );
		}

		return $args;
	}

	/**
	 * @param array $args
	 * @return SiteConfigObject
	 */
	protected function createInstance( array $args ) {
		$configObject = new \ReflectionClass( 'MW\SiteConfigObject' );
		return $configObject->newInstanceArgs( $args );
	}

	/**
	 * @dataProvider constructorProvider
	 * @param array $args
	 */
	public function testConstructor( array $args ) {
		$configObject = $this->createInstance( $args );

		$this->assertInstanceOf( 'MW\SiteConfig', $configObject );
	}

	/**
	 * @dataProvider constructorProvider
	 * @param array $args
	 */
	public function testGetLocalId( array $args ) {
		$configObject = $this->createInstance( $args );

		$this->assertInternalType( 'string', $configObject->getLocalId() );
		$this->assertEquals( $args[0], $configObject->getLocalId() );
	}

	/**
	 * @dataProvider constructorProvider
	 * @param array $args
	 */
	public function testGetLinkInline( array $args ) {
		$configObject = $this->createInstance( $args );

		$this->assertInternalType( 'boolean', $configObject->getLinkInline() );
		$this->assertEquals( $args[1], $configObject->getLinkInline() );
	}

	/**
	 * @dataProvider constructorProvider
	 * @param array $args
	 */
	public function testGetLinkNavigation( array $args ) {
		$configObject = $this->createInstance( $args );

		$this->assertInternalType( 'boolean', $configObject->getLinkNavigation() );
		$this->assertEquals( $args[2], $configObject->getLinkNavigation() );
	}

	/**
	 * @dataProvider constructorProvider
	 * @param array $args
	 */
	public function testGetForward( array $args ) {
		$configObject = $this->createInstance( $args );

		$this->assertInternalType( 'boolean', $configObject->getForward() );
		$this->assertEquals( $args[3], $configObject->getForward() );
	}

	/**
	 * @dataProvider constructorProvider
	 * @param array $args
	 */
	public function testGetExtraInfo( array $args ) {
		$configObject = $this->createInstance( $args );

		$this->assertInternalType( 'array', $configObject->getExtraInfo() );
		$this->assertEquals(
			array_key_exists( 4, $args ) ? $args[4] : array(),
			$configObject->getExtraInfo()
		);
	}

}
