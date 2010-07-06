<?php

/**
 * Base installer class
 * Handles everything that is independent of user interface
 */
abstract class Installer {
	var $settings, $output;

	/**
	 * MediaWiki configuration globals that will eventually be passed through 
	 * to LocalSettings.php. The names only are given here, the defaults 
	 * typically come from DefaultSettings.php.
	 */
	protected $defaultVarNames = array(
		'wgSitename',
		'wgPasswordSender',
		'wgLanguageCode', 
		'wgRightsIcon',
		'wgRightsText',
		'wgRightsUrl',
		'wgMainCacheType',
		'wgEnableEmail',
		'wgEnableUserEmail',
		'wgEnotifUserTalk',
		'wgEnotifWatchlist',
		'wgEmailAuthentication',
		'wgDBtype',
		'wgDiff3',
		'wgImageMagickConvertCommand',
		'IP',
		'wgScriptPath',
		'wgScriptExtension',
		'wgMetaNamespace',
		'wgDeletedDirectory',
		'wgEnableUploads',
		'wgLogo',
		'wgShellLocale',
		'wgSecretKey',
	);

	/**
	 * Variables that are stored alongside globals, and are used for any 
	 * configuration of the installation process aside from the MediaWiki 
	 * configuration. Map of names to defaults.
	 */
	protected $internalDefaults = array(
		'_UserLang' => 'en',
		'_Environment' => false,
		'_CompiledDBs' => array(),
		'_SafeMode' => false,
		'_RaiseMemory' => false,
		'_UpgradeDone' => false,
		'_Caches' => array(),
		'_InstallUser' => 'root',
		'_InstallPassword' => '',
		'_SameAccount' => true,
		'_CreateDBAccount' => false,
		'_NamespaceType' => 'site-name',
		'_AdminName' => null, // will be set later, when the user selects language
		'_AdminPassword' => '',
		'_AdminPassword2' => '',
		'_AdminEmail' => '',
		'_Subscribe' => false,
		'_SkipOptional' => 'continue',
		'_RightsProfile' => 'wiki',
		'_LicenseCode' => 'none',
		'_CCDone' => false,
		'_Extensions' => array(),
		'_MemCachedServers' => '',
	);

	/**
	 * Known database types. These correspond to the class names <type>Installer,
	 * and are also MediaWiki database types valid for $wgDBtype.
	 *
	 * To add a new type, create a <type>Installer class and a Database<type> 
	 * class, and add a config-type-<type> message to MessagesEn.php.
	 */
	private $dbTypes = array(
		'mysql',
		'postgres',
		'sqlite',
		'oracle'
	);

	/**
	 * Minimum memory size in MB
	 */
	private $minMemorySize = 50;

	/**
	 * Cached DB installer instances, access using getDBInstaller()
	 */
	private $dbInstallers = array();

	/**
	 * A list of environment check methods called by doEnvironmentChecks(). 
	 * These may output warnings using showMessage(), and/or abort the 
	 * installation process by returning false.
	 */
	protected $envChecks = array(
		'envLatestVersion',
		'envCheckDB',
		'envCheckRegisterGlobals',
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
		'envCheckWriteableDir',
		'envCheckExtension',
		'envCheckShellLocale',
		'envCheckUploadsDirectory',
	);

	/**
	 * Steps for installation.
	 * @TODO Should be protected...
	 */
	var $installSteps = array(
		'database',
		'tables',
		'interwiki',
		'secretkey',
		'sysop',
		'localsettings',
	);

	/**
	 * Known object cache types and the functions used to test for their existence
	 */
	protected $objectCaches = array(
		'xcache' => 'xcache_get',
		'apc' => 'apc_fetch',
		'eaccel' => 'eaccelerator_get',
		'wincache' => 'wincache_ucache_get'
	);

	/**
	 * User rights profiles
	 */
	var $rightsProfiles = array(
		'wiki' => array(),
		'no-anon' => array(
			'*' => array( 'edit' => false )
		),
		'fishbowl' => array(
			'*' => array( 
				'createaccount' => false,
				'edit' => false,
			),
		),
		'private' => array(
			'*' => array(
				'createaccount' => false,
				'edit' => false,
				'read' => false,
			),
		),
	);

	/**
	 * License types
	 */
	var $licenses = array(
		'none' => array(
			'url' => '',
			'icon' => '',
			'text' => ''
		),
		'cc-by-sa' => array(
			'url' => 'http://creativecommons.org/licenses/by-sa/3.0/',
			'icon' => '{$wgStylePath}/common/images/cc-by-sa.png',
		),
		'cc-by-nc-sa' => array(
			'url' => 'http://creativecommons.org/licenses/by-nc-sa/3.0/',
			'icon' => '{$wgStylePath}/common/images/cc-by-nc-sa.png',
		),
		'pd' => array(
			'url' => 'http://creativecommons.org/licenses/publicdomain/',
			'icon' => '{$wgStylePath}/common/images/public-domain.png',
		),
		'gfdl-old' => array(
			'url' => 'http://www.gnu.org/licenses/old-licenses/fdl-1.2.html',
			'icon' => '{$wgStylePath}/common/images/gnu-fdl.png',
		),
		'gfdl-current' => array(
			'url' => 'http://www.gnu.org/copyleft/fdl.html',
			'icon' => '{$wgStylePath}/common/images/gnu-fdl.png',
		),
		'cc-choose' => array(
			// details will be filled in by the selector
			'url' => '', 
			'icon' => '',
			'text' => '',
		),
	);
	/**
	 * Cached Title and ParserOptions used by parse()
	 */
	private $parserTitle, $parserOptions;

	/**
	 * Constructor, always call this from child classes
	 */
	function __construct() {
		// Disable the i18n cache and LoadBalancer
		Language::getLocalisationCache()->disableBackend();
		LBFactory::disableBackend();

		// Load the installer's i18n file
		global $wgExtensionMessagesFiles;
		$wgExtensionMessagesFiles['MediawikiInstaller'] =
			'./includes/installer/Installer.i18n.php';

		global $wgUser;
		$wgUser = User::newFromId( 0 );
		// Having a user with id = 0 safeguards us from DB access via User::loadOptions()

		// Set our custom <doclink> hook
		global $wgHooks;
		$wgHooks['ParserFirstCallInit'][] = array( $this, 'registerDocLink' );

		$this->settings = $this->internalDefaults;
		foreach ( $this->defaultVarNames as $var ) {
			$this->settings[$var] = $GLOBALS[$var];
		}

		$this->parserTitle = Title::newFromText( 'Installer' );
		$this->parserOptions = new ParserOptions;
		$this->parserOptions->setEditSection( false );
	}

	/*
	 * Set up our database objects. They need to inject some of their
	 * own configuration into our global context. Usually this'll just be
	 * things like the default $wgDBname.
	 */
	function setupDatabaseObjects() {
		foreach ( $this->dbTypes as $type ) {
			$installer = $this->getDBInstaller( $type );
			if ( !$installer->isCompiled() ) {
				continue;
			}
			$defaults = $installer->getGlobalDefaults();
			foreach ( $installer->getGlobalNames() as $var ) {
				if ( isset( $defaults[$var] ) ) {
					$this->settings[$var] = $defaults[$var];
				} else {
					$this->settings[$var] = $GLOBALS[$var];
				}
			}
		}
	}

	/**
	 * UI interface for displaying a short message
	 * The parameters are like parameters to wfMsg().
	 * The messages will be in wikitext format, which will be converted to an 
	 * output format such as HTML or text before being sent to the user.
	 */
	abstract function showMessage( $msg /*, ... */ );

	abstract function showStatusMessage( $status );

	/**
	 * Get a list of known DB types
	 */
	function getDBTypes() {
		return $this->dbTypes;
	}

	/**
	 * Get an instance of InstallerDBType for the specified DB type
	 * @param $type Mixed: DB installer for which is needed, false to use default
	 */
	function getDBInstaller( $type = false ) {
		if ( !$type ) {
			$type = $this->getVar( 'wgDBtype' );
		}
		if ( !isset( $this->dbInstallers[$type] ) ) {
			$class = ucfirst( $type ). 'Installer';
			$this->dbInstallers[$type] = new $class( $this );
		}
		return $this->dbInstallers[$type];
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
	 */
	function doEnvironmentChecks() {
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
	 * Get an MW configuration variable, or internal installer configuration variable.
	 * The defaults come from $GLOBALS (ultimately DefaultSettings.php).
	 * Installer variables are typically prefixed by an underscore.
	 */
	function getVar( $name, $default = null ) {
		if ( !isset( $this->settings[$name] ) ) {
			return $default;
		} else {
			return $this->settings[$name];
		}
	}

	/**
	 * Set a MW configuration variable, or internal installer configuration variable.
	 */
	function setVar( $name, $value ) {
		$this->settings[$name] = $value;
	}

	/**
	 * Exports all wg* variables stored by the installer into global scope
	 */
	function exportVars() {
		foreach ( $this->settings as $name => $value ) {
			if ( substr( $name, 0, 2 ) == 'wg' ) {
				$GLOBALS[$name] = $value;
			}
		}
	}

	/**
	 * Get a fake password for sending back to the user in HTML.
	 * This is a security mechanism to avoid compromise of the password in the
	 * event of session ID compromise.
	 */
	function getFakePassword( $realPassword ) {
		return str_repeat( '*', strlen( $realPassword ) );
	}

	/**
	 * Set a variable which stores a password, except if the new value is a 
	 * fake password in which case leave it as it is.
	 */
	function setPassword( $name, $value ) {
		if ( !preg_match( '/^\*+$/', $value ) ) {
			$this->setVar( $name, $value );
		}
	}
	
	/** Check if we're installing the latest version */
	function envLatestVersion() {
		global $wgVersion;
		$latestInfoUrl = 'http://www.mediawiki.org/w/api.php?action=mwreleases&format=json';
		$latestInfo = Http::get( $latestInfoUrl );
		if( !$latestInfo ) {
			$this->showMessage( 'config-env-latest-can-not-check', $latestInfoUrl );
			return;
		}
		$latestInfo = FormatJson::decode($latestInfo);
		if ($latestInfo === false || !isset( $latestInfo->mwreleases ) ) {
			# For when the request is successful but there's e.g. some silly man in
			# the middle firewall blocking us, e.g. one of those annoying airport ones
			$this->showMessage( 'config-env-latest-data-invalid', $latestInfoUrl );
			return;
		}
		foreach( $latestInfo->mwreleases as $rel ) {
			if( isset( $rel->current ) )
				$currentVersion = $rel->version;
		}
		if( version_compare( $wgVersion, $currentVersion, '<' ) ) {
			$this->showMessage( 'config-env-latest-old' );
			$this->showHelpBox( 'config-env-latest-help', $wgVersion, $currentVersion ); 
		} elseif( version_compare( $wgVersion, $currentVersion, '>' ) ) {
			$this->showMessage( 'config-env-latest-new' );
		}
		$this->showMessage( 'config-env-latest-ok' );
	}

	/** Environment check for DB types */
	function envCheckDB() {
		$compiledDBs = array();
		$goodNames = array();
		$allNames = array();
		foreach ( $this->dbTypes as $name ) {
			$db = $this->getDBInstaller( $name );
			$readableName = wfMsg( 'config-type-' . $name );
			if ( $db->isCompiled() ) {
				$compiledDBs[] = $name;
				$goodNames[] = $readableName;
			}
			$allNames[] = $readableName;
		}
		$this->setVar( '_CompiledDBs', $compiledDBs );

		global $wgLang;
		if ( !$compiledDBs ) {
			$this->showMessage( 'config-no-db' );
			$this->showHelpBox( 'config-no-db-help', $wgLang->commaList( $allNames ) );
			return false;
		}
		$this->showMessage( 'config-have-db', $wgLang->commaList( $goodNames ) );
	}

	/** Environment check for register_globals */
	function envCheckRegisterGlobals() {
		if( wfIniGetBool( "magic_quotes_runtime" ) ) {
			$this->showMessage( 'config-register-globals' );
		}
	}

	/** Environment check for magic_quotes_runtime */
	function envCheckMagicQuotes() {
		if( wfIniGetBool( "magic_quotes_runtime" ) ) {
			$this->showMessage( 'config-magic-quotes-runtime' );
			return false;
		}
	}

	/** Environment check for magic_quotes_sybase */
	function envCheckMagicSybase() {
		if ( wfIniGetBool( 'magic_quotes_sybase' ) ) {
			$this->showMessage( 'config-magic-quotes-sybase' );
			return false;
		}
	}

	/* Environment check for mbstring.func_overload */
	function envCheckMbstring() {
		if ( wfIniGetBool( 'mbstring.func_overload' ) ) {
			$this->showMessage( 'config-mbstring' );
			return false;
		}
	}

	/** Environment check for zend.ze1_compatibility_mode */
	function envCheckZE1() {
		if ( wfIniGetBool( 'zend.ze1_compatibility_mode' ) ) {
			$this->showMessage( 'config-ze1' );
			return false;
		}
	}

	/** Environment check for safe_mode */
	function envCheckSafeMode() {
		if ( wfIniGetBool( 'safe_mode' ) ) {
			$this->setVar( '_SafeMode', true );
			$this->showMessage( 'config-safe-mode' );
		}
	}

	/** Environment check for the XML module */
	function envCheckXML() {
		if ( !function_exists( "utf8_encode" ) ) {
			$this->showMessage( 'config-xml-bad' );
			return false;
		}
		$this->showMessage( 'config-xml-good' );
	}

	/** Environment check for the PCRE module */
	function envCheckPCRE() {
		if ( !function_exists( 'preg_match' ) ) {
			$this->showMessage( 'config-pcre' );
			return false;
		}
	}

	/** Environment check for available memory */
	function envCheckMemory() {
		$limit = ini_get( 'memory_limit' );
		if ( !$limit || $limit == -1 ) {
			$this->showMessage( 'config-memory-none' );
			return true;
		}
		$n = intval( $limit );
		if( preg_match( '/^([0-9]+)[Mm]$/', trim( $limit ), $m ) ) {
			$n = intval( $m[1] * (1024*1024) );
		}
		if( $n < $this->minMemorySize*1024*1024 ) {
			$newLimit = "{$this->minMemorySize}M";
			if( false === ini_set( "memory_limit", $newLimit ) ) {
				$this->showMessage( 'config-memory-bad', $limit );
			} else {
				$this->showMessage( 'config-memory-raised', $limit, $newLimit );
				$this->setVar( '_RaiseMemory', true );
			}
		} else {
			$this->showMessage( 'config-memory-ok', $limit );
		}
	}

	/** Environment check for compiled object cache types */
	function envCheckCache() {
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

	/** Search for GNU diff3 */
	function envCheckDiff3() {
		$paths = array_merge(
			array(
				"/usr/bin",
				"/usr/local/bin",
				"/opt/csw/bin",
				"/usr/gnu/bin",
				"/usr/sfw/bin" ),
			explode( PATH_SEPARATOR, getenv( "PATH" ) ) );
		$names = array( "gdiff3", "diff3", "diff3.exe" );

		$versionInfo = array( '$1 --version 2>&1', 'diff3 (GNU diffutils)' );
		$haveDiff3 = false;
		foreach ( $paths as $path ) {
			$exe = $this->locateExecutable( $path, $names, $versionInfo );
			if ($exe !== false) {
				$this->setVar( 'wgDiff3', $exe );
				$haveDiff3 = true;
				break;
			}
		}
		if ( $haveDiff3 ) {
			$this->showMessage( 'config-diff3-good', $exe );
		} else {
			$this->setVar( 'wgDiff3', false );
			$this->showMessage( 'config-diff3-bad' );
		}
	}

	/**
	 * Search a path for any of the given executable names. Returns the 
	 * executable name if found. Also checks the version string returned 
	 * by each executable
	 *
	 * @param $path String: path to search
	 * @param $names Array of executable names
	 * @param $versionInfo Boolean false or array with two members:
	 *       0 => Command to run for version check, with $1 for the path
	 *       1 => String to compare the output with
	 *
	 * If $versionInfo is not false, only executables with a version 
	 * matching $versionInfo[1] will be returned.
	 */
	function locateExecutable( $path, $names, $versionInfo = false ) {
		if (!is_array($names))
			$names = array($names);

		foreach ($names as $name) {
			$command = "$path/$name";
			if ( @file_exists( $command ) ) {
				if ( !$versionInfo )
					return $command;

				$file = str_replace( '$1', $command, $versionInfo[0] );
				# Should maybe be wfShellExec( $file), but runs into a ulimit, see
				# http://www.mediawiki.org/w/index.php?title=New-installer_issues&diff=prev&oldid=335456 
				if ( strstr( `$file`, $versionInfo[1]) !== false )
					return $command;
			}
		}
		return false;
	}

	/** Environment check for ImageMagick and GD */
	function envCheckGraphics() {
		$imcheck = array( "/usr/bin", "/opt/csw/bin", "/usr/local/bin", "/sw/bin", "/opt/local/bin" );
		foreach( $imcheck as $dir ) {
			$im = "$dir/convert";
			if( @file_exists( $im ) ) {
				$this->showMessage( 'config-imagemagick', $im );
				$this->setVar( 'wgImageMagickConvertCommand', $im );
				return true;
			}
		}
		if ( function_exists( 'imagejpeg' ) ) {
			$this->showMessage( 'config-gd' );
			return true;
		}
		$this->showMessage( 'no-scaling' );
	}

	/** Environment check for setting $IP and $wgScriptPath */
	function envCheckPath() {
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

	/** Environment check for writable config/ directory */
	function envCheckWriteableDir() {
		$ipDir = $this->getVar( 'IP' );
		$configDir = $ipDir . '/config';
		if( !is_writeable( $configDir ) ) {
			$webserverGroup = self::maybeGetWebserverPrimaryGroup();
			if ( $webserverGroup !== null ) {
				$this->showMessage( 'config-dir-not-writable-group', $ipDir, $webserverGroup );
			} else {
				$this->showMessage( 'config-dir-not-writable-nogroup', $ipDir, $webserverGroup );
			}
			return false;
		}
	}

	/** Environment check for setting the preferred PHP file extension */
	function envCheckExtension() {
		// FIXME: detect this properly
		if ( defined( 'MW_INSTALL_PHP5_EXT' ) ) {
			$ext = 'php5';
		} else {
			$ext = 'php';
		}
		$this->setVar( 'wgScriptExtension', ".$ext" );
		$this->showMessage( 'config-file-extension', $ext );
	}

	function envCheckShellLocale() {
		# Give up now if we're in safe mode or open_basedir
		# It's theoretically possible but tricky to work with
		if ( wfIniGetBool( "safe_mode" ) || ini_get( 'open_basedir' ) || !function_exists( 'exec' ) ) {
			return true;
		}

		$os = php_uname( 's' );
		$supported = array( 'Linux', 'SunOS', 'HP-UX' ); # Tested these
		if ( !in_array( $os, $supported ) ) {
			return true;
		}

		# Get a list of available locales
		$lines = $ret = false;
		exec( '/usr/bin/locale -a', $lines, $ret );
		if ( $ret ) {
			return true;
		}

		$lines = wfArrayMap( 'trim', $lines );
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

		# Try the current value of LANG
		if ( isset( $candidatesByLocale[ getenv( 'LANG' ) ] ) ) {
			$this->setVar( 'wgShellLocale', getenv( 'LANG' ) );
			$this->showMessage( 'config-shell-locale', getenv( 'LANG' ) );
			return true;
		}

		# Try the most common ones
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

		# Give up
		return true;
	}
	
	function envCheckUploadsDirectory() {
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
	 * Checks if scripts located in the given directory can be executed via the given URL
	 */
	function dirIsExecutable( $dir, $url ) {
		$scriptTypes = array(
			'php' => array(
				"<?php echo 'ex' . 'ec';",
				"#!/var/env php5\n<?php echo 'ex' . 'ec';",
			),
		);
		// it would be good to check other popular languages here, but it'll be slow

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
	function parse( $text, $lineStart = false ) {
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
	 * Register tag hook below
	 */
	function registerDocLink( &$parser ) {
		$parser->setHook( 'doclink', array( $this, 'docLink' ) );
		return true;
	}

	/**
	 * Extension tag hook for a documentation link
	 */
	function docLink( $linkText, $attribs, $parser ) {
		$url = $this->getDocUrl( $attribs['href'] );
		return '<a href="' . htmlspecialchars( $url ) . '">' . 
			htmlspecialchars( $linkText ) . 
			'</a>';
	}

	/**
	 * Overridden by WebInstaller to provide lastPage parameters
	 */
	protected function getDocUrl( $page ) {
		return "{$_SERVER['PHP_SELF']}?page=" . urlencode( $attribs['href'] );
	}

	public function findExtensions() {
		if( $this->getVar( 'IP' ) === null ) {
			return false;
		}
		$exts = array();
		$dir = $this->getVar( 'IP' ) . '/extensions';
		$dh = opendir( $dir );
		while ( ( $file = readdir( $dh ) ) !== false ) {
			if( file_exists( "$dir/$file/$file.php" ) ) {
				$exts[] = $file;
			}
		}
		$this->setVar( '_Extensions', $exts );
		return $exts;
	}

	public function getInstallSteps() {
		if( $this->getVar( '_UpgradeDone' ) ) {
			$this->installSteps = array( 'localsettings' );
		}
		if( count( $this->getVar( '_Extensions' ) ) ) {
			array_unshift( $this->installSteps, 'extensions' );
		}
		return $this->installSteps;
	}

	/**
	 * Actually perform the installation
	 * @param Array $startCB A callback array for the beginning of each step
	 * @param Array $endCB A callback array for the end of each step
	 * @return Array of Status objects
	 */
	public function performInstallation( $startCB, $endCB ) {
		$installResults = array();
		foreach( $this->getInstallSteps() as $stepObj ) {
			$step = is_array( $stepObj ) ? $stepObj['name'] : $stepObj;
			call_user_func_array( $startCB, array( $step ) );
			$status = null;

			# Call our working function
			if ( is_array( $step ) ) {
				# A custom callaback
				$callback = $stepObj['callback'];
				$status = call_user_func_array( $callback, array() );
			} else {
				# Boring implicitly named callback
				$func = 'install' . ucfirst( $step );
				$status = $this->{$func}();
			}
			call_user_func_array( $endCB, array( $step, $status ) );
			$installResults[$step] = $status;
		}
		return $installResults;
	}

	public function installExtensions() {
		global $wgHooks, $wgAutoloadClasses;
		$exts = $this->getVar( '_Extensions' );
		$path = $this->getVar( 'IP' ) . '/extensions';
		foreach( $exts as $e ) {
			require( "$path/$e/$e.php" );
		}
		return Status::newGood();
	}

	public function installDatabase() {
		$installer = $this->getDBInstaller( $this->getVar( 'wgDBtype' ) );
		$status = $installer->setupDatabase();
		return $status;
	}

	public function installUser() {
		$installer = $this->getDBInstaller( $this->getVar( 'wgDBtype' ) );
		$status = $installer->setupUser();
		return $status;
	}

	public function installTables() {
		$installer = $this->getDBInstaller();
		$status = $installer->createTables();
		if( $status->isOK() ) {
			LBFactory::enableBackend();
		}
		return $status;
	}

	public function installInterwiki() {
		$installer = $this->getDBInstaller();
		return $installer->populateInterwikiTable();
	}

	public function installSecretKey() {
		if ( wfIsWindows() ) {
			$file = null;
		} else {
			wfSuppressWarnings();
			$file = fopen( "/dev/urandom", "r" );
			wfRestoreWarnings();
		}

		$status = Status::newGood();

		if ( $file ) {
			$secretKey = bin2hex( fread( $file, 32 ) );
			fclose( $file );
		} else {
			$secretKey = "";
			for ( $i=0; $i<8; $i++ ) {
				$secretKey .= dechex(mt_rand(0, 0x7fffffff));
			}
			$status->warning( 'config-insecure-secretkey' );
		}
		$this->setVar( 'wgSecretKey', $secretKey );

		return $status;
	}

	public function installSysop() {
		$name = $this->getVar( '_AdminName' );
		$user = User::newFromName( $name );
		if ( !$user ) {
			// we should've validated this earlier anyway!
			return Status::newFatal( 'config-admin-error-user', $name );
		}
		if ( $user->idForName() == 0 ) {
			$user->addToDatabase();
			try {
				$user->setPassword( $this->getVar( '_AdminPassword' ) );
			} catch( PasswordError $pwe ) {
				return Status::newFatal( 'config-admin-error-password', $name, $pwe->getMessage() );
			}
			$user->addGroup( 'sysop' );
			$user->addGroup( 'bureaucrat' );
			$user->saveSettings();
		}
		return Status::newGood();
	}

	public function installLocalsettings() {
		$localSettings = new LocalSettingsGenerator( $this );
		return $localSettings->writeLocalSettings();
	}

	/**
	 * Determine if LocalSettings exists. If it does, return an appropriate
	 * status for whether we should can upgrade or not
	 * @return Status
	 */
	function getLocalSettingsStatus() {
		global $IP;

		$status = Status::newGood();

		wfSuppressWarnings();
		$ls = file_exists( "$IP/LocalSettings.php" );
		wfRestoreWarnings();
		
		if( $ls ) {
			if( $this->getDBInstaller()->needsUpgrade() ) {
				$status->warning( 'config-localsettings-upgrade' );
			}
			else {
				$status->fatal( 'config-localsettings-noupgrade' );
			}
		}
		return $status;
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
	 * @return String
	 */
	public static function maybeGetWebserverPrimaryGroup() {
		if ( ! function_exists('posix_getegid') || ! function_exists('posix_getpwuid') ) {
			# I don't know this, this isn't UNIX
			return null;
		}

		# posix_getegid() *not* getmygid() because we want the group of the webserver,
		# not whoever owns the current script
		$gid = posix_getegid();
		$getpwuid = posix_getpwuid( $gid );
		$group = $getpwuid["name"];

		return $group;
	}

	/**
	 * Override the necessary bits of the config to run an installation
	 */
	public static function overrideConfig() {
		define( 'MW_NO_SESSION', 1 );

		// Don't access the database
		$GLOBALS['wgUseDatabaseMessages'] = false;
		// Debug-friendly
		$GLOBALS['wgShowExceptionDetails'] = true;
		// Don't break forms
		$GLOBALS['wgExternalLinkTarget'] = '_blank';

		// Extended debugging. Maybe disable before release?
		$GLOBALS['wgShowSQLErrors'] = true;
		$GLOBALS['wgShowDBErrorBacktrace'] = true;
	}
}
