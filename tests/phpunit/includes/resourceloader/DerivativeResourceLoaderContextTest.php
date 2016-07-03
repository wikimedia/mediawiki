<?php

/**
 * @group ResourceLoader
 */
class DerivativeResourceLoaderContextTest extends PHPUnit_Framework_TestCase {

	protected static function getResourceLoaderContext() {
		$resourceLoader = new ResourceLoader();
		$request = new FauxRequest( [
				'lang' => 'zh',
				'modules' => 'test.context',
				'only' => 'scripts',
				'skin' => 'fallback',
				'target' => 'test',
		] );
		return new ResourceLoaderContext( $resourceLoader, $request );
	}

	public function testGet() {
		$context = self::getResourceLoaderContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$this->assertEquals( $derived->getLanguage(), 'zh' );
		$this->assertEquals( $derived->getModules(), [ 'test.context' ] );
		$this->assertEquals( $derived->getOnly(), 'scripts' );
		$this->assertEquals( $derived->getSkin(), 'fallback' );
		$this->assertEquals( $derived->getHash(), 'zh|fallback|||scripts|||||' );
	}

	public function testSetLanguage() {
		$context = self::getResourceLoaderContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$derived->setLanguage( 'nl' );
		$this->assertEquals( $derived->getLanguage(), 'nl' );

		$derived->setLanguage( 'he' );
		$this->assertEquals( $derived->getDirection(), 'rtl' );
	}

	public function testSetModules() {
		$context = self::getResourceLoaderContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$derived->setModules( [ 'test.override' ] );
		$this->assertEquals( $derived->getModules(), [ 'test.override' ] );
	}

	public function testSetOnly() {
		$context = self::getResourceLoaderContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$derived->setOnly( 'styles' );
		$this->assertEquals( $derived->getOnly(), 'styles' );

		$derived->setOnly( null );
		$this->assertEquals( $derived->getOnly(), null );
	}

	public function testSetSkin() {
		$context = self::getResourceLoaderContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$derived->setSkin( 'override' );
		$this->assertEquals( $derived->getSkin(), 'override' );
	}

	public function testGetHash() {
		$context = self::getResourceLoaderContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$derived->setLanguage( 'nl' );
		// Assert that subclass is able to clear parent class "hash" member
		$this->assertEquals( $derived->getHash(), 'nl|fallback|||scripts|||||' );
	}

}
