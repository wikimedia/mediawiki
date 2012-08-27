<?php

require_once __DIR__ . "/../../../maintenance/fetchText.php";

/**
 * Mock for the input/output of FetchText
 *
 * FetchText internally tries to access stdin and stdout. We mock those aspects
 * for testing.
 */
class SemiMockedFetchText extends FetchText {

	/**
	 * @var String|null Text to pass as stdin
	 */
	private $mockStdinText = null;

	/**
	 * @var bool Whether or not a text for stdin has been provided
	 */
	private $mockSetUp = False;

	/**
	 * @var Array Invocation counters for the mocked aspects
	 */
	private $mockInvocations = array( 'getStdin' => 0 );



	/**
	 * Data for the fake stdin
	 *
	 * @param $stdin String The string to be used instead of stdin
	 */
	function mockStdin( $stdin )
	{
		$this->mockStdinText = $stdin;
		$this->mockSetUp = True;
	}

	/**
	 * Gets invocation counters for mocked methods.
	 *
	 * @return Array An array, whose keys are function names. The corresponding values
	 * denote the number of times the function has been invoked.
	 */
	function mockGetInvocations()
	{
		return $this->mockInvocations;
	}

	// -----------------------------------------------------------------
	// Mocked functions from FetchText follow.

	function getStdin( $len = null )
	{
		$this->mockInvocations['getStdin']++;
		if ( $len !== null ) {
			throw new PHPUnit_Framework_ExpectationFailedException(
				"Tried to get stdin with non null parameter" );
		}

		if ( ! $this->mockSetUp ) {
			throw new PHPUnit_Framework_ExpectationFailedException(
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
 */
class FetchTextTest extends MediaWikiTestCase {

	// We add 5 Revisions for this test. Their corresponding text id's
	// are stored in the following 5 variables.
	private $textId1;
	private $textId2;
	private $textId3;
	private $textId4;
	private $textId5;


	/**
	 * @var Exception|null As the current MediaWikiTestCase::run is not
	 * robust enough to recover from thrown exceptions directly, we cannot
	 * throw frow within addDBData, although it would be appropriate. Hence,
	 * we catch the exception and store it until we are in setUp and may
	 * finally rethrow the exception without crashing the test suite.
	 */
	private $exceptionFromAddDBData;

	/**
	 * @var FetchText the (mocked) FetchText that is to test
	 */
	private $fetchText;

	/**
	 * Adds a revision to a page, while returning the resuting text's id
	 *
	 * @param $page WikiPage The page to add the revision to
	 * @param $text String The revisions text
	 * @param $text String The revisions summare
	 *
	 * @throws MWExcepion
	 */
	private function addRevision( $page, $text, $summary ) {
		$status = $page->doEdit( $text, $summary );
		if ( $status->isGood() ) {
			$value = $status->getValue();
			$revision = $value['revision'];
			$id = $revision->getTextId();
			if ( $id > 0 ) {
				return $id;
			}
		}
		throw new MWException( "Could not determine text id" );
	}


	function addDBData() {
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'text';

		try {
			$title = Title::newFromText( 'FetchTextTestPage1' );
			$page = WikiPage::factory( $title );
			$this->textId1 = $this->addRevision( $page, "FetchTextTestPage1Text1", "FetchTextTestPage1Summary1" );

			$title = Title::newFromText( 'FetchTextTestPage2' );
			$page = WikiPage::factory( $title );
			$this->textId2 = $this->addRevision( $page, "FetchTextTestPage2Text1", "FetchTextTestPage2Summary1" );
			$this->textId3 = $this->addRevision( $page, "FetchTextTestPage2Text2", "FetchTextTestPage2Summary2" );
			$this->textId4 = $this->addRevision( $page, "FetchTextTestPage2Text3", "FetchTextTestPage2Summary3" );
			$this->textId5 = $this->addRevision( $page, "FetchTextTestPage2Text4 some additional Text  ", "FetchTextTestPage2Summary4 extra " );
		} catch ( Exception $e ) {
			// We'd love to pass $e directly. However, ... see
			// documentation of exceptionFromAddDBData
			$this->exceptionFromAddDBData = $e;
		}
	}


	protected function setUp() {
		parent::setUp();

		// Check if any Exception is stored for rethrowing from addDBData
		if ( $this->exceptionFromAddDBData !== null ) {
			throw $this->exceptionFromAddDBData;
		}

		$this->fetchText = new SemiMockedFetchText();
	}


	/**
	 * Helper to relate FetchText's input and output
	 */
	private function assertFilter( $input, $expectedOutput ) {
		$this->fetchText->mockStdin( $input );
		$this->fetchText->execute();
		$invocations = $this->fetchText->mockGetInvocations();
		$this->assertEquals( 1, $invocations['getStdin'],
			"getStdin invocation counter" );
		$this->expectOutputString( $expectedOutput );
	}



	// Instead of the following functions, a data provider would be great.
	// However, as data providers are evaluated /before/ addDBData, a data
	// provider would not know the required ids.

	function testExistingSimple() {
		$this->assertFilter( $this->textId2,
			$this->textId2 . "\n23\nFetchTextTestPage2Text1" );
	}

	function testExistingSimpleWithNewline() {
		$this->assertFilter( $this->textId2 . "\n",
			$this->textId2 . "\n23\nFetchTextTestPage2Text1" );
	}

	function testExistingSeveral() {
		$this->assertFilter( "$this->textId1\n$this->textId5\n"
			. "$this->textId3\n$this->textId3",
			implode( "", array(
					$this->textId1 . "\n23\nFetchTextTestPage1Text1",
					$this->textId5 . "\n44\nFetchTextTestPage2Text4 "
					. "some additional Text",
					$this->textId3 . "\n23\nFetchTextTestPage2Text2",
					$this->textId3 . "\n23\nFetchTextTestPage2Text2"
				) ) );
	}

	function testEmpty() {
		$this->assertFilter( "", null );
	}

	function testNonExisting() {
		$this->assertFilter( $this->textId5 + 10, ( $this->textId5 + 10 ) . "\n-1\n" );
	}

	function testNegativeInteger() {
		$this->assertFilter( "-42", "-42\n-1\n" );
	}

	function testFloatingPointNumberExisting() {
		// float -> int -> revision
		$this->assertFilter( $this->textId3 + 0.14159,
			$this->textId3 . "\n23\nFetchTextTestPage2Text2" );
	}

	function testFloatingPointNumberNonExisting() {
		$this->assertFilter( $this->textId5 + 3.14159,
			( $this->textId5 + 3 ) . "\n-1\n" );
	}

	function testCharacters() {
		$this->assertFilter( "abc", "0\n-1\n" );
	}

	function testMix() {
		$this->assertFilter( "ab\n" . $this->textId4 . ".5cd\n\nefg\n" . $this->textId2
			. "\n" . $this->textId3,
			implode( "", array(
					"0\n-1\n",
					$this->textId4 . "\n23\nFetchTextTestPage2Text3",
					"0\n-1\n",
					"0\n-1\n",
					$this->textId2 . "\n23\nFetchTextTestPage2Text1",
					$this->textId3 . "\n23\nFetchTextTestPage2Text2"
				) ) );
	}

}
