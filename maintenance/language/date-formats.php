<?php
/**
 * Test various language time and date functions
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup MaintenanceLanguage
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that tests various language time and date functions.
 *
 * @ingroup MaintenanceLanguage
 */
class DateFormats extends Maintenance {

	private const TS = '20010115123456';

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Test various language time and date functions' );
	}

	public function execute() {
		foreach ( glob( MW_INSTALL_PATH . '/languages/messages/Messages*.php' ) as $filename ) {
			$base = basename( $filename );
			$m = [];
			if ( !preg_match( '/Messages(.*)\.php$/', $base, $m ) ) {
				continue;
			}
			$code = str_replace( '_', '-', strtolower( $m[1] ) );
			$this->output( "$code " );
			$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( $code );
			$prefs = $lang->getDatePreferences();
			if ( !$prefs ) {
				$prefs = [ 'default' ];
			}
			$this->output( "date: " );
			foreach ( $prefs as $index => $pref ) {
				if ( $index > 0 ) {
					$this->output( ' | ' );
				}
				$this->output( $lang->date( self::TS, false, $pref ) );
			}
			$this->output( "\n$code time: " );
			foreach ( $prefs as $index => $pref ) {
				if ( $index > 0 ) {
					$this->output( ' | ' );
				}
				$this->output( $lang->time( self::TS, false, $pref ) );
			}
			$this->output( "\n$code both: " );
			foreach ( $prefs as $index => $pref ) {
				if ( $index > 0 ) {
					$this->output( ' | ' );
				}
				$this->output( $lang->timeanddate( self::TS, false, $pref ) );
			}
			$this->output( "\n\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = DateFormats::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
