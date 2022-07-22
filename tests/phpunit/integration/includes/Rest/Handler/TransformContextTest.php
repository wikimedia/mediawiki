<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler\TransformContext;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Core\ClientError;

/**
 * @covers \MediaWiki\Rest\Handler\TransformContext
 */
class TransformContextTest extends MediaWikiIntegrationTestCase {

	private const ORIG_HTML = '<html><body>Original HTML</body></html>';
	private const ORIG_DATA_MW = '<html><body>Data-MW</body></html>';
	private const ORIG_DATA_PARSOID = '<html><body>Original Data-Parsoid</body></html>';
	private const MODIFIED_DATA_MW = '<html><body>Modified Data-MW</body></html>';

	public function newTransformContext() {
		return new TransformContext( [
			'opts' => [
				'from' => 'pagebundle',
				'contentmodel' => 'wikitext',
				'original' => [
					'html' => [
						'body' => self::ORIG_HTML,
						'headers' => [
							'content-type' => 'text/html; charset=utf-8',
						],
					],
					'data-mw' => [
						'body' => [ self::ORIG_DATA_MW ],
						'headers' => [
							'content-type' => 'application/json; charset=utf-8',
						],
					],
					'data-parsoid' => [
						'body' => [ self::ORIG_DATA_PARSOID ],
						'headers' => [
							'content-type' => 'application/json; charset=utf-8',
						],
					],
				],
				'data-mw' => [
					'body' => [ self::MODIFIED_DATA_MW ],
					'headers' => [
						'content-type' => 'application/json; charset=utf-8',
					],
				],
			],
			'envOptions' => [
				'offsetType' => 'byte',
			],
			'oldid' => 0,
		] );
	}

	public function testHasOriginalHtml() {
		$ctx = $this->newTransformContext();
		$this->assertTrue( $ctx->hasOriginalHtml() );
	}

	public function testGetOriginalSchemaVersion() {
		$this->expectException( ClientError::class );
		$this->expectExceptionMessage( 'Content-type of original html is missing.' );
		$ctx = $this->newTransformContext();
		$this->assertSame( '1.0', $ctx->getOriginalSchemaVersion() );
	}

	public function testInputIsPageBundle() {
		$ctx = $this->newTransformContext();
		$this->assertTrue( $ctx->inputIsPageBundle() );
	}

	public function testGetOriginalPageBundle() {
		$ctx = $this->newTransformContext();
		$this->expectException( ClientError::class );
		$this->expectExceptionMessage( 'Content-type of original html is missing.' );
		$this->assertSame(
			self::ORIG_HTML,
			$ctx->getOriginalPageBundle()->html
		);
	}

	public function testGetModifiedPageBundle() {
		$ctx = $this->newTransformContext();
		$this->assertSame(
			[ self::MODIFIED_DATA_MW ],
			$ctx->getModifiedPageBundle( '1.1' )->mw
		);
	}

	public function testHasModifiedDataMw() {
		$ctx = $this->newTransformContext();
		$this->assertTrue( $ctx->hasModifiedDataMw() );
	}

	public function testOldId() {
		$ctx = $this->newTransformContext();
		$this->assertSame( 0, $ctx->getOldId() );
	}

	public function testGetContentModel() {
		$ctx = $this->newTransformContext();
		$this->assertSame( 'wikitext', $ctx->getContentModel() );
	}

	public function testGetEnvOpts() {
		$ctx = $this->newTransformContext();
		$this->assertSame( 'byte', $ctx->getEnvironmentOffsetType() );
	}
}
