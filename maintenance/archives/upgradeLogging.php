<?php
/**
 * Replication-safe online upgrade for log_id/log_deleted fields.
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
 * @ingroup MaintenanceArchive
 */

require __DIR__ . '/../commandLine.inc';

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\Database;

/**
 * Maintenance script that upgrade for log_id/log_deleted fields in a
 * replication-safe way.
 *
 * @ingroup Maintenance
 */
class UpdateLogging {

	/**
	 * @var Database
	 */
	public $dbw;
	public $batchSize = 1000;
	public $minTs = false;

	public function execute() {
		$this->dbw = wfGetDB( DB_MASTER );
		$logging = $this->dbw->tableName( 'logging' );
		$logging_1_10 = $this->dbw->tableName( 'logging_1_10' );
		$logging_pre_1_10 = $this->dbw->tableName( 'logging_pre_1_10' );

		if ( $this->dbw->tableExists( 'logging_pre_1_10', __METHOD__ )
			&& !$this->dbw->tableExists( 'logging', __METHOD__ )
		) {
			# Fix previous aborted run
			echo "Cleaning up from previous aborted run\n";
			$this->dbw->query( "RENAME TABLE $logging_pre_1_10 TO $logging", __METHOD__ );
		}

		if ( $this->dbw->tableExists( 'logging_pre_1_10', __METHOD__ ) ) {
			echo "This script has already been run to completion\n";

			return;
		}

		# Create the target table
		if ( !$this->dbw->tableExists( 'logging_1_10', __METHOD__ ) ) {
			global $wgDBTableOptions;

			$sql = <<<EOT
CREATE TABLE $logging_1_10 (
  -- Log ID, for referring to this specific log entry, probably for deletion and such.
  log_id int unsigned NOT NULL auto_increment,

  -- Symbolic keys for the general log type and the action type
  -- within the log. The output format will be controlled by the
  -- action field, but only the type controls categorization.
  log_type varbinary(10) NOT NULL default '',
  log_action varbinary(10) NOT NULL default '',

  -- Timestamp. Duh.
  log_timestamp binary(14) NOT NULL default '19700101000000',

  -- The user who performed this action; key to user_id
  log_user int unsigned NOT NULL default 0,

  -- Key to the page affected. Where a user is the target,
  -- this will point to the user page.
  log_namespace int NOT NULL default 0,
  log_title varchar(255) binary NOT NULL default '',

  -- Freeform text. Interpreted as edit history comments.
  log_comment varchar(255) NOT NULL default '',

  -- LF separated list of miscellaneous parameters
  log_params blob NOT NULL,

  -- rev_deleted for logs
  log_deleted tinyint unsigned NOT NULL default '0',

  PRIMARY KEY log_id (log_id),
  KEY type_time (log_type, log_timestamp),
  KEY user_time (log_user, log_timestamp),
  KEY page_time (log_namespace, log_title, log_timestamp),
  KEY times (log_timestamp)

) $wgDBTableOptions
EOT;
			echo "Creating table logging_1_10\n";
			$this->dbw->query( $sql, __METHOD__ );
		}

		# Synchronise the tables
		echo "Doing initial sync...\n";
		$this->sync( 'logging', 'logging_1_10' );
		echo "Sync done\n\n";

		# Rename the old table away
		echo "Renaming the old table to $logging_pre_1_10\n";
		$this->dbw->query( "RENAME TABLE $logging TO $logging_pre_1_10", __METHOD__ );

		# Copy remaining old rows
		# Done before the new table is active so that $copyPos is accurate
		echo "Doing final sync...\n";
		$this->sync( 'logging_pre_1_10', 'logging_1_10' );

		# Move the new table in
		echo "Moving the new table in...\n";
		$this->dbw->query( "RENAME TABLE $logging_1_10 TO $logging", __METHOD__ );
		echo "Finished.\n";
	}

	/**
	 * Copy all rows from $srcTable to $dstTable
	 * @param string $srcTable
	 * @param string $dstTable
	 */
	private function sync( $srcTable, $dstTable ) {
		$batchSize = 1000;
		$minTs = $this->dbw->selectField( $srcTable, 'MIN(log_timestamp)', '', __METHOD__ );
		$minTsUnix = wfTimestamp( TS_UNIX, $minTs );
		$numRowsCopied = 0;
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		while ( true ) {
			$maxTs = $this->dbw->selectField( $srcTable, 'MAX(log_timestamp)', '', __METHOD__ );
			$copyPos = $this->dbw->selectField( $dstTable, 'MAX(log_timestamp)', '', __METHOD__ );
			$maxTsUnix = wfTimestamp( TS_UNIX, $maxTs );
			$copyPosUnix = wfTimestamp( TS_UNIX, $copyPos );

			if ( $copyPos === null ) {
				$percent = 0;
			} else {
				$percent = ( $copyPosUnix - $minTsUnix ) / ( $maxTsUnix - $minTsUnix ) * 100;
			}
			printf( "%s  %.2f%%\n", $copyPos, $percent );

			# Handle all entries with timestamp equal to $copyPos
			if ( $copyPos !== null ) {
				$numRowsCopied += $this->copyExactMatch( $srcTable, $dstTable, $copyPos );
			}

			# Now copy a batch of rows
			if ( $copyPos === null ) {
				$conds = false;
			} else {
				$conds = [ 'log_timestamp > ' . $this->dbw->addQuotes( $copyPos ) ];
			}
			$srcRes = $this->dbw->select( $srcTable, '*', $conds, __METHOD__,
				[ 'LIMIT' => $batchSize, 'ORDER BY' => 'log_timestamp' ] );

			if ( !$srcRes->numRows() ) {
				# All done
				break;
			}

			$batch = [];
			foreach ( $srcRes as $srcRow ) {
				$batch[] = (array)$srcRow;
			}
			$this->dbw->insert( $dstTable, $batch, __METHOD__ );
			$numRowsCopied += count( $batch );

			$lbFactory->waitForReplication();
		}
		echo "Copied $numRowsCopied rows\n";
	}

	private function copyExactMatch( $srcTable, $dstTable, $copyPos ) {
		$numRowsCopied = 0;
		$srcRes = $this->dbw->select( $srcTable, '*', [ 'log_timestamp' => $copyPos ], __METHOD__ );
		$dstRes = $this->dbw->select( $dstTable, '*', [ 'log_timestamp' => $copyPos ], __METHOD__ );

		if ( $srcRes->numRows() ) {
			$srcRow = $srcRes->fetchObject();
			$srcFields = array_keys( (array)$srcRow );
			$srcRes->seek( 0 );
			$dstRowsSeen = [];

			# Make a hashtable of rows that already exist in the destination
			foreach ( $dstRes as $dstRow ) {
				$reducedDstRow = [];
				foreach ( $srcFields as $field ) {
					$reducedDstRow[$field] = $dstRow->$field;
				}
				$hash = md5( serialize( $reducedDstRow ) );
				$dstRowsSeen[$hash] = true;
			}

			# Copy all the source rows that aren't already in the destination
			foreach ( $srcRes as $srcRow ) {
				$hash = md5( serialize( (array)$srcRow ) );
				if ( !isset( $dstRowsSeen[$hash] ) ) {
					$this->dbw->insert( $dstTable, (array)$srcRow, __METHOD__ );
					$numRowsCopied++;
				}
			}
		}

		return $numRowsCopied;
	}
}

$ul = new UpdateLogging;
$ul->execute();
