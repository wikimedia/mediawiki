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

/**
 * DeferredUpdates helper class for tracking DeferrableUpdate::doUpdate() nesting levels
 * caused by nested calls to DeferredUpdates::doUpdates()
 *
 * @internal For use by DeferredUpdates only
 * @since 1.36
 */
class DeferredUpdatesScopeStack {
	/** @var DeferredUpdatesScope[] Stack of root scope and any recursive scopes */
	private $stack;

	public function __construct() {
		$this->stack = [ DeferredUpdatesScope::newRootScope() ];
	}

	/**
	 * @return DeferredUpdatesScope The innermost scope
	 */
	public function current() {
		return $this->stack[count( $this->stack ) - 1];
	}

	/**
	 * Make a new child scope, push it onto the stack, and return it
	 *
	 * @param int $activeStage The in-progress stage; one of DeferredUpdates::STAGES
	 * @param DeferrableUpdate $update The deferred update that owns this scope
	 * @return DeferredUpdatesScope Scope for the case of an in-progress deferred update
	 */
	public function descend( $activeStage, DeferrableUpdate $update ) {
		$scope = DeferredUpdatesScope::newChildScope( $activeStage, $update, $this->current() );
		$this->stack[count( $this->stack )] = $scope;

		return $scope;
	}

	/**
	 * Pop the innermost scope from the stack
	 *
	 * @return DeferredUpdatesScope
	 */
	public function ascend() {
		if ( count( $this->stack ) <= 1 ) {
			throw new LogicException( "Cannot pop root stack scope; something is out of sync" );
		}

		return array_pop( $this->stack );
	}

	/**
	 * Get the depth of the scope stack below the root scope
	 *
	 * @return int
	 */
	public function getRecursiveDepth() {
		return count( $this->stack ) - 1;
	}

	/**
	 * Whether DeferredUpdates::addUpdate() may run the update right away
	 *
	 * @return bool
	 */
	public function allowOpportunisticUpdates(): bool {
		// Overridden in DeferredUpdatesScopeMediaWikiStack::allowOpportunisticUpdates
		return false;
	}

	/**
	 * Queue an EnqueueableDataUpdate as a job instead
	 *
	 * @see JobQueueGroup::push
	 * @param EnqueueableDataUpdate $update
	 */
	public function queueDataUpdate( EnqueueableDataUpdate $update ): void {
		throw new LogicException( 'Cannot queue jobs from DeferredUpdates in standalone mode' );
	}

	/**
	 * @param DeferrableUpdate $update
	 */
	public function onRunUpdateStart( DeferrableUpdate $update ): void {
		// No-op
		// Overridden in DeferredUpdatesScopeMediaWikiStack::onRunUpdateStart
	}

	/**
	 * @param DeferrableUpdate $update
	 */
	public function onRunUpdateEnd( DeferrableUpdate $update ): void {
		// No-op
		// Overridden in DeferredUpdatesScopeMediaWikiStack::onRunUpdateEnd
	}

	/**
	 * @param DeferrableUpdate $update
	 */
	public function onRunUpdateFailed( DeferrableUpdate $update ): void {
		// No-op
		// Overridden in DeferredUpdatesScopeMediaWikiStack::onRunUpdateFailed
	}
}
