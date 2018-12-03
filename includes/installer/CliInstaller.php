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

use MediaWiki\MediaWikiServices;

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
	 * @param string|null $admin
	 * @param array $options
	 */
	function __construct( $siteName, $admin = null, array $options = [] ) {
		global $wgContLang;

		parent::__construct();

		if ( isset( $options['scriptpath'] ) ) {
			$this->specifiedScriptPath = true;
		}

		foreach ( $this->optionMap as $opt => $global ) {
			if ( isset( $options[$opt] ) ) {
				$GLOBALS[$global] = $options[$opt];
				$this->setVar( $global, $options[$opt] );
			}
		}

		if ( isset( $options['lang'] ) ) {
			global $wgLang, $wgLanguageCode;
			$this->setVar( '_UserLang', $options['lang'] );
			$wgLanguageCode = $options['lang'];
			$this->setVar( 'wgLanguageCode', $wgLanguageCode );
			$wgContLang = MediaWikiServices::getInstance()->getContentLanguage();
			$wgLang = Language::factory( $options['lang'] );
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

		if ( !isset( $options['installdbuser'] ) ) {
			$this->setVar( '_InstallUser',
				$this->getVar( 'wgDBuser' ) );
			$this->setVar( '_InstallPassword',
				$this->getVar( 'wgDBpassword' ) );
		} else {
			$this->setVar( '_InstallUser',
				$options['installdbuser'] );
			$this->setVar( '_InstallPassword',
				$options['installdbpass'] ?? "" );

			// Assume that if we're given the installer user, we'll create the account.
			$this->setVar( '_CreateDBAccount', true );
		}

		if ( isset( $options['pass'] ) ) {
			$this->setVar( '_AdminPassword', $options['pass'] );
		}

		// Detect and inject any extension found
		if ( isset( $options['extensions'] ) ) {
			$status = $this->validateExtensions(
				'extension', 'extensions', $options['extensions'] );
			if ( !$status->isOK() ) {
				$this->showStatusMessage( $status );
			}
			$this->setVar( '_Extensions', $status->value );
		} elseif ( isset( $options['with-extensions'] ) ) {
			$this->setVar( '_Extensions', array_keys( $this->findExtensions() ) );
		}

		// Set up the default skins
		if ( isset( $options['skins'] ) ) {
			$status = $this->validateExtensions( 'skin', 'skins', $options['skins'] );
			if ( !$status->isOK() ) {
				$this->showStatusMessage( $status );
			}
			$skins = $status->value;
		} else {
			$skins = array_keys( $this->findExtensions( 'skins' ) );
		}
		$this->setVar( '_Skins', $skins );

		if ( $skins ) {
			$skinNames = array_map( 'strtolower', $skins );
			$this->setVar( 'wgDefaultSkin', $this->getDefaultSkin( $skinNames ) );
		}
	}

	private function validateExtensions( $type, $directory, $nameLists ) {
		$extensions = [];
		$status = new Status;
		foreach ( (array)$nameLists as $nameList ) {
			foreach ( explode( ',', $nameList ) as $name ) {
				$name = trim( $name );
				if ( $name === '' ) {
					continue;
				}
				$extStatus = $this->getExtensionInfo( $type, $directory, $name );
				if ( $extStatus->isOK() ) {
					$extensions[] = $name;
				} else {
					$status->merge( $extStatus );
				}
			}
		}
		$extensions = array_unique( $extensions );
		$status->value = $extensions;
		return $status;
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
				$this->showMessage( ...$w );
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
