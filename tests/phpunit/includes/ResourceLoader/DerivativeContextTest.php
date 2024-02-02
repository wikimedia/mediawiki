<?php

namespace MediaWiki\Tests\ResourceLoader;

use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\DerivativeContext;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWikiIntegrationTestCase;

/**
 * @group ResourceLoader
 * @covers \MediaWiki\ResourceLoader\DerivativeContext
 */
class DerivativeContextTest extends MediaWikiIntegrationTestCase {

	protected static function makeContext() {
		$request = new FauxRequest( [
				'lang' => 'qqx',
				'modules' => 'test.default',
				'only' => 'scripts',
				'skin' => 'fallback',
				'target' => 'test',
		] );
		return new Context(
			new ResourceLoader( ResourceLoaderTestCase::getMinimalConfig() ),
			$request
		);
	}

	public function testChangeModules() {
		$derived = new DerivativeContext( self::makeContext() );
		$this->assertSame( [ 'test.default' ], $derived->getModules(), 'inherit from parent' );

		$derived->setModules( [ 'test.override' ] );
		$this->assertSame( [ 'test.override' ], $derived->getModules() );
	}

	public function testChangeLanguageAndDirection() {
		$derived = new DerivativeContext( self::makeContext() );
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
		$derived = new DerivativeContext( self::makeContext() );
		$this->assertSame( 'fallback', $derived->getSkin(), 'inherit from parent' );

		$derived->setSkin( 'myskin' );
		$this->assertSame( 'myskin', $derived->getSkin() );
	}

	public function testChangeUser() {
		$derived = new DerivativeContext( self::makeContext() );
		$this->assertNull( $derived->getUser(), 'inherit from parent' );

		$derived->setUser( 'MyUser' );
		$this->assertSame( 'MyUser', $derived->getUser() );
	}

	public function testChangeUserObj() {
		$user = $this->createMock( User::class );
		$userIdentity = $this->createMock( UserIdentity::class );
		$parent = $this->createMock( Context::class );
		$parent
			->expects( $this->once() )
			->method( 'getUserObj' )
			->willReturn( $user );
		$parent
			->expects( $this->once() )
			->method( 'getUserIdentity' )
			->willReturn( $userIdentity );

		$derived = new DerivativeContext( $parent );
		$this->assertSame( $derived->getUserObj(), $user, 'inherit from parent' );
		$this->assertSame( $derived->getUserIdentity(), $userIdentity, 'inherit from parent' );

		$derived->setUser( null );
		$this->assertNotSame( $derived->getUserObj(), $user, 'different' );
		$this->assertNull( $derived->getUserIdentity(), 'no user identity' );
	}

	public function testChangeDebug() {
		$derived = new DerivativeContext( self::makeContext() );
		$this->assertSame( 0, $derived->getDebug(), 'inherit from parent' );

		$derived->setDebug( 1 );
		$this->assertSame( 1, $derived->getDebug() );
	}

	public function testChangeOnly() {
		$derived = new DerivativeContext( self::makeContext() );
		$this->assertSame( 'scripts', $derived->getOnly(), 'inherit from parent' );

		$derived->setOnly( 'styles' );
		$this->assertSame( 'styles', $derived->getOnly() );

		$derived->setOnly( null );
		$this->assertNull( $derived->getOnly() );
	}

	public function testChangeVersion() {
		$derived = new DerivativeContext( self::makeContext() );
		$this->assertNull( $derived->getVersion() );

		$derived->setVersion( 'hw1' );
		$this->assertSame( 'hw1', $derived->getVersion() );
	}

	public function testChangeRaw() {
		$derived = new DerivativeContext( self::makeContext() );
		$this->assertFalse( $derived->getRaw(), 'inherit from parent' );

		$derived->setRaw( true );
		$this->assertTrue( $derived->getRaw() );
	}

	public function testChangeHash() {
		$derived = new DerivativeContext( self::makeContext() );
		$this->assertSame( 'qqx|fallback|0||scripts|||||', $derived->getHash(), 'inherit' );

		$derived->setLanguage( 'nl' );
		$derived->setUser( 'Example' );
		// Assert that subclass is able to clear parent class "hash" member
		$this->assertSame( 'nl|fallback|0|Example|scripts|||||', $derived->getHash() );
	}

	public function testChangeContentOverrides() {
		$derived = new DerivativeContext( self::makeContext() );
		$this->assertNull( $derived->getContentOverrideCallback(), 'default' );

		$override = static function ( Title $t ) {
			return null;
		};
		$derived->setContentOverrideCallback( $override );
		$this->assertSame( $override, $derived->getContentOverrideCallback(), 'changed' );

		$derived2 = new DerivativeContext( $derived );
		$this->assertSame(
			$override,
			$derived2->getContentOverrideCallback(),
			'change via a second derivative layer'
		);
	}

	public function testImmutableAccessors() {
		$context = self::makeContext();
		$derived = new DerivativeContext( $context );
		$this->assertSame( $derived->getRequest(), $context->getRequest() );
		$this->assertSame( $derived->getResourceLoader(), $context->getResourceLoader() );
	}
}
