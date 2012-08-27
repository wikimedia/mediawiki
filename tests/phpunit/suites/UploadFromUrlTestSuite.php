<?php

require_once( dirname( __DIR__ ) . '/includes/upload/UploadFromUrlTest.php' );

class UploadFromUrlTestSuite extends PHPUnit_Framework_TestSuite {
	public $savedGlobals = array();

	public static function addTables( &$tables ) {
		$tables[] = 'user_properties';
		$tables[] = 'filearchive';
		$tables[] = 'logging';
		$tables[] = 'updatelog';
		$tables[] = 'iwlinks';

		return true;
	}

	function setUp() {
		global $wgParser, $wgParserConf, $IP, $messageMemc, $wgMemc,
			  $wgUser, $wgLang, $wgOut, $wgRequest, $wgStyleDirectory, $wgEnableParserCache,
			  $wgNamespaceAliases, $wgNamespaceProtection, $parserMemc;

		$tmpGlobals = array();

		$tmpGlobals['wgScript'] = '/index.php';
		$tmpGlobals['wgScriptPath'] = '/';
		$tmpGlobals['wgArticlePath'] = '/wiki/$1';
		$tmpGlobals['wgStyleSheetPath'] = '/skins';
		$tmpGlobals['wgStylePath'] = '/skins';
		$tmpGlobals['wgThumbnailScriptPath'] = false;
		$tmpGlobals['wgLocalFileRepo'] = array(
			'class'           => 'LocalRepo',
			'name'            => 'local',
			'url'             => 'http://example.com/images',
			'hashLevels'      => 2,
			'transformVia404' => false,
			'backend'         => new FSFileBackend( array(
				'name'        => 'local-backend',
				'lockManager' => 'fsLockManager',
				'containerPaths' => array(
					'local-public'  => wfTempDir() . '/test-repo/public',
					'local-thumb'   => wfTempDir() . '/test-repo/thumb',
					'local-temp'    => wfTempDir() . '/test-repo/temp',
					'local-deleted' => wfTempDir() . '/test-repo/delete',
				)
			) ),
		);
		foreach ( $tmpGlobals as $var => $val ) {
			if ( array_key_exists( $var, $GLOBALS ) ) {
				$this->savedGlobals[$var] = $GLOBALS[$var];
			}
			$GLOBALS[$var] = $val;
		}

		$wgNamespaceProtection[NS_MEDIAWIKI] = 'editinterface';
		$wgNamespaceAliases['Image'] = NS_FILE;
		$wgNamespaceAliases['Image_talk'] = NS_FILE_TALK;


		$wgEnableParserCache = false;
		DeferredUpdates::clearPendingUpdates();
		$wgMemc = wfGetMainCache();
		$messageMemc = wfGetMessageCacheStorage();
		$parserMemc = wfGetParserCacheStorage();

		// $wgContLang = new StubContLang;
		$wgUser = new User;
		$context = new RequestContext;
		$wgLang = $context->getLanguage();
		$wgOut = $context->getOutput();
		$wgParser = new StubObject( 'wgParser', $wgParserConf['class'], array( $wgParserConf ) );
		$wgRequest = $context->getRequest();

		if ( $wgStyleDirectory === false ) {
			$wgStyleDirectory   = "$IP/skins";
		}

		RepoGroup::destroySingleton();
		FileBackendGroup::destroySingleton();
	}

	public function tearDown() {
		foreach ( $this->savedGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
		}
		// Restore backends
		RepoGroup::destroySingleton();
		FileBackendGroup::destroySingleton();

		$this->teardownUploadDir( $this->uploadDir );
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
	 *
	 * @param $files Array: full paths to files to delete.
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
	 *
	 * @param $dirs Array: full paths to directories to delete.
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
	 *
	 * @return String: the directory
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

		wfMkdirParents( $dir . '/3/3a', null, __METHOD__ );
		copy( "$IP/skins/monobook/headbg.jpg", "$dir/3/3a/Foobar.jpg" );

		wfMkdirParents( $dir . '/0/09', null, __METHOD__ );
		copy( "$IP/skins/monobook/headbg.jpg", "$dir/0/09/Bad.jpg" );

		return $dir;
	}

	public static function suite() {
		// Hack to invoke the autoloader required to get phpunit to recognize
		// the UploadFromUrlTest class
		class_exists( 'UploadFromUrlTest' );
		$suite = new UploadFromUrlTestSuite( 'UploadFromUrlTest' );
		return $suite;
	}
}
