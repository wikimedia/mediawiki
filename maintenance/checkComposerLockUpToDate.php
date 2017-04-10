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
	}

	public function execute() {
		global $IP;
		$lockLocation = "$IP/composer.lock";
		$jsonLocation = "$IP/composer.json";
		if ( !file_exists( $lockLocation ) ) {
			// Maybe they're using mediawiki/vendor?
			$lockLocation = "$IP/vendor/composer.lock";
			if ( !file_exists( $lockLocation ) ) {
				$this->error(
					'Could not find composer.lock file. Have you run "composer install"?',
					1
				);
			}
		}

		$lock = new ComposerLock( $lockLocation );
		$json = new ComposerJson( $jsonLocation );

		if ( $lock->getHash() === $json->getHash() ) {
			$this->output( "Your composer.lock file is up to date with current dependencies!\n" );
			return;
		}
		// Out of date, lets figure out which dependencies are old
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
					'Run "composer update" to install newer dependencies',
				1
			);
		} else {
			// The hash is the entire composer.json file,
			// so it can be updated without any of the dependencies changing
			// We couldn't find any out-of-date dependencies, so assume everything is ok!
			$this->output( "Your composer.lock file is up to date with current dependencies!\n" );
		}

	}
}

$maintClass = 'CheckComposerLockUpToDate';
require_once RUN_MAINTENANCE_IF_MAIN;
