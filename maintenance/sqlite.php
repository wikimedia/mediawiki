<?php
/**
 * Performs some operations specific to SQLite database backend
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

class SqliteMaintenance extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Performs some operations specific to SQLite database backend";
		$this->addOption( 'vacuum', 'Clean up database by removing deleted pages. Decreases database file size' );
		$this->addOption( 'integrity', 'Check database for integrity' );
		$this->addOption( 'backup-to', 'Backup database to the given file', false, true );
		$this->addOption( 'check-syntax', 'Check SQL file(s) for syntax errors', false, true );
	}

	/**
	 * While we use database connection, this simple lie prevents useless --dbpass and
	 * --dbuser options from appearing in help message for this script.
	 *
	 * @return int DB constant
	 */
	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		// Should work even if we use a non-SQLite database
		if ( $this->hasOption( 'check-syntax' ) ) {
			$this->checkSyntax();
			return;
		}

		$this->db = wfGetDB( DB_MASTER );

		if ( $this->db->getType() != 'sqlite' ) {
			$this->error( "This maintenance script requires a SQLite database.\n" );
			return;
		}

		if ( $this->hasOption( 'vacuum' ) ) {
			$this->vacuum();
		}

		if ( $this->hasOption( 'integrity' ) ) {
			$this->integrityCheck();
		}

		if ( $this->hasOption( 'backup-to' ) ) {
			$this->backup( $this->getOption( 'backup-to' ) );
		}
	}

	private function vacuum() {
		$prevSize = filesize( $this->db->mDatabaseFile );
		if ( $prevSize == 0 ) {
			$this->error( "Can't vacuum an empty database.\n", true );
		}

		$this->output( 'VACUUM: ' );
		if ( $this->db->query( 'VACUUM' ) ) {
			clearstatcache();
			$newSize = filesize( $this->db->mDatabaseFile );
			$this->output( sprintf( "Database size was %d, now %d (%.1f%% reduction).\n",
				$prevSize, $newSize, ( $prevSize - $newSize ) * 100.0 / $prevSize ) );
		} else {
			$this->output( 'Error\n' );
		}
	}

	private function integrityCheck() {
		$this->output( "Performing database integrity checks:\n" );
		$res = $this->db->query( 'PRAGMA integrity_check' );

		if ( !$res || $res->numRows() == 0 ) {
			$this->error( "Error: integrity check query returned nothing.\n" );
			return;
		}

		foreach ( $res as $row ) {
			$this->output( $row->integrity_check );
		}
	}

	private function backup( $fileName ) {
		$this->output( "Backing up database:\n   Locking..." );
		$this->db->query( 'BEGIN IMMEDIATE TRANSACTION', __METHOD__ );
		$ourFile = $this->db->mDatabaseFile;
		$this->output( "   Copying database file $ourFile to $fileName... " );
		wfSuppressWarnings( false );
		if ( !copy( $ourFile, $fileName ) ) {
			$err = error_get_last();
			$this->error( "      {$err['message']}" );
		}
		wfSuppressWarnings( true );
		$this->output( "   Releasing lock...\n" );
		$this->db->query( 'COMMIT TRANSACTION', __METHOD__ );
	}

	private function checkSyntax() {
		if ( !Sqlite::IsPresent() ) {
			$this->error( "Error: SQLite support not found\n" );
		}
		$files = array( $this->getOption( 'check-syntax' ) );
		$files += $this->mArgs;
		$result = Sqlite::checkSqlSyntax( $files );
		if ( $result === true ) {
			$this->output( "SQL syntax check: no errors detected.\n" );
		} else {
			$this->error( "Error: $result\n" );
		}
	}
}

$maintClass = "SqliteMaintenance";
require_once( RUN_MAINTENANCE_IF_MAIN );
