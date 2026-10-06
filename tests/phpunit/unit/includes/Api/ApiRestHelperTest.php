<?php

namespace MediaWiki\Tests\Unit\Api;

use MediaWiki\Api\ApiRestHelper;
use MediaWiki\Api\IApiMessage;
use MediaWiki\Rest\PathTemplateMatcher\ModuleConfigurationException;
use MediaWikiUnitTestCase;

/**
 * @group API
 * @covers \MediaWiki\Api\ApiRestHelper
 */
class ApiRestHelperTest extends MediaWikiUnitTestCase {

	private const DATA_PREFIX = __DIR__ . '/rest-helper-';

	/**
	 * Creates a minimal concrete helper, overriding only what is needed to
	 * exercise the base class.
	 */
	private function newHelper( ?string $schemaFile = null ): ApiRestHelper {
		return new class( $schemaFile ) extends ApiRestHelper {
			public function __construct( private readonly ?string $schemaFile ) {
			}

			public function getResponseBodySchemaFileName(): ?string {
				return $this->schemaFile;
			}
		};
	}

	public function testGetStatusForErrorMessage_defersToHandlerByDefault() {
		$helper = $this->newHelper();

		// 0 means "let ActionModuleBasedHandler decide".
		$this->assertSame(
			0,
			$helper->getStatusForErrorMessage( $this->createNoOpMock( IApiMessage::class ) )
		);
	}

	public function testGetResponseBodySchema_noFile() {
		$this->assertNull( $this->newHelper()->getResponseBodySchema() );
	}

	public function testGetResponseBodySchema_loadsFile() {
		$helper = $this->newHelper( schemaFile: self::DATA_PREFIX . 'body.json' );

		$this->assertSame(
			[ 'type' => 'object', 'properties' => [ 'name' => [ 'type' => 'string' ] ] ],
			$helper->getResponseBodySchema()
		);
	}

	public function testGetResponseBodySchema_malformedFile() {
		$helper = $this->newHelper( schemaFile: self::DATA_PREFIX . 'malformed.json' );

		$this->expectException( ModuleConfigurationException::class );
		$helper->getResponseBodySchema();
	}
}
