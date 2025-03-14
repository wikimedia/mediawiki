<?php

use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\ForeignAPIRepo;
use MediaWiki\FileRepo\ForeignDBRepo;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Logging\LogFormatter;
use MediaWiki\Logging\NewUsersLogFormatter;
use MediaWiki\Logging\PageLangLogFormatter;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\ConfigSchemaAggregator;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\DynamicDefaultValues;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\Source\ReflectionSchemaSource;
use MediaWiki\Title\NamespaceInfo;

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
			MainConfigNames::ScriptPath => '/wiki',
			MainConfigNames::Script => '/wiki/index.php',
			MainConfigNames::LoadScript => '/wiki/load.php',
			MainConfigNames::RestPath => '/wiki/rest.php',
			// XXX $wgUsePathInfo set based on PHP_SAPI, no good way to test?
			// XXX Testing $wgArticlePath doesn't seem to work
			MainConfigNames::ResourceBasePath => '/wiki',
			MainConfigNames::StylePath => '/wiki/skins',
			MainConfigNames::LocalStylePath => '/wiki/skins',
			MainConfigNames::ExtensionAssetsPath => '/wiki/extensions',
			MainConfigNames::Logo => '/wiki/resources/assets/change-your-logo.svg',
			MainConfigNames::UploadPath => '/wiki/images',
			MainConfigNames::UploadDirectory => '/install/path/images',
			MainConfigNames::ReadOnlyFile => '/install/path/images/lock_yBgMBwiR',
			MainConfigNames::FileCacheDirectory => '/install/path/images/cache',
			MainConfigNames::DeletedDirectory => '/install/path/images/deleted',
			MainConfigNames::SharedPrefix => '',
			MainConfigNames::SharedSchema => null,
			MainConfigNames::MetaNamespace => 'MediaWiki',
			MainConfigNames::EnableUserEmailMuteList => false,
			'EnableUserEmailBlacklist' => false,
			MainConfigNames::NamespaceProtection => [ NS_MEDIAWIKI => 'editinterface' ],
			MainConfigNames::LockManagers => [ [
				'name' => 'fsLockManager',
				'class' => FSLockManager::class,
				'lockDirectory' => '/install/path/images/lockdir',
			], [
				'name' => 'nullLockManager',
				'class' => NullLockManager::class,
			] ],
			// Can't really test this properly without being able to mock global functions
			MainConfigNames::ShowEXIF => function_exists( 'exif_read_data' ),
			MainConfigNames::GalleryOptions => [
				'imagesPerRow' => 0,
				'imageWidth' => 120,
				'imageHeight' => 120,
				'captionLength' => true,
				'showBytes' => true,
				'showDimensions' => true,
				'mode' => 'traditional',
			],
			MainConfigNames::LocalFileRepo => [
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
			MainConfigNames::CookiePrefix => 'my_wiki',
			MainConfigNames::Localtimezone => 'UTC',
			MainConfigNames::LocalTZoffset => 0,
			MainConfigNames::DBerrorLogTZ => 'UTC',
			MainConfigNames::CanonicalNamespaceNames => NamespaceInfo::CANONICAL_NAMES,
			MainConfigNames::DummyLanguageCodes => self::getExpectedDummyLanguageCodes( [] ),
			'SlaveLagWarning' => 10,
			MainConfigNames::DatabaseReplicaLagWarning => 10,
			'SlaveLagCritical' => 30,
			MainConfigNames::DatabaseReplicaLagCritical => 30,
			// This will be wrong if LocalSettings.php was last touched before May 16, 2003.
			MainConfigNames::CacheEpoch => static function (): string {
				// We need a callback that will be evaluated at test time, because otherwise this
				// doesn't work on CI for some reason.
				$ret = max( '20030516000000', gmdate( 'YmdHis', @filemtime( MW_CONFIG_FILE ) ) );
				return $ret;
			},
			MainConfigNames::RateLimits => MainConfigSchema::getDefaultValue( MainConfigNames::RateLimits ),
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
			[ MainConfigNames::ScriptPath => '/mywiki' ],
			array_map( self::recursiveReplaceCallback( '/wiki', '/mywiki' ), $expectedDefault ),
		];
		yield '$wgResourceBasePath set' => [
			[ MainConfigNames::ResourceBasePath => '/resources' ],
			[
				MainConfigNames::ResourceBasePath => '/resources',
				MainConfigNames::ExtensionAssetsPath => '/resources/extensions',
				MainConfigNames::StylePath => '/resources/skins',
				MainConfigNames::Logo => '/resources/resources/assets/change-your-logo.svg',
			],
		];
		yield '$wgUploadDirectory set' => [
			[ MainConfigNames::UploadDirectory => '/uploads' ],
			[
				MainConfigNames::UploadDirectory => '/uploads',
				MainConfigNames::ReadOnlyFile => '/uploads/lock_yBgMBwiR',
				MainConfigNames::FileCacheDirectory => '/uploads/cache',
				MainConfigNames::DeletedDirectory => '/uploads/deleted',
				MainConfigNames::LockManagers => [ [
					'name' => 'fsLockManager',
					'class' => FSLockManager::class,
					'lockDirectory' => '/uploads/lockdir',
				], [
					'name' => 'nullLockManager',
					'class' => NullLockManager::class,
				] ],
				MainConfigNames::LocalFileRepo => [
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
			[ MainConfigNames::CacheDirectory => '/cache' ],
			[ MainConfigNames::CacheDirectory => '/cache' ],
		];
		yield '$wgDBprefix set' => [
			[ MainConfigNames::DBprefix => 'prefix_' ],
			[
				MainConfigNames::DBprefix => 'prefix_',
				MainConfigNames::SharedPrefix => 'prefix_',
				MainConfigNames::CookiePrefix => 'my_wiki_prefix_',
			],
		];
		yield '$wgDBmwschema set' => [
			[ MainConfigNames::DBmwschema => 'schema' ],
			[
				MainConfigNames::DBmwschema => 'schema',
				MainConfigNames::SharedSchema => 'schema',
			],
		];
		yield '$wgSitename set' => [
			[ MainConfigNames::Sitename => 'my site' ],
			[
				MainConfigNames::Sitename => 'my site',
				MainConfigNames::MetaNamespace => 'my_site',
			],
		];
		yield '$wgProhibitedFileExtensions set' => [
			[ MainConfigNames::ProhibitedFileExtensions => [ 'evil' ] ],
			[
				MainConfigNames::ProhibitedFileExtensions => [ 'evil' ],
				'FileBlacklist' => [ 'evil' ],
			],
		];
		yield '$wgProhibitedFileExtensions and $wgFileBlacklist set' => [
			[
				MainConfigNames::ProhibitedFileExtensions => [ 'evil' ],
				'FileBlacklist' => [ 'eviler' ],
			],
			[
				MainConfigNames::ProhibitedFileExtensions => [ 'evil', 'eviler' ],
				'FileBlacklist' => [ 'eviler' ],
			],
		];
		yield '$wgMimeTypeExclusions set' => [
			[ MainConfigNames::MimeTypeExclusions => [ 'evil' ] ],
			[
				MainConfigNames::MimeTypeExclusions => [ 'evil' ],
				'MimeTypeBlacklist' => [ 'evil' ],
			],
		];
		yield '$wgMimeTypeExclusions and $wgMimeTypeBlacklist set' => [
			[
				MainConfigNames::MimeTypeExclusions => [ 'evil' ],
				'MimeTypeBlacklist' => [ 'eviler' ],
			],
			[
				MainConfigNames::MimeTypeExclusions => [ 'evil', 'eviler' ],
				'MimeTypeBlacklist' => [ 'eviler' ],
			],
		];
		yield '$wgEnableUserEmailMuteList set' => [
			[ MainConfigNames::EnableUserEmailMuteList => true ],
			[
				MainConfigNames::EnableUserEmailMuteList => true,
				'EnableUserEmailBlacklist' => true,
			],
		];
		yield '$wgEnableUserEmailMuteList and $wgEnableUserEmailBlacklist both true' => [
			[
				MainConfigNames::EnableUserEmailMuteList => true,
				'EnableUserEmailBlacklist' => true,
			],
			[
				MainConfigNames::EnableUserEmailMuteList => true,
				'EnableUserEmailBlacklist' => true,
			],
		];
		yield '$wgEnableUserEmailMuteList true and $wgEnableUserEmailBlacklist false' => [
			[
				MainConfigNames::EnableUserEmailMuteList => true,
				'EnableUserEmailBlacklist' => false,
			],
			[
				MainConfigNames::EnableUserEmailMuteList => false,
				'EnableUserEmailBlacklist' => false,
			],
		];
		yield '$wgShortPagesNamespaceExclusions set' => [
			[ MainConfigNames::ShortPagesNamespaceExclusions => [ NS_TALK ] ],
			[
				MainConfigNames::ShortPagesNamespaceExclusions => [ NS_TALK ],
				'ShortPagesNamespaceBlacklist' => [ NS_TALK ],
			],
		];
		yield '$wgShortPagesNamespaceBlacklist set' => [
			[ 'ShortPagesNamespaceBlacklist' => [ NS_PROJECT ] ],
			[
				MainConfigNames::ShortPagesNamespaceExclusions => [ NS_PROJECT ],
				'ShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			],
		];
		yield '$wgShortPagesNamespaceExclusions and $wgShortPagesNamespaceBlacklist set' => [
			[
				MainConfigNames::ShortPagesNamespaceExclusions => [ NS_TALK ],
				'ShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			],
			[
				MainConfigNames::ShortPagesNamespaceExclusions => [ NS_PROJECT ],
				'ShortPagesNamespaceBlacklist' => [ NS_PROJECT ],
			],
		];
		yield '$wgFileExtension contains something from $wgProhibitedFileExtensions' => [
			[
				MainConfigNames::FileExtensions => [ 'a', 'b', 'c' ],
				MainConfigNames::ProhibitedFileExtensions => [ 'b', 'd' ],
			],
			[
				MainConfigNames::FileExtensions => [ 'a', 'c' ],
				MainConfigNames::ProhibitedFileExtensions => [ 'b', 'd' ],
			],
		];
		yield '$wgRightsIcon old path convention' => [
			[
				MainConfigNames::StylePath => '/style',
				MainConfigNames::ResourceBasePath => '/resources',
				MainConfigNames::RightsIcon => '/style/common/images/rights.png',
			],
			[
				MainConfigNames::StylePath => '/style',
				MainConfigNames::ResourceBasePath => '/resources',
				MainConfigNames::ExtensionAssetsPath => '/resources/extensions',
				MainConfigNames::Logo => '/resources/resources/assets/change-your-logo.svg',
				MainConfigNames::RightsIcon => '/resources/resources/assets/licenses/rights.png',
			],
		];
		yield 'Empty $wgFooterIcons[\'copyright\'][\'copyright\'], $wgRightsIcon set' => [
			[
				MainConfigNames::RightsIcon => 'ico',
				MainConfigNames::FooterIcons => [ 'copyright' => [ 'copyright' => [] ] ],
			],
			[
				MainConfigNames::FooterIcons => [ 'copyright' => [ 'copyright' => [
					'url' => null,
					'src' => 'ico',
					'alt' => null,
				] ] ],
			],
		];
		yield 'Empty $wgFooterIcons[\'copyright\'][\'copyright\'], $wgRightsText set' => [
			[
				MainConfigNames::RightsText => 'text',
				MainConfigNames::FooterIcons => [ 'copyright' => [ 'copyright' => [] ] ],
			],
			[
				MainConfigNames::FooterIcons => [ 'copyright' => [ 'copyright' => [
					'url' => null,
					'src' => null,
					'alt' => 'text',
				] ] ],
			],
		];
		yield '$wgFooterIcons[\'poweredby\'][\'mediawiki\'][\'src\'] === null' => [
			[
				MainConfigNames::ResourceBasePath => '/resources',
				MainConfigNames::FooterIcons => [ 'poweredby' => [ 'mediawiki' => [ 'src' => null ] ] ],
			],
			[
				MainConfigNames::ResourceBasePath => '/resources',
				MainConfigNames::ExtensionAssetsPath => '/resources/extensions',
				MainConfigNames::StylePath => '/resources/skins',
				MainConfigNames::Logo => '/resources/resources/assets/change-your-logo.svg',
				MainConfigNames::FooterIcons => [ 'poweredby' => [ 'mediawiki' => [
					'src' => '/resources/resources/assets/mediawiki_compact.svg',
					'sources' => [
						[
							'media' => '(min-width: 500px)',
							'srcset' => '/resources/resources/assets/poweredby_mediawiki.svg',
							'width' => 88,
							'height' => 31,
						]
					],
					'width' => 25,
					'height' => 25
				] ] ],
			],
		];
		yield '$wgFooterIcons[\'poweredby\'][\'mediawiki\'][\'src\'] === \'\'' => [
			[ MainConfigNames::FooterIcons => [ 'poweredby' => [ 'mediawiki' => [ 'src' => '' ] ] ] ],
			[ MainConfigNames::FooterIcons => [ 'poweredby' => [ 'mediawiki' => [ 'src' => '' ] ] ] ],
		];
		yield '$wgFooterIcons[\'poweredby\']=== []' => [
			[ MainConfigNames::FooterIcons => [ 'poweredby' => [] ] ],
			[ MainConfigNames::FooterIcons => [ 'poweredby' => [] ] ],
		];
		yield '$wgNamespaceProtection set only for non-NS_MEDIAWIKI' => [
			[ MainConfigNames::NamespaceProtection => [ NS_PROJECT => 'editprotected' ] ],
			[ MainConfigNames::NamespaceProtection => [
				NS_PROJECT => 'editprotected',
				NS_MEDIAWIKI => 'editinterface',
			] ],
		];
		yield '$wgNamespaceProtection[NS_MEDIAWIKI] not editinterface' => [
			[ MainConfigNames::NamespaceProtection => [ NS_MEDIAWIKI => 'editprotected' ] ],
			[ MainConfigNames::NamespaceProtection => [ NS_MEDIAWIKI => 'editinterface' ] ],
		];
		yield 'Custom lock manager' => [
			[ MainConfigNames::LockManagers => [ [ 'foo' ] ] ],
			[ MainConfigNames::LockManagers => [ [ 'foo' ], [
				'name' => 'fsLockManager',
				'class' => FSLockManager::class,
				'lockDirectory' => '/install/path/images/lockdir',
			], [
				'name' => 'nullLockManager',
				'class' => NullLockManager::class,
			] ] ],
		];
		yield 'Customize some $wgGalleryOptions' => [
			[ MainConfigNames::GalleryOptions => [
				'imagesPerRow' => 42,
				'imageHeight' => -1,
				'showBytes' => null,
				'mode' => 'iconoclastic',
				'custom' => 'Rembrandt',
			] ],
			[ MainConfigNames::GalleryOptions => [
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
			[ MainConfigNames::LocalFileRepo => [ 'name' => 'asdfgh' ] ],
			[ MainConfigNames::LocalFileRepo => [
				'name' => 'asdfgh',
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
				'backend' => 'asdfgh-backend',
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
			[ MainConfigNames::UseSharedUploads => true ],
			[ MainConfigNames::ForeignFileRepos => [ $sharedUploadsExpected ] ],
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
				MainConfigNames::UseSharedUploads => true,
				MainConfigNames::SharedUploadDBname => 'shared_uploads',
			],
			[ MainConfigNames::ForeignFileRepos => [ $sharedUploadsDBnameExpected ] ],
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
			[ MainConfigNames::UseInstantCommons => true ],
			[ MainConfigNames::ForeignFileRepos => [ $instantCommonsExpected ] ],
		];
		yield '$wgUseSharedUploads and $wgUseInstantCommons' => [
			[
				MainConfigNames::UseSharedUploads => true,
				MainConfigNames::UseInstantCommons => true,
			],
			[ MainConfigNames::ForeignFileRepos => [
				$sharedUploadsExpected, $instantCommonsExpected ]
			],
		];
		yield '$wgUseSharedUploads and $wgSharedUploadDBname and $wgUseInstantCommons' => [
			[
				MainConfigNames::UseSharedUploads => true,
				MainConfigNames::SharedUploadDBname => 'shared_uploads',
				MainConfigNames::UseInstantCommons => true,
			],
			[ MainConfigNames::ForeignFileRepos => [
				$sharedUploadsDBnameExpected, $instantCommonsExpected ]
			],
		];
		yield 'ForeignAPIRepo with no directory' => [
			[
				MainConfigNames::ForeignFileRepos => [ [
					'name' => 'foreigner',
					'class' => ForeignAPIRepo::class,
				] ],
				MainConfigNames::UploadDirectory => '/upload',
			],
			[ MainConfigNames::ForeignFileRepos => [ [
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
				MainConfigNames::DefaultUserOptions => [
					'rcdays' => 100,
					'watchlistdays' => 75,
				],
				MainConfigNames::RCMaxAge => 36 * 3600,
			],
			[ MainConfigNames::DefaultUserOptions => [
				'rcdays' => 2.0,
				'watchlistdays' => 2.0,
				'timecorrection' => 'System|0',
			] ],
		];
		yield '$wgSharedDB set' => [
			[ MainConfigNames::SharedDB => 'shared_db' ],
			[ MainConfigNames::CookiePrefix => 'shared_db' ],
		];
		yield '$wgSharedDB and $wgSharedPrefix set' => [
			[ MainConfigNames::SharedDB => 'shared_db', MainConfigNames::SharedPrefix => 'shared_prefix' ],
			[
				MainConfigNames::CookiePrefix => 'shared_db_shared_prefix',
				MainConfigNames::SharedPrefix => 'shared_prefix',
			],
		];
		yield '$wgSharedPrefix set with no $wgSharedDB' => [
			[ MainConfigNames::SharedPrefix => 'shared_prefix' ],
			[ MainConfigNames::SharedPrefix => 'shared_prefix' ],
		];
		yield '$wgSharedDB set but user not in $wgSharedTables' => [
			[ MainConfigNames::SharedDB => 'shared_db', MainConfigNames::SharedTables => [ 'user_properties' ] ],
			[],
		];
		yield '$wgSharedDB and $wgSharedPrefix set but user not in $wgSharedTables' => [
			[
				MainConfigNames::SharedDB => 'shared_db',
				MainConfigNames::SharedPrefix => 'shared_prefix',
				MainConfigNames::SharedTables => [ 'user_properties' ],
			],
			[ MainConfigNames::SharedPrefix => 'shared_prefix' ],
		];
		yield '$wgCookiePrefix with allowed punctuation' => [
			[ MainConfigNames::CookiePrefix => "~!@#$%^&*()_-]{}|:<>/?\t\r\n\f" ],
			[ MainConfigNames::CookiePrefix => "~!@#$%^&*()_-]{}|:<>/?\t\r\n\f" ],
		];
		yield '$wgCookiePrefix with bad characters' => [
			[ MainConfigNames::CookiePrefix => 'n=o,t;a l+l.o"w\'e\\d[' ],
			[ MainConfigNames::CookiePrefix => 'n_o_t_a_l_l_o_w_e_d_' ],
		];
		yield '$wgEnableEmail set to false' => [
			[
				MainConfigNames::EnableEmail => false,
				MainConfigNames::AllowHTMLEmail => true,
				MainConfigNames::EmailAuthentication => true,
				MainConfigNames::EnableUserEmail => true,
				MainConfigNames::EnotifFromEditor => true,
				MainConfigNames::EnotifMinorEdits => true,
				MainConfigNames::EnotifRevealEditorAddress => true,
				MainConfigNames::EnotifUseRealName => true,
				MainConfigNames::EnotifUserTalk => true,
				MainConfigNames::EnotifWatchlist => true,
				MainConfigNames::GroupPermissions => [ 'user' => [ 'sendemail' => true ] ],
				MainConfigNames::UserEmailUseReplyTo => true,
				MainConfigNames::UsersNotifiedOnAllChanges => [ 'Admin' ],
			], [
				MainConfigNames::AllowHTMLEmail => false,
				MainConfigNames::EmailAuthentication => false,
				MainConfigNames::EnableUserEmail => false,
				MainConfigNames::EnotifFromEditor => false,
				MainConfigNames::EnotifMinorEdits => false,
				MainConfigNames::EnotifRevealEditorAddress => false,
				MainConfigNames::EnotifUseRealName => false,
				MainConfigNames::EnotifUserTalk => false,
				MainConfigNames::EnotifWatchlist => false,
				MainConfigNames::GroupPermissions => [ 'user' => [] ],
				MainConfigNames::UserEmailUseReplyTo => false,
				MainConfigNames::UsersNotifiedOnAllChanges => [],
			],
		];
		yield 'Change PHP default timezone' => [
			[ MainConfigNames::DefaultUserOptions => [
				'rcdays' => 0,
				'watchlistdays' => 0,
			] ],
			[
				MainConfigNames::Localtimezone => 'America/Phoenix',
				MainConfigNames::LocalTZoffset => -7 * 60,
				MainConfigNames::DefaultUserOptions => [
					'rcdays' => 0,
					'watchlistdays' => 0,
					'timecorrection' => 'System|' . ( -7 * 60 ),
				],
				MainConfigNames::DBerrorLogTZ => 'America/Phoenix',
			],
			static function ( self $testObj ): void {
				// Pick a timezone with no DST
				$testObj->assertTrue( date_default_timezone_set( 'America/Phoenix' ) );
			},
		];
		yield '$wgDBerrorLogTZ set' => [
			[ MainConfigNames::DBerrorLogTZ => 'America/Phoenix' ],
			[ MainConfigNames::DBerrorLogTZ => 'America/Phoenix' ],
		];
		yield 'Setting $wgCanonicalNamespaceNames does nothing' => [
			[ MainConfigNames::CanonicalNamespaceNames => [ NS_MAIN => 'abc' ] ],
			[],
		];
		yield 'Setting $wgExtraNamespaces does not affect $wgCanonicalNamespaceNames' => [
			[ MainConfigNames::ExtraNamespaces => [ 100 => 'Extra' ] ],
			[],
		];
		yield '$wgDummyLanguageCodes set' => [
			[ MainConfigNames::DummyLanguageCodes => [ 'qqq' => 'qqqq', 'foo' => 'bar', 'bh' => 'hb' ] ],
			[ MainConfigNames::DummyLanguageCodes => self::getExpectedDummyLanguageCodes(
				[ 'qqq' => 'qqqq', 'foo' => 'bar', 'bh' => 'hb' ] ) ],
		];
		yield '$wgExtraLanguageCodes set' => [
			[ MainConfigNames::ExtraLanguageCodes => [ 'foo' => 'bar' ] ],
			[ MainConfigNames::DummyLanguageCodes => self::getExpectedDummyLanguageCodes( [],
				[ 'foo' => 'bar' ] ) ],
		];
		yield '$wgDummyLanguageCodes and $wgExtraLanguageCodes set' => [
			[
				MainConfigNames::DummyLanguageCodes => [ 'foo' => 'bar', 'abc' => 'def' ],
				MainConfigNames::ExtraLanguageCodes => [ 'foo' => 'baz', 'ghi' => 'jkl' ],
			],
			[ MainConfigNames::DummyLanguageCodes => self::getExpectedDummyLanguageCodes(
				[ 'foo' => 'bar', 'abc' => 'def' ], [ 'foo' => 'baz', 'ghi' => 'jkl' ] ) ],
		];
		yield '$wgDatabaseReplicaLagWarning set' => [
			[ MainConfigNames::DatabaseReplicaLagWarning => 20 ],
			[ MainConfigNames::DatabaseReplicaLagWarning => 20, 'SlaveLagWarning' => 20 ],
		];
		yield '$wgSlaveLagWarning set' => [
			[ 'SlaveLagWarning' => 20 ],
			[ MainConfigNames::DatabaseReplicaLagWarning => 20, 'SlaveLagWarning' => 20 ],
		];
		// XXX The settings are out of sync, this doesn't look intended
		yield '$wgDatabaseReplicaLagWarning and $wgSlaveLagWarning set' => [
			[ MainConfigNames::DatabaseReplicaLagWarning => 20, 'SlaveLagWarning' => 30 ],
			[ MainConfigNames::DatabaseReplicaLagWarning => 20, 'SlaveLagWarning' => 30 ],
		];
		yield '$wgDatabaseReplicaLagCritical set' => [
			[ MainConfigNames::DatabaseReplicaLagCritical => 40 ],
			[ MainConfigNames::DatabaseReplicaLagCritical => 40, 'SlaveLagCritical' => 40 ],
		];
		yield '$wgSlaveLagCritical set' => [
			[ 'SlaveLagCritical' => 40 ],
			[ MainConfigNames::DatabaseReplicaLagCritical => 40, 'SlaveLagCritical' => 40 ],
		];
		// XXX The settings are out of sync, this doesn't look intended
		yield '$wgDatabaseReplicaLagCritical and $wgSlaveLagCritical set' => [
			[ MainConfigNames::DatabaseReplicaLagCritical => 40, 'SlaveLagCritical' => 60 ],
			[ MainConfigNames::DatabaseReplicaLagCritical => 40, 'SlaveLagCritical' => 60 ],
		];
		yield '$wgCacheEpoch set to before LocalSettings touched' => [
			[ MainConfigNames::CacheEpoch => static function () use ( $expectedDefault ): string {
				return $expectedDefault[MainConfigNames::CacheEpoch]() - 1;
			} ],
			[ MainConfigNames::CacheEpoch => static function () use ( $expectedDefault ): string {
				$expected = $expectedDefault[MainConfigNames::CacheEpoch]();
				// If the file exists, its mtime is later than what we set $wgCacheEpoch to and so
				// it should override what we set.
				return file_exists( MW_CONFIG_FILE ) ? $expected : $expected - 1;
			} ],
		];
		yield '$wgCacheEpoch set to after LocalSettings touched' => [
			[ MainConfigNames::CacheEpoch => static function () use ( $expectedDefault ): string {
				return $expectedDefault[MainConfigNames::CacheEpoch]() + 1;
			} ],
			[ MainConfigNames::CacheEpoch => static function () use ( $expectedDefault ): string {
				return $expectedDefault[MainConfigNames::CacheEpoch]() + 1;
			} ],
		];
		yield '$wgInvalidateCacheOnLocalSettingsChange false' => [
			[ MainConfigNames::InvalidateCacheOnLocalSettingsChange => false ],
			[ MainConfigNames::CacheEpoch => '20030516000000' ],
		];
		yield '$wgNewUserLog is true' => [
			[ MainConfigNames::NewUserLog => true ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertContains( 'newusers', $vars[MainConfigNames::LogTypes] );
				$testObj->assertSame( 'newuserlogpage', $vars[MainConfigNames::LogNames]['newusers'] );
				$testObj->assertSame( 'newuserlogpagetext', $vars[MainConfigNames::LogHeaders]['newusers'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars[MainConfigNames::LogActionsHandlers]['newusers/newusers']['class'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars[MainConfigNames::LogActionsHandlers]['newusers/create']['class'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars[MainConfigNames::LogActionsHandlers]['newusers/create2']['class'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars[MainConfigNames::LogActionsHandlers]['newusers/byemail']['class'] );
				$testObj->assertSame( NewUsersLogFormatter::class,
					$vars[MainConfigNames::LogActionsHandlers]['newusers/autocreate']['class'] );

				return $expectedDefault;
			},
		];
		yield '$wgNewUserLog is false`' => [
			[ MainConfigNames::NewUserLog => false ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				// Test that the new user log is not added to any of the various logging globals
				$testObj->assertNotContains( 'newusers', $vars[MainConfigNames::LogTypes] );
				$testObj->assertArrayNotHasKey( 'newusers', $vars[MainConfigNames::LogNames] );
				$testObj->assertArrayNotHasKey( 'newusers', $vars[MainConfigNames::LogHeaders] );
				foreach ( $vars[MainConfigNames::LogActionsHandlers] as $key => $unused ) {
					$testObj->assertStringStartsNotWith( 'newusers', $key );
				}

				return $expectedDefault;
			},
		];
		yield '$wgPageCreationLog is true' => [
			[ MainConfigNames::PageCreationLog => true ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertContains( 'create', $vars[MainConfigNames::LogTypes] );
				$testObj->assertSame( LogFormatter::class,
					$vars[MainConfigNames::LogActionsHandlers]['create/create'] );

				return $expectedDefault;
			},
		];
		yield '$wgPageCreationLog is false`' => [
			[ MainConfigNames::PageCreationLog => false ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertNotContains( 'create', $vars[MainConfigNames::LogTypes] );
				$testObj->assertArrayNotHasKey( 'create/create', $vars[MainConfigNames::LogActionsHandlers] );

				return $expectedDefault;
			},
		];
		yield '$wgPageLanguageUseDB is true' => [
			[ MainConfigNames::PageLanguageUseDB => true ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertContains( 'pagelang', $vars[MainConfigNames::LogTypes] );
				$testObj->assertSame( PageLangLogFormatter::class,
					$vars[MainConfigNames::LogActionsHandlers]['pagelang/pagelang']['class'] );

				return $expectedDefault;
			},
		];
		yield '$wgPageLanguageUseDB is false' => [
			[ MainConfigNames::PageLanguageUseDB => false ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault ): array {
				$testObj->assertNotContains( 'pagelang', $vars[MainConfigNames::LogTypes] );
				$testObj->assertArrayNotHasKey( 'pagelang/pagelang',
					$vars[MainConfigNames::LogActionsHandlers] );

				return $expectedDefault;
			},
		];
		yield '$wgForceHTTPS is true, not HTTPS' => [
			[ MainConfigNames::ForceHTTPS => true ],
			[ MainConfigNames::CookieSecure => true ],
			static function () {
				$_SERVER['HTTPS'] = null;
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = null;
			}
		];
		yield '$wgForceHTTPS is true, HTTPS' => [
			[ MainConfigNames::ForceHTTPS => true ],
			[ MainConfigNames::CookieSecure => true ],
			static function () {
				$_SERVER['HTTPS'] = 'on';
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
			}
		];
		yield '$wgForceHTTPS is false, not HTTPS' => [
			[ MainConfigNames::ForceHTTPS => false ],
			[ MainConfigNames::CookieSecure => false ],
			static function () {
				$_SERVER['HTTPS'] = null;
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = null;
			}
		];
		yield '$wgForceHTTPS is false, HTTPS off' => [
			[ MainConfigNames::ForceHTTPS => false ],
			[ MainConfigNames::CookieSecure => false ],
			static function () {
				$_SERVER['HTTPS'] = 'off';
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = null;
			}
		];
		yield '$wgForceHTTPS is false, HTTPS' => [
			[ MainConfigNames::ForceHTTPS => false ],
			[ MainConfigNames::CookieSecure => true ],
			static function () {
				$_SERVER['HTTPS'] = 'on';
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = null;
			}
		];
		yield '$wgForceHTTPS is false, forwarded HTTPS' => [
			[ MainConfigNames::ForceHTTPS => false ],
			[ MainConfigNames::CookieSecure => true ],
			static function () {
				$_SERVER['HTTPS'] = null;
				$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
			}
		];
		yield 'Bogus $wgPHPSessionHandling' => [
			[ MainConfigNames::PHPSessionHandling => 'bogus' ],
			[ MainConfigNames::PHPSessionHandling => 'warn' ],
		];
		yield 'Enable $wgPHPSessionHandling' => [
			[ MainConfigNames::PHPSessionHandling => 'enable' ],
			[ MainConfigNames::PHPSessionHandling => 'enable' ],
		];
		yield 'Disable $wgPHPSessionHandling' => [
			[ MainConfigNames::PHPSessionHandling => 'disable' ],
			[ MainConfigNames::PHPSessionHandling => 'disable' ],
		];

		// use old deprecated rate limit names
		$rateLimits = [
				'emailuser' => [
					'newbie' => [ 1, 86400 ],
				],
				'changetag' => [
					'ip' => [ 1, 60 ],
					'newbie' => [ 2, 60 ],
				],
			];

		yield 'renamed $wgRateLimits' => [
			[ MainConfigNames::RateLimits => $rateLimits + $expectedDefault[MainConfigNames::RateLimits] ],
			static function ( self $testObj, array $vars ) use ( $expectedDefault, $rateLimits ): array {
				$testObj->assertSame( $rateLimits['emailuser'], $vars[MainConfigNames::RateLimits]['sendemail'], 'emailuser' );
				$testObj->assertSame( $rateLimits['changetag'], $vars[MainConfigNames::RateLimits]['changetags'], 'changetag' );
				return [];
			}
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
	 * @covers \MediaWiki\Settings\DynamicDefaultValues
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

		// Set dependencies normally set by Setup.php
		$configBuilder->set( MainConfigNames::UploadDirectory, '/install/path/images' );

		// Apply test values
		foreach ( $test as $key => $val ) {
			// Some defaults don't work properly on CI if evaluated in the provider,
			// so use a callback.
			if ( is_callable( $val ) ) {
				$val = $val();
			}

			$configBuilder->set( $key, $val );
		}

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
