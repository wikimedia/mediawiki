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
		$result = $checker->check();
		if ( $result->isGood() ) {
			// We couldn't find any out-of-date dependencies, so assume everything is ok!
			$this->output( "Your composer.lock file is up to date with current dependencies!\n" );
		} else {
			// NOTE: wfMessage will fail if MediaWikiServices is not yet initialized.
			// This can happen when this class is called directly from bootstrap code,
			// e.g. by TestSetup. We get around this by having testSetup use quiet mode.
			if ( !$this->isQuiet() ) {
				foreach ( $result->getErrors() as $error ) {
					$this->error(
						wfMessage( $error['message'], ...$error['params'] )->inLanguage( 'en' )->plain() . "\n"
					);
				}
			}
			$this->fatalError(
				'Error: your composer.lock file is not up to date. ' .
				'Run "composer update --no-dev" to install newer dependencies'
			);
		}
	}
}

$maintClass = CheckComposerLockUpToDate::class;
require_once RUN_MAINTENANCE_IF_MAIN;
