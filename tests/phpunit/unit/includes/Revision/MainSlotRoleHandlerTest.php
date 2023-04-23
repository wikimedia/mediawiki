<?php

namespace MediaWiki\Tests\Unit\Revision;

use ContentHandler;
use MediaWiki\Revision\MainSlotRoleHandler;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWikiUnitTestCase;
use MockTitleTrait;

/**
 * @covers \MediaWiki\Revision\MainSlotRoleHandler
 */
class MainSlotRoleHandlerTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;
	use MockTitleTrait;

	/**
	 * @param string[] $namespaceContentModels
	 * @return MainSlotRoleHandler
	 */
	private function getRoleHandler( array $namespaceContentModels ) {
		// The ContentHandlerFactory is only used for retrieving the content handler
		// for the model in ::isAllowedModel(), so that that content handler can have
		// ::canBeUsedOn() called. We only have tests for ::isAllowedModel() with
		// CONTENT_MODEL_WIKITEXT and CONTENT_MODEL_TEXT, and neither TextContentHandler
		// or WikitextContentHandler overrides the method ::canBeUsedOn(), instead using
		// the default in ContentHandler::canBeUsedOn of returning true unless a hook
		// says otherwise. That hook is called via ProtectedHookAccessorTrait and
		// thus uses MediaWikiServices, which we can't have in this unit test. But, since
		// we aren't testing anything with the hooks, we can just return a mock content
		// handler that returns true directly
		$contentHandler = $this->createMock( ContentHandler::class );
		$contentHandler->method( 'canBeUsedOn' )->willReturn( true );
		$contentHandlerFactory = $this->getDummyContentHandlerFactory( [
			CONTENT_MODEL_WIKITEXT => $contentHandler,
			CONTENT_MODEL_TEXT => $contentHandler,
		] );

		// TitleFactory that for these tests is only called with Title objects, so just
		// return them
		$titleFactory = $this->createMock( TitleFactory::class );
		$titleFactory->method( 'newFromLinkTarget' )
			->with( $this->isInstanceOf( Title::class ) )
			->willReturnArgument( 0 );
		$titleFactory->method( 'newFromPageIdentity' )
			->with( $this->isInstanceOf( Title::class ) )
			->willReturnArgument( 0 );

		return new MainSlotRoleHandler(
			$namespaceContentModels,
			$contentHandlerFactory,
			$this->createHookContainer(),
			$titleFactory
		);
	}

	/**
	 * @covers \MediaWiki\Revision\MainSlotRoleHandler::__construct
	 * @covers \MediaWiki\Revision\MainSlotRoleHandler::getRole()
	 * @covers \MediaWiki\Revision\MainSlotRoleHandler::getNameMessageKey()
	 * @covers \MediaWiki\Revision\MainSlotRoleHandler::getOutputLayoutHints()
	 */
	public function testConstruction() {
		$handler = $this->getRoleHandler( [] );
		$this->assertSame( SlotRecord::MAIN, $handler->getRole() );
		$this->assertSame( 'slot-name-main', $handler->getNameMessageKey() );

		$hints = $handler->getOutputLayoutHints();
		$this->assertArrayHasKey( 'display', $hints );
		$this->assertArrayHasKey( 'region', $hints );
		$this->assertArrayHasKey( 'placement', $hints );
	}

	/**
	 * @covers \MediaWiki\Revision\MainSlotRoleHandler::getDefaultModel()
	 */
	public function testGetDefaultModel() {
		$handler = $this->getRoleHandler(
			[ 100 => CONTENT_MODEL_TEXT ]
		);

		// For the main handler, the namespace determins the default model
		$titleMain = $this->makeMockTitle(
			'Article',
			[ 'namespace' => NS_MAIN ]
		);
		$this->assertSame( CONTENT_MODEL_WIKITEXT, $handler->getDefaultModel( $titleMain ) );

		$title100 = $this->makeMockTitle(
			'Other page',
			[ 'namespace' => 100 ]
		);
		$this->assertSame( CONTENT_MODEL_TEXT, $handler->getDefaultModel( $title100 ) );
	}

	/**
	 * @covers \MediaWiki\Revision\MainSlotRoleHandler::isAllowedModel()
	 */
	public function testIsAllowedModel() {
		$handler = $this->getRoleHandler( [] );

		// For the main handler, (nearly) all models are allowed
		$title = $this->makeMockTitle(
			'Article',
			[ 'namespace' => NS_MAIN ]
		);
		$this->assertTrue( $handler->isAllowedModel( CONTENT_MODEL_WIKITEXT, $title ) );
		$this->assertTrue( $handler->isAllowedModel( CONTENT_MODEL_TEXT, $title ) );
	}

	/**
	 * @covers \MediaWiki\Revision\MainSlotRoleHandler::supportsArticleCount()
	 */
	public function testSupportsArticleCount() {
		$handler = $this->getRoleHandler( [] );

		$this->assertTrue( $handler->supportsArticleCount() );
	}

}
