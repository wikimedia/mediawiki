<?php

global $IP;
define( "NO_COMMAND_LINE", 1 );
define( "PARSER_TESTS", "$IP/maintenance/parserTests.txt" );

require_once( "$IP/maintenance/parserTests.inc" );

class PHPUnitTestRecorder extends TestRecorder {

	function record( $test, $result ) {
		$this->total++;
		$this->success += $result;

	}

	function reportPercentage( $success, $total ) { }
}

class MediaWikiParserTestSuite extends PHPUnit_Framework_TestSuite {
	static private $count;
	static public $parser;
	static public $iter;

	public static function addTables( &$tables ) {
		$tables[] = 'user_properties';
		$tables[] = 'filearchive';
		$tables[] = 'logging';
		$tables[] = 'updatelog';
		$tables[] = 'iwlinks';
		return true;
	}

	public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite();

		global $wgHooks;
		$wgHooks['ParserTestTables'][] = "MediaWikiParserTestSuite::addTables";

		self::$iter = new TestFileIterator( PARSER_TESTS );

		foreach ( self::$iter as $i => $test ) {
			$suite->addTest( new ParserUnitTest( $i, $test['test'] ) );
			self::$count++;
		}
		unset( $tests );

		self::$parser = new PTShell;
		self::$iter->setParser( self::$parser );
		self::$parser->recorder->start();
		self::$parser->setupDatabase();
		self::$iter->rewind();
	/* } */
	/* function setUp() { */
		global $wgParser,  $wgParserConf, $IP, $messageMemc, $wgMemc, $wgDeferredUpdateList,
                  $wgUser, $wgLang, $wgOut, $wgRequest, $wgStyleDirectory, $wgEnableParserCache,
                  $wgMessageCache, $wgUseDatabaseMessages, $wgMsgCacheExpiry, $parserMemc,
                  $wgNamespaceAliases, $wgNamespaceProtection, $wgLocalFileRepo,
                  $wgNamespacesWithSubpages, $wgThumbnailScriptPath, $wgScriptPath,
                  $wgArticlePath, $wgStyleSheetPath, $wgScript, $wgStylePath;

		$wgScript = '/index.php';
		$wgScriptPath = '/';
		$wgArticlePath = '/wiki/$1';
		$wgStyleSheetPath = '/skins';
		$wgStylePath = '/skins';
		$wgThumbnailScriptPath = false;
		$wgLocalFileRepo = array(
			'class' => 'LocalRepo',
			'name' => 'local',
			'directory' => 'test-repo',
			'url' => 'http://example.com/images',
			'hashLevels' => 2,
			'transformVia404' => false,
		);
		$wgNamespaceProtection[NS_MEDIAWIKI] = 'editinterface';
		$wgNamespaceAliases['Image'] = NS_FILE;
		$wgNamespaceAliases['Image_talk'] = NS_FILE_TALK;


		$wgEnableParserCache = false;
		$wgDeferredUpdateList = array();
		$wgMemc =& wfGetMainCache();
		$messageMemc =& wfGetMessageCacheStorage();
		$parserMemc =& wfGetParserCacheStorage();

		$wgContLang = new StubContLang;
		$wgUser = new StubUser;
		$wgLang = new StubUserLang;
		$wgOut = new StubObject( 'wgOut', 'OutputPage' );
		$wgParser = new StubObject( 'wgParser', $wgParserConf['class'], array( $wgParserConf ) );
		$wgRequest = new WebRequest;

		$wgMessageCache = new StubObject( 'wgMessageCache', 'MessageCache',
										  array( $messageMemc, $wgUseDatabaseMessages,
												 $wgMsgCacheExpiry, wfWikiID() ) );
		if ( $wgStyleDirectory === false ) $wgStyleDirectory   = "$IP/skins";

		return $suite;
	}

	public function tearDown() {
		/* $this->teardownDatabase(); */
		$this->recorder->report();
		$this->recorder->end();
		$this->teardownUploadDir( $this->uploadDir );
	}

	public function count() { return self::$count; }

	public function toString() {
		return "MediaWiki Parser Tests";
	}


	private $uploadDir;
	private $keepUploads;
	/**
	 * Remove the dummy uploads directory
	 */
	private function teardownUploadDir( $dir ) {
		if ( $this->keepUploads ) {
			return;
		}

		// delete the files first, then the dirs.
		self::deleteFiles(
			array (
				"$dir/3/3a/Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/180px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/200px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/640px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/120px-Foobar.jpg",

				"$dir/0/09/Bad.jpg",
			)
		);

		self::deleteDirs(
			array (
				"$dir/3/3a",
				"$dir/3",
				"$dir/thumb/6/65",
				"$dir/thumb/6",
				"$dir/thumb/3/3a/Foobar.jpg",
				"$dir/thumb/3/3a",
				"$dir/thumb/3",

				"$dir/0/09/",
				"$dir/0/",

				"$dir/thumb",
				"$dir",
			)
		);
	}

	/**
	 * Delete the specified files, if they exist.
	 * @param array $files full paths to files to delete.
	 */
	private static function deleteFiles( $files ) {
		foreach ( $files as $file ) {
			if ( file_exists( $file ) ) {
				unlink( $file );
			}
		}
	}
	/**
	 * Delete the specified directories, if they exist. Must be empty.
	 * @param array $dirs full paths to directories to delete.
	 */
	private static function deleteDirs( $dirs ) {
		foreach ( $dirs as $dir ) {
			if ( is_dir( $dir ) ) {
				rmdir( $dir );
			}
		}
	}

	/**
	 * Create a dummy uploads directory which will contain a couple
	 * of files in order to pass existence tests.
	 * @return string The directory
	 */
	private function setupUploadDir() {
		global $IP;
		if ( $this->keepUploads ) {
			$dir = wfTempDir() . '/mwParser-images';
			if ( is_dir( $dir ) ) {
				return $dir;
			}
		} else {
			$dir = wfTempDir() . "/mwParser-" . mt_rand() . "-images";
		}

		wfDebug( "Creating upload directory $dir\n" );
		if ( file_exists( $dir ) ) {
			wfDebug( "Already exists!\n" );
			return $dir;
		}
		wfMkdirParents( $dir . '/3/3a' );
		copy( "$IP/skins/monobook/headbg.jpg", "$dir/3/3a/Foobar.jpg" );

		wfMkdirParents( $dir . '/0/09' );
		copy( "$IP/skins/monobook/headbg.jpg", "$dir/0/09/Bad.jpg" );
		return $dir;
	}
}

/**
 * @group Stub
 */
class ParserUnitTest extends PHPUnit_Framework_TestCase {
	private $number = 0;
	private $test = "";

	public function testBogus() {
		$this->markTestSkipped( "This is a stub" );
	}

	public function __construct( $number = null, $test = null ) {
		$this->number = $number;
		$this->test = $test;
	}

	function count() { return 1; }

	public function run( PHPUnit_Framework_TestResult $result = NULL ) {
        PHPUnit_Framework_Assert::resetCount();
        if ( $result === NULL ) {
            $result = new PHPUnit_Framework_TestResult;
        }

		$t = MediaWikiParserTestSuite::$iter->current();
		$k = MediaWikiParserTestSuite::$iter->key();

		if ( !MediaWikiParserTestSuite::$iter->valid() ) {
			return;
		}

		// The only way this should happen is if the parserTest.txt
		// file were modified while the script is running.
		if ( $k != $this->number ) {
			$i = $this->number;
			wfDie( "I got confused!\n" );
		}

		$result->startTest( $this );
		PHPUnit_Util_Timer::start();

		$r = false;
		try {
			$r = MediaWikiParserTestSuite::$parser->runTest(
				$t['test'], $t['input'], $t['result'], $t['options'], $t['config']
  			);
			PHPUnit_Framework_Assert::assertTrue( true, $t['test'] );
		}
		catch ( PHPUnit_Framework_AssertionFailedError $e ) {
			$result->addFailure( $this, $e, PHPUnit_Util_Timer::stop() );
		}
		catch ( Exception $e ) {
			$result->addError( $this, $e, PHPUnit_Util_Timer::stop() );
		}
		PHPUnit_Framework_Assert::assertTrue( true, $t['test'] );

		$result->endTest( $this, PHPUnit_Util_Timer::stop() );

		MediaWikiParserTestSuite::$parser->recorder->record( $t['test'], $r );
		MediaWikiParserTestSuite::$iter->next();
		$this->addToAssertionCount( PHPUnit_Framework_Assert::getCount() );

		return $result;
	}

}

class PTShell extends ParserTest {
	function showTesting( $desc ) {
	}

	function showRunFile( $path ) {
	}

	function showSuccess( $desc ) {
		PHPUnit_Framework_Assert::assertTrue( true, $desc );
		return true;
	}

	function showFailure( $desc, $expected, $got ) {
		PHPUnit_Framework_Assert::assertEquals( $expected, $got, $desc );
	}

}


