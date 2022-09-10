<?php

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\ConfigSchemaAggregator;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\DynamicDefaultValues;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\Source\ReflectionSchemaSource;

class SetupDynamicConfigTest extends MediaWikiUnitTestCase {
	/** @var string */
	private $originalDefaultTimezone;

	/** @var string[] */
	private $originalServerVars;

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
	 * @before
	 */
	public function serverVarsSetUp(): void {
		$this->originalServerVars = $_SERVER;
	}

	/**
	 * @after
	 */
	public function serverVarsTearDown(): void {
		$_SERVER = $this->originalServerVars;
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

	public static function provideGlobals(): Generator {
		$expectedDefault = [
			'ScriptPath' => '/wiki',
			'Script' => '/wiki/index.php',
			'LoadScript' => '/wiki/load.php',
			'RestPath' => '/wiki/rest.php',
			// XXX $wgUsePathInfo set based on PHP_SAPI, no good way to test?
			// XXX Testing $wgArticlePath doesn't seem to work
			'ResourceBasePath' => '/wiki',
			'StylePath' => '/wiki/skins',
			'LocalStylePath' => '/wiki/skins',
			'ExtensionAssetsPath' => '/wiki/extensions',
			'Logo' => '/wiki/resources/assets/change-your-logo.svg',
			'UploadPath' => '/wiki/images',
			'UploadDirectory' => '/install/path/images',
			'ReadOnlyFile' => '/install/path/images/lock_yBgMBwiR',
			'FileCacheDirectory' => '/install/path/images/cache',
			'DeletedDirectory' => '/install/path/images/deleted',
			'SharedPrefix' => '',
			'SharedSchema' => null,
			'MetaNamespace' => 'MediaWiki',
			'MainWANCache' => 'mediawiki-main-default',
			'WANObjectCaches' => [
				// XXX Is this duplication really intentional? Isn't the first entry unused?
				0 => [
					'class' => WANObjectCache::class,
					'cacheId' => 0,
				],
				'mediawiki-main-default' => [
					'class' => WANObjectCache::class,
					'cacheId' => 0,
				],
			],
			'EnableUserEmailMuteList' => false,
			'EnableUserEmailBlacklist' => false,
			'NamespaceProtection' => [ NS_MEDIAWIKI => 'editinterface' ],
			'LockManagers' => [ [
				'name' => 'fsLockManager',
				'class' => FSLockManager::class,
				'lockDirectory' => '/install/path/images/lockdir',
			], [
				'name' => 'nullLockManager',
				'class' => NullLockManager::class,
			] ],
			// Can't really test this properly without being able to mock global functions
			'ShowEXIF' => function_exists( 'exif_read_data' ),
			'GalleryOptions' => [
				'imagesPerRow' => 0,
				'imageWidth' => 120,
				'imageHeight' => 120,
				'captionLength' => true,
				'showBytes' => true,
				'showDimensions' => true,
				'mode' => 'traditional',
			],
			'LocalFileRepo' => [
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
			'CookiePrefix' => 'my_wiki',
			'Localtimezone' => 'UTC',
			'LocalTZoffset' => 0,
			'DBerrorLogTZ' => 'UTC',
			'CanonicalNamespaceNames' => NamespaceInfo::CANONICAL_NAMES,
			'DummyLanguageCodes' => self::getExpectedDummyLanguageCodes( [] ),
			'SlaveLagWarning' => 10,
			'DatabaseReplicaLagWarning' => 10,
			'SlaveLagCritical' => 30,
			'DatabaseReplicaLagCritical' => 30,
			// This will be wrong if LocalSettings.php was last touched before May 16, 2003.
			'CacheEpoch' => static function (): string {
				// We need a callback that will be evaluated at test time, because otherwise this
				// doesn't work on CI for some reason.
				$ret = max( '20030516000000', gmdate( 'YmdHis', @filemtime( MW_CONFIG_FILE ) ) );
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
			[ 'ScriptPath' => '/mywiki' ],
			array_map( self::recursiveReplaceCallback( '/wiki', '/mywiki' ), $expectedDefault ),
		];
		yield '$wgResourceBasePath set' => [
			[ 'ResourceBasePath' => '/resources' ],
			[
				'ResourceBasePath' => '/resources',
				'ExtensionAssetsPath' => '/resources/extensions',
				'StylePath' => '/resources/skins',
				'Logo' => '/resources/resources/assets/change-your-logo.svg',
			],
		];
		yield '$wgUploadDirectory set' => [
			[ 'UploadDirectory' => '/uploads' ],
			[
				'UploadDirectory' => '/uploads',
				'ReadOnlyFile' => '/uploads/lock_yBgMBwiR',
				'FileCacheDirectory' => '/uploads/cache',
				'DeletedDirectory' => '/uploads/deleted',
				'LockManagers' => [ [
					'name' => 'fsLockManager',
					'class' => FSLockManager::class,
					'lockDirectory' => '/uploads/lockdir',
				], [
					'name' => 'nullLockManager',
					'class' => NullLockManager::class,
				] ],
				'LocalFileRepo' => [
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
			[ 'CacheDirectory' => '/cache' ],
			[ 'CacheDirectory' => '/cache' ],
		];
		yield '$wgDBprefix set' => [
			[ 'DBprefix' => 'prefix_' ],
			[
				'DBprefix' => 'prefix_',
				'SharedPrefix' => 'prefix_',
				'CookiePrefix' => 'my_wiki_prefix_',
			],
		];
		yield '$wgDBmwschema set' => [
			[ 'DBmwschema' => 'schema' ],
			[
				'DBmwschema' => 'schema',
				'SharedSchema' => 'schema',
			],
		];
		yield '$wgSitename set' => [
			[ 'Sitename' => 'my site' ],
			[
				'Sitename' => 'my site',
				'MetaNamespace' => 'my_site',
			],
		];
		yield '$wgMainCacheType set' => [
			[ 'MainCacheType' => 7 ],
			[
				'MainCacheType' => 7,
				'WANObjectCaches' => [
					0 => [
						'class' => WANObjectCache::class,
						'cacheId' => 0,
					],
					'mediawiki-main-default' => [
						'class' => WANObjectCache::class,
						'cacheId' => 7,
					],
				],
			],
		];
		yield '$wgMainWANCache set' => [
			[ 'MainWANCache' => 'my-cache' ],
			[
				'MainWANCache' => 'my-cache',
				// XXX Is this intentional? Customizing MainWANCache without adding it to
				// WANObjectCaches seems like it will break everything?
				'WANObjectCaches' => [ [ 'class' => WANObjectCache::class, 'cacheId' => 0 ] ],
			],
		];
		yield '$wgProhibitedFileExtensions set' => [
			[ 'ProhibitedFileExtensions' => [ 'evil' ] ],
			[
				'ProhibitedFileExtensions' => [ 'evil' ],
				'FileBlacklist' => [ 'evil' ],
			],
		];
		yield '$wgProhibitedFileExtensions and $wgFileBlacklist set' => [
			[
				'ProhibitedFileExtensions' => [ 'evil' ],
				'FileBlacklist' => [ 'eviler' ],
			],
			[
				'ProhibitedFileExtensions' => [ 'evil', 'eviler' ],
				'FileBlacklist' => [ 'eviler' ],
			],
		];
		yield '$wgMimeTypeExclusions set' => [
			[ 'MimeTypeExclusions' => [ 'evil' ] ],
			[
				'MimeTypeExclusions' => [ 'evil' ],
				'MimeTypeBlacklist' => [ 'evil' ],
			],
		];
		yield '$wgMimeTypeExclusions and $wgMimeTypeBlacklist set' => [
			[
				'MimeTypeExclusions' => [ 'evil' ],
				'MimeTypeBlacklist' => [ 'eviler' ],
			],
			[
				'MimeTypeExclusions' => [ 'evil', 'eviler' ],
				'MimeTypeBlacklist' => [ 'eviler' ],
			],
		];
		yield '$wgEnableUserEmailMuteList set' => [
			[ 'EnableUserEmailMuteList' => true ],
			[
				'EnableUserEmailMuteList' => true,
				'EnableUserEmailBlacklist' => true,
			],
		];
		yield '$wgEnableUserEmailMuteList and $wgEnableUserEmailBlacklist both true' => [
			[
				'EnableUserEmailMuteList' => true,
				'EnableUserEmailBlacklist' => true,
			],
			[
				'EnableUserEmailMuteList' => true,
				'EnableUserEmailBlacklist' => true,
			],
		];
		yield '$wgEnableUserEmailMuteList true and $wgEnableUserEmailBlacklist false' => [
			[
				'EnableUserEmailMuteList' => true,
				'EnableUserEmailBlacklist' => false,
			],
			[
				'EnableUserEmailMuteList' => false,
				'EnableUserEmailBlacklist' => false,
			],
		];
		yield '$wgShortPagesNamespaceExclusions set' => [
			[ 'ShortPagesNamespaceExclusions' => [ NS_TALK ] ],
			[
				'ShortPagesNamespaceExclusions' => [ NS_TALK ],
				'ShortPagesNamespaceBlacklist' => [ NS_TALK ],
			],
		];
		yield '$wgShortPagesNamespaceBlacklist set' => [
			[ 'ShortPagesNamespaceBlacklist' => [ NS_PROJECT ] ],
			[
				'ShortPagesNamespaceExclusions' => [ NS_PROJECT ],
				'ShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			],
		];
		yield '$wgShortPagesNamespaceExclusions and $wgShortPagesNamespaceBlacklist set' => [
			[
				'ShortPagesNamespaceExclusions' => [ NS_TALK ],
				'ShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			],
			[
				'ShortPagesNamespaceExclusions' => [ NS_PROJECT ],
				'ShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			],
		];
		yield '$wgFileExtension contains something from $wgProhibitedFileExtensions' => [
			[
				'FileExtensions' => [ 'a', 'b', 'c' ],
				'ProhibitedFileExtensions' => [ 'b', 'd' ],
			],
			[
				'FileExtensions' => [ 'a', 'c' ],
				'ProhibitedFileExtensions' => [ 'b', 'd' ],
			],
		];
		yield '$wgRightsIcon old path convention' => [
			[
				'StylePath' => '/style',
				'ResourceBasePath' => '/resources',
				'RightsIcon' => '/style/common/images/rights.png',
			],
			[
				'StylePath' => '/style',
				'ResourceBasePath' => '/resources',
				'ExtensionAssetsPath' => '/resources/extensions',
				'Logo' => '/resources/resources/assets/change-your-logo.svg',
				'RightsIcon' => '/resources/resources/assets/licenses/rights.png',
			],
		];
		yield 'Empty $wgFooterIcons[\'copyright\'][\'copyright\'], $wgRightsIcon set' => [
			[
				'RightsIcon' => 'ico',
				'FooterIcons' => [ 'copyright' => [ 'copyright' => [] ] ],
			],
			[
				'FooterIcons' => [ 'copyright' => [ 'copyright' => [
					'url' => null,
					'src' => 'ico',
					'alt' => null,
				] ] ],
			],
		];
		yield 'Empty $wgFooterIcons[\'copyright\'][\'copyright\'], $wgRightsText set' => [
			[
				'RightsText' => 'text',
				'FooterIcons' => [ 'copyright' => [ 'copyright' => [] ] ],
			],
			[
				'FooterIcons' => [ 'copyright' => [ 'copyright' => [
					'url' => null,
					'src' => null,
					'alt' => 'text',
				] ] ],
			],
		];
		yield '$wgFooterIcons[\'poweredby\'][\'mediawiki\'][\'src\'] === null' => [
			[
				'ResourceBasePath' => '/resources',
				'FooterIcons' => [ 'poweredby' => [ 'mediawiki' => [ 'src' => null ] ] ],
			],
			[
				'ResourceBasePath' => '/resources',
				'ExtensionAssetsPath' => '/resources/extensions',
				'StylePath' => '/resources/skins',
				'Logo' => '/resources/resources/assets/change-your-logo.svg',
				'FooterIcons' => [ 'poweredby' => [ 'mediawiki' => [
					'src' => '/resources/resources/assets/poweredby_mediawiki_88x31.png',
					'srcset' => '/resources/resources/assets/poweredby_mediawiki_132x47.png 1.5x,' .
						' /resources/resources/assets/poweredby_mediawiki_176x62.png 2x',
				] ] ],
			],
		];
		yield '$wgFooterIcons[\'poweredby\'][\'mediawiki\'][\'src\'] === \'\'' => [
			[ 'FooterIcons' => [ 'poweredby' => [ 'mediawiki' => [ 'src' => '' ] ] ] ],
			[ 'FooterIcons' => [ 'poweredby' => [ 'mediawiki' => [ 'src' => '' ] ] ] ],
		];
		yield '$wgFooterIcons[\'poweredby\']=== []' => [
			[ 'FooterIcons' => [ 'poweredby' => [] ] ],
			[ 'FooterIcons' => [ 'poweredby' => [] ] ],
		];
		yield '$wgNamespaceProtection set only for non-NS_MEDIAWIKI' => [
			[ 'NamespaceProtection' => [ NS_PROJECT => 'editprotected' ] ],
			[ 'NamespaceProtection' => [
				NS_PROJECT => 'editprotected',
				NS_MEDIAWIKI => 'editinterface',
			] ],
		];
		yield '$wgNamespaceProtection[NS_MEDIAWIKI] not editinterface' => [
			[ 'NamespaceProtection' => [ NS_MEDIAWIKI => 'editprotected' ] ],
			[ 'NamespaceProtection' => [ NS_MEDIAWIKI => 'editinterface' ] ],
		];
		yield 'Custom lock manager' => [
			[ 'LockManagers' => [ [ 'foo' ] ] ],
			[ 'LockManagers' => [ [ 'foo' ], [
				'name' => 'fsLockManager',
				'class' => FSLockManager::class,
				'lockDirectory' => '/install/path/images/lockdir',
			], [
				'name' => 'nullLockManager',
				'class' => NullLockManager::class,
			] ] ],
		];
		yield 'Customize some $wgGalleryOptions' => [
			[ 'GalleryOptions' => [
				'imagesPerRow' => 42,
				'imageHeight' => -1,
				'showBytes' => null,
				'mode' => 'iconoclastic',
				'custom' => 'Rembrandt',
			] ],
			[ 'GalleryOptions' => [
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
			[ 'LocalFileRepo' => [ 'name' => 'asdfgh' ] ],
			[ 'LocalFileRepo' => [
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
			[ 'UseSharedUploads' => true ],
			[ 'ForeignFileRepos' => [ $sharedUploadsExpected ] ],
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
				'UseSharedUploads' => true,
				'SharedUploadDBname' => 'shared_uploads',
			],
			[ 'ForeignFileRepos' => [ $sharedUploadsDBnameExpected ] ],
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
			[ 'UseInstantCommons' => true ],
			[ 'ForeignFileRepos' => [ $instantCommonsExpected ] ],
		];
		yield '$wgUseSharedUploads and $wgUseInstantCommons' => [
			[
				'UseSharedUploads' => true,
				'UseInstantCommons' => true,
			],
			[ 'ForeignFileRepos' => [
				$sharedUploadsExpected, $instantCommonsExpected ]
			],
		];
		yield '$wgUseSharedUploads and $wgSharedUploadDBname and $wgUseInstantCommons' => [
			[
				'UseSharedUploads' => true,
				'SharedUploadDBname' => 'shared_uploads',
				'UseInstantCommons' => true,
			],
			[ 'ForeignFileRepos' => [
				$sharedUploadsDBnameExpected, $instantCommonsExpected ]
			],
		];
		yield 'ForeignAPIRepo with no directory' => [
			[
				'ForeignFileRepos' => [ [
					'name' => 'foreigner',
					'class' => ForeignAPIRepo::class,
				] ],
				'UploadDirectory' => '/upload',
			],
			[ 'ForeignFileRepos' => [ [
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
				'DefaultUserOptions' => [
					'rcdays' => 100,
					'watchlistdays' => 75,
				],
				'RCMaxAge' => 36 * 3600,
			],
			[ 'DefaultUserOptions' => [
				'rcdays' => 2.0,
				'watchlistdays' => 2.0,
				'timecorrection' => 'System|0',
			] ],
		];
		yield '$wgSharedDB set' => [
			[ 'SharedDB' => 'shared_db' ],
			[ 'CookiePrefix' => 'shared_db' ],
		];
		yield '$wgSharedDB and $wgSharedPrefix set' => [
			[ 'SharedDB' => 'shared_db', 'SharedPrefix' => 'shared_prefix' ],
			[
				'CookiePrefix' => 'shared_db_shared_prefix',
				'SharedPrefix' => 'shared_prefix',
			],
		];
		yield '$wgSharedPrefix set with no $wgSharedDB' => [
			[ 'SharedPrefix' => 'shared_prefix' ],
			[ 'SharedPrefix' => 'shared_prefix' ],
		];
		yield '$wgSharedDB set but user not in $wgSharedTables' => [
			[ 'SharedDB' => 'shared_db', 'SharedTables' => [ 'user_properties' ] ],
			[],
		];
		yield '$wgSharedDB and $wgSharedPrefix set but user not in $wgSharedTables' => [
			[
				'SharedDB' => 'shared_db',
				'SharedPrefix' => 'shared_prefix',
				'SharedTables' => [ 'user_properties' ],
			],
			[ 'SharedPrefix' => 'shared_prefix' ],
		];
		yield '$wgCookiePrefix with allowed punctuation' => [
			[ 'CookiePrefix' => "~!@#$%^&*()_-]{}|:<>/?\t\r\n\f" ],
			[ 'CookiePrefix' => "~!@#$%^&*()_-]{}|:<>/?\t\r\n\f" ],
		];
		yield '$wgCookiePrefix with bad characters' => [
			[ 'CookiePrefix' => 'n=o,t;a l+l.o"w\'e\\d[' ],
			[ 'CookiePrefix' => 'n_o_t_a_l_l_o_w_e_d_' ],
		];
		yield '$wgEnableEmail set to false' => [
			[
				'EnableEmail' => false,
				'AllowHTMLEmail' => true,
				'EmailAuthentication' => true,
				'EnableUserEmail' => true,
				'EnotifFromEditor' => true,
				'EnotifImpersonal' => true,
				'EnotifMaxRecips' => 0,
				'EnotifMinorEdits' => true,
				'EnotifRevealEditorAddress' => true,
				'EnotifUseRealName' => true,
				'EnotifUserTalk' => true,
				'EnotifWatchlist' => true,
				'GroupPermissions' => [ 'user' => [ 'sendemail' => true ] ],
				'UserEmailUseReplyTo' => true,
				'UsersNotifiedOnAllChanges' => [ 'Admin' ],
			], [
				'AllowHTMLEmail' => false,
				'EmailAuthentication' => false,
				'EnableUserEmail' => false,
				'EnotifFromEditor' => false,
				'EnotifImpersonal' => false,
				'EnotifMaxRecips' => 0,
				'EnotifMinorEdits' => false,
				'EnotifRevealEditorAddress' => false,
				'EnotifUseRealName' => false,
				'EnotifUserTalk' => false,
				'EnotifWatchlist' => false,
				'GroupPermissions' => [ 'user' => [] ],
				'UserEmailUseReplyTo' => false,
				'UsersNotifiedOnAllChanges' => [],
			],
		];
		yield 'Change PHP default timezone' => [
			[ 'DefaultUserOptions' => [
				'rcdays' => 0,
				'watchlistdays' => 0,
			] ],
			[
				'Localtimezone' => 'America/Phoenix',
				'LocalTZoffset' => -7 * 60,
				'DefaultUserOptions' => [
					'rcdays' => 0,
					'watchlistdays' => 0,
					'timecorrection' => 'System|' . (string)( -7 * 60 ),
				],
				'DBerrorLogTZ' => 'America/Phoenix',
			],
			static function ( self $testObj ): void {
				// Pick a timezone with no DST
				$testObj->assertTrue( date_default_timezone_set( 'America/Phoenix' ) );
			},
		];
		yield '$wgDBerrorLogTZ set' => [
			[ 'DBerrorLogTZ' => 'America/Phoenix' ],
			[ 'DBerrorLogTZ' => 'America/Phoenix' ],
		];
		yield 'Setting $wgCanonicalNamespaceNames does nothing' => [
			[ 'CanonicalNamespaceNames' => [ NS_MAIN => 'abc' ] ],
			[],
		];
		yield 'Setting $wgExtraNamespaces does not affect $wgCanonicalNamespaceNames' => [
			[ 'ExtraNamespaces' => [ 100 => 'Extra' ] ],
			[],
		];
		yield '$wgDummyLanguageCodes set' => [
			[ 'DummyLanguageCodes' => [ 'qqq' => 'qqqq', 'foo' => 'bar', 'bh' => 'hb' ] ],
			[ 'DummyLanguageCodes' => self::getExpectedDummyLanguageCodes(
				[ 'qqq' => 'qqqq', 'foo' => 'bar', 'bh' => 'hb' ] ) ],
		];
		yield '$wgExtraLanguageCodes set' => [
			[ 'ExtraLanguageCodes' => [ 'foo' => 'bar' ] ],
			[ 'DummyLanguageCodes' => self::getExpectedDummyLanguageCodes( [],
				[ 'foo' => 'bar' ] ) ],
		];
		yield '$wgDummyLanguageCodes and $wgExtraLanguageCodes set' => [
			[
				'DummyLanguageCodes' => [ 'foo' => 'bar', 'abc' => 'def' ],
				'ExtraLanguageCodes' => [ 'foo' => 'baz', 'ghi' => 'jkl' ],
			],
			[ 'DummyLanguageCodes' => self::getExpectedDummyLanguageCodes(
				[ 'foo' => 'bar', 'abc' => 'def' ], [ 'foo' => 'baz', 'ghi' => 'jkl' ] ) ],
		];
		yield '$wgDatabaseReplicaLagWarning set' => [
			[ 'DatabaseReplicaLagWarning' => 20 ],
			[ 'DatabaseReplicaLagWarning' => 20, 'SlaveLagWarning' => 20 ],
		];
		yield '$wgSlaveLagWarning set' => [
			[ 'SlaveLagWarning' => 20 ],
			[ 'DatabaseReplicaLagWarning' => 20, 'SlaveLagWarning' => 20 ],
		];
		// XXX The settings are out of sync, this doesn't look intended
		yield '$wgDatabaseReplicaLagWarning and $wgSlaveLagWarning set' => [
			[ 'DatabaseReplicaLagWarning' => 20, 'SlaveLagWarning' => 30 ],
			[ 'DatabaseReplicaLagWarning' => 20, 'SlaveLagWarning' => 30 ],
		];
		yield '$wgDatabaseReplicaLagCritical set' => [
			[ 'DatabaseReplicaLagCritical' => 40 ],
			[ 'DatabaseReplicaLagCritical' => 40, 'SlaveLagCritical' => 40 ],
		];
		yield '$wgSlaveLagCritical set' => [
			[ 'SlaveLagCritical' => 40 ],
			[ 'DatabaseReplicaLagCritical' => 40, 'SlaveLagCritical' => 40 ],
		];
		// XXX The settings are out of sync, this doesn't look intended
		yield '$wgDatabaseReplicaLagCritical and $wgSlaveLagCritical set' => [
			[ 'DatabaseReplicaLagCritical' => 40, 'SlaveLagCritical' => 60 ],
			[ 'DatabaseReplicaLagCritical' => 40, 'SlaveLagCritical' => 60 ],
		];
		yield '$wgCacheEpoch set to before LocalSettings touched' => [
			[ 'CacheEpoch' => static function () use ( $expectedDefault ): string {
				return $expectedDefault['CacheEpoch']() - 1;
			} ],
			[ 'CacheEpoch' => static function () use ( $expectedDefault ): string {
				$expected = $expectedDefault['CacheEpoch']();
				// If the file exists, its mtime is later than what we set $wgCacheEpoch to and so
				// it should override what we set.
				return file_exists( MW_CONFIG_FILE ) ? $expected : $expected - 1;
			} ],
		];
		yield '$wgCacheEpoch set to after LocalSettings touched' => [
			[ 'CacheEpoch' => static function () use ( $expectedDefault ): string {
				return $expectedDefault['CacheEpoch']() + 1;
			} ],
			[ 'CacheEpoch' => static function () use ( $expectedDefault ): string {
				return $expectedDefault['CacheEpoch']() + 1;
			} ],
		];
		yield '$wgInvalidateCacheOnLocalSettingsChange false' => [
			[ 'InvalidateCacheOnLocalSettingsChange' => false ],
			[ 'CacheEpoch' => '20030516000000' ],
		];
		yield '$wgNewUserLog is true' => [
			[ 'NewUserLog' => true ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertContains( 'newusers', $vars['LogTypes'] );
				$testObj->assertSame( 'newuserlogpage', $vars['LogNames']['newusers'] );
				$testObj->assertSame( 'newuserlogpagetext', $vars['LogHeaders']['newusers'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars['LogActionsHandlers']['newusers/newusers'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars['LogActionsHandlers']['newusers/create'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars['LogActionsHandlers']['newusers/create2'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars['LogActionsHandlers']['newusers/byemail'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars['LogActionsHandlers']['newusers/autocreate'] );

				return $expectedDefault;
			},
		];
		yield '$wgNewUserLog is false`' => [
			[ 'NewUserLog' => false ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				// Test that the new user log is not added to any of the various logging globals
				$testObj->assertNotContains( 'newusers', $vars['LogTypes'] );
				$testObj->assertArrayNotHasKey( 'newusers', $vars['LogNames'] );
				$testObj->assertArrayNotHasKey( 'newusers', $vars['LogHeaders'] );
				foreach ( $vars['LogActionsHandlers'] as $key => $unused ) {
					$testObj->assertStringStartsNotWith( 'newusers', $key );
				}

				return $expectedDefault;
			},
		];
		yield '$wgPageCreationLog is true' => [
			[ 'PageCreationLog' => true ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertContains( 'create', $vars['LogTypes'] );
				$testObj->assertSame( LogFormatter::class,
					$vars['LogActionsHandlers']['create/create'] );

				return $expectedDefault;
			},
		];
		yield '$wgPageCreationLog is false`' => [
			[ 'PageCreationLog' => false ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertNotContains( 'create', $vars['LogTypes'] );
				$testObj->assertArrayNotHasKey( 'create/create', $vars['LogActionsHandlers'] );

				return $expectedDefault;
			},
		];
		yield '$wgPageLanguageUseDB is true' => [
			[ 'PageLanguageUseDB' => true ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertContains( 'pagelang', $vars['LogTypes'] );
				$testObj->assertSame( PageLangLogFormatter::class,
					$vars['LogActionsHandlers']['pagelang/pagelang'] );

				return $expectedDefault;
			},
		];
		yield '$wgPageLanguageUseDB is false' => [
			[ 'PageLanguageUseDB' => false ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertNotContains( 'pagelang', $vars['LogTypes'] );
				$testObj->assertArrayNotHasKey( 'pagelang/pagelang',
					$vars['LogActionsHandlers'] );

				return $expectedDefault;
			},
		];
		yield '$wgForceHTTPS is true, not HTTPS' => [
			[ 'ForceHTTPS' => true ],
			[ 'CookieSecure' => true ],
			static function () {
				$_SERVER['HTTPS'] = null;
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = null;
			}
		];
		yield '$wgForceHTTPS is true, HTTPS' => [
			[ 'ForceHTTPS' => true ],
			[ 'CookieSecure' => true ],
			static function () {
				$_SERVER['HTTPS'] = 'on';
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
			}
		];
		yield '$wgForceHTTPS is false, not HTTPS' => [
			[ 'ForceHTTPS' => false ],
			[ 'CookieSecure' => false ],
			static function () {
				$_SERVER['HTTPS'] = null;
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = null;
			}
		];
		yield '$wgForceHTTPS is false, HTTPS off' => [
			[ 'ForceHTTPS' => false ],
			[ 'CookieSecure' => false ],
			static function () {
				$_SERVER['HTTPS'] = 'off';
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = null;
			}
		];
		yield '$wgForceHTTPS is false, HTTPS' => [
			[ 'ForceHTTPS' => false ],
			[ 'CookieSecure' => true ],
			static function () {
				$_SERVER['HTTPS'] = 'on';
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = null;
			}
		];
		yield '$wgForceHTTPS is false, forwarded HTTPS' => [
			[ 'ForceHTTPS' => false ],
			[ 'CookieSecure' => true ],
			static function () {
				$_SERVER['HTTPS'] = null;
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
			}
		];
		yield '$wgMinimalPasswordLength' => [
			[ 'MinimalPasswordLength' => 17 ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertSame( 17,
					$vars['PasswordPolicy']['policies']['default']['MinimalPasswordLength'] );
				return $expectedDefault;
			},
		];
		yield '$wgMaximalPasswordLength' => [
			[ 'MaximalPasswordLength' => 17 ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertSame( 17,
					$vars['PasswordPolicy']['policies']['default']['MaximalPasswordLength'] );
				return $expectedDefault;
			},
		];
		yield 'Bogus $wgPHPSessionHandling' => [
			[ 'PHPSessionHandling' => 'bogus' ],
			[ 'PHPSessionHandling' => 'warn' ],
		];
		yield 'Enable $wgPHPSessionHandling' => [
			[ 'PHPSessionHandling' => 'enable' ],
			[ 'PHPSessionHandling' => 'enable' ],
		];
		yield 'Disable $wgPHPSessionHandling' => [
			[ 'PHPSessionHandling' => 'disable' ],
			[ 'PHPSessionHandling' => 'disable' ],
		];
		// XXX No obvious way to test MW_NO_SESSION, because constants can't be undefined
	}

	/**
	 * Test that if the variables $test are set after DefaultSettings.php is loaded, then
	 * DynamicDefaultValues and SetupDynamicConfig.php will result in the variables
	 * in $expected being set to the given values.
	 * (This does not test that other variables aren't also set.)
	 *
	 * @dataProvider provideGlobals
	 * @covers       \MediaWiki\Settings\DynamicDefaultValues
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
		$IP = '/install/path';

		$configBuilder = new ArrayConfigBuilder();
		$schemaSource = new ReflectionSchemaSource( MainConfigSchema::class );
		$schemaDeclarations = $schemaSource->load()['config-schema'];

		$schema = new ConfigSchemaAggregator();

		// Declare schemas and apply default values
		foreach ( $schemaDeclarations as $key => $sch ) {
			$schema->addSchema( $key, $sch );

			$val = $schema->getDefaultFor( $key );
			$configBuilder->set( $key, $val );
		}

		// Apply test values
		foreach ( $test as $key => $val ) {
			// Some defaults don't work properly on CI if evaluated in the provider,
			// so use a callback.
			if ( is_callable( $val ) ) {
				$val = $val();
			}

			$configBuilder->set( $key, $val );
		}

		$configBuilder->set( MainConfigNames::BaseDirectory, $IP );

		if ( $setup ) {
			$scopedCallback = $setup( $this );
		}

		$dynamicDefaults = new DynamicDefaultValues( $schema );
		$dynamicDefaults->applyDynamicDefaults( $configBuilder );
		$config = $configBuilder->build();

		// Put the config variables into the local scope, so SetupDynamicConfig.php can use them.
		foreach ( $config as $key => $val ) {
			$var = "wg$key";
			$$var = $val;
		}

		// phpcs:ignore MediaWiki.VariableAnalysis.MisleadingGlobalNames.Misleading$wgSettings
		$wgSettings = new SettingsBuilder(
			MW_INSTALL_PATH,
			$this->createNoOpMock( ExtensionRegistry::class ),
			$configBuilder,
			$this->createNoOpMock( PhpIniSink::class )
		);
		require MW_INSTALL_PATH . '/includes/SetupDynamicConfig.php';

		if ( is_callable( $expected ) ) {
			$vars = get_defined_vars();

			$newKeys = array_map( static function ( $key ) {
				return substr( $key, 2 );
			}, array_keys( $vars ) );
			$vars = array_combine( $newKeys, $vars );

			$expected = $expected( $this, $vars );
		}

		foreach ( $expected as $key => $val ) {
			$var = "wg$key";
			$this->assertSame( is_callable( $val ) ? $val() : $val,
				$$var, "Unexpected value for \$$var" );
		}
	}

}
