<?php
/**
 * Run a database query in batches and wait for replica DBs. This is used on large
 * wikis to prevent replication lag from going through the roof when executing
 * large write queries.
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

use MediaWiki\Maintenance\Maintenance;
use Wikimedia\Rdbms\Platform\ISQLPlatform;

/**
 * Maintenance script to run a database query in batches and wait for replica DBs.
 *
 * @ingroup Maintenance
 */
class RunBatchedQuery extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			"Run an update query on all rows of a table. " .
			"Waits for replicas at appropriate intervals." );
		$this->addOption( 'table', 'The table name', true, true );
		$this->addOption( 'set', 'The SET clause', true, true );
		$this->addOption( 'where', 'The WHERE clause', false, true );
		$this->addOption( 'key', 'A column name, the values of which are unique', true, true );
		$this->addOption( 'batch-size', 'The batch size (default 1000)', false, true );
		$this->addOption( 'db', 'The database name, or omit to use the current wiki.', false, true );
	}

	public function execute() {
		$table = $this->getOption( 'table' );
		$key = $this->getOption( 'key' );
		$set = $this->getOption( 'set' );
		$where = $this->getOption( 'where', null );
		$where = $where === null ? [] : [ $where ];
		$batchSize = $this->getOption( 'batch-size', 1000 );

		$dbName = $this->getOption( 'db', null );
		if ( $dbName === null ) {
			$dbw = $this->getPrimaryDB();
		} else {
			$dbw = $this->getServiceContainer()->getConnectionProvider()->getPrimaryDatabase( $dbName );
		}

		$selectConds = $where;
		$prevEnd = false;

		$n = 1;
		do {
			$this->output( "Batch $n: " );
			$n++;

			// Note that the update conditions do not rely on the atomicity of the
			// SELECT query in order to guarantee that all rows are updated. The
			// results of the SELECT are merely a partitioning hint. Simultaneous
			// updates merely result in the wrong number of rows being updated
			// in a batch.

			$res = $dbw->newSelectQueryBuilder()
				->select( $key )
				->from( $table )
				->where( $selectConds )
				->orderBy( $key )
				->limit( $batchSize )
				->caller( __METHOD__ )
				->fetchResultSet();

			// Calculate the WHERE strings needed for this UPDATE and the next SELECT.
			// Example $updateConds: `WHERE id > 999 AND id <= 1999`
			if ( $res->numRows() ) {
				$res->seek( $res->numRows() - 1 );
				$row = $res->fetchObject();
				$end = $row->$key;
				$selectConds = array_merge( $where, [ $dbw->expr( $key, '>', $end ) ] );
				$updateConds = array_merge( $where, [ $dbw->expr( $key, '<=', $end ) ] );
			} else {
				$updateConds = $where;
				$end = false;
			}
			if ( $prevEnd !== false ) {
				$updateConds = array_merge( [ $dbw->expr( $key, '>', $prevEnd ) ], $updateConds );
			}

			$query = "UPDATE " . $dbw->tableName( $table ) .
				" SET " . $set .
				" WHERE " . $dbw->makeList( $updateConds, ISQLPlatform::LIST_AND );

			$dbw->query( $query, __METHOD__ );

			$prevEnd = $end;

			$affected = $dbw->affectedRows();
			$this->output( "$affected rows affected\n" );
			$this->waitForReplication();
		} while ( $res->numRows() );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}
}

// @codeCoverageIgnoreStart
$maintClass = RunBatchedQuery::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
