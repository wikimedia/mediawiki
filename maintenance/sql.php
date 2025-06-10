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

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Installer\DatabaseUpdater;
use MediaWiki\Maintenance\Maintenance;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\ServerInfo;

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
		$this->addOption( 'query',
			'Run a single query instead of running interactively', false, true );
		$this->addOption( 'json', 'Output the results as JSON instead of PHP objects' );
		$this->addOption( 'status', 'Return successful exit status only if the query succeeded '
			. '(selected or altered rows), otherwise 1 for errors, 2 for no rows' );
		$this->addOption( 'cluster', 'Use an external cluster by name', false, true );
		$this->addOption( 'wikidb',
			'The database wiki ID to use if not the current one', false, true );
		$this->addOption( 'replicadb',
			'Replica DB server to use instead of the primary DB (can be "any")', false, true );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		global $IP;

		// We want to allow "" for the wikidb, meaning don't call select_db()
		$wiki = $this->hasOption( 'wikidb' ) ? $this->getOption( 'wikidb' ) : false;
		// Get the appropriate load balancer (for this wiki)
		$lbFactory = $this->getServiceContainer()->getDBLoadBalancerFactory();
		if ( $this->hasOption( 'cluster' ) ) {
			$lb = $lbFactory->getExternalLB( $this->getOption( 'cluster' ) );
		} else {
			$lb = $lbFactory->getMainLB( $wiki );
		}
		// Figure out which server to use
		$replicaDB = $this->getOption( 'replicadb', '' );
		if ( $replicaDB === 'any' ) {
			$index = DB_REPLICA;
		} elseif ( $replicaDB !== '' ) {
			$index = null;
			$serverCount = $lb->getServerCount();
			for ( $i = 0; $i < $serverCount; ++$i ) {
				if ( $lb->getServerName( $i ) === $replicaDB ) {
					$index = $i;
					break;
				}
			}
			// @phan-suppress-next-line PhanSuspiciousValueComparison
			if ( $index === null || $index === ServerInfo::WRITER_INDEX ) {
				$this->fatalError( "No replica DB server configured with the name '$replicaDB'." );
			}
		} else {
			$index = DB_PRIMARY;
		}

		$db = $lb->getMaintenanceConnectionRef( $index, [], $wiki );
		if ( $replicaDB != '' && $db->getLBInfo( 'master' ) !== null ) {
			$this->fatalError( "Server {$db->getServerName()} is not a replica DB." );
		}

		if ( $index === DB_PRIMARY ) {
			$updater = DatabaseUpdater::newForDB( $db, true, $this );
			$db->setSchemaVars( $updater->getSchemaVars() );
		}

		if ( $this->hasArg( 0 ) ) {
			$file = fopen( $this->getArg( 0 ), 'r' );
			if ( !$file ) {
				$this->fatalError( "Unable to open input file" );
			}

			$error = $db->sourceStream( $file, null, $this->sqlPrintResult( ... ), __METHOD__ );
			if ( $error !== true ) {
				$this->fatalError( $error );
			}
			return;
		}

		if ( $this->hasOption( 'query' ) ) {
			$query = $this->getOption( 'query' );
			$res = $this->sqlDoQuery( $db, $query, /* dieOnError */ true );
			$this->waitForReplication();
			if ( $this->hasOption( 'status' ) && !$res ) {
				$this->fatalError( 'Failed.', 2 );
			}
			return;
		}

		if (
			function_exists( 'readline_add_history' ) &&
			Maintenance::posix_isatty( 0 /*STDIN*/ )
		) {
			$home = getenv( 'HOME' );
			$historyFile = $home ?
				"$home/.mwsql_history" : "$IP/maintenance/.mwsql_history";
			readline_read_history( $historyFile );
		} else {
			$historyFile = null;
		}

		$wholeLine = '';
		$newPrompt = '> ';
		$prompt = $newPrompt;
		$doDie = !Maintenance::posix_isatty( 0 );
		$res = 1;
		$batchCount = 0;
		// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
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
			if ( $historyFile ) {
				# Delimiter is eaten by streamStatementEnd, we add it
				# up in the history (T39020)
				readline_add_history( $wholeLine . ';' );
				readline_write_history( $historyFile );
			}
			// @phan-suppress-next-line SecurityCheck-SQLInjection
			$res = $this->sqlDoQuery( $db, $wholeLine, $doDie );
			if ( $this->getBatchSize() && ++$batchCount >= $this->getBatchSize() ) {
				$batchCount = 0;
				$this->waitForReplication();
			}
			$prompt = $newPrompt;
			$wholeLine = '';
		}
		$this->waitForReplication();
		if ( $this->hasOption( 'status' ) && !$res ) {
			$this->fatalError( 'Failed.', 2 );
		}
	}

	/**
	 * @param IDatabase $db
	 * @param string $line The SQL text of the query
	 * @param bool $dieOnError
	 * @return int|null Number of rows selected or updated, or null if the query was unsuccessful.
	 */
	protected function sqlDoQuery( IDatabase $db, $line, $dieOnError ) {
		try {
			$res = $db->query( $line, __METHOD__ );
			return $this->sqlPrintResult( $res, $db );
		} catch ( DBQueryError $e ) {
			if ( $dieOnError ) {
				$this->fatalError( (string)$e );
			} else {
				$this->error( (string)$e );
			}
		}
		return null;
	}

	/**
	 * Print the results, callback for $db->sourceStream()
	 * @param IResultWrapper|bool $res
	 * @param IDatabase $db
	 * @return int|null Number of rows selected or updated, or null if the query was unsuccessful.
	 */
	private function sqlPrintResult( $res, $db ) {
		if ( !$res ) {
			// Do nothing
			return null;
		} elseif ( is_object( $res ) ) {
			$out = '';
			$rows = [];
			foreach ( $res as $row ) {
				$out .= print_r( $row, true );
				$rows[] = $row;
			}
			if ( $this->hasOption( 'json' ) ) {
				$out = json_encode( $rows, JSON_PRETTY_PRINT );
			} elseif ( !$rows ) {
				$out = 'Query OK, 0 row(s) affected';
			}
			$this->output( $out . "\n" );
			return count( $rows );
		} else {
			$affected = $db->affectedRows();
			if ( $this->hasOption( 'json' ) ) {
				$this->output( json_encode( [ 'affected' => $affected ], JSON_PRETTY_PRINT ) . "\n" );
			} else {
				$this->output( "Query OK, $affected row(s) affected\n" );
			}
			return $affected;
		}
	}

	/**
	 * @return int DB_TYPE constant
	 */
	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}
}

// @codeCoverageIgnoreStart
$maintClass = MwSql::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
