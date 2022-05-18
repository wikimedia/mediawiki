<?php

use MediaWiki\MainConfigSchema;
use Wikimedia\AtEase\AtEase;
use Wikimedia\ScopedCallback;

class SetupDynamicConfigTest extends MediaWikiUnitTestCase {
	/** @var string */
	private $originalDefaultTimezone;

	/**
	 * Stop $wgLocaltimezone from clobbering the default, and make sure the timezone is UTC
	 *
	 * @before
	 */
	public function saveTimezoneSetUp(): void {
		$this->originalDefaultTimezone = date_default_timezone_get();
		date_default_timezone_set( 'UTC' );
	}

	/**
	 * @after
	 */
	public function restoreTimezoneTearDown(): void {
		date_default_timezone_set( $this->originalDefaultTimezone );
	}

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

	/**
	 * This method is made necessary by the fact that the dynamic default for DummyLanguageCodes is
	 * based on long lists that are returned from static methods that we can't override.
	 * Copy-pasting those lists into the expected results would be both fragile and hard to read.
	 *
	 * @param array $dummyLanguageCodes Value that DummyLanguageCodes was set to in configuration
	 * @param array $extraLanguageCodes Value that ExtraLanguageCodes was set to in configuration
	 * @return array Expected value after dynamic defaults
	 */
	private static function getExpectedDummyLanguageCodes( array $dummyLanguageCodes,
		array $extraLanguageCodes = MainConfigSchema::ExtraLanguageCodes['default']
	): array {
		$expected = $dummyLanguageCodes + [ 'qqq' => 'qqq', 'qqx' => 'qqx' ] + $extraLanguageCodes
			+ LanguageCode::getDeprecatedCodeMapping();

		// Copy-paste from SetupDynamicConfig
		foreach ( LanguageCode::getNonstandardLanguageCodeMapping() as $code => $bcp47 ) {
			$bcp47 = strtolower( $bcp47 ); // force case-insensitivity
			if ( !isset( $expected[$bcp47] ) ) {
				$expected[$bcp47] = $expected[$code] ?? $code;
			}
		}

		return $expected;
	}

	/**
	 * Returns a callback that sets $_SERVER['HTTPS'] and $_SERVER['HTTP_X_FORWARDED_PROTO'] to
	 * given values, which itself returns a ScopedCallback to reset them to their original values.
	 * (This is probably more cautious than necessary, because tests should be run from the command
	 * line and not have these values set to begin with, but you never know.)
	 *
	 * @param ?string $https New value for $_SERVER['HTTPS']
	 * @param ?string $hxfp New value for $_SERVER['HTTP_X_FORWARDED_PROTO']
	 * @return callable Sets $_SERVER as requested, and returns a ScopedCallback that restores the
	 *   original values.
	 */
	private static function getHttpsResetCallback( ?string $https, ?string $hxfp ): callable {
		return static function () use ( $https, $hxfp ): ScopedCallback {
			$originalServerVals = [];
			foreach ( [ 'HTTPS', 'HTTP_X_FORWARDED_PROTO' ] as $key ) {
				if ( array_key_exists( $key, $_SERVER ) ) {
					$originalServerVals[$key] = $_SERVER[$key];
				}
			}
			$_SERVER['HTTPS'] = $https;
			$_SERVER['HTTP_X_FORWARDED_PROTO'] = $hxfp;

			return new ScopedCallback( static function () use ( $originalServerVals ): void {
				foreach ( $originalServerVals as $key => $val ) {
					$_SERVER[$key] = $val;
				}
			} );
		};
	}

	public static function provideGlobals(): Generator {
		$expectedDefault = [
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
			'wgUseEnotif' => false,
			'wgLocaltimezone' => 'UTC',
			'wgLocalTZoffset' => 0,
			'wgDBerrorLogTZ' => 'UTC',
			'wgCanonicalNamespaceNames' => NamespaceInfo::CANONICAL_NAMES,
			'wgDummyLanguageCodes' => self::getExpectedDummyLanguageCodes( [] ),
			'wgSlaveLagWarning' => 10,
			'wgDatabaseReplicaLagWarning' => 10,
			'wgSlaveLagCritical' => 30,
			'wgDatabaseReplicaLagCritical' => 30,
			// This will be wrong if LocalSettings.php was last touched before May 16, 2003.
			'wgCacheEpoch' => static function (): string {
				// We need a callback that will be evaluated at test time, because otherwise this
				// doesn't work on CI for some reason.
				AtEase::suppressWarnings();
				$ret = max( '20030516000000', gmdate( 'YmdHis', filemtime( MW_CONFIG_FILE ) ) );
				AtEase::restoreWarnings();
				return $ret;
			},
		];

		foreach (
			self::provideGlobalsInternal( $expectedDefault ) as $desc => $arr
		) {
			if ( is_array( $arr[1] ) ) {
				$arr[1] += $expectedDefault;
			}
			yield $desc => $arr;
		}
	}

	private static function provideGlobalsInternal( array $expectedDefault ): Generator {
		yield 'Nothing set' => [ [], [] ];
		yield '$wgScriptPath set' => [
			[ 'wgScriptPath' => '/mywiki' ],
			array_map( self::recursiveReplaceCallback( '/wiki', '/mywiki' ), $expectedDefault ),
		];
		yield '$wgResourceBasePath set' => [
			[ 'wgResourceBasePath' => '/resources' ],
			[
				'wgResourceBasePath' => '/resources',
				'wgExtensionAssetsPath' => '/resources/extensions',
				'wgStylePath' => '/resources/skins',
				'wgLogo' => '/resources/resources/assets/change-your-logo.svg',
			],
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
			],
		];
		yield '$wgCacheDirectory set' => [
			[ 'wgCacheDirectory' => '/cache' ],
			[ 'wgCacheDirectory' => '/cache' ],
		];
		yield '$wgDBprefix set' => [
			[ 'wgDBprefix' => 'prefix_' ],
			[
				'wgDBprefix' => 'prefix_',
				'wgSharedPrefix' => 'prefix_',
				'wgCookiePrefix' => 'my_wiki_prefix_',
			],
		];
		yield '$wgDBmwschema set' => [
			[ 'wgDBmwschema' => 'schema' ],
			[
				'wgDBmwschema' => 'schema',
				'wgSharedSchema' => 'schema',
			],
		];
		yield '$wgSitename set' => [
			[ 'wgSitename' => 'my site' ],
			[
				'wgSitename' => 'my site',
				'wgMetaNamespace' => 'my_site',
			],
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
			],
		];
		yield '$wgMainWANCache set' => [
			[ 'wgMainWANCache' => 'my-cache' ],
			[
				'wgMainWANCache' => 'my-cache',
				// XXX Is this intentional? Customizing MainWANCache without adding it to
				// WANObjectCaches seems like it will break everything?
				'wgWANObjectCaches' => [ [ 'class' => 'WANObjectCache', 'cacheId' => 0 ] ],
			],
		];
		yield '$wgProhibitedFileExtensions set' => [
			[ 'wgProhibitedFileExtensions' => [ 'evil' ] ],
			[
				'wgProhibitedFileExtensions' => [ 'evil' ],
				'wgFileBlacklist' => [ 'evil' ],
			],
		];
		yield '$wgProhibitedFileExtensions and $wgFileBlacklist set' => [
			[
				'wgProhibitedFileExtensions' => [ 'evil' ],
				'wgFileBlacklist' => [ 'eviler' ],
			],
			[
				'wgProhibitedFileExtensions' => [ 'evil', 'eviler' ],
				'wgFileBlacklist' => [ 'eviler' ],
			],
		];
		yield '$wgMimeTypeExclusions set' => [
			[ 'wgMimeTypeExclusions' => [ 'evil' ] ],
			[
				'wgMimeTypeExclusions' => [ 'evil' ],
				'wgMimeTypeBlacklist' => [ 'evil' ],
			],
		];
		yield '$wgMimeTypeExclusions and $wgMimeTypeBlacklist set' => [
			[
				'wgMimeTypeExclusions' => [ 'evil' ],
				'wgMimeTypeBlacklist' => [ 'eviler' ],
			],
			[
				'wgMimeTypeExclusions' => [ 'evil', 'eviler' ],
				'wgMimeTypeBlacklist' => [ 'eviler' ],
			],
		];
		yield '$wgEnableUserEmailMuteList set' => [
			[ 'wgEnableUserEmailMuteList' => true ],
			[
				'wgEnableUserEmailMuteList' => true,
				'wgEnableUserEmailBlacklist' => true,
			],
		];
		yield '$wgEnableUserEmailMuteList and $wgEnableUserEmailBlacklist both true' => [
			[
				'wgEnableUserEmailMuteList' => true,
				'wgEnableUserEmailBlacklist' => true,
			],
			[
				'wgEnableUserEmailMuteList' => true,
				'wgEnableUserEmailBlacklist' => true,
			],
		];
		yield '$wgEnableUserEmailMuteList true and $wgEnableUserEmailBlacklist false' => [
			[
				'wgEnableUserEmailMuteList' => true,
				'wgEnableUserEmailBlacklist' => false,
			],
			[
				'wgEnableUserEmailMuteList' => false,
				'wgEnableUserEmailBlacklist' => false,
			],
		];
		yield '$wgShortPagesNamespaceExclusions set' => [
			[ 'wgShortPagesNamespaceExclusions' => [ NS_TALK ] ],
			[
				'wgShortPagesNamespaceExclusions' => [ NS_TALK ],
				'wgShortPagesNamespaceBlacklist' => [ NS_TALK ],
			],
		];
		yield '$wgShortPagesNamespaceBlacklist set' => [
			[ 'wgShortPagesNamespaceBlacklist' => [ NS_PROJECT ] ],
			[
				'wgShortPagesNamespaceExclusions' => [ NS_PROJECT ],
				'wgShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			],
		];
		yield '$wgShortPagesNamespaceExclusions and $wgShortPagesNamespaceBlacklist set' => [
			[
				'wgShortPagesNamespaceExclusions' => [ NS_TALK ],
				'wgShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			],
			[
				'wgShortPagesNamespaceExclusions' => [ NS_PROJECT ],
				'wgShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			],
		];
		yield '$wgFileExtension contains something from $wgProhibitedFileExtensions' => [
			[
				'wgFileExtensions' => [ 'a', 'b', 'c' ],
				'wgProhibitedFileExtensions' => [ 'b', 'd' ],
			],
			[
				'wgFileExtensions' => [ 'a', 'c' ],
				'wgProhibitedFileExtensions' => [ 'b', 'd' ],
			],
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
			],
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
			],
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
			],
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
			],
		];
		yield '$wgFooterIcons[\'poweredby\'][\'mediawiki\'][\'src\'] === \'\'' => [
			[ 'wgFooterIcons' => [ 'poweredby' => [ 'mediawiki' => [ 'src' => '' ] ] ] ],
			[ 'wgFooterIcons' => [ 'poweredby' => [ 'mediawiki' => [ 'src' => '' ] ] ] ],
		];
		yield '$wgFooterIcons[\'poweredby\']=== []' => [
			[ 'wgFooterIcons' => [ 'poweredby' => [] ] ],
			[ 'wgFooterIcons' => [ 'poweredby' => [] ] ],
		];
		yield '$wgNamespaceProtection set only for non-NS_MEDIAWIKI' => [
			[ 'wgNamespaceProtection' => [ NS_PROJECT => 'editprotected' ] ],
			[ 'wgNamespaceProtection' => [
				NS_PROJECT => 'editprotected',
				NS_MEDIAWIKI => 'editinterface',
			] ],
		];
		yield '$wgNamespaceProtection[NS_MEDIAWIKI] not editinterface' => [
			[ 'wgNamespaceProtection' => [ NS_MEDIAWIKI => 'editprotected' ] ],
			[ 'wgNamespaceProtection' => [ NS_MEDIAWIKI => 'editinterface' ] ],
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
			] ] ],
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
			] ],
		];
		yield 'Set $wgLocalFileRepo' => [
			[ 'wgLocalFileRepo' => [ 'name' => 'asdfgh' ] ],
			[ 'wgLocalFileRepo' => [
				'name' => 'asdfgh', 'backend' => 'asdfgh-backend'
			] ],
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
			[ 'wgForeignFileRepos' => [ $sharedUploadsExpected ] ],
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
			[ 'wgForeignFileRepos' => [ $sharedUploadsDBnameExpected ] ],
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
			[ 'wgForeignFileRepos' => [ $instantCommonsExpected ] ],
		];
		yield '$wgUseSharedUploads and $wgUseInstantCommons' => [
			[
				'wgUseSharedUploads' => true,
				'wgUseInstantCommons' => true,
			],
			[ 'wgForeignFileRepos' => [
				$sharedUploadsExpected, $instantCommonsExpected ]
			],
		];
		yield '$wgUseSharedUploads and $wgSharedUploadDBname and $wgUseInstantCommons' => [
			[
				'wgUseSharedUploads' => true,
				'wgSharedUploadDBname' => 'shared_uploads',
				'wgUseInstantCommons' => true,
			],
			[ 'wgForeignFileRepos' => [
				$sharedUploadsDBnameExpected, $instantCommonsExpected ]
			],
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
				self::recursiveReplaceCallback( '/install/path/images', '/upload' ),
					$expectedDefault ),
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
		yield '$wgSharedDB set' => [
			[ 'wgSharedDB' => 'shared_db' ],
			[ 'wgCookiePrefix' => 'shared_db' ],
		];
		yield '$wgSharedDB and $wgSharedPrefix set' => [
			[ 'wgSharedDB' => 'shared_db', 'wgSharedPrefix' => 'shared_prefix' ],
			[
				'wgCookiePrefix' => 'shared_db_shared_prefix',
				'wgSharedPrefix' => 'shared_prefix',
			],
		];
		yield '$wgSharedPrefix set with no $wgSharedDB' => [
			[ 'wgSharedPrefix' => 'shared_prefix' ],
			[ 'wgSharedPrefix' => 'shared_prefix' ],
		];
		yield '$wgSharedDB set but user not in $wgSharedTables' => [
			[ 'wgSharedDB' => 'shared_db', 'wgSharedTables' => [ 'user_properties' ] ],
			[],
		];
		yield '$wgSharedDB and $wgSharedPrefix set but user not in $wgSharedTables' => [
			[
				'wgSharedDB' => 'shared_db',
				'wgSharedPrefix' => 'shared_prefix',
				'wgSharedTables' => [ 'user_properties' ],
			],
			[ 'wgSharedPrefix' => 'shared_prefix' ],
		];
		yield '$wgCookiePrefix with allowed punctuation' => [
			[ 'wgCookiePrefix' => "~!@#$%^&*()_-]{}|:<>/?\t\r\n\f" ],
			[ 'wgCookiePrefix' => "~!@#$%^&*()_-]{}|:<>/?\t\r\n\f" ],
		];
		yield '$wgCookiePrefix with bad characters' => [
			[ 'wgCookiePrefix' => 'n=o,t;a l+l.o"w\'e\\d[' ],
			[ 'wgCookiePrefix' => 'n_o_t_a_l_l_o_w_e_d_' ],
		];
		yield '$wgEnotifUserTalk true' => [
			[ 'wgEnotifUserTalk' => true ],
			[ 'wgUseEnotif' => true ],
		];
		yield '$wgEnableEmail set to false' => [
			[
				'wgEnableEmail' => false,
				'wgAllowHTMLEmail' => true,
				'wgEmailAuthentication' => true,
				'wgEnableUserEmail' => true,
				'wgEnotifFromEditor' => true,
				'wgEnotifImpersonal' => true,
				'wgEnotifMaxRecips' => 0,
				'wgEnotifMinorEdits' => true,
				'wgEnotifRevealEditorAddress' => true,
				'wgEnotifUseRealName' => true,
				'wgEnotifUserTalk' => true,
				'wgEnotifWatchlist' => true,
				'wgGroupPermissions' => [ 'user' => [ 'sendemail' => true ] ],
				'wgUseEnotif' => true,
				'wgUserEmailUseReplyTo' => true,
				'wgUsersNotifiedOnAllChanges' => [ 'Admin' ],
			], [
				'wgAllowHTMLEmail' => false,
				'wgEmailAuthentication' => false,
				'wgEnableUserEmail' => false,
				'wgEnotifFromEditor' => false,
				'wgEnotifImpersonal' => false,
				'wgEnotifMaxRecips' => 0,
				'wgEnotifMinorEdits' => false,
				'wgEnotifRevealEditorAddress' => false,
				'wgEnotifUseRealName' => false,
				'wgEnotifUserTalk' => false,
				'wgEnotifWatchlist' => false,
				'wgGroupPermissions' => [ 'user' => [] ],
				'wgUseEnotif' => false,
				'wgUserEmailUseReplyTo' => false,
				'wgUsersNotifiedOnAllChanges' => [],
			],
		];
		yield 'Change PHP default timezone' => [
			[ 'wgDefaultUserOptions' => [
				'rcdays' => 0,
				'watchlistdays' => 0,
			] ],
			[
				'wgLocaltimezone' => 'America/Phoenix',
				'wgLocalTZoffset' => -7 * 60,
				'wgDefaultUserOptions' => [
					'rcdays' => 0,
					'watchlistdays' => 0,
					'timecorrection' => 'System|' . (string)( -7 * 60 ),
				],
				'wgDBerrorLogTZ' => 'America/Phoenix',
			],
			static function ( self $testObj ): void {
				// Pick a timezone with no DST
				$testObj->assertTrue( date_default_timezone_set( 'America/Phoenix' ) );
			},
		];
		yield '$wgDBerrorLogTZ set' => [
			[ 'wgDBerrorLogTZ' => 'America/Phoenix' ],
			[ 'wgDBerrorLogTZ' => 'America/Phoenix' ],
		];
		yield 'Setting $wgCanonicalNamespaceNames does nothing' => [
			[ 'wgCanonicalNamespaceNames' => [ NS_MAIN => 'abc' ] ],
			[],
		];
		yield '$wgExtraNamespaces set' => [
			[ 'wgExtraNamespaces' => [ 100 => 'Extra' ] ],
			[ 'wgCanonicalNamespaceNames' => NamespaceInfo::CANONICAL_NAMES + [ 100 => 'Extra' ] ],
		];
		yield '$wgDummyLanguageCodes set' => [
			[ 'wgDummyLanguageCodes' => [ 'qqq' => 'qqqq', 'foo' => 'bar', 'bh' => 'hb' ] ],
			[ 'wgDummyLanguageCodes' => self::getExpectedDummyLanguageCodes(
				[ 'qqq' => 'qqqq', 'foo' => 'bar', 'bh' => 'hb' ] ) ],
			static function ( self $testObj ): void {
				$testObj->expectDeprecationAndContinue(
					'/Use of \$wgDummyLanguageCodes was deprecated in MediaWiki 1\.29\./' );
			},
		];
		yield '$wgExtraLanguageCodes set' => [
			[ 'wgExtraLanguageCodes' => [ 'foo' => 'bar' ] ],
			[ 'wgDummyLanguageCodes' => self::getExpectedDummyLanguageCodes( [],
				[ 'foo' => 'bar' ] ) ],
		];
		yield '$wgDummyLanguageCodes and $wgExtraLanguageCodes set' => [
			[
				'wgDummyLanguageCodes' => [ 'foo' => 'bar', 'abc' => 'def' ],
				'wgExtraLanguageCodes' => [ 'foo' => 'baz', 'ghi' => 'jkl' ],
			],
			[ 'wgDummyLanguageCodes' => self::getExpectedDummyLanguageCodes(
				[ 'foo' => 'bar', 'abc' => 'def' ], [ 'foo' => 'baz', 'ghi' => 'jkl' ] ) ],
			static function ( self $testObj ): void {
				$testObj->expectDeprecationAndContinue(
					'/Use of \$wgDummyLanguageCodes was deprecated in MediaWiki 1\.29\./' );
			},
		];
		yield '$wgDatabaseReplicaLagWarning set' => [
			[ 'wgDatabaseReplicaLagWarning' => 20 ],
			[ 'wgDatabaseReplicaLagWarning' => 20, 'wgSlaveLagWarning' => 20 ],
		];
		yield '$wgSlaveLagWarning set' => [
			[ 'wgSlaveLagWarning' => 20 ],
			[ 'wgDatabaseReplicaLagWarning' => 20, 'wgSlaveLagWarning' => 20 ],
			static function ( self $testObj ): void {
				$testObj->expectDeprecationAndContinue(
					'/Use of \$wgSlaveLagWarning set but \$wgDatabaseReplicaLagWarning unchanged;' .
					' using \$wgSlaveLagWarning was deprecated in MediaWiki 1\.36\./' );
			},
		];
		// XXX The settings are out of sync, this doesn't look intended
		yield '$wgDatabaseReplicaLagWarning and $wgSlaveLagWarning set' => [
			[ 'wgDatabaseReplicaLagWarning' => 20, 'wgSlaveLagWarning' => 30 ],
			[ 'wgDatabaseReplicaLagWarning' => 20, 'wgSlaveLagWarning' => 30 ],
		];
		yield '$wgDatabaseReplicaLagCritical set' => [
			[ 'wgDatabaseReplicaLagCritical' => 40 ],
			[ 'wgDatabaseReplicaLagCritical' => 40, 'wgSlaveLagCritical' => 40 ],
		];
		yield '$wgSlaveLagCritical set' => [
			[ 'wgSlaveLagCritical' => 40 ],
			[ 'wgDatabaseReplicaLagCritical' => 40, 'wgSlaveLagCritical' => 40 ],
			static function ( self $testObj ): void {
				$testObj->expectDeprecationAndContinue(
					'/Use of \$wgSlaveLagCritical set but \$wgDatabaseReplicaLagCritical unchanged;' .
					' using \$wgSlaveLagCritical was deprecated in MediaWiki 1\.36\./' );
			},
		];
		// XXX The settings are out of sync, this doesn't look intended
		yield '$wgDatabaseReplicaLagCritical and $wgSlaveLagCritical set' => [
			[ 'wgDatabaseReplicaLagCritical' => 40, 'wgSlaveLagCritical' => 60 ],
			[ 'wgDatabaseReplicaLagCritical' => 40, 'wgSlaveLagCritical' => 60 ],
		];
		yield '$wgCacheEpoch set to before LocalSettings touched' => [
			[ 'wgCacheEpoch' => static function () use ( $expectedDefault ): string {
				return $expectedDefault['wgCacheEpoch']() - 1;
			} ],
			[ 'wgCacheEpoch' => static function () use ( $expectedDefault ): string {
				$expected = $expectedDefault['wgCacheEpoch']();
				// If the file exists, its mtime is later than what we set $wgCacheEpoch to and so
				// it should override what we set.
				return file_exists( MW_CONFIG_FILE ) ? $expected : $expected - 1;
			} ],
		];
		yield '$wgCacheEpoch set to after LocalSettings touched' => [
			[ 'wgCacheEpoch' => static function () use ( $expectedDefault ): string {
				return $expectedDefault['wgCacheEpoch']() + 1;
			} ],
			[ 'wgCacheEpoch' => static function () use ( $expectedDefault ): string {
				return $expectedDefault['wgCacheEpoch']() + 1;
			} ],
		];
		yield '$wgInvalidateCacheOnLocalSettingsChange false' => [
			[ 'wgInvalidateCacheOnLocalSettingsChange' => false ],
			[ 'wgCacheEpoch' => '20030516000000' ],
		];
		yield '$wgNewUserLog is true' => [
			[ 'wgNewUserLog' => true ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertContains( 'newusers', $vars['wgLogTypes'] );
				$testObj->assertSame( 'newuserlogpage', $vars['wgLogNames']['newusers'] );
				$testObj->assertSame( 'newuserlogpagetext', $vars['wgLogHeaders']['newusers'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars['wgLogActionsHandlers']['newusers/newusers'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars['wgLogActionsHandlers']['newusers/create'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars['wgLogActionsHandlers']['newusers/create2'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars['wgLogActionsHandlers']['newusers/byemail'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars['wgLogActionsHandlers']['newusers/autocreate'] );

				return $expectedDefault;
			},
		];
		yield '$wgNewUserLog is false`' => [
			[ 'wgNewUserLog' => false ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				// Test that the new user log is not added to any of the various logging globals
				$testObj->assertNotContains( 'newusers', $vars['wgLogTypes'] );
				$testObj->assertArrayNotHasKey( 'newusers', $vars['wgLogNames'] );
				$testObj->assertArrayNotHasKey( 'newusers', $vars['wgLogHeaders'] );
				foreach ( $vars['wgLogActionsHandlers'] as $key => $unused ) {
					$testObj->assertStringStartsNotWith( 'newusers', $key );
				}

				return $expectedDefault;
			},
		];
		yield '$wgPageCreationLog is true' => [
			[ 'wgPageCreationLog' => true ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertContains( 'create', $vars['wgLogTypes'] );
				$testObj->assertSame( LogFormatter::class,
					$vars['wgLogActionsHandlers']['create/create'] );

				return $expectedDefault;
			},
		];
		yield '$wgPageCreationLog is false`' => [
			[ 'wgPageCreationLog' => false ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertNotContains( 'create', $vars['wgLogTypes'] );
				$testObj->assertArrayNotHasKey( 'create/create', $vars['wgLogActionsHandlers'] );

				return $expectedDefault;
			},
		];
		yield '$wgPageLanguageUseDB is true' => [
			[ 'wgPageLanguageUseDB' => true ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertContains( 'pagelang', $vars['wgLogTypes'] );
				$testObj->assertSame( PageLangLogFormatter::class,
					$vars['wgLogActionsHandlers']['pagelang/pagelang'] );

				return $expectedDefault;
			},
		];
		yield '$wgPageLanguageUseDB is false' => [
			[ 'wgPageLanguageUseDB' => false ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertNotContains( 'pagelang', $vars['wgLogTypes'] );
				$testObj->assertArrayNotHasKey( 'pagelang/pagelang',
					$vars['wgLogActionsHandlers'] );

				return $expectedDefault;
			},
		];
		yield '$wgForceHTTPS is true, not HTTPS' => [
			[ 'wgForceHTTPS' => true ],
			[ 'wgCookieSecure' => true ],
			self::getHttpsResetCallback( null, null ),
		];
		yield '$wgForceHTTPS is true, HTTPS' => [
			[ 'wgForceHTTPS' => true ],
			[ 'wgCookieSecure' => true ],
			self::getHttpsResetCallback( 'on', 'https' ),
		];
		yield '$wgForceHTTPS is false, not HTTPS' => [
			[ 'wgForceHTTPS' => false ],
			[ 'wgCookieSecure' => false ],
			self::getHttpsResetCallback( null, null ),
		];
		yield '$wgForceHTTPS is false, HTTPS off' => [
			[ 'wgForceHTTPS' => false ],
			[ 'wgCookieSecure' => false ],
			self::getHttpsResetCallback( 'off', null ),
		];
		yield '$wgForceHTTPS is false, HTTPS' => [
			[ 'wgForceHTTPS' => false ],
			[ 'wgCookieSecure' => true ],
			self::getHttpsResetCallback( 'on', null ),
		];
		yield '$wgForceHTTPS is false, forwarded HTTPS' => [
			[ 'wgForceHTTPS' => false ],
			[ 'wgCookieSecure' => true ],
			self::getHttpsResetCallback( null, 'https' ),
		];
		yield '$wgMinimalPasswordLength' => [
			[ 'wgMinimalPasswordLength' => 17 ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertSame( 17,
					$vars['wgPasswordPolicy']['policies']['default']['MinimalPasswordLength'] );
				return $expectedDefault;
			},
		];
		yield '$wgMaximalPasswordLength' => [
			[ 'wgMaximalPasswordLength' => 17 ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertSame( 17,
					$vars['wgPasswordPolicy']['policies']['default']['MaximalPasswordLength'] );
				return $expectedDefault;
			},
		];
		yield 'Bogus $wgPHPSessionHandling' => [
			[ 'wgPHPSessionHandling' => 'bogus' ],
			[ 'wgPHPSessionHandling' => 'warn' ],
		];
		yield 'Enable $wgPHPSessionHandling' => [
			[ 'wgPHPSessionHandling' => 'enable' ],
			[ 'wgPHPSessionHandling' => 'enable' ],
		];
		yield 'Disable $wgPHPSessionHandling' => [
			[ 'wgPHPSessionHandling' => 'disable' ],
			[ 'wgPHPSessionHandling' => 'disable' ],
		];
		// XXX No obvious way to test MW_NO_SESSION, because constants can't be undefined
	}

	/**
	 * Test that if the variables $test are set after DefaultSettings.php is loaded, then
	 * SetupDynamicConfig.php will result in the variables in $expected being set to the given
	 * values. (This does not test that other variables aren't also set.)
	 *
	 * @dataProvider provideGlobals
	 * @coversNothing Only covers code in global scope, no way to annotate that?
	 *
	 * @param array $test Keys are the names of variables, values are their values. If a value is
	 *   callable, it will be called with no arguments to obtain the value.
	 * @param array|callable $expected Keys are the names of variables, values are their values. If
	 *   a value is callable, it will be called with no arguments to obtain the value. If $expected
	 *   is callable, then it will be called with $this and get_defined_vars() as arguments, and
	 *   the returned array will be used for $expected.
	 * @param ?callable $setup Run before SetupDynamicConfig is included. $this is passed as an
	 *   argument, and a ScopedCallback may optionally be returned.
	 */
	public function testGlobals( array $test, $expected, ?callable $setup = null ): void {
		foreach ( MainConfigSchema::listDefaultValues( 'wg' ) as $key => $val ) {
			$$key = $val;
		}
		foreach ( $test as $key => $val ) {
			// $wgCacheEpoch default doesn't work properly on CI if evaluated in the provider
			$$key = is_callable( $val ) ? $val() : $val;
		}
		if ( $setup ) {
			$scopedCallback = $setup( $this );
		}

		$IP = '/install/path';
		require MW_INSTALL_PATH . '/includes/SetupDynamicConfig.php';

		if ( is_callable( $expected ) ) {
			$expected = $expected( $this, get_defined_vars() );
		}

		foreach ( $expected as $key => $val ) {
			$this->assertSame( is_callable( $val ) ? $val() : $val,
				$$key, "Unexpected value for \$$key" );
		}
	}

	/**
	 * @coversNothing Only covers code in global scope, no way to annotate that?
	 */
	public function testSetLocaltimezone(): void {
		$tz = 'America/Los_Angeles';
		$this->testGlobals( [ 'wgLocaltimezone' => $tz ], [] );
		$this->assertSame( $tz, date_default_timezone_get() );
	}
}
