<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;

class UserCondition extends ChangesListConditionBase {
	/** @inheritDoc */
	public function validateValue( $value ) {
		if ( $value instanceof UserIdentity ) {
			// Convert to UserIdentityValue since that is stringable and so will
			// work with array_unique() etc.
			return new UserIdentityValue( $value->getId(), $value->getName() );
		} else {
			throw new \InvalidArgumentException( 'user filter value must be a UserIdentity' );
		}
	}

	/**
	 * @param stdClass $row
	 * @param UserIdentityValue $value
	 * @return bool
	 */
	public function evaluate( stdClass $row, $value ): bool {
		return $row->rc_user_text === $value->getName();
	}

	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->rcUserFields();
	}

	protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		[ $required, $excluded ] = $this->getUniqueValues();
		if ( $required === [] ) {
			$query->forceEmptySet();
		} elseif ( $required ) {
			[ $ids, $names ] = $this->makeSet( $required );
			$orConds = [];
			if ( $ids ) {
				 $orConds[] = $dbr->expr( 'actor_user', '=', $ids );
			}
			if ( $names ) {
				$orConds[] = $dbr->expr( 'actor_name', '=', $names );
			}
			$query->where( count( $orConds ) > 1 ? $dbr->orExpr( $orConds ) : $orConds[0] );
			$query->joinForConds( 'actor' )->reorderable();
			$query->adjustDensity( QueryBackend::DENSITY_USER );
		} elseif ( $excluded ) {
			$names = $this->makeNames( $excluded );
			$query->where( $dbr->expr( 'actor_name', '!=', $names ) );
			$query->joinForConds( 'actor' )->straight();
		}
	}

	/**
	 * @param UserIdentityValue[] $users
	 * @return array{int[],string[]}
	 */
	private function makeSet( $users ) {
		$ids = [];
		$names = [];
		foreach ( $users as $user ) {
			if ( $user->isRegistered() ) {
				$ids[] = $user->getId();
			} else {
				$names[] = $user->getName();
			}
		}
		return [ $ids, $names ];
	}

	/**
	 * @param UserIdentityValue[] $users
	 * @return string[]
	 */
	private function makeNames( $users ) {
		$names = [];
		foreach ( $users as $user ) {
			$names[] = $user->getName();
		}
		return $names;
	}
}
