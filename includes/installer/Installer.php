<?php
/**
 * Base code for MediaWiki installer.
 *
 * @file
 * @ingroup Deployment
 */

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
	 * TODO: make protected?
	 *
	 * @var array
	 */
	public $settings;

	/**
	 * Cached DB installer instances, access using getDBInstaller().
	 *
	 * @var array
	 */
	protected $dbInstallers = array();

	/**
	 * Minimum memory size in MB.
	 *
	 * @var integer
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
	protected static $dbTypes = array(
		'mysql',
		'postgres',
		'oracle',
		'sqlite',
	);

	/**
	 * A list of environment check methods called by doEnvironmentChecks().
	 * These may output warnings using showMessage(), and/or abort the
	 * installation process by returning false.
	 *
	 * @var array
	 */
	protected $envChecks = array(
		'envCheckMediaWikiVersion',
		'envCheckDB',
		'envCheckRegisterGlobals',
		'envCheckBrokenXML',
		'envCheckPHP531',
		'envCheckMagicQuotes',
		'envCheckMagicSybase',
		'envCheckMbstring',
		'envCheckZE1',
		'envCheckSafeMode',
		'envCheckXML',
		'envCheckPCRE',
		'envCheckMemory',
		'envCheckCache',
		'envCheckDiff3',
		'envCheckGraphics',
		'envCheckPath',
		'envCheckExtension',
		'envCheckShellLocale',
		'envCheckUploadsDirectory',
		'envCheckLibicu'
	);

	/**
	 * UI interface for displaying a short message
	 * The parameters are like parameters to wfMsg().
	 * The messages will be in wikitext format, which will be converted to an
	 * output format such as HTML or text before being sent to the user.
	 */
	public abstract function showMessage( $msg /*, ... */ );

	/**
	 * Constructor, always call this from child classes.
	 */
	public function __construct() {
		// Disable the i18n cache and LoadBalancer
		Language::getLocalisationCache()->disableBackend();
		LBFactory::disableBackend();
	}

	/**
	 * Get a list of known DB types.
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
	 * @return boolean
	 */
	public function doEnvironmentChecks() {
		$this->showMessage( 'config-env-php', phpversion() );

		$good = true;

		foreach ( $this->envChecks as $check ) {
			$status = $this->$check();
			if ( $status === false ) {
				$good = false;
			}
		}

		$this->setVar( '_Environment', $good );

		if ( $good ) {
			$this->showMessage( 'config-env-good' );
		} else {
			$this->showMessage( 'config-env-bad' );
		}

		return $good;
	}

	/**
	 * Set a MW configuration variable, or internal installer configuration variable.
	 *
	 * @param $name String
	 * @param $value Mixed
	 */
	public function setVar( $name, $value ) {
		$this->settings[$name] = $value;
	}

	/**
	 * Get an MW configuration variable, or internal installer configuration variable.
	 * The defaults come from $GLOBALS (ultimately DefaultSettings.php).
	 * Installer variables are typically prefixed by an underscore.
	 *
	 * @param $name String
	 * @param $default Mixed
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
	 * Get an instance of DatabaseInstaller for the specified DB type.
	 *
	 * @param $type Mixed: DB installer for which is needed, false to use default.
	 *
	 * @return DatabaseInstaller
	 */
	public function getDBInstaller( $type = false ) {
		if ( !$type ) {
			$type = $this->getVar( 'wgDBtype' );
		}

		$type = strtolower( $type );

		if ( !isset( $this->dbInstallers[$type] ) ) {
			$class = ucfirst( $type ). 'Installer';
			$this->dbInstallers[$type] = new $class( $this );
		}

		return $this->dbInstallers[$type];
	}

	/**
	 * Determine if LocalSettings exists. If it does, return an appropriate
	 * status for whether we should can upgrade or not.
	 *
	 * @return Status
	 */
	public function getLocalSettingsStatus() {
		global $IP;

		$status = Status::newGood();

		wfSuppressWarnings();
		$ls = file_exists( "$IP/LocalSettings.php" );
		wfRestoreWarnings();

		if( $ls ) {
			require( "$IP/includes/DefaultSettings.php" );
			require_once( "$IP/LocalSettings.php" );
			$vars = get_defined_vars();
			if( isset( $vars['wgUpgradeKey'] ) && $vars['wgUpgradeKey'] ) {
				$status->warning( 'config-localsettings-upgrade' );
				$this->setVar( '_UpgradeKey', $vars['wgUpgradeKey' ] );
			} else {
				$status->fatal( 'config-localsettings-noupgrade' );
			}
		}

		return $status;
	}

	/**
	 * Get a fake password for sending back to the user in HTML.
	 * This is a security mechanism to avoid compromise of the password in the
	 * event of session ID compromise.
	 *
	 * @param $realPassword String
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
	 * @param $name String
	 * @param $value Mixed
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
	 * This is used to advice the user that he should chgrp his config/data/images directory as the
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
		$getpwuid = posix_getpwuid( $gid );
		$group = $getpwuid['name'];

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
	 * But in case a translator decides to throw in a #ifexist or internal link or
	 * whatever, this function is guarded to catch attempted DB access and to present
	 * some fallback text.
	 *
	 * @param $text String
	 * @param $lineStart Boolean
	 * @return String
	 */
	public function parse( $text, $lineStart = false ) {
		global $wgParser;

		try {
			$out = $wgParser->parse( $text, $this->parserTitle, $this->parserOptions, $lineStart );
			$html = $out->getText();
		} catch ( DBAccessError $e ) {
			$html = '<!--DB access attempted during parse-->  ' . htmlspecialchars( $text );

			if ( !empty( $this->debug ) ) {
				$html .= "<!--\n" . $e->getTraceAsString() . "\n-->";
			}
		}

		return $html;
	}

	/**
	 * TODO: document
	 *
	 * @param $installer DatabaseInstaller
	 *
	 * @return Status
	 */
	public function installTables( DatabaseInstaller &$installer ) {
		$status = $installer->createTables();

		if( $status->isOK() ) {
			LBFactory::enableBackend();
		}
		
		return $status;
	}

	/**
	 * Exports all wg* variables stored by the installer into global scope.
	 */
	public function exportVars() {
		foreach ( $this->settings as $name => $value ) {
			if ( substr( $name, 0, 2 ) == 'wg' ) {
				$GLOBALS[$name] = $value;
			}
		}
	}

	/**
	 * Check if we're installing the latest version.
	 */
	public function envCheckMediaWikiVersion() {
		global $wgVersion;

		if( !$this->getVar( '_ExternalHTTP' ) ) {
			$this->showMessage( 'config-env-latest-disabled' );
			return;
		}

		$repository = wfGetRepository();
		$currentVersion = $repository->getLatestCoreVersion();

		if ( $currentVersion === false ) {
			# For when the request is successful but there's e.g. some silly man in
			# the middle firewall blocking us, e.g. one of those annoying airport ones
			$this->showMessage( 'config-env-latest-can-not-check', $repository->getLocation() );
			return;
		}

		if( version_compare( $wgVersion, $currentVersion, '<' ) ) {
			$this->showMessage( 'config-env-latest-old' );
			// FIXME: this only works for the web installer!
			$this->showHelpBox( 'config-env-latest-help', $wgVersion, $currentVersion );
		} elseif( version_compare( $wgVersion, $currentVersion, '>' ) ) {
			$this->showMessage( 'config-env-latest-new' );
		} else {
			$this->showMessage( 'config-env-latest-ok' );
		}
	}

	/**
	 * Environment check for DB types.
	 */
	public function envCheckDB() {
		global $wgLang;

		$compiledDBs = array();
		$goodNames = array();
		$allNames = array();

		foreach ( self::getDBTypes() as $name ) {
			$db = $this->getDBInstaller( $name );
			$readableName = wfMsg( 'config-type-' . $name );

			if ( $db->isCompiled() ) {
				$compiledDBs[] = $name;
				$goodNames[] = $readableName;
			}

			$allNames[] = $readableName;
		}

		$this->setVar( '_CompiledDBs', $compiledDBs );

		if ( !$compiledDBs ) {
			$this->showMessage( 'config-no-db' );
			// FIXME: this only works for the web installer!
			$this->showHelpBox( 'config-no-db-help', $wgLang->commaList( $allNames ) );
			return false;
		}

		$this->showMessage( 'config-have-db', $wgLang->listToText( $goodNames ), count( $goodNames ) );

		// Check for FTS3 full-text search module
		$sqlite = $this->getDBInstaller( 'sqlite' );
		if ( $sqlite->isCompiled() ) {
			$db = new DatabaseSqliteStandalone( ':memory:' );
			$this->showMessage( $db->getFulltextSearchModule() == 'FTS3'
				? 'config-have-fts3'
				: 'config-no-fts3'
			);
		}
	}

	/**
	 * Environment check for register_globals.
	 */
	public function envCheckRegisterGlobals() {
		if( wfIniGetBool( "magic_quotes_runtime" ) ) {
			$this->showMessage( 'config-register-globals' );
		}
	}

	/**
	 * Some versions of libxml+PHP break < and > encoding horribly
	 */
	public function envCheckBrokenXML() {
		$test = new PhpXmlBugTester();
		if ( !$test->ok ) {
			$this->showMessage( 'config-brokenlibxml' );
			return false;
		}
	}

	/**
	 * Test PHP (probably 5.3.1, but it could regress again) to make sure that
	 * reference parameters to __call() are not converted to null
	 */
	public function envCheckPHP531() {
		$test = new PhpRefCallBugTester;
		$test->execute();
		if ( !$test->ok ) {
			$this->showMessage( 'config-using531' );
			return false;
		}
	}

	/**
	 * Environment check for magic_quotes_runtime.
	 */
	public function envCheckMagicQuotes() {
		if( wfIniGetBool( "magic_quotes_runtime" ) ) {
			$this->showMessage( 'config-magic-quotes-runtime' );
			return false;
		}
	}

	/**
	 * Environment check for magic_quotes_sybase.
	 */
	public function envCheckMagicSybase() {
		if ( wfIniGetBool( 'magic_quotes_sybase' ) ) {
			$this->showMessage( 'config-magic-quotes-sybase' );
			return false;
		}
	}

	/**
	 * Environment check for mbstring.func_overload.
	 */
	public function envCheckMbstring() {
		if ( wfIniGetBool( 'mbstring.func_overload' ) ) {
			$this->showMessage( 'config-mbstring' );
			return false;
		}
	}

	/**
	 * Environment check for zend.ze1_compatibility_mode.
	 */
	public function envCheckZE1() {
		if ( wfIniGetBool( 'zend.ze1_compatibility_mode' ) ) {
			$this->showMessage( 'config-ze1' );
			return false;
		}
	}

	/**
	 * Environment check for safe_mode.
	 */
	public function envCheckSafeMode() {
		if ( wfIniGetBool( 'safe_mode' ) ) {
			$this->setVar( '_SafeMode', true );
			$this->showMessage( 'config-safe-mode' );
		}
	}

	/**
	 * Environment check for the XML module.
	 */
	public function envCheckXML() {
		if ( !function_exists( "utf8_encode" ) ) {
			$this->showMessage( 'config-xml-bad' );
			return false;
		}
		$this->showMessage( 'config-xml-good' );
	}

	/**
	 * Environment check for the PCRE module.
	 */
	public function envCheckPCRE() {
		if ( !function_exists( 'preg_match' ) ) {
			$this->showMessage( 'config-pcre' );
			return false;
		}
	}

	/**
	 * Environment check for available memory.
	 */
	public function envCheckMemory() {
		$limit = ini_get( 'memory_limit' );

		if ( !$limit || $limit == -1 ) {
			$this->showMessage( 'config-memory-none' );
			return true;
		}

		$n = intval( $limit );

		if( preg_match( '/^([0-9]+)[Mm]$/', trim( $limit ), $m ) ) {
			$n = intval( $m[1] * ( 1024 * 1024 ) );
		}

		if( $n < $this->minMemorySize * 1024 * 1024 ) {
			$newLimit = "{$this->minMemorySize}M";

			if( ini_set( "memory_limit", $newLimit ) === false ) {
				$this->showMessage( 'config-memory-bad', $limit );
			} else {
				$this->showMessage( 'config-memory-raised', $limit, $newLimit );
				$this->setVar( '_RaiseMemory', true );
			}
		} else {
			$this->showMessage( 'config-memory-ok', $limit );
		}
	}

	/**
	 * Environment check for compiled object cache types.
	 */
	public function envCheckCache() {
		$caches = array();

		foreach ( $this->objectCaches as $name => $function ) {
			if ( function_exists( $function ) ) {
				$caches[$name] = true;
				$this->showMessage( 'config-' . $name );
			}
		}

		if ( !$caches ) {
			$this->showMessage( 'config-no-cache' );
		}

		$this->setVar( '_Caches', $caches );
	}

	/**
	 * Search for GNU diff3.
	 */
	public function envCheckDiff3() {
		$names = array( "gdiff3", "diff3", "diff3.exe" );
		$versionInfo = array( '$1 --version 2>&1', 'GNU diffutils' );

		$diff3 = $this->locateExecutableInDefaultPaths( $names, $versionInfo );

		if ( $diff3 ) {
			$this->showMessage( 'config-diff3-good', $diff3 );
			$this->setVar( 'wgDiff3', $diff3 );
		} else {
			$this->setVar( 'wgDiff3', false );
			$this->showMessage( 'config-diff3-bad' );
		}
	}

	/**
	 * Environment check for ImageMagick and GD.
	 */
	public function envCheckGraphics() {
		$names = array( wfIsWindows() ? 'convert.exe' : 'convert' );
		$convert = $this->locateExecutableInDefaultPaths( $names, array( '$1 -version', 'ImageMagick' ) );

		if ( $convert ) {
			$this->setVar( 'wgImageMagickConvertCommand', $convert );
			$this->showMessage( 'config-imagemagick', $convert );
			return true;
		} elseif ( function_exists( 'imagejpeg' ) ) {
			$this->showMessage( 'config-gd' );
			return true;
		} else {
			$this->showMessage( 'no-scaling' );
		}
	}

	/**
	 * Environment check for setting $IP and $wgScriptPath.
	 */
	public function envCheckPath() {
		global $IP;
		$IP = dirname( dirname( dirname( __FILE__ ) ) );

		$this->setVar( 'IP', $IP );
		$this->showMessage( 'config-dir', $IP );

		// PHP_SELF isn't available sometimes, such as when PHP is CGI but
		// cgi.fix_pathinfo is disabled. In that case, fall back to SCRIPT_NAME
		// to get the path to the current script... hopefully it's reliable. SIGH
		if ( !empty( $_SERVER['PHP_SELF'] ) ) {
			$path = $_SERVER['PHP_SELF'];
		} elseif ( !empty( $_SERVER['SCRIPT_NAME'] ) ) {
			$path = $_SERVER['SCRIPT_NAME'];
		} elseif ( $this->getVar( 'wgScriptPath' ) ) {
			// Some kind soul has set it for us already (e.g. debconf)
			return true;
		} else {
			$this->showMessage( 'config-no-uri' );
			return false;
		}

		$uri = preg_replace( '{^(.*)/config.*$}', '$1', $path );
		$this->setVar( 'wgScriptPath', $uri );
		$this->showMessage( 'config-uri', $uri );
	}

	/**
	 * Environment check for setting the preferred PHP file extension.
	 */
	public function envCheckExtension() {
		// FIXME: detect this properly
		if ( defined( 'MW_INSTALL_PHP5_EXT' ) ) {
			$ext = 'php5';
		} else {
			$ext = 'php';
		}

		$this->setVar( 'wgScriptExtension', ".$ext" );
		$this->showMessage( 'config-file-extension', $ext );
	}

	/**
	 * TODO: document
	 */
	public function envCheckShellLocale() {
		$os = php_uname( 's' );
		$supported = array( 'Linux', 'SunOS', 'HP-UX', 'Darwin' ); # Tested these

		if ( !in_array( $os, $supported ) ) {
			return true;
		}

		# Get a list of available locales.
		$ret = false;
		$lines = wfShellExec( '/usr/bin/locale -a', $ret );

		if ( $ret ) {
			return true;
		}

		$lines = wfArrayMap( 'trim', explode( "\n", $lines ) );
		$candidatesByLocale = array();
		$candidatesByLang = array();

		foreach ( $lines as $line ) {
			if ( $line === '' ) {
				continue;
			}

			if ( !preg_match( '/^([a-zA-Z]+)(_[a-zA-Z]+|)\.(utf8|UTF-8)(@[a-zA-Z_]*|)$/i', $line, $m ) ) {
				continue;
			}

			list( $all, $lang, $territory, $charset, $modifier ) = $m;

			$candidatesByLocale[$m[0]] = $m;
			$candidatesByLang[$lang][] = $m;
		}

		# Try the current value of LANG.
		if ( isset( $candidatesByLocale[ getenv( 'LANG' ) ] ) ) {
			$this->setVar( 'wgShellLocale', getenv( 'LANG' ) );
			$this->showMessage( 'config-shell-locale', getenv( 'LANG' ) );
			return true;
		}

		# Try the most common ones.
		$commonLocales = array( 'en_US.UTF-8', 'en_US.utf8', 'de_DE.UTF-8', 'de_DE.utf8' );
		foreach ( $commonLocales as $commonLocale ) {
			if ( isset( $candidatesByLocale[$commonLocale] ) ) {
				$this->setVar( 'wgShellLocale', $commonLocale );
				$this->showMessage( 'config-shell-locale', $commonLocale );
				return true;
			}
		}

		# Is there an available locale in the Wiki's language?
		$wikiLang = $this->getVar( 'wgLanguageCode' );

		if ( isset( $candidatesByLang[$wikiLang] ) ) {
			$m = reset( $candidatesByLang[$wikiLang] );
			$this->setVar( 'wgShellLocale', $m[0] );
			$this->showMessage( 'config-shell-locale', $m[0] );
			return true;
		}

		# Are there any at all?
		if ( count( $candidatesByLocale ) ) {
			$m = reset( $candidatesByLocale );
			$this->setVar( 'wgShellLocale', $m[0] );
			$this->showMessage( 'config-shell-locale', $m[0] );
			return true;
		}

		# Give up.
		return true;
	}

	/**
	 * TODO: document
	 */
	public function envCheckUploadsDirectory() {
		global $IP, $wgServer;

		$dir = $IP . '/images/';
		$url = $wgServer . $this->getVar( 'wgScriptPath' ) . '/images/';
		$safe = !$this->dirIsExecutable( $dir, $url );

		if ( $safe ) {
			$this->showMessage( 'config-uploads-safe' );
		} else {
			$this->showMessage( 'config-uploads-not-safe', $dir );
		}
	}

	/**
	 * Convert a hex string representing a Unicode code point to that code point.
	 * @param $c String
	 * @return string
	 */
	protected function unicodeChar( $c ) {
		$c = hexdec($c);
		if ($c <= 0x7F) {
			return chr($c);
		} else if ($c <= 0x7FF) {
			return chr(0xC0 | $c >> 6) . chr(0x80 | $c & 0x3F);
		} else if ($c <= 0xFFFF) {
			return chr(0xE0 | $c >> 12) . chr(0x80 | $c >> 6 & 0x3F)
				. chr(0x80 | $c & 0x3F);
		} else if ($c <= 0x10FFFF) {
			return chr(0xF0 | $c >> 18) . chr(0x80 | $c >> 12 & 0x3F)
				. chr(0x80 | $c >> 6 & 0x3F)
				. chr(0x80 | $c & 0x3F);
		} else {
			return false;
		}
	}


	/**
	 * Check the libicu version
	 */
	public function envCheckLibicu() {
		$utf8 = function_exists( 'utf8_normalize' );
		$intl = function_exists( 'normalizer_normalize' );

		/**
		 * This needs to be updated something that the latest libicu
		 * will properly normalize.  This normalization was found at
		 * http://www.unicode.org/versions/Unicode5.2.0/#Character_Additions
		 * Note that we use the hex representation to create the code
		 * points in order to avoid any Unicode-destroying during transit.
		 */
		$not_normal_c = $this->unicodeChar("FA6C");
		$normal_c = $this->unicodeChar("242EE");

		$useNormalizer = 'php';
		$needsUpdate = false;

		/**
		 * We're going to prefer the pecl extension here unless
		 * utf8_normalize is more up to date.
		 */
		if( $utf8 ) {
			$useNormalizer = 'utf8';
			$utf8 = utf8_normalize( $not_normal_c, UNORM_NFC );
			if ( $utf8 !== $normal_c ) $needsUpdate = true;
		}
		if( $intl ) {
			$useNormalizer = 'intl';
			$intl = normalizer_normalize( $not_normal_c, Normalizer::FORM_C );
			if ( $intl !== $normal_c ) $needsUpdate = true;
		}

		// Uses messages 'config-unicode-using-php', 'config-unicode-using-utf8', 'config-unicode-using-intl'
		if( $useNormalizer === 'php' ) {
			$this->showMessage( 'config-unicode-pure-php-warning' );
		} else {
			$this->showMessage( 'config-unicode-using-' . $useNormalizer );
			if( $needsUpdate ) {
				$this->showMessage( 'config-unicode-update-warning' );
			}
		}
	}

	/**
	 * Get an array of likely places we can find executables. Check a bunch
	 * of known Unix-like defaults, as well as the PATH environment variable
	 * (which should maybe make it work for Windows?)
	 *
	 * @return Array
	 */
	protected static function getPossibleBinPaths() {
		return array_merge(
			array( '/usr/bin', '/usr/local/bin', '/opt/csw/bin',
				'/usr/gnu/bin', '/usr/sfw/bin', '/sw/bin', '/opt/local/bin' ),
			explode( PATH_SEPARATOR, getenv( 'PATH' ) )
		);
	}

	/**
	 * Search a path for any of the given executable names. Returns the
	 * executable name if found. Also checks the version string returned
	 * by each executable.
	 *
	 * Used only by environment checks.
	 *
	 * @param $path String: path to search
	 * @param $names Array of executable names
	 * @param $versionInfo Boolean false or array with two members:
	 *		 0 => Command to run for version check, with $1 for the path
	 *		 1 => String to compare the output with
	 *
	 * If $versionInfo is not false, only executables with a version
	 * matching $versionInfo[1] will be returned.
	 */
	public static function locateExecutable( $path, $names, $versionInfo = false ) {
		if ( !is_array( $names ) ) {
			$names = array( $names );
		}

		foreach ( $names as $name ) {
			$command = $path . DIRECTORY_SEPARATOR . $name;

			wfSuppressWarnings();
			$file_exists = file_exists( $command );
			wfRestoreWarnings();

			if ( $file_exists ) {
				if ( !$versionInfo ) {
					return $command;
				}

				if ( wfIsWindows() ) {
					$command = "\"$command\"";
				}
				$file = str_replace( '$1', $command, $versionInfo[0] );
				if ( strstr( wfShellExec( $file ), $versionInfo[1] ) !== false ) {
					return $command;
				}
			}
		}
		return false;
	}

	/**
	 * Same as locateExecutable(), but checks in getPossibleBinPaths() by default
	 * @see locateExecutable()
	 */
	public static function locateExecutableInDefaultPaths( $names, $versionInfo = false ) {
		foreach( self::getPossibleBinPaths() as $path ) {
			$exe = self::locateExecutable( $path, $names, $versionInfo );
			if( $exe !== false ) {
				return $exe;
			}
		}
		return false;
	}

	/**
	 * Checks if scripts located in the given directory can be executed via the given URL.
	 *
	 * Used only by environment checks.
	 */
	public function dirIsExecutable( $dir, $url ) {
		$scriptTypes = array(
			'php' => array(
				"<?php echo 'ex' . 'ec';",
				"#!/var/env php5\n<?php echo 'ex' . 'ec';",
			),
		);

		// it would be good to check other popular languages here, but it'll be slow.

		wfSuppressWarnings();

		foreach ( $scriptTypes as $ext => $contents ) {
			foreach ( $contents as $source ) {
				$file = 'exectest.' . $ext;

				if ( !file_put_contents( $dir . $file, $source ) ) {
					break;
				}

				$text = Http::get( $url . $file );
				unlink( $dir . $file );

				if ( $text == 'exec' ) {
					wfRestoreWarnings();
					return $ext;
				}
			}
		}

		wfRestoreWarnings();

		return false;
	}

}
