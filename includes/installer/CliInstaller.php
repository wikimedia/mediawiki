<?php
/**
 * Core installer command line interface.
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

/**
 * Class for the core installer command line interface.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class CliInstaller extends Installer {
	private $specifiedScriptPath = false;

	private $optionMap = [
		'dbtype' => 'wgDBtype',
		'dbserver' => 'wgDBserver',
		'dbname' => 'wgDBname',
		'dbuser' => 'wgDBuser',
		'dbpass' => 'wgDBpassword',
		'dbprefix' => 'wgDBprefix',
		'dbtableoptions' => 'wgDBTableOptions',
		'dbport' => 'wgDBport',
		'dbschema' => 'wgDBmwschema',
		'dbpath' => 'wgSQLiteDataDir',
		'server' => 'wgServer',
		'scriptpath' => 'wgScriptPath',
	];

	/**
	 * @param string $siteName
	 * @param string $admin
	 * @param array $option
	 */
	function __construct( $siteName, $admin = null, array $option = [] ) {
		global $wgContLang;

		parent::__construct();

		if ( isset( $option['scriptpath'] ) ) {
			$this->specifiedScriptPath = true;
		}

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
			RequestContext::getMain()->setLanguage( $wgLang );
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
		} else {
			$this->setVar( '_InstallUser',
				$option['installdbuser'] );
			$this->setVar( '_InstallPassword',
				isset( $option['installdbpass'] ) ? $option['installdbpass'] : "" );

			// Assume that if we're given the installer user, we'll create the account.
			$this->setVar( '_CreateDBAccount', true );
		}

		if ( isset( $option['pass'] ) ) {
			$this->setVar( '_AdminPassword', $option['pass'] );
		}

		// Detect and inject any extension found
		if ( isset( $option['with-extensions'] ) ) {
			$this->setVar( '_Extensions', array_keys( $this->findExtensions() ) );
		}

		// Set up the default skins
		$skins = array_keys( $this->findExtensions( 'skins' ) );
		$this->setVar( '_Skins', $skins );

		if ( $skins ) {
			$skinNames = array_map( 'strtolower', $skins );
			$this->setVar( 'wgDefaultSkin', $this->getDefaultSkin( $skinNames ) );
		}
	}

	/**
	 * Main entry point.
	 */
	public function execute() {
		$vars = Installer::getExistingLocalSettings();
		if ( $vars ) {
			$this->showStatusMessage(
				Status::newFatal( "config-localsettings-cli-upgrade" )
			);
		}

		$this->performInstallation(
			[ $this, 'startStage' ],
			[ $this, 'endStage' ]
		);
	}

	/**
	 * Write LocalSettings.php to a given path
	 *
	 * @param string $path Full path to write LocalSettings.php to
	 */
	public function writeConfigurationFile( $path ) {
		$ls = InstallerOverrides::getLocalSettingsGenerator( $this );
		$ls->writeFile( "$path/LocalSettings.php" );
	}

	public function startStage( $step ) {
		// Messages: config-install-database, config-install-tables, config-install-interwiki,
		// config-install-stats, config-install-keys, config-install-sysop, config-install-mainpage,
		// config-install-extensions
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
	 * @param array $params
	 *
	 * @return string
	 */
	protected function getMessageText( $params ) {
		$msg = array_shift( $params );

		$text = wfMessage( $msg, $params )->parse();

		$text = preg_replace( '/<a href="(.*?)".*?>(.*?)<\/a>/', '$2 &lt;$1&gt;', $text );

		return Sanitizer::stripAllTags( $text );
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
				call_user_func_array( [ $this, 'showMessage' ], $w );
			}
		}

		if ( !$status->isOK() ) {
			echo "\n";
			exit( 1 );
		}
	}

	public function envCheckPath() {
		if ( !$this->specifiedScriptPath ) {
			$this->showMessage( 'config-no-cli-uri', $this->getVar( "wgScriptPath" ) );
		}

		return parent::envCheckPath();
	}

	protected function envGetDefaultServer() {
		return null; // Do not guess if installing from CLI
	}

	public function dirIsExecutable( $dir, $url ) {
		$this->showMessage( 'config-no-cli-uploads-check', $dir );

		return false;
	}
}
