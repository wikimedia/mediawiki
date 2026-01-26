<?php
/**
 * Implements the MWSaltedPassword class for the MediaWiki software.
 *
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types = 1 );

namespace MediaWiki\Password;

use MediaWiki\Utils\MWCryptRand;

/**
 * The old style of MediaWiki password hashing, with a salt. It involves
 * running MD5 on the password, and then running MD5 on the salt concatenated
 * with the first hash.
 *
 * @since 1.24
 */
class MWSaltedPassword extends ParameterizedPassword {
	protected function getDefaultParams(): array {
		return [];
	}

	protected function getDelimiter(): string {
		return ':';
	}

	public function crypt( string $plaintext ): void {
		if ( count( $this->args ) == 0 ) {
			$this->args[] = MWCryptRand::generateHex( 8 );
		}

		$this->hash = md5( $this->args[0] . '-' . md5( $plaintext ) );

		if ( strlen( $this->hash ) < 32 ) {
			throw new PasswordError( 'Error when hashing password.' );
		}
	}
}

/** @deprecated since 1.43 use MediaWiki\\Password\\MWSaltedPassword */
class_alias( MWSaltedPassword::class, 'MWSaltedPassword' );
