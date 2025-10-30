<?php
/**
 * Implements the InvalidPassword class for the MediaWiki software.
 *
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types = 1 );

namespace MediaWiki\Password;

/**
 * Represents an invalid password hash. It is represented as the empty string (i.e.,
 * a password hash with no type).
 *
 * No two invalid passwords are equal. Comparing anything to an invalid password will
 * return false.
 *
 * @since 1.24
 */
class InvalidPassword extends Password {
	public function crypt( string $plaintext ): void {
	}

	public function toString(): string {
		return '';
	}

	public function verify( string $password ): bool {
		return false;
	}

	public function needsUpdate(): bool {
		return false;
	}
}

/** @deprecated since 1.43 use MediaWiki\\Password\\InvalidPassword */
class_alias( InvalidPassword::class, 'InvalidPassword' );
