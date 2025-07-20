<?php

namespace MediaWiki\Tests\Integration\Edit;

use MediaWiki\Content\Content;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Edit\SelserContext;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWikiIntegrationTestCase;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Tests\SerializationTestTrait;

/**
 * @covers \MediaWiki\Edit\SimpleParsoidOutputStash
 * @covers \MediaWiki\Edit\SelserContext
 */
class SimpleParsoidOutputStashSerializationTest extends MediaWikiIntegrationTestCase {
	use SerializationTestTrait;

	public static function getClassToTest(): string {
		return SelserContext::class;
	}

	public static function getSerializedDataPath(): string {
		return __DIR__ . '/../../../data/SelserContext';
	}

	public static function getTestInstancesAndAssertions(): array {
		return [
			'basic' => [
				'instance' => new SelserContext(
					new HtmlPageBundle(
						'<b>html</b>',
						[
							'counter' => 1234,
							'offsetType' => 'byte',
							'ids' => [
								'mwAA' => [ 'parsoid' => true ],
							],
						],
						[
							'ids' => [
								'mwAA' => [ 'mw' => true ],
							],
						],
						'1.2.3.4',
						[
							'X-Header-Test' => 'header test',
						],
						CONTENT_MODEL_WIKITEXT,
					),
					5678, /* revision */
					new WikitextContent( 'wiki wiki wiki' )
				),
				'assertions' => static function ( MediaWikiIntegrationTestCase $testCase, SelserContext $ss ) {
					$pb = $ss->getPageBundle();
					$testCase->assertSame( '<b>html</b>', $pb->html );
					$testCase->assertSame( [
						'counter' => 1234,
						'offsetType' => 'byte',
						'ids' => [
							'mwAA' => [ 'parsoid' => true ],
						],
					], $pb->parsoid );
					$testCase->assertSame( [
						'ids' => [
							'mwAA' => [ 'mw' => true ],
						],
					], $pb->mw );
					$testCase->assertSame( '1.2.3.4', $pb->version );
					$testCase->assertSame( [
						'X-Header-Test' => 'header test',
					], $pb->headers );
					$testCase->assertSame( CONTENT_MODEL_WIKITEXT, $pb->contentmodel );

					$testCase->assertSame( 5678, $ss->getRevisionID() );

					$content = $ss->getContent();
					$testCase->assertSame( CONTENT_MODEL_WIKITEXT, $content->getModel() );
					$testCase->assertSame( 'wiki wiki wiki', $content->getText() );
				},
			],
		];
	}

	public static function getSupportedSerializationFormats(): array {
		$stash = new SimpleParsoidOutputStash(
			new class implements IContentHandlerFactory {
				public function getContentHandler( string $modelID ): ContentHandler {
					return new class( CONTENT_MODEL_WIKITEXT, [ CONTENT_FORMAT_WIKITEXT ] ) extends ContentHandler {
						public function serializeContent( Content $content, $format = null ) {
							return $content->getText();
						}

						public function unserializeContent( $blob, $format = null ) {
							return new WikitextContent( $blob );
						}

						public function makeEmptyContent() {
							throw new \Error( "unimplemented" );
						}
					};
				}

				public function getContentModels(): array {
					return [ CONTENT_MODEL_WIKITEXT ];
				}

				public function getAllContentFormats(): array {
					return [ CONTENT_FORMAT_WIKITEXT ];
				}

				public function isDefinedModel( string $modelId ): bool {
					return $modelId === CONTENT_MODEL_WIKITEXT;
				}
			},
			new HashBagOStuff(),
			10000
		);
		$wrapper = TestingAccessWrapper::newFromObject( $stash );
		return [
			[
				'ext' => 'serialized',
				'serializer' => static function ( $obj ) use ( $wrapper ) {
					return serialize( $wrapper->selserContextToJsonArray( $obj ) );
				},
				'deserializer' => static function ( $data ) use ( $wrapper ) {
					return $wrapper->newSelserContextFromJson( unserialize( $data ) );
				},
			],
			[
				'ext' => 'json',
				'serializer' => static function ( $obj ) use ( $wrapper ) {
					return json_encode( $wrapper->selserContextToJsonArray( $obj ) );
				},
				'deserializer' => static function ( $data ) use ( $wrapper ) {
					return $wrapper->newSelserContextFromJson( json_decode( $data, true ) );
				},
			],
		];
	}

}
