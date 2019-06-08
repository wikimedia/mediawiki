<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\Revision\SlotRoleHandler;
use MediaWikiTestCase;
use Title;

/**
 * @covers \MediaWiki\Revision\SlotRoleHandler
 */
class SlotRoleHandlerTest extends MediaWikiTestCase {

	private function makeBlankTitleObject() {
		/** @var Title $title */
		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();

		return $title;
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
	 * @covers \MediaWiki\Revision\SlotRoleHandler::isAllowedModel()
	 */
	public function testIsAllowedModel() {
		$handler = new SlotRoleHandler( 'foo', 'FooModel' );

		$title = $this->makeBlankTitleObject();
		$this->assertTrue( $handler->isAllowedModel( 'FooModel', $title ) );
		$this->assertFalse( $handler->isAllowedModel( 'QuaxModel', $title ) );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleHandler::supportsArticleCount()
	 */
	public function testSupportsArticleCount() {
		$handler = new SlotRoleHandler( 'foo', 'FooModel' );

		$this->assertFalse( $handler->supportsArticleCount() );
	}

}
