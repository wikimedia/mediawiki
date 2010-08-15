<?php

/**
 * Base core installer class.
 * Handles everything that is independent of user interface.
 *
 * @ingroup Deployment
 * @since 1.17
 */
abstract class CoreInstaller extends Installer {

	/**
	 * MediaWiki configuration globals that will eventually be passed through
	 * to LocalSettings.php. The names only are given here, the defaults
	 * typically come from DefaultSettings.php.
	 *
	 * @var array
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
		'wgUseInstantCommons',
	);

	/**
	 * Variables that are stored alongside globals, and are used for any
	 * configuration of the installation process aside from the MediaWiki
	 * configuration. Map of names to defaults.
	 *
	 * @var array
	 */
	protected $internalDefaults = array(
		'_UserLang' => 'en',
		'_Environment' => false,
		'_CompiledDBs' => array(),
		'_SafeMode' => false,
		'_RaiseMemory' => false,
		'_UpgradeDone' => false,
		'_InstallDone' => false,
		'_Caches' => array(),
		'_InstallUser' => 'root',
		'_InstallPassword' => '',
		'_SameAccount' => true,
		'_CreateDBAccount' => false,
		'_NamespaceType' => 'site-name',
		'_AdminName' => '', // will be set later, when the user selects language
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
		'_ExternalHTTP' => false,
	);

	/**
	 * Steps for installation.
	 *
	 * @var array
	 */
	protected $installSteps = array(
		'database',
		'tables',
		'interwiki',
		'secretkey',
		'sysop',
	);

	/**
	 * Known object cache types and the functions used to test for their existence.
	 *
	 * @var array
	 */
	protected $objectCaches = array(
		'xcache' => 'xcache_get',
		'apc' => 'apc_fetch',
		'eaccel' => 'eaccelerator_get',
		'wincache' => 'wincache_ucache_get'
	);

	/**
	 * User rights profiles.
	 *
	 * @var array
	 */
	public $rightsProfiles = array(
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
	 * License types.
	 *
	 * @var array
	 */
	public $licenses = array(
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
			// Details will be filled in by the selector.
			'url' => '',
			'icon' => '',
			'text' => '',
		),
	);

	/**
	 * TODO: document
	 *
	 * @param Status $status
	 */
	public abstract function showStatusMessage( Status $status );


	/**
	 * Constructor, always call this from child classes.
	 */
	public function __construct() {
		parent::__construct();

		global $wgExtensionMessagesFiles, $wgUser, $wgHooks;

		// Load the installer's i18n file.
		$wgExtensionMessagesFiles['MediawikiInstaller'] =
			'./includes/installer/Installer.i18n.php';

		// Having a user with id = 0 safeguards us from DB access via User::loadOptions().
		$wgUser = User::newFromId( 0 );

		// Set our custom <doclink> hook.
		$wgHooks['ParserFirstCallInit'][] = array( $this, 'registerDocLink' );

		$this->settings = $this->internalDefaults;

		foreach ( $this->defaultVarNames as $var ) {
			$this->settings[$var] = $GLOBALS[$var];
		}

		foreach ( self::getDBTypes() as $type ) {
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

		$this->parserTitle = Title::newFromText( 'Installer' );
		$this->parserOptions = new ParserOptions;
		$this->parserOptions->setEditSection( false );
	}

	/**
	 * Register tag hook below.
	 *
	 * @param $parser Parser
	 */
	public function registerDocLink( Parser &$parser ) {
		$parser->setHook( 'doclink', array( $this, 'docLink' ) );
		return true;
	}

	/**
	 * Extension tag hook for a documentation link.
	 */
	public function docLink( $linkText, $attribs, $parser ) {
		$url = $this->getDocUrl( $attribs['href'] );
		return '<a href="' . htmlspecialchars( $url ) . '">' .
			htmlspecialchars( $linkText ) .
			'</a>';
	}

	/**
	 * Overridden by WebInstaller to provide lastPage parameters.
	 */
	protected function getDocUrl( $page ) {
		return "{$_SERVER['PHP_SELF']}?page=" . urlencode( $attribs['href'] );
	}

	/**
	 * Finds extensions that follow the format /extensions/Name/Name.php,
	 * and returns an array containing the value for 'Name' for each found extension.
	 *
	 * @return array
	 */
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

		return $exts;
	}

	/**
	 * Installs the auto-detected extensions.
	 *
	 * TODO: this only requires them?
	 *
	 * @return Status
	 */
	public function installExtensions() {
		$exts = $this->getVar( '_Extensions' );
		$path = $this->getVar( 'IP' ) . '/extensions';

		foreach( $exts as $e ) {
			require( "$path/$e/$e.php" );
		}

		return Status::newGood();
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
	 * Actually perform the installation.
	 *
	 * @param Array $startCB A callback array for the beginning of each step
	 * @param Array $endCB A callback array for the end of each step
	 *
	 * @return Array of Status objects
	 */
	public function performInstallation( $startCB, $endCB ) {
		$installResults = array();
		$installer = $this->getDBInstaller();

		foreach( $this->getInstallSteps() as $stepObj ) {
			$step = is_array( $stepObj ) ? $stepObj['name'] : $stepObj;
			call_user_func_array( $startCB, array( $step ) );
			$status = null;

			# Call our working function
			if ( is_array( $stepObj ) ) {
				# A custom callaback
				$callback = $stepObj['callback'];
				$status = call_user_func_array( $callback, array( $installer ) );
			} else {
				# Boring implicitly named callback
				$func = 'install' . ucfirst( $step );
				$status = $this->{$func}( $installer );
			}

			call_user_func_array( $endCB, array( $step, $status ) );
			$installResults[$step] = $status;

			// If we've hit some sort of fatal, we need to bail.
			// Callback already had a chance to do output above.
			if( !$status->isOk() ) {
				break;
			}

		}

		if( $status->isOk() ) {
			$this->setVar( '_InstallDone', true );
		}

		return $installResults;
	}

	/**
	 * TODO: document
	 *
	 * @return Status
	 */
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
			$secretKey = '';

			for ( $i=0; $i<8; $i++ ) {
				$secretKey .= dechex( mt_rand( 0, 0x7fffffff ) );
			}

			$status->warning( 'config-insecure-secretkey' );
		}

		$this->setVar( 'wgSecretKey', $secretKey );

		return $status;
	}

	/**
	 * TODO: document
	 *
	 * @return Status
	 */
	public function installSysop() {
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
			} catch( PasswordError $pwe ) {
				return Status::newFatal( 'config-admin-error-password', $name, $pwe->getMessage() );
			}

			$user->addGroup( 'sysop' );
			$user->addGroup( 'bureaucrat' );
			$user->saveSettings();
		}

		return Status::newGood();
	}

	/**
	 * Override the necessary bits of the config to run an installation.
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

	/**
	 * Add an installation step following the given step.
	 *
	 * @param $findStep String the step to find.  Use NULL to put the step at the beginning.
	 * @param $callback array
	 */
	public function addInstallStepFollowing( $findStep, $callback ) {
		$where = 0;

		if( $findStep !== null ) {
			$where = array_search( $findStep, $this->installSteps );
		}

		array_splice( $this->installSteps, $where, 0, $callback );
	}

}