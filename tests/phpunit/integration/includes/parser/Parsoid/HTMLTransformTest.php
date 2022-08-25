<?php

namespace MediaWiki\Tests\Parser\Parsoid;

use Composer\Semver\Semver;
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

	private function setOriginalData( HTMLTransform $transform ) {
		$transform->setOriginalRevisionId( 1 );
		$transform->setOriginalSchemaVersion( '2.4.0' );
		$transform->setOriginalHtml( self::ORIG_HTML );
		$transform->setOriginalDataMW( self::ORIG_DATA_MW );
		$transform->setOriginalDataParsoid( self::ORIG_DATA_PARSOID );
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

		// Set some options to assert on $transform object.
		$transform->setOptions( [
			'contentmodel' => 'wikitext',
			'offsetType' => 'byte',
		] );

		$this->setOriginalData( $transform );

		return $transform;
	}

	public function testGetOriginalBodyRequiresValidDataParsoid() {
		$transform = $this->createHTMLTransformWithHTML( self::ORIG_HTML );
		$transform->setOriginalSchemaVersion( '2.4.0' );
		$transform->setOriginalHtml( self::ORIG_HTML );

		// Invalid data-parsoid structure!
		$transform->setOriginalDataParsoid( [ 'foo' => 'bar' ] );

		$exception = new ClientError( 'Invalid data-parsoid was provided.' );
		$this->expectException( get_class( $exception ) );
		$this->expectExceptionMessage( $exception->getMessage() );

		// Should throw because setOriginalDataParsoid got bad data.
		// Note that setOriginalDataParsoid can't validate immediately, because it
		// may not know the schema version. The order in which the setters are called
		// should not matter. All checks happen in getters.
		$transform->getOriginalBody();
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

		$transform->setOriginalSchemaVersion( '999.0.0' );
		$transform->setOriginalHtml( $html );
		$transform->setOriginalDataParsoid( self::ORIG_DATA_PARSOID );

		// should automatically apply downgrade
		$oldBody = $transform->getOriginalBody();

		// all getters should now reflect the state after the downgrade.
		// we expect a version >= 2.4.0 and < 3.0.0. So use ^2.4.0
		Semver::satisfies( $transform->getOriginalSchemaVersion(), '^2.4.0' );
		$this->assertNotSame( $html, $transform->getOriginalHtml() );
		$this->assertNotSame( $oldBody, ContentUtils::toXML( $transform->getOriginalBody() ) );
	}

	public function testModifiedDataMW() {
		$html = $this->getTextFromFile( 'Minimal-999.html' ); // Uses profile version 2.4.0
		$transform = $this->createHTMLTransformWithHTML( $html );

		$transform->setOriginalHtml( self::ORIG_HTML );
		$transform->setOriginalDataParsoid( self::ORIG_DATA_PARSOID );
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

	public function testSetOriginalHTML() {
		$html = '<html><body>xyz</body></html>'; // no schema version!
		$transform = $this->createHTMLTransformWithHTML( $html );

		// mainly check that this doesn't explode.
		$transform->setOriginalSchemaVersion( '999.0.0' );
		$transform->setOriginalHtml( 'hi' );

		$this->assertTrue( $transform->hasOriginalHtml() );
		$this->assertFalse( $transform->hasOriginalDataParsoid() );
	}

	public function testSetOriginalDataParsoidAfterGetModified() {
		// Use HTML that contains a schema version!
		// Otherwise, we won't trigger the right error.
		$html = $this->getTextFromFile( 'Minimal.html' );
		$transform = $this->createHTMLTransformWithHTML( $html );

		$transform->getModifiedDocument();

		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( 'getModifiedDocument()' );

		$transform->setOriginalDataParsoid( [] );
	}

	public function testOffsetTypeMismatch() {
		$transform = $this->createHTMLTransformWithHTML( self::ORIG_HTML );
		$this->setOriginalData( $transform );

		// Set some options to assert on $transform.
		$transform->setOptions( [
			'contentmodel' => 'wikitext',
			'offsetType' => 'byte',
		] );

		$dataParsoid = self::ORIG_DATA_PARSOID;
		$dataParsoid['offsetType'] = 'UCS2';

		$transform->setOriginalDataParsoid( $dataParsoid );

		$this->expectException( ClientError::class );
		$transform->getOriginalBody();
	}
}
