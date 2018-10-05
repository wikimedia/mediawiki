<?php
/**
 * Normalize double-byte Latin UTF-8 characters
 *
 * Usage: php updateDoubleWidthSearch.php
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

/**
 * Maintenance script to normalize double-byte Latin UTF-8 characters.
 *
 * @ingroup Maintenance
 */
class UpdateDoubleWidthSearch extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to normalize double-byte Latin UTF-8 characters' );
		$this->addOption( 'q', 'quiet', false, true );
		$this->addOption(
			'l',
			'How long the searchindex and revision tables will be locked for',
			false,
			true
		);
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		$maxLockTime = $this->getOption( 'l', 20 );

		$dbw = $this->getDB( DB_MASTER );
		if ( $dbw->getType() !== 'mysql' ) {
			$this->fatalError( "This change is only needed on MySQL, quitting.\n" );
		}

		$res = $this->findRows( $dbw );
		$this->updateSearchIndex( $maxLockTime, [ $this, 'searchIndexUpdateCallback' ], $dbw, $res );

		$this->output( "Done\n" );
	}

	public function searchIndexUpdateCallback( $dbw, $row ) {
		return $this->updateSearchIndexForPage( $dbw, $row->si_page );
	}

	private function findRows( $dbw ) {
		$searchindex = $dbw->tableName( 'searchindex' );
		$regexp = '[[:<:]]u8efbd([89][1-9a]|8[b-f]|90)[[:>:]]';
		$sql = "SELECT si_page FROM $searchindex
				 WHERE ( si_text RLIKE '$regexp' )
					OR ( si_title RLIKE '$regexp' )";

		return $dbw->query( $sql, __METHOD__ );
	}
}

$maintClass = UpdateDoubleWidthSearch::class;
require_once RUN_MAINTENANCE_IF_MAIN;
