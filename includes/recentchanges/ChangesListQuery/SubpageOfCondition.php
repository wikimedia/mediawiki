<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\RawSQLExpression;

/**
 * Check if the changed title is a subpage of some specified title.
 *
 * @since 1.45
 */
class SubpageOfCondition extends ChangesListConditionBase {
	/**
	 * @param \stdClass $row
	 * @param TitleConditionValue $value
	 * @return bool
	 */
	public function evaluate( \stdClass $row, $value ): bool {
		return (int)$row->rc_namespace === $value->namespace
			&& str_starts_with( $row->rc_title, $value->dbKey );
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->fields( [ 'rc_namespace', 'rc_title' ] );
	}

	/** @inheritDoc */
	public function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		[ $required, $excluded ] = $this->getUniqueValues();
		if ( $required === [] ) {
			$query->forceEmptySet();
		} elseif ( $required ) {
			$query->where( $this->makeExpression( $dbr, $required ) );
		} elseif ( $excluded ) {
			$expr = $this->makeExpression( $dbr, $excluded );
			$query->where(
				new RawSQLExpression( 'NOT (' . $expr->toSql( $dbr ) . ')' )
			);
		}
	}

	/**
	 * @param IReadableDatabase $dbr
	 * @param TitleConditionValue[] $values
	 * @return \Wikimedia\Rdbms\OrExpressionGroup
	 */
	private function makeExpression( IReadableDatabase $dbr, $values ) {
		$prefixesByNs = TitleConditionValue::makeSet( $values );
		$orConds = [];
		foreach ( $prefixesByNs as $ns => $prefixes ) {
			$titleExpr = null;
			foreach ( $prefixes as $prefix => $unused ) {
				$prefixExpr = $dbr->expr(
					'rc_title',
					IExpression::LIKE,
					new LikeValue(
						$prefix,
						$dbr->anyString()
					)
				);
				$titleExpr = $titleExpr ? $titleExpr->orExpr( $prefixExpr ) : $prefixExpr;
			}
			if ( $titleExpr ) {
				$orConds[] = $dbr->expr( 'rc_namespace', '=', $ns )
					->andExpr( $titleExpr );
			}
		}
		if ( $orConds ) {
			return $dbr->orExpr( $orConds );
		} else {
			throw new \RuntimeException( 'Need at least one title' );
		}
	}

	/** @inheritDoc */
	public function validateValue( $value ) {
		if ( $value instanceof LinkTarget ) {
			$ns = $value->getNamespace();
			$dbk = $value->getDBkey();
		} elseif ( $value instanceof PageReference ) {
			$ns = $value->getNamespace();
			$dbk = $value->getDBkey();
		} else {
			throw new \InvalidArgumentException(
				"Unknown value type for subpageof"
			);
		}
		// Need a single trailing slash
		$dbk = rtrim( $dbk, '/' );
		return new TitleConditionValue( $ns, "$dbk/" );
	}
}
