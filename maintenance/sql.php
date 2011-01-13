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
		if ( $this->hasArg() ) {
			$fileName = $this->getArg();
			$file = fopen( $fileName, 'r' );
			$promptCallback = false;
		} else {
			$file = $this->getStdin();
			$promptObject = new SqlPromptPrinter( "> " );
			$promptCallback = $promptObject->cb();
		}

		if ( !$file )
			$this->error( "Unable to open input file", true );

		$dbw = wfGetDB( DB_MASTER );
		$error = $dbw->sourceStream( $file, $promptCallback, array( $this, 'sqlPrintResult' ) );
		if ( $error !== true ) {
			$this->error( $error, true );
		} else {
			exit( 0 );
		}
	}

	/**
	 * Print the results, callback for $db->sourceStream()
	 * @param $res The results object
	 * @param $db Database object
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

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}
}

class SqlPromptPrinter {
	function __construct( $prompt ) {
		$this->prompt = $prompt;
	}

	function cb() {
		return array( $this, 'printPrompt' );
	}

	function printPrompt() {
		echo $this->prompt;
	}
}

$maintClass = "MwSql";
require_once( RUN_MAINTENANCE_IF_MAIN );
