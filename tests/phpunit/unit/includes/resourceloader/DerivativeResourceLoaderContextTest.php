<?php

/**
 * @group ResourceLoader
 * @covers DerivativeResourceLoaderContext
 */
class DerivativeResourceLoaderContextTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	protected static function makeContext() {
		$request = new FauxRequest( [
				'lang' => 'qqx',
				'modules' => 'test.default',
				'only' => 'scripts',
				'skin' => 'fallback',
				'target' => 'test',
		] );
		return new ResourceLoaderContext(
			new ResourceLoader( ResourceLoaderTestCase::getMinimalConfig() ),
			$request
		);
	}

	public function testChangeModules() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( $derived->getModules(), [ 'test.default' ], 'inherit from parent' );

		$derived->setModules( [ 'test.override' ] );
		$this->assertSame( $derived->getModules(), [ 'test.override' ] );
	}

	public function testChangeLanguageAndDirection() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( $derived->getLanguage(), 'qqx', 'inherit from parent' );
		$this->assertSame( $derived->getDirection(), 'ltr', 'inherit from parent' );

		$derived->setLanguage( 'nl' );
		$this->assertSame( $derived->getLanguage(), 'nl' );
		$this->assertSame( $derived->getDirection(), 'ltr' );

		// Changing the language must clear cache of computed direction
		$derived->setLanguage( 'he' );
		$this->assertSame( $derived->getDirection(), 'rtl' );
		$this->assertSame( $derived->getLanguage(), 'he' );

		// Overriding the direction explicitly is allowed
		$derived->setDirection( 'ltr' );
		$this->assertSame( $derived->getDirection(), 'ltr' );
		$this->assertSame( $derived->getLanguage(), 'he' );
	}

	public function testChangeSkin() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( $derived->getSkin(), 'fallback', 'inherit from parent' );

		$derived->setSkin( 'myskin' );
		$this->assertSame( $derived->getSkin(), 'myskin' );
	}

	public function testChangeUser() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( $derived->getUser(), null, 'inherit from parent' );

		$derived->setUser( 'MyUser' );
		$this->assertSame( $derived->getUser(), 'MyUser' );
	}

	public function testChangeUserObj() {
		$user = $this->createMock( User::class );
		$parent = $this->createMock( ResourceLoaderContext::class );
		$parent
			->expects( $this->once() )
			->method( 'getUserObj' )
			->willReturn( $user );

		$derived = new DerivativeResourceLoaderContext( $parent );
		$this->assertSame( $derived->getUserObj(), $user, 'inherit from parent' );

		$derived->setUser( null );
		$this->assertNotSame( $derived->getUserObj(), $user, 'different' );
	}

	public function testChangeDebug() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( $derived->getDebug(), false, 'inherit from parent' );

		$derived->setDebug( true );
		$this->assertSame( $derived->getDebug(), true );
	}

	public function testChangeOnly() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( $derived->getOnly(), 'scripts', 'inherit from parent' );

		$derived->setOnly( 'styles' );
		$this->assertSame( $derived->getOnly(), 'styles' );

		$derived->setOnly( null );
		$this->assertSame( $derived->getOnly(), null );
	}

	public function testChangeVersion() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( $derived->getVersion(), null );

		$derived->setVersion( 'hw1' );
		$this->assertSame( $derived->getVersion(), 'hw1' );
	}

	public function testChangeRaw() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( $derived->getRaw(), false, 'inherit from parent' );

		$derived->setRaw( true );
		$this->assertSame( $derived->getRaw(), true );
	}

	public function testChangeHash() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( $derived->getHash(), 'qqx|fallback|||scripts|||||', 'inherit' );

		$derived->setLanguage( 'nl' );
		$derived->setUser( 'Example' );
		// Assert that subclass is able to clear parent class "hash" member
		$this->assertSame( $derived->getHash(), 'nl|fallback||Example|scripts|||||' );
	}

	public function testChangeContentOverrides() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertNull( $derived->getContentOverrideCallback(), 'default' );

		$override = function ( Title $t ) {
			return null;
		};
		$derived->setContentOverrideCallback( $override );
		$this->assertSame( $override, $derived->getContentOverrideCallback(), 'changed' );

		$derived2 = new DerivativeResourceLoaderContext( $derived );
		$this->assertSame(
			$override,
			$derived2->getContentOverrideCallback(),
			'change via a second derivative layer'
		);
	}

	public function testImmutableAccessors() {
		$context = self::makeContext();
		$derived = new DerivativeResourceLoaderContext( $context );
		$this->assertSame( $derived->getRequest(), $context->getRequest() );
		$this->assertSame( $derived->getResourceLoader(), $context->getResourceLoader() );
	}
}
