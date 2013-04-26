<?php

require_once __DIR__ . "/../../../maintenance/backupPrefetch.inc";

/**
 * Tests for BaseDump
 *
 * @group Dump
 */
class BaseDumpTest extends MediaWikiTestCase {

	/**
	 * @var BaseDump the BaseDump instance used within a test.
	 *
	 * If set, this BaseDump gets automatically closed in tearDown.
	 */
	private $dump = null;

	protected function tearDown() {
		if ( $this->dump !== null ) {
			$this->dump->close();
		}

		// Bug 37458, parent teardown need to be done after closing the
		// dump or it might cause some permissions errors.
		parent::tearDown();
	}

	/**
	 * asserts that a prefetch yields an expected string
	 *
	 * @param $expected string|null: the exepcted result of the prefetch
	 * @param $page int: the page number to prefetch the text for
	 * @param $revision int: the revision number to prefetch the text for
	 */
	private function assertPrefetchEquals( $expected, $page, $revision ) {
		$this->assertEquals( $expected, $this->dump->prefetch( $page, $revision ),
			"Prefetch of page $page revision $revision" );
	}

	function testSequential() {
		$fname = $this->setUpPrefetch();
		$this->dump = new BaseDump( $fname );

		$this->assertPrefetchEquals( "BackupDumperTestP1Text1", 1, 1 );
		$this->assertPrefetchEquals( "BackupDumperTestP2Text1", 2, 2 );
		$this->assertPrefetchEquals( "BackupDumperTestP2Text4 some additional Text", 2, 5 );
		$this->assertPrefetchEquals( "Talk about BackupDumperTestP1 Text1", 4, 8 );
	}

	function testSynchronizeRevisionMissToRevision() {
		$fname = $this->setUpPrefetch();
		$this->dump = new BaseDump( $fname );

		$this->assertPrefetchEquals( "BackupDumperTestP2Text1", 2, 2 );
		$this->assertPrefetchEquals( null, 2, 3 );
		$this->assertPrefetchEquals( "BackupDumperTestP2Text4 some additional Text", 2, 5 );
	}

	function testSynchronizeRevisionMissToPage() {
		$fname = $this->setUpPrefetch();
		$this->dump = new BaseDump( $fname );

		$this->assertPrefetchEquals( "BackupDumperTestP2Text1", 2, 2 );
		$this->assertPrefetchEquals( null, 2, 40 );
		$this->assertPrefetchEquals( "Talk about BackupDumperTestP1 Text1", 4, 8 );
	}

	function testSynchronizePageMiss() {
		$fname = $this->setUpPrefetch();
		$this->dump = new BaseDump( $fname );

		$this->assertPrefetchEquals( "BackupDumperTestP2Text1", 2, 2 );
		$this->assertPrefetchEquals( null, 3, 40 );
		$this->assertPrefetchEquals( "Talk about BackupDumperTestP1 Text1", 4, 8 );
	}

	function testPageMissAtEnd() {
		$fname = $this->setUpPrefetch();
		$this->dump = new BaseDump( $fname );

		$this->assertPrefetchEquals( "BackupDumperTestP2Text1", 2, 2 );
		$this->assertPrefetchEquals( null, 6, 40 );
	}

	function testRevisionMissAtEnd() {
		$fname = $this->setUpPrefetch();
		$this->dump = new BaseDump( $fname );

		$this->assertPrefetchEquals( "BackupDumperTestP2Text1", 2, 2 );
		$this->assertPrefetchEquals( null, 4, 40 );
	}

	function testSynchronizePageMissAtStart() {
		$fname = $this->setUpPrefetch();
		$this->dump = new BaseDump( $fname );

		$this->assertPrefetchEquals( null, 0, 2 );
		$this->assertPrefetchEquals( "BackupDumperTestP2Text1", 2, 2 );
	}

	function testSynchronizeRevisionMissAtStart() {
		$fname = $this->setUpPrefetch();
		$this->dump = new BaseDump( $fname );

		$this->assertPrefetchEquals( null, 1, -2 );
		$this->assertPrefetchEquals( "BackupDumperTestP2Text1", 2, 2 );
	}

	function testSequentialAcrossFiles() {
		$fname1 = $this->setUpPrefetch( array( 1 ) );
		$fname2 = $this->setUpPrefetch( array( 2, 4 ) );
		$this->dump = new BaseDump( $fname1 . ";" . $fname2 );

		$this->assertPrefetchEquals( "BackupDumperTestP1Text1", 1, 1 );
		$this->assertPrefetchEquals( "BackupDumperTestP2Text1", 2, 2 );
		$this->assertPrefetchEquals( "BackupDumperTestP2Text4 some additional Text", 2, 5 );
		$this->assertPrefetchEquals( "Talk about BackupDumperTestP1 Text1", 4, 8 );
	}

	function testSynchronizeSkipAcrossFile() {
		$fname1 = $this->setUpPrefetch( array( 1 ) );
		$fname2 = $this->setUpPrefetch( array( 2 ) );
		$fname3 = $this->setUpPrefetch( array( 4 ) );
		$this->dump = new BaseDump( $fname1 . ";" . $fname2 . ";" . $fname3 );

		$this->assertPrefetchEquals( "BackupDumperTestP1Text1", 1, 1 );
		$this->assertPrefetchEquals( "Talk about BackupDumperTestP1 Text1", 4, 8 );
	}

	function testSynchronizeMissInWholeFirstFile() {
		$fname1 = $this->setUpPrefetch( array( 1 ) );
		$fname2 = $this->setUpPrefetch( array( 2 ) );
		$this->dump = new BaseDump( $fname1 . ";" . $fname2 );

		$this->assertPrefetchEquals( "BackupDumperTestP2Text1", 2, 2 );
	}


	/**
	 * Constructs a temporary file that can be used for prefetching
	 *
	 * The temporary file is removed by DumpBackup upon tearDown.
	 *
	 * @param $requested_pages Array The indices of the page parts that should
	 *             go into the prefetch file. 1,2,4 are available.
	 * @return String The file name of the created temporary file
	 */
	private function setUpPrefetch( $requested_pages = array( 1, 2, 4 ) ) {
		// The file name, where we store the prepared prefetch file
		$fname = $this->getNewTempFile();

		// The header of every prefetch file
		$header = '<mediawiki xmlns="http://www.mediawiki.org/xml/export-0.7/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.mediawiki.org/xml/export-0.7/ http://www.mediawiki.org/xml/export-0.7.xsd" version="0.7" xml:lang="en">
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

		// An array holding the pages that are available for prefetch
		$available_pages = array();

		// Simple plain page
		$available_pages[1] = '  <page>
    <title>BackupDumperTestP1</title>
    <ns>0</ns>
    <id>1</id>
    <revision>
      <id>1</id>
      <timestamp>2012-04-01T16:46:05Z</timestamp>
      <contributor>
        <ip>127.0.0.1</ip>
      </contributor>
      <comment>BackupDumperTestP1Summary1</comment>
      <sha1>0bolhl6ol7i6x0e7yq91gxgaan39j87</sha1>
      <text xml:space="preserve">BackupDumperTestP1Text1</text>
      <model name="wikitext">1</model>
      <format mime="text/x-wiki">1</format>
    </revision>
  </page>
';
		// Page with more than one revisions. Hole in rev ids.
		$available_pages[2] = '  <page>
    <title>BackupDumperTestP2</title>
    <ns>0</ns>
    <id>2</id>
    <revision>
      <id>2</id>
      <timestamp>2012-04-01T16:46:05Z</timestamp>
      <contributor>
        <ip>127.0.0.1</ip>
      </contributor>
      <comment>BackupDumperTestP2Summary1</comment>
      <sha1>jprywrymfhysqllua29tj3sc7z39dl2</sha1>
      <text xml:space="preserve">BackupDumperTestP2Text1</text>
      <model name="wikitext">1</model>
      <format mime="text/x-wiki">1</format>
    </revision>
    <revision>
      <id>5</id>
      <parentid>2</parentid>
      <timestamp>2012-04-01T16:46:05Z</timestamp>
      <contributor>
        <ip>127.0.0.1</ip>
      </contributor>
      <comment>BackupDumperTestP2Summary4 extra</comment>
      <sha1>6o1ciaxa6pybnqprmungwofc4lv00wv</sha1>
      <text xml:space="preserve">BackupDumperTestP2Text4 some additional Text</text>
      <model name="wikitext">1</model>
      <format mime="text/x-wiki">1</format>
    </revision>
  </page>
';
		// Page with id higher than previous id + 1
		$available_pages[4] = '  <page>
    <title>Talk:BackupDumperTestP1</title>
    <ns>1</ns>
    <id>4</id>
    <revision>
      <id>8</id>
      <timestamp>2012-04-01T16:46:05Z</timestamp>
      <contributor>
        <ip>127.0.0.1</ip>
      </contributor>
      <comment>Talk BackupDumperTestP1 Summary1</comment>
      <sha1>nktofwzd0tl192k3zfepmlzxoax1lpe</sha1>
      <model name="wikitext">1</model>
      <format mime="text/x-wiki">1</format>
      <text xml:space="preserve">Talk about BackupDumperTestP1 Text1</text>
    </revision>
  </page>
';

		// The common ending for all files
		$tail = '</mediawiki>
';

		// Putting together the content of the prefetch files
		$content = $header;
		foreach ( $requested_pages as $i ) {
			$this->assertTrue( array_key_exists( $i, $available_pages ),
				"Check for availability of requested page " . $i );
			$content .= $available_pages[$i];
		}
		$content .= $tail;

		$this->assertEquals( strlen( $content ), file_put_contents(
			$fname, $content ), "Length of prepared prefetch" );

		return $fname;
	}
}
