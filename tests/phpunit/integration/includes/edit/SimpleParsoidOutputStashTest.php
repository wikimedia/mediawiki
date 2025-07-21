<?php

namespace MediaWiki\Tests\Unit\Edit;

use MediaWiki\Content\WikitextContent;
use MediaWiki\Edit\ParsoidRenderID;
use MediaWiki\Edit\SelserContext;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWiki\Json\JsonCodec;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

/**
 * @covers \MediaWiki\Edit\SimpleParsoidOutputStash
 * @covers \MediaWiki\Edit\SelserContext
 */
class SimpleParsoidOutputStashTest extends \MediaWikiIntegrationTestCase {

	public function testSetAndGetWithNoContent() {
		$codec = new JsonCodec( $this->getServiceContainer() );
		$stash = new SimpleParsoidOutputStash( $codec, new HashBagOStuff(), 12 );

		$key = new ParsoidRenderID( 7, 'acme' );
		$pageBundle = new HtmlPageBundle( '<p>Hello World</p>' );
		$selserContext = new SelserContext( $pageBundle, 7 );

		$stash->set( $key, $selserContext );
		$this->assertEquals( $selserContext, $stash->get( $key ) );
	}

	public function testSetAndGetWithContent() {
		$codec = new JsonCodec( $this->getServiceContainer() );
		$stash = new SimpleParsoidOutputStash( $codec, new HashBagOStuff(), 12 );

		$key = new ParsoidRenderID( 7, 'acme' );
		$pageBundle = new HtmlPageBundle( '<p>Hello World</p>' );

		$content = new WikitextContent( 'Hello World' );

		$selserContext = new SelserContext( $pageBundle, 7, $content );

		$stash->set( $key, $selserContext );

		$actual = $stash->get( $key );
		$this->assertEquals( $pageBundle, $actual->getPageBundle() );
		$this->assertEquals( 'Hello World', $actual->getContent()->getText() );
		$this->assertEquals( 7, $actual->getRevisionID() );
	}
}
