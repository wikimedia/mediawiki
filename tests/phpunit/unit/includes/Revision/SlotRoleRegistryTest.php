<?php

namespace MediaWiki\Tests\Unit\Revision;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Revision\MainSlotRoleHandler;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleHandler;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWikiUnitTestCase;
use Wikimedia\Assert\PostconditionException;

/**
 * @covers \MediaWiki\Revision\SlotRoleRegistry
 */
class SlotRoleRegistryTest extends MediaWikiUnitTestCase {

	private function makeNameTableStore( array $names = [] ) {
		$mock = $this->createMock( NameTableStore::class );

		$mock->method( 'getMap' )
			->willReturn( $names );

		return $mock;
	}

	private function newSlotRoleRegistry( ?NameTableStore $roleNameStore = null ) {
		if ( !$roleNameStore ) {
			$roleNameStore = $this->makeNameTableStore();
		}

		return new SlotRoleRegistry( $roleNameStore );
	}

	public function testDefineRole() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRole( 'FOO', static function ( $role ) {
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

		$title = $this->createMock( Title::class );
		$this->assertSame( 'FooModel', $handler->getDefaultModel( $title ) );
	}

	public function testDefineRoleFailsForDupe() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRole( 'foo', static function ( $role ) {
			return new SlotRoleHandler( $role, 'FooModel' );
		} );

		$this->expectException( LogicException::class );
		$registry->defineRole( 'FOO', static function ( $role ) {
			return new SlotRoleHandler( $role, 'FooModel' );
		} );
	}

	public function testDefineRoleWithContentModel() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRoleWithModel( 'FOO', 'FooModel' );

		$this->assertTrue( $registry->isDefinedRole( 'foo' ) );
		$this->assertContains( 'foo', $registry->getDefinedRoles() );
		$this->assertContains( 'foo', $registry->getKnownRoles() );

		$handler = $registry->getRoleHandler( 'foo' );
		$this->assertSame( 'foo', $handler->getRole() );

		/** @var Title $title */
		$title = $this->createMock( Title::class );
		$this->assertSame( 'FooModel', $handler->getDefaultModel( $title ) );
	}

	public function testGetRoleHandlerForUnknownModel() {
		$registry = $this->newSlotRoleRegistry();

		$this->expectException( InvalidArgumentException::class );

		$registry->getRoleHandler( 'foo' );
	}

	public function testGetRoleHandlerFallbackHandler() {
		$registry = $this->newSlotRoleRegistry(
			$this->makeNameTableStore( [ 1 => 'foo' ] )
		);

		$handler = @$registry->getRoleHandler( 'foo' );
		$this->assertSame( 'foo', $handler->getRole() );
	}

	public function testGetRoleHandlerWithBadInstantiator() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRole( 'foo', static function ( $role ) {
			return 'Not a SlotRoleHandler instance';
		} );

		$this->expectException( PostconditionException::class );
		$registry->getRoleHandler( 'foo' );
	}

	public function testGetRequiredRoles() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRole( SlotRecord::MAIN, function ( $role ) {
			return new MainSlotRoleHandler(
				[],
				$this->createMock( IContentHandlerFactory::class ),
				$this->createMock( HookContainer::class ),
				$this->createMock( TitleFactory::class )
			);
		} );

		$title = $this->createMock( Title::class );
		$this->assertEquals( [ SlotRecord::MAIN ], $registry->getRequiredRoles( $title ) );
	}

	public function testGetAllowedRoles() {
		$registry = $this->newSlotRoleRegistry();
		$registry->defineRole( SlotRecord::MAIN, function ( $role ) {
			return new MainSlotRoleHandler(
				[],
				$this->createMock( IContentHandlerFactory::class ),
				$this->createMock( HookContainer::class ),
				$this->createMock( TitleFactory::class )
			);
		} );
		$registry->defineRoleWithModel( 'FOO', CONTENT_MODEL_TEXT );

		$title = $this->createMock( Title::class );
		$this->assertEquals( [ SlotRecord::MAIN, 'foo' ], $registry->getAllowedRoles( $title ) );
	}

	public function testGetKnownRoles() {
		$registry = $this->newSlotRoleRegistry(
			$this->makeNameTableStore( [ 1 => 'foo' ] )
		);
		$registry->defineRoleWithModel( 'BAR', CONTENT_MODEL_TEXT );

		$this->assertTrue( $registry->isKnownRole( 'foo' ) );
		$this->assertTrue( $registry->isKnownRole( 'bar' ) );
		$this->assertTrue( $registry->isKnownRole( 'Bar' ) );
		$this->assertFalse( $registry->isKnownRole( 'xyzzy' ) );

		$this->assertArrayEquals( [ 'foo', 'bar' ], $registry->getKnownRoles() );
	}

}
