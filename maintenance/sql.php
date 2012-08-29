<?php
/**
 * Send SQL queries from the specified file to the database, performing
 * variable replacement along the way.
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

class MwSql extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Send SQL queries to a MediaWiki database";
	}

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );
		if ( $this->hasArg() ) {
			$fileName = $this->getArg();
			$file = fopen( $fileName, 'r' );
			if ( !$file ) {
				$this->error( "Unable to open input file", true );
			}

			$error = $dbw->sourceStream( $file, false, array( $this, 'sqlPrintResult' ) );
			if ( $error !== true ) {
				$this->error( $error, true );
			} else {
				exit( 0 );
			}
		}

		$useReadline = function_exists( 'readline_add_history' )
				&& Maintenance::posix_isatty( 0 /*STDIN*/ );

		if ( $useReadline ) {
			global $IP;
			$historyFile = isset( $_ENV['HOME'] ) ?
					"{$_ENV['HOME']}/.mwsql_history" : "$IP/maintenance/.mwsql_history";
			readline_read_history( $historyFile );
		}

		$wholeLine = '';
		while ( ( $line = Maintenance::readconsole() ) !== false ) {
			$done = $dbw->streamStatementEnd( $wholeLine, $line );

			$wholeLine .= $line;

			if ( !$done ) {
				continue;
			}
			if ( $useReadline ) {
				readline_add_history( $wholeLine );
				readline_write_history( $historyFile );
			}
			try{
				$res = $dbw->query( $wholeLine );
				$this->sqlPrintResult( $res, $dbw );
				$wholeLine = '';
			} catch (DBQueryError $e) {
				$this->error( $e, true );
			}
		}
	}

	/**
	 * Print the results, callback for $db->sourceStream()
	 * @param $res ResultWrapper The results object
	 * @param $db DatabaseBase object
	 */
	public function sqlPrintResult( $res, $db ) {
		if ( !$res ) {
			// Do nothing
		} elseif ( is_object( $res ) && $res->numRows() ) {
			foreach ( $res as $row ) {
				$this->output( print_r( $row, true ) );
			}
		} else {
			$affected = $db->affectedRows();
			$this->output( "Query OK, $affected row(s) affected\n" );
		}
	}

	/**
	 * @return int DB_TYPE constant
	 */
	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}
}

$maintClass = "MwSql";
require_once( RUN_MAINTENANCE_IF_MAIN );
