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
 * Remove patrol and autopatrol logs in the logging table.
 *
 * @ingroup Maintenance
 */
class DeletePatrolLogs extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Remove patrol and autopatrol logs in the logging table' );
		$this->addOption( 'dry-run', 'Print debug info instead of actually deleting' );
		$this->addOption( 'delete-all', 'If set, it deletes all patrol logs not just autopatrol' );
		$this->addOption(
			'batch-size', "Number of rows to delete per batch (Default: 1000)",
			false,
			true
		);
		$this->addOption(
			'before',
			'Timestamp to delete only before that time',
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
				$this->output( 'These rows will get deleted: ' . implode( ', ', $rows ) . "\n" );
			} else {
				$this->deleteRows( $rows );
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
		$batchSize = (int)$this->getOption( 'batch-size', 1000 );
		$before = $this->getOption( 'before', false );

		$conds = [ 'log_type' => 'patrol' ];
		if ( !$this->getOption( 'delete-all', false ) ) {
			$conds['log_action'] = 'autopatrol';
		} elseif ( !$before ) {
			throw new MWException( 'When using --delete-all option, using --before is mandatory' );
		}

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
			[ 'LIMIT' => $batchSize ]
		);
	}

	private function deleteRows( array  $rows ) {
		$dbw = MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection(
			DB_MASTER
		);

		$dbw->delete(
			'logging',
			[ 'log_id' => $rows ],
			__METHOD__
		);
	}

}

$maintClass = DeletePatrolLogs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
