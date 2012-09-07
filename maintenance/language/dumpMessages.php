<?php
/**
 * Dump an entire language, using the keys from English
 * so we get all the values, not just the customized ones
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
 * @todo Make this more useful, right now just dumps $wgContLang
 */

require_once( __DIR__ . '/../Maintenance.php' );

/**
 * Maintenance script that dumps an entire language, using the keys from English.
 *
 * @ingroup MaintenanceLanguage
 */
class DumpMessages extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Dump an entire language, using the keys from English";
	}

	public function execute() {
		global $wgVersion;

		$messages = array();
		foreach ( array_keys( Language::getMessagesFor( 'en' ) ) as $key ) {
			$messages[$key] = wfMessage( $key )->text();
		}
		$this->output( "MediaWiki $wgVersion language file\n" );
		$this->output( serialize( $messages ) );
	}
}

$maintClass = "DumpMessages";
require_once( RUN_MAINTENANCE_IF_MAIN );
