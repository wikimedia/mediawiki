<?php

namespace MediaWiki\Tests\Maintenance;

use Exception;
use FetchText;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Page\WikiPage;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\ExpectationFailedException;
use RuntimeException;

/**
 * Mock for the input/output of FetchText
 *
 * FetchText internally tries to access stdin and stdout. We mock those aspects
 * for testing.
 */
class SemiMockedFetchText extends FetchText {

	/**
	 * @var string|null Text to pass as stdin
	 */
	private $mockStdinText = null;

	/**
	 * @var bool Whether or not a text for stdin has been provided
	 */
	private $mockSetUp = false;

	/**
	 * @var array Invocation counters for the mocked aspects
	 */
	private $mockInvocations = [ 'getStdin' => 0 ];

	/**
	 * Data for the fake stdin
	 *
	 * @param string $stdin The string to be used instead of stdin
	 */
	public function mockStdin( $stdin ) {
		$this->mockStdinText = $stdin;
		$this->mockSetUp = true;
	}

	/**
	 * Gets invocation counters for mocked methods.
	 *
	 * @return array An array, whose keys are function names. The corresponding values
	 * denote the number of times the function has been invoked.
	 */
	public function mockGetInvocations() {
		return $this->mockInvocations;
	}

	// -----------------------------------------------------------------
	// Mocked functions from FetchText follow.

	public function getStdin( $len = null ) {
		$this->mockInvocations['getStdin']++;
		if ( $len !== null ) {
			throw new ExpectationFailedException(
				"Tried to get stdin with non null parameter" );
		}

		if ( !$this->mockSetUp ) {
			throw new ExpectationFailedException(
				"Tried to get stdin before setting up rerouting" );
		}

		return fopen( 'data://text/plain,' . $this->mockStdinText, 'r' );
	}
}

/**
 * TestCase for FetchText
 *
 * @group Database
 * @group Dump
 * @covers \FetchText
 */
class FetchTextTest extends MediaWikiIntegrationTestCase {

	// We add 5 Revisions for this test. Their corresponding text id's
	// are stored in the following 5 variables.
	/** @var int */
	protected static $textId1;
	/** @var int */
	protected static $textId2;
	/** @var int */
	protected static $textId3;
	/** @var int */
	protected static $textId4;
	/** @var int */
	protected static $textId5;

	/**
	 * @var Exception|null As the current MediaWikiIntegrationTestCase::run is not
	 * robust enough to recover from thrown exceptions directly, we cannot
	 * throw frow within addDBData, although it would be appropriate. Hence,
	 * we catch the exception and store it until we are in setUp and may
	 * finally rethrow the exception without crashing the test suite.
	 */
	protected static $exceptionFromAddDBDataOnce;

	/**
	 * @var FetchText The (mocked) FetchText that is to test
	 */
	private $fetchText;

	/**
	 * Adds a revision to a page and returns the main slot's blob address
	 *
	 * @param WikiPage $page The page to add the revision to
	 * @param string $text The revisions text
	 * @param string $summary The revisions summare
	 * @return string
	 */
	private function addRevision( $page, $text, $summary ) {
		$status = $page->doUserEditContent(
			ContentHandler::makeContent( $text, $page->getTitle() ),
			$this->getTestSysop()->getUser(),
			$summary
		);

		if ( $status->isGood() ) {
			$revision = $status->getNewRevision();
			$address = $revision->getSlot( SlotRecord::MAIN )->getAddress();
			return $address;
		}

		throw new RuntimeException( "Could not create revision" );
	}

	public function addDBDataOnce() {
		$wikitextNamespace = $this->getDefaultWikitextNS();
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();

		try {
			$title = Title::makeTitle( $wikitextNamespace, 'FetchTextTestPage1' );
			$page = $wikiPageFactory->newFromTitle( $title );
			self::$textId1 = $this->addRevision(
				$page,
				"FetchTextTestPage1Text1",
				"FetchTextTestPage1Summary1"
			);

			$title = Title::makeTitle( $wikitextNamespace, 'FetchTextTestPage2' );
			$page = $wikiPageFactory->newFromTitle( $title );
			self::$textId2 = $this->addRevision(
				$page,
				"FetchTextTestPage2Text1",
				"FetchTextTestPage2Summary1"
			);
			self::$textId3 = $this->addRevision(
				$page,
				"FetchTextTestPage2Text2",
				"FetchTextTestPage2Summary2"
			);
			self::$textId4 = $this->addRevision(
				$page,
				"FetchTextTestPage2Text3",
				"FetchTextTestPage2Summary3"
			);
			self::$textId5 = $this->addRevision(
				$page,
				"FetchTextTestPage2Text4 some additional Text  ",
				"FetchTextTestPage2Summary4 extra "
			);
		} catch ( Exception $e ) {
			// We'd love to pass $e directly. However, ... see
			// documentation of exceptionFromAddDBDataOnce
			self::$exceptionFromAddDBDataOnce = $e;
		}
	}

	protected function setUp(): void {
		parent::setUp();

		// Check if any Exception is stored for rethrowing from addDBData
		if ( self::$exceptionFromAddDBDataOnce !== null ) {
			throw self::$exceptionFromAddDBDataOnce;
		}

		$this->fetchText = new SemiMockedFetchText();
	}

	/**
	 * Helper to relate FetchText's input and output
	 * @param string $input
	 * @param string $expectedOutput
	 */
	private function assertFilter( $input, $expectedOutput ) {
		$this->fetchText->mockStdin( $input );
		$this->fetchText->execute();
		$invocations = $this->fetchText->mockGetInvocations();
		$this->assertSame( 1, $invocations['getStdin'],
			"getStdin invocation counter" );
		$this->expectOutputString( $expectedOutput );
	}

	// Instead of the following functions, a data provider would be great.
	// However, as data providers are evaluated /before/ addDBData, a data
	// provider would not know the required ids.

	public function testExistingSimple() {
		$this->assertFilter( self::$textId2,
			self::$textId2 . "\n23\nFetchTextTestPage2Text1" );
	}

	public function testExistingSimpleWithNewline() {
		$this->assertFilter( self::$textId2 . "\n",
			self::$textId2 . "\n23\nFetchTextTestPage2Text1" );
	}

	public function testExistingInteger() {
		$this->assertFilter( (int)preg_replace( '/^tt:/', '', self::$textId2 ),
			self::$textId2 . "\n23\nFetchTextTestPage2Text1" );
	}

	public function testExistingSeveral() {
		$this->assertFilter(
			implode( "\n", [
				self::$textId1,
				self::$textId5,
				self::$textId3,
				self::$textId3,
			] ),
			implode( '', [
				self::$textId1 . "\n23\nFetchTextTestPage1Text1",
				self::$textId5 . "\n44\nFetchTextTestPage2Text4 "
					. "some additional Text",
				self::$textId3 . "\n23\nFetchTextTestPage2Text2",
				self::$textId3 . "\n23\nFetchTextTestPage2Text2"
			] ) );
	}

	public function testEmpty() {
		$this->assertFilter( "", "" );
	}

	public function testNonExisting() {
		@$this->assertFilter( 'tt:77889911', 'tt:77889911' . "\n-1\n" );
	}

	public function testNonExistingInteger() {
		@$this->assertFilter( '77889911', 'tt:77889911' . "\n-1\n" );
	}

	public function testBadBlobAddressWithColon() {
		$this->assertFilter( 'foo:bar', 'foo:bar' . "\n-1\n" );
	}

	public function testNegativeInteger() {
		$this->assertFilter( "-42", "tt:-42\n-1\n" );
	}

	public function testFloatingPointNumberExisting() {
		// float -> int -> address -> revision
		$id = intval( preg_replace( '/^tt:/', '', self::$textId3 ) ) + 0.14159;
		$this->assertFilter( 'tt:' . intval( $id ),
			self::$textId3 . "\n23\nFetchTextTestPage2Text2" );
	}

	public function testFloatingPointNumberNonExisting() {
		$id = intval( preg_replace( '/^tt:/', '', self::$textId5 ) ) + 3.14159;
		@$this->assertFilter( $id, 'tt:' . intval( $id ) . "\n-1\n" );
	}

	public function testCharacters() {
		$this->assertFilter( "abc", "abc\n-1\n" );
	}

	public function testMix() {
		$this->assertFilter( "ab\n" . self::$textId4 . ".5cd\n\nefg\nfoo:bar\n" . self::$textId2
				. "\n" . self::$textId3,
			implode( "", [
				"ab\n-1\n",
				self::$textId4 . ".5cd\n-1\n",
				"\n-1\n",
				"efg\n-1\n",
				"foo:bar\n-1\n",
				self::$textId2 . "\n23\nFetchTextTestPage2Text1",
				self::$textId3 . "\n23\nFetchTextTestPage2Text2"
			] ) );
	}
}
