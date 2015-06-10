<?php

/**
 * @group ResourceLoader
 */
class DerivativeResourceLoaderContextTest extends PHPUnit_Framework_TestCase {

	protected static function getResourceLoaderContext() {
		$resourceLoader = new ResourceLoader();
		$request = new FauxRequest( array(
				'lang' => 'zh',
				'modules' => 'test.context',
				'only' => 'scripts',
				'skin' => 'fallback',
				'target' => 'test',
		) );
		return new ResourceLoaderContext( $resourceLoader, $request );
	}

	public function testGet() {
		$context = self::getResourceLoaderContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$this->assertEquals( $derived->getLanguage(), 'zh' );
		$this->assertEquals( $derived->getModules(), array( 'test.context' ) );
		$this->assertEquals( $derived->getOnly(), 'scripts' );
		$this->assertEquals( $derived->getSkin(), 'fallback' );
	}

	public function testSetLanguage() {
		$context = self::getResourceLoaderContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$derived->setLanguage( 'nl' );
		$this->assertEquals( $derived->getLanguage(), 'nl' );
	}

	public function testSetModules() {
		$context = self::getResourceLoaderContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$derived->setModules( array( 'test.override' ) );
		$this->assertEquals( $derived->getModules(), array( 'test.override' ) );
	}

	public function testSetOnly() {
		$context = self::getResourceLoaderContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$derived->setOnly( 'styles' );
		$this->assertEquals( $derived->getOnly(), 'styles' );

		$derived->setOnly( false );
		$this->assertEquals( $derived->getOnly(), false );
	}

	public function testSetSkin() {
		$context = self::getResourceLoaderContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$derived->setSkin( 'override' );
		$this->assertEquals( $derived->getSkin(), 'override' );
	}

}
