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
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that sends SQL queries from the specified file to the database.
 *
 * @ingroup Maintenance
 */
class MwSql extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Send SQL queries to a MediaWiki database";
		$this->addOption( 'cluster', 'Use an external cluster by name', false, true );
		$this->addOption( 'slave', 'Use a slave server (either "any" or by name)', false, true );
	}

	public function execute() {
		// Get the appropriate load balancer (for this wiki)
		if ( $this->hasOption( 'cluster' ) ) {
			$lb = wfGetLBFactory()->getExternalLB( $this->getOption( 'cluster' ) );
		} else {
			$lb = wfGetLB();
		}
		// Figure out which server to use
		if ( $this->hasOption( 'slave' ) ) {
			$server = $this->getOption( 'slave' );
			if ( $server === 'any' ) {
				$index = DB_SLAVE;
			} else {
				$index = null;
				for ( $i = 0; $i < $lb->getServerCount(); ++$i ) {
					if ( $lb->getServerName( $i ) === $server ) {
						$index = $i;
						break;
					}
				}
				if ( $index === null ) {
					$this->error( "No slave server configured with the name '$server'.", 1 );
				}
			}
		} else {
			$index = DB_MASTER;
		}
		// Get a DB handle (with this wiki's DB selected) from the appropriate load balancer
		$dbw = $lb->getConnection( $index );
		if ( $this->hasOption( 'slave' ) && $dbw->getLBInfo( 'master' ) !== null ) {
			$this->error( "The server selected ({$dbw->getServer()}) is not a slave.", 1 );
		}

		if ( $this->hasArg( 0 ) ) {
			$file = fopen( $this->getArg( 0 ), 'r' );
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
		$newPrompt = '> ';
		$prompt = $newPrompt;
		while ( ( $line = Maintenance::readconsole( $prompt ) ) !== false ) {
			if ( !$line ) {
				# User simply pressed return key
				continue;
			}
			$done = $dbw->streamStatementEnd( $wholeLine, $line );

			$wholeLine .= $line;

			if ( !$done ) {
				$wholeLine .= ' ';
				$prompt = '    -> ';
				continue;
			}
			if ( $useReadline ) {
				# Delimiter is eated by streamStatementEnd, we add it
				# up in the history (bug 37020)
				readline_add_history( $wholeLine . $dbw->getDelimiter() );
				readline_write_history( $historyFile );
			}
			try {
				$res = $dbw->query( $wholeLine );
				$this->sqlPrintResult( $res, $dbw );
				$prompt = $newPrompt;
				$wholeLine = '';
			} catch ( DBQueryError $e ) {
				$doDie = ! Maintenance::posix_isatty( 0 );
				$this->error( $e, $doDie );
			}
		}
		wfWaitForSlaves();
	}

	/**
	 * Print the results, callback for $db->sourceStream()
	 * @param $res ResultWrapper The results object
	 * @param $db DatabaseBase object
	 */
	public function sqlPrintResult( $res, $db ) {
		if ( !$res ) {
			// Do nothing
			return;
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
require_once RUN_MAINTENANCE_IF_MAIN;
