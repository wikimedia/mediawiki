<?php

/**
 * @group ResourceLoader
 * @covers DerivativeResourceLoaderContext
 */
class DerivativeResourceLoaderContextTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	protected static function getContext() {
		$request = new FauxRequest( [
				'lang' => 'zh',
				'modules' => 'test.context',
				'only' => 'scripts',
				'skin' => 'fallback',
				'target' => 'test',
		] );
		return new ResourceLoaderContext( new ResourceLoader(), $request );
	}

	public function testGetInherited() {
		$derived = new DerivativeResourceLoaderContext( self::getContext() );

		// Request parameters
		$this->assertEquals( $derived->getDebug(), false );
		$this->assertEquals( $derived->getLanguage(), 'zh' );
		$this->assertEquals( $derived->getModules(), [ 'test.context' ] );
		$this->assertEquals( $derived->getOnly(), 'scripts' );
		$this->assertEquals( $derived->getSkin(), 'fallback' );
		$this->assertEquals( $derived->getUser(), null );

		// Misc
		$this->assertEquals( $derived->getDirection(), 'ltr' );
		$this->assertEquals( $derived->getHash(), 'zh|fallback|||scripts|||||' );
	}

	public function testModules() {
		$derived = new DerivativeResourceLoaderContext( self::getContext() );

		$derived->setModules( [ 'test.override' ] );
		$this->assertEquals( $derived->getModules(), [ 'test.override' ] );
	}

	public function testLanguage() {
		$context = self::getContext();
		$derived = new DerivativeResourceLoaderContext( $context );

		$derived->setLanguage( 'nl' );
		$this->assertEquals( $derived->getLanguage(), 'nl' );
	}

	public function testDirection() {
		$derived = new DerivativeResourceLoaderContext( self::getContext() );

		$derived->setLanguage( 'nl' );
		$this->assertEquals( $derived->getDirection(), 'ltr' );

		$derived->setLanguage( 'he' );
		$this->assertEquals( $derived->getDirection(), 'rtl' );

		$derived->setDirection( 'ltr' );
		$this->assertEquals( $derived->getDirection(), 'ltr' );
	}

	public function testSkin() {
		$derived = new DerivativeResourceLoaderContext( self::getContext() );

		$derived->setSkin( 'override' );
		$this->assertEquals( $derived->getSkin(), 'override' );
	}

	public function testUser() {
		$derived = new DerivativeResourceLoaderContext( self::getContext() );

		$derived->setUser( 'Example' );
		$this->assertEquals( $derived->getUser(), 'Example' );
	}

	public function testDebug() {
		$derived = new DerivativeResourceLoaderContext( self::getContext() );

		$derived->setDebug( true );
		$this->assertEquals( $derived->getDebug(), true );
	}

	public function testOnly() {
		$derived = new DerivativeResourceLoaderContext( self::getContext() );

		$derived->setOnly( 'styles' );
		$this->assertEquals( $derived->getOnly(), 'styles' );

		$derived->setOnly( null );
		$this->assertEquals( $derived->getOnly(), null );
	}

	public function testVersion() {
		$derived = new DerivativeResourceLoaderContext( self::getContext() );

		$derived->setVersion( 'hw1' );
		$this->assertEquals( $derived->getVersion(), 'hw1' );
	}

	public function testRaw() {
		$derived = new DerivativeResourceLoaderContext( self::getContext() );

		$derived->setRaw( true );
		$this->assertEquals( $derived->getRaw(), true );
	}

	public function testGetHash() {
		$derived = new DerivativeResourceLoaderContext( self::getContext() );

		$this->assertEquals( $derived->getHash(), 'zh|fallback|||scripts|||||' );

		$derived->setLanguage( 'nl' );
		$derived->setUser( 'Example' );
		// Assert that subclass is able to clear parent class "hash" member
		$this->assertEquals( $derived->getHash(), 'nl|fallback||Example|scripts|||||' );
	}

	public function testAccessors() {
		$context = self::getContext();
		$derived = new DerivativeResourceLoaderContext( $context );
		$this->assertSame( $derived->getRequest(), $context->getRequest() );
		$this->assertSame( $derived->getResourceLoader(), $context->getResourceLoader() );
	}
}
