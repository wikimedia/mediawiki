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
		$this->addDescription( 'Send SQL queries to a MediaWiki database. ' .
			'Takes a file name containing SQL as argument or runs interactively.' );
		$this->addOption( 'query', 'Run a single query instead of running interactively', false, true );
		$this->addOption( 'cluster', 'Use an external cluster by name', false, true );
		$this->addOption( 'wikidb', 'The database wiki ID to use if not the current one', false, true );
		$this->addOption( 'slave', 'Use a slave server (either "any" or by name)', false, true );
	}

	public function execute() {
		// We wan't to allow "" for the wikidb, meaning don't call select_db()
		$wiki = $this->hasOption( 'wikidb' ) ? $this->getOption( 'wikidb' ) : false;
		// Get the appropriate load balancer (for this wiki)
		if ( $this->hasOption( 'cluster' ) ) {
			$lb = wfGetLBFactory()->getExternalLB( $this->getOption( 'cluster' ), $wiki );
		} else {
			$lb = wfGetLB( $wiki );
		}
		// Figure out which server to use
		if ( $this->hasOption( 'slave' ) ) {
			$server = $this->getOption( 'slave' );
			if ( $server === 'any' ) {
				$index = DB_SLAVE;
			} else {
				$index = null;
				$serverCount = $lb->getServerCount();
				for ( $i = 0; $i < $serverCount; ++$i ) {
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
		$db = $lb->getConnection( $index, [], $wiki );
		if ( $this->hasOption( 'slave' ) && $db->getLBInfo( 'master' ) !== null ) {
			$this->error( "The server selected ({$db->getServer()}) is not a slave.", 1 );
		}

		if ( $this->hasArg( 0 ) ) {
			$file = fopen( $this->getArg( 0 ), 'r' );
			if ( !$file ) {
				$this->error( "Unable to open input file", true );
			}

			$error = $db->sourceStream( $file, false, [ $this, 'sqlPrintResult' ] );
			if ( $error !== true ) {
				$this->error( $error, true );
			} else {
				exit( 0 );
			}
		}

		if ( $this->hasOption( 'query' ) ) {
			$query = $this->getOption( 'query' );
			$this->sqlDoQuery( $db, $query, /* dieOnError */ true );
			wfWaitForSlaves();
			return;
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
		$doDie = !Maintenance::posix_isatty( 0 );
		while ( ( $line = Maintenance::readconsole( $prompt ) ) !== false ) {
			if ( !$line ) {
				# User simply pressed return key
				continue;
			}
			$done = $db->streamStatementEnd( $wholeLine, $line );

			$wholeLine .= $line;

			if ( !$done ) {
				$wholeLine .= ' ';
				$prompt = '    -> ';
				continue;
			}
			if ( $useReadline ) {
				# Delimiter is eated by streamStatementEnd, we add it
				# up in the history (bug 37020)
				readline_add_history( $wholeLine . $db->getDelimiter() );
				readline_write_history( $historyFile );
			}
			$this->sqlDoQuery( $db, $wholeLine, $doDie );
			$prompt = $newPrompt;
			$wholeLine = '';
		}
		wfWaitForSlaves();
	}

	protected function sqlDoQuery( $db, $line, $dieOnError ) {
		try {
			$res = $db->query( $line );
			$this->sqlPrintResult( $res, $db );
		} catch ( DBQueryError $e ) {
			$this->error( $e, $dieOnError );
		}
	}

	/**
	 * Print the results, callback for $db->sourceStream()
	 * @param ResultWrapper $res The results object
	 * @param DatabaseBase $db
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
