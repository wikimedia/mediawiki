<?php
/**
 * Init the user_editcount database field based on the number of rows in the
 * revision table.
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

use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\RawSQLValue;

class InitEditCount extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'quick', 'Force the update to be done in a single query' );
		$this->addOption( 'background', 'Force replication-friendly mode; may be inefficient but avoids'
		. 'locking tables or lagging replica DBs with large updates; calculates counts on a replica DB'
		. 'if possible. Background mode will be automatically used if multiple servers are listed in the'
		. 'load balancer, usually indicating a replication environment.' );
		$this->addDescription( 'Batch-recalculate user_editcount fields from the revision table' );
	}

	public function execute() {
		$dbw = $this->getPrimaryDB();

		// Autodetect mode...
		if ( $this->hasOption( 'background' ) ) {
			$backgroundMode = true;
		} elseif ( $this->hasOption( 'quick' ) ) {
			$backgroundMode = false;
		} else {
			$lb = $this->getServiceContainer()->getDBLoadBalancer();
			$backgroundMode = $lb->hasReplicaServers();
		}

		if ( $backgroundMode ) {
			$this->output( "Using replication-friendly background mode...\n" );

			$dbr = $this->getReplicaDB();
			$chunkSize = 100;
			$lastUser = $dbr->newSelectQueryBuilder()
				->select( 'MAX(user_id)' )
				->from( 'user' )
				->caller( __METHOD__ )->fetchField();

			$start = microtime( true );
			$migrated = 0;
			for ( $min = 0; $min <= $lastUser; $min += $chunkSize ) {
				$max = $min + $chunkSize;

				$result = $dbr->newSelectQueryBuilder()
					->select( [ 'user_id', 'user_editcount' => "COUNT(actor_rev_user.actor_user)" ] )
					->from( 'user' )
					->join( 'actor', 'actor_rev_user', 'user_id = actor_rev_user.actor_user' )
					->leftJoin( 'revision', 'rev', 'actor_rev_user.actor_id = rev.rev_actor' )
					->where( $dbr->expr( 'user_id', '>', $min )->and( 'user_id', '<=', $max ) )
					->groupBy( 'user_id' )
					->caller( __METHOD__ )->fetchResultSet();

				foreach ( $result as $row ) {
					$dbw->newUpdateQueryBuilder()
						->update( 'user' )
						->set( [ 'user_editcount' => $row->user_editcount ] )
						->where( [ 'user_id' => $row->user_id ] )
						->caller( __METHOD__ )->execute();
					++$migrated;
				}

				$delta = microtime( true ) - $start;
				$rate = ( $delta == 0.0 ) ? 0.0 : $migrated / $delta;
				$this->output( sprintf( "%s %d (%0.1f%%) done in %0.1f secs (%0.3f accounts/sec).\n",
					WikiMap::getCurrentWikiDbDomain()->getId(),
					$migrated,
					min( $max, $lastUser ) / $lastUser * 100.0,
					$delta,
					$rate ) );

				$this->waitForReplication();
			}
		} else {
			$this->output( "Using single-query mode...\n" );

			$subquery = $dbw->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'revision' )
				->join( 'actor', 'actor_rev_user', 'actor_rev_user.actor_id = rev_actor' )
				->where( 'user_id = actor_rev_user.actor_user' )
				->caller( __METHOD__ )->getSQL();

			$dbw->newUpdateQueryBuilder()
				->table( 'user' )
				->set( [ 'user_editcount' => new RawSQLValue( "($subquery)" ) ] )
				->where( IDatabase::ALL_ROWS )
				->caller( __METHOD__ )
				->execute();
		}

		$this->output( "Done!\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = InitEditCount::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
