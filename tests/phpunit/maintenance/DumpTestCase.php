<?php

namespace MediaWiki\Tests\Maintenance;

use DOMDocument;
use ExecutableFinder;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\WikiPage;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWikiLangTestCase;
use RuntimeException;
use WikiExporter;

/**
 * Base TestCase for dumps
 */
abstract class DumpTestCase extends MediaWikiLangTestCase {

	/**
	 * exception to be rethrown once in sound PHPUnit surrounding
	 *
	 * As the current MediaWikiIntegrationTestCase::run is not robust enough to recover
	 * from thrown exceptions directly, we cannot throw frow within
	 * self::addDBData, although it would be appropriate. Hence, we catch the
	 * exception and store it until we are in setUp and may finally rethrow
	 * the exception without crashing the test suite.
	 *
	 * @var \Exception|null
	 */
	protected $exceptionFromAddDBData = null;

	/** @var bool|null Whether the 'gzip' utility is available */
	protected static $hasGzip = null;

	/**
	 * Skip the test if 'gzip' is not in $PATH.
	 *
	 * @return bool
	 */
	protected function checkHasGzip() {
		if ( self::$hasGzip === null ) {
			self::$hasGzip = ( ExecutableFinder::findInDefaultPaths( 'gzip' ) !== false );
		}

		if ( !self::$hasGzip ) {
			$this->markTestSkipped( "Skip test, requires the gzip utility in PATH" );
		}

		return self::$hasGzip;
	}

	/**
	 * Adds a revision to a page, while returning the resuting revision's id text id.
	 *
	 * @param WikiPage $page Page to add the revision to
	 * @param string $text Revisions text
	 * @param string $summary Revisions summary
	 * @param string $model The model ID (defaults to wikitext)
	 * @return array
	 */
	protected function addRevision(
		WikiPage $page,
		$text,
		$summary,
		$model = CONTENT_MODEL_WIKITEXT
	) {
		$contentHandler = $this->getServiceContainer()
			->getContentHandlerFactory()->getContentHandler( $model );

		$content = $contentHandler->unserializeContent( $text );

		$rev = $this->addMultiSlotRevision( $page, [ SlotRecord::MAIN => $content ], $summary );

		if ( !$rev ) {
			throw new RuntimeException( "Could not create revision" );
		}

		$text_id = $this->getSlotTextId( $rev->getSlot( SlotRecord::MAIN ) );
		return [ $rev->getId(), $text_id, $rev ];
	}

	/**
	 * @param SlotRecord $slot
	 *
	 * @return string|null
	 */
	protected function getSlotText( SlotRecord $slot ) {
		try {
			return $slot->getContent()->serialize();
		} catch ( RevisionAccessException $ex ) {
			return null;
		}
	}

	/**
	 * @param SlotRecord $slot
	 *
	 * @return int
	 */
	protected function getSlotTextId( SlotRecord $slot ) {
		return (int)preg_replace( '/^tt:/', '', $slot->getAddress() );
	}

	/**
	 * @param SlotRecord $slot
	 *
	 * @return string
	 */
	protected function getSlotFormat( SlotRecord $slot ) {
		$contentHandler = $this->getServiceContainer()
			->getContentHandlerFactory()->getContentHandler( $slot->getModel() );

		return $contentHandler->getDefaultFormat();
	}

	/**
	 * Adds a revision to a page, while returning the resulting revision's id and text id.
	 *
	 * @param WikiPage $page Page to add the revision to
	 * @param Content[] $slots A mapping of slot names to Content objects
	 * @param string $summary Revisions summary
	 * @return RevisionRecord
	 */
	protected function addMultiSlotRevision(
		WikiPage $page,
		array $slots,
		$summary
	) {
		$slotRoleRegistry = MediaWikiServices::getInstance()->getSlotRoleRegistry();

		$updater = $page->newPageUpdater( $this->getTestUser()->getUser() );

		foreach ( $slots as $role => $content ) {
			if ( !$slotRoleRegistry->isDefinedRole( $role ) ) {
				$slotRoleRegistry->defineRoleWithModel( $role, $content->getModel() );
			}

			$updater->setContent( $role, $content );
		}

		$updater->saveRevision( CommentStoreComment::newUnsavedComment( trim( $summary ) ) );
		return $updater->getNewRevision();
	}

	/**
	 * gunzips the given file and stores the result in the original file name
	 *
	 * @param string $fname Filename to read the gzipped data from and stored
	 *   the gunzipped data into
	 */
	protected function gunzip( $fname ) {
		$gzipped_contents = file_get_contents( $fname );
		if ( $gzipped_contents === false ) {
			$this->fail( "Could not get contents of $fname" );
		}

		$contents = gzdecode( $gzipped_contents );

		$this->assertEquals(
			strlen( $contents ),
			file_put_contents( $fname, $contents ),
			'# bytes written'
		);
	}

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();

		if ( !function_exists( 'libxml_set_external_entity_loader' ) ) {
			return;
		}

		// The W3C is intentionally slow about returning schema files,
		// see <https://www.w3.org/Help/Webmaster#slowdtd>.
		// To work around that, we keep our own copies of the relevant schema files.
		libxml_set_external_entity_loader(
			static function ( $public, $system, $context ) {
				switch ( $system ) {
					// if more schema files are needed, add them here.
					case 'http://www.w3.org/2001/xml.xsd':
						$file = __DIR__ . '/xml.xsd';
						break;
					default:
						if ( is_file( $system ) ) {
							$file = $system;
						} else {
							return null;
						}
				}

				return $file;
			}
		);
	}

	/**
	 * Default set up function.
	 *
	 * Reports errors from addDBData to PHPUnit
	 */
	protected function setUp(): void {
		parent::setUp();

		// Check if any Exception is stored for rethrowing from addDBData
		// @see self::exceptionFromAddDBData
		if ( $this->exceptionFromAddDBData !== null ) {
			throw $this->exceptionFromAddDBData;
		}
	}

	/**
	 * Returns the path to the XML schema file for the given schema version.
	 *
	 * @param string|null $schemaVersion
	 *
	 * @return string
	 */
	protected function getXmlSchemaPath( $schemaVersion = null ) {
		global $IP, $wgXmlDumpSchemaVersion;

		$schemaVersion = $schemaVersion ?: $wgXmlDumpSchemaVersion;

		return "$IP/docs/export-$schemaVersion.xsd";
	}

	/**
	 * Checks for test output consisting only of lines containing ETA announcements
	 */
	protected function expectETAOutput() {
		// Newer PHPUnits require assertion about the output using PHPUnit's own
		// expectOutput[...] functions. However, the PHPUnit shipped prediactes
		// do not allow to check /each/ line of the output using /readable/ REs.
		// So we ...

		// 1. ... add a dummy output checking to make PHPUnit not complain
		//    about unchecked test output
		$this->expectOutputRegex( '//' );

		// 2. Do the real output checking on our own.
		$lines = explode( "\n", $this->getActualOutputForAssertion() );
		$this->assertGreaterThan( 1, count( $lines ), "Minimal lines of produced output" );
		$this->assertSame( '', array_pop( $lines ), "Output ends in LF" );
		$timestamp_re = "[0-9]{4}-[01][0-9]-[0-3][0-9] [0-2][0-9]:[0-5][0-9]:[0-6][0-9]";
		foreach ( $lines as $line ) {
			$this->assertMatchesRegularExpression(
				"/$timestamp_re: .* \(ID [0-9]+\) [0-9]* pages .*, [0-9]* revs .*, ETA/",
				$line
			);
		}
	}

	/**
	 * @param null|string $schemaVersion
	 *
	 * @return DumpAsserter
	 */
	protected function getDumpAsserter( $schemaVersion = null ) {
		$schemaVersion = $schemaVersion ?: WikiExporter::schemaVersion();
		return new DumpAsserter( $schemaVersion );
	}

	/**
	 * Checks an XML file against an XSD schema.
	 * @param string $fname
	 * @param string $schemaFile
	 */
	protected function assertDumpSchema( $fname, $schemaFile ) {
		if ( !function_exists( 'libxml_use_internal_errors' ) ) {
			// Would be nice to leave a warning somehow.
			// We don't want to skip all of the test case that calls this, though.
			$this->markAsRisky();
			return;
		}
		$xml = new DOMDocument();
		$this->assertTrue( $xml->load( $fname ),
			"Opening temporary file $fname via DOMDocument failed" );

		// Don't throw
		$oldLibXmlInternalErrors = libxml_use_internal_errors( true );

		// NOTE: if this reports "Invalid Schema", the schema may be referencing an external
		// entity (typically, another schema) that needs to be mapped in the
		// libxml_set_external_entity_loader callback defined in setUpBeforeClass() above!
		// Or $schemaFile doesn't point to a schema file, or the schema is indeed just broken.
		if ( !$xml->schemaValidate( $schemaFile ) ) {
			$errorText = '';

			foreach ( libxml_get_errors() as $error ) {
				$errorText .= "\nline {$error->line}: {$error->message}";
			}

			libxml_clear_errors();

			$this->fail(
				"Failed asserting that $fname conforms to the schema in $schemaFile:\n$errorText"
			);
		}

		libxml_use_internal_errors( $oldLibXmlInternalErrors );
	}

}
