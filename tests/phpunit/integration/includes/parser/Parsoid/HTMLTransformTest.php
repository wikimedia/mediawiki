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
	private const MODIFIED_HTML = '<html><head>' .
	'<meta charset="utf-8"/><meta property="mw:htmlVersion" content="2.4.0"/></head>' .
	'<body>Modified HTML</body></html>';

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

	private function createHTMLTransform( $html = '' ) {
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

	private function createHTMLTransformWithOriginalData( $html = '' ) {
		$transform = $this->createHTMLTransform( $html );

		// Set some options to assert on $transform object.
		$transform->setOptions( [
			'contentmodel' => 'wikitext',
			'offsetType' => 'byte',
		] );

		$this->setOriginalData( $transform );

		return $transform;
	}

	public function testGetOriginalBodyRequiresValidDataParsoid() {
		$transform = $this->createHTMLTransform( self::ORIG_HTML );
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
		$transform = $this->createHTMLTransform( self::MODIFIED_HTML );
		$this->assertFalse( $transform->hasOriginalHtml() );

		$transform->setOriginalDataParsoid( self::ORIG_DATA_PARSOID );
		$this->assertFalse( $transform->hasOriginalHtml() );

		$transform->setOriginalHtml( self::ORIG_HTML );
		$this->assertTrue( $transform->hasOriginalHtml() );
	}

	public function testGetOriginalSchemaVersion() {
		$transform = $this->createHTMLTransform( self::ORIG_HTML ); // no version inline
		$this->assertSame( Parsoid::defaultHTMLVersion(), $transform->getOriginalSchemaVersion() );

		$transform = $this->createHTMLTransform( self::MODIFIED_HTML ); // has version inline
		$this->assertSame( '2.4.0', $transform->getOriginalSchemaVersion() );

		$transform->setOriginalSchemaVersion( '2.3.0' );
		$this->assertSame( '2.3.0', $transform->getOriginalSchemaVersion() );
	}

	public function testGetSchemaVersion() {
		$transform = $this->createHTMLTransform( self::ORIG_HTML ); // no version inline
		$this->assertSame( Parsoid::defaultHTMLVersion(), $transform->getSchemaVersion() );

		// Should have an effect, since the HTML has no version specified inline
		$transform->setOriginalSchemaVersion( '2.3.0' );
		$this->assertSame( '2.3.0', $transform->getSchemaVersion() );

		$transform = $this->createHTMLTransform( self::MODIFIED_HTML ); // has version inline
		$this->assertSame( '2.4.0', $transform->getSchemaVersion() );

		// Should have no impact, since the HTML has a version specified inline
		$transform->setOriginalSchemaVersion( '2.3.0' );
		$this->assertSame( '2.4.0', $transform->getSchemaVersion() );
	}

	public function testHasOriginalDataParsoid() {
		$transform = $this->createHTMLTransform( self::MODIFIED_HTML );
		$this->assertFalse( $transform->hasOriginalDataParsoid() );

		$transform->setOriginalDataParsoid( self::ORIG_DATA_PARSOID );
		$this->assertTrue( $transform->hasOriginalDataParsoid() );
	}

	public function testGetOriginalHtml() {
		$transform = $this->createHTMLTransform( self::MODIFIED_HTML );

		$this->assertFalse( $transform->hasOriginalHtml() );

		$transform->setOriginalSchemaVersion( '2.4.0' );
		$transform->setOriginalHtml( self::ORIG_HTML );

		$this->assertTrue( $transform->hasOriginalHtml() );
		$this->assertSame( self::ORIG_HTML, $transform->getOriginalHtml() );
	}

	public function testGetOriginalBody() {
		$transform = $this->createHTMLTransform( self::MODIFIED_HTML );
		$transform->setOriginalSchemaVersion( '2.4.0' );
		$transform->setOriginalHtml( self::ORIG_HTML );

		$this->assertSame(
			self::ORIG_BODY,
			ContentUtils::toXML( $transform->getOriginalBody() )
		);
	}

	public function testOldId() {
		$transform = $this->createHTMLTransformWithOriginalData();
		$this->assertSame( 1, $transform->getOriginalRevisionId() );
	}

	public function testGetContentModel() {
		$transform = $this->createHTMLTransformWithOriginalData();
		$this->assertSame( 'wikitext', $transform->getContentModel() );
	}

	public function testGetEnvOpts() {
		$transform = $this->createHTMLTransformWithOriginalData();
		$this->assertSame( 'byte', $transform->getOffsetType() );
	}

	private function getTextFromFile( string $name ): string {
		return trim( file_get_contents( __DIR__ . "/data/Transform/$name" ) );
	}

	public function testDowngrade() {
		$html = $this->getTextFromFile( 'Minimal.html' ); // Uses profile version 2.4.0
		$transform = $this->createHTMLTransform( $html );

		$transform->setOriginalSchemaVersion( '999.0.0' );
		$transform->setOriginalHtml( $html );
		$transform->setOriginalDataParsoid( self::ORIG_DATA_PARSOID );

		// should automatically apply downgrade
		$oldBody = $transform->getOriginalBody();

		// all getters should now reflect the state after the downgrade.
		// we expect a version >= 2.4.0 and < 3.0.0. So use ^2.4.0
		$this->assertTrue( Semver::satisfies( $transform->getOriginalSchemaVersion(), '^2.4.0' ) );
		$this->assertNotSame( $html, $transform->getOriginalHtml() );
		$this->assertNotSame( $oldBody, ContentUtils::toXML( $transform->getOriginalBody() ) );
	}

	public function testModifiedDataMW() {
		$html = $this->getTextFromFile( 'Minimal-999.html' ); // Uses profile version 2.4.0
		$transform = $this->createHTMLTransform( $html );

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
		$transform = $this->createHTMLTransform( $html );

		$metrics = $this->createNoOpMock( StatsdDataFactoryInterface::class, [ 'increment' ] );
		$metrics->expects( $this->atLeastOnce() )->method( 'increment' );
		$transform->setMetrics( $metrics );

		// getSchemaVersion should ioncrement the html2wt.original.version.notinline counter
		// because the input HTML doesn't contain a schema version.
		$transform->getSchemaVersion();
	}

	public function testHtmlSize() {
		$html = '<html><body>hällö</body></html>'; // use some multi-byte characters
		$transform = $this->createHTMLTransform( $html );

		// make sure it counts characters, not bytes
		$this->assertSame( 31, $transform->getModifiedHtmlSize() );
	}

	public function testSetOriginalHTML() {
		$html = '<html><body>xyz</body></html>'; // no schema version!
		$transform = $this->createHTMLTransform( $html );

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
		$transform = $this->createHTMLTransform( $html );

		$transform->getModifiedDocument();

		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( 'getModifiedDocument()' );

		$transform->setOriginalDataParsoid( [] );
	}

	public function testOffsetTypeMismatch() {
		$transform = $this->createHTMLTransform( self::ORIG_HTML );
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
