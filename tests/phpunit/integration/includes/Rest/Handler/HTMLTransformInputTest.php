<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LogicException;
use MediaWiki\Rest\Handler\HTMLTransformInput;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Utils\ContentUtils;

/**
 * @covers \MediaWiki\Rest\Handler\HTMLTransformInput
 */
class HTMLTransformInputTest extends MediaWikiIntegrationTestCase {

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

	private function newHTMLTransformInput() {
		$input = new HTMLTransformInput( self::ORIG_HTML );
		$originalData = $this->getOriginalData();

		// Set some options to assert on $input.
		$input->setOptions( [
			'contentmodel' => 'wikitext',
			'offsetType' => 'byte',
		] );

		$input->setOriginalRevisionId( $originalData['revid'] );
		$input->setOriginalData( $originalData );

		return $input;
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
		$input = new HTMLTransformInput( self::ORIG_HTML );

		$this->expectException( get_class( $exception ) );
		$this->expectExceptionMessage( $exception->getMessage() );
		$input->setOriginalData( $original );
		$input->getOriginalSchemaVersion();
		$input->getOriginalPageBundle();
	}

	public function testHasOriginalHtml() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertTrue( $ctx->hasOriginalHtml() );
	}

	public function testGetOriginalSchemaVersion() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame( '2.4.0', $ctx->getOriginalSchemaVersion() );
	}

	public function testGetSchemaVersion() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame( '2.4.0', $ctx->getSchemaVersion() );
	}

	public function testHasOriginalDataParsoid() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertTrue( $ctx->hasOriginalDataParsoid() );
	}

	public function testGetOriginalPageBundle() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame(
			self::ORIG_HTML,
			$ctx->getOriginalPageBundle()->html
		);
	}

	public function testGetOriginalHtml() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame(
			self::ORIG_BODY,
			$ctx->getOriginalHtml()
		);
	}

	public function testGetOriginalBody() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame(
			self::ORIG_BODY,
			ContentUtils::toXML( $ctx->getOriginalBody() )
		);
	}

	public function testOldId() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame( 1, $ctx->getOriginalRevisionId() );
	}

	public function testGetContentModel() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame( 'wikitext', $ctx->getContentModel() );
	}

	public function testGetEnvOpts() {
		$ctx = $this->newHTMLTransformInput();
		$this->assertSame( 'byte', $ctx->getOffsetType() );
	}

	private function getTextFromFile( string $name ): string {
		return trim( file_get_contents( __DIR__ . "/data/Transform/$name" ) );
	}

	public function testDowngrade() {
		$html = $this->getTextFromFile( 'Minimal.html' ); // Uses profile version 2.4.0
		$input = new HTMLTransformInput( $html );

		$original = $this->getOriginalData();

		// Specify newer profile version for original HTML
		$original['html']['headers']['content-type'] = 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"';

		// The profile version given inline in the original HTML doesn't matter, it's ignored
		$original['html']['body'] = $html;

		$input->setOriginalData( $original );

		// should automatically apply downgrade
		$oldBody = $input->getOriginalBody();

		// all getters should now reflect the state after the downgrade.
		$this->assertSame( '2.5.0', $input->getOriginalSchemaVersion() );
		$this->assertNotSame( $html, $input->getOriginalHtml() );
		$this->assertNotSame( $oldBody, ContentUtils::toXML( $input->getOriginalBody() ) );
	}

	public function testModifiedDataMW() {
		$html = $this->getTextFromFile( 'Minimal-999.html' ); // Uses profile version 2.4.0
		$input = new HTMLTransformInput( $html );

		$original = $this->getOriginalData();

		$input->setOriginalData( $original );
		$input->setModifiedDataMW( self::MODIFIED_DATA_MW );

		// should automatically apply downgrade
		$doc = $input->getModifiedDocument();
		$html = ContentUtils::toXML( $doc );

		// all getters should now reflect the state after the downgrade.
		$this->assertNotSame( '"hi"', $html );
	}

	public function testMetrics() {
		$html = '<html><body>xyz</body></html>'; // no schema version!
		$input = new HTMLTransformInput( $html );

		$metrics = $this->createNoOpMock( StatsdDataFactoryInterface::class, [ 'increment' ] );
		$metrics->expects( $this->atLeastOnce() )->method( 'increment' );
		$input->setMetrics( $metrics );

		// getSchemaVersion should ioncrement the html2wt.original.version.notinline counter
		// because the input HTML doesn't contain a schema version.
		$input->getSchemaVersion();
	}

	public function testHtmlSize() {
		$html = '<html><body>hällö</body></html>'; // use some multi-byte characters
		$input = new HTMLTransformInput( $html );

		// make sure it counts characters, not bytes
		$this->assertSame( 31, $input->getModifiedHtmlSize() );
	}

	public function testSetOriginalWikitext() {
		$html = '<html><body>xyz</body></html>'; // no schema version!
		$input = new HTMLTransformInput( $html );

		$originalData = [
			'wikitext' => [ 'body' => 'hi' ],
		];

		// mainly check that this doesn't explode.
		$input->setOriginalData( $originalData );

		$this->assertFalse( $input->hasOriginalHtml() );
		$this->assertFalse( $input->hasOriginalDataParsoid() );
	}

	public function testSetOriginalHTML() {
		$html = '<html><body>xyz</body></html>'; // no schema version!
		$input = new HTMLTransformInput( $html );

		$originalData = [
			'html' => [
				'headers' => [
					'content-type' => 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"',
				],
				'body' => 'hi',
			],
		];

		// mainly check that this doesn't explode.
		$input->setOriginalData( $originalData );

		$this->assertTrue( $input->hasOriginalHtml() );
		$this->assertFalse( $input->hasOriginalDataParsoid() );
	}

	public function testSetOriginalDataTwice() {
		$html = '<html><body>xyz</body></html>'; // no schema version!
		$input = new HTMLTransformInput( $html );

		$originalData = $this->getOriginalData();
		$input->setOriginalData( $originalData );

		$this->expectException( LogicException::class );
		$input->setOriginalData( $originalData );
	}

	public function testSetOriginalDataAfterGetModified() {
		// Use HTML that contains a schema version!
		// Otherwise, we won't trigger the right error.
		$html = $this->getTextFromFile( 'Minimal.html' );
		$input = new HTMLTransformInput( $html );

		$input->getModifiedDocument();

		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( 'getModifiedDocument()' );

		$originalData = $this->getOriginalData();
		$input->setOriginalData( $originalData );
	}

	public function testOffsetTypeMismatch() {
		$input = new HTMLTransformInput( self::ORIG_HTML );
		$originalData = $this->getOriginalData();

		// Set some options to assert on $input.
		$input->setOptions( [
			'contentmodel' => 'wikitext',
			'offsetType' => 'byte',
		] );

		$originalData['data-parsoid']['body']['offsetType'] = 'UCS2';

		$input->setOriginalData( $originalData );

		$this->expectException( ClientError::class );
		$input->getOriginalPageBundle();
	}
}
