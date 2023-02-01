<?php

use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;

/**
 * Test class for Export methods.
 *
 * @group Database
 *
 * @author Isaac Hutt <mhutti1@gmail.com>
 */
class ExportTest extends MediaWikiLangTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, true );
	}

	/**
	 * @covers WikiExporter::pageByTitle
	 */
	public function testPageByTitle() {
		$pageTitle = 'UTPage';

		$services = $this->getServiceContainer();

		$title = Title::newFromText( $pageTitle );

		$xmlObject = $this->getXmlDumpForPage( $title );

		/**
		 * Check namespaces match xml
		 */
		foreach ( $xmlObject->siteinfo->namespaces->children() as $namespace ) {
			// Get the text content of the SimpleXMLElement
			$xmlNamespaces[] = (string)$namespace;
		}
		$xmlNamespaces = str_replace( ' ', '_', $xmlNamespaces );

		$actualNamespaces = (array)$services->getContentLanguage()->getNamespaces();
		$actualNamespaces = array_values( $actualNamespaces );
		$this->assertEquals( $actualNamespaces, $xmlNamespaces );

		// Check xml page title correct
		$xmlTitle = (array)$xmlObject->page->title;
		$this->assertEquals( $pageTitle, $xmlTitle[0] );

		// Check xml page text is not empty
		$text = (array)$xmlObject->page->revision->text;
		$this->assertNotEquals( '', $text[0] );
	}

	/**
	 * Regression test for T328503 to verify that custom content types
	 * with a getNativeData() override that returns a non-string value export correctly.
	 *
	 * @covers XmlDumpWriter::writeText
	 */
	public function testShouldExportContentWithNonStringNativeData(): void {
		// Make a mock ContentHandler for a Content that has a getNativeData() method
		// with a non-string return value.
		$contentModelId = 'non-string-test-content-model';
		$contentHandler = new class( $contentModelId ) extends ContentHandler {

			public function __construct( $contentModelId ) {
				parent::__construct(
					$contentModelId,
					[ CONTENT_FORMAT_TEXT ]
				);
			}

			public function serializeContent( Content $content, $format = null ) {
				return json_encode( $content->getNativeData() );
			}

			public function unserializeContent( $blob, $format = null ) {
				return $this->getTestContent( $blob );
			}

			public function makeEmptyContent() {
				return $this->getTestContent( '{}' );
			}

			protected function fillParserOutput(
				Content $content,
				ContentParseParams $cpoParams,
				ParserOutput &$output
			) {
				$output->setText( json_encode( $content->getNativeData() ) );
			}

			private function getTestContent( string $blob ): Content {
				return new class( $blob, $this->getModelID() ) extends TextContent {
					/** @var array */
					private $data;

					public function __construct( $text, $contentModelId ) {
						parent::__construct(
							$text,
							$contentModelId
						);

						$this->data = json_decode( $text, true );
					}

					public function getNativeData() {
						return $this->data;
					}
				};
			}
		};

		$this->setTemporaryHook(
			'ContentHandlerForModelID',
			static function (
				string $modelId,
				?ContentHandler &$handlerRef
			) use ( $contentModelId, $contentHandler ): void {
				if ( $modelId === $contentModelId ) {
					$handlerRef = $contentHandler;
				}
			}
		);

		$wikiPage = $this->getNonexistingTestPage( 'NonStringNativeDataExportTest' );

		$testText = json_encode( [ 'test' => 'data' ] );
		$content = $contentHandler->unserializeContent( $testText );

		$this->editPage( $wikiPage, $content );

		$xmlObject = $this->getXmlDumpForPage( $wikiPage );

		$this->assertSame( $contentModelId, (string)$xmlObject->page->revision->model );
		$this->assertSame( $testText, (string)$xmlObject->page->revision->text );
	}

	/**
	 * Convenience function to export the content of the given page in MediaWiki's XML dump format.
	 * @param PageIdentity $page page to export
	 * @return SimpleXMLElement root element of the generated XML
	 */
	private function getXmlDumpForPage( PageIdentity $page ): SimpleXMLElement {
		$exporter = $this->getServiceContainer()
			->getWikiExporterFactory()
			->getWikiExporter( $this->db, WikiExporter::FULL );

		$sink = new DumpStringOutput();
		$exporter->setOutputSink( $sink );
		$exporter->openStream();
		$exporter->pageByTitle( $page );
		$exporter->closeStream();

		// phpcs:ignore Generic.PHP.NoSilencedErrors -- suppress deprecation per T268847
		$oldDisable = @libxml_disable_entity_loader( true );

		// This throws error if invalid xml output
		$xmlObject = simplexml_load_string( $sink );

		// phpcs:ignore Generic.PHP.NoSilencedErrors
		@libxml_disable_entity_loader( $oldDisable );

		return $xmlObject;
	}

}
