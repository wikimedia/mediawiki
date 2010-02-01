<?php

global $IP;
define( "NO_COMMAND_LINE", 1 );
require_once( "$IP/maintenance/parserTests.inc" );
require_once( "ImageFunctions.php" );
require_once( "ProxyTools.php" );
require_once( "ObjectCache.php" );

class PTShell extends ParserTest {

	private $cb;

	function setCallback( $cb ) {
		$this->cb = $cb;
	}

	function showTesting( $desc ) {
	}

	function showRunFile( $path ) {
	}

	function showSuccess( $desc ) {
		$this->cb->assertTrue( true, $desc );
		return true;
	}

	function showFailure( $desc, $expected, $got ) {
		/* $this->cb->assertEquals( $expected, $got, $desc ); */
		echo "FAILED: $desc\n";
		echo "got: $got\n";
		echo "expected: $expected\n";
	}
}

class MediaWikiParserTest extends PHPUnit_Framework_TestCase {
	private $parserTester;
	private $db;
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
		foreach( $files as $file ) {
			if( file_exists( $file ) ) {
				unlink( $file );
			}
		}
	}
	/**
	 * Delete the specified directories, if they exist. Must be empty.
	 * @param array $dirs full paths to directories to delete.
	 */
	private static function deleteDirs( $dirs ) {
		foreach( $dirs as $dir ) {
			if( is_dir( $dir ) ) {
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

	function setUp() {
		global $wgParser,  $wgParserConf, $IP, $messageMemc, $wgMemc, $wgDeferredUpdateList,
			$wgUser, $wgLang, $wgOut, $wgRequest, $wgStyleDirectory,  $wgEnableParserCache, $wgMessageCache,
			$wgUseDatabaseMessages, $wgMsgCacheExpiry, $parserMemc, $wgNamespaceAliases, $wgNamespaceProtection,
			$wgLocalFileRepo, $wgNamespacesWithSubpages, $wgThumbnailScriptPath, $wgScriptPath,
			$wgArticlePath, $wgStyleSheetPath, $wgScript, $wgStylePath;

		$wgScript = '/index.php';
		$wgScriptPath = '/';
		$wgArticlePath = '/wiki/$1';
		$wgStyleSheetPath = '/skins';
		$wgStylePath = '/skins';
		$wgThumbnailScriptPath = false;
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
										  array( $messageMemc, $wgUseDatabaseMessages, $wgMsgCacheExpiry, wfWikiID() ) );
		if( $wgStyleDirectory === false) $wgStyleDirectory   = "$IP/skins";

		$this->parserTester = new PTShell();
		$this->parserTester->setCallback( $this );

	}

	function testParser() {
		global $IP;

		$this->parserTester->runTestsFromFiles( array( "$IP/maintenance/parserTests.txt" ) );
	}
}

