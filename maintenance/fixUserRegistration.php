<?php
/**
 * Fix the user_registration field.
 * In particular, for values which are NULL, set them to the date of the first edit
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
 * Maintenance script that fixes the user_registration field.
 *
 * @ingroup Maintenance
 */
class FixUserRegistration extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Fix the user_registration field' );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$dbw = $this->getDB( DB_MASTER );

		$lastId = 0;
		do {
			// Get user IDs which need fixing
			$res = $dbw->select(
				'user',
				'user_id',
				[
					'user_id > ' . $dbw->addQuotes( $lastId ),
					'user_registration IS NULL'
				],
				__METHOD__,
				[
					'LIMIT' => $this->mBatchSize,
					'ORDER BY' => 'user_id',
				]
			);
			foreach ( $res as $row ) {
				$id = $row->user_id;
				$lastId = $id;
				// Get first edit time
				$timestamp = $dbw->selectField(
					'revision',
					'MIN(rev_timestamp)',
					[ 'rev_user' => $id ],
					__METHOD__
				);
				// Update
				if ( $timestamp !== null ) {
					$dbw->update(
						'user',
						[ 'user_registration' => $timestamp ],
						[ 'user_id' => $id ],
						__METHOD__
					);
					$user = User::newFromId( $id );
					$user->invalidateCache();
					$this->output( "Set registration for #$id to $timestamp\n" );
				} else {
					$this->output( "Could not find registration for #$id NULL\n" );
				}
			}
			$this->output( "Waiting for slaves..." );
			wfWaitForSlaves();
			$this->output( " done.\n" );
		} while ( $res->numRows() >= $this->mBatchSize );
	}
}

$maintClass = "FixUserRegistration";
require_once RUN_MAINTENANCE_IF_MAIN;
