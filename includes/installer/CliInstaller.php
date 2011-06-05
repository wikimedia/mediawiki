<?php
/**
 * Core installer command line interface.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Class for the core installer command line interface.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class CliInstaller extends Installer {

	private $optionMap = array(
		'dbtype' => 'wgDBtype',
		'dbserver' => 'wgDBserver',
		'dbname' => 'wgDBname',
		'dbuser' => 'wgDBuser',
		'dbpass' => 'wgDBpassword',
		'dbprefix' => 'wgDBprefix',
		'dbtableoptions' => 'wgDBTableOptions',
		'dbmysql5' => 'wgDBmysql5',
		'dbserver' => 'wgDBserver',
		'dbport' => 'wgDBport',
		'dbname' => 'wgDBname',
		'dbuser' => 'wgDBuser',
		'dbpass' => 'wgDBpassword',
		'dbschema' => 'wgDBmwschema',
		'dbpath' => 'wgSQLiteDataDir',
		'scriptpath' => 'wgScriptPath',
	);

	/**
	 * Constructor.
	 *
	 * @param $siteName
	 * @param $admin
	 * @param $option Array
	 */
	function __construct( $siteName, $admin = null, array $option = array() ) {
		global $wgContLang;

		parent::__construct();

		foreach ( $this->optionMap as $opt => $global ) {
			if ( isset( $option[$opt] ) ) {
				$GLOBALS[$global] = $option[$opt];
				$this->setVar( $global, $option[$opt] );
			}
		}

		if ( isset( $option['lang'] ) ) {
			global $wgLang, $wgLanguageCode;
			$this->setVar( '_UserLang', $option['lang'] );
			$wgContLang = Language::factory( $option['lang'] );
			$wgLang = Language::factory( $option['lang'] );
			$wgLanguageCode = $option['lang'];
		}

		$this->setVar( 'wgSitename', $siteName );

		$metaNS = $wgContLang->ucfirst( str_replace( ' ', '_', $siteName ) );
		if ( $metaNS == 'MediaWiki' ) {
			$metaNS = 'Project';
		}
		$this->setVar( 'wgMetaNamespace', $metaNS );

		if ( $admin ) {
			$this->setVar( '_AdminName', $admin );
		}

		if ( !isset( $option['installdbuser'] ) ) {
			$this->setVar( '_InstallUser',
				$this->getVar( 'wgDBuser' ) );
			$this->setVar( '_InstallPassword',
				$this->getVar( 'wgDBpassword' ) );
		}

		if ( isset( $option['pass'] ) ) {
			$this->setVar( '_AdminPassword', $option['pass'] );
		}
	}

	/**
	 * Main entry point.
	 */
	public function execute() {
		$vars = Installer::getExistingLocalSettings();
		if( $vars ) {
			$this->showStatusMessage(
				Status::newFatal( "config-localsettings-cli-upgrade" )
			);
		}

		$this->performInstallation(
			array( $this, 'startStage' ),
			array( $this, 'endStage' )
		);
	}

	/**
	 * Write LocalSettings.php to a given path
	 *
	 * @param $path String Full path to write LocalSettings.php to
	 */
	public function writeConfigurationFile( $path ) {
		$ls = new LocalSettingsGenerator( $this );
		$ls->writeFile( "$path/LocalSettings.php" );
	}

	public function startStage( $step ) {
		$this->showMessage( "config-install-$step" );
	}

	public function endStage( $step, $status ) {
		$this->showStatusMessage( $status );
		$this->showMessage( 'config-install-step-done' );
	}

	public function showMessage( $msg /*, ... */ ) {
		echo $this->getMessageText( func_get_args() ) . "\n";
		flush();
	}

	public function showError( $msg /*, ... */ ) {
		echo "***{$this->getMessageText( func_get_args() )}***\n";
		flush();
	}

	/**
	 * @param $params array
	 *
	 * @return string
	 */
	protected function getMessageText( $params ) {
		$msg = array_shift( $params );

		$text = wfMsgExt( $msg, array( 'parseinline' ), $params );

		$text = preg_replace( '/<a href="(.*?)".*?>(.*?)<\/a>/', '$2 &lt;$1&gt;', $text );
		return html_entity_decode( strip_tags( $text ), ENT_QUOTES );
	}

	/**
	 * Dummy
	 */
	public function showHelpBox( $msg /*, ... */ ) {
	}

	public function showStatusMessage( Status $status ) {
		$warnings = array_merge( $status->getWarningsArray(),
			$status->getErrorsArray() );

		if ( count( $warnings ) !== 0 ) {
			foreach ( $warnings as $w ) {
				call_user_func_array( array( $this, 'showMessage' ), $w );
			}
		}

		if ( !$status->isOk() ) {
			echo "\n";
			exit;
		}
	}
}
