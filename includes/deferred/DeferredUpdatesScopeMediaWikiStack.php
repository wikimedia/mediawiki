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
 *
 * @file
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\DBTransactionError;

/**
 * This class decouples DeferredUpdates's awareness of MediaWikiServices to ease unit testing.
 *
 * NOTE: As a process-level utility, it is important that MediaWikiServices::getInstance() is
 * referenced explicitly each time, so as to not cache potentially stale references.
 * For example after the Installer, or MediaWikiIntegrationTestCase, replace the service container.
 *
 * @internal For use by DeferredUpdates only
 * @since 1.41
 */
class DeferredUpdatesScopeMediaWikiStack extends DeferredUpdatesScopeStack {

	private function areDatabaseTransactionsActive(): bool {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		if ( $lbFactory->hasTransactionRound()
			|| !$lbFactory->isReadyForRoundOperations()
		) {
			return true;
		}

		foreach ( $lbFactory->getAllLBs() as $lb ) {
			if ( $lb->hasPrimaryChanges() || $lb->explicitTrxActive() ) {
				return true;
			}
		}

		return false;
	}

	public function allowOpportunisticUpdates(): bool {
		global $wgCommandLineMode;

		if ( !$wgCommandLineMode ) {
			// In web req
			return false;
		}

		// Run the updates only if they will have outer transaction scope
		if ( $this->areDatabaseTransactionsActive() ) {
			// transaction round is active or connection is not ready for commit()
			return false;
		}

		return true;
	}

	public function queueDataUpdate( EnqueueableDataUpdate $update ): void {
		$spec = $update->getAsJobSpecification();
		$jobQueueGroupFactory = MediaWikiServices::getInstance()->getJobQueueGroupFactory();
		$jobQueueGroupFactory->makeJobQueueGroup( $spec['domain'] )->push( $spec['job'] );
	}

	public function onRunUpdateStart( DeferrableUpdate $update ): void {
		global $wgCommandLineMode;

		// Increment a counter metric
		$type = get_class( $update )
			. ( $update instanceof DeferrableCallback ? '_' . $update->getOrigin() : '' );
		$httpMethod = $wgCommandLineMode ? 'cli' : strtolower( $_SERVER['REQUEST_METHOD'] ?? 'GET' );
		$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
		$stats->increment( "deferred_updates.$httpMethod.$type" );

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );
		if ( !$ticket || $lbFactory->hasTransactionRound() ) {
			throw new DBTransactionError( null, "A database transaction round is pending." );
		}

		if ( $update instanceof DataUpdate ) {
			$update->setTransactionTicket( $ticket );
		}

		// Designate $update::doUpdate() as the write round owner
		$fnameTrxOwner = ( $update instanceof DeferrableCallback )
			? $update->getOrigin()
			: get_class( $update ) . '::doUpdate';

		// Determine whether the write round will be explicit or implicit
		$useExplicitTrxRound = !(
			$update instanceof TransactionRoundAwareUpdate &&
			$update->getTransactionRoundRequirement() == $update::TRX_ROUND_ABSENT
		);

		// Ensure any stale repeatable-read snapshot on the primary DB have been flushed
		// before running the update. E.g. left-over from an implicit transaction round
		if ( $useExplicitTrxRound ) {
			// new explicit round
			$lbFactory->beginPrimaryChanges( $fnameTrxOwner );
		} else {
			// new implicit round
			$lbFactory->commitPrimaryChanges( $fnameTrxOwner );
		}
	}

	public function onRunUpdateEnd( DeferrableUpdate $update ): void {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		$fnameTrxOwner = ( $update instanceof DeferrableCallback )
			? $update->getOrigin()
			: get_class( $update ) . '::doUpdate';

		// Commit any pending changes from the explicit or implicit transaction round
		$lbFactory->commitPrimaryChanges( $fnameTrxOwner );
	}

	public function onRunUpdateFailed( DeferrableUpdate $update ): void {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->rollbackPrimaryChanges( __METHOD__ );
	}
}
