<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;
use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\RawSQLExpression;

/**
 * A condition matching a set of titles
 *
 * @since 1.45
 */
class TitleCondition extends ChangesListConditionBase {

	/**
	 * @param LinkTarget|PageReference $value
	 * @return TitleConditionValue
	 */
	public function validateValue( $value ) {
		return TitleConditionValue::create( $value );
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		return (int)$row->rc_namespace === $value->namespace
			&& $row->rc_title === $value->dbKey;
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->fields( [ 'rc_namespace', 'rc_title' ] );
	}

	/** @inheritDoc */
	protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		[ $required, $excluded ] = $this->getUniqueValues();
		if ( $required === [] ) {
			$query->forceEmptySet();
		} elseif ( $required ) {
			$query->where(
				new RawSQLExpression(
					'(' .
					$dbr->makeWhereFrom2d(
						TitleConditionValue::makeSet( $required ),
						'rc_namespace',
						'rc_title'
					) . ')'
				)
			);
		} elseif ( $excluded ) {
			$query->where(
				new RawSQLExpression(
					'NOT (' .
					$dbr->makeWhereFrom2d(
						TitleConditionValue::makeSet( $excluded ),
						'rc_namespace',
						'rc_title'
					) . ')'
				)
			);
		}
	}
}
