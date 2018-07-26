<?php

namespace MediaWiki\Tests\Maintenance;

use DumpBackup;
use Title;
use WikiExporter;
use WikiPage;

/**
 * Tests for page dumps of BackupDumper
 *
 * @group Database
 * @group Dump
 * @covers BackupDumper
 */
class BackupDumperPageTest extends DumpTestCase {

	// We'll add several pages, revision and texts. The following variables hold the
	// corresponding ids.
	private $pageId1, $pageId2, $pageId3, $pageId4;
	private $pageTitle1, $pageTitle2, $pageTitle3, $pageTitle4;
	private $revId1_1, $textId1_1;
	private $revId2_1, $textId2_1, $revId2_2, $textId2_2;
	private $revId2_3, $textId2_3, $revId2_4, $textId2_4;
	private $revId3_1, $textId3_1, $revId3_2, $textId3_2;
	private $revId4_1, $textId4_1;
	private $namespace, $talk_namespace;

	function addDBData() {
		// be sure, titles created here using english namespace names
		$this->setContentLang( 'en' );

		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'ip_changes';
		$this->tablesUsed[] = 'text';

		try {
			$this->namespace = $this->getDefaultWikitextNS();
			$this->talk_namespace = NS_TALK;

			if ( $this->namespace === $this->talk_namespace ) {
				// @todo work around this.
				throw new MWException( "The default wikitext namespace is the talk namespace. "
					. " We can't currently deal with that." );
			}

			$this->pageTitle1 = Title::newFromText( 'BackupDumperTestP1', $this->namespace );
			$page = WikiPage::factory( $this->pageTitle1 );
			list( $this->revId1_1, $this->textId1_1 ) = $this->addRevision( $page,
				"BackupDumperTestP1Text1", "BackupDumperTestP1Summary1" );
			$this->pageId1 = $page->getId();

			$this->pageTitle2 = Title::newFromText( 'BackupDumperTestP2', $this->namespace );
			$page = WikiPage::factory( $this->pageTitle2 );
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

			$this->pageTitle3 = Title::newFromText( 'BackupDumperTestP3', $this->namespace );
			$page = WikiPage::factory( $this->pageTitle3 );
			list( $this->revId3_1, $this->textId3_1 ) = $this->addRevision( $page,
				"BackupDumperTestP3Text1", "BackupDumperTestP2Summary1" );
			list( $this->revId3_2, $this->textId3_2 ) = $this->addRevision( $page,
				"BackupDumperTestP3Text2", "BackupDumperTestP2Summary2" );
			$this->pageId3 = $page->getId();
			$page->doDeleteArticle( "Testing ;)" );

			$this->pageTitle4 = Title::newFromText( 'BackupDumperTestP1', $this->talk_namespace );
			$page = WikiPage::factory( $this->pageTitle4 );
			list( $this->revId4_1, $this->textId4_1 ) = $this->addRevision( $page,
				"Talk about BackupDumperTestP1 Text1",
				"Talk BackupDumperTestP1 Summary1" );
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
			[ $this->pageId2, $this->pageId3, $this->pageId4 ],
			[ $this->pageId1 + 1, $this->pageId2 + 1, $this->pageId3 + 1 ],
			"Page ids increasing without holes" );
	}

	function testFullTextPlain() {
		// Preparing the dump
		$fname = $this->getNewTempFile();

		$dumper = new DumpBackup();
		$dumper->loadWithArgv( [ '--full', '--quiet', '--output', 'file:' . $fname ] );
		$dumper->startId = $this->pageId1;
		$dumper->endId = $this->pageId4 + 1;
		$dumper->setDB( $this->db );

		// Performing the dump
		$dumper->execute();

		// Checking the dumped data
		$this->assertDumpStart( $fname );

		// Page 1
		$this->assertPageStart( $this->pageId1, $this->namespace, $this->pageTitle1->getPrefixedText() );
		$this->assertRevision( $this->revId1_1, "BackupDumperTestP1Summary1",
			$this->textId1_1, 23, "0bolhl6ol7i6x0e7yq91gxgaan39j87",
			"BackupDumperTestP1Text1" );
		$this->assertPageEnd();

		// Page 2
		$this->assertPageStart( $this->pageId2, $this->namespace, $this->pageTitle2->getPrefixedText() );
		$this->assertRevision( $this->revId2_1, "BackupDumperTestP2Summary1",
			$this->textId2_1, 23, "jprywrymfhysqllua29tj3sc7z39dl2",
			"BackupDumperTestP2Text1" );
		$this->assertRevision( $this->revId2_2, "BackupDumperTestP2Summary2",
			$this->textId2_2, 23, "b7vj5ks32po5m1z1t1br4o7scdwwy95",
			"BackupDumperTestP2Text2", $this->revId2_1 );
		$this->assertRevision( $this->revId2_3, "BackupDumperTestP2Summary3",
			$this->textId2_3, 23, "jfunqmh1ssfb8rs43r19w98k28gg56r",
			"BackupDumperTestP2Text3", $this->revId2_2 );
		$this->assertRevision( $this->revId2_4, "BackupDumperTestP2Summary4 extra",
			$this->textId2_4, 44, "6o1ciaxa6pybnqprmungwofc4lv00wv",
			"BackupDumperTestP2Text4 some additional Text", $this->revId2_3 );
		$this->assertPageEnd();

		// Page 3
		// -> Page is marked deleted. Hence not visible

		// Page 4
		$this->assertPageStart(
			$this->pageId4,
			$this->talk_namespace,
			$this->pageTitle4->getPrefixedText()
		);
		$this->assertRevision( $this->revId4_1, "Talk BackupDumperTestP1 Summary1",
			$this->textId4_1, 35, "nktofwzd0tl192k3zfepmlzxoax1lpe",
			"Talk about BackupDumperTestP1 Text1" );
		$this->assertPageEnd();

		$this->assertDumpEnd();
	}

	function testFullStubPlain() {
		// Preparing the dump
		$fname = $this->getNewTempFile();

		$dumper = new DumpBackup();
		$dumper->loadWithArgv( [ '--full', '--quiet', '--output', 'file:' . $fname, '--stub' ] );
		$dumper->startId = $this->pageId1;
		$dumper->endId = $this->pageId4 + 1;
		$dumper->setDB( $this->db );

		// Performing the dump
		$dumper->execute();

		// Checking the dumped data
		$this->assertDumpStart( $fname );

		// Page 1
		$this->assertPageStart( $this->pageId1, $this->namespace, $this->pageTitle1->getPrefixedText() );
		$this->assertRevision( $this->revId1_1, "BackupDumperTestP1Summary1",
			$this->textId1_1, 23, "0bolhl6ol7i6x0e7yq91gxgaan39j87" );
		$this->assertPageEnd();

		// Page 2
		$this->assertPageStart( $this->pageId2, $this->namespace, $this->pageTitle2->getPrefixedText() );
		$this->assertRevision( $this->revId2_1, "BackupDumperTestP2Summary1",
			$this->textId2_1, 23, "jprywrymfhysqllua29tj3sc7z39dl2" );
		$this->assertRevision( $this->revId2_2, "BackupDumperTestP2Summary2",
			$this->textId2_2, 23, "b7vj5ks32po5m1z1t1br4o7scdwwy95", false, $this->revId2_1 );
		$this->assertRevision( $this->revId2_3, "BackupDumperTestP2Summary3",
			$this->textId2_3, 23, "jfunqmh1ssfb8rs43r19w98k28gg56r", false, $this->revId2_2 );
		$this->assertRevision( $this->revId2_4, "BackupDumperTestP2Summary4 extra",
			$this->textId2_4, 44, "6o1ciaxa6pybnqprmungwofc4lv00wv", false, $this->revId2_3 );
		$this->assertPageEnd();

		// Page 3
		// -> Page is marked deleted. Hence not visible

		// Page 4
		$this->assertPageStart(
			$this->pageId4,
			$this->talk_namespace,
			$this->pageTitle4->getPrefixedText()
		);
		$this->assertRevision( $this->revId4_1, "Talk BackupDumperTestP1 Summary1",
			$this->textId4_1, 35, "nktofwzd0tl192k3zfepmlzxoax1lpe" );
		$this->assertPageEnd();

		$this->assertDumpEnd();
	}

	function testCurrentStubPlain() {
		// Preparing the dump
		$fname = $this->getNewTempFile();

		$dumper = new DumpBackup( [ '--output', 'file:' . $fname ] );
		$dumper->startId = $this->pageId1;
		$dumper->endId = $this->pageId4 + 1;
		$dumper->reporting = false;
		$dumper->setDB( $this->db );

		// Performing the dump
		$dumper->dump( WikiExporter::CURRENT, WikiExporter::STUB );

		// Checking the dumped data
		$this->assertDumpStart( $fname );

		// Page 1
		$this->assertPageStart( $this->pageId1, $this->namespace, $this->pageTitle1->getPrefixedText() );
		$this->assertRevision( $this->revId1_1, "BackupDumperTestP1Summary1",
			$this->textId1_1, 23, "0bolhl6ol7i6x0e7yq91gxgaan39j87" );
		$this->assertPageEnd();

		// Page 2
		$this->assertPageStart( $this->pageId2, $this->namespace, $this->pageTitle2->getPrefixedText() );
		$this->assertRevision( $this->revId2_4, "BackupDumperTestP2Summary4 extra",
			$this->textId2_4, 44, "6o1ciaxa6pybnqprmungwofc4lv00wv", false, $this->revId2_3 );
		$this->assertPageEnd();

		// Page 3
		// -> Page is marked deleted. Hence not visible

		// Page 4
		$this->assertPageStart(
			$this->pageId4,
			$this->talk_namespace,
			$this->pageTitle4->getPrefixedText()
		);
		$this->assertRevision( $this->revId4_1, "Talk BackupDumperTestP1 Summary1",
			$this->textId4_1, 35, "nktofwzd0tl192k3zfepmlzxoax1lpe" );
		$this->assertPageEnd();

		$this->assertDumpEnd();
	}

	function testCurrentStubGzip() {
		$this->checkHasGzip();

		// Preparing the dump
		$fname = $this->getNewTempFile();

		$dumper = new DumpBackup( [ '--output', 'gzip:' . $fname ] );
		$dumper->startId = $this->pageId1;
		$dumper->endId = $this->pageId4 + 1;
		$dumper->reporting = false;
		$dumper->setDB( $this->db );

		// Performing the dump
		$dumper->dump( WikiExporter::CURRENT, WikiExporter::STUB );

		// Checking the dumped data
		$this->gunzip( $fname );
		$this->assertDumpStart( $fname );

		// Page 1
		$this->assertPageStart( $this->pageId1, $this->namespace, $this->pageTitle1->getPrefixedText() );
		$this->assertRevision( $this->revId1_1, "BackupDumperTestP1Summary1",
			$this->textId1_1, 23, "0bolhl6ol7i6x0e7yq91gxgaan39j87" );
		$this->assertPageEnd();

		// Page 2
		$this->assertPageStart( $this->pageId2, $this->namespace, $this->pageTitle2->getPrefixedText() );
		$this->assertRevision( $this->revId2_4, "BackupDumperTestP2Summary4 extra",
			$this->textId2_4, 44, "6o1ciaxa6pybnqprmungwofc4lv00wv", false, $this->revId2_3 );
		$this->assertPageEnd();

		// Page 3
		// -> Page is marked deleted. Hence not visible

		// Page 4
		$this->assertPageStart(
			$this->pageId4,
			$this->talk_namespace,
			$this->pageTitle4->getPrefixedText()
		);
		$this->assertRevision( $this->revId4_1, "Talk BackupDumperTestP1 Summary1",
			$this->textId4_1, 35, "nktofwzd0tl192k3zfepmlzxoax1lpe" );
		$this->assertPageEnd();

		$this->assertDumpEnd();
	}

	/**
	 * xmldumps-backup typically performs a single dump that that writes
	 * out three files
	 * - gzipped stubs of everything (meta-history)
	 * - gzipped stubs of latest revisions of all pages (meta-current)
	 * - gzipped stubs of latest revisions of all pages of namespage 0
	 *   (articles)
	 *
	 * We reproduce such a setup with our mini fixture, although we omit
	 * chunks, and all the other gimmicks of xmldumps-backup.
	 */
	function testXmlDumpsBackupUseCase() {
		$this->checkHasGzip();

		$fnameMetaHistory = $this->getNewTempFile();
		$fnameMetaCurrent = $this->getNewTempFile();
		$fnameArticles = $this->getNewTempFile();

		$dumper = new DumpBackup( [ "--full", "--stub", "--output=gzip:" . $fnameMetaHistory,
			"--output=gzip:" . $fnameMetaCurrent, "--filter=latest",
			"--output=gzip:" . $fnameArticles, "--filter=latest",
			"--filter=notalk", "--filter=namespace:!NS_USER",
			"--reporting=1000" ] );
		$dumper->startId = $this->pageId1;
		$dumper->endId = $this->pageId4 + 1;
		$dumper->setDB( $this->db );

		// xmldumps-backup uses reporting. We will not check the exact reported
		// message, as they are dependent on the processing power of the used
		// computer. We only check that reporting does not crash the dumping
		// and that something is reported
		$dumper->stderr = fopen( 'php://output', 'a' );
		if ( $dumper->stderr === false ) {
			$this->fail( "Could not open stream for stderr" );
		}

		// Performing the dump
		$dumper->dump( WikiExporter::FULL, WikiExporter::STUB );

		$this->assertTrue( fclose( $dumper->stderr ), "Closing stderr handle" );

		// Checking meta-history -------------------------------------------------

		$this->gunzip( $fnameMetaHistory );
		$this->assertDumpStart( $fnameMetaHistory );

		// Page 1
		$this->assertPageStart( $this->pageId1, $this->namespace, $this->pageTitle1->getPrefixedText() );
		$this->assertRevision( $this->revId1_1, "BackupDumperTestP1Summary1",
			$this->textId1_1, 23, "0bolhl6ol7i6x0e7yq91gxgaan39j87" );
		$this->assertPageEnd();

		// Page 2
		$this->assertPageStart( $this->pageId2, $this->namespace, $this->pageTitle2->getPrefixedText() );
		$this->assertRevision( $this->revId2_1, "BackupDumperTestP2Summary1",
			$this->textId2_1, 23, "jprywrymfhysqllua29tj3sc7z39dl2" );
		$this->assertRevision( $this->revId2_2, "BackupDumperTestP2Summary2",
			$this->textId2_2, 23, "b7vj5ks32po5m1z1t1br4o7scdwwy95", false, $this->revId2_1 );
		$this->assertRevision( $this->revId2_3, "BackupDumperTestP2Summary3",
			$this->textId2_3, 23, "jfunqmh1ssfb8rs43r19w98k28gg56r", false, $this->revId2_2 );
		$this->assertRevision( $this->revId2_4, "BackupDumperTestP2Summary4 extra",
			$this->textId2_4, 44, "6o1ciaxa6pybnqprmungwofc4lv00wv", false, $this->revId2_3 );
		$this->assertPageEnd();

		// Page 3
		// -> Page is marked deleted. Hence not visible

		// Page 4
		$this->assertPageStart(
			$this->pageId4,
			$this->talk_namespace,
			$this->pageTitle4->getPrefixedText()
		);
		$this->assertRevision( $this->revId4_1, "Talk BackupDumperTestP1 Summary1",
			$this->textId4_1, 35, "nktofwzd0tl192k3zfepmlzxoax1lpe" );
		$this->assertPageEnd();

		$this->assertDumpEnd();

		// Checking meta-current -------------------------------------------------

		$this->gunzip( $fnameMetaCurrent );
		$this->assertDumpStart( $fnameMetaCurrent );

		// Page 1
		$this->assertPageStart( $this->pageId1, $this->namespace, $this->pageTitle1->getPrefixedText() );
		$this->assertRevision( $this->revId1_1, "BackupDumperTestP1Summary1",
			$this->textId1_1, 23, "0bolhl6ol7i6x0e7yq91gxgaan39j87" );
		$this->assertPageEnd();

		// Page 2
		$this->assertPageStart( $this->pageId2, $this->namespace, $this->pageTitle2->getPrefixedText() );
		$this->assertRevision( $this->revId2_4, "BackupDumperTestP2Summary4 extra",
			$this->textId2_4, 44, "6o1ciaxa6pybnqprmungwofc4lv00wv", false, $this->revId2_3 );
		$this->assertPageEnd();

		// Page 3
		// -> Page is marked deleted. Hence not visible

		// Page 4
		$this->assertPageStart(
			$this->pageId4,
			$this->talk_namespace,
			$this->pageTitle4->getPrefixedText()
		);
		$this->assertRevision( $this->revId4_1, "Talk BackupDumperTestP1 Summary1",
			$this->textId4_1, 35, "nktofwzd0tl192k3zfepmlzxoax1lpe" );
		$this->assertPageEnd();

		$this->assertDumpEnd();

		// Checking articles -------------------------------------------------

		$this->gunzip( $fnameArticles );
		$this->assertDumpStart( $fnameArticles );

		// Page 1
		$this->assertPageStart( $this->pageId1, $this->namespace, $this->pageTitle1->getPrefixedText() );
		$this->assertRevision( $this->revId1_1, "BackupDumperTestP1Summary1",
			$this->textId1_1, 23, "0bolhl6ol7i6x0e7yq91gxgaan39j87" );
		$this->assertPageEnd();

		// Page 2
		$this->assertPageStart( $this->pageId2, $this->namespace, $this->pageTitle2->getPrefixedText() );
		$this->assertRevision( $this->revId2_4, "BackupDumperTestP2Summary4 extra",
			$this->textId2_4, 44, "6o1ciaxa6pybnqprmungwofc4lv00wv", false, $this->revId2_3 );
		$this->assertPageEnd();

		// Page 3
		// -> Page is marked deleted. Hence not visible

		// Page 4
		// -> Page is not in $this->namespace. Hence not visible

		$this->assertDumpEnd();

		$this->expectETAOutput();
	}
}
