<?php

namespace MediaWiki\User\TempUser;

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
	 * Does the name match the configured pattern indicating that it is a
	 * temporary auto-created user?
	 *
	 * @param string $name
	 * @return bool
	 */
	public function isReservedName( string $name );

	/**
	 * Get a placeholder name which matches the reserved prefix
	 *
	 * @return string
	 */
	public function getPlaceholderName(): string;
}
