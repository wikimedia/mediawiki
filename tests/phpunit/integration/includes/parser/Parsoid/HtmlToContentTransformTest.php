<?php

namespace MediaWiki\Tests\Parser\Parsoid;

use Composer\Semver\Semver;
use LogicException;
use MediaWiki\Content\JsonContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\Parsoid\Config\PageConfig;
use MediaWiki\Parser\Parsoid\HtmlToContentTransform;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\SelserData;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Parser\Parsoid\HtmlToContentTransform
 * @group Database
 */
class HtmlToContentTransformTest extends MediaWikiIntegrationTestCase {
	private const MODIFIED_HTML = '<html><head>' .
	'<meta charset="utf-8"/><meta property="mw:htmlVersion" content="2.4.0"/></head>' .
	'<body>Modified HTML</body></html>';

	private const ORIG_BODY = '<body>Original Content</body>';
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

	private function setOriginalData( HtmlToContentTransform $transform ) {
		$transform->setOriginalRevisionId( 1 );
		$transform->setOriginalSchemaVersion( '2.4.0' );
		$transform->setOriginalHtml( self::ORIG_HTML );
		$transform->setOriginalDataMW( self::ORIG_DATA_MW );
		$transform->setOriginalDataParsoid( self::ORIG_DATA_PARSOID );
	}

	private function createHtmlToContentTransform( $html = '' ) {
		return new HtmlToContentTransform(
			$html ?? self::ORIG_HTML,
			PageIdentityValue::localIdentity( 7, NS_MAIN, 'Test' ),
			new Parsoid(
				$this->getServiceContainer()->getParsoidSiteConfig(),
				$this->getServiceContainer()->getParsoidDataAccess()
			),
			MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidSettings ),
			$this->getServiceContainer()->getParsoidPageConfigFactory(),
			$this->getServiceContainer()->getContentHandlerFactory()
		);
	}

	private function createHtmlToContentTransformWithOriginalData( $html = '', ?array $options = null ) {
		$transform = $this->createHtmlToContentTransform( $html );

		$options ??= [
			'contentmodel' => 'wikitext',
			'offsetType' => 'byte',
		];

		// Set some options to assert on $transform object.
		$transform->setOptions( $options );

		$this->setOriginalData( $transform );

		return $transform;
	}

	public function testGetOriginalBodyRequiresValidDataParsoid() {
		$transform = $this->createHtmlToContentTransform( self::ORIG_HTML );
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
		$transform = $this->createHtmlToContentTransform( self::MODIFIED_HTML );
		$this->assertFalse( $transform->hasOriginalHtml() );

		$transform->setOriginalDataParsoid( self::ORIG_DATA_PARSOID );
		$this->assertFalse( $transform->hasOriginalHtml() );

		$transform->setOriginalHtml( self::ORIG_HTML );
		$this->assertTrue( $transform->hasOriginalHtml() );
	}

	public function testGetOriginalSchemaVersion() {
		$transform = $this->createHtmlToContentTransform( self::ORIG_HTML ); // no version inline
		$this->assertSame( Parsoid::defaultHTMLVersion(), $transform->getOriginalSchemaVersion() );

		$transform = $this->createHtmlToContentTransform( self::MODIFIED_HTML ); // has version inline
		$this->assertSame( '2.4.0', $transform->getOriginalSchemaVersion() );

		$transform->setOriginalSchemaVersion( '2.3.0' );
		$this->assertSame( '2.3.0', $transform->getOriginalSchemaVersion() );
	}

	public function testGetSchemaVersion() {
		$transform = $this->createHtmlToContentTransform( self::ORIG_HTML ); // no version inline
		$this->assertSame( Parsoid::defaultHTMLVersion(), $transform->getSchemaVersion() );

		// Should have an effect, since the HTML has no version specified inline
		$transform->setOriginalSchemaVersion( '2.3.0' );
		$this->assertSame( '2.3.0', $transform->getSchemaVersion() );

		$transform = $this->createHtmlToContentTransform( self::MODIFIED_HTML ); // has version inline
		$this->assertSame( '2.4.0', $transform->getSchemaVersion() );

		// Should have no impact, since the HTML has a version specified inline
		$transform->setOriginalSchemaVersion( '2.3.0' );
		$this->assertSame( '2.4.0', $transform->getSchemaVersion() );
	}

	public function testHasOriginalDataParsoid() {
		$transform = $this->createHtmlToContentTransform( self::MODIFIED_HTML );
		$this->assertFalse( $transform->hasOriginalDataParsoid() );

		$transform->setOriginalDataParsoid( self::ORIG_DATA_PARSOID );
		$this->assertTrue( $transform->hasOriginalDataParsoid() );
	}

	public function testGetOriginalHtml() {
		$transform = $this->createHtmlToContentTransform( self::MODIFIED_HTML );

		$this->assertFalse( $transform->hasOriginalHtml() );

		$transform->setOriginalSchemaVersion( '2.4.0' );
		$transform->setOriginalHtml( self::ORIG_HTML );

		$this->assertTrue( $transform->hasOriginalHtml() );
		$this->assertSame( self::ORIG_HTML, $transform->getOriginalHtml() );
	}

	public function testGetOriginalBody() {
		$transform = $this->createHtmlToContentTransform( self::MODIFIED_HTML );
		$transform->setOriginalSchemaVersion( '2.4.0' );
		$transform->setOriginalHtml( self::ORIG_HTML );

		$this->assertSame(
			self::ORIG_BODY,
			ContentUtils::toXML( $transform->getOriginalBody() )
		);
	}

	private function assertTransformHasOriginalContent( HtmlToContentTransform $transform, $text ) {
		$this->assertTrue( $transform->knowsOriginalContent() );

		$access = TestingAccessWrapper::newFromObject( $transform );

		/** @var PageConfig $pageConfig */
		$pageConfig = $access->getPageConfig();

		$this->assertSame( $text, $pageConfig->getPageMainContent() );

		/** @var ?SelserData $selserData */
		$selserData = $access->getSelserData();

		$this->assertNotNull( $selserData );
	}

	public function testOldId() {
		$text = 'Lorem Ipsum';
		$rev = $this->editPage( __METHOD__, $text )->getValue()['revision-record'];

		$transform = $this->createHtmlToContentTransformWithOriginalData();
		$transform->setOriginalRevisionId( $rev->getId() );

		$this->assertSame( $rev->getId(), $transform->getOriginalRevisionId() );

		$this->assertTransformHasOriginalContent( $transform, $text );
	}

	public function testSetOriginalRevision() {
		$text = 'Lorem Ipsum';

		$page = PageIdentityValue::localIdentity( 17, NS_MAIN, 'Test' );
		$rev = new MutableRevisionRecord( $page );
		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );

		$transform = $this->createHtmlToContentTransformWithOriginalData();
		$transform->setOriginalRevision( $rev );
		$this->assertSame( $rev->getId(), $transform->getOriginalRevisionId() );

		$this->assertTransformHasOriginalContent( $transform, $text );
	}

	public function testSetOriginalText() {
		$text = 'Lorem Ipsum';

		$transform = $this->createHtmlToContentTransformWithOriginalData();
		$transform->setOriginalText( $text );

		$this->assertTransformHasOriginalContent( $transform, $text );
	}

	public function testSetOriginalContent() {
		$text = 'Lorem Ipsum';

		$transform = $this->createHtmlToContentTransformWithOriginalData();
		$transform->setOriginalContent( new JsonContent( $text ) );

		$this->assertTransformHasOriginalContent( $transform, $text );
		$this->assertSame( CONTENT_MODEL_JSON, $transform->getContentModel() );
	}

	public function testOptions() {
		$transform = $this->createHtmlToContentTransformWithOriginalData( '', [] );

		$this->assertSame( 'wikitext', $transform->getContentModel() );
		$this->assertSame( 'byte', $transform->getOffsetType() );

		$transform->setOptions( [
			'contentmodel' => 'text',
			'offsetType' => 'ucs2',
		] );

		$this->assertSame( 'text', $transform->getContentModel() );
		$this->assertSame( 'ucs2', $transform->getOffsetType() );
	}

	/**
	 * Assert that in case we set only one of the options, the other(s)
	 * should fall back to their correct defaults.
	 */
	public function testOptionsForIndividualDefaults() {
		$transform = $this->createHtmlToContentTransformWithOriginalData( '', [] );

		$this->assertSame( 'wikitext', $transform->getContentModel() );
		$this->assertSame( 'byte', $transform->getOffsetType() );

		$transform = $this->createHtmlToContentTransformWithOriginalData( '', [] );
		// Set only content model
		$transform->setOptions( [ 'contentmodel' => 'text' ] );

		$this->assertSame( 'text', $transform->getContentModel() );
		$this->assertSame( 'byte', $transform->getOffsetType() );

		$transform = $this->createHtmlToContentTransformWithOriginalData( '', [] );
		// Set only offset type
		$transform->setOptions( [ 'offsetType' => 'ucs2' ] );

		$this->assertSame( 'wikitext', $transform->getContentModel() );
		$this->assertSame( 'ucs2', $transform->getOffsetType() );
	}

	private function getTextFromFile( string $name ): string {
		return trim( file_get_contents( __DIR__ . "/data/Transform/$name" ) );
	}

	public function testDowngrade() {
		$html = $this->getTextFromFile( 'Minimal.html' ); // Uses profile version 2.4.0
		$transform = $this->createHtmlToContentTransform( $html );
		$transform->setMetrics( StatsFactory::newNull() );

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
		$transform = $this->createHtmlToContentTransform( $html );

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
		$transform = $this->createHtmlToContentTransform( $html );

		$statsHelper = StatsFactory::newUnitTestingHelper();
		$transform->setMetrics( $statsHelper->getStatsFactory() );

		// getSchemaVersion should report that the input HTML lacks a schema version.
		$transform->getSchemaVersion();
		$this->assertSame( [
			'mediawiki.html2wt_original_version_total:1|c|#input_content_version:none',
		], $statsHelper->consumeAllFormatted() );
	}

	public function testHtmlSize() {
		$html = '<html><body>hällö</body></html>'; // use some multi-byte characters
		$transform = $this->createHtmlToContentTransform( $html );

		// make sure it counts characters, not bytes
		$this->assertSame( 31, $transform->getModifiedHtmlSize() );
	}

	public function testSetOriginalHTML() {
		$html = '<html><body>xyz</body></html>'; // no schema version!
		$transform = $this->createHtmlToContentTransform( $html );

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
		$transform = $this->createHtmlToContentTransform( $html );

		$transform->getModifiedDocument();

		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( 'getModifiedDocument()' );

		$transform->setOriginalDataParsoid( [] );
	}

	public function testOffsetTypeMismatch() {
		$transform = $this->createHtmlToContentTransform( self::ORIG_HTML );
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

	public function testHtmlToWikitextContent() {
		$transform = $this->createHtmlToContentTransform( self::ORIG_HTML );

		// Set some options to assert on $transform.
		$transform->setOptions( [
			'contentmodel' => null,
			'offsetType' => 'byte',
		] );

		$content = $transform->htmlToContent();
		$this->assertInstanceOf( WikitextContent::class, $content );
		$this->assertStringContainsString( 'Original Content', $content->getText() );
	}

	public function testHtmlToJsonContent() {
		$jsonConfigHtml = $this->getTextFromFile( 'JsonConfig.html' );
		$transform = $this->createHtmlToContentTransform( $jsonConfigHtml );

		// Set some options to assert on $transform.
		$transform->setOptions( [
			'contentmodel' => CONTENT_MODEL_JSON,
			'offsetType' => 'byte',
		] );

		$content = $transform->htmlToContent();
		$this->assertInstanceOf( JsonContent::class, $content );
	}
}
