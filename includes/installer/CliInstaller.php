<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use MediaWiki\Context\RequestContext;
use MediaWiki\Installer\Task\Task;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Password\UserPasswordPolicy;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use Wikimedia\Message\MessageSpecifier;

/**
 * Class for the core installer command line interface.
 *
 * @ingroup Installer
 * @since 1.17
 */
class CliInstaller extends Installer {
	/** @var bool */
	private $specifiedScriptPath = false;

	private const OPTION_MAP = [
		'dbtype' => 'wgDBtype',
		'dbserver' => 'wgDBserver',
		'dbname' => 'wgDBname',
		'dbuser' => 'wgDBuser',
		'dbpass' => 'wgDBpassword',
		'dbprefix' => 'wgDBprefix',
		'dbtableoptions' => 'wgDBTableOptions',
		'dbport' => 'wgDBport',
		'dbssl' => 'wgDBssl',
		'dbschema' => 'wgDBmwschema',
		'dbpath' => 'wgSQLiteDataDir',
		'server' => 'wgServer',
		'scriptpath' => 'wgScriptPath',
	];

	/**
	 * @param string $siteName
	 * @param string|null $admin
	 * @param array $options
	 * @throws InstallException
	 */
	public function __construct( $siteName, $admin = null, array $options = [] ) {
		global $wgPasswordPolicy;

		parent::__construct();

		if ( isset( $options['scriptpath'] ) ) {
			$this->specifiedScriptPath = true;
		}

		foreach ( self::OPTION_MAP as $opt => $global ) {
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
			$wgLang = MediaWikiServices::getInstance()->getLanguageFactory()
				->getLanguage( $options['lang'] );
			RequestContext::getMain()->setLanguage( $wgLang );
		}

		$this->setVar( 'wgSitename', $siteName );

		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$metaNS = $contLang->ucfirst( str_replace( ' ', '_', $siteName ) );
		if ( $metaNS == 'MediaWiki' ) {
			$metaNS = 'Project';
		}
		$this->setVar( 'wgMetaNamespace', $metaNS );

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

		if ( $admin ) {
			$this->setVar( '_AdminName', $admin );
			if ( isset( $options['pass'] ) ) {
				$adminUser = User::newFromName( $admin );
				if ( !$adminUser ) {
					throw new InstallException( Status::newFatal( 'config-admin-name-invalid' ) );
				}
				$upp = new UserPasswordPolicy(
					$wgPasswordPolicy['policies'],
					$wgPasswordPolicy['checks']
				);
				$status = $upp->checkUserPasswordForGroups( $adminUser, $options['pass'],
					[ 'bureaucrat', 'sysop', 'interface-admin' ] ); // per Installer::createSysop()
				if ( !$status->isGood() ) {
					throw new InstallException( Status::newFatal(
						$status->getMessage( 'config-admin-error-password-invalid' ) ) );
				}
				$this->setVar( '_AdminPassword', $options['pass'] );
			}
		}

		// Detect and inject any extension found
		if ( isset( $options['extensions'] ) ) {
			$status = $this->validateExtensions(
				'extension', 'extensions', $options['extensions'] );
			if ( !$status->isOK() ) {
				throw new InstallException( $status );
			}
			$this->setVar( '_Extensions', $status->value );
		} elseif ( isset( $options['with-extensions'] ) ) {
			$status = $this->findExtensions();
			if ( !$status->isOK() ) {
				throw new InstallException( $status );
			}
			$this->setVar( '_Extensions', array_keys( $status->value ) );
		}

		// Set up the default skins
		if ( isset( $options['skins'] ) ) {
			$status = $this->validateExtensions( 'skin', 'skins', $options['skins'] );
			if ( !$status->isOK() ) {
				throw new InstallException( $status );
			}
			$skins = $status->value;
		} else {
			$status = $this->findExtensions( 'skins' );
			if ( !$status->isOK() ) {
				throw new InstallException( $status );
			}
			$skins = array_keys( $status->value );
		}
		$this->setVar( '_Skins', $skins );

		if ( $skins ) {
			$skinNames = array_map( 'strtolower', $skins );
			$this->setVar( 'wgDefaultSkin', $this->getDefaultSkin( $skinNames ) );
		}

		$this->setVar( '_WithDevelopmentSettings', isset( $options['with-developmentsettings'] ) );
	}

	/**
	 * @param string $type
	 * @param string $directory
	 * @param string|string[] $nameLists
	 */
	private function validateExtensions( string $type, string $directory, $nameLists ): Status {
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
	 * @return Status
	 */
	public function execute() {
		// If APC is available, use that as the MainCacheType, instead of nothing.
		// This is hacky and should be consolidated with WebInstallerOptions.
		// This is here instead of in __construct(), because it should run after
		// doEnvironmentChecks(), which populates '_Caches'.
		if ( count( $this->getVar( '_Caches' ) ) ) {
			// We detected a CACHE_ACCEL implementation, use it.
			$this->setVar( '_MainCacheType', 'accel' );
		}

		$vars = Installer::getExistingLocalSettings();
		if ( $vars ) {
			$status = Status::newFatal( "config-localsettings-cli-upgrade" );
			$this->showStatusMessage( $status );
			return $status;
		}

		$status = $this->performInstallation(
			$this->startStage( ... ),
			$this->endStage( ... )
		);
		if ( $status->isOK() ) {
			return Status::newGood();
		} else {
			return $status;
		}
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

	/**
	 * @param Task $task
	 */
	public function startStage( $task ) {
		// @phan-suppress-next-line SecurityCheck-XSS -- it's CLI
		echo $this->formatMessage( $task->getDescriptionMessage() ) . '... ';
	}

	/**
	 * @param Task $task
	 * @param Status $status
	 */
	public function endStage( $task, $status ) {
		$this->showStatusMessage( $status );
		if ( $status->isOK() ) {
			$this->showMessage( 'config-install-step-done' );
		} else {
			$this->showError( 'config-install-step-failed' );
		}
	}

	/** @inheritDoc */
	public function showMessage( $msg, ...$params ) {
		// @phan-suppress-next-line SecurityCheck-XSS
		echo $this->getMessageText( $msg, $params ) . "\n";
		flush();
	}

	/** @inheritDoc */
	public function showSuccess( $msg, ...$params ) {
		// @phan-suppress-next-line SecurityCheck-XSS
		echo $this->getMessageText( $msg, $params ) . "\n";
		flush();
	}

	/** @inheritDoc */
	public function showWarning( $msg, ...$params ) {
		// @phan-suppress-next-line SecurityCheck-XSS
		echo $this->getMessageText( $msg, $params ) . "\n";
		flush();
	}

	/** @inheritDoc */
	public function showError( $msg, ...$params ) {
		// @phan-suppress-next-line SecurityCheck-XSS
		echo "***{$this->getMessageText( $msg, $params )}***\n";
		flush();
	}

	/**
	 * @param string|MessageSpecifier $msg
	 * @param (string|int|float)[] $params Message parameters
	 * @return string
	 */
	protected function getMessageText( $msg, $params ) {
		return $this->formatMessage( wfMessage( $msg, $params ) );
	}

	/**
	 * @param Message $message
	 * @return string
	 */
	protected function formatMessage( $message ) {
		$text = $message->parse();
		$text = preg_replace( '/<a href="(.*?)".*?>(.*?)<\/a>/', '$2 &lt;$1&gt;', $text );
		return Sanitizer::stripAllTags( $text );
	}

	public function showStatusMessage( Status $status ) {
		// Show errors at the end in CLI installer to make them easier to notice
		foreach ( $status->getMessages( 'warning' ) as $msg ) {
			$this->showMessage( $msg );
		}
		foreach ( $status->getMessages( 'error' ) as $msg ) {
			$this->showMessage( $msg );
		}
	}

	/** @inheritDoc */
	public function envCheckPath() {
		if ( !$this->specifiedScriptPath ) {
			$this->showMessage( 'config-no-cli-uri', $this->getVar( "wgScriptPath" ) );
		}

		return parent::envCheckPath();
	}

	/** @inheritDoc */
	protected function envGetDefaultServer() {
		// Use a basic value if the user didn't pass in --server
		return 'http://localhost';
	}

	/** @inheritDoc */
	public function dirIsExecutable( $dir, $url ) {
		$this->showMessage( 'config-no-cli-uploads-check', $dir );

		return false;
	}
}
