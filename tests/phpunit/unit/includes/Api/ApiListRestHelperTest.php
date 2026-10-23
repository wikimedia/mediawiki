<?php

namespace MediaWiki\Tests\Unit\Api;

use MediaWiki\Api\ApiListRestHelper;
use MediaWiki\Api\ApiRestHelperContext;
use MediaWikiUnitTestCase;
use RuntimeException;

/**
 * @group API
 * @covers \MediaWiki\Api\ApiListRestHelper
 */
class ApiListRestHelperTest extends MediaWikiUnitTestCase {

	private const DATA_PREFIX = __DIR__ . '/rest-helper-';

	private const ITEM_SCHEMA = [
		'type' => 'object',
		'required' => [ 'id' ],
		'properties' => [ 'id' => [ 'type' => 'integer' ] ],
	];

	/**
	 * Creates a minimal concrete helper. Parameters that are null leave the
	 * corresponding base class behavior (and default envelope) in place.
	 */
	private function newHelper(
		?string $itemSchemaFile = null,
		?string $envelopeFile = null
	): ApiListRestHelper {
		return new class( $itemSchemaFile, $envelopeFile ) extends ApiListRestHelper {
			public function __construct(
				?string $itemSchemaFile,
				private readonly ?string $envelopeFile
			) {
				parent::__construct( new ApiRestHelperContext( [], [] ), $itemSchemaFile );
			}

			public function getResponseBodySchemaFileName(): string {
				return $this->envelopeFile ?? parent::getResponseBodySchemaFileName();
			}
		};
	}

	/**
	 * Finds the schema of the "items" array in the allOf fragments of a list response schema.
	 */
	private function getItemsSchema( array $responseSchema ): array {
		foreach ( $responseSchema['allOf'] as $fragment ) {
			if ( isset( $fragment['properties']['items'] ) ) {
				return $fragment['properties']['items'];
			}
		}
		$this->fail( 'No "items" property found in the response schema' );
	}

	public function testGetResponseBodySchema_usesListEnvelopeByDefault() {
		$schema = $this->newHelper( itemSchemaFile: self::DATA_PREFIX . 'item.json' )->getResponseBodySchema();

		// The default envelope describes "items" as an array, with the schema
		// of the entries left open for the module to fill in.
		$items = $this->getItemsSchema( $schema );
		$this->assertSame( 'array', $items['type'] );
	}

	public function testGetResponseBodySchema_injectsItemSchema() {
		$helper = $this->newHelper( itemSchemaFile: self::DATA_PREFIX . 'item.json' );

		$items = $this->getItemsSchema( $helper->getResponseBodySchema() );

		// The essential assertion: the module's item schema ends up as the
		// schema of the entries of the "items" array.
		$this->assertSame( self::ITEM_SCHEMA, $items['items'] );
		$this->assertSame( 'array', $items['type'] );
	}

	public function testGetResponseBodySchema_injectsIntoCustomEnvelope() {
		$helper = $this->newHelper(
			itemSchemaFile: self::DATA_PREFIX . 'item.json',
			envelopeFile: self::DATA_PREFIX . 'list-envelope.json'
		);

		$items = $this->getItemsSchema( $helper->getResponseBodySchema() );

		$this->assertSame( self::ITEM_SCHEMA, $items['items'] );
	}

	public function testGetResponseItemSchema_noFile() {
		$this->expectException( RuntimeException::class );
		$this->newHelper()->getResponseItemSchema();
	}

	public function testGetResponseItemSchema_loadsFile() {
		$helper = $this->newHelper( itemSchemaFile: self::DATA_PREFIX . 'item.json' );

		$this->assertSame( self::ITEM_SCHEMA, $helper->getResponseItemSchema() );
	}

	public function testGetResponseBodySchema_noItemSchemaFile() {
		$this->expectException( RuntimeException::class );
		$this->newHelper()->getResponseBodySchema();
	}

	public function testGetItemStatus_defersToHandlerByDefault() {
		// 0 means "let GenericItemHandler decide".
		$this->assertSame( 0, $this->newHelper()->getItemStatus( [ 'name' => 'Foo', 'missing' => true ] ) );
	}

	public function testGetResponseBodySchema_envelopeWithoutItemsIsLeftAlone() {
		$helper = $this->newHelper(
			itemSchemaFile: self::DATA_PREFIX . 'item.json',
			envelopeFile: self::DATA_PREFIX . 'envelope-without-items.json'
		);

		// No slot for the item schema: the envelope must be returned as is,
		// and injecting must not fail.
		$this->assertSame(
			[ 'type' => 'object', 'properties' => [ 'count' => [ 'type' => 'integer' ] ] ],
			$helper->getResponseBodySchema()
		);
	}
}
