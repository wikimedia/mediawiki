<?php

namespace MediaWiki\Tests\Unit\Edit;

use MediaWiki\Content\ContentJsonCodec;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Content\WikitextContentHandler;
use MediaWiki\Edit\ParsoidRenderID;
use MediaWiki\Edit\SelserContext;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use Psr\Container\ContainerInterface;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

/**
 * @covers \MediaWiki\Edit\SimpleParsoidOutputStash
 * @covers \MediaWiki\Edit\SelserContext
 */
class SimpleParsoidOutputStashTest extends \MediaWikiUnitTestCase {
	use DummyServicesTrait;

	public function testSetAndGetWithNoContent() {
		$codec = $this->getJsonCodec( $this->getDummyContentHandlerFactory() );
		$stash = new SimpleParsoidOutputStash( $codec, new HashBagOStuff(), 12 );

		$key = new ParsoidRenderID( 7, 'acme' );
		$pageBundle = new HtmlPageBundle( '<p>Hello World</p>' );
		$selserContext = new SelserContext( $pageBundle, 7 );

		$stash->set( $key, $selserContext );
		$this->assertEquals( $selserContext, $stash->get( $key ) );
	}

	public function testSetAndGetWithContent() {
		$contentHandler = $this->createNoOpMock(
			WikitextContentHandler::class, [
				'serializeContentToJsonArray',
				'deserializeContentFromJsonArray',
			] );
		$contentHandler->method( 'serializeContentToJsonArray' )->willReturnCallback( static function ( $content ) {
			return [
				'format' => CONTENT_FORMAT_WIKITEXT,
				'blob' => 'Hello World',
			];
		} );
		$contentHandler->method( 'deserializeContentFromJsonArray' )->willReturnCallback( static function ( $data ) {
			return new WikitextContent( $data['blob'] );
		} );

		$chFactory = $this->getDummyContentHandlerFactory(
			[ CONTENT_MODEL_WIKITEXT => $contentHandler ]
		);
		$codec = $this->getJsonCodec( $chFactory );

		$stash = new SimpleParsoidOutputStash( $codec, new HashBagOStuff(), 12 );

		$key = new ParsoidRenderID( 7, 'acme' );
		$pageBundle = new HtmlPageBundle( '<p>Hello World</p>' );

		// Create a WikitextContent that uses the above ContentHandlerFactory
		$content = new class( 'Hello World', $chFactory ) extends WikitextContent {
			public function __construct( $text, private $chFactory ) {
				parent::__construct( $text );
			}

			protected function getContentHandlerFactory(): IContentHandlerFactory {
				return $this->chFactory;
			}
		};

		$selserContext = new SelserContext( $pageBundle, 7, $content );

		$stash->set( $key, $selserContext );

		$actual = $stash->get( $key );
		$this->assertEquals( $pageBundle, $actual->getPageBundle() );
		$this->assertEquals( 'Hello World', $actual->getContent()->getText() );
		$this->assertEquals( 7, $actual->getRevisionID() );
	}

	private function getJsonCodec( $contentHandlerFactory ) {
		return new JsonCodec( $this->getMockServices( [
			'ContentHandlerFactory' => $contentHandlerFactory,
			'ContentJsonCodec' => new ContentJsonCodec( $contentHandlerFactory ),
		] ) );
	}

	private function getMockServices( array $services ) {
		return new class( $services ) implements ContainerInterface {
			public function __construct( private array $services ) {
			}

			public function get( $id ) {
				return $this->services[$id] ?? null;
			}

			public function has( $id ): bool {
				return isset( $this->services[$id] );
			}

			public function set( $id, $value ) {
				$this->services[$id] = $value;
			}
		};
	}
}
