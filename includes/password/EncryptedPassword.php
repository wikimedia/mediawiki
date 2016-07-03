<?php
/**
 * Implements the EncryptedPassword class for the MediaWiki software.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Helper class for passwords that use another password hash underneath it
 * and encrypts that hash with a configured secret.
 *
 * @since 1.24
 */
class EncryptedPassword extends ParameterizedPassword {
	protected function getDelimiter() {
		return ':';
	}

	protected function getDefaultParams() {
		return [
			'cipher' => $this->config['cipher'],
			'secret' => count( $this->config['secrets'] ) - 1
		];
	}

	public function crypt( $password ) {
		$secret = $this->config['secrets'][$this->params['secret']];

		if ( $this->hash ) {
			$underlyingPassword = $this->factory->newFromCiphertext( openssl_decrypt(
					base64_decode( $this->hash ), $this->params['cipher'],
					$secret, 0, base64_decode( $this->args[0] )
				) );
		} else {
			$underlyingPassword = $this->factory->newFromType( $this->config['underlying'] );
		}

		$underlyingPassword->crypt( $password );
		$iv = MWCryptRand::generate( openssl_cipher_iv_length( $this->params['cipher'] ), true );

		$this->hash = openssl_encrypt(
			$underlyingPassword->toString(), $this->params['cipher'], $secret, 0, $iv );
		$this->args = [ base64_encode( $iv ) ];
	}

	/**
	 * Updates the underlying hash by encrypting it with the newest secret.
	 *
	 * @throws MWException If the configuration is not valid
	 * @return bool True if the password was updated
	 */
	public function update() {
		if ( count( $this->args ) != 2 || $this->params == $this->getDefaultParams() ) {
			// Hash does not need updating
			return false;
		}

		// Decrypt the underlying hash
		$underlyingHash = openssl_decrypt(
			base64_decode( $this->args[1] ),
			$this->params['cipher'],
			$this->config['secrets'][$this->params['secret']],
			0,
			base64_decode( $this->args[0] )
		);

		// Reset the params
		$this->params = $this->getDefaultParams();

		// Check the key size with the new params
		$iv = MWCryptRand::generate( openssl_cipher_iv_length( $this->params['cipher'] ), true );
		$this->hash = base64_encode( openssl_encrypt(
				$underlyingHash,
				$this->params['cipher'],
				$this->config['secrets'][$this->params['secret']],
				0,
				$iv
			) );
		$this->args = [ base64_encode( $iv ) ];

		return true;
	}
}
