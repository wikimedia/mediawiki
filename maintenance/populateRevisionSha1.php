<?php
/**
 * Fills the rev_sha1 and ar_sha1 columns of revision & archive tables.
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

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class PopulateRevisionSha1 extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Populates the rev_sha1 and ar_sha1 fields";
		$this->setBatchSize( 150 );
	}

	public function execute() {
		$db = $this->getDB( DB_MASTER );

		$this->output( "Populating rev_sha1 column\n" );
		$this->doSha1Updates( $db, 'revision', 'rev_id', 'rev' );

		$this->output( "Populating ar_sha1 column\n" );
		$this->doSha1Updates( $db, 'archive', 'ar_rev_id', 'ar' );

		if ( $db->insert(
				'updatelog',
				array( 'ul_key' => 'populate rev_sha1' ),
				__METHOD__,
				'IGNORE'
			)
		) {
			$this->output( "rev_sha1 and ar_sha1 population complete.\n" );
			return true;
		} else {
			$this->output( "Could not insert rev_sha1 population row.\n" );
			return false;
		}
	}

	protected function doSha1Updates( $db, $table, $idCol, $prefix ) {
		$start = $db->selectField( $table, "MIN($idCol)", "$idCol IS NOT NULL", __METHOD__ );
		if ( !$start ) {
			$this->output( "Nothing to do.\n" );
			return true;
		}
		$end = $db->selectField( $table, "MAX($idCol)", "$idCol IS NOT NULL", __METHOD__ );

		# Do remaining chunk
		$end += $this->mBatchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;
		while ( $blockEnd <= $end ) {
			$this->output( "...doing $idCol from $blockStart to $blockEnd\n" );
			$cond = "$idCol BETWEEN $blockStart AND $blockEnd
				AND $idCol IS NOT NULL AND {$prefix}_sha1 != ''";
			$res = $db->select( $table, '*', $cond, __METHOD__ );

			$db->begin();
			foreach ( $res as $row ) {
				if ( $table === 'archive' ) {
					$rev = Revision::newFromArchiveRow( $row );
				} else {
					$rev = new Revision( $row );
				}
				$db->update( $table,
					array( "{$prefix}_sha1" => Revision::base36Sha1( $rev->getRawText() ) ),
					array( $idCol => $row->$idCol ),
					__METHOD__ );
			}
			$db->commit();

			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
			wfWaitForSlaves();
		}
	}
}

$maintClass = "PopulateRevisionSha1";
require_once( RUN_MAINTENANCE_IF_MAIN );
