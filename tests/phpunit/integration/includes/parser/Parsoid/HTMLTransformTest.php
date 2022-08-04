<?php

namespace MediaWiki\Tests\Parser\Parsoid;

use Exception;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LogicException;
use MediaWiki\Parser\Parsoid\HTMLTransform;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Mocks\MockPageConfig;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\ContentUtils;

/**
 * @covers \MediaWiki\Parser\Parsoid\HTMLTransform
 */
class HTMLTransformTest extends MediaWikiIntegrationTestCase {

	private const ORIG_BODY = '<body>Original HTML</body>';
	private const ORIG_HTML = '<html>' . self::ORIG_BODY . '</html>';
	private const ORIG_DATA_MW = [ 'ids' => [ 'mwAQ' => [] ] ];
	private const ORIG_DATA_PARSOID = [ 'ids' => [ 'mwAQ' => [ 'pi' => [ [ [ 'k' => '1' ] ] ] ] ] ];
	private const MODIFIED_DATA_MW = [ 'ids' => [ 'mwAQ' => [
		'parts' => [ [
			'template' => [
				'target' => [ 'wt' => '1x', 'href' => './Template:1x' ],
				'params' => [ '1' => [ 'wt' => 'hi' ] ],
				'i' => 0
			]
		] ]
	] ] ];

	private function getOriginalData() {
		$profileVersion = '2.4.0';
		$htmlProfileUri = 'https://www.mediawiki.org/wiki/Specs/HTML/' . $profileVersion;
		$dataParsoidProfileUri = 'https://www.mediawiki.org/wiki/Specs/data-parsoid/' . $profileVersion;
		$dataMwProfileUri = 'https://www.mediawiki.org/wiki/Specs/data-mw/' . $profileVersion;

		$htmlContentType = "text/html;profile=\"$htmlProfileUri\"";
		$dataParsoidContentType = "application/json;profile=\"$dataParsoidProfileUri\"";
		$dataMwContentType = "application/json;profile=\"$dataMwProfileUri\"";

		return [
			'revid' => 1,
			'html' => [
				'body' => self::ORIG_HTML,
				'headers' => [
					'content-type' => $htmlContentType,
				],
			],
			'data-mw' => [
				'body' => self::ORIG_DATA_MW,
				'headers' => [
					'content-type' => $dataMwContentType,
				],
			],
			'data-parsoid' => [
				'body' => self::ORIG_DATA_PARSOID,
				'headers' => [
					'content-type' => $dataParsoidContentType,
				],
			],
		];
	}

	private function createHTMLTransformWithHTML( $html = '' ) {
		return new HTMLTransform(
			$html ?? self::ORIG_HTML,
			new MockPageConfig( [], null ),
			new Parsoid(
				$this->getServiceContainer()->getParsoidSiteConfig(),
				$this->getServiceContainer()->getParsoidDataAccess()
			),
			[]
		);
	}

	private function newHTMLTransform() {
		$transform = $this->createHTMLTransformWithHTML();
		$originalData = $this->getOriginalData();

		// Set some options to assert on $transform object.
		$transform->setOptions( [
			'contentmodel' => 'wikitext',
			'offsetType' => 'byte',
		] );

		$transform->setOriginalRevisionId( $originalData['revid'] );
		$transform->setOriginalData( $originalData );

		return $transform;
	}

	public function provideBadOriginalData() {
		$base = $this->getOriginalData();

		$orig = $base;
		unset( $orig['html']['headers']['content-type'] );
		yield 'no html content type' => [
			$orig,
			new ClientError( 'Content-type of original html is missing.' )
		];

		$orig = $base;
		$orig['html']['headers']['content-type'] = 'xyz';
		yield 'bad html content type' => [
			$orig,
			new ClientError( 'Content-type of original html is missing.' )
		];

		$orig = $base;
		unset( $orig['data-parsoid']['body']['ids'] );
		yield 'missing ids key in data-parsoid' => [
			$orig,
			new ClientError( 'Invalid data-parsoid was provided.' )
		];
	}

	/**
	 * @dataProvider provideBadOriginalData
	 *
	 * @param array $original
	 * @param Exception $exception
	 *
	 * @return void
	 * @throws ClientError
	 */
	public function testBadOriginalData( array $original, Exception $exception ) {
		$transform = $this->createHTMLTransformWithHTML();

		$this->expectException( get_class( $exception ) );
		$this->expectExceptionMessage( $exception->getMessage() );
		$transform->setOriginalData( $original );
		$transform->getOriginalSchemaVersion();
		$transform->getOriginalPageBundle();
	}

	public function testHasOriginalHtml() {
		$ctx = $this->newHTMLTransform();
		$this->assertTrue( $ctx->hasOriginalHtml() );
	}

	public function testGetOriginalSchemaVersion() {
		$ctx = $this->newHTMLTransform();
		$this->assertSame( '2.4.0', $ctx->getOriginalSchemaVersion() );
	}

	public function testGetSchemaVersion() {
		$ctx = $this->newHTMLTransform();
		$this->assertSame( '2.4.0', $ctx->getSchemaVersion() );
	}

	public function testHasOriginalDataParsoid() {
		$ctx = $this->newHTMLTransform();
		$this->assertTrue( $ctx->hasOriginalDataParsoid() );
	}

	public function testGetOriginalPageBundle() {
		$ctx = $this->newHTMLTransform();
		$this->assertSame(
			self::ORIG_HTML,
			$ctx->getOriginalPageBundle()->html
		);
	}

	public function testGetOriginalHtml() {
		$ctx = $this->newHTMLTransform();
		$this->assertSame(
			self::ORIG_BODY,
			$ctx->getOriginalHtml()
		);
	}

	public function testGetOriginalBody() {
		$ctx = $this->newHTMLTransform();
		$this->assertSame(
			self::ORIG_BODY,
			ContentUtils::toXML( $ctx->getOriginalBody() )
		);
	}

	public function testOldId() {
		$ctx = $this->newHTMLTransform();
		$this->assertSame( 1, $ctx->getOriginalRevisionId() );
	}

	public function testGetContentModel() {
		$ctx = $this->newHTMLTransform();
		$this->assertSame( 'wikitext', $ctx->getContentModel() );
	}

	public function testGetEnvOpts() {
		$ctx = $this->newHTMLTransform();
		$this->assertSame( 'byte', $ctx->getOffsetType() );
	}

	private function getTextFromFile( string $name ): string {
		return trim( file_get_contents( __DIR__ . "/data/Transform/$name" ) );
	}

	public function testDowngrade() {
		$html = $this->getTextFromFile( 'Minimal.html' ); // Uses profile version 2.4.0
		$transform = $this->createHTMLTransformWithHTML( $html );

		$original = $this->getOriginalData();

		// Specify newer profile version for original HTML
		$original['html']['headers']['content-type'] = 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"';

		// The profile version given inline in the original HTML doesn't matter, it's ignored
		$original['html']['body'] = $html;

		$transform->setOriginalData( $original );

		// should automatically apply downgrade
		$oldBody = $transform->getOriginalBody();

		// all getters should now reflect the state after the downgrade.
		$this->assertSame( '2.5.0', $transform->getOriginalSchemaVersion() );
		$this->assertNotSame( $html, $transform->getOriginalHtml() );
		$this->assertNotSame( $oldBody, ContentUtils::toXML( $transform->getOriginalBody() ) );
	}

	public function testModifiedDataMW() {
		$html = $this->getTextFromFile( 'Minimal-999.html' ); // Uses profile version 2.4.0
		$transform = $this->createHTMLTransformWithHTML( $html );

		$original = $this->getOriginalData();

		$transform->setOriginalData( $original );
		$transform->setModifiedDataMW( self::MODIFIED_DATA_MW );

		// should automatically apply downgrade
		$doc = $transform->getModifiedDocument();
		$html = ContentUtils::toXML( $doc );

		// all getters should now reflect the state after the downgrade.
		$this->assertNotSame( '"hi"', $html );
	}

	public function testMetrics() {
		$html = '<html><body>xyz</body></html>'; // no schema version!
		$transform = $this->createHTMLTransformWithHTML( $html );

		$metrics = $this->createNoOpMock( StatsdDataFactoryInterface::class, [ 'increment' ] );
		$metrics->expects( $this->atLeastOnce() )->method( 'increment' );
		$transform->setMetrics( $metrics );

		// getSchemaVersion should ioncrement the html2wt.original.version.notinline counter
		// because the input HTML doesn't contain a schema version.
		$transform->getSchemaVersion();
	}

	public function testHtmlSize() {
		$html = '<html><body>hällö</body></html>'; // use some multi-byte characters
		$transform = $this->createHTMLTransformWithHTML( $html );

		// make sure it counts characters, not bytes
		$this->assertSame( 31, $transform->getModifiedHtmlSize() );
	}

	public function testSetOriginalWikitext() {
		$html = '<html><body>xyz</body></html>'; // no schema version!
		$transform = $this->createHTMLTransformWithHTML( $html );

		$originalData = [
			'wikitext' => [ 'body' => 'hi' ],
		];

		// mainly check that this doesn't explode.
		$transform->setOriginalData( $originalData );

		$this->assertFalse( $transform->hasOriginalHtml() );
		$this->assertFalse( $transform->hasOriginalDataParsoid() );
	}

	public function testSetOriginalHTML() {
		$html = '<html><body>xyz</body></html>'; // no schema version!
		$transform = $this->createHTMLTransformWithHTML( $html );

		$originalData = [
			'html' => [
				'headers' => [
					'content-type' => 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"',
				],
				'body' => 'hi',
			],
		];

		// mainly check that this doesn't explode.
		$transform->setOriginalData( $originalData );

		$this->assertTrue( $transform->hasOriginalHtml() );
		$this->assertFalse( $transform->hasOriginalDataParsoid() );
	}

	public function testSetOriginalDataTwice() {
		$html = '<html><body>xyz</body></html>'; // no schema version!
		$transform = $this->createHTMLTransformWithHTML( $html );

		$originalData = $this->getOriginalData();
		$transform->setOriginalData( $originalData );

		$this->expectException( LogicException::class );
		$transform->setOriginalData( $originalData );
	}

	public function testSetOriginalDataAfterGetModified() {
		// Use HTML that contains a schema version!
		// Otherwise, we won't trigger the right error.
		$html = $this->getTextFromFile( 'Minimal.html' );
		$transform = $this->createHTMLTransformWithHTML( $html );

		$transform->getModifiedDocument();

		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( 'getModifiedDocument()' );

		$originalData = $this->getOriginalData();
		$transform->setOriginalData( $originalData );
	}

	public function testOffsetTypeMismatch() {
		$transform = $this->createHTMLTransformWithHTML();
		$originalData = $this->getOriginalData();

		// Set some options to assert on $transform.
		$transform->setOptions( [
			'contentmodel' => 'wikitext',
			'offsetType' => 'byte',
		] );

		$originalData['data-parsoid']['body']['offsetType'] = 'UCS2';

		$transform->setOriginalData( $originalData );

		$this->expectException( ClientError::class );
		$transform->getOriginalPageBundle();
	}
}
