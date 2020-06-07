<?php
/**
 * Cleans up old database tables, dropping old indexes and fields.
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
 * Maintenance script to cleans up old database tables, dropping old indexes
 * and fields.
 *
 * @ingroup Maintenance
 */
class CleanupAncientTables extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Cleanup ancient tables and indexes' );
		$this->addOption( 'force', 'Actually run this script' );
	}

	public function execute() {
		if ( !$this->hasOption( 'force' ) ) {
			$this->error( "This maintenance script will remove old columns and indexes.\n"
				. "It is recommended to backup your database first, and ensure all your data has\n"
				. "been migrated to newer tables. If you want to continue, run this script again\n"
				. "with --force.\n"
			);
		}

		$db = $this->getDB( DB_MASTER );
		$ancientTables = [
			'blobs', // 1.4
			'brokenlinks', // 1.4
			'cur', // 1.4
			'ip_blocks_old', // Temporary in 1.6
			'links', // 1.4
			'linkscc', // 1.4
			// 'math', // 1.18, but don't want to drop if math extension is enabled...
			'old', // 1.4
			'oldwatchlist', // pre 1.1?
			'trackback', // 1.19
			'user_rights', // 1.5
			'validate', // 1.6
		];

		foreach ( $ancientTables as $table ) {
			if ( $db->tableExists( $table, __METHOD__ ) ) {
				$this->output( "Dropping table $table..." );
				$db->dropTable( $table, __METHOD__ );
				$this->output( "done.\n" );
			}
		}

		$this->output( "Cleaning up text table\n" );

		$oldIndexes = [
			'old_namespace',
			'old_timestamp',
			'name_title_timestamp',
			'user_timestamp',
			'usertext_timestamp',
		];
		foreach ( $oldIndexes as $index ) {
			if ( $db->indexExists( 'text', $index, __METHOD__ ) ) {
				$this->output( "Dropping index $index from the text table..." );
				$db->query( "DROP INDEX " . $db->addIdentifierQuotes( $index )
					. " ON " . $db->tableName( 'text' ), __METHOD__ );
				$this->output( "done.\n" );
			}
		}

		$oldFields = [
			'old_namespace',
			'old_title',
			'old_comment',
			'old_user',
			'old_user_text',
			'old_timestamp',
			'old_minor_edit',
			'inverse_timestamp',
		];
		foreach ( $oldFields as $field ) {
			if ( $db->fieldExists( 'text', $field, __METHOD__ ) ) {
				$this->output( "Dropping the $field field from the text table..." );
				$db->query( "ALTER TABLE  " . $db->tableName( 'text' )
					. " DROP COLUMN " . $db->addIdentifierQuotes( $field ), __METHOD__ );
				$this->output( "done.\n" );
			}
		}
		$this->output( "Done!\n" );
	}
}

$maintClass = CleanupAncientTables::class;
require_once RUN_MAINTENANCE_IF_MAIN;
