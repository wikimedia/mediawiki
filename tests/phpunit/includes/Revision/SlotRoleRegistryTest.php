<?php

namespace MediaWiki\Tests\Revision;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Revision\MainSlotRoleHandler;
use MediaWiki\Revision\SlotRoleHandler;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\NameTableStore;
use MediaWikiIntegrationTestCase;
use Title;
use Wikimedia\Assert\PostconditionException;

/**
 * @covers \MediaWiki\Revision\SlotRoleRegistry
 */
class SlotRoleRegistryTest extends MediaWikiIntegrationTestCase {

	/**
	 * @return Title
	 */
	private function makeBlankTitleObject() {
		return $this->createMock( Title::class );
	}

	private function makeNameTableStore( array $names = [] ) {
		$mock = $this->getMockBuilder( NameTableStore::class )
			->disableOriginalConstructor()
			->getMock();

		$mock->method( 'getMap' )
			->willReturn( $names );

		return $mock;
	}

	private function newSlotRoleRegistry( NameTableStore $roleNameStore = null ) {
		if ( !$roleNameStore ) {
			$roleNameStore = $this->makeNameTableStore();
		}

		return new SlotRoleRegistry( $roleNameStore );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::defineRole()
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getDefinedRoles()
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getKnownRoles()
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getRoleHandler()
	 */
	public function testDefineRole() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRole( 'FOO', function ( $role ) {
			return new SlotRoleHandler( $role, 'FooModel' );
		} );

		$this->assertTrue( $registry->isDefinedRole( 'foo' ) );
		$this->assertTrue( $registry->isDefinedRole( 'Foo' ) );
		$this->assertContains( 'foo', $registry->getDefinedRoles() );
		$this->assertContains( 'foo', $registry->getKnownRoles() );
		$this->assertNotContains( 'FOO', $registry->getDefinedRoles() );
		$this->assertNotContains( 'FOO', $registry->getKnownRoles() );

		$handler = $registry->getRoleHandler( 'foo' );
		$this->assertSame( 'foo', $handler->getRole() );

		$handler = $registry->getRoleHandler( 'Foo' );
		$this->assertSame( 'foo', $handler->getRole() );

		$title = $this->makeBlankTitleObject();
		$this->assertSame( 'FooModel', $handler->getDefaultModel( $title ) );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::defineRole()
	 */
	public function testDefineRoleFailsForDupe() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRole( 'foo', function ( $role ) {
			return new SlotRoleHandler( $role, 'FooModel' );
		} );

		$this->expectException( LogicException::class );
		$registry->defineRole( 'FOO', function ( $role ) {
			return new SlotRoleHandler( $role, 'FooModel' );
		} );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::defineRoleWithModel()
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getDefinedRoles()
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getKnownRoles()
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getRoleHandler()
	 */
	public function testDefineRoleWithContentModel() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRoleWithModel( 'FOO', 'FooModel' );

		$this->assertTrue( $registry->isDefinedRole( 'foo' ) );
		$this->assertContains( 'foo', $registry->getDefinedRoles() );
		$this->assertContains( 'foo', $registry->getKnownRoles() );

		$handler = $registry->getRoleHandler( 'foo' );
		$this->assertSame( 'foo', $handler->getRole() );

		/** @var Title $title */
		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();
		$this->assertSame( 'FooModel', $handler->getDefaultModel( $title ) );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getRoleHandler()
	 */
	public function testGetRoleHandlerForUnknownModel() {
		$registry = $this->newSlotRoleRegistry();

		$this->expectException( InvalidArgumentException::class );

		$registry->getRoleHandler( 'foo' );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getRoleHandler()
	 */
	public function testGetRoleHandlerFallbackHandler() {
		$registry = $this->newSlotRoleRegistry(
			$this->makeNameTableStore( [ 1 => 'foo' ] )
		);

		\Wikimedia\suppressWarnings();
		$handler = $registry->getRoleHandler( 'foo' );
		$this->assertSame( 'foo', $handler->getRole() );

		\Wikimedia\restoreWarnings();
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getRoleHandler()
	 */
	public function testGetRoleHandlerWithBadInstantiator() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRole( 'foo', function ( $role ) {
			return 'Not a SlotRoleHandler instance';
		} );

		$this->expectException( PostconditionException::class );
		$registry->getRoleHandler( 'foo' );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getRequiredRoles()
	 */
	public function testGetRequiredRoles() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRole( 'main', function ( $role ) {
			return new MainSlotRoleHandler(
				[],
				$this->createMock( IContentHandlerFactory::class )
			);
		} );

		$title = $this->makeBlankTitleObject();
		$this->assertEquals( [ 'main' ], $registry->getRequiredRoles( $title ) );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getAllowedRoles()
	 */
	public function testGetAllowedRoles() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRole( 'main', function ( $role ) {
			return new MainSlotRoleHandler(
				[],
				$this->createMock( IContentHandlerFactory::class )
			);
		} );
		$registry->defineRoleWithModel( 'FOO', CONTENT_MODEL_TEXT );

		$title = $this->makeBlankTitleObject();
		$this->assertEquals( [ 'main', 'foo' ], $registry->getAllowedRoles( $title ) );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::getKnownRoles()
	 * @covers \MediaWiki\Revision\SlotRoleRegistry::isKnownRole()
	 */
	public function testGetKnownRoles() {
		$registry = $this->newSlotRoleRegistry(
			$this->makeNameTableStore( [ 1 => 'foo' ] )
		);
		$registry->defineRoleWithModel( 'BAR', CONTENT_MODEL_TEXT );

		$this->assertTrue( $registry->isKnownRole( 'foo' ) );
		$this->assertTrue( $registry->isKnownRole( 'bar' ) );
		$this->assertTrue( $registry->isKnownRole( 'Bar' ) );
		$this->assertFalse( $registry->isKnownRole( 'xyzzy' ) );

		$title = $this->makeBlankTitleObject();
		$this->assertArrayEquals( [ 'foo', 'bar' ], $registry->getKnownRoles( $title ) );
	}

}
