<?php
/**
 * Resets the page_random field for articles in the provided time range.
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
 * Maintenance script that resets page_random over a time range.
 *
 * @ingroup Maintenance
 */
class ResetPageRandom extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Reset the page_random for articles within given date range' );
		$this->addOption( 'from',
			'From date range selector to select articles to update, ex: 20041011000000' );
		$this->addOption( 'to',
			'To date range selector to select articles to update, ex: 20050708000000' );
		$this->addOption( 'dry', 'Do not update column' );
		$this->addOption( 'batch-start',
			'Optional: Use when you need to restart the reset process from a given page ID offset'
			. ' in case a previous reset failed or was stopped'
		);
		// Initialize batch size to a good default value and enable the batch size option.
		$this->setBatchSize( 200 );
	}

	public function execute() {
		$batchSize = $this->getBatchSize();
		$dbw = $this->getDB( DB_MASTER );
		$lbFactory = \MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$dbr = $this->getDB( DB_REPLICA );
		$from = wfTimestampOrNull( TS_MW, $this->getOption( 'from' ) );
		$to = wfTimestampOrNull( TS_MW, $this->getOption( 'to' ) );

		if ( $from === null || $to === null ) {
			$this->output( "--from and --to have to be provided" . PHP_EOL );
			return false;
		}
		if ( $from >= $to ) {
			$this->output( "--from has to be smaller than --to" . PHP_EOL );
			return false;
		}
		$batchStart = (int)$this->getOption( 'batch-start', 0 );
		$changed = 0;
		$dry = (bool)$this->getOption( 'dry' );

		$message = "Resetting page_random column within date range from $from to $to";
		if ( $batchStart > 0 ) {
			$message .= " starting from page ID $batchStart";
		}
		$message .= $dry ? ". dry run" : '.';

		$this->output( $message . PHP_EOL );
		do {
			$this->output( "  ...doing chunk of $batchSize from $batchStart " . PHP_EOL );

			// Find the oldest page revision associated with each page_id. Iff it falls in the given
			// time range AND it's greater than $batchStart, yield the page ID. If it falls outside the
			// time range, it was created before or after the occurrence of T208909 and its page_random
			// is considered valid. The replica is used for this read since page_id and the rev_timestamp
			// will not change between queries.
			$res = $dbr->select(
				'page',
				'page_id',
				[
					'(' . $dbr->selectSQLText( 'revision', 'MIN(rev_timestamp)', 'rev_page=page_id' ) . ') ' .
						'BETWEEN ' . $dbr->addQuotes( $dbr->timestamp( $from ) ) .
						' AND ' . $dbr->addQuotes( $dbr->timestamp( $to ) ),
					'page_id > ' . $dbr->addQuotes( $batchStart )
				],
				__METHOD__,
				[ 'LIMIT' => $batchSize, 'ORDER BY' => 'page_id' ]
			);

			foreach ( $res as $row ) {
				if ( !$dry ) {
					# Update the row...
					$dbw->update( 'page',
						[ 'page_random' => wfRandom() ],
						[ 'page_id' => $row->page_id ],
						__METHOD__ );
					$changed += $dbw->affectedRows();
				} else {
					$changed++;
				}
			}
			if ( $row ) {
				$batchStart = $row->page_id;
			} else {
				// We don't need to set the $batchStart as $res is empty,
				// and we don't need to do another loop
				// the while() condition will evaluate to false and
				// we will leave the do{}while() block.
			}

			$lbFactory->waitForReplication();
		} while ( $res->numRows() === $batchSize );
		$this->output( "page_random reset complete ... changed $changed rows" . PHP_EOL );

		return true;
	}
}

$maintClass = ResetPageRandom::class;
require_once RUN_MAINTENANCE_IF_MAIN;
