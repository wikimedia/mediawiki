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
		$this->mDescription = 'Checks whether your composer.lock file is up to date with the current composer.json';
	}

	public function execute() {
		global $IP;
		$lock = "$IP/composer.lock";
		$json = "$IP/composer.json";
		if ( !file_exists( $lock ) ) {
			$this->error( "Could not find composer.lock file at $lock", 1 );
		}
		// This is the same thing that composer does internally
		$hash = md5_file( $json );
		$lockContents = FormatJson::decode( file_get_contents( $lock ), true );
		if ( $lockContents['hash'] === $hash ) {
			$this->output( "Your composer.lock file is up to date with current dependencies!\n" );
			return;
		}
		$jsonContents = FormatJson::decode( file_get_contents( $json ), true );
		$deps = $jsonContents['require'];
		$installed = $lockContents['packages'];
		// Out of date, lets figure out which dependencies are old
		$found = false;
		foreach ( $installed as $package ) {
			$shouldHaveVersion = $deps[$package['name']];
			$haveVersion = $package['version'];
			if ( strpos( $haveVersion, 'v' ) === 0 ) {
				// Composer auto-strips the "v" in front of the tag name
				$haveVersion = ltrim( $haveVersion, 'v' );
			}
			if ( $haveVersion !== $shouldHaveVersion ) {
				$this->output( "{$package['name']}: $haveVersion installed, $shouldHaveVersion required.\n" );
				$found = true;
			}
		}
		if ( $found ) {
			$this->error( 'Error: your composer.lock file is not up to date, run "composer update" to install newer dependencies', 1 );
		} else {
			// The hash is the entire composer.json file, so it can be updated without any of the dependencies changing
			// We couldn't find any out-of-date dependencies, so assume everything is ok!
			$this->output( "Your composer.lock file is up to date with current dependencies!\n" );
		}

	}
}

$maintClass = 'CheckComposerLockUpToDate';
require_once RUN_MAINTENANCE_IF_MAIN;