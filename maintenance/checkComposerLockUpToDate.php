<?php

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\Composer\LockFileChecker;
use Wikimedia\Composer\ComposerJson;
use Wikimedia\Composer\ComposerLock;

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

	public function canExecuteWithoutLocalSettings(): bool {
		return true;
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

		// Check all the dependencies to see if any are old
		$checker = new LockFileChecker( $json, $lock );
		$errors = $checker->check();

		// NOTE: This is used by TestSetup before MediaWikiServices is initialized and thus
		//       may not rely on global singletons.
		// NOTE: This is used by maintenance/update.php and thus may not rely on
		//       database connections, including e.g. interface messages without useDatabase=false,
		//       which would call MessageCache.
		if ( $errors ) {
			foreach ( $errors as $error ) {
				$this->error( $error . "\n" );
			}
			$this->fatalError(
				'Error: your composer.lock file is not up to date. ' .
				'Run "composer update --no-dev" to install newer dependencies'
			);
		} else {
			// We couldn't find any out-of-date dependencies, so assume everything is ok!
			$this->output( "Your composer.lock file is up to date with current dependencies!\n" );
		}
	}
}

$maintClass = CheckComposerLockUpToDate::class;
require_once RUN_MAINTENANCE_IF_MAIN;
