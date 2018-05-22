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
 * @ingroup Deployment
 */
use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;

/**
 * This documentation group collects source code files with deployment functionality.
 *
 * @defgroup Deployment Deployment
 */

/**
 * Base installer class.
 *
 * This class provides the base for installation and update functionality
 * for both MediaWiki core and extensions.
 *
 * @ingroup Deployment
 * @since 1.17
 */
abstract class Installer {

	/**
	 * The oldest version of PCRE we can support.
	 *
	 * Defining this is necessary because PHP may be linked with a system version
	 * of PCRE, which may be older than that bundled with the minimum PHP version.
	 */
	const MINIMUM_PCRE_VERSION = '7.2';

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
		'oracle',
		'mssql',
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
		'envCheckBrokenXML',
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

		// $wgLogo is probably wrong (T50084); set something that will work.
		// Single quotes work fine here, as LocalSettingsGenerator outputs this unescaped.
		'wgLogo' => '$wgResourceBasePath/resources/assets/wiki.png',
		'wgAuthenticationTokenVersion' => 1,
	];

	/**
	 * The actual list of installation steps. This will be initialized by getInstallSteps()
	 *
	 * @var array
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
		'apc' => 'apc_fetch',
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
	 * URL to mediawiki-announce subscription
	 */
	protected $mediaWikiAnnounceUrl =
		'https://lists.wikimedia.org/mailman/subscribe/mediawiki-announce';

	/**
	 * Supported language codes for Mailman
	 */
	protected $mediaWikiAnnounceLanguages = [
		'ca', 'cs', 'da', 'de', 'en', 'es', 'et', 'eu', 'fi', 'fr', 'hr', 'hu',
		'it', 'ja', 'ko', 'lt', 'nl', 'no', 'pl', 'pt', 'pt-br', 'ro', 'ru',
		'sl', 'sr', 'sv', 'tr', 'uk'
	];

	/**
	 * UI interface for displaying a short message
	 * The parameters are like parameters to wfMessage().
	 * The messages will be in wikitext format, which will be converted to an
	 * output format such as HTML or text before being sent to the user.
	 * @param string $msg
	 */
	abstract public function showMessage( $msg /*, ... */ );

	/**
	 * Same as showMessage(), but for displaying errors
	 * @param string $msg
	 */
	abstract public function showError( $msg /*, ... */ );

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
		global $wgMemc, $wgUser, $wgObjectCaches;

		$defaultConfig = new GlobalVarConfig(); // all the stuff from DefaultSettings.php
		$installerConfig = self::getInstallerConfig( $defaultConfig );

		// Reset all services and inject config overrides
		MediaWiki\MediaWikiServices::resetGlobalInstance( $installerConfig );

		// Don't attempt to load user language options (T126177)
		// This will be overridden in the web installer with the user-specified language
		RequestContext::getMain()->setLanguage( 'en' );

		// Disable the i18n cache
		// TODO: manage LocalisationCache singleton in MediaWikiServices
		Language::getLocalisationCache()->disableBackend();

		// Disable all global services, since we don't have any configuration yet!
		MediaWiki\MediaWikiServices::disableStorageBackend();

		// Disable object cache (otherwise CACHE_ANYTHING will try CACHE_DB and
		// SqlBagOStuff will then throw since we just disabled wfGetDB)
		$wgObjectCaches = MediaWikiServices::getInstance()->getMainConfig()->get( 'ObjectCaches' );
		$wgMemc = ObjectCache::getInstance( CACHE_NONE );

		// Having a user with id = 0 safeguards us from DB access via User::loadOptions().
		$wgUser = User::newFromId( 0 );
		RequestContext::getMain()->setUser( $wgUser );

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
		$this->parserOptions = new ParserOptions( $wgUser ); // language will be wrong :(
		// Don't try to access DB before user language is initialised
		$this->setParserLanguage( Language::factory( 'en' ) );
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
		// Php version has already been checked by entry scripts
		// Show message here for information purposes
		if ( wfIsHHVM() ) {
			$this->showMessage( 'config-env-hhvm', HHVM_VERSION );
		} else {
			$this->showMessage( 'config-env-php', PHP_VERSION );
		}

		$good = true;
		// Must go here because an old version of PCRE can prevent other checks from completing
		list( $pcreVersion ) = explode( ' ', PCRE_VERSION, 2 );
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
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getVar( $name, $default = null ) {
		if ( !isset( $this->settings[$name] ) ) {
			return $default;
		} else {
			return $this->settings[$name];
		}
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
		$group = posix_getpwuid( $gid )['name'];

		return $group;
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
		global $wgParser;

		try {
			$out = $wgParser->parse( $text, $this->parserTitle, $this->parserOptions, $lineStart );
			$html = $out->getText( [
				'enableSectionEditLinks' => false,
				'unwrap' => true,
			] );
		} catch ( MediaWiki\Services\ServiceDisabledException $e ) {
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
			__METHOD__, 'IGNORE'
		);

		return Status::newGood();
	}

	/**
	 * Environment check for DB types.
	 * @return bool
	 */
	protected function envCheckDB() {
		global $wgLang;

		$allNames = [];

		// Messages: config-type-mysql, config-type-postgres, config-type-oracle,
		// config-type-sqlite
		foreach ( self::getDBTypes() as $name ) {
			$allNames[] = wfMessage( "config-type-$name" )->text();
		}

		$databases = $this->getCompiledDBs();

		$databases = array_flip( $databases );
		foreach ( array_keys( $databases ) as $db ) {
			$installer = $this->getDBInstaller( $db );
			$status = $installer->checkPrerequisites();
			if ( !$status->isGood() ) {
				$this->showStatusMessage( $status );
			}
			if ( !$status->isOK() ) {
				unset( $databases[$db] );
			}
		}
		$databases = array_flip( $databases );
		if ( !$databases ) {
			$this->showError( 'config-no-db', $wgLang->commaList( $allNames ), count( $allNames ) );

			// @todo FIXME: This only works for the web installer!
			return false;
		}

		return true;
	}

	/**
	 * Some versions of libxml+PHP break < and > encoding horribly
	 * @return bool
	 */
	protected function envCheckBrokenXML() {
		$test = new PhpXmlBugTester();
		if ( !$test->ok ) {
			$this->showError( 'config-brokenlibxml' );

			return false;
		}

		return true;
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
		$regexprop = preg_replace( '/\p{Zs}/u', '', "-\xE3\x80\x80-" );
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
			$key = 'config-no-cache-apcu';
			$this->showMessage( $key );
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

			return true;
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
		$result = Shell::command( '/usr/bin/locale', '-a' )
			->execute();

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
	 * a warning because it decreases ResourceLoader performance.
	 * @return bool
	 */
	protected function envCheckSuhosinMaxValueLength() {
		$maxValueLength = ini_get( 'suhosin.get.max_value_length' );
		if ( $maxValueLength > 0 && $maxValueLength < 1024 ) {
			// Only warn if the value is below the sane 1024
			$this->showMessage( 'config-suhosin-max-value-length', $maxValueLength );
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
	 * Convert a hex string representing a Unicode code point to that code point.
	 * @param string $c
	 * @return string|false
	 */
	protected function unicodeChar( $c ) {
		$c = hexdec( $c );
		if ( $c <= 0x7F ) {
			return chr( $c );
		} elseif ( $c <= 0x7FF ) {
			return chr( 0xC0 | $c >> 6 ) . chr( 0x80 | $c & 0x3F );
		} elseif ( $c <= 0xFFFF ) {
			return chr( 0xE0 | $c >> 12 ) . chr( 0x80 | $c >> 6 & 0x3F ) .
				chr( 0x80 | $c & 0x3F );
		} elseif ( $c <= 0x10FFFF ) {
			return chr( 0xF0 | $c >> 18 ) . chr( 0x80 | $c >> 12 & 0x3F ) .
				chr( 0x80 | $c >> 6 & 0x3F ) .
				chr( 0x80 | $c & 0x3F );
		} else {
			return false;
		}
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
		$not_normal_c = $this->unicodeChar( "FA6C" );
		$normal_c = $this->unicodeChar( "242EE" );

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
				"<?php echo 'ex' . 'ec';",
				"#!/var/env php5\n<?php echo 'ex' . 'ec';",
			],
		];

		// it would be good to check other popular languages here, but it'll be slow.

		Wikimedia\suppressWarnings();

		foreach ( $scriptTypes as $ext => $contents ) {
			foreach ( $contents as $source ) {
				$file = 'exectest.' . $ext;

				if ( !file_put_contents( $dir . $file, $source ) ) {
					break;
				}

				try {
					$text = Http::get( $url . $file, [ 'timeout' => 3 ], __METHOD__ );
				} catch ( Exception $e ) {
					// Http::get throws with allow_url_fopen = false and no curl extension.
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
	 * Finds extensions that follow the format /$directory/Name/Name.php,
	 * and returns an array containing the value for 'Name' for each found extension.
	 *
	 * Reasonable values for $directory include 'extensions' (the default) and 'skins'.
	 *
	 * @param string $directory Directory to search in
	 * @return array [ $extName => [ 'screenshots' => [ '...' ] ]
	 */
	public function findExtensions( $directory = 'extensions' ) {
		if ( $this->getVar( 'IP' ) === null ) {
			return [];
		}

		$extDir = $this->getVar( 'IP' ) . '/' . $directory;
		if ( !is_readable( $extDir ) || !is_dir( $extDir ) ) {
			return [];
		}

		// extensions -> extension.json, skins -> skin.json
		$jsonFile = substr( $directory, 0, strlen( $directory ) - 1 ) . '.json';

		$dh = opendir( $extDir );
		$exts = [];
		while ( ( $file = readdir( $dh ) ) !== false ) {
			if ( !is_dir( "$extDir/$file" ) ) {
				continue;
			}
			$fullJsonFile = "$extDir/$file/$jsonFile";
			$isJson = file_exists( $fullJsonFile );
			$isPhp = false;
			if ( !$isJson ) {
				// Only fallback to PHP file if JSON doesn't exist
				$fullPhpFile = "$extDir/$file/$file.php";
				$isPhp = file_exists( $fullPhpFile );
			}
			if ( $isJson || $isPhp ) {
				// Extension exists. Now see if there are screenshots
				$exts[$file] = [];
				if ( is_dir( "$extDir/$file/screenshots" ) ) {
					$paths = glob( "$extDir/$file/screenshots/*.png" );
					foreach ( $paths as $path ) {
						$exts[$file]['screenshots'][] = str_replace( $extDir, "../$directory", $path );
					}

				}
			}
			if ( $isJson ) {
				$info = $this->readExtension( $fullJsonFile );
				if ( $info === false ) {
					continue;
				}
				$exts[$file] += $info;
			}
		}
		closedir( $dh );
		uksort( $exts, 'strnatcasecmp' );

		return $exts;
	}

	/**
	 * @param string $fullJsonFile
	 * @param array $extDeps
	 * @param array $skinDeps
	 *
	 * @return array|bool False if this extension can't be loaded
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
					return false;
				}
				$load[$fname] = 1;
			}
		}
		if ( $skinDeps ) {
			$skinDir = $this->getVar( 'IP' ) . '/skins';
			foreach ( $skinDeps as $dep ) {
				$fname = "$skinDir/$dep/skin.json";
				if ( !file_exists( $fname ) ) {
					return false;
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
				return false;
			} elseif ( $e->missingExtensions || $e->missingSkins ) {
				// There's an extension missing in the dependency tree,
				// so add those to the dependency list and try again
				return $this->readExtension(
					$fullJsonFile,
					array_merge( $extDeps, $e->missingExtensions ),
					array_merge( $skinDeps, $e->missingSkins )
				);
			}
			// Some other kind of dependency error?
			return false;
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

		return $ret;
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
	 * @return Status
	 */
	protected function includeExtensions() {
		global $IP;
		$exts = $this->getVar( '_Extensions' );
		$IP = $this->getVar( 'IP' );

		// Marker for DatabaseUpdater::loadExtensions so we don't
		// double load extensions
		define( 'MW_EXTENSIONS_LOADED', true );

		/**
		 * We need to include DefaultSettings before including extensions to avoid
		 * warnings about unset variables. However, the only thing we really
		 * want here is $wgHooks['LoadExtensionSchemaUpdates']. This won't work
		 * if the extension has hidden hook registration in $wgExtensionFunctions,
		 * but we're not opening that can of worms
		 * @see https://phabricator.wikimedia.org/T28857
		 */
		global $wgAutoloadClasses;
		$wgAutoloadClasses = [];
		$queue = [];

		require "$IP/includes/DefaultSettings.php";

		foreach ( $exts as $e ) {
			if ( file_exists( "$IP/extensions/$e/extension.json" ) ) {
				$queue["$IP/extensions/$e/extension.json"] = 1;
			} else {
				require_once "$IP/extensions/$e/$e.php";
			}
		}

		$registry = new ExtensionRegistry();
		$data = $registry->readFromQueue( $queue );
		$wgAutoloadClasses += $data['autoload'];

		$hooksWeWant = isset( $wgHooks['LoadExtensionSchemaUpdates'] ) ?
			/** @suppress PhanUndeclaredVariable $wgHooks is set by DefaultSettings */
			$wgHooks['LoadExtensionSchemaUpdates'] : [];

		if ( isset( $data['globals']['wgHooks']['LoadExtensionSchemaUpdates'] ) ) {
			$hooksWeWant = array_merge_recursive(
				$hooksWeWant,
				$data['globals']['wgHooks']['LoadExtensionSchemaUpdates']
			);
		}
		// Unset everyone else's hooks. Lord knows what someone might be doing
		// in ParserFirstCallInit (see T29171)
		$GLOBALS['wgHooks'] = [ 'LoadExtensionSchemaUpdates' => $hooksWeWant ];

		return Status::newGood();
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
	 * @return array
	 */
	protected function getInstallSteps( DatabaseInstaller $installer ) {
		$coreInstallSteps = [
			[ 'name' => 'database', 'callback' => [ $installer, 'setupDatabase' ] ],
			[ 'name' => 'tables', 'callback' => [ $installer, 'createTables' ] ],
			[ 'name' => 'interwiki', 'callback' => [ $installer, 'populateInterwikiTable' ] ],
			[ 'name' => 'stats', 'callback' => [ $this, 'populateSiteStats' ] ],
			[ 'name' => 'keys', 'callback' => [ $this, 'generateKeys' ] ],
			[ 'name' => 'updates', 'callback' => [ $installer, 'insertUpdateKeys' ] ],
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
	 * @return array Array of Status objects
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
			if ( !$status->isOk() ) {
				break;
			}
		}
		if ( $status->isOk() ) {
			$this->showMessage(
				'config-install-success',
				$this->getVar( 'wgServer' ),
				$this->getVar( 'wgScriptPath' )
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
	 * Generate a secret value for variables using our CryptRand generator.
	 * Produce a warning if the random source was insecure.
	 *
	 * @param array $keys
	 * @return Status
	 */
	protected function doGenerateKeys( $keys ) {
		$status = Status::newGood();

		$strong = true;
		foreach ( $keys as $name => $length ) {
			$secretKey = MWCryptRand::generateHex( $length, true );
			if ( !MWCryptRand::wasStrong() ) {
				$strong = false;
			}

			$this->setVar( $name, $secretKey );
		}

		if ( !$strong ) {
			$names = array_keys( $keys );
			$names = preg_replace( '/^(.*)$/', '\$$1', $names );
			global $wgLang;
			$status->warning( 'config-insecure-keys', $wgLang->listToText( $names ), count( $names ) );
		}

		return $status;
	}

	/**
	 * Create the first user account, grant it sysop and bureaucrat rights
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

			try {
				$user->setPassword( $this->getVar( '_AdminPassword' ) );
			} catch ( PasswordError $pwe ) {
				return Status::newFatal( 'config-admin-error-password', $name, $pwe->getMessage() );
			}

			$user->addGroup( 'sysop' );
			$user->addGroup( 'bureaucrat' );
			if ( $this->getVar( '_AdminEmail' ) ) {
				$user->setEmail( $this->getVar( '_AdminEmail' ) );
			}
			$user->saveSettings();

			// Update user count
			$ssUpdate = SiteStatsUpdate::factory( [ 'users' => 1 ] );
			$ssUpdate->doUpdate();
		}
		$status = Status::newGood();

		if ( $this->getVar( '_Subscribe' ) && $this->getVar( '_AdminEmail' ) ) {
			$this->subscribeToMediaWikiAnnounce( $status );
		}

		return $status;
	}

	/**
	 * @param Status $s
	 */
	private function subscribeToMediaWikiAnnounce( Status $s ) {
		$params = [
			'email' => $this->getVar( '_AdminEmail' ),
			'language' => 'en',
			'digest' => 0
		];

		// Mailman doesn't support as many languages as we do, so check to make
		// sure their selected language is available
		$myLang = $this->getVar( '_UserLang' );
		if ( in_array( $myLang, $this->mediaWikiAnnounceLanguages ) ) {
			$myLang = $myLang == 'pt-br' ? 'pt_BR' : $myLang; // rewrite to Mailman's pt_BR
			$params['language'] = $myLang;
		}

		if ( MWHttpRequest::canMakeRequests() ) {
			$res = MWHttpRequest::factory( $this->mediaWikiAnnounceUrl,
				[ 'method' => 'POST', 'postData' => $params ], __METHOD__ )->execute();
			if ( !$res->isOK() ) {
				$s->warning( 'config-install-subscribe-fail', $res->getMessage() );
			}
		} else {
			$s->warning( 'config-install-subscribe-notpossible' );
		}
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

			$status = $page->doEditContent( $content,
				'',
				EDIT_NEW,
				false,
				User::newFromName( 'MediaWiki default' )
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
		define( 'MW_NO_SESSION_HANDLER', 1 );

		// Don't access the database
		$GLOBALS['wgUseDatabaseMessages'] = false;
		// Don't cache langconv tables
		$GLOBALS['wgLanguageConverterCacheType'] = CACHE_NONE;
		// Debug-friendly
		$GLOBALS['wgShowExceptionDetails'] = true;
		// Don't break forms
		$GLOBALS['wgExternalLinkTarget'] = '_blank';

		// Extended debugging
		$GLOBALS['wgShowSQLErrors'] = true;
		$GLOBALS['wgShowDBErrorBacktrace'] = true;

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

		// Don't try to use any object cache for SessionManager either.
		$GLOBALS['wgSessionCacheType'] = CACHE_NONE;
	}

	/**
	 * Add an installation step following the given step.
	 *
	 * @param callable $callback A valid installation callback array, in this form:
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
