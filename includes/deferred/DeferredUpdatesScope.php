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

namespace MediaWiki\Deferred;

/**
 * DeferredUpdates helper class for managing DeferrableUpdate::doUpdate() nesting levels
 * caused by nested calls to DeferredUpdates::doUpdates()
 *
 * @see DeferredUpdates
 * @see DeferredUpdatesScopeStack
 * @internal For use by DeferredUpdates and DeferredUpdatesScopeStack only
 * @since 1.36
 */
class DeferredUpdatesScope {
	/** @var DeferredUpdatesScope|null Parent scope (root scope as none) */
	private $parentScope;
	/** @var DeferrableUpdate|null Deferred update that owns this scope (root scope has none) */
	private $activeUpdate;
	/** @var int|null Active processing stage in DeferredUpdates::STAGES (if any) */
	private $activeStage;
	/** @var DeferrableUpdate[][] Stage-ordered (stage => merge class or position => update) map */
	private $queueByStage;

	/**
	 * @param int|null $activeStage One of DeferredUpdates::STAGES or null
	 * @param DeferrableUpdate|null $update
	 * @param DeferredUpdatesScope|null $parentScope
	 */
	private function __construct(
		$activeStage,
		?DeferrableUpdate $update,
		?DeferredUpdatesScope $parentScope
	) {
		$this->activeStage = $activeStage;
		$this->activeUpdate = $update;
		$this->parentScope = $parentScope;
		$this->queueByStage = array_fill_keys( DeferredUpdates::STAGES, [] );
	}

	/**
	 * @return DeferredUpdatesScope Scope for the case of no in-progress deferred update
	 */
	public static function newRootScope() {
		return new self( null, null, null );
	}

	/**
	 * @param int $activeStage The in-progress stage; one of DeferredUpdates::STAGES
	 * @param DeferrableUpdate $update The deferred update that owns this scope
	 * @param DeferredUpdatesScope $parentScope The parent scope of this scope
	 * @return DeferredUpdatesScope Scope for the case of an in-progress deferred update
	 */
	public static function newChildScope(
		$activeStage,
		DeferrableUpdate $update,
		DeferredUpdatesScope $parentScope
	) {
		return new self( $activeStage, $update, $parentScope );
	}

	/**
	 * Get the deferred update that owns this scope (root scope has none)
	 *
	 * @return DeferrableUpdate|null
	 */
	public function getActiveUpdate() {
		return $this->activeUpdate;
	}

	/**
	 * Enqueue a deferred update within this scope using the specified "defer until" time
	 *
	 * @param DeferrableUpdate $update
	 * @param int $stage One of DeferredUpdates::STAGES
	 */
	public function addUpdate( DeferrableUpdate $update, $stage ) {
		// Handle the case where the specified stage must have already passed
		$stageEffective = max( $stage, $this->activeStage );

		$queue =& $this->queueByStage[$stageEffective];

		if ( $update instanceof MergeableUpdate ) {
			$class = get_class( $update ); // fully-qualified class
			if ( isset( $queue[$class] ) ) {
				/** @var MergeableUpdate $existingUpdate */
				$existingUpdate = $queue[$class];
				'@phan-var MergeableUpdate $existingUpdate';
				$existingUpdate->merge( $update );
				// Move the update to the end to handle things like mergeable purge
				// updates that might depend on the prior updates in the queue running
				unset( $queue[$class] );
				$queue[$class] = $existingUpdate;
			} else {
				$queue[$class] = $update;
			}
		} else {
			$queue[] = $update;
		}
	}

	/**
	 * Get the number of pending updates within this scope
	 *
	 * @return int
	 */
	public function pendingUpdatesCount() {
		return array_sum( array_map( 'count', $this->queueByStage ) );
	}

	/**
	 * Get pending updates within this scope with the given "defer until" stage
	 *
	 * @param int $stage One of DeferredUpdates::STAGES or DeferredUpdates::ALL
	 * @return DeferrableUpdate[]
	 */
	public function getPendingUpdates( $stage ) {
		$matchingQueues = [];
		foreach ( $this->queueByStage as $queueStage => $queue ) {
			if ( $stage === DeferredUpdates::ALL || $stage === $queueStage ) {
				$matchingQueues[] = $queue;
			}
		}

		return array_merge( ...$matchingQueues );
	}

	/**
	 * Cancel all pending updates within this scope
	 */
	public function clearPendingUpdates() {
		$this->queueByStage = array_fill_keys( array_keys( $this->queueByStage ), [] );
	}

	/**
	 * Remove pending updates of the specified stage/class and pass them to a callback
	 *
	 * @param int $stage One of DeferredUpdates::STAGES or DeferredUpdates::ALL
	 * @param string $class Only take updates of this fully qualified class/interface name
	 * @param callable $callback Callback that takes DeferrableUpdate
	 */
	public function consumeMatchingUpdates( $stage, $class, callable $callback ) {
		// T268840: defensively claim the pending updates in case of recursion
		$claimedUpdates = [];
		foreach ( $this->queueByStage as $queueStage => $queue ) {
			if ( $stage === DeferredUpdates::ALL || $stage === $queueStage ) {
				foreach ( $queue as $k => $update ) {
					if ( $update instanceof $class ) {
						$claimedUpdates[] = $update;
						unset( $this->queueByStage[$queueStage][$k] );
					}
				}
			}
		}
		// Execute the callback for each update
		foreach ( $claimedUpdates as $update ) {
			$callback( $update );
		}
	}

	/**
	 * Iteratively, reassign unready pending updates to the parent scope (if applicable) and
	 * process the ready pending updates in stage-order with the callback, repeating the process
	 * until there is nothing left to do
	 *
	 * @param int $stage One of DeferredUpdates::STAGES or DeferredUpdates::ALL
	 * @param callable $callback Processing function with arguments (update, effective stage)
	 */
	public function processUpdates( $stage, callable $callback ) {
		if ( $stage === DeferredUpdates::ALL ) {
			// Do everything, all the way to the last "defer until" stage
			$activeStage = DeferredUpdates::STAGES[count( DeferredUpdates::STAGES ) - 1];
		} else {
			// Handle the case where the specified stage must have already passed
			$activeStage = max( $stage, $this->activeStage );
		}

		do {
			$processed = $this->upmergeUnreadyUpdates( $activeStage );
			foreach ( range( DeferredUpdates::STAGES[0], $activeStage ) as $queueStage ) {
				$processed += $this->processStageQueue( $queueStage, $activeStage, $callback );
			}
		} while ( $processed > 0 );
	}

	/**
	 * If this is a child scope, then reassign unready pending updates to the parent scope:
	 *   - MergeableUpdate instances will be reassigned to the parent scope on account of their
	 *     de-duplication/melding semantics. They are normally only processed in the root scope.
	 *   - DeferrableUpdate instances with a "defer until" stage later than the specified stage
	 *     will be reassigned to the parent scope since they are not ready.
	 *
	 * @param int $activeStage One of DeferredUpdates::STAGES
	 * @return int Number of updates moved
	 */
	private function upmergeUnreadyUpdates( $activeStage ) {
		$reassigned = 0;

		if ( !$this->parentScope ) {
			return $reassigned;
		}

		foreach ( $this->queueByStage as $queueStage => $queue ) {
			foreach ( $queue as $k => $update ) {
				if ( $update instanceof MergeableUpdate || $queueStage > $activeStage ) {
					unset( $this->queueByStage[$queueStage][$k] );
					$this->parentScope->addUpdate( $update, $queueStage );
					++$reassigned;
				}
			}
		}

		return $reassigned;
	}

	/**
	 * @param int $stage One of DeferredUpdates::STAGES
	 * @param int $activeStage One of DeferredUpdates::STAGES
	 * @param callable $callback Processing function with arguments (update, effective stage)
	 * @return int Number of updates processed
	 */
	private function processStageQueue( $stage, $activeStage, callable $callback ) {
		$processed = 0;

		// Defensively claim the pending updates in case of recursion
		$claimedUpdates = $this->queueByStage[$stage];
		$this->queueByStage[$stage] = [];

		// Keep doing rounds of updates until none get enqueued...
		while ( $claimedUpdates ) {
			// Segregate the updates into one for DataUpdate and one for everything else.
			// This is done for historical reasons; DataUpdate used to have its own static
			// method for running DataUpdate instances and was called first in DeferredUpdates.
			// Before that, page updater code directly ran that static method.
			// @TODO: remove this logic given the existence of RefreshSecondaryDataUpdate
			$claimedDataUpdates = [];
			$claimedGenericUpdates = [];
			foreach ( $claimedUpdates as $claimedUpdate ) {
				if ( $claimedUpdate instanceof DataUpdate ) {
					$claimedDataUpdates[] = $claimedUpdate;
				} else {
					$claimedGenericUpdates[] = $claimedUpdate;
				}
				++$processed;
			}

			// Execute the DataUpdate queue followed by the DeferrableUpdate queue...
			foreach ( $claimedDataUpdates as $claimedDataUpdate ) {
				$callback( $claimedDataUpdate, $activeStage );
			}
			foreach ( $claimedGenericUpdates as $claimedGenericUpdate ) {
				$callback( $claimedGenericUpdate, $activeStage );
			}

			// Check for new entries;  defensively claim the pending updates in case of recursion
			$claimedUpdates = $this->queueByStage[$stage];
			$this->queueByStage[$stage] = [];
		}

		return $processed;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( DeferredUpdatesScope::class, 'DeferredUpdatesScope' );
