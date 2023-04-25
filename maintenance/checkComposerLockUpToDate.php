<?php

require_once __DIR__ . '/Maintenance.php';

use Composer\Semver\Semver;

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
				$this->fatalError(
					'Could not find composer.lock file. Have you run "composer install --no-dev"?'
				);
			}
		}

		$lock = new ComposerLock( $lockLocation );
		$json = new ComposerJson( $jsonLocation );

		$requiredButOld = [];
		$requiredButMissing = [];

		// Check all the dependencies to see if any are old
		$installed = $lock->getInstalledDependencies();
		foreach ( $json->getRequiredDependencies() as $name => $version ) {
			// Not installed at all.
			if ( !isset( $installed[$name] ) ) {
				$requiredButMissing[] = [
					'name' => $name,
					'wantedVersion' => $version
				];
				continue;
			}

			// Installed; need to check it's the right version
			if ( !SemVer::satisfies( $installed[$name]['version'], $version ) ) {
				$requiredButOld[] = [
					'name' => $name,
					'wantedVersion' => $version,
					'suppliedVersion' => $installed[$name]['version']
				];
			}

			// We're happy; loop to the next dependency.
		}

		if ( count( $requiredButOld ) === 0 && count( $requiredButMissing ) === 0 ) {
			// We couldn't find any out-of-date or missing dependencies, so assume everything is ok!
			$this->output( "Your composer.lock file is up to date with current dependencies!\n" );
			return;
		}

		foreach ( $requiredButOld as [
			"name" => $name,
			"suppliedVersion" => $suppliedVersion,
			"wantedVersion" => $wantedVersion
		] ) {
			$this->error( "$name: $suppliedVersion installed, $wantedVersion required.\n" );
		}

		foreach ( $requiredButMissing as [
			"name" => $name,
			"wantedVersion" => $wantedVersion
		] ) {
			$this->error( "$name: not installed, $wantedVersion required.\n" );
		}

		$this->fatalError(
			'Error: your composer.lock file is not up to date. ' .
				'Run "composer update --no-dev" to install newer dependencies'
		);
	}
}

$maintClass = CheckComposerLockUpToDate::class;
require_once RUN_MAINTENANCE_IF_MAIN;
