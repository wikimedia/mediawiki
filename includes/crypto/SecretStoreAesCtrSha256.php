<?php
/**
 * Implements a generic secret storage mechanism, using aes-ctr mode
 *  - Strong protection against an attacker who has access to encrypted
 *    objects (e.g., if an attacker gets encrypted objects from the DB
 *    or memcached, but it does not explicitely protect against attackers
 *    who can ask for arbitrary encryption and decryption)
 *  - An api that is hard for developers to use insecurly
 *  - Allows easy key rotation in production
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
 * A generic secret storage, using aes-ctr for encryption
 * @since 1.27
 */

class SecretStoreAesCtrSha256 extends SecretStore {

	/**
	 * This is one of the primary interface into this SecretStore. Given some sensitive
	 * data, generate an ascii bundle containing all of the information needed to
	 * decrypt the data in a way that is unlikely to be decrypted by someone who doesn't
	 * know $this->secrets.
	 * @param string $data the secret to encrypt
	 * @param string $type the secret type. The $nonce should be unique for each $type
	 * @param string $nonce a string of bytes, passed to the key-generation algorithm
	 *	to ensure that the encryption keys are unique for each master-secret/type/nonce
	 * 	triple.
	 * @param string $keyId optional keyId
	 * @return string encrypted data, base64 encoded
	 */
	public function seal( $data, $type, $nonce, $keyId = null ) {
		if ( $keyId === null ) {
			$keyId = $this->currentSecretId;
		}
		if ( !isset( $this->secrets[$keyId] ) ) {
			throw new InvalidArgumentException( 'Invalid keyid to seal' );
		}

		return $this->sealForKeyId( $data, $type, $nonce, $keyId );
	}

	/**
	 * Encrypt data for a specific keyId.
	 * @return strong that includes the keyid, random IV used, and b64 encrypted data
	 */
	protected function sealForKeyId( $data, $type, $nonce, $keyId ) {
		$secret = $this->secrets[$keyId];
		list( $hmacKey, $encKey ) = $this->deriveKeys( $secret, $type, $nonce );

		$iv = $this->getIV( 16, $secret );

		$ciphertext = openssl_encrypt(
			$data,
			'aes-256-ctr',
			$encKey,
			1,
			$iv
		);

		$integ = hash_hmac( 'sha256', $ciphertext, $hmacKey, true );
		$encrypted = base64_encode( $ciphertext . $integ );
		$ivb64 = base64_encode( $iv );
		return "acs.$keyId.$ivb64.$encrypted";
	}

	/**
	 * This is one of the primary interface into this SecretStore. Given a bundle of data
	 * sealed using seal(), decrypt the sensitive information and return it.
	 *
	 * @param string $encrypted the bundle to decrypt
	 * @param string $type the secret type. The $nonce should be unique for each $type
	 * @param string $nonce a string of bytes, passed to the key-generation algorithm
	 *	to ensure that the encryption keys are unique for each master-secret/type/nonce
	 * 	triple.
	 * @return string plaintext data
	 */
	public function doUnseal( $encrypted, $type, $nonce ) {
		$pieces = explode( '.', $encrypted );
		if ( count( $pieces ) !== 3 ) {
			throw new InvalidArgumentException( 'Invalid sealed-secret format' );
		}

		list( $keyid, $ivb64, $encrypted ) = $pieces;

		if ( !isset( $this->secrets[$keyid] ) ) {
			throw new InvalidArgumentException( 'Invalid keyid to unseal' );
		}

		$secret = $this->secrets[$keyid];
		list( $hmacKey, $encKey ) = $this->deriveKeys( $secret, $type, $nonce );

		// check hmac
		$raw = base64_decode( $encrypted );
		$integ = substr( $raw, -32 );
		$ciphertext = substr( $raw, 0, -32 );
		$integCalc = hash_hmac( 'sha256', $ciphertext, $hmacKey, true );

		if ( !hash_equals( $integCalc, $integ ) ) {
			throw new Exception( 'Sealed secret has been tampered with, aborting.' );
		}

		// decrypt
		$plaintext = openssl_decrypt(
			$ciphertext,
			'aes-256-ctr',
			$encKey,
			1,
			base64_decode( $ivb64 )
		);

		return $plaintext;
	}


}
