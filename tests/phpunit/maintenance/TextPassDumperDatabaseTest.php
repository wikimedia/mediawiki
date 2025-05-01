<?php

namespace MediaWiki\Tests\Maintenance;

use BaseDump;
use MediaWiki\Maintenance\TextPassDumper;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWikiLangTestCase;
use WikiExporter;
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
 * @covers \MediaWiki\Maintenance\TextPassDumper
 */
class TextPassDumperDatabaseTest extends DumpTestCase {

	use PageDumpTestDataTrait;

	public function addDBData() {
		parent::addDBData();

		$this->addTestPages( $this->getTestSysop()->getUser() );
	}

	public static function schemaVersionProvider() {
		foreach ( XmlDumpWriter::$supportedSchemas as $schemaVersion ) {
			yield [ $schemaVersion ];
		}
	}

	/**
	 * @dataProvider schemaVersionProvider
	 */
	public function testFullTextPlain( $schemaVersion ) {
		// Setting up the dump
		$nameStub = $this->setUpStub( 'AllStubs', $schemaVersion );
		$nameFull = $this->getNewTempFile();

		$dumper = new TextPassDumper( [ "--stub=file:" . $nameStub,
			"--output=file:" . $nameFull, '--schema-version', $schemaVersion ] );
		$dumper->reporting = false;
		$dumper->setDB( $this->getDb() );

		// Performing the dump
		$dumper->dump( WikiExporter::FULL, WikiExporter::TEXT );

		// Checking the dumped data
		$this->assertDumpSchema( $nameFull, $this->getXmlSchemaPath( $schemaVersion ) );

		$asserter = $this->getDumpAsserter( $schemaVersion );
		$this->setSiteVarMappings( $asserter );
		$this->setAllRevisionsVarMappings( $asserter );

		$siteInfoTemplate = $this->getDumpTemplatePath( 'SiteInfo', $schemaVersion );
		$pagesTemplate = $this->getDumpTemplatePath( 'AllText', $schemaVersion );

		$asserter->open( $nameFull );
		$asserter->assertDumpHead( $siteInfoTemplate );
		$asserter->assertDOM( $pagesTemplate );
		$asserter->assertDumpEnd();
	}

	public function testPrefetchPlain() {
		global $wgXmlDumpSchemaVersion;

		/** @var RevisionRecord[] $revisions */
		$revisions = [
			$this->rev1_1->getId() => $this->rev1_1,
			$this->rev2_1->getId() => $this->rev2_1,
			$this->rev2_2->getId() => $this->rev2_2,
			$this->rev2_3->getId() => $this->rev2_3,
			$this->rev2_4->getId() => $this->rev2_4,
			$this->rev4_1->getId() => $this->rev4_1,
		];

		$getPrefetchText = static function ( $pageid, $revid, $role ) use ( $revisions ) {
			$rev = $revisions[$revid];
			$slot = $rev->getSlot( $role );

			// NOTE: TextPassDumper does a check on the string length,
			// so we have to pad to match the original length. The hash is not checked.
			return str_pad( "Prefetch: ({$pageid}/{$revid}/$role)", $slot->getSize(), '*' );
		};

		// The mock itself
		$prefetchMock = $this->getMockBuilder( BaseDump::class )
			->onlyMethods( [ 'prefetch' ] )
			->disableOriginalConstructor()
			->getMock();
		$prefetchMock->method( 'prefetch' )
			->willReturnCallback( $getPrefetchText );

		// Setting up of the dump
		$nameStub = $this->setUpStub( 'AllStubs', $wgXmlDumpSchemaVersion );
		$nameFull = $this->getNewTempFile();

		$dumper = new TextPassDumper( [ "--stub=file:" . $nameStub,
			"--output=file:" . $nameFull ] );

		$dumper->prefetch = $prefetchMock;
		$dumper->reporting = false;
		$dumper->setDB( $this->getDb() );

		// Performing the dump
		$dumper->dump( WikiExporter::FULL, WikiExporter::TEXT );

		// Checking the dumped data
		$this->assertDumpSchema( $nameFull, $this->getXmlSchemaPath( $wgXmlDumpSchemaVersion ) );

		$asserter = $this->getDumpAsserter( $wgXmlDumpSchemaVersion );
		$this->setSiteVarMappings( $asserter );
		$this->setAllRevisionsVarMappings( $asserter );

		$siteInfoTemplate = $this->getDumpTemplatePath( 'SiteInfo', $wgXmlDumpSchemaVersion );
		$pagesTemplate = $this->getDumpTemplatePath( 'AllText', $wgXmlDumpSchemaVersion );

		$asserter->setVarMapping(
			'rev1_1_main_text',
			$getPrefetchText( $this->rev1_1->getPageId(), $this->rev1_1->getId(), SlotRecord::MAIN )
		);
		$asserter->setVarMapping(
			'rev1_1_aux_text',
			$getPrefetchText( $this->rev1_1->getPageId(), $this->rev1_1->getId(), 'aux' )
		);
		$asserter->setVarMapping(
			'rev2_1_main_text',
			$getPrefetchText( $this->rev2_1->getPageId(), $this->rev2_1->getId(), SlotRecord::MAIN )
		);
		$asserter->setVarMapping(
			'rev2_2_main_text',
			$getPrefetchText( $this->rev2_2->getPageId(), $this->rev2_2->getId(), SlotRecord::MAIN )
		);
		$asserter->setVarMapping(
			'rev2_3_main_text',
			$getPrefetchText( $this->rev2_3->getPageId(), $this->rev2_3->getId(), SlotRecord::MAIN )
		);
		$asserter->setVarMapping(
			'rev2_4_main_text',
			$getPrefetchText( $this->rev2_4->getPageId(), $this->rev2_4->getId(), SlotRecord::MAIN )
		);
		$asserter->setVarMapping(
			'rev4_1_main_text',
			$getPrefetchText( $this->rev4_1->getPageId(), $this->rev4_1->getId(), SlotRecord::MAIN )
		);

		$asserter->open( $nameFull );
		$asserter->assertDumpHead( $siteInfoTemplate );
		$asserter->assertDOM( $pagesTemplate );
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
		global $wgXmlDumpSchemaVersion;

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
			$this->setUpStub( 'AllStubs', $wgXmlDumpSchemaVersion, $nameStub, $iterations );
			$dumper = new TextPassDumper();
			$dumper->loadWithArgv( [ "--stub=file:" . $nameStub,
				"--output=" . $checkpointFormat . ":" . $nameOutputDir . "/full",
				"--maxtime=1", // This is in minutes. Fixup is below
				"--buffersize=32768", // The default of 32 iterations fill up 32 KiB about twice
				"--checkpointfile=checkpoint-%s-%s.xml.gz" ] );
			$dumper->setDB( $this->getDb() );
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
				$asserter->open( $nameOutputDir . "/" . $fname );
				$asserter->assertDumpHead();
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
						$this->pageTitle1->getPrefixedText()
					);
					$asserter->assertRevision(
						$this->rev1_1->getId() + $i * self::$numOfRevs,
						$this->rev1_1->getComment()->text,
						$this->getSlotTextId( $this->rev1_1->getSlot( SlotRecord::MAIN ) ),
						false,
						$this->rev1_1->getSha1(),
						$this->getSlotText( $this->rev1_1->getSlot( SlotRecord::MAIN ) )
					);
					$asserter->assertPageEnd();

					$lookingForPage = 2;
					break;

				case 2:
					// Page 2
					$asserter->assertPageStart(
						$this->pageId2 + $i * self::$numOfPages,
						NS_MAIN,
						$this->pageTitle2->getPrefixedText()
					);
					$asserter->assertRevision(
						$this->rev2_1->getId() + $i * self::$numOfRevs,
						$this->rev2_1->getComment()->text,
						$this->getSlotTextId( $this->rev2_1->getSlot( SlotRecord::MAIN ) ),
						false,
						$this->rev2_1->getSha1(),
						$this->getSlotText( $this->rev2_1->getSlot( SlotRecord::MAIN ) )
					);
					$asserter->assertRevision(
						$this->rev2_2->getId() + $i * self::$numOfRevs,
						$this->rev2_2->getComment()->text,
						$this->getSlotTextId( $this->rev2_2->getSlot( SlotRecord::MAIN ) ),
						false,
						$this->rev2_2->getSha1(),
						$this->getSlotText( $this->rev2_2->getSlot( SlotRecord::MAIN ) ),
						$this->rev2_1->getId() + $i * self::$numOfRevs
					);
					$asserter->assertRevision(
						$this->rev2_3->getId() + $i * self::$numOfRevs,
						$this->rev2_3->getComment()->text,
						$this->getSlotTextId( $this->rev2_3->getSlot( SlotRecord::MAIN ) ),
						false,
						$this->rev2_3->getSha1(),
						$this->getSlotText( $this->rev2_3->getSlot( SlotRecord::MAIN ) ),
						$this->rev2_2->getId() + $i * self::$numOfRevs
					);
					$asserter->assertRevision(
						$this->rev2_4->getId() + $i * self::$numOfRevs,
						$this->rev2_4->getComment()->text,
						$this->getSlotTextId( $this->rev2_4->getSlot( SlotRecord::MAIN ) ),
						false,
						$this->rev2_4->getSha1(),
						$this->getSlotText( $this->rev2_4->getSlot( SlotRecord::MAIN ) ),
						$this->rev2_3->getId() + $i * self::$numOfRevs
					);
					$asserter->assertPageEnd();

					$lookingForPage = 4;
					break;

				case 4:
					// Page 4
					$asserter->assertPageStart(
						$this->pageId4 + $i * self::$numOfPages,
						NS_TALK,
						$this->pageTitle4->getPrefixedText()
					);
					$asserter->assertRevision(
						$this->rev4_1->getId() + $i * self::$numOfRevs,
						$this->rev4_1->getComment()->text,
						$this->getSlotTextId( $this->rev4_1->getSlot( SlotRecord::MAIN ) ),
						false,
						$this->rev4_1->getSha1(),
						$this->getSlotText( $this->rev4_1->getSlot( SlotRecord::MAIN ) ),
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
		}
		$asserter->assertDumpEnd();
		$fileOpened = false;

		// Assuring we completely read all files ...
		$this->assertFalse( $fileOpened, "Currently read file still open?" );
		$this->assertSame( [], $files, "Remaining unchecked files" );

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
	 * @param string $templateName
	 * @param string $schemaVersion
	 * @param string|null $outFile Absolute name of the file to write
	 *   the stub into. If this parameter is null, a new temporary
	 *   file is generated that is automatically removed upon tearDown.
	 * @param int $iterations (Optional) specifies how often the block
	 *   of 3 pages should go into the stub file. The page and
	 *   revision id increase further and further, while the text
	 *   id of the first iteration is reused. The pages and revision
	 *   of iteration > 1 have no corresponding representation in the database.
	 *
	 * @return string Absolute filename of the stub
	 */
	private function setUpStub( $templateName, $schemaVersion, $outFile = null, $iterations = 1 ) {
		$outFile ??= $this->getNewTempFile();

		$templatePath = $this->getDumpTemplatePath( $templateName, $schemaVersion );

		$asserter = $this->getDumpAsserter( $schemaVersion );
		$this->setAllRevisionsVarMappings( $asserter );

		// Make revision point to a non-existent address, to test refreshing
		// content address
		$asserter->setVarMapping( 'rev4_1_main_location', 'tt:11111111' );

		$writer = new XmlDumpWriter( XmlDumpWriter::WRITE_STUB, $schemaVersion );
		$content = $writer->openStream();

		for ( $i = 0; $i < $iterations; $i++ ) {
			$asserter->setVarMapping( 'rev1_1_pageid', $this->pageId1 + $i * self::$numOfPages );
			$asserter->setVarMapping( 'rev1_1_id', $this->rev1_1->getId() + $i * self::$numOfRevs );

			$asserter->setVarMapping( 'rev2_1_pageid', $this->pageId2 + $i * self::$numOfPages );
			$asserter->setVarMapping( 'rev2_1_id', $this->rev2_1->getId() + $i * self::$numOfRevs );
			$asserter->setVarMapping( 'rev2_2_id', $this->rev2_2->getId() + $i * self::$numOfRevs );
			$asserter->setVarMapping( 'rev2_3_id', $this->rev2_3->getId() + $i * self::$numOfRevs );
			$asserter->setVarMapping( 'rev2_4_id', $this->rev2_4->getId() + $i * self::$numOfRevs );

			$asserter->setVarMapping( 'rev4_1_pageid', $this->pageId4 + $i * self::$numOfPages );
			$asserter->setVarMapping( 'rev4_1_id', $this->rev4_1->getId() + $i * self::$numOfRevs );

			$asserter->setVarMapping( 'rev5_1_pageid', $this->pageId5 + $i * self::$numOfPages );
			$asserter->setVarMapping( 'rev5_1_id', $this->rev5_1->getId() + $i * self::$numOfRevs );

			$xml = file_get_contents( $templatePath );
			$xml = $asserter->stripTestTags( $xml );
			$xml = $asserter->resolveVars( $xml );
			$content .= $xml;
		}
		$content .= $writer->closeStream();

		$this->assertEquals( strlen( $content ), file_put_contents(
			$outFile, $content ), "Length of prepared stub" );

		return $outFile;
	}
}

/**
 * Tests for TextPassDumper that do not rely on the database
 *
 * (As the Database group is only detected at class level (not method level), we
 * cannot bring this test case's tests into the above main test case.)
 *
 * @group Dump
 * @covers \MediaWiki\Maintenance\TextPassDumper
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
	public static function bufferSizeProvider() {
		// expected, bufferSize to initialize with, message
		return [
			[ 512 * 1024, 512 * 1024, "Setting 512 KiB is not effective" ],
			[ 8192, 8192, "Setting 8 KiB is not effective" ],
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
	 *
	 * @return int
	 */
	public function getBufferSize() {
		return $this->bufferSize;
	}

	public function dump( $history, $text = null ) {
		return true;
	}
}
