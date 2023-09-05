<?php
/**
 * Cleanup tables that have valid usernames with no user ID
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

use Wikimedia\Rdbms\IDatabase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that cleans up tables that have valid usernames with no
 * user ID.
 *
 * @ingroup Maintenance
 * @since 1.31
 */
class CleanupUsersWithNoId extends LoggedUpdateMaintenance {
	private $prefix, $table, $assign;
	private $triedCreations = [];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Cleans up tables that have valid usernames with no user ID' );
		$this->addOption( 'prefix', 'Interwiki prefix to apply to the usernames', true, true, 'p' );
		$this->addOption( 'table', 'Only clean up this table', false, true );
		$this->addOption( 'assign', 'Assign edits to existing local users if they exist', false, false );
		$this->setBatchSize( 100 );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function doDBUpdates() {
		$this->prefix = $this->getOption( 'prefix' );
		$this->table = $this->getOption( 'table', null );
		$this->assign = $this->getOption( 'assign' );

		$this->cleanup(
			'revision', 'rev_id', 'rev_user', 'rev_user_text',
			[ 'rev_user' => 0 ], [ 'rev_timestamp', 'rev_id' ]
		);
		$this->cleanup(
			'archive', 'ar_id', 'ar_user', 'ar_user_text',
			[], [ 'ar_id' ]
		);
		$this->cleanup(
			'logging', 'log_id', 'log_user', 'log_user_text',
			[ 'log_user' => 0 ], [ 'log_timestamp', 'log_id' ]
		);
		$this->cleanup(
			'image', 'img_name', 'img_user', 'img_user_text',
			[ 'img_user' => 0 ], [ 'img_timestamp', 'img_name' ]
		);
		$this->cleanup(
			'oldimage', [ 'oi_name', 'oi_timestamp' ], 'oi_user', 'oi_user_text',
			[], [ 'oi_name', 'oi_timestamp' ]
		);
		$this->cleanup(
			'filearchive', 'fa_id', 'fa_user', 'fa_user_text',
			[], [ 'fa_id' ]
		);
		$this->cleanup(
			'ipblocks', 'ipb_id', 'ipb_by', 'ipb_by_text',
			[], [ 'ipb_id' ]
		);
		$this->cleanup(
			'recentchanges', 'rc_id', 'rc_user', 'rc_user_text',
			[], [ 'rc_id' ]
		);

		return true;
	}

	/**
	 * Calculate a "next" condition and progress display string
	 * @param IDatabase $dbw
	 * @param string[] $indexFields Fields in the index being ordered by
	 * @param stdClass $row Database row
	 * @return string[] [ string $next, string $display ]
	 */
	private function makeNextCond( $dbw, $indexFields, $row ) {
		$display = [];
		$conds = [];
		foreach ( $indexFields as $field ) {
			$display[] = $field . '=' . $row->$field;
			$conds[ $field ] = $row->$field;
		}
		$display = implode( ' ', $display );
		$conds = $dbw->buildComparison( '>', $conds );
		return [ $conds, $display ];
	}

	/**
	 * Cleanup a table
	 *
	 * @param string $table Table to migrate
	 * @param string|string[] $primaryKey Primary key of the table.
	 * @param string $idField User ID field name
	 * @param string $nameField User name field name
	 * @param array $conds Query conditions
	 * @param string[] $orderby Fields to order by
	 */
	protected function cleanup(
		$table, $primaryKey, $idField, $nameField, array $conds, array $orderby
	) {
		if ( $this->table !== null && $this->table !== $table ) {
			return;
		}

		$dbw = $this->getDB( DB_PRIMARY );
		if ( !$dbw->fieldExists( $table, $idField, __METHOD__ ) ||
			!$dbw->fieldExists( $table, $nameField, __METHOD__ )
		) {
			$this->output( "Skipping $table, fields $idField and/or $nameField do not exist\n" );
			return;
		}

		$primaryKey = (array)$primaryKey;
		$pkFilter = array_fill_keys( $primaryKey, true );
		$this->output( "Beginning cleanup of $table\n" );

		$next = '1=1';
		$countAssigned = 0;
		$countPrefixed = 0;
		$userNameUtils = $this->getServiceContainer()->getUserNameUtils();
		$userIdentityLookup = $this->getServiceContainer()->getUserIdentityLookup();
		while ( true ) {
			// Fetch the rows needing update
			$res = $dbw->newSelectQueryBuilder()
				->select( array_merge( $primaryKey, [ $idField, $nameField ], $orderby ) )
				->from( $table )
				->where( array_merge( $conds, [ $next ] ) )
				->orderBy( $orderby )
				->limit( $this->mBatchSize )
				->caller( __METHOD__ )
				->fetchResultSet();
			if ( !$res->numRows() ) {
				break;
			}

			// Update the existing rows
			foreach ( $res as $row ) {
				$name = $row->$nameField;
				if ( $row->$idField || !$userNameUtils->isUsable( $name ) ) {
					continue;
				}

				$id = 0;
				if ( $this->assign ) {
					$userIdentity = $userIdentityLookup->getUserIdentityByName( $name );
					if ( !$userIdentity || !$userIdentity->isRegistered() ) {
						// See if any extension wants to create it.
						if ( !isset( $this->triedCreations[$name] ) ) {
							$this->triedCreations[$name] = true;
							if ( !$this->getHookRunner()->onImportHandleUnknownUser( $name ) ) {
								$userIdentity = $userIdentityLookup
									->getUserIdentityByName( $name, IDBAccessObject::READ_LATEST );
								$id = $userIdentity ? $userIdentity->getId() : 0;
							}
						}
					}
				}
				if ( $id ) {
					$set = [ $idField => $id ];
					$counter = &$countAssigned;
				} else {
					$set = [ $nameField => substr( $this->prefix . '>' . $name, 0, 255 ) ];
					$counter = &$countPrefixed;
				}

				$dbw->update(
					$table,
					$set,
					array_intersect_key( (array)$row, $pkFilter ) + [
						$idField => 0,
						$nameField => $name,
					],
					__METHOD__
				);
				$counter += $dbw->affectedRows();
			}

			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable,PhanPossiblyUndeclaredVariable row is set
			[ $next, $display ] = $this->makeNextCond( $dbw, $orderby, $row );
			$this->output( "... $display\n" );
			$this->waitForReplication();
		}

		$this->output(
			"Completed cleanup, assigned $countAssigned and prefixed $countPrefixed row(s)\n"
		);
	}
}

$maintClass = CleanupUsersWithNoId::class;
require_once RUN_MAINTENANCE_IF_MAIN;
