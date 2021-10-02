<?php
/**
 * Base code for MediaWiki installer.
 *
 * DO NOT PATCH THIS FILE IF YOU NEED TO CHANGE INSTALLER BEHAVIOR IN YOUR PACKAGE!
 * See mw-config/overrides/README for details.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Installer
 */

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWiki\Interwiki\NullInterwikiLookup;
use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;

/**
 * The Installer helps admins create or upgrade their wiki.
 *
 * The installer classes are exposed through these human interfaces:
 *
 * - The `maintenance/install.php` script, backed by CliInstaller.
 * - The `maintenance/update.php` script, backed by DatabaseUpdater.
 * - The `mw-config/index.php` web entry point, backed by WebInstaller.
 *
 * @defgroup Installer Installer
 */

/**
 * Base installer class.
 *
 * This class provides the base for installation and update functionality
 * for both MediaWiki core and extensions.
 *
 * @ingroup Installer
 * @since 1.17
 */
abstract class Installer {

	/**
	 * The oldest version of PCRE we can support.
	 *
	 * Defining this is necessary because PHP may be linked with a system version
	 * of PCRE, which may be older than that bundled with the minimum PHP version.
	 */
	public const MINIMUM_PCRE_VERSION = '7.2';

	/**
	 * URL to mediawiki-announce list summary page
	 */
	private const MEDIAWIKI_ANNOUNCE_URL =
		'https://lists.wikimedia.org/postorius/lists/mediawiki-announce.lists.wikimedia.org/';

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * List of detected DBs, access using getCompiledDBs().
	 *
	 * @var array
	 */
	protected $compiledDBs;

	/**
	 * Cached DB installer instances, access using getDBInstaller().
	 *
	 * @var array
	 */
	protected $dbInstallers = [];

	/**
	 * Minimum memory size in MB.
	 *
	 * @var int
	 */
	protected $minMemorySize = 50;

	/**
	 * Cached Title, used by parse().
	 *
	 * @var Title
	 */
	protected $parserTitle;

	/**
	 * Cached ParserOptions, used by parse().
	 *
	 * @var ParserOptions
	 */
	protected $parserOptions;

	/**
	 * Known database types. These correspond to the class names <type>Installer,
	 * and are also MediaWiki database types valid for $wgDBtype.
	 *
	 * To add a new type, create a <type>Installer class and a Database<type>
	 * class, and add a config-type-<type> message to MessagesEn.php.
	 *
	 * @var array
	 */
	protected static $dbTypes = [
		'mysql',
		'postgres',
		'sqlite',
	];

	/**
	 * A list of environment check methods called by doEnvironmentChecks().
	 * These may output warnings using showMessage(), and/or abort the
	 * installation process by returning false.
	 *
	 * For the WebInstaller these are only called on the Welcome page,
	 * if these methods have side-effects that should affect later page loads
	 * (as well as the generated stylesheet), use envPreps instead.
	 *
	 * @var array
	 */
	protected $envChecks = [
		'envCheckDB',
		'envCheckPCRE',
		'envCheckMemory',
		'envCheckCache',
		'envCheckModSecurity',
		'envCheckDiff3',
		'envCheckGraphics',
		'envCheckGit',
		'envCheckServer',
		'envCheckPath',
		'envCheckShellLocale',
		'envCheckUploadsDirectory',
		'envCheckLibicu',
		'envCheckSuhosinMaxValueLength',
		'envCheck64Bit',
	];

	/**
	 * A list of environment preparation methods called by doEnvironmentPreps().
	 *
	 * @var array
	 */
	protected $envPreps = [
		'envPrepServer',
		'envPrepPath',
	];

	/**
	 * MediaWiki configuration globals that will eventually be passed through
	 * to LocalSettings.php. The names only are given here, the defaults
	 * typically come from DefaultSettings.php.
	 *
	 * @var array
	 */
	protected $defaultVarNames = [
		'wgSitename',
		'wgPasswordSender',
		'wgLanguageCode',
		'wgRightsIcon',
		'wgRightsText',
		'wgRightsUrl',
		'wgEnableEmail',
		'wgEnableUserEmail',
		'wgEnotifUserTalk',
		'wgEnotifWatchlist',
		'wgEmailAuthentication',
		'wgDBname',
		'wgDBtype',
		'wgDiff3',
		'wgImageMagickConvertCommand',
		'wgGitBin',
		'IP',
		'wgScriptPath',
		'wgMetaNamespace',
		'wgDeletedDirectory',
		'wgEnableUploads',
		'wgShellLocale',
		'wgSecretKey',
		'wgUseInstantCommons',
		'wgUpgradeKey',
		'wgDefaultSkin',
		'wgPingback',
	];

	/**
	 * Variables that are stored alongside globals, and are used for any
	 * configuration of the installation process aside from the MediaWiki
	 * configuration. Map of names to defaults.
	 *
	 * @var array
	 */
	protected $internalDefaults = [
		'_UserLang' => 'en',
		'_Environment' => false,
		'_RaiseMemory' => false,
		'_UpgradeDone' => false,
		'_InstallDone' => false,
		'_Caches' => [],
		'_InstallPassword' => '',
		'_SameAccount' => true,
		'_CreateDBAccount' => false,
		'_NamespaceType' => 'site-name',
		'_AdminName' => '', // will be set later, when the user selects language
		'_AdminPassword' => '',
		'_AdminPasswordConfirm' => '',
		'_AdminEmail' => '',
		'_Subscribe' => false,
		'_SkipOptional' => 'continue',
		'_RightsProfile' => 'wiki',
		'_LicenseCode' => 'none',
		'_CCDone' => false,
		'_Extensions' => [],
		'_Skins' => [],
		'_MemCachedServers' => '',
		'_UpgradeKeySupplied' => false,
		'_ExistingDBSettings' => false,
		// Single quotes are intentional, LocalSettingsGenerator must output this unescaped.
		'_Logo' => '$wgResourceBasePath/resources/assets/wiki.png',

		'wgAuthenticationTokenVersion' => 1,
	];

	/**
	 * The actual list of installation steps. This will be initialized by getInstallSteps()
	 *
	 * @var array[]
	 * @phan-var array<int,array{name:string,callback:array{0:object,1:string}}>
	 */
	private $installSteps = [];

	/**
	 * Extra steps for installation, for things like DatabaseInstallers to modify
	 *
	 * @var array
	 */
	protected $extraInstallSteps = [];

	/**
	 * Known object cache types and the functions used to test for their existence.
	 *
	 * @var array
	 */
	protected $objectCaches = [
		'apcu' => 'apcu_fetch',
		'wincache' => 'wincache_ucache_get'
	];

	/**
	 * User rights profiles.
	 *
	 * @var array
	 */
	public $rightsProfiles = [
		'wiki' => [],
		'no-anon' => [
			'*' => [ 'edit' => false ]
		],
		'fishbowl' => [
			'*' => [
				'createaccount' => false,
				'edit' => false,
			],
		],
		'private' => [
			'*' => [
				'createaccount' => false,
				'edit' => false,
				'read' => false,
			],
		],
	];

	/**
	 * License types.
	 *
	 * @var array
	 */
	public $licenses = [
		'cc-by' => [
			'url' => 'https://creativecommons.org/licenses/by/4.0/',
			'icon' => '$wgResourceBasePath/resources/assets/licenses/cc-by.png',
		],
		'cc-by-sa' => [
			'url' => 'https://creativecommons.org/licenses/by-sa/4.0/',
			'icon' => '$wgResourceBasePath/resources/assets/licenses/cc-by-sa.png',
		],
		'cc-by-nc-sa' => [
			'url' => 'https://creativecommons.org/licenses/by-nc-sa/4.0/',
			'icon' => '$wgResourceBasePath/resources/assets/licenses/cc-by-nc-sa.png',
		],
		'cc-0' => [
			'url' => 'https://creativecommons.org/publicdomain/zero/1.0/',
			'icon' => '$wgResourceBasePath/resources/assets/licenses/cc-0.png',
		],
		'gfdl' => [
			'url' => 'https://www.gnu.org/copyleft/fdl.html',
			'icon' => '$wgResourceBasePath/resources/assets/licenses/gnu-fdl.png',
		],
		'none' => [
			'url' => '',
			'icon' => '',
			'text' => ''
		],
		'cc-choose' => [
			// Details will be filled in by the selector.
			'url' => '',
			'icon' => '',
			'text' => '',
		],
	];

	/**
	 * @var HookContainer|null
	 */
	protected $autoExtensionHookContainer;

	/**
	 * UI interface for displaying a short message
	 * The parameters are like parameters to wfMessage().
	 * The messages will be in wikitext format, which will be converted to an
	 * output format such as HTML or text before being sent to the user.
	 * @param string $msg
	 * @param mixed ...$params
	 */
	abstract public function showMessage( $msg, ...$params );

	/**
	 * Same as showMessage(), but for displaying errors
	 * @param string $msg
	 * @param mixed ...$params
	 */
	abstract public function showError( $msg, ...$params );

	/**
	 * Show a message to the installing user by using a Status object
	 * @param Status $status
	 */
	abstract public function showStatusMessage( Status $status );

	/**
	 * Constructs a Config object that contains configuration settings that should be
	 * overwritten for the installation process.
	 *
	 * @since 1.27
	 *
	 * @param Config $baseConfig
	 *
	 * @return Config The config to use during installation.
	 */
	public static function getInstallerConfig( Config $baseConfig ) {
		$configOverrides = new HashConfig();

		// disable (problematic) object cache types explicitly, preserving all other (working) ones
		// bug T113843
		$emptyCache = [ 'class' => EmptyBagOStuff::class ];

		$objectCaches = [
				CACHE_NONE => $emptyCache,
				CACHE_DB => $emptyCache,
				CACHE_ANYTHING => $emptyCache,
				CACHE_MEMCACHED => $emptyCache,
			] + $baseConfig->get( 'ObjectCaches' );

		$configOverrides->set( 'ObjectCaches', $objectCaches );

		// Load the installer's i18n.
		$messageDirs = $baseConfig->get( 'MessagesDirs' );
		$messageDirs['MediawikiInstaller'] = __DIR__ . '/i18n';

		$configOverrides->set( 'MessagesDirs', $messageDirs );

		$installerConfig = new MultiConfig( [ $configOverrides, $baseConfig ] );

		// make sure we use the installer config as the main config
		$configRegistry = $baseConfig->get( 'ConfigRegistry' );
		$configRegistry['main'] = function () use ( $installerConfig ) {
			return $installerConfig;
		};

		$configOverrides->set( 'ConfigRegistry', $configRegistry );

		return $installerConfig;
	}

	/**
	 * Constructor, always call this from child classes.
	 */
	public function __construct() {
		$defaultConfig = new GlobalVarConfig(); // all the stuff from DefaultSettings.php
		$installerConfig = self::getInstallerConfig( $defaultConfig );

		$this->resetMediaWikiServices( $installerConfig );

		// Disable all storage services, since we don't have any configuration yet!
		MediaWikiServices::disableStorageBackend();

		$this->settings = $this->internalDefaults;

		foreach ( $this->defaultVarNames as $var ) {
			$this->settings[$var] = $GLOBALS[$var];
		}

		$this->doEnvironmentPreps();

		$this->compiledDBs = [];
		foreach ( self::getDBTypes() as $type ) {
			$installer = $this->getDBInstaller( $type );

			if ( !$installer->isCompiled() ) {
				continue;
			}
			$this->compiledDBs[] = $type;
		}

		$this->parserTitle = Title::newFromText( 'Installer' );
	}

	/**
	 * Reset the global service container and associated global state
	 * to accommodate different stages of the installation.
	 * @since 1.35
	 *
	 * @param Config|null $installerConfig Config override. If null, the previous
	 *        config will be inherited.
	 * @param array $serviceOverrides Service definition overrides. Values can be null to
	 *        disable specific overrides that would be applied per default, namely
	 *        'InterwikiLookup' and 'UserOptionsLookup'.
	 *
	 * @return MediaWikiServices
	 * @throws MWException
	 */
	public function resetMediaWikiServices( Config $installerConfig = null, $serviceOverrides = [] ) {
		global $wgMemc, $wgUser, $wgObjectCaches, $wgLang;

		$serviceOverrides += [
			// Disable interwiki lookup, to avoid database access during parses
			'InterwikiLookup' => function () {
				return new NullInterwikiLookup();
			},

			// Disable user options database fetching, only rely on default options.
			'UserOptionsLookup' => function ( MediaWikiServices $services ) {
				return $services->get( '_DefaultOptionsLookup' );
			}
		];

		$lang = $this->getVar( '_UserLang', 'en' );

		// Reset all services and inject config overrides
		MediaWikiServices::resetGlobalInstance( $installerConfig );

		$mwServices = MediaWikiServices::getInstance();

		foreach ( $serviceOverrides as $name => $callback ) {
			// Skip if the caller set $callback to null
			// to suppress default overrides.
			if ( $callback ) {
				$mwServices->redefineService( $name, $callback );
			}
		}

		// Disable i18n cache
		$mwServices->getLocalisationCache()->disableBackend();

		// Clear language cache so the old i18n cache doesn't sneak back in
		Language::$mLangObjCache = [];

		// Set a fake user.
		// Note that this will reset the context's language,
		// so set the user before setting the language.
		$user = User::newFromId( 0 );
		$wgUser = $user;

		RequestContext::getMain()->setUser( $user );

		// Don't attempt to load user language options (T126177)
		// This will be overridden in the web installer with the user-specified language
		// Ensure $wgLang does not have a reference to a stale LocalisationCache instance
		// (T241638, T261081)
		RequestContext::getMain()->setLanguage( $lang );
		$wgLang = RequestContext::getMain()->getLanguage();

		// Disable object cache (otherwise CACHE_ANYTHING will try CACHE_DB and
		// SqlBagOStuff will then throw since we just disabled wfGetDB)
		$wgObjectCaches = $mwServices->getMainConfig()->get( 'ObjectCaches' );
		$wgMemc = ObjectCache::getInstance( CACHE_NONE );

		$this->parserOptions = new ParserOptions( $user ); // language will be wrong :(
		// Don't try to access DB before user language is initialised
		$this->setParserLanguage( $mwServices->getLanguageFactory()->getLanguage( 'en' ) );

		return $mwServices;
	}

	/**
	 * Get a list of known DB types.
	 *
	 * @return array
	 */
	public static function getDBTypes() {
		return self::$dbTypes;
	}

	/**
	 * Do initial checks of the PHP environment. Set variables according to
	 * the observed environment.
	 *
	 * It's possible that this may be called under the CLI SAPI, not the SAPI
	 * that the wiki will primarily run under. In that case, the subclass should
	 * initialise variables such as wgScriptPath, before calling this function.
	 *
	 * Under the web subclass, it can already be assumed that PHP 5+ is in use
	 * and that sessions are working.
	 *
	 * @return Status
	 */
	public function doEnvironmentChecks() {
		// PHP version has already been checked by entry scripts
		// Show message here for information purposes
		$this->showMessage( 'config-env-php', PHP_VERSION );

		$good = true;
		// Must go here because an old version of PCRE can prevent other checks from completing
		$pcreVersion = explode( ' ', PCRE_VERSION, 2 )[0];
		if ( version_compare( $pcreVersion, self::MINIMUM_PCRE_VERSION, '<' ) ) {
			$this->showError( 'config-pcre-old', self::MINIMUM_PCRE_VERSION, $pcreVersion );
			$good = false;
		} else {
			foreach ( $this->envChecks as $check ) {
				$status = $this->$check();
				if ( $status === false ) {
					$good = false;
				}
			}
		}

		$this->setVar( '_Environment', $good );

		return $good ? Status::newGood() : Status::newFatal( 'config-env-bad' );
	}

	public function doEnvironmentPreps() {
		foreach ( $this->envPreps as $prep ) {
			$this->$prep();
		}
	}

	/**
	 * Set a MW configuration variable, or internal installer configuration variable.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function setVar( $name, $value ) {
		$this->settings[$name] = $value;
	}

	/**
	 * Get an MW configuration variable, or internal installer configuration variable.
	 * The defaults come from $GLOBALS (ultimately DefaultSettings.php).
	 * Installer variables are typically prefixed by an underscore.
	 *
	 * @param string $name
	 * @param mixed|null $default
	 *
	 * @return mixed
	 */
	public function getVar( $name, $default = null ) {
		return $this->settings[$name] ?? $default;
	}

	/**
	 * Get a list of DBs supported by current PHP setup
	 *
	 * @return array
	 */
	public function getCompiledDBs() {
		return $this->compiledDBs;
	}

	/**
	 * Get the DatabaseInstaller class name for this type
	 *
	 * @param string $type database type ($wgDBtype)
	 * @return string Class name
	 * @since 1.30
	 */
	public static function getDBInstallerClass( $type ) {
		return ucfirst( $type ) . 'Installer';
	}

	/**
	 * Get an instance of DatabaseInstaller for the specified DB type.
	 *
	 * @param mixed $type DB installer for which is needed, false to use default.
	 *
	 * @return DatabaseInstaller
	 */
	public function getDBInstaller( $type = false ) {
		if ( !$type ) {
			$type = $this->getVar( 'wgDBtype' );
		}

		$type = strtolower( $type );

		if ( !isset( $this->dbInstallers[$type] ) ) {
			$class = self::getDBInstallerClass( $type );
			$this->dbInstallers[$type] = new $class( $this );
		}

		return $this->dbInstallers[$type];
	}

	/**
	 * Determine if LocalSettings.php exists. If it does, return its variables.
	 *
	 * @return array|false
	 */
	public static function getExistingLocalSettings() {
		global $IP;

		// You might be wondering why this is here. Well if you don't do this
		// then some poorly-formed extensions try to call their own classes
		// after immediately registering them. We really need to get extension
		// registration out of the global scope and into a real format.
		// @see https://phabricator.wikimedia.org/T69440
		global $wgAutoloadClasses;
		$wgAutoloadClasses = [];

		// LocalSettings.php should not call functions, except wfLoadSkin/wfLoadExtensions
		// Define the required globals here, to ensure, the functions can do it work correctly.
		// phpcs:ignore MediaWiki.VariableAnalysis.UnusedGlobalVariables
		global $wgExtensionDirectory, $wgStyleDirectory;

		Wikimedia\suppressWarnings();
		$_lsExists = file_exists( "$IP/LocalSettings.php" );
		Wikimedia\restoreWarnings();

		if ( !$_lsExists ) {
			return false;
		}
		unset( $_lsExists );

		require "$IP/includes/DefaultSettings.php";
		require "$IP/LocalSettings.php";

		return get_defined_vars();
	}

	/**
	 * Get a fake password for sending back to the user in HTML.
	 * This is a security mechanism to avoid compromise of the password in the
	 * event of session ID compromise.
	 *
	 * @param string $realPassword
	 *
	 * @return string
	 */
	public function getFakePassword( $realPassword ) {
		return str_repeat( '*', strlen( $realPassword ) );
	}

	/**
	 * Set a variable which stores a password, except if the new value is a
	 * fake password in which case leave it as it is.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function setPassword( $name, $value ) {
		if ( !preg_match( '/^\*+$/', $value ) ) {
			$this->setVar( $name, $value );
		}
	}

	/**
	 * On POSIX systems return the primary group of the webserver we're running under.
	 * On other systems just returns null.
	 *
	 * This is used to advice the user that he should chgrp his mw-config/data/images directory as the
	 * webserver user before he can install.
	 *
	 * Public because SqliteInstaller needs it, and doesn't subclass Installer.
	 *
	 * @return mixed
	 */
	public static function maybeGetWebserverPrimaryGroup() {
		if ( !function_exists( 'posix_getegid' ) || !function_exists( 'posix_getpwuid' ) ) {
			# I don't know this, this isn't UNIX.
			return null;
		}

		# posix_getegid() *not* getmygid() because we want the group of the webserver,
		# not whoever owns the current script.
		$gid = posix_getegid();
		return posix_getpwuid( $gid )['name'] ?? null;
	}

	/**
	 * Convert wikitext $text to HTML.
	 *
	 * This is potentially error prone since many parser features require a complete
	 * installed MW database. The solution is to just not use those features when you
	 * write your messages. This appears to work well enough. Basic formatting and
	 * external links work just fine.
	 *
	 * But in case a translator decides to throw in a "#ifexist" or internal link or
	 * whatever, this function is guarded to catch the attempted DB access and to present
	 * some fallback text.
	 *
	 * @param string $text
	 * @param bool $lineStart
	 * @return string
	 */
	public function parse( $text, $lineStart = false ) {
		$parser = MediaWikiServices::getInstance()->getParser();

		try {
			$out = $parser->parse( $text, $this->parserTitle, $this->parserOptions, $lineStart );
			$html = $out->getText( [
				'enableSectionEditLinks' => false,
				'unwrap' => true,
			] );
			$html = Parser::stripOuterParagraph( $html );
		} catch ( Wikimedia\Services\ServiceDisabledException $e ) {
			$html = '<!--DB access attempted during parse-->  ' . htmlspecialchars( $text );
		}

		return $html;
	}

	/**
	 * @return ParserOptions
	 */
	public function getParserOptions() {
		return $this->parserOptions;
	}

	public function disableLinkPopups() {
		$this->parserOptions->setExternalLinkTarget( false );
	}

	public function restoreLinkPopups() {
		global $wgExternalLinkTarget;
		$this->parserOptions->setExternalLinkTarget( $wgExternalLinkTarget );
	}

	/**
	 * Install step which adds a row to the site_stats table with appropriate
	 * initial values.
	 *
	 * @param DatabaseInstaller $installer
	 *
	 * @return Status
	 */
	public function populateSiteStats( DatabaseInstaller $installer ) {
		$status = $installer->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		// @phan-suppress-next-line PhanUndeclaredMethod
		$status->value->insert(
			'site_stats',
			[
				'ss_row_id' => 1,
				'ss_total_edits' => 0,
				'ss_good_articles' => 0,
				'ss_total_pages' => 0,
				'ss_users' => 0,
				'ss_active_users' => 0,
				'ss_images' => 0
			],
			__METHOD__,
			'IGNORE'
		);

		return Status::newGood();
	}

	/**
	 * Environment check for DB types.
	 * @return bool
	 */
	protected function envCheckDB() {
		global $wgLang;
		/** @var string|null $dbType The user-specified database type */
		$dbType = $this->getVar( 'wgDBtype' );

		$allNames = [];

		// Messages: config-type-mysql, config-type-postgres, config-type-sqlite
		foreach ( self::getDBTypes() as $name ) {
			$allNames[] = wfMessage( "config-type-$name" )->text();
		}

		$databases = $this->getCompiledDBs();

		$databases = array_flip( $databases );
		$ok = true;
		foreach ( array_keys( $databases ) as $db ) {
			$installer = $this->getDBInstaller( $db );
			$status = $installer->checkPrerequisites();
			if ( !$status->isGood() ) {
				if ( !$this instanceof WebInstaller && $db === $dbType ) {
					// Strictly check the key database type instead of just outputting message
					// Note: No perform this check run from the web installer, since this method always called by
					// the welcome page under web installation, so $dbType will always be 'mysql'
					$ok = false;
				}
				$this->showStatusMessage( $status );
				unset( $databases[$db] );
			}
		}
		$databases = array_flip( $databases );
		if ( !$databases ) {
			$this->showError( 'config-no-db', $wgLang->commaList( $allNames ), count( $allNames ) );
			return false;
		}
		return $ok;
	}

	/**
	 * Environment check for the PCRE module.
	 *
	 * @note If this check were to fail, the parser would
	 *   probably throw an exception before the result
	 *   of this check is shown to the user.
	 * @return bool
	 */
	protected function envCheckPCRE() {
		Wikimedia\suppressWarnings();
		$regexd = preg_replace( '/[\x{0430}-\x{04FF}]/iu', '', '-АБВГД-' );
		// Need to check for \p support too, as PCRE can be compiled
		// with utf8 support, but not unicode property support.
		// check that \p{Zs} (space separators) matches
		// U+3000 (Ideographic space)
		$regexprop = preg_replace( '/\p{Zs}/u', '', "-\u{3000}-" );
		Wikimedia\restoreWarnings();
		if ( $regexd != '--' || $regexprop != '--' ) {
			$this->showError( 'config-pcre-no-utf8' );

			return false;
		}

		return true;
	}

	/**
	 * Environment check for available memory.
	 * @return bool
	 */
	protected function envCheckMemory() {
		$limit = ini_get( 'memory_limit' );

		if ( !$limit || $limit == -1 ) {
			return true;
		}

		$n = wfShorthandToInteger( $limit );

		if ( $n < $this->minMemorySize * 1024 * 1024 ) {
			$newLimit = "{$this->minMemorySize}M";

			if ( ini_set( "memory_limit", $newLimit ) === false ) {
				$this->showMessage( 'config-memory-bad', $limit );
			} else {
				$this->showMessage( 'config-memory-raised', $limit, $newLimit );
				$this->setVar( '_RaiseMemory', true );
			}
		}

		return true;
	}

	/**
	 * Environment check for compiled object cache types.
	 */
	protected function envCheckCache() {
		$caches = [];
		foreach ( $this->objectCaches as $name => $function ) {
			if ( function_exists( $function ) ) {
				$caches[$name] = true;
			}
		}

		if ( !$caches ) {
			$this->showMessage( 'config-no-cache-apcu' );
		}

		$this->setVar( '_Caches', $caches );
	}

	/**
	 * Scare user to death if they have mod_security or mod_security2
	 * @return bool
	 */
	protected function envCheckModSecurity() {
		if ( self::apacheModulePresent( 'mod_security' )
			|| self::apacheModulePresent( 'mod_security2' ) ) {
			$this->showMessage( 'config-mod-security' );
		}

		return true;
	}

	/**
	 * Search for GNU diff3.
	 * @return bool
	 */
	protected function envCheckDiff3() {
		$names = [ "gdiff3", "diff3" ];
		if ( wfIsWindows() ) {
			$names[] = 'diff3.exe';
		}
		$versionInfo = [ '--version', 'GNU diffutils' ];

		$diff3 = ExecutableFinder::findInDefaultPaths( $names, $versionInfo );

		if ( $diff3 ) {
			$this->setVar( 'wgDiff3', $diff3 );
		} else {
			$this->setVar( 'wgDiff3', false );
			$this->showMessage( 'config-diff3-bad' );
		}

		return true;
	}

	/**
	 * Environment check for ImageMagick and GD.
	 * @return bool
	 */
	protected function envCheckGraphics() {
		$names = wfIsWindows() ? 'convert.exe' : 'convert';
		$versionInfo = [ '-version', 'ImageMagick' ];
		$convert = ExecutableFinder::findInDefaultPaths( $names, $versionInfo );

		$this->setVar( 'wgImageMagickConvertCommand', '' );
		if ( $convert ) {
			$this->setVar( 'wgImageMagickConvertCommand', $convert );
			$this->showMessage( 'config-imagemagick', $convert );
		} elseif ( function_exists( 'imagejpeg' ) ) {
			$this->showMessage( 'config-gd' );
		} else {
			$this->showMessage( 'config-no-scaling' );
		}

		return true;
	}

	/**
	 * Search for git.
	 *
	 * @since 1.22
	 * @return bool
	 */
	protected function envCheckGit() {
		$names = wfIsWindows() ? 'git.exe' : 'git';
		$versionInfo = [ '--version', 'git version' ];

		$git = ExecutableFinder::findInDefaultPaths( $names, $versionInfo );

		if ( $git ) {
			$this->setVar( 'wgGitBin', $git );
			$this->showMessage( 'config-git', $git );
		} else {
			$this->setVar( 'wgGitBin', false );
			$this->showMessage( 'config-git-bad' );
		}

		return true;
	}

	/**
	 * Environment check to inform user which server we've assumed.
	 *
	 * @return bool
	 */
	protected function envCheckServer() {
		$server = $this->envGetDefaultServer();
		if ( $server !== null ) {
			$this->showMessage( 'config-using-server', $server );
		}
		return true;
	}

	/**
	 * Environment check to inform user which paths we've assumed.
	 *
	 * @return bool
	 */
	protected function envCheckPath() {
		$this->showMessage(
			'config-using-uri',
			$this->getVar( 'wgServer' ),
			$this->getVar( 'wgScriptPath' )
		);
		return true;
	}

	/**
	 * Environment check for preferred locale in shell
	 * @return bool
	 */
	protected function envCheckShellLocale() {
		$os = php_uname( 's' );
		$supported = [ 'Linux', 'SunOS', 'HP-UX', 'Darwin' ]; # Tested these

		if ( !in_array( $os, $supported ) ) {
			return true;
		}

		if ( Shell::isDisabled() ) {
			return true;
		}

		# Get a list of available locales.
		$result = Shell::command( '/usr/bin/locale', '-a' )->execute();

		if ( $result->getExitCode() != 0 ) {
			return true;
		}

		$lines = $result->getStdout();
		$lines = array_map( 'trim', explode( "\n", $lines ) );
		$candidatesByLocale = [];
		$candidatesByLang = [];
		foreach ( $lines as $line ) {
			if ( $line === '' ) {
				continue;
			}

			if ( !preg_match( '/^([a-zA-Z]+)(_[a-zA-Z]+|)\.(utf8|UTF-8)(@[a-zA-Z_]*|)$/i', $line, $m ) ) {
				continue;
			}

			list( , $lang, , , ) = $m;

			$candidatesByLocale[$m[0]] = $m;
			$candidatesByLang[$lang][] = $m;
		}

		# Try the current value of LANG.
		if ( isset( $candidatesByLocale[getenv( 'LANG' )] ) ) {
			$this->setVar( 'wgShellLocale', getenv( 'LANG' ) );

			return true;
		}

		# Try the most common ones.
		$commonLocales = [ 'C.UTF-8', 'en_US.UTF-8', 'en_US.utf8', 'de_DE.UTF-8', 'de_DE.utf8' ];
		foreach ( $commonLocales as $commonLocale ) {
			if ( isset( $candidatesByLocale[$commonLocale] ) ) {
				$this->setVar( 'wgShellLocale', $commonLocale );

				return true;
			}
		}

		# Is there an available locale in the Wiki's language?
		$wikiLang = $this->getVar( 'wgLanguageCode' );

		if ( isset( $candidatesByLang[$wikiLang] ) ) {
			$m = reset( $candidatesByLang[$wikiLang] );
			$this->setVar( 'wgShellLocale', $m[0] );

			return true;
		}

		# Are there any at all?
		if ( count( $candidatesByLocale ) ) {
			$m = reset( $candidatesByLocale );
			$this->setVar( 'wgShellLocale', $m[0] );

			return true;
		}

		# Give up.
		return true;
	}

	/**
	 * Environment check for the permissions of the uploads directory
	 * @return bool
	 */
	protected function envCheckUploadsDirectory() {
		global $IP;

		$dir = $IP . '/images/';
		$url = $this->getVar( 'wgServer' ) . $this->getVar( 'wgScriptPath' ) . '/images/';
		$safe = !$this->dirIsExecutable( $dir, $url );

		if ( !$safe ) {
			$this->showMessage( 'config-uploads-not-safe', $dir );
		}

		return true;
	}

	/**
	 * Checks if suhosin.get.max_value_length is set, and if so generate
	 * a warning because it is incompatible with ResourceLoader.
	 * @return bool
	 */
	protected function envCheckSuhosinMaxValueLength() {
		$currentValue = ini_get( 'suhosin.get.max_value_length' );
		$minRequired = 2000;
		$recommended = 5000;
		if ( $currentValue > 0 && $currentValue < $minRequired ) {
			$this->showError( 'config-suhosin-max-value-length', $currentValue, $minRequired, $recommended );
			return false;
		}

		return true;
	}

	/**
	 * Checks if we're running on 64 bit or not. 32 bit is becoming increasingly
	 * hard to support, so let's at least warn people.
	 *
	 * @return bool
	 */
	protected function envCheck64Bit() {
		if ( PHP_INT_SIZE == 4 ) {
			$this->showMessage( 'config-using-32bit' );
		}

		return true;
	}

	/**
	 * Check the libicu version
	 */
	protected function envCheckLibicu() {
		/**
		 * This needs to be updated something that the latest libicu
		 * will properly normalize.  This normalization was found at
		 * https://www.unicode.org/versions/Unicode5.2.0/#Character_Additions
		 * Note that we use the hex representation to create the code
		 * points in order to avoid any Unicode-destroying during transit.
		 */
		$not_normal_c = "\u{FA6C}";
		$normal_c = "\u{242EE}";

		$useNormalizer = 'php';
		$needsUpdate = false;

		if ( function_exists( 'normalizer_normalize' ) ) {
			$useNormalizer = 'intl';
			$intl = normalizer_normalize( $not_normal_c, Normalizer::FORM_C );
			if ( $intl !== $normal_c ) {
				$needsUpdate = true;
			}
		}

		// Uses messages 'config-unicode-using-php' and 'config-unicode-using-intl'
		if ( $useNormalizer === 'php' ) {
			$this->showMessage( 'config-unicode-pure-php-warning' );
		} else {
			$this->showMessage( 'config-unicode-using-' . $useNormalizer );
			if ( $needsUpdate ) {
				$this->showMessage( 'config-unicode-update-warning' );
			}
		}
	}

	/**
	 * Environment prep for the server hostname.
	 */
	protected function envPrepServer() {
		$server = $this->envGetDefaultServer();
		if ( $server !== null ) {
			$this->setVar( 'wgServer', $server );
		}
	}

	/**
	 * Helper function to be called from envPrepServer()
	 * @return string
	 */
	abstract protected function envGetDefaultServer();

	/**
	 * Environment prep for setting $IP and $wgScriptPath.
	 */
	protected function envPrepPath() {
		global $IP;
		$IP = dirname( dirname( __DIR__ ) );
		$this->setVar( 'IP', $IP );
	}

	/**
	 * Checks if scripts located in the given directory can be executed via the given URL.
	 *
	 * Used only by environment checks.
	 * @param string $dir
	 * @param string $url
	 * @return bool|int|string
	 */
	public function dirIsExecutable( $dir, $url ) {
		$scriptTypes = [
			'php' => [
				"<?php echo 'exec';",
				"#!/var/env php\n<?php echo 'exec';",
			],
		];

		// it would be good to check other popular languages here, but it'll be slow.
		// TODO no need to have a loop if there is going to only be one script type

		$httpRequestFactory = MediaWikiServices::getInstance()->getHttpRequestFactory();

		Wikimedia\suppressWarnings();

		foreach ( $scriptTypes as $ext => $contents ) {
			foreach ( $contents as $source ) {
				$file = 'exectest.' . $ext;

				if ( !file_put_contents( $dir . $file, $source ) ) {
					break;
				}

				try {
					$text = $httpRequestFactory->get(
						$url . $file,
						[ 'timeout' => 3 ],
						__METHOD__
					);
				} catch ( Exception $e ) {
					// HttpRequestFactory::get can throw with allow_url_fopen = false and no curl
					// extension.
					$text = null;
				}
				unlink( $dir . $file );

				if ( $text == 'exec' ) {
					Wikimedia\restoreWarnings();

					return $ext;
				}
			}
		}

		Wikimedia\restoreWarnings();

		return false;
	}

	/**
	 * Checks for presence of an Apache module. Works only if PHP is running as an Apache module, too.
	 *
	 * @param string $moduleName Name of module to check.
	 * @return bool
	 */
	public static function apacheModulePresent( $moduleName ) {
		if ( function_exists( 'apache_get_modules' ) && in_array( $moduleName, apache_get_modules() ) ) {
			return true;
		}
		// try it the hard way
		ob_start();
		phpinfo( INFO_MODULES );
		$info = ob_get_clean();

		return strpos( $info, $moduleName ) !== false;
	}

	/**
	 * ParserOptions are constructed before we determined the language, so fix it
	 *
	 * @param Language $lang
	 */
	public function setParserLanguage( $lang ) {
		$this->parserOptions->setTargetLanguage( $lang );
		$this->parserOptions->setUserLang( $lang );
	}

	/**
	 * Overridden by WebInstaller to provide lastPage parameters.
	 * @param string $page
	 * @return string
	 */
	protected function getDocUrl( $page ) {
		return "{$_SERVER['PHP_SELF']}?page=" . urlencode( $page );
	}

	/**
	 * Find extensions or skins in a subdirectory of $IP.
	 * Returns an array containing the value for 'Name' for each found extension.
	 *
	 * @param string $directory Directory to search in, relative to $IP, must be either "extensions"
	 *     or "skins"
	 * @return Status An object containing an error list. If there were no errors, an associative
	 *     array of information about the extension can be found in $status->value.
	 */
	public function findExtensions( $directory = 'extensions' ) {
		switch ( $directory ) {
			case 'extensions':
				return $this->findExtensionsByType( 'extension', 'extensions' );
			case 'skins':
				return $this->findExtensionsByType( 'skin', 'skins' );
			default:
				throw new InvalidArgumentException( "Invalid extension type" );
		}
	}

	/**
	 * Find extensions or skins, and return an array containing the value for 'Name' for each found
	 * extension.
	 *
	 * @param string $type Either "extension" or "skin"
	 * @param string $directory Directory to search in, relative to $IP
	 * @return Status An object containing an error list. If there were no errors, an associative
	 *     array of information about the extension can be found in $status->value.
	 */
	protected function findExtensionsByType( $type = 'extension', $directory = 'extensions' ) {
		if ( $this->getVar( 'IP' ) === null ) {
			return Status::newGood( [] );
		}

		$extDir = $this->getVar( 'IP' ) . '/' . $directory;
		if ( !is_readable( $extDir ) || !is_dir( $extDir ) ) {
			return Status::newGood( [] );
		}

		$dh = opendir( $extDir );
		$exts = [];
		$status = new Status;
		while ( ( $file = readdir( $dh ) ) !== false ) {
			// skip non-dirs and hidden directories
			if ( !is_dir( "$extDir/$file" ) || $file[0] === '.' ) {
				continue;
			}
			$extStatus = $this->getExtensionInfo( $type, $directory, $file );
			if ( $extStatus->isOK() ) {
				$exts[$file] = $extStatus->value;
			} elseif ( $extStatus->hasMessage( 'config-extension-not-found' ) ) {
				// (T225512) The directory is not actually an extension. Downgrade to warning.
				$status->warning( 'config-extension-not-found', $file );
			} else {
				$status->merge( $extStatus );
			}
		}
		closedir( $dh );
		uksort( $exts, 'strnatcasecmp' );

		$status->value = $exts;

		return $status;
	}

	/**
	 * @param string $type Either "extension" or "skin"
	 * @param string $parentRelPath The parent directory relative to $IP
	 * @param string $name The extension or skin name
	 * @return Status An object containing an error list. If there were no errors, an associative
	 *     array of information about the extension can be found in $status->value.
	 */
	protected function getExtensionInfo( $type, $parentRelPath, $name ) {
		if ( $this->getVar( 'IP' ) === null ) {
			throw new Exception( 'Cannot find extensions since the IP variable is not yet set' );
		}
		if ( $type !== 'extension' && $type !== 'skin' ) {
			throw new InvalidArgumentException( "Invalid extension type" );
		}
		$absDir = $this->getVar( 'IP' ) . "/$parentRelPath/$name";
		$relDir = "../$parentRelPath/$name";
		if ( !is_dir( $absDir ) ) {
			return Status::newFatal( 'config-extension-not-found', $name );
		}
		$jsonFile = $type . '.json';
		$fullJsonFile = "$absDir/$jsonFile";
		$isJson = file_exists( $fullJsonFile );
		$isPhp = false;
		if ( !$isJson ) {
			// Only fallback to PHP file if JSON doesn't exist
			$fullPhpFile = "$absDir/$name.php";
			$isPhp = file_exists( $fullPhpFile );
		}
		if ( !$isJson && !$isPhp ) {
			return Status::newFatal( 'config-extension-not-found', $name );
		}

		// Extension exists. Now see if there are screenshots
		$info = [];
		if ( is_dir( "$absDir/screenshots" ) ) {
			$paths = glob( "$absDir/screenshots/*.png" );
			foreach ( $paths as $path ) {
				$info['screenshots'][] = str_replace( $absDir, $relDir, $path );
			}
		}

		if ( $isJson ) {
			$jsonStatus = $this->readExtension( $fullJsonFile );
			if ( !$jsonStatus->isOK() ) {
				return $jsonStatus;
			}
			$info += $jsonStatus->value;
		}

		return Status::newGood( $info );
	}

	/**
	 * @param string $fullJsonFile
	 * @param array $extDeps
	 * @param array $skinDeps
	 *
	 * @return Status On success, an array of extension information is in $status->value. On
	 *    failure, the Status object will have an error list.
	 */
	private function readExtension( $fullJsonFile, $extDeps = [], $skinDeps = [] ) {
		$load = [
			$fullJsonFile => 1
		];
		if ( $extDeps ) {
			$extDir = $this->getVar( 'IP' ) . '/extensions';
			foreach ( $extDeps as $dep ) {
				$fname = "$extDir/$dep/extension.json";
				if ( !file_exists( $fname ) ) {
					return Status::newFatal( 'config-extension-not-found', $dep );
				}
				$load[$fname] = 1;
			}
		}
		if ( $skinDeps ) {
			$skinDir = $this->getVar( 'IP' ) . '/skins';
			foreach ( $skinDeps as $dep ) {
				$fname = "$skinDir/$dep/skin.json";
				if ( !file_exists( $fname ) ) {
					return Status::newFatal( 'config-extension-not-found', $dep );
				}
				$load[$fname] = 1;
			}
		}
		$registry = new ExtensionRegistry();
		try {
			$info = $registry->readFromQueue( $load );
		} catch ( ExtensionDependencyError $e ) {
			if ( $e->incompatibleCore || $e->incompatibleSkins
				|| $e->incompatibleExtensions
			) {
				// If something is incompatible with a dependency, we have no real
				// option besides skipping it
				return Status::newFatal( 'config-extension-dependency',
					basename( dirname( $fullJsonFile ) ), $e->getMessage() );
			} elseif ( $e->missingExtensions || $e->missingSkins ) {
				// There's an extension missing in the dependency tree,
				// so add those to the dependency list and try again
				$status = $this->readExtension(
					$fullJsonFile,
					array_merge( $extDeps, $e->missingExtensions ),
					array_merge( $skinDeps, $e->missingSkins )
				);
				if ( !$status->isOK() && !$status->hasMessage( 'config-extension-dependency' ) ) {
					$status = Status::newFatal( 'config-extension-dependency',
						basename( dirname( $fullJsonFile ) ), $status->getMessage() );
				}
				return $status;
			}
			// Some other kind of dependency error?
			return Status::newFatal( 'config-extension-dependency',
				basename( dirname( $fullJsonFile ) ), $e->getMessage() );
		}
		$ret = [];
		// The order of credits will be the order of $load,
		// so the first extension is the one we want to load,
		// everything else is a dependency
		$i = 0;
		foreach ( $info['credits'] as $name => $credit ) {
			$i++;
			if ( $i == 1 ) {
				// Extension we want to load
				continue;
			}
			$type = basename( $credit['path'] ) === 'skin.json' ? 'skins' : 'extensions';
			$ret['requires'][$type][] = $credit['name'];
		}
		$credits = array_values( $info['credits'] )[0];
		if ( isset( $credits['url'] ) ) {
			$ret['url'] = $credits['url'];
		}
		$ret['type'] = $credits['type'];

		return Status::newGood( $ret );
	}

	/**
	 * Returns a default value to be used for $wgDefaultSkin: normally the one set in DefaultSettings,
	 * but will fall back to another if the default skin is missing and some other one is present
	 * instead.
	 *
	 * @param string[] $skinNames Names of installed skins.
	 * @return string
	 */
	public function getDefaultSkin( array $skinNames ) {
		$defaultSkin = $GLOBALS['wgDefaultSkin'];
		if ( !$skinNames || in_array( $defaultSkin, $skinNames ) ) {
			return $defaultSkin;
		} else {
			return $skinNames[0];
		}
	}

	/**
	 * Installs the auto-detected extensions.
	 *
	 * @suppress SecurityCheck-OTHER It thinks $exts/$IP is user controlled but they are not.
	 * @return Status
	 */
	protected function includeExtensions() {
		// Marker for DatabaseUpdater::loadExtensions so we don't
		// double load extensions
		define( 'MW_EXTENSIONS_LOADED', true );

		$legacySchemaHooks = $this->getAutoExtensionLegacyHooks();
		$data = $this->getAutoExtensionData();
		if ( isset( $data['globals']['wgHooks']['LoadExtensionSchemaUpdates'] ) ) {
			$legacySchemaHooks = array_merge( $legacySchemaHooks,
				$data['globals']['wgHooks']['LoadExtensionSchemaUpdates'] );
		}
		$extDeprecatedHooks = $data['attributes']['DeprecatedHooks'] ?? [];

		$this->autoExtensionHookContainer = new HookContainer(
			new StaticHookRegistry(
				[ 'LoadExtensionSchemaUpdates' => $legacySchemaHooks ],
				$data['attributes']['Hooks'] ?? [],
				$extDeprecatedHooks
			),
			MediaWikiServices::getInstance()->getObjectFactory()
		);

		return Status::newGood();
	}

	/**
	 * Auto-detect extensions with an old style .php registration file, load
	 * the extensions, and return the merged $wgHooks array.
	 *
	 * @suppress SecurityCheck-OTHER It thinks $exts/$IP is user controlled but they are not.
	 * @return array
	 */
	protected function getAutoExtensionLegacyHooks() {
		$exts = $this->getVar( '_Extensions' );
		$installPath = $this->getVar( 'IP' );
		$files = [];
		foreach ( $exts as $e ) {
			if ( file_exists( "$installPath/extensions/$e/$e.php" ) ) {
				$files[] = "$installPath/extensions/$e/$e.php";
			}
		}

		if ( $files ) {
			return $this->includeExtensionFiles( $files );
		} else {
			return [];
		}
	}

	/**
	 * Include the specified extension PHP files. Populate $wgAutoloadClasses
	 * and return the LoadExtensionSchemaUpdates hooks.
	 *
	 * @param string[] $files
	 * @return array LoadExtensionSchemaUpdates legacy hooks
	 */
	protected function includeExtensionFiles( $files ) {
		global $IP;
		$IP = $this->getVar( 'IP' );

		/**
		 * We need to include DefaultSettings before including extensions to avoid
		 * warnings about unset variables. However, the only thing we really
		 * want here is $wgHooks['LoadExtensionSchemaUpdates']. This won't work
		 * if the extension has hidden hook registration in $wgExtensionFunctions,
		 * but we're not opening that can of worms
		 * @see https://phabricator.wikimedia.org/T28857
		 */
		require "$IP/includes/DefaultSettings.php";

		// phpcs:ignore MediaWiki.VariableAnalysis.UnusedGlobalVariables
		global $wgAutoloadClasses;
		foreach ( $files as $file ) {
			require_once $file;
		}

		// @phan-suppress-next-line PhanUndeclaredVariable,PhanCoalescingAlwaysNull $wgHooks is set by DefaultSettings
		$hooksWeWant = $wgHooks['LoadExtensionSchemaUpdates'] ?? [];

		// Ignore everyone else's hooks. Lord knows what someone might be doing
		// in ParserFirstCallInit (see T29171)
		return [ 'LoadExtensionSchemaUpdates' => $hooksWeWant ];
	}

	/**
	 * Auto-detect extensions with an extension.json file. Load the extensions,
	 * populate $wgAutoloadClasses and return the merged registry data.
	 *
	 * @return array
	 */
	protected function getAutoExtensionData() {
		$exts = $this->getVar( '_Extensions' );
		$installPath = $this->getVar( 'IP' );
		$queue = [];
		foreach ( $exts as $e ) {
			if ( file_exists( "$installPath/extensions/$e/extension.json" ) ) {
				$queue["$installPath/extensions/$e/extension.json"] = 1;
			}
		}

		$registry = new ExtensionRegistry();
		$data = $registry->readFromQueue( $queue );
		global $wgAutoloadClasses;
		$wgAutoloadClasses += $data['globals']['wgAutoloadClasses'];
		return $data;
	}

	/**
	 * Get the hook container previously populated by includeExtensions().
	 *
	 * @internal For use by DatabaseInstaller
	 * @since 1.35.1
	 * @return HookContainer
	 */
	public function getAutoExtensionHookContainer() {
		if ( !$this->autoExtensionHookContainer ) {
			throw new \Exception( __METHOD__ .
				': includeExtensions() has not been called' );
		}
		return $this->autoExtensionHookContainer;
	}

	/**
	 * Get an array of install steps. Should always be in the format of
	 * [
	 *   'name'     => 'someuniquename',
	 *   'callback' => [ $obj, 'method' ],
	 * ]
	 * There must be a config-install-$name message defined per step, which will
	 * be shown on install.
	 *
	 * @param DatabaseInstaller $installer DatabaseInstaller so we can make callbacks
	 * @return array[]
	 * @phan-return array<int,array{name:string,callback:array{0:object,1:string}}>
	 */
	protected function getInstallSteps( DatabaseInstaller $installer ) {
		$coreInstallSteps = [
			[ 'name' => 'database', 'callback' => [ $installer, 'setupDatabase' ] ],
			[ 'name' => 'tables', 'callback' => [ $installer, 'createTables' ] ],
			[ 'name' => 'tables-manual', 'callback' => [ $installer, 'createManualTables' ] ],
			[ 'name' => 'interwiki', 'callback' => [ $installer, 'populateInterwikiTable' ] ],
			[ 'name' => 'stats', 'callback' => [ $this, 'populateSiteStats' ] ],
			[ 'name' => 'keys', 'callback' => [ $this, 'generateKeys' ] ],
			[ 'name' => 'updates', 'callback' => [ $installer, 'insertUpdateKeys' ] ],
			[ 'name' => 'restore-services', 'callback' => [ $this, 'restoreServices' ] ],
			[ 'name' => 'sysop', 'callback' => [ $this, 'createSysop' ] ],
			[ 'name' => 'mainpage', 'callback' => [ $this, 'createMainpage' ] ],
		];

		// Build the array of install steps starting from the core install list,
		// then adding any callbacks that wanted to attach after a given step
		foreach ( $coreInstallSteps as $step ) {
			$this->installSteps[] = $step;
			if ( isset( $this->extraInstallSteps[$step['name']] ) ) {
				$this->installSteps = array_merge(
					$this->installSteps,
					$this->extraInstallSteps[$step['name']]
				);
			}
		}

		// Prepend any steps that want to be at the beginning
		if ( isset( $this->extraInstallSteps['BEGINNING'] ) ) {
			$this->installSteps = array_merge(
				$this->extraInstallSteps['BEGINNING'],
				$this->installSteps
			);
		}

		// Extensions should always go first, chance to tie into hooks and such
		if ( count( $this->getVar( '_Extensions' ) ) ) {
			array_unshift( $this->installSteps,
				[ 'name' => 'extensions', 'callback' => [ $this, 'includeExtensions' ] ]
			);
			$this->installSteps[] = [
				'name' => 'extension-tables',
				'callback' => [ $installer, 'createExtensionTables' ]
			];
		}

		return $this->installSteps;
	}

	/**
	 * Actually perform the installation.
	 *
	 * @param callable $startCB A callback array for the beginning of each step
	 * @param callable $endCB A callback array for the end of each step
	 *
	 * @return Status[] Array of Status objects
	 */
	public function performInstallation( $startCB, $endCB ) {
		$installResults = [];
		$installer = $this->getDBInstaller();
		$installer->preInstall();
		$steps = $this->getInstallSteps( $installer );
		foreach ( $steps as $stepObj ) {
			$name = $stepObj['name'];
			call_user_func_array( $startCB, [ $name ] );

			// Perform the callback step
			$status = call_user_func( $stepObj['callback'], $installer );

			// Output and save the results
			call_user_func( $endCB, $name, $status );
			$installResults[$name] = $status;

			// If we've hit some sort of fatal, we need to bail.
			// Callback already had a chance to do output above.
			if ( !$status->isOK() ) {
				break;
			}
		}
		if ( $status->isOK() ) {
			$this->showMessage(
				'config-install-db-success'
			);
			$this->setVar( '_InstallDone', true );
		}

		return $installResults;
	}

	/**
	 * Generate $wgSecretKey. Will warn if we had to use an insecure random source.
	 *
	 * @return Status
	 */
	public function generateKeys() {
		$keys = [ 'wgSecretKey' => 64 ];
		if ( strval( $this->getVar( 'wgUpgradeKey' ) ) === '' ) {
			$keys['wgUpgradeKey'] = 16;
		}

		return $this->doGenerateKeys( $keys );
	}

	/**
	 * Restore services that have been redefined in the early stage of installation
	 * @return Status
	 */
	public function restoreServices() {
		$this->resetMediaWikiServices( null, [
			'UserOptionsLookup' => function ( MediaWikiServices $services ) {
				return $services->get( 'UserOptionsManager' );
			}
		] );
		return Status::newGood();
	}

	/**
	 * Generate a secret value for variables using a secure generator.
	 *
	 * @param array $keys
	 * @return Status
	 */
	protected function doGenerateKeys( $keys ) {
		foreach ( $keys as $name => $length ) {
			$secretKey = MWCryptRand::generateHex( $length );
			$this->setVar( $name, $secretKey );
		}
		return Status::newGood();
	}

	/**
	 * Create the first user account, grant it sysop, bureaucrat and interface-admin rights
	 *
	 * @return Status
	 */
	protected function createSysop() {
		$name = $this->getVar( '_AdminName' );
		$user = User::newFromName( $name );

		if ( !$user ) {
			// We should've validated this earlier anyway!
			return Status::newFatal( 'config-admin-error-user', $name );
		}

		if ( $user->idForName() == 0 ) {
			$user->addToDatabase();

			$password = $this->getVar( '_AdminPassword' );
			$status = $user->changeAuthenticationData( [
				'username' => $user->getName(),
				'password' => $password,
				'retype' => $password,
			] );
			if ( !$status->isGood() ) {
				return Status::newFatal( 'config-admin-error-password',
					$name, $status->getWikiText( null, null, $this->getVar( '_UserLang' ) ) );
			}

			$user->addGroup( 'sysop' );
			$user->addGroup( 'bureaucrat' );
			$user->addGroup( 'interface-admin' );
			if ( $this->getVar( '_AdminEmail' ) ) {
				$user->setEmail( $this->getVar( '_AdminEmail' ) );
			}
			$user->saveSettings();

			// Update user count
			$ssUpdate = SiteStatsUpdate::factory( [ 'users' => 1 ] );
			$ssUpdate->doUpdate();
		}

		if ( $this->getVar( '_Subscribe' ) && $this->getVar( '_AdminEmail' ) ) {
			return $this->subscribeToMediaWikiAnnounce();
		}
		return Status::newGood();
	}

	/**
	 * @return Status
	 */
	private function subscribeToMediaWikiAnnounce() {
		$status = Status::newGood();
		$http = MediaWikiServices::getInstance()->getHttpRequestFactory();
		if ( !$http->canMakeRequests() ) {
			$status->warning( 'config-install-subscribe-fail',
				wfMessage( 'config-install-subscribe-notpossible' ) );
			return $status;
		}

		// Create subscription request
		$params = [ 'email' => $this->getVar( '_AdminEmail' ) ];
		$req = $http->create( self::MEDIAWIKI_ANNOUNCE_URL . 'anonymous_subscribe',
			[ 'method' => 'POST', 'postData' => $params ], __METHOD__ );

		// Add headers needed to pass Django's CSRF checks
		$token = str_repeat( 'a', 64 );
		$req->setHeader( 'Referer', self::MEDIAWIKI_ANNOUNCE_URL );
		$req->setHeader( 'Cookie', "csrftoken=$token" );
		$req->setHeader( 'X-CSRFToken', $token );

		// Send subscription request
		$reqStatus = $req->execute();
		if ( !$reqStatus->isOK() ) {
			$status->warning( 'config-install-subscribe-fail',
				Status::wrap( $reqStatus )->getMessage() );
			return $status;
		}

		// Was the request submitted successfully?
		// The status message is displayed after a redirect, using Django's messages
		// framework, so load the list summary page and look for the expected text.
		// (Though parsing the cookie set by the framework may be possible, it isn't
		// simple, since the format of the cookie has changed between versions.)
		$checkReq = $http->create( self::MEDIAWIKI_ANNOUNCE_URL, [], __METHOD__ );
		$checkReq->setCookieJar( $req->getCookieJar() );
		if ( !$checkReq->execute()->isOK() ) {
			$status->warning( 'config-install-subscribe-possiblefail' );
			return $status;
		}
		$html = $checkReq->getContent();
		if ( strpos( $html, 'Please check your inbox for further instructions' ) !== false ) {
			// Success
		} elseif ( strpos( $html, 'Member already subscribed' ) !== false ) {
			$status->warning( 'config-install-subscribe-alreadysubscribed' );
		} elseif ( strpos( $html, 'Subscription request already pending' ) !== false ) {
			$status->warning( 'config-install-subscribe-alreadypending' );
		} else {
			$status->warning( 'config-install-subscribe-possiblefail' );
		}
		return $status;
	}

	/**
	 * Insert Main Page with default content.
	 *
	 * @param DatabaseInstaller $installer
	 * @return Status
	 */
	protected function createMainpage( DatabaseInstaller $installer ) {
		$status = Status::newGood();
		$title = Title::newMainPage();
		if ( $title->exists() ) {
			$status->warning( 'config-install-mainpage-exists' );
			return $status;
		}
		try {
			$page = WikiPage::factory( $title );
			$content = new WikitextContent(
				wfMessage( 'mainpagetext' )->inContentLanguage()->text() . "\n\n" .
				wfMessage( 'mainpagedocfooter' )->inContentLanguage()->text()
			);

			$status = $page->doEditContent(
				$content,
				'',
				EDIT_NEW,
				false,
				User::newSystemUser( 'MediaWiki default' )
			);
		} catch ( Exception $e ) {
			// using raw, because $wgShowExceptionDetails can not be set yet
			$status->fatal( 'config-install-mainpage-failed', $e->getMessage() );
		}

		return $status;
	}

	/**
	 * Override the necessary bits of the config to run an installation.
	 */
	public static function overrideConfig() {
		// Use PHP's built-in session handling, since MediaWiki's
		// SessionHandler can't work before we have an object cache set up.
		if ( !defined( 'MW_NO_SESSION_HANDLER' ) ) {
			define( 'MW_NO_SESSION_HANDLER', 1 );
		}

		// Don't access the database
		$GLOBALS['wgUseDatabaseMessages'] = false;
		// Don't cache langconv tables
		$GLOBALS['wgLanguageConverterCacheType'] = CACHE_NONE;
		// Don't try to cache ResourceLoader dependencies in the database
		$GLOBALS['wgResourceLoaderUseObjectCacheForDeps'] = true;
		// Debug-friendly
		$GLOBALS['wgShowExceptionDetails'] = true;
		$GLOBALS['wgShowHostnames'] = true;
		// Don't break forms
		$GLOBALS['wgExternalLinkTarget'] = '_blank';

		// Allow multiple ob_flush() calls
		$GLOBALS['wgDisableOutputCompression'] = true;

		// Use a sensible cookie prefix (not my_wiki)
		$GLOBALS['wgCookiePrefix'] = 'mw_installer';

		// Some of the environment checks make shell requests, remove limits
		$GLOBALS['wgMaxShellMemory'] = 0;

		// Override the default CookieSessionProvider with a dummy
		// implementation that won't stomp on PHP's cookies.
		$GLOBALS['wgSessionProviders'] = [
			[
				'class' => InstallerSessionProvider::class,
				'args' => [ [
					'priority' => 1,
				] ]
			]
		];

		// Don't use the DB as the main stash
		$GLOBALS['wgMainStash'] = CACHE_NONE;

		// Don't try to use any object cache for SessionManager either.
		$GLOBALS['wgSessionCacheType'] = CACHE_NONE;

		// Set a dummy $wgServer to bypass the check in Setup.php, the
		// web installer will automatically detect it and not use this value.
		$GLOBALS['wgServer'] = 'https://🌻.invalid';
	}

	/**
	 * Add an installation step following the given step.
	 *
	 * @param array $callback A valid installation callback array, in this form:
	 *    [ 'name' => 'some-unique-name', 'callback' => [ $obj, 'function' ] ];
	 * @param string $findStep The step to find. Omit to put the step at the beginning
	 */
	public function addInstallStep( $callback, $findStep = 'BEGINNING' ) {
		$this->extraInstallSteps[$findStep][] = $callback;
	}

	/**
	 * Disable the time limit for execution.
	 * Some long-running pages (Install, Upgrade) will want to do this
	 */
	protected function disableTimeLimit() {
		Wikimedia\suppressWarnings();
		set_time_limit( 0 );
		Wikimedia\restoreWarnings();
	}
}
