<?php
/**
 * Communications protocol.
 * This is used by dumpTextPass.php when the --spawn option is present.
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

use Wikimedia\Rdbms\IDatabase;

/**
 * Maintenance script used to fetch page text in a subprocess.
 *
 * @ingroup Maintenance
 */
class FetchText extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( "Fetch the raw revision blob from an old_id.\n" .
			"NOTE: Export transformations are NOT applied. " .
			"This is left to backupTextPass.php"
		);
	}

	/**
	 * returns a string containing the following in order:
	 *   textid
	 *   \n
	 *   length of text (-1 on error = failure to retrieve/unserialize/gunzip/etc)
	 *   \n
	 *   text  (may be empty)
	 *
	 * note that the text string itself is *not* followed by newline
	 */
	public function execute() {
		$db = $this->getDB( DB_REPLICA );
		$stdin = $this->getStdin();
		while ( !feof( $stdin ) ) {
			$line = fgets( $stdin );
			if ( $line === false ) {
				// We appear to have lost contact...
				break;
			}
			$textId = intval( $line );
			$text = $this->doGetText( $db, $textId );
			if ( $text === false ) {
				# actual error, not zero-length text
				$textLen = "-1";
			} else {
				$textLen = strlen( $text );
			}
			$this->output( $textId . "\n" . $textLen . "\n" . $text );
		}
	}

	/**
	 * May throw a database error if, say, the server dies during query.
	 * @param IDatabase $db
	 * @param int $id The old_id
	 * @return string
	 */
	private function doGetText( $db, $id ) {
		$id = intval( $id );
		$row = $db->selectRow( 'text',
			[ 'old_text', 'old_flags' ],
			[ 'old_id' => $id ],
			__METHOD__ );
		$text = Revision::getRevisionText( $row );
		if ( $text === false ) {
			return false;
		}

		return $text;
	}
}

$maintClass = FetchText::class;
require_once RUN_MAINTENANCE_IF_MAIN;
