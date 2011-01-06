<?php
/**
 * Base core installer.
 *
 * @file
 * @ingroup Deployment
 */

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
		'wgUpgradeKey',
		'wgDefaultSkin',
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
		'_UpgradeKeySupplied' => false,
		'_ExistingDBSettings' => false,
	);

	/**
	 * The actual list of installation steps. This will be initialized by getInstallSteps()
	 *
	 * @var array
	 */
	private $installSteps = array();

	/**
	 * Extra steps for installation, for things like DatabaseInstallers to modify
	 *
	 * @var array
	 */
	protected $extraInstallSteps = array();

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
		'none' => array(
			'url' => '',
			'icon' => '',
			'text' => ''
		),
		'cc-choose' => array(
			// Details will be filled in by the selector.
			'url' => '',
			'icon' => '',
			'text' => '',
		),
	);

	/**
	 * URL to mediawiki-announce subscription
	 */
	protected $mediaWikiAnnounceUrl = 'https://lists.wikimedia.org/mailman/subscribe/mediawiki-announce';

	/**
	 * Supported language codes for Mailman
	 */
	protected $mediaWikiAnnounceLanguages = array(
		'ca', 'cs', 'da', 'de', 'en', 'es', 'et', 'eu', 'fi', 'fr', 'hr', 'hu',
		'it', 'ja', 'ko', 'lt', 'nl', 'no', 'pl', 'pt', 'pt-br', 'ro', 'ru',
		'sl', 'sr', 'sv', 'tr', 'uk'
	);

	/**
	 * TODO: document
	 *
	 * @param $status Status
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
			dirname( __FILE__ ) . '/Installer.i18n.php';

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
		$this->parserOptions = new ParserOptions; // language will  be wrong :(
		$this->parserOptions->setEditSection( false );
	}

	/**
	 * Register tag hook below.
	 *
	 * @todo Move this to WebInstaller with the two things below?
	 *
	 * @param $parser Parser
	 */
	public function registerDocLink( Parser &$parser ) {
		$parser->setHook( 'doclink', array( $this, 'docLink' ) );
		return true;
	}

	/**
	 * ParserOptions are constructed before we determined the language, so fix it
	 */
	public function setParserLanguage( $lang ) {
		$this->parserOptions->setTargetLanguage( $lang );
		$this->parserOptions->setUserLang( $lang );
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
		return "{$_SERVER['PHP_SELF']}?page=" . urlencode( $page );
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
	 * @return Status
	 */
	protected function includeExtensions() {
		$exts = $this->getVar( '_Extensions' );
		$path = $this->getVar( 'IP' ) . '/extensions';

		foreach( $exts as $e ) {
			require( "$path/$e/$e.php" );
		}

		return Status::newGood();
	}

	/**
	 * Get an array of install steps. Should always be in the format of
	 * array(
	 *   'name'     => 'someuniquename',
	 *   'callback' => array( $obj, 'method' ),
	 * )
	 * There must be a config-install-$name message defined per step, which will
	 * be shown on install.
	 *
	 * @param $installer DatabaseInstaller so we can make callbacks
	 * @return array
	 */
	protected function getInstallSteps( DatabaseInstaller &$installer ) {
		$coreInstallSteps = array(
			array( 'name' => 'database',   'callback' => array( $installer, 'setupDatabase' ) ),
			array( 'name' => 'tables',     'callback' => array( $this, 'installTables' ) ),
			array( 'name' => 'interwiki',  'callback' => array( $installer, 'populateInterwikiTable' ) ),
			array( 'name' => 'secretkey',  'callback' => array( $this, 'generateSecretKey' ) ),
			array( 'name' => 'upgradekey', 'callback' => array( $this, 'generateUpgradeKey' ) ),
			array( 'name' => 'sysop',      'callback' => array( $this, 'createSysop' ) ),
			array( 'name' => 'mainpage',   'callback' => array( $this, 'createMainpage' ) ),
		);
		if( count( $this->getVar( '_Extensions' ) ) ) {
			array_unshift( $coreInstallSteps,
				array( 'name' => 'extensions', 'callback' => array( $this, 'includeExtensions' ) )
			);
		}
		$this->installSteps = $coreInstallSteps;
		foreach( $this->extraInstallSteps as $step ) {
			// Put the step at the beginning
			if( !strval( $step['position' ] ) ) {
				array_unshift( $installSteps, $step['callback'] );
				continue;
			} else {
				// Walk the $coreInstallSteps array to see if we can modify
				// $this->installSteps with a callback that wants to attach after
				// a given step
				array_walk( 
					$coreInstallSteps,
					array( $this, 'installStepCallback' ),
					$step
				);
			}
		}
		return $this->installSteps;
	}

	/**
	 * Callback for getInstallSteps() - used for finding if a given $insertableStep
	 * should be inserted after the current $coreStep in question
	 */
	private function installStepCallback( $coreStep, $key, $insertableStep ) {
		if( $coreStep['name'] == $insertableStep['position'] ) {
			$front = array_slice( $this->installSteps, 0, $key + 1 );
			$front[] = $insertableStep['callback'];
			$back = array_slice( $this->installSteps, $key + 1 );
			$this->installSteps = array_merge( $front, $back );
		}
	}

	/**
	 * Actually perform the installation.
	 *
	 * @param $startCB A callback array for the beginning of each step
	 * @param $endCB A callback array for the end of each step
	 *
	 * @return Array of Status objects
	 */
	public function performInstallation( $startCB, $endCB ) {
		$installResults = array();
		$installer = $this->getDBInstaller();
		$installer->preInstall();
		$steps = $this->getInstallSteps( $installer );
		foreach( $steps as $stepObj ) {
			$name = $stepObj['name'];
			call_user_func_array( $startCB, array( $name ) );

			// Perform the callback step
			$status = call_user_func_array( $stepObj['callback'], array( &$installer ) );

			// Output and save the results
			call_user_func_array( $endCB, array( $name, $status ) );
			$installResults[$name] = $status;

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
	 * Generate $wgSecretKey. Will warn if we had to use mt_rand() instead of
	 * /dev/urandom
	 *
	 * @return Status
	 */
	protected function generateSecretKey() {
		return $this->generateSecret( 'wgSecretKey' );
	}

	/**
	 * Generate a secret value for a variable using either
	 * /dev/urandom or mt_rand() Produce a warning in the later case.
	 *
	 * @return Status
	 */
	protected function generateSecret( $secretName, $length = 64 ) {
		if ( wfIsWindows() ) {
			$file = null;
		} else {
			wfSuppressWarnings();
			$file = fopen( "/dev/urandom", "r" );
			wfRestoreWarnings();
		}

		$status = Status::newGood();

		if ( $file ) {
			$secretKey = bin2hex( fread( $file, $length / 2 ) );
			fclose( $file );
		} else {
			$secretKey = '';

			for ( $i = 0; $i < $length / 8; $i++ ) {
				$secretKey .= dechex( mt_rand( 0, 0x7fffffff ) );
			}

			$status->warning( 'config-insecure-secret', '$' . $secretName );
		}

		$this->setVar( $secretName, $secretKey );

		return $status;
	}

	/**
	 * Generate a default $wgUpgradeKey. Will warn if we had to use
	 * mt_rand() instead of /dev/urandom
	 *
	 * @return Status
	 */
	public function generateUpgradeKey() {
		if ( strval( $this->getVar( 'wgUpgradeKey' ) ) === '' ) {
			return $this->generateSecret( 'wgUpgradeKey', 16 );
		}
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
			} catch( PasswordError $pwe ) {
				return Status::newFatal( 'config-admin-error-password', $name, $pwe->getMessage() );
			}

			$user->addGroup( 'sysop' );
			$user->addGroup( 'bureaucrat' );
			if( $this->getVar( '_AdminEmail' ) ) {
				$user->setEmail( $this->getVar( '_AdminEmail' ) );
			}
			$user->saveSettings();
		}
		$status = Status::newGood();

		if( $this->getVar( '_Subscribe' ) && $this->getVar( '_AdminEmail' ) ) {
			$this->subscribeToMediaWikiAnnounce( $status );
		}

		return $status;
	}

	private function subscribeToMediaWikiAnnounce( Status $s ) {
		$params = array( 
			'email'    => $this->getVar( '_AdminEmail' ),
			'language' => 'en',
			'digest'   => 0
		);

		// Mailman doesn't support as many languages as we do, so check to make
		// sure their selected language is available
		$myLang = $this->getVar( '_UserLang' );
		if( in_array( $myLang, $this->mediaWikiAnnounceLanguages ) ) {
			$myLang = $myLang == 'pt-br' ? 'pt_BR' : $myLang; // rewrite to Mailman's pt_BR
			$params['language'] = $myLang;
		}

		$res = Http::post( $this->mediaWikiAnnounceUrl, array( 'postData' => $params ) );
		if( !$res ) {
			$s->warning( 'config-install-subscribe-fail' );
		}
	}

	/**
	 * Insert Main Page with default content.
	 *
	 * @return Status
	 */
	protected function createMainpage( DatabaseInstaller &$installer ) {
		$status = Status::newGood();
		try {
			$article = new Article( Title::newMainPage() );
			$article->doEdit( wfMsgForContent( 'mainpagetext' ) . "\n\n" .
								wfMsgForContent( 'mainpagedocfooter' ),
								'',
								EDIT_NEW,
								false,
								User::newFromName( 'MediaWiki Default' ) );
		} catch (MWException $e) {
			//using raw, because $wgShowExceptionDetails can not be set yet
			$status->fatal( 'config-install-mainpage-failed', $e->getMessage() );
		}

		return $status;
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

		// Extended debugging
		$GLOBALS['wgShowSQLErrors'] = true;
		$GLOBALS['wgShowDBErrorBacktrace'] = true;

		// Allow multiple ob_flush() calls
		$GLOBALS['wgDisableOutputCompression'] = true;

		// Use a sensible cookie prefix (not my_wiki)
		$GLOBALS['wgCookiePrefix'] = 'mw_installer';

		// Some of the environment checks make shell requests, remove limits
		$GLOBALS['wgMaxShellMemory'] = 0;
	}

	/**
	 * Add an installation step following the given step.
	 *
	 * @param $callback Array A valid installation callback array, in this form:
	 *    array( 'name' => 'some-unique-name', 'callback' => array( $obj, 'function' ) );
	 * @param $findStep String the step to find. Omit to put the step at the beginning
	 */
	public function addInstallStep( $callback, $findStep = '' ) {
		$this->extraInstallSteps[] = array(
			'position' => $findStep, 'callback' => $callback
		);
	}
}
