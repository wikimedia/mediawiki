<?php
/**
 * Migrate central IDs from one provider to another.
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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Migrate central IDs from one provider to another.
 *
 * E.g. to update OAuth data after enabling CentralAuth in a multiwiki setup, you would use
 *   mwscript migrateCentralIds.php --wiki <oauth central wiki> --table oauth_registered_consumer \
 *       --column oarc_user_id --old local --new CentralAuth
 *   mwscript migrateCentralIds.php --wiki <oauth central wiki> --table oauth_accepted_consumer \
 *       --column oaac_user_id --old local --new CentralAuth
 *
 * The script assumes that sorting by the user ID has reasonable performance. Batch size is applied
 * to the central ID (ie. the script assumes a single user does not own a huge number of rows).
 * No effort is made to prevent race conditions; you should ensure that IDs are not changed
 * while the script runs.
 *
 * @since 1.28
 * @ingroup Maintenance
 */
class MigrateCentralIds extends \Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Migrate central user IDs in a given table/column from one provider to another.'
		);
		$this->addOption( 'table', "which table to update", true, true );
		$this->addOption( 'column', "which column to update", true, true );
		$this->addOption( 'old', '$wgCentralIdLookupProviders key for old provider', true, true );
		$this->addOption( 'new', '$wgCentralIdLookupProviders key for new provider', true, true );
		$this->addOption( 'from', 'ID to continue from (exclusive)', false, true );
		$this->setBatchSize( 500 );
	}

	public function execute() {
		$db = $this->getServices()->getDBLoadBalancer()->getConnection( DB_MASTER );
		$table = $this->getOption( 'table' );
		$column = $this->getOption( 'column' );
		$oldLookup = \CentralIdLookup::factory( $this->getOption( 'old' ) );
		if ( !$oldLookup ) {
			$this->error( 'invalid name for old provider', 1 );
		}
		$newLookup = \CentralIdLookup::factory( $this->getOption( 'new' ) );
		if ( !$newLookup ) {
			$this->error( 'invalid name for new provider', 1 );
		}
		$from = (int)$this->getOption( 'from', -1 );

		while ( true ) {
			$oldIds = $db->selectFieldValues( $table, $column,
				$column . ' > ' . $from,
				__METHOD__,
				[
					'LIMIT' => $this->mBatchSize,
					'ORDER BY' => $column . ' ASC',
					'DISTINCT',
				]
			);

			if ( !$oldIds ) {
				break;
			}
			$from = end( $oldIds );
			$this->output( "Processing users from " . reset( $oldIds ) . " to $from\n" );

			$oldIdToName = $oldLookup->lookupCentralIds( array_fill_keys( $oldIds, null ),
				\CentralIdLookup::AUDIENCE_RAW );
			$nameToNewID = $newLookup->lookupUserNames(
				array_fill_keys( array_values( array_filter( $oldIdToName ) ), null ),
				\CentralIdLookup::AUDIENCE_RAW );

			foreach ( $oldIdToName as $oldId => $name ) {
				if ( !isset( $nameToNewID[$name] ) ) {
					$this->output( "Could not migrate user $oldId\n" );
					continue;
				}
				$newId = $nameToNewID[$name];
				$db->update( $table, [ $column => $newId ], [ $column => $oldId ] );
				$this->output( "Updated user $oldId to $newId ({$db->affectedRows()} rows)\n" );
			}

			wfWaitForSlaves();
		}
	}
}

$maintClass = MigrateCentralIds::class;
require_once RUN_MAINTENANCE_IF_MAIN;
