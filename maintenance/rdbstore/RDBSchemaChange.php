<?php
/**
 * Tool to help with CREATE/ALTER statements on a virtual table that
 * is split up into many shards in an ExternalRDBStore backend.
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
 * @author Aaron Schulz
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

/**
 * Maintenance script to help with ExternalRDBStore schema changes
 *
 * @ingroup Maintenance
 */
class RDBSchemaChange extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( "mode", "Either 'generate' or 'run'", true, true );
		$this->addOption( "store", "The external RDB store name", true, true );
		$this->addOption( "patchfile", "The .sql file to run", false, true );
		$this->addOption( "table", "Virtual table name", false, true );
		$this->addOption( "column", "Virtual table shard column", false, true );
		$this->addOption( "dir", "Directory to generate or run .sql files", true, true );
		$this->mDescription = "Prepare or run a schema change for a table on a wiki";
	}

	function getDbType() {
		/* If we used the class constant PHP4 would give a parser error here */
		return 2 /* Maintenance::DB_ADMIN */;
	}

	public function execute() {
		$dir = $this->getOption( 'dir' );
		if ( !is_dir( $dir ) && !mkdir( $dir ) ) {
			$this->error( "Invalid SQL file directory '$dir'.", 1 );
		}
		if ( $this->getOption( 'mode' ) === 'run' ) {
			$this->runGeneratedSQL();
		} else {
			$this->generateSQL();
		}
	}

	protected function generateSQL() {
		$table = $this->getOption( 'table' );
		$column = $this->getOption( 'column' );
		if ( !strlen( $table ) | !strlen( $column ) ) {
			$this->error( "Missing SQL table name or shard column parameter.", 1 );
		}
		$dir = $this->getOption( 'dir' );
		// Read in the SQL patch file...
		$patchFile = $this->getOption( 'patchfile' );
		if ( FileBackend::extensionFromPath( $patchFile ) !== 'sql' || !is_file( $patchFile ) ) {
			$this->error( "Invalid SQL patch file '$patchFile' given.", 1 );
		}
		$patchName = basename( $patchFile );
		$sql = file_get_contents( $patchFile );
		if ( $sql === false ) {
			$this->error( "Could not read SQL patch file '$patchFile'.", 1 );
		}
		// Get the SQL patch file to run on each cluster...
		$rdbStore = RDBStoreGroup::singleton()->getExternal( $this->getOption( 'store' ) );
		foreach ( $rdbStore->getClusterMapping() as $cluster => $shards ) {
			@mkdir( "{$dir}/{$cluster}" );
			// Go through each shard that this cluster serves...
			foreach ( $shards as $index ) {
				$i = $rdbStore->formatShardIndex( $index ); // e.g. "0200"
				// Transform the patch to account for the table sharding conventions.
				// E.g., replace "/*_*/flaggedimages" with "/*_*/flaggedimages__0200__fi_rev_id".
				$shardSQL = str_replace( "/*_*/{$table}", "/*_*/{$table}__{$i}__{$column}", $sql );
				if ( $shardSQL === $sql ) {
					var_dump( $shardSQL );
					$this->error( "Could not transform SQL patch file '$patchFile'.", 1 );
				}
				// Save SQL patch to make the schema change for each table shard
				if ( !file_put_contents( "{$dir}/{$cluster}/{$i}-{$patchName}", $shardSQL ) ) {
					$this->error( "Could not write SQL patch file '{$i}-{$patchName}'.", 1 );
				}
			}
		}
	}

	protected function runGeneratedSQL() {
		$wiki = $this->getOption( 'wiki' );
		if ( !strlen( $wiki ) ) {
			$this->error( "No wiki ID provided.", 1 );
		}
		$dir = $this->getOption( 'dir' );
		// Get the SQL patch file to run on each cluster...
		$rdbStore = RDBStoreGroup::singleton()->getExternal( $this->getOption( 'store' ) );
		foreach ( $rdbStore->getClusterMapping() as $cluster => $shards ) {
			// Go through all generated SQL files for this cluster...
			if ( is_dir( "{$dir}/{$cluster}" ) ) {
				$handle = opendir( "{$dir}/{$cluster}" );
				while ( $file = readdir( $handle ) ) {
					if ( FileBackend::extensionFromPath( $file ) === 'sql' ) {
						$lb = wfGetLBFactory()->getExternalLB( $cluster );
						$dbw = $lb->getConnection( DB_MASTER, array(), $wiki );
						$dbw->sourceFile( "{$dir}/{$cluster}/{$file}" );
					}
				}
				closedir( $handle );
			}
		}
	}
}

$maintClass = "RDBSchemaChange";
require_once( RUN_MAINTENANCE_IF_MAIN );
