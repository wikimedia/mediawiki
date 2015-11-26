<?php

require_once __DIR__ . "/../../../maintenance/backupTextPass.inc";

/**
 * Tests for TextPassDumper that rely on the database
 *
 * @group Database
 * @group Dump
 * @covers TextPassDumper
 */
class TextPassDumperDatabaseTest extends DumpTestCase {

	// We'll add several pages, revision and texts. The following variables hold the
	// corresponding ids.
	private $pageId1, $pageId2, $pageId3, $pageId4;
	private static $numOfPages = 4;
	private $revId1_1, $textId1_1;
	private $revId2_1, $textId2_1, $revId2_2, $textId2_2;
	private $revId2_3, $textId2_3, $revId2_4, $textId2_4;
	private $revId3_1, $textId3_1, $revId3_2, $textId3_2;
	private $revId4_1, $textId4_1;
	private static $numOfRevs = 8;

	function addDBData() {
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'text';

		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', array(
			"BackupTextPassTestModel" => "BackupTextPassTestModelHandler"
		) );

		$ns = $this->getDefaultWikitextNS();

		try {
			// Simple page
			$title = Title::newFromText( 'BackupDumperTestP1', $ns );
			$page = WikiPage::factory( $title );
			list( $this->revId1_1, $this->textId1_1 ) = $this->addRevision( $page,
				"BackupDumperTestP1Text1", "BackupDumperTestP1Summary1" );
			$this->pageId1 = $page->getId();

			// Page with more than one revision
			$title = Title::newFromText( 'BackupDumperTestP2', $ns );
			$page = WikiPage::factory( $title );
			list( $this->revId2_1, $this->textId2_1 ) = $this->addRevision( $page,
				"BackupDumperTestP2Text1", "BackupDumperTestP2Summary1" );
			list( $this->revId2_2, $this->textId2_2 ) = $this->addRevision( $page,
				"BackupDumperTestP2Text2", "BackupDumperTestP2Summary2" );
			list( $this->revId2_3, $this->textId2_3 ) = $this->addRevision( $page,
				"BackupDumperTestP2Text3", "BackupDumperTestP2Summary3" );
			list( $this->revId2_4, $this->textId2_4 ) = $this->addRevision( $page,
				"BackupDumperTestP2Text4 some additional Text  ",
				"BackupDumperTestP2Summary4 extra " );
			$this->pageId2 = $page->getId();

			// Deleted page.
			$title = Title::newFromText( 'BackupDumperTestP3', $ns );
			$page = WikiPage::factory( $title );
			list( $this->revId3_1, $this->textId3_1 ) = $this->addRevision( $page,
				"BackupDumperTestP3Text1", "BackupDumperTestP2Summary1" );
			list( $this->revId3_2, $this->textId3_2 ) = $this->addRevision( $page,
				"BackupDumperTestP3Text2", "BackupDumperTestP2Summary2" );
			$this->pageId3 = $page->getId();
			$page->doDeleteArticle( "Testing ;)" );

			// Page from non-default namespace and model.
			// ExportTransform applies.

			if ( $ns === NS_TALK ) {
				// @todo work around this.
				throw new MWException( "The default wikitext namespace is the talk namespace. "
					. " We can't currently deal with that." );
			}

			$title = Title::newFromText( 'BackupDumperTestP1', NS_TALK );
			$page = WikiPage::factory( $title );
			list( $this->revId4_1, $this->textId4_1 ) = $this->addRevision( $page,
				"Talk about BackupDumperTestP1 Text1",
				"Talk BackupDumperTestP1 Summary1",
				"BackupTextPassTestModel" );
			$this->pageId4 = $page->getId();
		} catch ( Exception $e ) {
			// We'd love to pass $e directly. However, ... see
			// documentation of exceptionFromAddDBData in
			// DumpTestCase
			$this->exceptionFromAddDBData = $e;
		}
	}

	protected function setUp() {
		parent::setUp();

		// Since we will restrict dumping by page ranges (to allow
		// working tests, even if the db gets prepopulated by a base
		// class), we have to assert, that the page id are consecutively
		// increasing
		$this->assertEquals(
			array( $this->pageId2, $this->pageId3, $this->pageId4 ),
			array( $this->pageId1 + 1, $this->pageId2 + 1, $this->pageId3 + 1 ),
			"Page ids increasing without holes" );
	}

	function testPlain() {
		// Setting up the dump
		$nameStub = $this->setUpStub();
		$nameFull = $this->getNewTempFile();
		$dumper = new TextPassDumper( array( "--stub=file:" . $nameStub,
			"--output=file:" . $nameFull ) );
		$dumper->reporting = false;
		$dumper->setDb( $this->db );

		// Performing the dump
		$dumper->dump( WikiExporter::FULL, WikiExporter::TEXT );

		// Checking for correctness of the dumped data
		$this->assertDumpStart( $nameFull );

		// Page 1
		$this->assertPageStart( $this->pageId1, NS_MAIN, "BackupDumperTestP1" );
		$this->assertRevision( $this->revId1_1, "BackupDumperTestP1Summary1",
			$this->textId1_1, false, "0bolhl6ol7i6x0e7yq91gxgaan39j87",
			"BackupDumperTestP1Text1" );
		$this->assertPageEnd();

		// Page 2
		$this->assertPageStart( $this->pageId2, NS_MAIN, "BackupDumperTestP2" );
		$this->assertRevision( $this->revId2_1, "BackupDumperTestP2Summary1",
			$this->textId2_1, false, "jprywrymfhysqllua29tj3sc7z39dl2",
			"BackupDumperTestP2Text1" );
		$this->assertRevision( $this->revId2_2, "BackupDumperTestP2Summary2",
			$this->textId2_2, false, "b7vj5ks32po5m1z1t1br4o7scdwwy95",
			"BackupDumperTestP2Text2", $this->revId2_1 );
		$this->assertRevision( $this->revId2_3, "BackupDumperTestP2Summary3",
			$this->textId2_3, false, "jfunqmh1ssfb8rs43r19w98k28gg56r",
			"BackupDumperTestP2Text3", $this->revId2_2 );
		$this->assertRevision( $this->revId2_4, "BackupDumperTestP2Summary4 extra",
			$this->textId2_4, false, "6o1ciaxa6pybnqprmungwofc4lv00wv",
			"BackupDumperTestP2Text4 some additional Text", $this->revId2_3 );
		$this->assertPageEnd();

		// Page 3
		// -> Page is marked deleted. Hence not visible

		// Page 4
		$this->assertPageStart( $this->pageId4, NS_TALK, "Talk:BackupDumperTestP1" );
		$this->assertRevision( $this->revId4_1, "Talk BackupDumperTestP1 Summary1",
			$this->textId4_1, false, "nktofwzd0tl192k3zfepmlzxoax1lpe",
			"TALK ABOUT BACKUPDUMPERTESTP1 TEXT1",
			false,
			"BackupTextPassTestModel",
			"text/plain" );
		$this->assertPageEnd();

		$this->assertDumpEnd();
	}

	function testPrefetchPlain() {
		// The mapping between ids and text, for the hits of the prefetch mock
		$prefetchMap = array(
			array( $this->pageId1, $this->revId1_1, "Prefetch_________1Text1" ),
			array( $this->pageId2, $this->revId2_3, "Prefetch_________2Text3" )
		);

		// The mock itself
		$prefetchMock = $this->getMock( 'BaseDump', array( 'prefetch' ), array(), '', false );
		$prefetchMock->expects( $this->exactly( 6 ) )
			->method( 'prefetch' )
			->will( $this->returnValueMap( $prefetchMap ) );

		// Setting up of the dump
		$nameStub = $this->setUpStub();
		$nameFull = $this->getNewTempFile();
		$dumper = new TextPassDumper( array( "--stub=file:"
			. $nameStub, "--output=file:" . $nameFull ) );
		$dumper->prefetch = $prefetchMock;
		$dumper->reporting = false;
		$dumper->setDb( $this->db );

		// Performing the dump
		$dumper->dump( WikiExporter::FULL, WikiExporter::TEXT );

		// Checking for correctness of the dumped data
		$this->assertDumpStart( $nameFull );

		// Page 1
		$this->assertPageStart( $this->pageId1, NS_MAIN, "BackupDumperTestP1" );
		// Prefetch kicks in. This is still the SHA-1 of the original text,
		// But the actual text (with different SHA-1) comes from prefetch.
		$this->assertRevision( $this->revId1_1, "BackupDumperTestP1Summary1",
			$this->textId1_1, false, "0bolhl6ol7i6x0e7yq91gxgaan39j87",
			"Prefetch_________1Text1" );
		$this->assertPageEnd();

		// Page 2
		$this->assertPageStart( $this->pageId2, NS_MAIN, "BackupDumperTestP2" );
		$this->assertRevision( $this->revId2_1, "BackupDumperTestP2Summary1",
			$this->textId2_1, false, "jprywrymfhysqllua29tj3sc7z39dl2",
			"BackupDumperTestP2Text1" );
		$this->assertRevision( $this->revId2_2, "BackupDumperTestP2Summary2",
			$this->textId2_2, false, "b7vj5ks32po5m1z1t1br4o7scdwwy95",
			"BackupDumperTestP2Text2", $this->revId2_1 );
		// Prefetch kicks in. This is still the SHA-1 of the original text,
		// But the actual text (with different SHA-1) comes from prefetch.
		$this->assertRevision( $this->revId2_3, "BackupDumperTestP2Summary3",
			$this->textId2_3, false, "jfunqmh1ssfb8rs43r19w98k28gg56r",
			"Prefetch_________2Text3", $this->revId2_2 );
		$this->assertRevision( $this->revId2_4, "BackupDumperTestP2Summary4 extra",
			$this->textId2_4, false, "6o1ciaxa6pybnqprmungwofc4lv00wv",
			"BackupDumperTestP2Text4 some additional Text", $this->revId2_3 );
		$this->assertPageEnd();

		// Page 3
		// -> Page is marked deleted. Hence not visible

		// Page 4
		$this->assertPageStart( $this->pageId4, NS_TALK, "Talk:BackupDumperTestP1" );
		$this->assertRevision( $this->revId4_1, "Talk BackupDumperTestP1 Summary1",
			$this->textId4_1, false, "nktofwzd0tl192k3zfepmlzxoax1lpe",
			"TALK ABOUT BACKUPDUMPERTESTP1 TEXT1",
			false,
			"BackupTextPassTestModel",
			"text/plain" );
		$this->assertPageEnd();

		$this->assertDumpEnd();
	}

	/**
	 * Ensures that checkpoint dumps are used and written, by successively increasing the
	 * stub size and dumping until the duration crosses a threshold.
	 *
	 * @param string $checkpointFormat Either "file" for plain text or "gzip" for gzipped
	 *   checkpoint files.
	 */
	private function checkpointHelper( $checkpointFormat = "file" ) {
		// Getting temporary names
		$nameStub = $this->getNewTempFile();
		$nameOutputDir = $this->getNewTempDirectory();

		$stderr = fopen( 'php://output', 'a' );
		if ( $stderr === false ) {
			$this->fail( "Could not open stream for stderr" );
		}

		$iterations = 32; // We'll start with that many iterations of revisions
		// in stub. Make sure that the generated volume is above the buffer size
		// set below. Otherwise, the checkpointing does not trigger.
		$lastDuration = 0;
		$minDuration = 2; // We want the dump to take at least this many seconds
		$checkpointAfter = 0.5; // Generate checkpoint after this many seconds

		// Until a dump takes at least $minDuration seconds, perform a dump and check
		// duration. If the dump did not take long enough increase the iteration
		// count, to generate a bigger stub file next time.
		while ( $lastDuration < $minDuration ) {

			// Setting up the dump
			wfRecursiveRemoveDir( $nameOutputDir );
			$this->assertTrue( wfMkdirParents( $nameOutputDir ),
				"Creating temporary output directory " );
			$this->setUpStub( $nameStub, $iterations );
			$dumper = new TextPassDumper( array( "--stub=file:" . $nameStub,
				"--output=" . $checkpointFormat . ":" . $nameOutputDir . "/full",
				"--maxtime=1" /*This is in minutes. Fixup is below*/,
				"--buffersize=32768", // The default of 32 iterations fill up 32KB about twice
				"--checkpointfile=checkpoint-%s-%s.xml.gz" ) );
			$dumper->setDb( $this->db );
			$dumper->maxTimeAllowed = $checkpointAfter; // Patching maxTime from 1 minute
			$dumper->stderr = $stderr;

			// The actual dump and taking time
			$ts_before = microtime( true );
			$dumper->dump( WikiExporter::FULL, WikiExporter::TEXT );
			$ts_after = microtime( true );
			$lastDuration = $ts_after - $ts_before;

			// Handling increasing the iteration count for the stubs
			if ( $lastDuration < $minDuration ) {
				$old_iterations = $iterations;
				if ( $lastDuration > 0.2 ) {
					// lastDuration is big enough, to allow an educated guess
					$factor = ( $minDuration + 0.5 ) / $lastDuration;
					if ( ( $factor > 1.1 ) && ( $factor < 100 ) ) {
						// educated guess is reasonable
						$iterations = (int)( $iterations * $factor );
					}
				}

				if ( $old_iterations == $iterations ) {
					// Heuristics were not applied, so we just *2.
					$iterations *= 2;
				}

				$this->assertLessThan( 50000, $iterations,
					"Emergency stop against infinitely increasing iteration "
						. "count ( last duration: $lastDuration )" );
			}
		}

		// The dump (hopefully) did take long enough to produce more than one
		// checkpoint file.
		//
		// We now check all the checkpoint files for validity.

		$files = scandir( $nameOutputDir );
		$this->assertTrue( asort( $files ), "Sorting files in temporary directory" );
		$fileOpened = false;
		$lookingForPage = 1;
		$checkpointFiles = 0;

		// Each run of the following loop body tries to handle exactly 1 /page/ (not
		// iteration of stub content). $i is only increased after having treated page 4.
		for ( $i = 0; $i < $iterations; ) {

			// 1. Assuring a file is opened and ready. Skipping across header if
			//    necessary.
			if ( !$fileOpened ) {
				$this->assertNotEmpty( $files, "No more existing dump files, "
					. "but not yet all pages found" );
				$fname = array_shift( $files );
				while ( $fname == "." || $fname == ".." ) {
					$this->assertNotEmpty( $files, "No more existing dump"
						. " files, but not yet all pages found" );
					$fname = array_shift( $files );
				}
				if ( $checkpointFormat == "gzip" ) {
					$this->gunzip( $nameOutputDir . "/" . $fname );
				}
				$this->assertDumpStart( $nameOutputDir . "/" . $fname );
				$fileOpened = true;
				$checkpointFiles++;
			}

			// 2. Performing a single page check
			switch ( $lookingForPage ) {
				case 1:
					// Page 1
					$this->assertPageStart( $this->pageId1 + $i * self::$numOfPages, NS_MAIN,
						"BackupDumperTestP1" );
					$this->assertRevision( $this->revId1_1 + $i * self::$numOfRevs, "BackupDumperTestP1Summary1",
						$this->textId1_1, false, "0bolhl6ol7i6x0e7yq91gxgaan39j87",
						"BackupDumperTestP1Text1" );
					$this->assertPageEnd();

					$lookingForPage = 2;
					break;

				case 2:
					// Page 2
					$this->assertPageStart( $this->pageId2 + $i * self::$numOfPages, NS_MAIN,
						"BackupDumperTestP2" );
					$this->assertRevision( $this->revId2_1 + $i * self::$numOfRevs, "BackupDumperTestP2Summary1",
						$this->textId2_1, false, "jprywrymfhysqllua29tj3sc7z39dl2",
						"BackupDumperTestP2Text1" );
					$this->assertRevision( $this->revId2_2 + $i * self::$numOfRevs, "BackupDumperTestP2Summary2",
						$this->textId2_2, false, "b7vj5ks32po5m1z1t1br4o7scdwwy95",
						"BackupDumperTestP2Text2", $this->revId2_1 + $i * self::$numOfRevs );
					$this->assertRevision( $this->revId2_3 + $i * self::$numOfRevs, "BackupDumperTestP2Summary3",
						$this->textId2_3, false, "jfunqmh1ssfb8rs43r19w98k28gg56r",
						"BackupDumperTestP2Text3", $this->revId2_2 + $i * self::$numOfRevs );
					$this->assertRevision( $this->revId2_4 + $i * self::$numOfRevs,
						"BackupDumperTestP2Summary4 extra",
						$this->textId2_4, false, "6o1ciaxa6pybnqprmungwofc4lv00wv",
						"BackupDumperTestP2Text4 some additional Text",
						$this->revId2_3 + $i * self::$numOfRevs );
					$this->assertPageEnd();

					$lookingForPage = 4;
					break;

				case 4:
					// Page 4
					$this->assertPageStart( $this->pageId4 + $i * self::$numOfPages, NS_TALK,
						"Talk:BackupDumperTestP1" );
					$this->assertRevision( $this->revId4_1 + $i * self::$numOfRevs,
						"Talk BackupDumperTestP1 Summary1",
						$this->textId4_1, false, "nktofwzd0tl192k3zfepmlzxoax1lpe",
						"TALK ABOUT BACKUPDUMPERTESTP1 TEXT1",
						false,
						"BackupTextPassTestModel",
						"text/plain" );
					$this->assertPageEnd();

					$lookingForPage = 1;

					// We dealt with the whole iteration.
					$i++;
					break;

				default:
					$this->fail( "Bad setting for lookingForPage ($lookingForPage)" );
			}

			// 3. Checking for the end of the current checkpoint file
			if ( $this->xml->nodeType == XMLReader::END_ELEMENT
				&& $this->xml->name == "mediawiki"
			) {
				$this->assertDumpEnd();
				$fileOpened = false;
			}
		}

		// Assuring we completely read all files ...
		$this->assertFalse( $fileOpened, "Currently read file still open?" );
		$this->assertEmpty( $files, "Remaining unchecked files" );

		// ... and have dealt with more than one checkpoint file
		$this->assertGreaterThan(
			1,
			$checkpointFiles,
			"expected more than 1 checkpoint to have been created. "
				. "Checkpoint interval is $checkpointAfter seconds, maybe your computer is too fast?"
		);

		$this->expectETAOutput();
	}

	/**
	 * Broken per T70653.
	 *
	 * @group large
	 * @group Broken
	 */
	function testCheckpointPlain() {
		$this->checkpointHelper();
	}

	/**
	 * tests for working checkpoint generation in gzip format work.
	 *
	 * We keep this test in addition to the simpler self::testCheckpointPlain, as there
	 * were once problems when the used sinks were DumpPipeOutputs.
	 *
	 * xmldumps-backup typically uses bzip2 instead of gzip. However, as bzip2 requires
	 * PHP extensions, we go for gzip instead, which triggers the same relevant code
	 * paths while still being testable on more systems.
	 *
	 * Broken per T70653.
	 *
	 * @group large
	 * @group Broken
	 */
	function testCheckpointGzip() {
		$this->checkHasGzip();
		$this->checkpointHelper( "gzip" );
	}

	/**
	 * Creates a stub file that is used for testing the text pass of dumps
	 *
	 * @param string $fname (Optional) Absolute name of the file to write
	 *   the stub into. If this parameter is null, a new temporary
	 *   file is generated that is automatically removed upon tearDown.
	 * @param int $iterations (Optional) specifies how often the block
	 *   of 3 pages should go into the stub file. The page and
	 *   revision id increase further and further, while the text
	 *   id of the first iteration is reused. The pages and revision
	 *   of iteration > 1 have no corresponding representation in the database.
	 * @return string Absolute filename of the stub
	 */
	private function setUpStub( $fname = null, $iterations = 1 ) {
		if ( $fname === null ) {
			$fname = $this->getNewTempFile();
		}
		$header = '<mediawiki xmlns="http://www.mediawiki.org/xml/export-0.10/" '
			. 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
			. 'xsi:schemaLocation="http://www.mediawiki.org/xml/export-0.10/ '
			. 'http://www.mediawiki.org/xml/export-0.10.xsd" version="0.10" xml:lang="en">
  <siteinfo>
    <sitename>wikisvn</sitename>
    <base>http://localhost/wiki-svn/index.php/Main_Page</base>
    <generator>MediaWiki 1.21alpha</generator>
    <case>first-letter</case>
    <namespaces>
      <namespace key="-2" case="first-letter">Media</namespace>
      <namespace key="-1" case="first-letter">Special</namespace>
      <namespace key="0" case="first-letter" />
      <namespace key="1" case="first-letter">Talk</namespace>
      <namespace key="2" case="first-letter">User</namespace>
      <namespace key="3" case="first-letter">User talk</namespace>
      <namespace key="4" case="first-letter">Wikisvn</namespace>
      <namespace key="5" case="first-letter">Wikisvn talk</namespace>
      <namespace key="6" case="first-letter">File</namespace>
      <namespace key="7" case="first-letter">File talk</namespace>
      <namespace key="8" case="first-letter">MediaWiki</namespace>
      <namespace key="9" case="first-letter">MediaWiki talk</namespace>
      <namespace key="10" case="first-letter">Template</namespace>
      <namespace key="11" case="first-letter">Template talk</namespace>
      <namespace key="12" case="first-letter">Help</namespace>
      <namespace key="13" case="first-letter">Help talk</namespace>
      <namespace key="14" case="first-letter">Category</namespace>
      <namespace key="15" case="first-letter">Category talk</namespace>
    </namespaces>
  </siteinfo>
';
		$tail = '</mediawiki>
';

		$content = $header;
		$iterations = intval( $iterations );
		for ( $i = 0; $i < $iterations; $i++ ) {

			$page1 = '  <page>
    <title>BackupDumperTestP1</title>
    <ns>0</ns>
    <id>' . ( $this->pageId1 + $i * self::$numOfPages ) . '</id>
    <revision>
      <id>' . ( $this->revId1_1 + $i * self::$numOfRevs ) . '</id>
      <timestamp>2012-04-01T16:46:05Z</timestamp>
      <contributor>
        <ip>127.0.0.1</ip>
      </contributor>
      <comment>BackupDumperTestP1Summary1</comment>
      <model>wikitext</model>
      <format>text/x-wiki</format>
      <text id="' . $this->textId1_1 . '" bytes="23" />
      <sha1>0bolhl6ol7i6x0e7yq91gxgaan39j87</sha1>
    </revision>
  </page>
';
			$page2 = '  <page>
    <title>BackupDumperTestP2</title>
    <ns>0</ns>
    <id>' . ( $this->pageId2 + $i * self::$numOfPages ) . '</id>
    <revision>
      <id>' . ( $this->revId2_1 + $i * self::$numOfRevs ) . '</id>
      <timestamp>2012-04-01T16:46:05Z</timestamp>
      <contributor>
        <ip>127.0.0.1</ip>
      </contributor>
      <comment>BackupDumperTestP2Summary1</comment>
      <model>wikitext</model>
      <format>text/x-wiki</format>
      <text id="' . $this->textId2_1 . '" bytes="23" />
      <sha1>jprywrymfhysqllua29tj3sc7z39dl2</sha1>
    </revision>
    <revision>
      <id>' . ( $this->revId2_2 + $i * self::$numOfRevs ) . '</id>
      <parentid>' . ( $this->revId2_1 + $i * self::$numOfRevs ) . '</parentid>
      <timestamp>2012-04-01T16:46:05Z</timestamp>
      <contributor>
        <ip>127.0.0.1</ip>
      </contributor>
      <comment>BackupDumperTestP2Summary2</comment>
      <model>wikitext</model>
      <format>text/x-wiki</format>
      <text id="' . $this->textId2_2 . '" bytes="23" />
      <sha1>b7vj5ks32po5m1z1t1br4o7scdwwy95</sha1>
    </revision>
    <revision>
      <id>' . ( $this->revId2_3 + $i * self::$numOfRevs ) . '</id>
      <parentid>' . ( $this->revId2_2 + $i * self::$numOfRevs ) . '</parentid>
      <timestamp>2012-04-01T16:46:05Z</timestamp>
      <contributor>
        <ip>127.0.0.1</ip>
      </contributor>
      <comment>BackupDumperTestP2Summary3</comment>
      <model>wikitext</model>
      <format>text/x-wiki</format>
      <text id="' . $this->textId2_3 . '" bytes="23" />
      <sha1>jfunqmh1ssfb8rs43r19w98k28gg56r</sha1>
    </revision>
    <revision>
      <id>' . ( $this->revId2_4 + $i * self::$numOfRevs ) . '</id>
      <parentid>' . ( $this->revId2_3 + $i * self::$numOfRevs ) . '</parentid>
      <timestamp>2012-04-01T16:46:05Z</timestamp>
      <contributor>
        <ip>127.0.0.1</ip>
      </contributor>
      <comment>BackupDumperTestP2Summary4 extra</comment>
      <model>wikitext</model>
      <format>text/x-wiki</format>
      <text id="' . $this->textId2_4 . '" bytes="44" />
      <sha1>6o1ciaxa6pybnqprmungwofc4lv00wv</sha1>
    </revision>
  </page>
';
			// page 3 not in stub

			$page4 = '  <page>
    <title>Talk:BackupDumperTestP1</title>
    <ns>1</ns>
    <id>' . ( $this->pageId4 + $i * self::$numOfPages ) . '</id>
    <revision>
      <id>' . ( $this->revId4_1 + $i * self::$numOfRevs ) . '</id>
      <timestamp>2012-04-01T16:46:05Z</timestamp>
      <contributor>
        <ip>127.0.0.1</ip>
      </contributor>
      <comment>Talk BackupDumperTestP1 Summary1</comment>
      <model>BackupTextPassTestModel</model>
      <format>text/plain</format>
      <text id="' . $this->textId4_1 . '" bytes="35" />
      <sha1>nktofwzd0tl192k3zfepmlzxoax1lpe</sha1>
    </revision>
  </page>
';
			$content .= $page1 . $page2 . $page4;
		}
		$content .= $tail;
		$this->assertEquals( strlen( $content ), file_put_contents(
			$fname, $content ), "Length of prepared stub" );

		return $fname;
	}
}

class BackupTextPassTestModelHandler extends TextContentHandler {

	public function __construct() {
		parent::__construct( 'BackupTextPassTestModel' );
	}

	public function exportTransform( $text, $format = null ) {
		return strtoupper( $text );
	}

}

/**
 * Tests for TextPassDumper that do not rely on the database
 *
 * (As the Database group is only detected at class level (not method level), we
 * cannot bring this test case's tests into the above main test case.)
 *
 * @group Dump
 * @covers TextPassDumper
 */
class TextPassDumperDatabaselessTest extends MediaWikiLangTestCase {
	/**
	 * Ensures that setting the buffer size is effective.
	 *
	 * @dataProvider bufferSizeProvider
	 */
	function testBufferSizeSetting( $expected, $size, $msg ) {
		$dumper = new TextPassDumperAccessor( array( "--buffersize=" . $size ) );
		$this->assertEquals( $expected, $dumper->getBufferSize(), $msg );
	}

	/**
	 * Ensures that setting the buffer size is effective.
	 *
	 * @dataProvider bufferSizeProvider
	 */
	function bufferSizeProvider() {
		// expected, bufferSize to initialize with, message
		return array(
			array( 512 * 1024, 512 * 1024, "Setting 512KB is not effective" ),
			array( 8192, 8192, "Setting 8KB is not effective" ),
			array( 4096, 2048, "Could set buffer size below lower bound" )
		);
	}
}

/**
 * Accessor for internal state of TextPassDumper
 *
 * Do not warrentless add getters here.
 */
class TextPassDumperAccessor extends TextPassDumper {
	/**
	 * Gets the bufferSize.
	 *
	 * If bufferSize setting does not work correctly, testCheckpoint... tests
	 * fail and point in the wrong direction. To aid in troubleshooting when
	 * testCheckpoint... tests break at some point in the future, we test the
	 * bufferSize setting, hence need this accessor.
	 *
	 * (Yes, bufferSize is internal state of the TextPassDumper, but aiding
	 * debugging of testCheckpoint... in the future seems to be worth testing
	 * against it nonetheless.)
	 */
	public function getBufferSize() {
		return $this->bufferSize;
	}
}
