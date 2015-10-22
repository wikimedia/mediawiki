<?php
/**
 * Implements a generic secret storage mechanism with,
 *  - Strong protection against an attacker who has access to encrypted
 *    objects (e.g., if an attacker gets encrypted objects from the DB
 *    or memcached, but it does not explicitely protect against attackers
 *    who can ask for arbitrary encryption and decryption)
 *  - An api that is hard for developers to use insecurly
 *  - Allows easy key rotation in production (add a new secret to
 *	$wgSecretStoreConfig['secrets'] and increment 'defaultSecret').
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
 * A generic secret storage
 * @since 1.27
 */

abstract class SecretStore {

	protected $secrets;

	protected $currentSecretId;

	private static $classes = array(
		'acs256' => 'SecretStoreAesCtrSha256',
	);

	public function __construct( $secrets, $currentSecretId ) {
		$this->secrets = $secrets;
		$this->currentSecretId = $currentSecretId;

		if ( !isset( $this->secrets[$currentSecretId] ) ) {
			throw new InvalidArgumentException( 'Invalid current secret/id' );
		}
	}

	/**
	 * Return the appropriate SecretStore object, as configured by $wgSecretStoreConfig
	 * @return SecretStore
	 */
	public static function getDefaultInstance() {
		static $secretStore = null;
		if ( $secretStore === null ) {
			$ssConfig = RequestContext::getMain()->getConfig()->get( 'SecretStoreConfig' );
			$secretStore = self::getInstance( $ssConfig );
		}
		return $secretStore;
	}

	/**
	 * Return a new instance of the SecretStore class specfied in the config.
	 * Most MediaWiki code should use getDefaultInstance to use the site config. Use
	 * this for testing, or to use a non-standard config.
	 * @param array $ssConfig configuration array
	 * @see $wgSecretStoreConfig
	 * @return SecretStore
	 */
	public static function getInstance( $ssConfig ) {
		$ssClass = $ssConfig['classes'][$ssConfig['defaultClass']];
		return new $ssClass( $ssConfig['secrets'], $ssConfig['defaultSecret'] );
	}

	/**
	 * This is one of the primary interface into the SecretStore. Given some sensitive
	 * data, generate an ascii bundle containing all of the information needed to
	 * decrypt the data in a way that is unlikely to be decrypted by someone who doesn't
	 * know $this->secrets.
	 * @param string $data the secret to encrypt
	 * @param string $type the secret type. The $nonce should be unique for each $type
	 * @param string $nonce a string of bytes. The encryption/hmac keys will be unique
	 * 	for each secret/type/nonce triple, which can harden against some attacks (attacker
	 * 	will have to regenerate each unique key if they manage to steal a large number of
	 * 	sealed objects).
	 * @param string $keyId optional keyId
	 * @return string encrypted data, base64 encoded
	 */
	abstract public function seal( $data, $type, $nonce );

	/**
	 * This is one of the primary interface into the SecretStore. Given a bundle of data
	 * sealed using seal(), decrypt the sensitive information and return it.
	 * @param string $encrypted the bundle to decrypt
	 * @param string $type the secret type. The $nonce should be unique for each $type
	 * @param string $nonce a string of bytes, passed to the key-generation algorithm
	 *	to ensure that the encryption keys are unique for each master-secret/type/nonce
	 * 	triple.
	 * @return string plaintext data
	 */
	public function unseal( $encrypted, $type, $nonce ) {
		$pieces = explode( '.', $encrypted, 2 );
		if ( !$pieces[0] ) {
			throw new InvalidArgumentException( 'Invalid sealed-secret format' );
		}
		return $this->doUnseal( 
	}


	/**
	 * Most encryption schemes will want to generate and store a truely random IV. This
	 * prevents attacks like two versions of the same type of data, when encrypted
	 * with a stream cipher, from having the property C1 xor C2 = P1 xor P2, and
	 * an attacker solving for a known plaintext.
	 * @param int $length size in bytes of the returned IV
	 * @param string $secret site-secret key, to hopefully prevent weaknesses in prng
	 */
	protected function getIV( $length, $secret ) {
		return Pbkdf2::hash(
			'sha256',
			$secret,
			MWCryptRand::generate( $length, true ),
			10,
			$length
		);
	}

	/**
	 * @return array( $hmacKey, $encryptionKey )
	 */
	protected function deriveKeys( $secret, $type, $nonce ) {
		$keymats = Pbkdf2::hash( 'sha256', $secret, "$type-$nonce", 101, 64 );
		$hmacKey = substr( $keymats, 0, 32 );
		$encKey = substr( $keymats, 32, 32 );
		return array( $hmacKey, $encKey );
	}

	/**
	 * Re-encrypt a bundle for a different $keyId
	 * @param $keyId the new KeyId to encrypt to
	 * @param $ciphertext the sealed bundle, sealed with the old key
	 * @param $type the type of secret (to both decrypt and reencrypt)
	 * @param $nonce the unique nonce for key generation (to both decrypt and reencrypt)
	 */
	public function updateForKey( $keyId, $ciphertext, $type, $nonce ) {
		$plain = $this->unseal( $ciphertext, $type, $nonce );
		return $this->seal( $plain, $type, $nonce, $keyId );
	}

}
