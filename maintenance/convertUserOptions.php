<?php
/**
 * Convert user options to the new `user_properties` table.
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
 * Maintenance script to convert user options to the new `user_properties` table.
 *
 * @ingroup Maintenance
 */
class ConvertUserOptions extends Maintenance {

	private $mConversionCount = 0;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Convert user options from old to new system";
		$this->setBatchSize( 50 );
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
			$res = $dbw->select( 'user',
				array( 'user_id', 'user_options' ),
				array(
					'user_id > ' . $dbw->addQuotes( $id ),
					"user_options != " . $dbw->addQuotes( '' ),
				),
				__METHOD__,
				array(
					'ORDER BY' => 'user_id',
					'LIMIT' => $this->mBatchSize,
				)
			);
			$id = $this->convertOptionBatch( $res, $dbw );

			wfWaitForSlaves();

			if ( $id ) {
				$this->output( "--Converted to ID $id\n" );
			}
		}
		$this->output( "done. Converted " . $this->mConversionCount . " user records.\n" );
	}

	/**
	 * @param ResultWrapper $res
	 * @param DatabaseBase $dbw
	 * @return null|int
	 */
	function convertOptionBatch( $res, $dbw ) {
		$id = null;
		foreach ( $res as $row ) {
			$this->mConversionCount++;
			$insertRows = array();
			foreach ( explode( "\n", $row->user_options ) as $s ) {
				$m = array();
				if ( !preg_match( "/^(.[^=]*)=(.*)$/", $s, $m ) ) {
					continue;
				}

				// MW < 1.16 would save even default values. Filter them out
				// here (as in User) to avoid adding many unnecessary rows.
				$defaultOption = User::getDefaultOption( $m[1] );
				if ( is_null( $defaultOption ) || $m[2] != $defaultOption ) {
					$insertRows[] = array(
						'up_user' => $row->user_id,
						'up_property' => $m[1],
						'up_value' => $m[2],
					);
				}
			}

			if ( count( $insertRows ) ) {
				$dbw->insert( 'user_properties', $insertRows, __METHOD__, array( 'IGNORE' ) );
			}

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
require_once RUN_MAINTENANCE_IF_MAIN;
