<?php
/**
 * Implements the PepperedPassword class for the MediaWiki software.
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
  * @since 1.22
  */
class PepperedPassword extends ParameterizedPassword {
	function getDelimiter() {
		return ':';
	}

	function getDefaultParams() {
		return array(
			'cipher' => $this->config['cipher'],
			'mode' => $this->config['mode'],
			'secret' => count( $this->config['secrets'] ) - 1
		);
	}

	function crypt( $password ) {
		$crypt = mcrypt_module_open( $this->params['cipher'], '', $this->params['mode'], '' );

		// Check the key size
		$keySize = mcrypt_enc_get_key_size( $crypt );
		$secret = $this->config['secrets'][$this->params['secret']];
		if ( $secret > $keySize ) {
			throw new MWException( 'Configured secret value larger than cipher supports.' );
		}

		if ( $this->hash !== null ) {
			// Existing hash, decrypt the hash
			$iv = base64_decode( $this->args[0] );

			mcrypt_generic_init( $crypt, $secret, $iv );
			$underlyingHash = mdecrypt_generic( $crypt, base64_decode( $this->hash ) );
			mcrypt_generic_deinit( $crypt );

			$underlyingPassword = self::newFromCiphertext( $underlyingHash );
		} else {
			// New hash, make a new IV and default hash
			$crypt = mcrypt_module_open( $this->params['cipher'], '', $this->params['mode'], '' );
			$iv = MWCryptRand::generate( mcrypt_enc_get_iv_size( $crypt ), true );
			$underlyingPassword = Password::newFromType( $this->config['underlying'], $this->config );

			$this->args = array( base64_encode( $iv ) );
		}

		// Crypt the password with the underlying hash
		$underlyingPassword->crypt( $password );

		mcrypt_generic_init( $crypt, $secret, $iv );
		$this->hash = base64_encode( mcrypt_generic( $crypt, $underlyingPassword->toString() ) );
		mcrypt_generic_deinit( $crypt );
		mcrypt_module_close( $crypt );
	}

	/**
	 * Updates the underlying hash by encrypting it with the newest secret.
	 *
	 * @return bool True if the password was updated
	 */
	public function update() {
		if ( count( $this->args ) != 2 || $this->params == $this->getDefaultParams() ) {
			// Hash does not need updating
			return false;
		}

		$crypt = mcrypt_module_open( $this->params['cipher'], '', $this->params['mode'], '' );

		// Check the key size
		$keySize = mcrypt_enc_get_key_size( $crypt );
		$secret = $this->config['secrets'][$this->params['secret']];
		if ( $secret > $keySize ) {
			throw new MWException( 'Configured secret value larger than cipher supports.' );
		}

		// Decrypt the underlying hash
		$iv = base64_decode( $this->args[0] );
		mcrypt_generic_init( $crypt, $secret, $iv );
		$underlyingHash = mdecrypt_generic( $crypt, base64_decode( $this->args[1] ) );
		mcrypt_generic_deinit( $crypt );

		// Reset the params
		$oldCipher = $this->params['cipher'];
		$this->params = $this->getDefaultParams();
		// Check if the cipher changed
		if ( $oldCipher !== $this->params['cipher'] ) {
			mcrypt_module_close( $crypt );
			$crypt = mcrypt_module_open( $this->params['cipher'], '', $this->params['mode'], '' );
		}

		// Check the key size with the new params
		$keySize = mcrypt_enc_get_key_size( $crypt );
		$secret = $this->config['secrets'][$this->params['secret']];
		if ( $secret > $keySize ) {
			throw new MWException( 'Configured secret value larger than cipher supports.' );
		}

		// Re-encrypt it
		$iv = MWCryptRand::generate( mcrypt_enc_get_iv_size( $crypt ), true );

		mcrypt_generic_init( $crypt, $secret, $iv );
		$this->args = array( base64_encode( $iv ) );
		$this->hash = base64_encode( mcrypt_generic( $crypt, $underlyingHash ) );
		mcrypt_generic_deinit( $crypt );
		mcrypt_module_close( $crypt );

		return true;
	}
}
