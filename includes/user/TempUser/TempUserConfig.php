<?php

namespace MediaWiki\User\TempUser;

use MediaWiki\Permissions\Authority;

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
	 * @return Pattern
	 */
	public function getMatchPattern(): Pattern;
}
