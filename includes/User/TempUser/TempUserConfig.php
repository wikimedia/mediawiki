<?php

namespace MediaWiki\User\TempUser;

use MediaWiki\Permissions\Authority;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Interface for temporary user creation config and name matching.
 *
 * This is separate from TempUserCreator to avoid dependency loops during
 * service construction, since TempUserCreator needs UserNameUtils which
 * needs TempUserConfig.
 *
 * @since 1.39
 */
interface TempUserConfig {
	/**
	 * Is temp user creation enabled?
	 *
	 * @return bool
	 */
	public function isEnabled();

	/**
	 * Are temporary accounts a known concept on the wiki?
	 * This should return true if any temporary accounts exist.
	 *
	 * @return bool
	 */
	public function isKnown();

	/**
	 * Is the action valid for user auto-creation?
	 *
	 * @param string $action
	 * @return bool
	 */
	public function isAutoCreateAction( string $action );

	/**
	 * Should/would auto-create be performed if the user attempts to perform
	 * the given action?
	 *
	 * @since 1.41
	 * @param Authority $authority
	 * @param string $action
	 * @return bool
	 */
	public function shouldAutoCreate( Authority $authority, string $action );

	/**
	 * Does the name match the configured pattern indicating that it is a
	 * temporary auto-created user?
	 *
	 * @param string $name
	 * @return bool
	 */
	public function isTempName( string $name );

	/**
	 * Does the name match a configured pattern which indicates that it
	 * conflicts with temporary user names? Should manual user creation
	 * be denied?
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function isReservedName( string $name );

	/**
	 * Get a placeholder name which matches the reserved prefix
	 *
	 * @return string
	 */
	public function getPlaceholderName(): string;

	/**
	 * Get a Pattern indicating how temporary account can be detected
	 *
	 * Used to avoid selecting a temp account via select queries.
	 *
	 * @deprecated since 1.42. Use ::getMatchPatterns as multiple patterns may be defined.
	 * @return Pattern
	 */
	public function getMatchPattern(): Pattern;

	/**
	 * Get Patterns indicating how temporary account can be detected
	 *
	 * Used to avoid selecting a temp account via select queries.
	 *
	 * @since 1.42
	 * @return Pattern[]
	 */
	public function getMatchPatterns(): array;

	/**
	 * Get a SQL query condition that will match (or not match) temporary accounts.
	 *
	 * @since 1.42
	 * @param IReadableDatabase $db
	 * @param string $field Database field to match against
	 * @param string $op Operator: IExpression::LIKE or IExpression::NOT_LIKE
	 * @return IExpression
	 */
	public function getMatchCondition( IReadableDatabase $db, string $field, string $op ): IExpression;

	/**
	 * After how many days do temporary users expire?
	 *
	 * @note expireTemporaryAccounts.php maintenance script needs to be periodically executed for
	 * temp account expiry to work.
	 * @since 1.42
	 * @return int|null Null if temp accounts should never expire
	 */
	public function getExpireAfterDays(): ?int;

	/**
	 * How many days before expiration should temporary users be notified?
	 *
	 * @note expireTemporaryAccounts.php maintenance script needs to be periodically executed for
	 * temp account expiry to work.
	 * @since 1.42
	 * @return int|null Null if temp accounts should never be notified before expiration
	 */
	public function getNotifyBeforeExpirationDays(): ?int;
}
