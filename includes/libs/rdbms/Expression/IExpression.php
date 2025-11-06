<?php

namespace Wikimedia\Rdbms;

use Wikimedia\Rdbms\Database\DbQuoter;

/**
 * @since 1.42
 */
interface IExpression {

	public const ACCEPTABLE_OPERATORS = [ '>', '<', '!=', '=', '>=', '<=', self::LIKE, self::NOT_LIKE ];

	public const LIKE = 'LIKE';
	public const NOT_LIKE = 'NOT LIKE';

	/**
	 * Return SQL for execution.
	 * @internal
	 */
	public function toSql( DbQuoter $dbQuoter ): string;

	/**
	 * Return SQL for aggregated logging.
	 *
	 * Replaces values with placeholders.
	 *
	 * @internal
	 */
	public function toGeneralizedSql(): string;
}
