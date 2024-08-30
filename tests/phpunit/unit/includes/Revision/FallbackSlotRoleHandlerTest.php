<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\Revision\FallbackSlotRoleHandler;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Revision\FallbackSlotRoleHandler
 * @covers \MediaWiki\Revision\SlotRoleHandler
 */
class FallbackSlotRoleHandlerTest extends \MediaWikiUnitTestCase {

	public function testConstruction() {
		$handler = new FallbackSlotRoleHandler( 'foo' );
		$this->assertSame( 'foo', $handler->getRole() );
		$this->assertSame( 'slot-name-foo', $handler->getNameMessageKey() );

		$title = $this->createMock( Title::class );
		$this->assertSame( CONTENT_MODEL_UNKNOWN, $handler->getDefaultModel( $title ) );

		$hints = $handler->getOutputLayoutHints();
		$this->assertArrayHasKey( 'display', $hints );
		$this->assertArrayHasKey( 'region', $hints );
		$this->assertArrayHasKey( 'placement', $hints );
	}

	public function testIsAllowedModel() {
		$handler = new FallbackSlotRoleHandler( 'foo' );

		// For the fallback handler, no models are allowed
		$title = $this->createMock( Title::class );
		$this->assertFalse( $handler->isAllowedModel( 'FooModel', $title ) );
		$this->assertFalse( $handler->isAllowedModel( 'QuaxModel', $title ) );
	}

	public function testIsAllowedOn() {
		$handler = new FallbackSlotRoleHandler( 'foo' );

		$title = $this->createMock( Title::class );
		$this->assertFalse( $handler->isAllowedOn( $title ) );
	}

	public function testSupportsArticleCount() {
		$handler = new FallbackSlotRoleHandler( 'foo' );

		$this->assertFalse( $handler->supportsArticleCount() );
	}

}
