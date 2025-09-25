<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\RawSQLValue;

class RevisionTypeCondition extends ChangesListConditionBase {

	private const TYPES = [ 'none', 'old', 'latest' ];

	/** @inheritDoc */
	public function validateValue( $value ) {
		if ( !in_array( $value, self::TYPES, true ) ) {
			throw new \InvalidArgumentException(
				"revisionType must be one of: none, old, latest"
			);
		}
		return $value;
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		if ( (int)$row->rc_this_oldid === 0 ) {
			$type = 'none';
		} elseif ( (int)$row->rc_this_oldid === (int)$row->page_latest ) {
			$type = 'latest';
		} else {
			$type = 'old';
		}
		return $value === $type;
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->joinForFields( 'page' )->weakLeft();
		$query->fields( [ 'rc_this_oldid', 'page_latest' ] );
	}

	/** @inheritDoc */
	protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		$required = $this->getEnumValues( self::TYPES );
		if ( $required === null ) {
			return;
		} elseif ( !$required ) {
			$query->forceEmptySet();
		} else {
			$query->joinForConds( 'page' )->weakLeft();
			$orConds = [];
			$req = $this->flip( $required );
			if ( $req['latest'] xor $req['old'] ) {
				$op = $req['latest'] ? '=' : '!=';
				$orConds[] = $dbr->expr( 'rc_this_oldid', $op, new RawSQLValue( 'page_latest' ) );
			}
			if ( $req['none'] ) {
				$orConds[] = $dbr->expr( 'rc_this_oldid', '=', 0 );
			}
			if ( $orConds ) {
				$query->where( $dbr->orExpr( $orConds ) );
			}
		}
	}

	/**
	 * Convert an list of values to an associative array of booleans
	 * @param array<int,string> $values
	 * @return array<string,bool>
	 */
	private function flip( $values ) {
		return array_fill_keys( $values, true ) +
			array_fill_keys( self::TYPES, false );
	}
}
