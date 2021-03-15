<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\Revision\MainSlotRoleHandler;
use PHPUnit\Framework\MockObject\MockObject;
use Title;

/**
 * @covers \MediaWiki\Revision\MainSlotRoleHandler
 *
 * TODO convert this to a Unit test
 */
class MainSlotRoleHandlerTest extends \MediaWikiIntegrationTestCase {

	/**
	 * @param int $ns
	 * @return Title|MockObject
	 */
	private function makeTitleObject( $ns ) {
		/** @var Title|MockObject $title */
		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();

		$title->method( 'getNamespace' )
			->willReturn( $ns );

		return $title;
	}

	/**
	 * @param string[] $namespaceContentModels
	 * @return MainSlotRoleHandler
	 */
	private function getRoleHandler( array $namespaceContentModels ) {
		$services = $this->getServiceContainer();
		return new MainSlotRoleHandler(
			$namespaceContentModels,
			$services->getContentHandlerFactory(),
			$services->getHookContainer(),
			$services->getTitleFactory()
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
		$this->assertSame( 'main', $handler->getRole() );
		$this->assertSame( 'slot-name-main', $handler->getNameMessageKey() );

		$hints = $handler->getOutputLayoutHints();
		$this->assertArrayHasKey( 'display', $hints );
		$this->assertArrayHasKey( 'region', $hints );
		$this->assertArrayHasKey( 'placement', $hints );
	}

	/**
	 * @covers \MediaWiki\Revision\MainSlotRoleHandler::getDefaultModel()
	 */
	public function testFetDefaultModel() {
		$handler = $this->getRoleHandler(
			[ 100 => CONTENT_MODEL_TEXT ]
		);

		// For the main handler, the namespace determins the default model
		$titleMain = $this->makeTitleObject( NS_MAIN );
		$this->assertSame( CONTENT_MODEL_WIKITEXT, $handler->getDefaultModel( $titleMain ) );

		$title100 = $this->makeTitleObject( 100 );
		$this->assertSame( CONTENT_MODEL_TEXT, $handler->getDefaultModel( $title100 ) );
	}

	/**
	 * @covers \MediaWiki\Revision\MainSlotRoleHandler::isAllowedModel()
	 */
	public function testIsAllowedModel() {
		$handler = $this->getRoleHandler( [] );

		// For the main handler, (nearly) all models are allowed
		$title = $this->makeTitleObject( NS_MAIN );
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
