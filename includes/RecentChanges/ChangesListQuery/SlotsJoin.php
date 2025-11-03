<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

/**
 * A join on the slots table, mostly to support requireSlotChanged().
 *
 * There can be multiple slots for a given change, which is dealt with here by
 * restricting the join to a specified role.
 *
 * We also provide an alias for a the slots of the parent revision of the
 * change.
 *
 * This could be generalised if there was a need to do so.
 *
 * @since 1.45
 */
class SlotsJoin extends BasicJoin {
	private ?int $roleId = null;

	public function __construct() {
		parent::__construct(
			'slots',
			'slot',
			'rc_this_oldid = slot.slot_revision_id' );
	}

	/**
	 * Set the slot_role_id to use
	 *
	 * @param int $roleId
	 */
	public function setRoleId( int $roleId ) {
		$this->roleId = $roleId;
	}

	/** @inheritDoc */
	protected function getExtraConds( ?string $alias ) {
		if ( $this->roleId === null ) {
			throw new \LogicException( 'Role ID must be set for slots join' );
		}
		$field = 'slot_role_id';
		if ( $alias !== null && $alias !== '' ) {
			$field = "$alias.$field";
		}
		return [ $field => $this->roleId ];
	}

	/**
	 * Fetch the instance of this join with a parent_slot alias, with join
	 * conditions such that it returns the slot for the parent change.
	 */
	public function parentAlias(): ChangesListJoinBuilder {
		if ( !isset( $this->instances['parent_slot'] ) ) {
			if ( $this->roleId === null ) {
				throw new \LogicException( 'Role ID must be set for slots join' );
			}
			$this->instances['parent_slot'] = new ChangesListJoinBuilder(
				'slots',
				'parent_slot',
				[
					'rc_last_oldid = parent_slot.slot_revision_id',
				] + $this->getExtraConds( 'parent_slot' )
			);
		}
		return $this->instances['parent_slot'];
	}
}
