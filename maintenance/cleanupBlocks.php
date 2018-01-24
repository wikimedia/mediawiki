<?php
/**
 * Cleans up user blocks with user names not matching the 'user' table
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
 * Maintenance script to clean up user blocks with user names not matching the
 * 'user' table.
 *
 * @ingroup Maintenance
 */
class CleanupBlocks extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( "Cleanup user blocks with user names not matching the 'user' table" );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$db = $this->getDB( DB_MASTER );
		$blockQuery = Block::getQueryInfo();

		$max = $db->selectField( 'ipblocks', 'MAX(ipb_user)' );

		// Step 1: Clean up any duplicate user blocks
		$batchSize = $this->getBatchSize();
		for ( $from = 1; $from <= $max; $from += $batchSize ) {
			$to = min( $max, $from + $batchSize - 1 );
			$this->output( "Cleaning up duplicate ipb_user ($from-$to of $max)\n" );

			$delete = [];

			$res = $db->select(
				'ipblocks',
				[ 'ipb_user' ],
				[
					"ipb_user >= " . (int)$from,
					"ipb_user <= " . (int)$to,
				],
				__METHOD__,
				[
					'GROUP BY' => 'ipb_user',
					'HAVING' => 'COUNT(*) > 1',
				]
			);
			foreach ( $res as $row ) {
				$bestBlock = null;
				$res2 = $db->select(
					$blockQuery['tables'],
					$blockQuery['fields'],
					[
						'ipb_user' => $row->ipb_user,
					],
					__METHOD__,
					[],
					$blockQuery['joins']
				);
				foreach ( $res2 as $row2 ) {
					$block = Block::newFromRow( $row2 );
					if ( !$bestBlock ) {
						$bestBlock = $block;
						continue;
					}

					// Find the most-restrictive block. Can't use
					// Block::chooseBlock because that's for IP blocks, not
					// user blocks.
					$keep = null;
					if ( $keep === null && $block->getExpiry() !== $bestBlock->getExpiry() ) {
						// This works for infinite blocks because 'infinity' > '20141024234513'
						$keep = $block->getExpiry() > $bestBlock->getExpiry();
					}
					if ( $keep === null ) {
						foreach ( [ 'createaccount', 'sendemail', 'editownusertalk' ] as $action ) {
							if ( $block->prevents( $action ) xor $bestBlock->prevents( $action ) ) {
								$keep = $block->prevents( $action );
								break;
							}
						}
					}

					if ( $keep ) {
						$delete[] = $bestBlock->getId();
						$bestBlock = $block;
					} else {
						$delete[] = $block->getId();
					}
				}
			}

			if ( $delete ) {
				$db->delete(
					'ipblocks',
					[ 'ipb_id' => $delete ],
					__METHOD__
				);
			}
		}

		// Step 2: Update the user name in any blocks where it doesn't match
		for ( $from = 1; $from <= $max; $from += $batchSize ) {
			$to = min( $max, $from + $batchSize - 1 );
			$this->output( "Cleaning up mismatched user name ($from-$to of $max)\n" );

			$res = $db->select(
				[ 'ipblocks', 'user' ],
				[ 'ipb_id', 'user_name' ],
				[
					'ipb_user = user_id',
					"ipb_user >= " . (int)$from,
					"ipb_user <= " . (int)$to,
					'ipb_address != user_name',
				],
				__METHOD__
			);
			foreach ( $res as $row ) {
				$db->update(
					'ipblocks',
					[ 'ipb_address' => $row->user_name ],
					[ 'ipb_id' => $row->ipb_id ],
					__METHOD__
				);
			}
		}

		$this->output( "Done!\n" );
	}
}

$maintClass = CleanupBlocks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
