<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Parser\Parsoid;

use Composer\Semver\Semver;
use LogicException;
use MediaWiki\Content\JsonContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\Parsoid\Config\PageConfig;
use MediaWiki\Parser\Parsoid\HtmlToContentTransform;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\DOMCompat;
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

	private function setOriginalData(
		HtmlToContentTransform $transform,
		?string $version = '2.4.0',
		?string $html = self::ORIG_HTML,
		?array $dataMw = self::ORIG_DATA_MW,
		?array $dataParsoid = self::ORIG_DATA_PARSOID,
	) {
		$transform->setOriginalRevisionId( 1 );
		$transform->setOriginalData( [
			'schemaVersion' => $version,
			'html' => $html,
			'data-mw' => $dataMw,
			'data-parsoid' => $dataParsoid,
		] );
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
			$this->getServiceContainer()->getParsoidSiteConfig(),
			$this->getServiceContainer()->getParsoidPageConfigFactory(),
			$this->getServiceContainer()->getContentHandlerFactory(),
			$this->getServiceContainer()->getLanguageFactory()
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

		// Invalid data-parsoid structure!
		$this->setOriginalData( $transform, dataParsoid: [ 'foo' => 'bar' ] );

		$exception = new ClientError( 'Invalid data-parsoid was provided.' );
		$this->expectException( get_class( $exception ) );
		$this->expectExceptionMessage( $exception->getMessage() );

		// Should throw because data-parsoid is invalid.
		// All checks happen in getters, so this call is necessary.
		$transform->processOriginalHtml();
	}

	public function testHasOriginalHtml() {
		$transform = $this->createHtmlToContentTransform( self::MODIFIED_HTML );
		$this->assertFalse( $transform->hasOriginalHtml() );

		$this->setOriginalData( $transform );

		$this->assertTrue( $transform->hasOriginalHtml() );
	}

	public function testGetSchema() {
		$transform1 = $this->createHtmlToContentTransform( self::ORIG_HTML ); // no version inline
		$this->assertSame( Parsoid::defaultHTMLVersion(), $transform1->getSchemaVersion() );
		$this->assertSame( Parsoid::defaultHTMLVersion(), $transform1->getOriginalSchemaVersion() );

		// Should have an effect, since the HTML has no version specified inline
		$transform1->setOriginalData( [ 'schemaVersion' => '2.3.0' ] );
		$this->assertSame( '2.3.0', $transform1->getSchemaVersion() );
		$this->assertSame( '2.3.0', $transform1->getOriginalSchemaVersion() );

		$transform2 = $this->createHtmlToContentTransform( self::MODIFIED_HTML ); // has version inline
		$this->assertSame( '2.4.0', $transform2->getSchemaVersion() );
		$this->assertSame( '2.4.0', $transform2->getOriginalSchemaVersion() );

		// Should have no impact on modified HTML, since the HTML has a version specified inline
		$transform2->setOriginalData( [ 'schemaVersion' => '2.3.0' ] );
		$this->assertSame( '2.4.0', $transform2->getSchemaVersion() );
		$this->assertSame( '2.3.0', $transform1->getOriginalSchemaVersion() );
	}

	public function testHasOriginalDataParsoid() {
		$transform = $this->createHtmlToContentTransform( self::MODIFIED_HTML );
		$this->assertFalse( $transform->hasOriginalDataParsoid() );

		$transform->setOriginalData( [ 'data-parsoid' => [] ] );
		$this->assertTrue( $transform->hasOriginalDataParsoid() );
	}

	public function testProcessOriginalHtml() {
		$transform = $this->createHtmlToContentTransform( self::MODIFIED_HTML );
		$transform->setOriginalData( [
			'schemaVersion' => '2.4.0',
			'html' => self::ORIG_HTML
		] );
		$this->assertSame(
			self::ORIG_BODY,
			ContentUtils::toXML( DOMCompat::getBody( $transform->getOriginalDocument() ) )
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
		$rev = MutableRevisionRecord::newFromContent( $page, new WikitextContent( $text ) );

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

		$transform->setOriginalData( [
			'schemaVersion' => '999.0.0',
			'html' => $html,
			'data-parsoid' => self::ORIG_DATA_PARSOID
		] );

		// should automatically apply downgrade
		$transform->processOriginalHtml();

		// all getters should now reflect the state after the downgrade.
		// we expect a version >= 2.4.0 and < 3.0.0. So use ^2.4.0
		$this->assertTrue( Semver::satisfies( $transform->getOriginalSchemaVersion(), '^2.4.0' ) );
		$this->assertNotSame(
			$html,
			ContentUtils::toXML( $newOrigBody = DOMCompat::getBody( $transform->getOriginalDocument() ) )
		);
	}

	public function testModifiedDataMW() {
		$html = $this->getTextFromFile( 'Minimal-999.html' ); // sets profile version 999.0.0
		$transform = $this->createHtmlToContentTransform( $html );

		$this->setOriginalData( $transform, version: null, dataMw: null );
		$transform->setModifiedDataMW( self::MODIFIED_DATA_MW );

		// should automatically apply downgrade from v999.0.0
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
		$transform->setOriginalData( [
			'schemaVersion' => '999.0.0',
			'html' => 'hi'
		] );

		$this->assertTrue( $transform->hasOriginalHtml() );
		$this->assertFalse( $transform->hasOriginalDataParsoid() );
	}

	public function testSetOriginalDataAfterUse() {
		// Use HTML that contains a schema version!
		// Otherwise, we won't trigger the right error.
		$html = $this->getTextFromFile( 'Minimal.html' );
		$transform = $this->createHtmlToContentTransform( $html );

		$transform->getModifiedDocument();

		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( 'getModifiedDocument()' );

		$transform->setOriginalData( [] );
	}

	public function testOffsetTypeMismatch() {
		$transform = $this->createHtmlToContentTransform( self::ORIG_HTML );
		// Set some options to assert on $transform.
		$transform->setOptions( [
			'contentmodel' => 'wikitext',
			'offsetType' => 'byte',
		] );

		$dataParsoid = self::ORIG_DATA_PARSOID;
		$dataParsoid['offsetType'] = 'UCS2';
		$this->setOriginalData( $transform, dataParsoid: $dataParsoid );

		$this->expectException( ClientError::class );
		$transform->processOriginalHtml();
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

	/**
	 * This is a regression test for T439453.
	 * FIXME: This should probably be part of HtmlInputransformHelper
	 */
	public function testSelser() {
		$wikitext = "{{Foo|\n|a = x\n|b = y|250px]]\n|}}\n\n'''bold'''   \n* item";
		$rev = $this->editPage( __METHOD__, $wikitext )->getValue()['revision-record'];

		$parsoid = new Parsoid(
			$this->getServiceContainer()->getParsoidSiteConfig(),
			$this->getServiceContainer()->getParsoidDataAccess()
		);
		$parserOptions = ParserOptions::newFromAnon();
		$parserOptions->setUseParsoid();
		$pageConfig = $this->getServiceContainer()->getParsoidPageConfigFactory()
			->createFromParserOptions( $parserOptions, $rev->getPage(), $rev );
		$pb = $parsoid->wikitext2html( $pageConfig, [ 'pageBundle' => true ] );

		$transform = $this->createHtmlToContentTransform( $pb->html );
		$transform->setOptions( [ 'contentmodel' => 'wikitext', 'offsetType' => 'byte' ] );
		$transform->setOriginalRevisionId( $rev->getId() );
		$transform->setOriginalData( [
			'schemaVersion' => $pb->getContentVersion(),
			'html' => $pb->html,
			'data-parsoid' => $pb->parsoid,
			'data-mw' => $pb->mw,
		] );

		$this->assertSame( $wikitext, $transform->htmlToContent()->getText() );

		// Test with a trivial edit
		$newHtml = preg_replace( "#</body>#", "<!--boo--></body>", $pb->html );
		$transform = $this->createHtmlToContentTransform( $newHtml );
		$transform->setOptions( [ 'contentmodel' => 'wikitext', 'offsetType' => 'byte' ] );
		$transform->setOriginalRevisionId( $rev->getId() );
		$transform->setOriginalData( [
			'schemaVersion' => $pb->getContentVersion(),
			'html' => $pb->html,
			'data-parsoid' => $pb->parsoid,
		] );

		$this->assertSame( $wikitext . "<!--boo-->", $transform->htmlToContent()->getText() );
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
