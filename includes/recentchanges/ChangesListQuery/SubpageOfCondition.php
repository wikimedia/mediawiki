<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use LogicException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\LikeValue;

/**
 * Check if the changed title is a subpage of some specified title.
 *
 * @since 1.45
 */
class SubpageOfCondition extends ChangesListConditionBase {
	private array $prefixesByNs = [];

	/** @inheritDoc */
	public function require( $value ): void {
		[ $ns, $dbk ] = $this->validateValue( $value );
		$this->prefixesByNs[$ns][$dbk] = true;
	}

	/**
	 * @param mixed $value
	 * @return never
	 */
	public function exclude( $value ): void {
		throw new LogicException( 'unimplemented' );
	}

	/** @inheritDoc */
	public function evaluate( \stdClass $row, $value ): bool {
		[ $ns, $dbk ] = $value;
		return (int)$row->rc_namespace === $ns
			&& str_starts_with( $row->rc_title, $dbk );
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->fields( [ 'rc_namespace', 'rc_title' ] );
	}

	/** @inheritDoc */
	public function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		$orConds = [];
		foreach ( $this->prefixesByNs as $ns => $prefixes ) {
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
			$query->where( $dbr->orExpr( $orConds ) );
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
		return [ $ns, "$dbk/" ];
	}
}
