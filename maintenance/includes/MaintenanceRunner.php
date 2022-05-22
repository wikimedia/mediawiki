<?php

namespace MediaWiki\Maintenance;

use Exception;
use LCStoreNull;
use Maintenance;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Settings\SettingsBuilder;

/**
 * A runner for maintenance scripts.
 *
 * @unstable
 */
class MaintenanceRunner {

	/** @var ?Maintenance */
	private $scriptObject = null;

	/**
	 * Initialize the runner
	 *
	 * @param string $scriptClass
	 */
	public function init( string $scriptClass ) {
		// Get an object to start us off
		$this->scriptObject = new $scriptClass();

		// Basic checks and such
		$this->scriptObject->setup();
	}

	/**
	 * Returns the maintenance script name.
	 *
	 * Safe to call after init().
	 *
	 * @return string
	 */
	public function getName(): string {
		// The name has been initialized by Maintenance::loadParamsAndArgs(),
		// which has been called by Maintenance::setup(), which was called
		// by $this->init().
		return $this->scriptObject->getName();
	}

	/**
	 * Define how settings are loaded (e.g. LocalSettings.php)
	 *
	 * @return void
	 */
	public function defineSettings() {
		// NOTE: This doesn't actually load anything, that will be done later
		//       by Setup.php. But it defines MW_CONFIG_CALLBACK and possibly
		//       other constants that control initialization.
		if ( !defined( 'MW_CONFIG_CALLBACK' ) ) {
			$this->scriptObject->loadSettings();
		}
	}

	/**
	 * MW_SETUP_CALLBACK handler, for overriding config.
	 *
	 * @param SettingsBuilder $settingsBuilder
	 *
	 * @return void
	 */
	public function overrideConfig( SettingsBuilder $settingsBuilder ) {
		$config = $settingsBuilder->getConfig();

		if ( $this->scriptObject->getDbType() === Maintenance::DB_NONE ) {
			$cacheConf = $config->get( MainConfigNames::LocalisationCacheConf );
			if ( $cacheConf['storeClass'] === false
				&& ( $cacheConf['store'] == 'db'
					|| ( $cacheConf['store'] == 'detect'
						&& !$config->get( MainConfigNames::CacheDirectory ) ) )
			) {
				$cacheConf['storeClass'] = LCStoreNull::class;
				$settingsBuilder->putConfigValue( MainConfigNames::LocalisationCacheConf, $cacheConf );
			}
		}

		$this->scriptObject->finalSetup( $settingsBuilder );
	}

	/**
	 * Run the maintenance script.
	 *
	 * @note The process should exit immediately after this method returns.
	 * At that point, MediaWiki will already have been shut down.
	 * It is no longer safe to perform any write operations on the database.
	 *
	 * @note Any exceptions thrown by the maintenance script will cause this
	 * method to terminate the process after reporting the error to the user,
	 * without shutdown and cleanup.
	 *
	 * @return bool true on success, false on failure,
	 *         passed through from Maintenance::execute().
	 */
	public function run(): bool {
		// Initialize main config instance
		$this->scriptObject->setConfig( MediaWikiServices::getInstance()->getMainConfig() );

		// Double check required extensions are installed
		$this->scriptObject->checkRequiredExtensions();

		if ( $this->scriptObject->getDbType() == Maintenance::DB_NONE ) {
			// Be strict with maintenance tasks that claim to not need a database by
			// disabling the storage backend.
			MediaWikiServices::disableStorageBackend();
		}

		$this->scriptObject->validateParamsAndArgs();

		// Do the work
		try {
			$success = $this->scriptObject->execute() !== false;

			// Potentially debug globals
			$this->scriptObject->globals();

			$this->scriptObject->shutdown();

			return $success;
		} catch ( Exception $ex ) {
			$exReportMessage = '';
			while ( $ex ) {
				$cls = get_class( $ex );
				$exReportMessage .= "$cls from line {$ex->getLine()} of {$ex->getFile()}: {$ex->getMessage()}\n";
				$exReportMessage .= $ex->getTraceAsString() . "\n";
				$ex = $ex->getPrevious();
			}
			$this->error( $exReportMessage );

			// Exit now because process is in an unsafe state.
			// Also to avoid DBTransactionError (T305730).
			// Do not commit database writes, do not run deferreds, do not pass Go.
			exit( 1 );
		}
	}

	/**
	 * @param string $msg
	 */
	private function error( string $msg ) {
		// Print to stderr if possible, don't mix it in with stdout output.
		if ( defined( 'STDERR' ) ) {
			fwrite( STDERR, $msg );
		} else {
			echo $msg;
		}
	}

}
