<?php
/**
 * Implements the MWOldPassword class for the MediaWiki software.
 *
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types = 1 );

namespace MediaWiki\Password;

/**
 * The old style of MediaWiki password hashing. It involves
 * running MD5 on the password.
 *
 * @since 1.24
 */
class MWOldPassword extends ParameterizedPassword {
	protected function getDefaultParams(): array {
		return [];
	}

	protected function getDelimiter(): string {
		return ':';
	}

	public function crypt( string $plaintext ): void {
		$this->args = [];
		$this->hash = md5( $plaintext );

		if ( strlen( $this->hash ) < 32 ) {
			throw new PasswordError( 'Error when hashing password.' );
		}
	}
}

/** @deprecated since 1.43 use MediaWiki\\Password\\MWOldPassword */
class_alias( MWOldPassword::class, 'MWOldPassword' );
