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
class CliInstaller extends CoreInstaller {

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
		'dbts2schema' => 'wgDBts2schema',
		'dbpath' => 'wgSQLiteDataDir',
		'scriptpath' => 'wgScriptPath',
		'wikiroot' => 'wgScriptPath',
		'upgrade' => 'cliUpgrade', /* As long as it isn't $confItems
									* in LocalSettingsGenerator, we
									* should be fine. */
	);

	/**
	 * Constructor.
	 *
	 * @param $siteName
	 * @param $admin
	 * @param $option Array
	 */
	function __construct( $siteName, $admin = null, array $option = array() ) {
		parent::__construct();

		foreach ( $this->optionMap as $opt => $global ) {
			if ( isset( $option[$opt] ) ) {
				$GLOBALS[$global] = $option[$opt];
				$this->setVar( $global, $option[$opt] );
			}
		}

		if ( isset( $option['lang'] ) ) {
			global $wgLang, $wgContLang, $wgLanguageCode;
			$this->setVar( '_UserLang', $option['lang'] );
			$wgContLang = Language::factory( $option['lang'] );
			$wgLang = Language::factory( $option['lang'] );
			$wgLanguageCode = $option['lang'];
		}

		$this->setVar( 'wgSitename', $siteName );

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
		global $cliUpgrade;

		$vars = $this->getExistingLocalSettings();
		if( $vars && ( !isset( $cliUpgrade ) || $cliUpgrade !== "yes" )  ) {
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
		$this->showMessage( wfMsg( "config-install-$step" ) .
			wfMsg( 'ellipsis' ) . wfMsg( 'word-separator' ) );
	}

	public function endStage( $step, $status ) {
		$this->showStatusMessage( $status );
		$this->showMessage( wfMsg( 'config-install-step-done' ) . "\n" );
	}

	public function showMessage( $msg /*, ... */ ) {
		$params = func_get_args();
		array_shift( $params );

		/* parseinline has the nasty side-effect of putting encoded
		 * angle brackets, around the message, so the substr removes
		 * them. */
		$text = substr( wfMsgExt( $msg, array( 'parseinline' ), $params ), 4, -4 );
		$text = preg_replace( '/<a href="(.*?)".*?>(.*?)<\/a>/', '$2 &lt;$1&gt;', $text );
		echo html_entity_decode( strip_tags( $text ), ENT_QUOTES ) . "\n";
		flush();
	}

	public function showStatusMessage( Status $status ) {
		$warnings = array_merge( $status->getWarningsArray(),
			$status->getErrorsArray() );

		if ( count( $warnings ) !== 0 ) {
			foreach ( $status->getWikiTextArray( $warnings ) as $w ) {
				$this->showMessage( $w . wfMsg( 'ellipsis' ) .
					wfMsg( 'word-separator' ) );
			}
		}

		if ( !$status->isOk() ) {
			echo "\n";
			exit;
		}
	}
}
