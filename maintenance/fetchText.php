<?php
/**
 * Communications protocol...
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
 * @ingroup Maintenance
 */

require_once( dirname(__FILE__) . '/Maintenance.php' );

class FetchText extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Fetch the revision text from an old_id";
	}

	public function execute() {
		$db = wfGetDB( DB_SLAVE );
		$stdin = $this->getStdin();
		while( !feof( $stdin ) ) {
			$line = fgets( $stdin );
			if( $line === false ) {
				// We appear to have lost contact...
				break;
			}
			$textId = intval( $line );
			$text = $this->doGetText( $db, $textId );
			$this->output( strlen( $text ) . "\n". $text );
		}
	}
	
	/**
 	 * May throw a database error if, say, the server dies during query.
	 * @param $db Database object
	 * @param $id int The old_id
	 * @return String
 	 */
	private function doGetText( $db, $id ) {
		$id = intval( $id );
		$row = $db->selectRow( 'text',
			array( 'old_text', 'old_flags' ),
			array( 'old_id' => $id ),
			'TextPassDumper::getText' );
		$text = Revision::getRevisionText( $row );
		if( $text === false ) {
			return false;
		}
		return $text;
	}
}

$maintClass = "FetchText";
require_once( DO_MAINTENANCE );
