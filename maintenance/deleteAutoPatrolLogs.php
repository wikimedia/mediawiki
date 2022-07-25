<?php
/**
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
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Remove autopatrol logs in the logging table.
 *
 * @ingroup Maintenance
 */
class DeleteAutoPatrolLogs extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Remove autopatrol logs in the logging table' );
		$this->addOption( 'dry-run', 'Print debug info instead of actually deleting' );
		$this->addOption(
			'check-old',
			'Check old patrol logs (for deleting old format autopatrols).'
		);
		$this->addOption(
			'before',
			'Timestamp to delete only before that time, all MediaWiki timestamp formats are accepted',
			false,
			true
		);
		$this->addOption(
			'from-id',
			'First row (log id) to start updating from',
			false,
			true
		);
		$this->addOption(
			'sleep',
			'Sleep time (in seconds) between every batch',
			false,
			true
		);
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$this->setBatchSize( $this->getOption( 'batch-size', $this->getBatchSize() ) );

		$sleep = (int)$this->getOption( 'sleep', 10 );
		$fromId = $this->getOption( 'from-id', null );
		$this->countDown( 5 );
		while ( true ) {
			if ( $this->hasOption( 'check-old' ) ) {
				$rowsData = $this->getRowsOld( $fromId );
				// We reached end of the table
				if ( !$rowsData ) {
					break;
				}
				$rows = $rowsData['rows'];
				$fromId = $rowsData['lastId'];

				// There is nothing to delete in this batch
				if ( !$rows ) {
					continue;
				}
			} else {
				$rows = $this->getRows( $fromId );
				if ( !$rows ) {
					break;
				}
				$fromId = end( $rows );
			}

			if ( $this->hasOption( 'dry-run' ) ) {
				$this->output( 'These rows will get deleted: ' . implode( ', ', $rows ) . "\n" );
			} else {
				$this->deleteRows( $rows );
				$this->output( 'Processed up to row id ' . end( $rows ) . "\n" );
			}

			if ( $sleep > 0 ) {
				sleep( $sleep );
			}
		}
	}

	private function getRows( $fromId ) {
		$lb = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbr = $lb->getConnectionRef( DB_REPLICA );
		$before = $this->getOption( 'before', false );

		$conds = [
			'log_type' => 'patrol',
			'log_action' => 'autopatrol',
		];

		if ( $fromId ) {
			$conds[] = 'log_id > ' . $dbr->addQuotes( $fromId );
		}

		if ( $before ) {
			$conds[] = 'log_timestamp < ' . $dbr->addQuotes( $dbr->timestamp( $before ) );
		}

		return $dbr->newSelectQueryBuilder()
			->select( 'log_id' )
			->from( 'logging' )
			->where( $conds )
			->orderBy( 'log_id' )
			->limit( $this->getBatchSize() )
			->caller( __METHOD__ )
			->fetchFieldValues();
	}

	private function getRowsOld( $fromId ) {
		$lb = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbr = $lb->getConnectionRef( DB_REPLICA );
		$batchSize = $this->getBatchSize();
		$before = $this->getOption( 'before', false );

		$conds = [
			'log_type' => 'patrol',
			'log_action' => 'patrol',
		];

		if ( $fromId ) {
			$conds[] = 'log_id > ' . $dbr->addQuotes( $fromId );
		}

		if ( $before ) {
			$conds[] = 'log_timestamp < ' . $dbr->addQuotes( $dbr->timestamp( $before ) );
		}

		$result = $dbr->newSelectQueryBuilder()
			->select( [ 'log_id', 'log_params' ] )
			->from( 'logging' )
			->where( $conds )
			->orderBy( 'log_id' )
			->limit( $batchSize )
			->caller( __METHOD__ )
			->fetchResultSet();

		$last = null;
		$autopatrols = [];
		foreach ( $result as $row ) {
			$last = $row->log_id;
			$logEntry = DatabaseLogEntry::newFromRow( $row );
			$params = $logEntry->getParameters();
			if ( !is_array( $params ) ) {
				continue;
			}

			// This logic belongs to PatrolLogFormatter::getMessageKey
			// and LogFormatter::extractParameters the 'auto' value is logically presented as key [5].
			// For legacy case the logical key is index + 3, meaning [2].
			// For the modern case, the logical key is index - 1 meaning [6].
			if ( array_key_exists( '6::auto', $params ) ) {
				// Between 2011-2016 autopatrol logs
				$auto = $params['6::auto'] === true;
			} elseif ( $logEntry->isLegacy() === true && array_key_exists( 2, $params ) ) {
				// Pre-2011 autopatrol logs
				$auto = $params[2] === '1';
			} else {
				continue;
			}

			if ( $auto ) {
				$autopatrols[] = $row->log_id;
			}
		}

		if ( $last === null ) {
			return null;
		}

		return [ 'rows' => $autopatrols, 'lastId' => $last ];
	}

	private function deleteRows( array $rows ) {
		$lb = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbw = $lb->getConnectionRef( DB_PRIMARY );

		$dbw->delete(
			'logging',
			[ 'log_id' => $rows ],
			__METHOD__
		);

		$lbFactory = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->waitForReplication();
	}

}

$maintClass = DeleteAutoPatrolLogs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
