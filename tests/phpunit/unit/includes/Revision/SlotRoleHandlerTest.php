<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\SlotRoleHandler;
use Title;

/**
 * @covers \MediaWiki\Revision\SlotRoleHandler
 */
class SlotRoleHandlerTest extends \MediaWikiUnitTestCase {

	/**
	 * @return Title
	 */
	private function makeBlankTitleObject() {
		return $this->createMock( Title::class );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleHandler::__construct
	 * @covers \MediaWiki\Revision\SlotRoleHandler::getRole()
	 * @covers \MediaWiki\Revision\SlotRoleHandler::getNameMessageKey()
	 * @covers \MediaWiki\Revision\SlotRoleHandler::getDefaultModel()
	 * @covers \MediaWiki\Revision\SlotRoleHandler::getOutputLayoutHints()
	 */
	public function testConstruction() {
		$handler = new SlotRoleHandler( 'foo', 'FooModel', [ 'frob' => 'niz' ] );
		$this->assertSame( 'foo', $handler->getRole() );
		$this->assertSame( 'slot-name-foo', $handler->getNameMessageKey() );

		$title = $this->makeBlankTitleObject();
		$this->assertSame( 'FooModel', $handler->getDefaultModel( $title ) );

		$hints = $handler->getOutputLayoutHints();
		$this->assertArrayHasKey( 'frob', $hints );
		$this->assertSame( 'niz', $hints['frob'] );

		$this->assertArrayHasKey( 'display', $hints );
		$this->assertArrayHasKey( 'region', $hints );
		$this->assertArrayHasKey( 'placement', $hints );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleHandler::__construct
	 * @covers \MediaWiki\Revision\SlotRoleHandler::isDerived
	 */
	public function testDerived() {
		$handler = new SlotRoleHandler( 'foo', 'FooModel', [ 'frob' => 'niz' ] );
		$this->assertFalse( $handler->isDerived() );

		$handler = new SlotRoleHandler( 'foo', 'FooModel', [ 'frob' => 'niz' ], false );
		$this->assertFalse( $handler->isDerived() );

		$handler = new SlotRoleHandler( 'foo', 'FooModel', [ 'frob' => 'niz' ], true );
		$this->assertTrue( $handler->isDerived() );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleHandler::isAllowedModel()
	 */
	public function testIsAllowedModel() {
		$handler = new SlotRoleHandler( 'foo', 'FooModel' );
		$this->assertTrue( $handler->isAllowedModel(
			'FooModel',
			PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' )
		) );
		$this->assertFalse( $handler->isAllowedModel(
			'QuaxModel',
			PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' ) )
		);
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleHandler::supportsArticleCount()
	 */
	public function testSupportsArticleCount() {
		$handler = new SlotRoleHandler( 'foo', 'FooModel' );

		$this->assertFalse( $handler->supportsArticleCount() );
	}

}
