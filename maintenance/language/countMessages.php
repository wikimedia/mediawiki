<?php
/**
 * Count how many messages we have defined for each language.
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
 * @ingroup MaintenanceLanguage
 */

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

class CountMessages extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Count how many messages we have defined for each language";
	}

	public function execute() {
		global $IP;
		$dir = $this->getArg( 0, "$IP/languages/messages" );
		$total = 0;
		$nonZero = 0;
		foreach ( glob( "$dir/*.php" ) as $file ) {
			$baseName = basename( $file );
			if ( !preg_match( '/Messages([A-Z][a-z_]+)\.php$/', $baseName, $m ) ) {
				continue;
			}

			$numMessages = $this->getNumMessages( $file );
			// print "$code: $numMessages\n";
			$total += $numMessages;
			if ( $numMessages > 0 ) {
				$nonZero ++;
			}
		}
		$this->output( "\nTotal: $total\n" );
		$this->output( "Languages: $nonZero\n" );
	}

	private function getNumMessages( $file ) {
		// Separate function to limit scope
		require( $file );
		if ( isset( $messages ) ) {
			return count( $messages );
		} else {
			return 0;
		}
	}
}

$maintClass = "CountMessages";
require_once( DO_MAINTENANCE );
