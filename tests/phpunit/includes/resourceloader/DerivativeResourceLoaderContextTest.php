<?php

use MediaWiki\User\UserIdentity;

/**
 * @group ResourceLoader
 * @covers DerivativeResourceLoaderContext
 */
class DerivativeResourceLoaderContextTest extends MediaWikiIntegrationTestCase {

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
		$this->assertSame( 'qqx', $derived->getLanguage(), 'inherit from parent' );
		$this->assertSame( 'ltr', $derived->getDirection(), 'inherit from parent' );

		$derived->setLanguage( 'nl' );
		$this->assertSame( 'nl', $derived->getLanguage() );
		$this->assertSame( 'ltr', $derived->getDirection() );

		// Changing the language must clear cache of computed direction
		$derived->setLanguage( 'he' );
		$this->assertSame( 'rtl', $derived->getDirection() );
		$this->assertSame( 'he', $derived->getLanguage() );

		// Overriding the direction explicitly is allowed
		$derived->setDirection( 'ltr' );
		$this->assertSame( 'ltr', $derived->getDirection() );
		$this->assertSame( 'he', $derived->getLanguage() );
	}

	public function testChangeSkin() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( 'fallback', $derived->getSkin(), 'inherit from parent' );

		$derived->setSkin( 'myskin' );
		$this->assertSame( 'myskin', $derived->getSkin() );
	}

	public function testChangeUser() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertNull( $derived->getUser(), 'inherit from parent' );

		$derived->setUser( 'MyUser' );
		$this->assertSame( 'MyUser', $derived->getUser() );
	}

	public function testChangeUserObj() {
		$user = $this->createMock( User::class );
		$userIdentity = $this->createMock( UserIdentity::class );
		$parent = $this->createMock( ResourceLoaderContext::class );
		$parent
			->expects( $this->once() )
			->method( 'getUserObj' )
			->willReturn( $user );
		$parent
			->expects( $this->once() )
			->method( 'getUserIdentity' )
			->willReturn( $userIdentity );

		$derived = new DerivativeResourceLoaderContext( $parent );
		$this->assertSame( $derived->getUserObj(), $user, 'inherit from parent' );
		$this->assertSame( $derived->getUserIdentity(), $userIdentity, 'inherit from parent' );

		$derived->setUser( null );
		$this->assertNotSame( $derived->getUserObj(), $user, 'different' );
		$this->assertNull( $derived->getUserIdentity(), 'no user identity' );
	}

	public function testChangeDebug() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( 0, $derived->getDebug(), 'inherit from parent' );

		$derived->setDebug( 1 );
		$this->assertSame( 1, $derived->getDebug() );
	}

	public function testChangeOnly() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( 'scripts', $derived->getOnly(), 'inherit from parent' );

		$derived->setOnly( 'styles' );
		$this->assertSame( 'styles', $derived->getOnly() );

		$derived->setOnly( null );
		$this->assertNull( $derived->getOnly() );
	}

	public function testChangeVersion() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertNull( $derived->getVersion() );

		$derived->setVersion( 'hw1' );
		$this->assertSame( 'hw1', $derived->getVersion() );
	}

	public function testChangeRaw() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertFalse( $derived->getRaw(), 'inherit from parent' );

		$derived->setRaw( true );
		$this->assertTrue( $derived->getRaw() );
	}

	public function testChangeHash() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertSame( 'qqx|fallback|0||scripts|||||', $derived->getHash(), 'inherit' );

		$derived->setLanguage( 'nl' );
		$derived->setUser( 'Example' );
		// Assert that subclass is able to clear parent class "hash" member
		$this->assertSame( 'nl|fallback|0|Example|scripts|||||', $derived->getHash() );
	}

	public function testChangeContentOverrides() {
		$derived = new DerivativeResourceLoaderContext( self::makeContext() );
		$this->assertNull( $derived->getContentOverrideCallback(), 'default' );

		$override = static function ( Title $t ) {
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
