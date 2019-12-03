<?php

namespace MediaWiki\Tests\Maintenance;

use BaseDump;
use Exception;
use MediaWikiLangTestCase;
use MWException;
use TextContent;
use TextContentHandler;
use TextPassDumper;
use Title;
use WikiExporter;
use WikiPage;
use WikitextContent;
use XmlDumpWriter;

/**
 * Tests for TextPassDumper that rely on the database
 *
 * Some of these tests use the old constuctor for TextPassDumper
 * and the dump() function, while others use the new loadWithArgv( $args )
 * function and execute(). This is to ensure both the old and new methods
 * work properly.
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
	private $revId4_1, $textId4_1, $textId4_1_aux;
	private static $numOfRevs = 8;

	public function addDBData() {
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'ip_changes';
		$this->tablesUsed[] = 'text';

		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', [
			"BackupTextPassTestModel" => BackupTextPassTestModelHandler::class,
		] );

		$ns = $this->getDefaultWikitextNS();

		try {
			// Simple page
			$title = Title::newFromText( 'BackupDumperTestP1', $ns );
			$page = WikiPage::factory( $title );
			[ $this->revId1_1, $this->textId1_1 ] = $this->addRevision( $page,
				"BackupDumperTestP1Text1", "BackupDumperTestP1Summary1" );
			$this->pageId1 = $page->getId();

			// Page with more than one revision
			$title = Title::newFromText( 'BackupDumperTestP2', $ns );
			$page = WikiPage::factory( $title );
			[ $this->revId2_1, $this->textId2_1 ] = $this->addRevision( $page,
				"BackupDumperTestP2Text1", "BackupDumperTestP2Summary1" );
			[ $this->revId2_2, $this->textId2_2 ] = $this->addRevision( $page,
				"BackupDumperTestP2Text2", "BackupDumperTestP2Summary2" );
			[ $this->revId2_3, $this->textId2_3 ] = $this->addRevision( $page,
				"BackupDumperTestP2Text3", "BackupDumperTestP2Summary3" );
			[ $this->revId2_4, $this->textId2_4 ] = $this->addRevision( $page,
				"BackupDumperTestP2Text4 some additional Text  ",
				"BackupDumperTestP2Summary4 extra " );
			$this->pageId2 = $page->getId();

			// Deleted page.
			$title = Title::newFromText( 'BackupDumperTestP3', $ns );
			$page = WikiPage::factory( $title );
			[ $this->revId3_1, $this->textId3_1 ] = $this->addRevision( $page,
				"BackupDumperTestP3Text1", "BackupDumperTestP2Summary1" );
			[ $this->revId3_2, $this->textId3_2 ] = $this->addRevision( $page,
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
			[ $this->revId4_1, $textIds4_1 ] = $this->addMultiSlotRevision(
				$page,
				[
					'main' => new TextContent(
						"Talk about BackupDumperTestP1 Text1",
						'BackupTextPassTestModel'
					),
					'aux' => new WikitextContent( "Talk about BackupDumperTestP1 Aux1" ),
				],
				"Talk BackupDumperTestP1 Summary1"
			);
			$this->textId4_1 = $textIds4_1['main'];
			$this->textId4_1_aux = $textIds4_1['aux'];
			$this->pageId4 = $page->getId();
		} catch ( Exception $e ) {
			// We'd love to pass $e directly. However, ... see
			// documentation of exceptionFromAddDBData in
			// DumpTestCase
			$this->exceptionFromAddDBData = $e;
		}
	}

	protected function setUp() : void {
		parent::setUp();

		// Since we will restrict dumping by page ranges (to allow
		// working tests, even if the db gets prepopulated by a base
		// class), we have to assert, that the page id are consecutively
		// increasing
		$this->assertEquals(
			[ $this->pageId2, $this->pageId3, $this->pageId4 ],
			[ $this->pageId1 + 1, $this->pageId1 + 2, $this->pageId1 + 3 ],
			"Page ids increasing without holes" );
	}

	public function testPlain() {
		// Setting up the dump
		$nameStub = $this->setUpStub();
		$nameFull = $this->getNewTempFile();
		$dumper = new TextPassDumper( [ "--stub=file:" . $nameStub,
			"--output=file:" . $nameFull ] );
		$dumper->reporting = false;
		$dumper->setDB( $this->db );

		// Performing the dump
		$dumper->dump( WikiExporter::FULL, WikiExporter::TEXT );

		// Checking for correctness of the dumped data
		$asserter = $this->getDumpAsserter();
		$asserter->assertDumpStart( $nameFull );

		// Page 1
		$asserter->assertPageStart( $this->pageId1, NS_MAIN, "BackupDumperTestP1" );
		$asserter->assertRevision( $this->revId1_1, "BackupDumperTestP1Summary1",
			$this->textId1_1, false, "0bolhl6ol7i6x0e7yq91gxgaan39j87",
			"BackupDumperTestP1Text1" );
		$asserter->assertPageEnd();

		// Page 2
		$asserter->assertPageStart( $this->pageId2, NS_MAIN, "BackupDumperTestP2" );
		$asserter->assertRevision( $this->revId2_1, "BackupDumperTestP2Summary1",
			$this->textId2_1, false, "jprywrymfhysqllua29tj3sc7z39dl2",
			"BackupDumperTestP2Text1" );
		$asserter->assertRevision( $this->revId2_2, "BackupDumperTestP2Summary2",
			$this->textId2_2, false, "b7vj5ks32po5m1z1t1br4o7scdwwy95",
			"BackupDumperTestP2Text2", $this->revId2_1 );
		$asserter->assertRevision( $this->revId2_3, "BackupDumperTestP2Summary3",
			$this->textId2_3, false, "jfunqmh1ssfb8rs43r19w98k28gg56r",
			"BackupDumperTestP2Text3", $this->revId2_2 );
		$asserter->assertRevision( $this->revId2_4, "BackupDumperTestP2Summary4 extra",
			$this->textId2_4, false, "6o1ciaxa6pybnqprmungwofc4lv00wv",
			"BackupDumperTestP2Text4 some additional Text", $this->revId2_3 );
		$asserter->assertPageEnd();

		// Page 3
		// -> Page is marked deleted. Hence not visible

		// Page 4
		$asserter->assertPageStart( $this->pageId4, NS_TALK, "Talk:BackupDumperTestP1" );
		$asserter->assertRevision( $this->revId4_1, "Talk BackupDumperTestP1 Summary1",
			$this->textId4_1, false, "klympv80smh0drjo6zaxd3jh8k3bghx",
			"TALK ABOUT BACKUPDUMPERTESTP1 TEXT1",
			false,
			"BackupTextPassTestModel",
			"text/plain" );
		$asserter->assertPageEnd();

		$asserter->assertDumpEnd();
	}

	public function testPrefetchPlain() {
		// The mapping between ids and text, for the hits of the prefetch mock
		$prefetchMap = [
			[ $this->pageId1, $this->revId1_1, "Prefetch_________1Text1" ],
			[ $this->pageId2, $this->revId2_3, "Prefetch_________2Text3" ]
		];

		// The mock itself
		$prefetchMock = $this->getMockBuilder( BaseDump::class )
			->setMethods( [ 'prefetch' ] )
			->disableOriginalConstructor()
			->getMock();
		$prefetchMock->expects( $this->exactly( 6 ) )
			->method( 'prefetch' )
			->will( $this->returnValueMap( $prefetchMap ) );

		// Setting up of the dump
		$nameStub = $this->setUpStub();
		$nameFull = $this->getNewTempFile();

		$dumper = new TextPassDumper( [ "--stub=file:" . $nameStub,
			"--output=file:" . $nameFull ] );

		$dumper->prefetch = $prefetchMock;
		$dumper->reporting = false;
		$dumper->setDB( $this->db );

		// Performing the dump
		$dumper->dump( WikiExporter::FULL, WikiExporter::TEXT );

		// Checking for correctness of the dumped data
		$asserter = $this->getDumpAsserter();
		$asserter->assertDumpStart( $nameFull );

		// Page 1
		$asserter->assertPageStart( $this->pageId1, NS_MAIN, "BackupDumperTestP1" );
		// Prefetch kicks in. This is still the SHA-1 of the original text,
		// But the actual text (with different SHA-1) comes from prefetch.
		$asserter->assertRevision( $this->revId1_1, "BackupDumperTestP1Summary1",
			$this->textId1_1, false, "0bolhl6ol7i6x0e7yq91gxgaan39j87",
			"Prefetch_________1Text1" );
		$asserter->assertPageEnd();

		// Page 2
		$asserter->assertPageStart( $this->pageId2, NS_MAIN, "BackupDumperTestP2" );
		$asserter->assertRevision( $this->revId2_1, "BackupDumperTestP2Summary1",
			$this->textId2_1, false, "jprywrymfhysqllua29tj3sc7z39dl2",
			"BackupDumperTestP2Text1" );
		$asserter->assertRevision( $this->revId2_2, "BackupDumperTestP2Summary2",
			$this->textId2_2, false, "b7vj5ks32po5m1z1t1br4o7scdwwy95",
			"BackupDumperTestP2Text2", $this->revId2_1 );
		// Prefetch kicks in. This is still the SHA-1 of the original text,
		// But the actual text (with different SHA-1) comes from prefetch.
		$asserter->assertRevision( $this->revId2_3, "BackupDumperTestP2Summary3",
			$this->textId2_3, false, "jfunqmh1ssfb8rs43r19w98k28gg56r",
			"Prefetch_________2Text3", $this->revId2_2 );
		$asserter->assertRevision( $this->revId2_4, "BackupDumperTestP2Summary4 extra",
			$this->textId2_4, false, "6o1ciaxa6pybnqprmungwofc4lv00wv",
			"BackupDumperTestP2Text4 some additional Text", $this->revId2_3 );
		$asserter->assertPageEnd();

		// Page 3
		// -> Page is marked deleted. Hence not visible

		// Page 4
		$asserter->assertPageStart( $this->pageId4, NS_TALK, "Talk:BackupDumperTestP1" );
		$asserter->assertRevision( $this->revId4_1, "Talk BackupDumperTestP1 Summary1",
			$this->textId4_1, false, "klympv80smh0drjo6zaxd3jh8k3bghx",
			"TALK ABOUT BACKUPDUMPERTESTP1 TEXT1",
			false,
			"BackupTextPassTestModel",
			"text/plain" );
		$asserter->assertPageEnd();

		$asserter->assertDumpEnd();
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
			$dumper = new TextPassDumper();
			$dumper->loadWithArgv( [ "--stub=file:" . $nameStub,
				"--output=" . $checkpointFormat . ":" . $nameOutputDir . "/full",
				"--maxtime=1" /*This is in minutes. Fixup is below*/,
				"--buffersize=32768", // The default of 32 iterations fill up 32KB about twice
				"--checkpointfile=checkpoint-%s-%s.xml.gz" ] );
			$dumper->setDB( $this->db );
			$dumper->maxTimeAllowed = $checkpointAfter; // Patching maxTime from 1 minute
			$dumper->stderr = $stderr;

			// The actual dump and taking time
			$ts_before = microtime( true );
			$dumper->execute();
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
		// We now check all the checkpoint files for validity.

		$files = scandir( $nameOutputDir );
		$this->assertTrue( asort( $files ), "Sorting files in temporary directory" );
		$fileOpened = false;
		$lookingForPage = 1;
		$checkpointFiles = 0;

		$asserter = $this->getDumpAsserter();

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
				$asserter->assertDumpStart( $nameOutputDir . "/" . $fname );
				$fileOpened = true;
				$checkpointFiles++;
			}

			// 2. Performing a single page check
			switch ( $lookingForPage ) {
				case 1:
					// Page 1
					$asserter->assertPageStart(
						$this->pageId1 + $i * self::$numOfPages,
						NS_MAIN,
						"BackupDumperTestP1"
					);
					$asserter->assertRevision(
						$this->revId1_1 + $i * self::$numOfRevs,
						"BackupDumperTestP1Summary1",
						$this->textId1_1,
						false,
						"0bolhl6ol7i6x0e7yq91gxgaan39j87",
						"BackupDumperTestP1Text1"
					);
					$asserter->assertPageEnd();

					$lookingForPage = 2;
					break;

				case 2:
					// Page 2
					$asserter->assertPageStart(
						$this->pageId2 + $i * self::$numOfPages,
						NS_MAIN,
						"BackupDumperTestP2"
					);
					$asserter->assertRevision(
						$this->revId2_1 + $i * self::$numOfRevs,
						"BackupDumperTestP2Summary1",
						$this->textId2_1,
						false,
						"jprywrymfhysqllua29tj3sc7z39dl2",
						"BackupDumperTestP2Text1"
					);
					$asserter->assertRevision(
						$this->revId2_2 + $i * self::$numOfRevs,
						"BackupDumperTestP2Summary2",
						$this->textId2_2,
						false,
						"b7vj5ks32po5m1z1t1br4o7scdwwy95",
						"BackupDumperTestP2Text2",
						$this->revId2_1 + $i * self::$numOfRevs
					);
					$asserter->assertRevision(
						$this->revId2_3 + $i * self::$numOfRevs,
						"BackupDumperTestP2Summary3",
						$this->textId2_3,
						false,
						"jfunqmh1ssfb8rs43r19w98k28gg56r",
						"BackupDumperTestP2Text3",
						$this->revId2_2 + $i * self::$numOfRevs
					);
					$asserter->assertRevision(
						$this->revId2_4 + $i * self::$numOfRevs,
						"BackupDumperTestP2Summary4 extra",
						$this->textId2_4,
						false,
						"6o1ciaxa6pybnqprmungwofc4lv00wv",
						"BackupDumperTestP2Text4 some additional Text",
						$this->revId2_3 + $i * self::$numOfRevs
					);
					$asserter->assertPageEnd();

					$lookingForPage = 4;
					break;

				case 4:
					// Page 4
					$asserter->assertPageStart(
						$this->pageId4 + $i * self::$numOfPages,
						NS_TALK,
						"Talk:BackupDumperTestP1"
					);
					$asserter->assertRevision(
						$this->revId4_1 + $i * self::$numOfRevs,
						"Talk BackupDumperTestP1 Summary1",
						$this->textId4_1,
						false,
						"nktofwzd0tl192k3zfepmlzxoax1lpe",
						"TALK ABOUT BACKUPDUMPERTESTP1 TEXT1",
						false,
						"BackupTextPassTestModel",
						"text/plain"
					);
					$asserter->assertPageEnd();

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
				$asserter->assertDumpEnd();
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
	public function testCheckpointPlain() {
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
	public function testCheckpointGzip() {
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
		global $wgXmlDumpSchemaVersion;

		if ( $fname === null ) {
			$fname = $this->getNewTempFile();
		}

		$writer = new XmlDumpWriter( XmlDumpWriter::WRITE_STUB, $wgXmlDumpSchemaVersion );
		$header = $writer->openStream();
		$tail = $writer->closeStream();

		$content = $header;
		$iterations = intval( $iterations );
		$username = $this->getTestUser()->getUser()->getName();
		$userid = $this->getTestUser()->getUser()->getId();
		for ( $i = 0; $i < $iterations; $i++ ) {
			$pageid = $this->pageId1 + $i * self::$numOfPages;
			$page1 = $writer->openPage( (object)[
				'page_id' => $pageid,
				'page_title' => 'BackupDumperTestP1',
				'page_namespace' => NS_MAIN,
				'page_is_redirect' => 0,
				'page_restrictions' => 0,
			] );

			$revid = $this->revId1_1 + $i * self::$numOfRevs;
			$page1 .= $writer->writeRevision(
				(object)[
					'rev_id' => $revid,
					'rev_page' => $pageid,
					'rev_timestamp' => '2012-04-01T16:46:05Z',
					'rev_minor_edit' => 0,
					'rev_deleted' => 0,
					'rev_user' => $userid,
					'rev_user_text' => $username,
					'rev_comment_text' => 'BackupDumperTestP1Summary1',
					'rev_comment_data' => null,
				],
				[
					(object)[
						'model_name' => 'wikitext',
						'role_name' => 'main',
						'slot_revision_id' => $revid,
						'slot_origin' => $revid,
						'slot_content_id' => $this->textId1_1,
						'content_size' => 23,
						'content_address' => 'tt:' . $this->textId1_1,
						'content_sha1' => '0bolhl6ol7i6x0e7yq91gxgaan39j87',
					]
				]
			);

			$page1 .= $writer->closePage();

			$pageid = $this->pageId2 + $i * self::$numOfPages;
			$page2 = $writer->openPage( (object)[
				'page_id' => $pageid,
				'page_title' => 'BackupDumperTestP2',
				'page_namespace' => NS_MAIN,
				'page_is_redirect' => 0,
				'page_restrictions' => 0,
			] );

			$revid1 = $this->revId2_1 + $i * self::$numOfRevs;
			$page2 .= $writer->writeRevision(
				(object)[
					'rev_id' => $revid1,
					'rev_page' => $pageid,
					'rev_timestamp' => '2012-04-01T16:46:05Z',
					'rev_minor_edit' => 0,
					'rev_deleted' => 0,
					'rev_user' => $userid,
					'rev_user_text' => $username,
					'rev_comment_text' => 'BackupDumperTestP2Summary1',
					'rev_comment_data' => null,
				],
				[
					(object)[
						'model_name' => 'wikitext',
						'role_name' => 'main',
						'slot_revision_id' => $revid1,
						'slot_origin' => $revid1,
						'slot_content_id' => $this->textId2_1,
						'content_size' => 23,
						'content_address' => 'tt:' . $this->textId2_1,
						'content_sha1' => 'jprywrymfhysqllua29tj3sc7z39dl2',
					]
				]
			);

			$revid2 = $this->revId2_2 + $i * self::$numOfRevs;
			$page2 .= $writer->writeRevision(
				(object)[
					'rev_id' => $revid2,
					'rev_page' => $pageid,
					'rev_parent_id' => $revid1,
					'rev_timestamp' => '2012-04-01T16:46:05Z',
					'rev_minor_edit' => 0,
					'rev_deleted' => 0,
					'rev_user' => $userid,
					'rev_user_text' => $username,
					'rev_comment_text' => 'BackupDumperTestP2Summary2',
					'rev_comment_data' => null,
				],
				[
					(object)[
						'model_name' => 'wikitext',
						'role_name' => 'main',
						'slot_revision_id' => $revid2,
						'slot_origin' => $revid2,
						'slot_content_id' => $this->textId2_2,
						'content_size' => 23,
						'content_address' => 'tt:' . $this->textId2_2,
						'content_sha1' => 'b7vj5ks32po5m1z1t1br4o7scdwwy95',
					]
				]
			);

			$revid3 = $this->revId2_3 + $i * self::$numOfRevs;
			$page2 .= $writer->writeRevision(
				(object)[
					'rev_id' => $revid3,
					'rev_page' => $pageid,
					'rev_parent_id' => $revid2,
					'rev_timestamp' => '2012-04-01T16:46:05Z',
					'rev_minor_edit' => 0,
					'rev_deleted' => 0,
					'rev_user' => $userid,
					'rev_user_text' => $username,
					'rev_comment_text' => 'BackupDumperTestP2Summary3',
					'rev_comment_data' => null,
				],
				[
					(object)[
						'model_name' => 'wikitext',
						'role_name' => 'main',
						'slot_revision_id' => $revid3,
						'slot_origin' => $revid3,
						'slot_content_id' => $this->textId2_3,
						'content_size' => 23,
						'content_address' => 'tt:' . $this->textId2_3,
						'content_sha1' => 'jfunqmh1ssfb8rs43r19w98k28gg56r',
					]
				]
			);

			$revid4 = $this->revId2_4 + $i * self::$numOfRevs;
			$page2 .= $writer->writeRevision(
				(object)[
					'rev_id' => $revid4,
					'rev_page' => $pageid,
					'rev_parent_id' => $revid3,
					'rev_timestamp' => '2012-04-01T16:46:05Z',
					'rev_minor_edit' => 0,
					'rev_deleted' => 0,
					'rev_user' => $userid,
					'rev_user_text' => $username,
					'rev_comment_text' => 'BackupDumperTestP2Summary4 extra',
					'rev_comment_data' => null,
				],
				[
					(object)[
						'model_name' => 'wikitext',
						'role_name' => 'main',
						'slot_revision_id' => $revid4,
						'slot_origin' => $revid4,
						'slot_content_id' => $this->textId2_4,
						'content_size' => 23,
						'content_address' => 'tt:' . $this->textId2_4,
						'content_sha1' => '6o1ciaxa6pybnqprmungwofc4lv00wv',
					]
				]
			);

			$page2 .= $writer->closePage();

			// page 3 not in stub

			$pageid = $this->pageId4 + $i * self::$numOfPages;
			$page4 = $writer->openPage( (object)[
				'page_id' => $pageid,
				'page_title' => 'BackupDumperTestP1',
				'page_namespace' => NS_TALK,
				'page_is_redirect' => 0,
				'page_restrictions' => 0,
			] );

			$revid = $this->revId4_1 + $i * self::$numOfRevs;
			$page4 .= $writer->writeRevision(
				(object)[
					'rev_id' => $revid,
					'rev_page' => $pageid,
					'rev_timestamp' => '2012-04-01T16:46:05Z',
					'rev_minor_edit' => 0,
					'rev_deleted' => 0,
					'rev_user' => $userid,
					'rev_user_text' => $username,
					'rev_comment_text' => 'Talk BackupDumperTestP1 Summary1',
					'rev_comment_data' => null,
				],
				[
					(object)[
						'model_name' => 'BackupTextPassTestModel',
						'role_name' => 'main',
						'slot_revision_id' => $revid,
						'slot_origin' => $revid,
						'slot_content_id' => $this->textId4_1,
						'content_size' => 23,
						'content_address' => 'tt:' . $this->textId4_1,
						'content_sha1' => 'nktofwzd0tl192k3zfepmlzxoax1lpe',
					],
					(object)[
						'model_name' => 'text',
						'role_name' => 'aux',
						'slot_revision_id' => $revid,
						'slot_origin' => $revid,
						'slot_content_id' => $this->textId4_1_aux,
						'content_size' => 23,
						'content_address' => 'tt:' . $this->textId4_1_aux,
						'content_sha1' => '512zxb9pkklg7s2duhh9oa41ai0fj5z',
					]
				]
			);

			$page4 .= $writer->closePage();

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
	public function testBufferSizeSetting( $expected, $size, $msg ) {
		$dumper = new TextPassDumperAccessor();
		$dumper->loadWithArgv( [ "--buffersize=" . $size ] );
		$dumper->execute();
		$this->assertEquals( $expected, $dumper->getBufferSize(), $msg );
	}

	/**
	 * Ensures that setting the buffer size is effective.
	 *
	 * @dataProvider bufferSizeProvider
	 */
	public function bufferSizeProvider() {
		// expected, bufferSize to initialize with, message
		return [
			[ 512 * 1024, 512 * 1024, "Setting 512KB is not effective" ],
			[ 8192, 8192, "Setting 8KB is not effective" ],
			[ 4096, 2048, "Could set buffer size below lower bound" ]
		];
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

	public function dump( $history, $text = null ) {
		return true;
	}
}
