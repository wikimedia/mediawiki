<?php

namespace Wikimedia\Rdbms;

use Wikimedia\Rdbms\Database\DbQuoter;

/**
 * @internal
 */
interface IExpression {

	public const ACCEPTABLE_OPERATORS = [ '>', '<', '!=', '=', '>=', '<=' ];

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
