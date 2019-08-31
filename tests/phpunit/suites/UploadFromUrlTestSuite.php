<?php

use MediaWiki\MediaWikiServices;

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
		global $IP, $wgMemc, $wgUser, $wgLang, $wgOut, $wgRequest, $wgStyleDirectory,
			$wgParserCacheType, $wgNamespaceAliases, $wgNamespaceProtection;

		$tmpDir = $this->getNewTempDirectory();
		$tmpGlobals = [];

		$tmpGlobals['wgScript'] = '/index.php';
		$tmpGlobals['wgScriptPath'] = '/';
		$tmpGlobals['wgArticlePath'] = '/wiki/$1';
		$tmpGlobals['wgStylePath'] = '/skins';
		$tmpGlobals['wgThumbnailScriptPath'] = false;
		$tmpGlobals['wgLocalFileRepo'] = [
			'class' => LocalRepo::class,
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
		$wgMemc = ObjectCache::getLocalClusterInstance();

		RequestContext::resetMain();
		$context = RequestContext::getMain();
		$wgUser = new User;
		$wgLang = $context->getLanguage();
		$wgOut = $context->getOutput();
		$wgRequest = $context->getRequest();

		if ( $wgStyleDirectory === false ) {
			$wgStyleDirectory = "$IP/skins";
		}

		MediaWikiServices::getInstance()->resetServiceForTesting( 'RepoGroup' );
		FileBackendGroup::destroySingleton();
	}

	protected function tearDown() {
		foreach ( $this->savedGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
		}
		// Restore backends
		MediaWikiServices::getInstance()->resetServiceForTesting( 'RepoGroup' );
		FileBackendGroup::destroySingleton();

		parent::tearDown();
	}

	public static function suite() {
		// Hack to invoke the autoloader required to get phpunit to recognize
		// the UploadFromUrlTest class
		class_exists( 'UploadFromUrlTest' );
		$suite = new UploadFromUrlTestSuite( 'UploadFromUrlTest' );

		return $suite;
	}
}
