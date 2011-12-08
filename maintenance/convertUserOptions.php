<?php
/**
 * Do each user sequentially, since accounts can't be deleted
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

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class ConvertUserOptions extends Maintenance {

	private $mConversionCount = 0;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Convert user options from old to new system";
	}

	public function execute() {
		$this->output( "...batch conversion of user_options: " );
		$id = 0;
		$dbw = wfGetDB( DB_MASTER );

		if ( !$dbw->fieldExists( 'user', 'user_options', __METHOD__ ) ) {
			$this->output( "nothing to migrate. " );
			return;
		}
		while ( $id !== null ) {
			$idCond = 'user_id > ' . $dbw->addQuotes( $id );
			$optCond = "user_options != " . $dbw->addQuotes( '' ); // For compatibility
			$res = $dbw->select( 'user', '*',
				array( $optCond, $idCond ), __METHOD__,
				array( 'LIMIT' => 50, 'FOR UPDATE' )
			);
			$id = $this->convertOptionBatch( $res, $dbw );
			$dbw->commit();

			wfWaitForSlaves();

			if ( $id ) {
				$this->output( "--Converted to ID $id\n" );
			}
		}
		$this->output( "done. Converted " . $this->mConversionCount . " user records.\n" );
	}

	/**
	 * @param $res
	 * @param $dbw DatabaseBase
	 * @return null|int
	 */
	function convertOptionBatch( $res, $dbw ) {
		$id = null;
		foreach ( $res as $row ) {
			$this->mConversionCount++;

			$u = User::newFromRow( $row );

			$u->saveSettings();

			// Do this here as saveSettings() doesn't set user_options to '' anymore!
			$dbw->update(
				'user',
				array( 'user_options' => '' ),
				array( 'user_id' => $row->user_id ),
				__METHOD__
			);
			$id = $row->user_id;
		}

		return $id;
	}
}

$maintClass = "ConvertUserOptions";
require_once( RUN_MAINTENANCE_IF_MAIN );
