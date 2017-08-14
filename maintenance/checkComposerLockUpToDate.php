<?php

require_once __DIR__ . '/Maintenance.php';

/**
 * Checks whether your composer-installed dependencies are up to date
 *
 * Composer creates a "composer.lock" file which specifies which versions are installed
 * (via `composer install`). It has a hash, which can be compared to the value of
 * the composer.json file to see if dependencies are up to date.
 */
class CheckComposerLockUpToDate extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Checks whether your composer.lock file is up to date with the current composer.json' );
		$this->addOption( 'without-extensions', 'Also recurse the extensions folder' );
	}

	public function execute() {
		global $IP;

		$this->updateComposerDirectory( "$IP", true );

		if ( $this->hasOption( 'without-extensions' ) ) {
			return;
		}

		$installedExtensions = ExtensionRegistry::getInstance()->getAllThings();

		foreach ( $installedExtensions as $extensionName => $extensionInfo ) {
			$directory = dirname( $extensionInfo[ 'path' ] );
			if ( !$this->isSuitablePath( $directory ) ) {
				continue;
			}
			$this->output( "Checking composer dependencies for: $extensionName\n" );
			$this->updateComposerDirectory( dirname( $extensionInfo[ 'path' ] ) );
		}
	}

	public function updateComposerDirectory( $directory, $root = false ) {
		global $IP;
		$lockLocation = "$directory/composer.lock";
		$jsonLocation = "$directory/composer.json";
		$workingDir = $root ? '' : " -d \"{$directory}\"";

		if ( !file_exists( $lockLocation ) ) {
			// Maybe they're using mediawiki/vendor?
			$lockLocation = "$IP/vendor/composer.lock";
			if ( !file_exists( $lockLocation ) ) {
				$this->error(
					"Could not find composer.lock file.\n" .
					"Have you run 'composer install{$workingDir}' ?\n",
					$root ? true : false
				);
				return;
			}
		}

		$lock = new ComposerLock( $lockLocation );
		$json = new ComposerJson( $jsonLocation );

		// Check all the dependencies to see if any are old
		$found = false;
		$installed = $lock->getInstalledDependencies();
		foreach ( $json->getRequiredDependencies() as $name => $version ) {
			if ( isset( $installed[$name] ) ) {
				if ( $installed[$name]['version'] !== $version ) {
					$this->output(
						"$name: {$installed[$name]['version']} installed, $version required.\n"
					);
					$found = true;
				}
			} else {
				$this->output( "$name: not installed, $version required.\n" );
				$found = true;
			}
		}
		if ( $found ) {
			$this->error(
				'Error: your composer.lock file is not up to date. ' .
					"Run 'composer update{$workingDir}' to install newer dependencies",
				1
			);
		} else {
			// We couldn't find any out-of-date dependencies, so assume everything is ok!
			$this->output( "Your composer.lock file is up to date with current dependencies!\n" );
		}
	}

	/**
	 * Returns true if $directory is of a type we can check
	 * @param string $directory
	 * @return bool
	 */
	private function isSuitablePath( $directory ) {
		$directory = str_replace( '\\', '/', $directory );
		if ( file_exists( "$directory/composer.json" ) ) {
			return true;
		}

		return false;
	}
}

$maintClass = 'CheckComposerLockUpToDate';
require_once RUN_MAINTENANCE_IF_MAIN;
