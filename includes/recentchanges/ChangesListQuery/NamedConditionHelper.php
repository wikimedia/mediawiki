<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\User\TempUser\TempUserConfig;
use stdClass;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Shared code between the named and experience filter conditions
 *
 * @since 1.45
 */
class NamedConditionHelper {
	public function __construct(
		private TempUserConfig $tempUserConfig
	) {
	}

	/**
	 * Determine whether a result row contains a named user
	 *
	 * @param stdClass $row
	 * @return bool
	 */
	public function isNamed( stdClass $row ) {
		return $row->rc_user && !$this->tempUserConfig->isTempName( $row->rc_user_text );
	}

	/**
	 * @param IReadableDatabase $dbr
	 * @param bool $isNamed
	 * @return IExpression
	 */
	public function getExpression( IReadableDatabase $dbr, bool $isNamed ) {
		$expr = $dbr->expr( 'actor_user', $isNamed ? '!=' : '=', null );
		if ( !$this->tempUserConfig->isKnown() ) {
			return $expr;
		}
		if ( $isNamed ) {
			return $expr->andExpr( $this->tempUserConfig->getMatchCondition( $dbr,
				'actor_name', IExpression::NOT_LIKE ) );
		} else {
			return $expr->orExpr( $this->tempUserConfig->getMatchCondition( $dbr,
				'actor_name', IExpression::LIKE ) );
		}
	}

}
