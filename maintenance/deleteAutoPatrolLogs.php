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
			'Check old patrol logs (for deleting old format autopatrols).' .
				'Note that this will not delete rows older than 2011 (MediaWiki 1.18).'
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
		$dbr = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection(
			DB_REPLICA
		);
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

		return $dbr->selectFieldValues(
			'logging',
			'log_id',
			$conds,
			__METHOD__,
			[ 'LIMIT' => $this->getBatchSize() ]
		);
	}

	private function getRowsOld( $fromId ) {
		$dbr = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection(
			DB_REPLICA
		);
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

		$result = $dbr->select(
			'logging',
			[ 'log_id', 'log_params' ],
			$conds,
			__METHOD__,
			[ 'LIMIT' => $batchSize ]
		);

		$last = null;
		$autopatrols = [];
		foreach ( $result as $row ) {
			$last = $row->log_id;
			Wikimedia\suppressWarnings();
			$params = unserialize( $row->log_params );
			Wikimedia\restoreWarnings();

			// Skipping really old rows, before 2011
			if ( !is_array( $params ) || !array_key_exists( '6::auto', $params ) ) {
				continue;
			}

			$auto = $params['6::auto'];
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
		$dbw = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection(
			DB_MASTER
		);

		$dbw->delete(
			'logging',
			[ 'log_id' => $rows ],
			__METHOD__
		);

		MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->waitForReplication();
	}

}

$maintClass = DeleteAutoPatrolLogs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
