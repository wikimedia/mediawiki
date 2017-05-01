<?php
/**
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

/**
 * When using shared tables that are referenced by foreign keys on local
 * tables you have to change the constraints on local tables.
 *
 * The shared tables have to have GRANT REFERENCE on shared tables to local schema
 * i.e.: GRANT REFERENCES (user_id) ON mwuser TO hubclient;
 */

require_once __DIR__ . '/../Maintenance.php';

class AlterSharedConstraints extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Alter foreign key to reference master tables in shared database setup.' );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		global $wgSharedDB, $wgSharedTables, $wgSharedPrefix, $wgDBprefix;

		if ( $wgSharedDB == null ) {
			$this->output( "Database sharing is not enabled\n" );

			return;
		}

		$dbw = $this->getDB( DB_MASTER );
		foreach ( $wgSharedTables as $table ) {
			$stable = $dbw->tableNameInternal( $table );
			if ( $wgSharedPrefix != null ) {
				$ltable = preg_replace( "/^$wgSharedPrefix(.*)/i", "$wgDBprefix\\1", $stable );
			} else {
				$ltable = "{$wgDBprefix}{$stable}";
			}

			$result = $dbw->query( "SELECT uc.constraint_name, uc.table_name, ucc.column_name,
						uccpk.table_name pk_table_name, uccpk.column_name pk_column_name,
						uc.delete_rule, uc.deferrable, uc.deferred
					FROM user_constraints uc, user_cons_columns ucc, user_cons_columns uccpk
					WHERE uc.constraint_type = 'R'
						AND ucc.constraint_name = uc.constraint_name
						AND uccpk.constraint_name = uc.r_constraint_name
						AND uccpk.table_name = '$ltable'" );
			while ( ( $row = $result->fetchRow() ) !== false ) {

				$this->output( "Altering {$row['constraint_name']} ..." );

				try {
					$dbw->query( "ALTER TABLE {$row['table_name']}
							DROP CONSTRAINT {$wgDBprefix}{$row['constraint_name']}" );
				} catch ( DBQueryError $exdb ) {
					if ( $exdb->errno != 2443 ) {
						throw $exdb;
					}
				}

				$deleteRule = $row['delete_rule'] == 'NO ACTION' ? '' : "ON DELETE {$row['delete_rule']}";
				$dbw->query( "ALTER TABLE {$row['table_name']}
						ADD CONSTRAINT {$wgDBprefix}{$row['constraint_name']}
						FOREIGN KEY ({$row['column_name']})
						REFERENCES {$wgSharedDB}.$stable({$row['pk_column_name']})
						{$deleteRule} {$row['deferrable']} INITIALLY {$row['deferred']}" );

				$this->output( "DONE\n" );
			}
		}
	}
}

$maintClass = "AlterSharedConstraints";
require_once RUN_MAINTENANCE_IF_MAIN;
