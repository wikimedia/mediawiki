<?php

require_once dirname( __DIR__ ) . '/includes/upload/UploadFromUrlTest.php';

class UploadFromUrlTestSuite extends PHPUnit_Framework_TestSuite {
	public $savedGlobals = [];

	public static function addTables( &$tables ) {
		$tables[] = 'user_properties';
		$tables[] = 'filearchive';
		$tables[] = 'logging';
		$tables[] = 'updatelog';
		$tables[] = 'iwlinks';

		return true;
	}

	protected function setUp() {
		global $wgParser, $wgParserConf, $IP, $messageMemc, $wgMemc, $wgUser,
			$wgLang, $wgOut, $wgRequest, $wgStyleDirectory,
			$wgParserCacheType, $wgNamespaceAliases, $wgNamespaceProtection,
			$parserMemc;

		$tmpDir = $this->getNewTempDirectory();
		$tmpGlobals = [];

		$tmpGlobals['wgScript'] = '/index.php';
		$tmpGlobals['wgScriptPath'] = '/';
		$tmpGlobals['wgArticlePath'] = '/wiki/$1';
		$tmpGlobals['wgStylePath'] = '/skins';
		$tmpGlobals['wgThumbnailScriptPath'] = false;
		$tmpGlobals['wgLocalFileRepo'] = [
			'class' => 'LocalRepo',
			'name' => 'local',
			'url' => 'http://example.com/images',
			'hashLevels' => 2,
			'transformVia404' => false,
			'backend' => new FSFileBackend( [
				'name' => 'local-backend',
				'wikiId' => wfWikiID(),
				'containerPaths' => [
					'local-public' => "{$tmpDir}/test-repo/public",
					'local-thumb' => "{$tmpDir}/test-repo/thumb",
					'local-temp' => "{$tmpDir}/test-repo/temp",
					'local-deleted' => "{$tmpDir}/test-repo/delete",
				]
			] ),
		];
		foreach ( $tmpGlobals as $var => $val ) {
			if ( array_key_exists( $var, $GLOBALS ) ) {
				$this->savedGlobals[$var] = $GLOBALS[$var];
			}
			$GLOBALS[$var] = $val;
		}

		$wgNamespaceProtection[NS_MEDIAWIKI] = 'editinterface';
		$wgNamespaceAliases['Image'] = NS_FILE;
		$wgNamespaceAliases['Image_talk'] = NS_FILE_TALK;

		$wgParserCacheType = CACHE_NONE;
		DeferredUpdates::clearPendingUpdates();
		$wgMemc = wfGetMainCache();
		$messageMemc = wfGetMessageCacheStorage();
		$parserMemc = wfGetParserCacheStorage();

		RequestContext::resetMain();
		$context = RequestContext::getMain();
		$wgUser = new User;
		$wgLang = $context->getLanguage();
		$wgOut = $context->getOutput();
		$wgParser = new StubObject( 'wgParser', $wgParserConf['class'], [ $wgParserConf ] );
		$wgRequest = $context->getRequest();

		if ( $wgStyleDirectory === false ) {
			$wgStyleDirectory = "$IP/skins";
		}

		RepoGroup::destroySingleton();
		FileBackendGroup::destroySingleton();
	}

	protected function tearDown() {
		foreach ( $this->savedGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
		}
		// Restore backends
		RepoGroup::destroySingleton();
		FileBackendGroup::destroySingleton();

		parent::tearDown();
	}

	/**
	 * Delete the specified files, if they exist.
	 *
	 * @param array $files Full paths to files to delete.
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
	 * @param array $dirs Full paths to directories to delete.
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
	 * @return string The directory
	 */
	private function setupUploadDir() {
		global $IP;

		$dir = $this->getNewTempDirectory();

		wfDebug( "Creating upload directory $dir\n" );

		wfMkdirParents( $dir . '/3/3a', null, __METHOD__ );
		copy( "$IP/tests/phpunit/data/upload/headbg.jpg", "$dir/3/3a/Foobar.jpg" );

		wfMkdirParents( $dir . '/0/09', null, __METHOD__ );
		copy( "$IP/tests/phpunit/data/upload/headbg.jpg", "$dir/0/09/Bad.jpg" );

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
