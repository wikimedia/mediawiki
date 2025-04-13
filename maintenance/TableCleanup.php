<?php
/**
 * Generic class to cleanup a database table.
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

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\WikiMap\WikiMap;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Generic class to cleanup a database table. Already subclasses Maintenance.
 *
 * @ingroup Maintenance
 */
class TableCleanup extends Maintenance {
	/** @var array */
	protected $defaultParams = [
		'table' => 'page',
		'conds' => [],
		'index' => 'page_id',
		'callback' => 'processRow',
	];

	/** @var bool */
	protected $dryrun = false;
	/** @var int */
	protected $reportInterval = 100;

	protected int $processed;
	protected int $updated;
	protected int $count;
	protected float $startTime;
	protected string $table;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'dry-run', 'Perform a dry run' );
		$this->addOption( 'reporting-interval', 'How often to print status line' );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$this->reportInterval = $this->getOption( 'reporting-interval', $this->reportInterval );

		$this->dryrun = $this->hasOption( 'dry-run' );

		if ( $this->dryrun ) {
			$this->output( "Checking for bad titles...\n" );
		} else {
			$this->output( "Checking and fixing bad titles...\n" );
		}

		$this->runTable( $this->defaultParams );
	}

	protected function init( int $count, string $table ) {
		$this->processed = 0;
		$this->updated = 0;
		$this->count = $count;
		$this->startTime = microtime( true );
		$this->table = $table;
	}

	/**
	 * @param int $updated
	 */
	protected function progress( $updated ) {
		$this->updated += $updated;
		$this->processed++;
		if ( $this->processed % $this->reportInterval != 0 ) {
			return;
		}
		$portion = $this->processed / $this->count;
		$updateRate = $this->updated / $this->processed;

		$now = microtime( true );
		$delta = $now - $this->startTime;
		$estimatedTotalTime = $delta / $portion;
		$eta = $this->startTime + $estimatedTotalTime;

		$this->output(
			sprintf( "%s %s: %6.2f%% done on %s; ETA %s [%d/%d] %.2f/sec <%.2f%% updated>\n",
				WikiMap::getCurrentWikiDbDomain()->getId(),
				wfTimestamp( TS_DB, intval( $now ) ),
				$portion * 100.0,
				$this->table,
				wfTimestamp( TS_DB, intval( $eta ) ),
				$this->processed,
				$this->count,
				$this->processed / $delta,
				$updateRate * 100.0
			)
		);
		flush();
	}

	/**
	 * @param array $params
	 */
	public function runTable( $params ) {
		$dbr = $this->getReplicaDB();

		if ( array_diff( array_keys( $params ),
			[ 'table', 'conds', 'index', 'callback' ] )
		) {
			$this->fatalError( __METHOD__ . ': Missing parameter ' . implode( ', ', $params ) );
		}

		$table = $params['table'];
		// count(*) would melt the DB for huge tables, we can estimate here
		$count = $dbr->newSelectQueryBuilder()
			->table( $table )
			->caller( __METHOD__ )
			->estimateRowCount();
		$this->init( $count, $table );
		$this->output( "Processing $table...\n" );

		$index = (array)$params['index'];
		$indexConds = [];
		$callback = [ $this, $params['callback'] ];

		while ( true ) {
			$conds = array_merge( $params['conds'], $indexConds );
			$res = $dbr->newSelectQueryBuilder()
				->select( '*' )
				->from( $table )
				->where( $conds )
				->orderBy( implode( ',', $index ) )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )->fetchResultSet();
			if ( !$res->numRows() ) {
				// Done
				break;
			}

			foreach ( $res as $row ) {
				$callback( $row );
			}

			if ( $res->numRows() < $this->getBatchSize() ) {
				// Done
				break;
			}

			// Update the conditions to select the next batch.
			$conds = [];
			foreach ( $index as $field ) {
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $res has at at least one item
				$conds[ $field ] = $row->$field;
			}
			$indexConds = [ $dbr->buildComparison( '>', $conds ) ];
		}

		$this->output( "Finished $table... $this->updated of $this->processed rows updated\n" );
	}

	/**
	 * @param string[] $matches
	 * @return string
	 */
	protected function hexChar( $matches ) {
		return sprintf( "\\x%02x", ord( $matches[1] ) );
	}
}
