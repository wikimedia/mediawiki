<?php

class SetupDynamicConfigTest extends MediaWikiUnitTestCase {
	/**
	 * Returns a callback that replaces its single input with all occurrences of $find replaced
	 * with $replace. Arrays are traversed recursively, and values that are neither arrays nor
	 * strings are returned as-is. $find and $replace may be arrays of the same length, in which
	 * case all replacements are performed.
	 *
	 * @param string|string[] $find
	 * @param string|string[] $replace
	 * @return Closure
	 */
	private static function recursiveReplaceCallback( $find, $replace ): Closure {
		$callback = static function ( $val ) use ( $find, $replace, &$callback ) {
			if ( is_array( $val ) ) {
				return array_map( $callback, $val );
			}
			if ( !is_string( $val ) ) {
				return $val;
			}
			return str_replace( $find, $replace, $val );
		};
		return $callback;
	}

	public static function provideGlobals(): Generator {
		$expected = [
			'IP' => '/install/path',
			'wgScriptPath' => '/wiki',
			'wgScript' => '/wiki/index.php',
			'wgLoadScript' => '/wiki/load.php',
			'wgRestPath' => '/wiki/rest.php',
			// XXX $wgUsePathInfo set based on PHP_SAPI, no good way to test?
			// XXX Testing $wgArticlePath doesn't seem to work
			'wgResourceBasePath' => '/wiki',
			'wgStylePath' => '/wiki/skins',
			'wgLocalStylePath' => '/wiki/skins',
			'wgExtensionAssetsPath' => '/wiki/extensions',
			'wgLogo' => '/wiki/resources/assets/change-your-logo.svg',
			'wgUploadPath' => '/wiki/images',
			'wgUploadDirectory' => '/install/path/images',
			'wgReadOnlyFile' => '/install/path/images/lock_yBgMBwiR',
			'wgFileCacheDirectory' => '/install/path/images/cache',
			'wgDeletedDirectory' => '/install/path/images/deleted',
			'wgSharedPrefix' => '',
			'wgSharedSchema' => null,
			'wgMetaNamespace' => 'MediaWiki',
			'wgMainWANCache' => 'mediawiki-main-default',
			'wgWANObjectCaches' => [
				// XXX Is this duplication really intentional? Isn't the first entry unused?
				0 => [
					'class' => 'WANObjectCache',
					'cacheId' => 0,
				],
				'mediawiki-main-default' => [
					'class' => 'WANObjectCache',
					'cacheId' => 0,
				],
			],
			'wgEnableUserEmailMuteList' => false,
			'wgEnableUserEmailBlacklist' => false,
			'wgNamespaceProtection' => [ NS_MEDIAWIKI => 'editinterface' ],
			'wgLockManagers' => [ [
				'name' => 'fsLockManager',
				'class' => FSLockManager::class,
				'lockDirectory' => '/install/path/images/lockdir',
			], [
				'name' => 'nullLockManager',
				'class' => NullLockManager::class,
			] ],
			// Can't really test this properly without being able to mock global functions
			'wgShowEXIF' => function_exists( 'exif_read_data' ),
			'wgGalleryOptions' => [
				'imagesPerRow' => 0,
				'imageWidth' => 120,
				'imageHeight' => 120,
				'captionLength' => true,
				'showBytes' => true,
				'showDimensions' => true,
				'mode' => 'traditional',
			],
			'wgLocalFileRepo' => [
				'class' => LocalRepo::class,
				'name' => 'local',
				'directory' => '/install/path/images',
				'scriptDirUrl' => '/wiki',
				'favicon' => '/favicon.ico',
				'url' => '/wiki/images',
				'hashLevels' => 2,
				'thumbScriptUrl' => false,
				'transformVia404' => false,
				'deletedDir' => '/install/path/images/deleted',
				'deletedHashLevels' => 3,
				'updateCompatibleMetadata' => false,
				'reserializeMetadata' => false,
				'backend' => 'local-backend',
			],
			'wgCookiePrefix' => 'my_wiki',
		];

		yield 'Nothing set' => [ [], $expected ];
		yield '$wgScriptPath set' => [
			[ 'wgScriptPath' => '/mywiki' ],
			array_map( self::recursiveReplaceCallback( '/wiki', '/mywiki' ), $expected ),
		];
		yield '$wgResourceBasePath set' => [
			[ 'wgResourceBasePath' => '/resources' ],
			[
				'wgResourceBasePath' => '/resources',
				'wgExtensionAssetsPath' => '/resources/extensions',
				'wgStylePath' => '/resources/skins',
				'wgLogo' => '/resources/resources/assets/change-your-logo.svg',
			] + $expected,
		];
		yield '$wgUploadDirectory set' => [
			[ 'wgUploadDirectory' => '/uploads' ],
			[
				'wgUploadDirectory' => '/uploads',
				'wgReadOnlyFile' => '/uploads/lock_yBgMBwiR',
				'wgFileCacheDirectory' => '/uploads/cache',
				'wgDeletedDirectory' => '/uploads/deleted',
				'wgLockManagers' => [ [
					'name' => 'fsLockManager',
					'class' => FSLockManager::class,
					'lockDirectory' => '/uploads/lockdir',
				], [
					'name' => 'nullLockManager',
					'class' => NullLockManager::class,
				] ],
				'wgLocalFileRepo' => [
					'class' => LocalRepo::class,
					'name' => 'local',
					'directory' => '/uploads',
					'scriptDirUrl' => '/wiki',
					'favicon' => '/favicon.ico',
					'url' => '/wiki/images',
					'hashLevels' => 2,
					'thumbScriptUrl' => false,
					'transformVia404' => false,
					'deletedDir' => '/uploads/deleted',
					'deletedHashLevels' => 3,
					'updateCompatibleMetadata' => false,
					'reserializeMetadata' => false,
					'backend' => 'local-backend',
				],
			] + $expected,
		];
		yield '$wgCacheDirectory set' => [
			[ 'wgCacheDirectory' => '/cache' ],
			[ 'wgCacheDirectory' => '/cache' ] + $expected,
		];
		yield '$wgDBprefix set' => [
			[ 'wgDBprefix' => 'prefix_' ],
			[
				'wgDBprefix' => 'prefix_',
				'wgSharedPrefix' => 'prefix_',
				'wgCookiePrefix' => 'my_wiki_prefix_',
			] + $expected,
		];
		yield '$wgDBmwschema set' => [
			[ 'wgDBmwschema' => 'schema' ],
			[
				'wgDBmwschema' => 'schema',
				'wgSharedSchema' => 'schema',
			] + $expected,
		];
		yield '$wgSitename set' => [
			[ 'wgSitename' => 'my site' ],
			[
				'wgSitename' => 'my site',
				'wgMetaNamespace' => 'my_site',
			] + $expected,
		];
		yield '$wgMainCacheType set' => [
			[ 'wgMainCacheType' => 7 ],
			[
				'wgMainCacheType' => 7,
				'wgWANObjectCaches' => [
					0 => [
						'class' => 'WANObjectCache',
						'cacheId' => 0,
					],
					'mediawiki-main-default' => [
						'class' => 'WANObjectCache',
						'cacheId' => 7,
					],
				],
			] + $expected,
		];
		yield '$wgMainWANCache set' => [
			[ 'wgMainWANCache' => 'my-cache' ],
			[
				'wgMainWANCache' => 'my-cache',
				// XXX Is this intentional? Customizing MainWANCache without adding it to
				// WANObjectCaches seems like it will break everything?
				'wgWANObjectCaches' => [ [ 'class' => 'WANObjectCache', 'cacheId' => 0 ] ],
			] + $expected,
		];
		yield '$wgProhibitedFileExtensions set' => [
			[ 'wgProhibitedFileExtensions' => [ 'evil' ] ],
			[
				'wgProhibitedFileExtensions' => [ 'evil' ],
				'wgFileBlacklist' => [ 'evil' ],
			] + $expected,
		];
		yield '$wgProhibitedFileExtensions and $wgFileBlacklist set' => [
			[
				'wgProhibitedFileExtensions' => [ 'evil' ],
				'wgFileBlacklist' => [ 'eviler' ],
			],
			[
				'wgProhibitedFileExtensions' => [ 'evil', 'eviler' ],
				'wgFileBlacklist' => [ 'eviler' ],
			] + $expected,
		];
		yield '$wgMimeTypeExclusions set' => [
			[ 'wgMimeTypeExclusions' => [ 'evil' ] ],
			[
				'wgMimeTypeExclusions' => [ 'evil' ],
				'wgMimeTypeBlacklist' => [ 'evil' ],
			] + $expected,
		];
		yield '$wgMimeTypeExclusions and $wgMimeTypeBlacklist set' => [
			[
				'wgMimeTypeExclusions' => [ 'evil' ],
				'wgMimeTypeBlacklist' => [ 'eviler' ],
			],
			[
				'wgMimeTypeExclusions' => [ 'evil', 'eviler' ],
				'wgMimeTypeBlacklist' => [ 'eviler' ],
			] + $expected,
		];
		yield '$wgEnableUserEmailMuteList set' => [
			[ 'wgEnableUserEmailMuteList' => true ],
			[
				'wgEnableUserEmailMuteList' => true,
				'wgEnableUserEmailBlacklist' => true,
			] + $expected,
		];
		yield '$wgEnableUserEmailMuteList and $wgEnableUserEmailBlacklist both true' => [
			[
				'wgEnableUserEmailMuteList' => true,
				'wgEnableUserEmailBlacklist' => true,
			],
			[
				'wgEnableUserEmailMuteList' => true,
				'wgEnableUserEmailBlacklist' => true,
			] + $expected,
		];
		yield '$wgEnableUserEmailMuteList true and $wgEnableUserEmailBlacklist false' => [
			[
				'wgEnableUserEmailMuteList' => true,
				'wgEnableUserEmailBlacklist' => false,
			],
			[
				'wgEnableUserEmailMuteList' => false,
				'wgEnableUserEmailBlacklist' => false,
			] + $expected,
		];
		yield '$wgShortPagesNamespaceExclusions set' => [
			[ 'wgShortPagesNamespaceExclusions' => [ NS_TALK ] ],
			[
				'wgShortPagesNamespaceExclusions' => [ NS_TALK ],
				'wgShortPagesNamespaceBlacklist' => [ NS_TALK ],
			] + $expected,
		];
		yield '$wgShortPagesNamespaceBlacklist set' => [
			[ 'wgShortPagesNamespaceBlacklist' => [ NS_PROJECT ] ],
			[
				'wgShortPagesNamespaceExclusions' => [ NS_PROJECT ],
				'wgShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			] + $expected,
		];
		yield '$wgShortPagesNamespaceExclusions and $wgShortPagesNamespaceBlacklist set' => [
			[
				'wgShortPagesNamespaceExclusions' => [ NS_TALK ],
				'wgShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			],
			[
				'wgShortPagesNamespaceExclusions' => [ NS_PROJECT ],
				'wgShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			] + $expected,
		];
		yield '$wgFileExtension contains something from $wgProhibitedFileExtensions' => [
			[
				'wgFileExtensions' => [ 'a', 'b', 'c' ],
				'wgProhibitedFileExtensions' => [ 'b', 'd' ],
			],
			[
				'wgFileExtensions' => [ 'a', 'c' ],
				'wgProhibitedFileExtensions' => [ 'b', 'd' ],
			] + $expected,
		];
		yield '$wgRightsIcon old path convention' => [
			[
				'wgStylePath' => '/style',
				'wgResourceBasePath' => '/resources',
				'wgRightsIcon' => '/style/common/images/rights.png',
			],
			[
				'wgStylePath' => '/style',
				'wgResourceBasePath' => '/resources',
				'wgExtensionAssetsPath' => '/resources/extensions',
				'wgLogo' => '/resources/resources/assets/change-your-logo.svg',
				'wgRightsIcon' => '/resources/resources/assets/licenses/rights.png',
			] + $expected,
		];
		yield 'Empty $wgFooterIcons[\'copyright\'][\'copyright\'], $wgRightsIcon set' => [
			[
				'wgRightsIcon' => 'ico',
				'wgFooterIcons' => [ 'copyright' => [ 'copyright' => [] ] ],
			],
			[
				'wgFooterIcons' => [ 'copyright' => [ 'copyright' => [
					'url' => null,
					'src' => 'ico',
					'alt' => null,
				] ] ],
			] + $expected,
		];
		yield 'Empty $wgFooterIcons[\'copyright\'][\'copyright\'], $wgRightsText set' => [
			[
				'wgRightsText' => 'text',
				'wgFooterIcons' => [ 'copyright' => [ 'copyright' => [] ] ],
			],
			[
				'wgFooterIcons' => [ 'copyright' => [ 'copyright' => [
					'url' => null,
					'src' => null,
					'alt' => 'text',
				] ] ],
			] + $expected,
		];
		yield '$wgFooterIcons[\'poweredby\'][\'mediawiki\'][\'src\'] === null' => [
			[
				'wgResourceBasePath' => '/resources',
				'wgFooterIcons' => [ 'poweredby' => [ 'mediawiki' => [ 'src' => null ] ] ],
			],
			[
				'wgResourceBasePath' => '/resources',
				'wgExtensionAssetsPath' => '/resources/extensions',
				'wgStylePath' => '/resources/skins',
				'wgLogo' => '/resources/resources/assets/change-your-logo.svg',
				'wgFooterIcons' => [ 'poweredby' => [ 'mediawiki' => [
					'src' => '/resources/resources/assets/poweredby_mediawiki_88x31.png',
					'srcset' => '/resources/resources/assets/poweredby_mediawiki_132x47.png 1.5x,' .
						' /resources/resources/assets/poweredby_mediawiki_176x62.png 2x',
				] ] ],
			] + $expected,
		];
		yield '$wgFooterIcons[\'poweredby\'][\'mediawiki\'][\'src\'] === \'\'' => [
			[ 'wgFooterIcons' => [ 'poweredby' => [ 'mediawiki' => [ 'src' => '' ] ] ] ],
			[ 'wgFooterIcons' => [ 'poweredby' => [ 'mediawiki' => [ 'src' => '' ] ] ] ] +
				$expected,
		];
		yield '$wgFooterIcons[\'poweredby\']=== []' => [
			[ 'wgFooterIcons' => [ 'poweredby' => [] ] ],
			[ 'wgFooterIcons' => [ 'poweredby' => [] ] ] + $expected,
		];
		yield '$wgNamespaceProtection set only for non-NS_MEDIAWIKI' => [
			[ 'wgNamespaceProtection' => [ NS_PROJECT => 'editprotected' ] ],
			[ 'wgNamespaceProtection' => [
				NS_PROJECT => 'editprotected',
				NS_MEDIAWIKI => 'editinterface',
			] ] + $expected,
		];
		yield '$wgNamespaceProtection[NS_MEDIAWIKI] not editinterface' => [
			[ 'wgNamespaceProtection' => [ NS_MEDIAWIKI => 'editprotected' ] ],
			[ 'wgNamespaceProtection' => [ NS_MEDIAWIKI => 'editinterface' ] ] + $expected,
		];
		yield 'Custom lock manager' => [
			[ 'wgLockManagers' => [ [ 'foo' ] ] ],
			[ 'wgLockManagers' => [ [ 'foo' ], [
				'name' => 'fsLockManager',
				'class' => FSLockManager::class,
				'lockDirectory' => '/install/path/images/lockdir',
			], [
				'name' => 'nullLockManager',
				'class' => NullLockManager::class,
			] ] ] + $expected,
		];
		yield 'Customize some $wgGalleryOptions' => [
			[ 'wgGalleryOptions' => [
				'imagesPerRow' => 42,
				'imageHeight' => -1,
				'showBytes' => null,
				'mode' => 'iconoclastic',
				'custom' => 'Rembrandt',
			] ],
			[ 'wgGalleryOptions' => [
				'imagesPerRow' => 42,
				'imageHeight' => -1,
				'showBytes' => null,
				'mode' => 'iconoclastic',
				'custom' => 'Rembrandt',
				'imageWidth' => 120,
				'captionLength' => true,
				'showDimensions' => true,
			] ] + $expected,
		];
		yield 'Set $wgLocalFileRepo' => [
			[ 'wgLocalFileRepo' => [ 'name' => 'asdfgh' ] ],
			[ 'wgLocalFileRepo' => [
				'name' => 'asdfgh', 'backend' => 'asdfgh-backend'
			] ] + $expected,
		];
		$sharedUploadsExpected = [
			'class' => FileRepo::class,
			'name' => 'shared',
			'directory' => null,
			'url' => null,
			'hashLevels' => 2,
			'thumbScriptUrl' => false,
			'transformVia404' => false,
			'descBaseUrl' => 'https://commons.wikimedia.org/wiki/File:',
			'fetchDescription' => false,
			'backend' => 'shared-backend',
		];
		yield '$wgUseSharedUploads' => [
			[ 'wgUseSharedUploads' => true ],
			[ 'wgForeignFileRepos' => [ $sharedUploadsExpected ] ] + $expected,
		];
		$sharedUploadsDBnameExpected = [
			'class' => ForeignDBRepo::class,
			'name' => 'shared',
			'directory' => null,
			'url' => null,
			'hashLevels' => 2,
			'thumbScriptUrl' => false,
			'transformVia404' => false,
			'dbType' => 'mysql',
			'dbServer' => 'localhost',
			'dbUser' => 'wikiuser',
			'dbPassword' => '',
			'dbName' => 'shared_uploads',
			'dbFlags' => DBO_DEFAULT,
			'tablePrefix' => '',
			'hasSharedCache' => true,
			'descBaseUrl' => 'https://commons.wikimedia.org/wiki/File:',
			'fetchDescription' => false,
			'backend' => 'shared-backend',
		];
		yield '$wgUseSharedUploads and $wgSharedUploadDBname' => [
			[
				'wgUseSharedUploads' => true,
				'wgSharedUploadDBname' => 'shared_uploads',
			],
			[ 'wgForeignFileRepos' => [ $sharedUploadsDBnameExpected ] ] + $expected,
		];
		$instantCommonsExpected = [
			'class' => ForeignAPIRepo::class,
			'name' => 'wikimediacommons',
			'apibase' => 'https://commons.wikimedia.org/w/api.php',
			'url' => 'https://upload.wikimedia.org/wikipedia/commons',
			'thumbUrl' => 'https://upload.wikimedia.org/wikipedia/commons/thumb',
			'hashLevels' => 2,
			'transformVia404' => true,
			'fetchDescription' => true,
			'descriptionCacheExpiry' => 43200,
			'apiThumbCacheExpiry' => 0,
			'directory' => '/install/path/images',
			'backend' => 'wikimediacommons-backend',
		];
		yield '$wgUseInstantCommons' => [
			[ 'wgUseInstantCommons' => true ],
			[ 'wgForeignFileRepos' => [ $instantCommonsExpected ] ] + $expected,
		];
		yield '$wgUseSharedUploads and $wgUseInstantCommons' => [
			[
				'wgUseSharedUploads' => true,
				'wgUseInstantCommons' => true,
			],
			[ 'wgForeignFileRepos' => [
				$sharedUploadsExpected, $instantCommonsExpected ]
			] + $expected,
		];
		yield '$wgUseSharedUploads and $wgSharedUploadDBname and $wgUseInstantCommons' => [
			[
				'wgUseSharedUploads' => true,
				'wgSharedUploadDBname' => 'shared_uploads',
				'wgUseInstantCommons' => true,
			],
			[ 'wgForeignFileRepos' => [
				$sharedUploadsDBnameExpected, $instantCommonsExpected ]
			] + $expected,
		];
		yield 'ForeignAPIRepo with no directory' => [
			[
				'wgForeignFileRepos' => [ [
					'name' => 'foreigner',
					'class' => ForeignAPIRepo::class,
				] ],
				'wgUploadDirectory' => '/upload',
			],
			[ 'wgForeignFileRepos' => [ [
				'name' => 'foreigner',
				'class' => ForeignAPIRepo::class,
				'directory' => '/upload',
				'backend' => 'foreigner-backend',
			] ] ] + array_map(
				self::recursiveReplaceCallback( '/install/path/images', '/upload' ), $expected ),
		];
		yield '$wgDefaultUserOptions days too high' => [
			[
				'wgDefaultUserOptions' => [
					'rcdays' => 100,
					'watchlistdays' => 75,
				],
				'wgRCMaxAge' => 36 * 3600,
			],
			[ 'wgDefaultUserOptions' => [
				'rcdays' => 2.0,
				'watchlistdays' => 2.0,
				'timecorrection' => 'System|0',
			] ],
		];
	}

	/**
	 * Test that if the variables $test are set after DefaultSettings.php is loaded, then
	 * SetupDynamicConfig.php will result in the variables in $expected being set to the given
	 * values. (This does not test that other variables aren't also set.)
	 *
	 * @dataProvider provideGlobals
	 * @coversNothing Only covers code in global scope, no way to annotate that?
	 *
	 * @param array $test Keys are the names of variables, values are their values
	 * @param array $expected Keys are the names of variables, values are their values
	 */
	public function testGlobals( array $test, array $expected ): void {
		$IP = '/install/path';
		require MW_INSTALL_PATH . '/includes/DefaultSettings.php';
		foreach ( $test as $key => $val ) {
			$$key = $val;
		}
		require MW_INSTALL_PATH . '/includes/SetupDynamicConfig.php';

		foreach ( $expected as $key => $val ) {
			$this->assertSame( $val, $$key, "Unexpected value for \$$key" );
		}
	}
}
