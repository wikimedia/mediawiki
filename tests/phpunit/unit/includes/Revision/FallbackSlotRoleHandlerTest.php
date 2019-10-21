<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\Revision\FallbackSlotRoleHandler;
use Title;

/**
 * @covers \MediaWiki\Revision\FallbackSlotRoleHandler
 */
class FallbackSlotRoleHandlerTest extends \MediaWikiUnitTestCase {

	/**
	 * @return Title
	 */
	private function makeBlankTitleObject() {
		return $this->createMock( Title::class );
	}

	/**
	 * @covers \MediaWiki\Revision\FallbackSlotRoleHandler::__construct
	 * @covers \MediaWiki\Revision\FallbackSlotRoleHandler::getRole()
	 * @covers \MediaWiki\Revision\FallbackSlotRoleHandler::getNameMessageKey()
	 * @covers \MediaWiki\Revision\FallbackSlotRoleHandler::getDefaultModel()
	 * @covers \MediaWiki\Revision\FallbackSlotRoleHandler::getOutputLayoutHints()
	 */
	public function testConstruction() {
		$handler = new FallbackSlotRoleHandler( 'foo' );
		$this->assertSame( 'foo', $handler->getRole() );
		$this->assertSame( 'slot-name-foo', $handler->getNameMessageKey() );

		$title = $this->makeBlankTitleObject();
		$this->assertSame( CONTENT_MODEL_TEXT, $handler->getDefaultModel( $title ) );

		$hints = $handler->getOutputLayoutHints();
		$this->assertArrayHasKey( 'display', $hints );
		$this->assertArrayHasKey( 'region', $hints );
		$this->assertArrayHasKey( 'placement', $hints );
	}

	/**
	 * @covers \MediaWiki\Revision\FallbackSlotRoleHandler::isAllowedModel()
	 */
	public function testIsAllowedModel() {
		$handler = new FallbackSlotRoleHandler( 'foo', 'FooModel' );

		// For the fallback handler, no models are allowed
		$title = $this->makeBlankTitleObject();
		$this->assertFalse( $handler->isAllowedModel( 'FooModel', $title ) );
		$this->assertFalse( $handler->isAllowedModel( 'QuaxModel', $title ) );
	}

	/**
	 * @covers \MediaWiki\Revision\SlotRoleHandler::isAllowedModel()
	 */
	public function testIsAllowedOn() {
		$handler = new FallbackSlotRoleHandler( 'foo', 'FooModel' );

		$title = $this->makeBlankTitleObject();
		$this->assertFalse( $handler->isAllowedOn( $title ) );
	}

	/**
	 * @covers \MediaWiki\Revision\FallbackSlotRoleHandler::supportsArticleCount()
	 */
	public function testSupportsArticleCount() {
		$handler = new FallbackSlotRoleHandler( 'foo', 'FooModel' );

		$this->assertFalse( $handler->supportsArticleCount() );
	}

}
