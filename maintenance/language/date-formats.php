<?php
/**
 * Test various language time and date functions
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
 * @ingroup MaintenanceLanguage
 */

require_once __DIR__ . '/../Maintenance.php';

/**
 * Maintenance script that tests various language time and date functions.
 *
 * @ingroup MaintenanceLanguage
 */
class DateFormats extends Maintenance {

	private $ts = '20010115123456';

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Test various language time and date functions' );
	}

	public function execute() {
		global $IP;
		foreach ( glob( "$IP/languages/messages/Messages*.php" ) as $filename ) {
			$base = basename( $filename );
			$m = [];
			if ( !preg_match( '/Messages(.*)\.php$/', $base, $m ) ) {
				continue;
			}
			$code = str_replace( '_', '-', strtolower( $m[1] ) );
			$this->output( "$code " );
			$lang = Language::factory( $code );
			$prefs = $lang->getDatePreferences();
			if ( !$prefs ) {
				$prefs = [ 'default' ];
			}
			$this->output( "date: " );
			foreach ( $prefs as $index => $pref ) {
				if ( $index > 0 ) {
					$this->output( ' | ' );
				}
				$this->output( $lang->date( $this->ts, false, $pref ) );
			}
			$this->output( "\n$code time: " );
			foreach ( $prefs as $index => $pref ) {
				if ( $index > 0 ) {
					$this->output( ' | ' );
				}
				$this->output( $lang->time( $this->ts, false, $pref ) );
			}
			$this->output( "\n$code both: " );
			foreach ( $prefs as $index => $pref ) {
				if ( $index > 0 ) {
					$this->output( ' | ' );
				}
				$this->output( $lang->timeanddate( $this->ts, false, $pref ) );
			}
			$this->output( "\n\n" );
		}
	}
}

$maintClass = "DateFormats";
require_once RUN_MAINTENANCE_IF_MAIN;
