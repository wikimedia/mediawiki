<?php

namespace MediaWiki\Tests\Unit\Edit;

use HashBagOStuff;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Edit\SelserContext;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use TextContentHandler;
use Wikimedia\Parsoid\Core\PageBundle;
use WikitextContent;

/**
 * @covers \MediaWiki\Edit\SimpleParsoidOutputStash
 * @covers \MediaWiki\Edit\SelserContext
 */
class SimpleParsoidOutputStashTest extends \MediaWikiUnitTestCase {

	public function testSetAndGetWithNoContent() {
		$chFactory = $this->createNoOpMock( IContentHandlerFactory::class );
		$stash = new SimpleParsoidOutputStash( $chFactory, new HashBagOStuff(), 12 );

		$key = new ParsoidRenderID( 7, 'acme' );
		$pageBundle = new PageBundle( '<p>Hello World</p>' );
		$selserContext = new SelserContext( $pageBundle, 7 );

		$stash->set( $key, $selserContext );
		$this->assertEquals( $selserContext, $stash->get( $key ) );
	}

	public function testSetAndGetWithContent() {
		$contentHandler = $this->createNoOpMock( TextContentHandler::class, [ 'unserializeContent' ] );
		$contentHandler->method( 'unserializeContent' )->willReturnCallback( static function ( $data ) {
			return new WikitextContent( $data );
		} );

		$chFactory = $this->createNoOpMock( IContentHandlerFactory::class, [ 'getContentHandler' ] );
		$chFactory->method( 'getContentHandler' )
			->with( CONTENT_MODEL_WIKITEXT )
			->willReturn(
				$contentHandler
			);

		$stash = new SimpleParsoidOutputStash( $chFactory, new HashBagOStuff(), 12 );

		$key = new ParsoidRenderID( 7, 'acme' );
		$pageBundle = new PageBundle( '<p>Hello World</p>' );

		$content = $this->createNoOpMock( WikitextContent::class, [ 'getModel', 'serialize' ] );
		$content->method( 'getModel' )->willReturn( CONTENT_MODEL_WIKITEXT );
		$content->method( 'serialize' )->willReturn( 'Hello World' );

		$selserContext = new SelserContext( $pageBundle, 7, $content );

		$stash->set( $key, $selserContext );

		$actual = $stash->get( $key );
		$this->assertEquals( $pageBundle, $actual->getPageBundle() );
		$this->assertEquals( 'Hello World', $actual->getContent()->getText() );
		$this->assertEquals( 7, $actual->getRevisionID() );
	}

}
