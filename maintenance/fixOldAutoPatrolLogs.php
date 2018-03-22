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

use Wikimedia\Rdbms\ResultWrapper;

require_once __DIR__ . '/Maintenance.php';

/**
 * Properly mark old autopatrol logs in the logging table.
 *
 * @ingroup Maintenance
 */
class FixOldAutoPatrolLogs extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Properly mark old autopatrol logs in the logging table' );
		$this->addOption( 'dry-run', 'Print debug info instead of actually changing' );
		$this->addOption(
			'batch-size', "Number of rows to change per batch (Default: 100)",
			false,
			true
		);
		$this->addOption(
			'before',
			'Timestamp to check only before that time',
			false,
			true
		);
		$this->addOption(
			'from-id',
			'First row (page id) to start updating from',
			false,
			true
		);
		$this->addOption(
			'sleep',
			'Sleep time (in seconds) between every batch',
			false,
			true
		);
	}

	public function execute() {
		$sleep = (int)$this->getOption( 'sleep', 10 );
		$fromId = $this->getOption( 'from-id', null );
		while ( true ) {
			$rows = $this->getRows( $fromId );
			if ( !$rows ) {
				break;
			}

			if ( $this->hasOption( 'dry-run' ) ) {
				$this->output(
					'These rows will be marked as autopatrolled: ' . implode( ', ', $rows	) . "\n"
				);
			} else {
				$this->changeRows( $rows );
				$this->output( 'Processed up to row id ' . end( $rows ) . "\n" );
			}

			$fromId = end( $rows );

			if ( $sleep > 0 ) {
				sleep( $sleep );
			}
		}
	}

	private function getRows( $fromId ) {
		$dbr = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection(
			DB_REPLICA
		);
		$batchSize = (int)$this->getOption( 'batch-size', 100 );
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
			[ 'log_id', 'log_user', 'log_params' ],
			$conds,
			__METHOD__,
			[ 'LIMIT' => $batchSize ]
		);

		return $this->filterManualPatrols( $result );
	}

	/**
	 * @param ResultWrapper $result
	 * @return int[]
	 */
	private function filterManualPatrols( ResultWrapper $result ) {
		$dbr = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection(
			DB_REPLICA
		);

		$func = function ( $row ) { return DatabaseLogEntry::newFromRow( $row ); };
		$logs = array_map( $func, iterator_to_array( $result, false ) );

		$revisionMap = [];
		/** @var DatabaseLogEntry $log */
		foreach ( $logs as $log ) {
			$params = $log->getParameters();
			var_dump( $params );
			if ( !isset( $params['4::curid'] ) ) {
				continue;
			}

			if ( !isset( $params['4::curid'] ) || $log->getPerformer()->getId() === 0 ) {
				continue;
			}

			$revisionMap[$params['4::curid']] = [ $log->getId(), $log->getPerformer()->getId() ];
		}

		if ( empty( $revisionMap ) ) {
			return [];
		}

		$revisionData = $dbr->select(
			'revision',
			[ 'rev_id', 'rev_user' ],
			[ 'rev_id' => array_keys( $revisionMap ) ]
		);

		if ( empty( $revisionData ) ) {
			return [];
		}

		$autoPatrols = [];
		foreach ( $revisionData as $row ) {
			$row->rev_id = (int)$row->rev_id;
			$row->rev_user = (int)$row->rev_user;

			if ( $row->rev_user === 0 ) {
				continue;
			}

			// Sanity check
			if ( !isset( $revisionMap[$row->rev_id] ) ) {
				continue;
			}

			if ( $row->rev_user === $revisionMap[$row->rev_id][1] ) {
				$autoPatrols[] = $revisionMap[$row->rev_id][0];
			}
		}

		return $autoPatrols;
	}

	private function changeRows( array  $rows ) {
		$dbw = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection(
			DB_MASTER
		);

		$dbw->update(
			'logging',
			[ 'log_action' => 'autopatrol' ],
			[ 'log_id' => $rows ]
		);
	}

}

$maintClass = FixOldAutoPatrolLogs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
