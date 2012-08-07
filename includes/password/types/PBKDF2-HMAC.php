<?php
/**
 * PBKDF2-HMAC password key derivation.
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
 * @author Daniel Friesen <mediawiki@danielfriesen.name>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @see https://tools.ietf.org/html/rfc2898#section-5.2 PKCS #5's PBKDF2 section
 * @ingroup Password
 */

/**
 * An implementation of PBKDF2-HMAC key derivation as a MW password storage type.
 * Stored as the :PBKHM: type.
 * @ingroup Password
 * @since 1.20
 */
class Password_TypePBKHM extends BasePasswordType {

	/**
	 * A PHP implementation of PBKDF2-HMAC defined by rfc2898.
	 *
	 * @param string $algo Name of the hash algorithm to use
	 * @param string $password The password to create a derived key for
	 * @param string $salt The raw binary data of the salt
	 * @param int $iterations The number of internal key derivation iterations to perform
	 * @param int $dkLength The length of the final dkey to generate
	 * @return string A raw binary derived key
	 */
	public function pbkdf2_hmac( $algo, $password, $salt, $iterations, $dkLength ) {
		// Compute the hash length (#bytes)
		$hashLength = strlen( hash( $algo, null, true ) ); 
		// Calculate the number of blocks to generate to create the correct size of derived key
		$blocks = ceil( $dkLength / $hashLength );

		$derivedKey = '';

		for ( $block = 1; $block <= $blocks; $block++ ) {
			// The first iteration of PBKDF2 uses the salt as the hmac key
			$blockTotal = $blockStep = hash_hmac( $algo, $salt . pack( 'N', $block ), $password, true );
			// Further iterations use the previous hmac's result as the key
			// The result is then XORed into the block's total
			for ( $i = 1; $i < $iterations; $i++ ) {
				$blockStep = hash_hmac( $algo, $blockStep, $password, true );
				$blockTotal ^= $blockStep;
			}
			// The block total is then finally appended to the derived key
			$derivedKey .= $blockTotal;
		}

		// The derived key is then finally truncated to the desired length
		$derivedKey = substr( $derivedKey, 0, $dkLength );
		return $derivedKey;
	}

	public function run( $params, $password ) {
		list( $salt, $hashFunc, $iterations, $dkLength ) = self::params( $params, 4 );
		$salt = base64_decode( $salt );
		if ( !in_array( $hashFunc, hash_algos() ) ) {
			throw self::error( 'password-crypt-nohashfunc', $hashFunc );
		}
		$iterations = intval( $iterations );
		$dkLength = intval( $dkLength );

		// @todo When PHP 5.5 is released conditionally make use of hash_pbkdf2 after re-testing it
		$derivedKey = self::pbkdf2_hmac( $hashFunc, $password, $salt, $iterations, $dkLength );

		return base64_encode( $derivedKey );
	}

	public function cryptParams() {
		global $wgPasswordPbkdf2Hmac;

		// Number of salt bits, never use less than 32 bits (the same as our old 8 hex chars)
		$saltBits = max( 32, $wgPasswordPbkdf2Hmac['saltbits'] );
		// Number of iterations to make, never use less than 1000 iterations
		$iterations = max( 1000, $wgPasswordPbkdf2Hmac['iterations'] );
		// The hash function
		$hashFunc = $wgPasswordPbkdf2Hmac['hash'];
		if ( !in_array( $hashFunc, hash_algos() ) ) {
			throw self::error( 'password-crypt-nohashfunc', $hashFunc );
		}
		// The number of bits to output in the derived key
		$hashedBits = $wgPasswordPbkdf2Hmac['hashedbits'];
		if ( !$hashedBits ) {
			// If hashed bits is not set default to the number of bits outputted by the chosen hash function
			$hashedBits = strlen( hash( $hashFunc, null, true ) ) * 8;
		}

		return array(
			/* salt */       base64_encode( MWCryptRand::generate( ceil( $saltBits / 8 ) ) ),
			/* hash */       $hashFunc,
			/* iterations */ $iterations,
			/* dkLength */   $hashedBits / 8,
		);
	}

	public function paramsNeedUpdate( $params ) {
		global $wgPasswordPbkdf2Hmac;
		list( $salt, $usedHashFunc, $usedIterations, $dkLength ) = self::params( $params, 4 );
		
		$saltBits = max( 32, $wgPasswordPbkdf2Hmac['saltbits'] );
		if ( strlen( base64_decode( $salt ) ) < ceil( $saltBits / 8 ) ) {
			// This needs update if there are less salt bits than configured
			return true;
		}

		// Number of iterations to make, never use less than 1000 iterations
		$iterations = max( 1000, $wgPasswordPbkdf2Hmac['iterations'] );
		if ( $usedIterations < $iterations ) {
			// This needs update if there are less iterations than configured
			return true;
		}

		$hashFunc = $wgPasswordPbkdf2Hmac['hash'];
		if ( in_array( $hashFunc, hash_algos() ) && $usedHashFunc !== $hashFunc ) {
			// This needs update if the hash function does not match configuration
			return true;
		}

		// The number of bits to output in the derived key
		$hashedBits = $wgPasswordPbkdf2Hmac['hashedbits'];
		if ( !$hashedBits ) {
			// If hashed bits is not set default to the number of bits outputted by the chosen hash function
			$hashedBits = strlen( hash( $hashFunc, null, true ) ) * 8;
		}

		if ( $dkLength < ( $hashedBits / 8 ) ) {
			// If the key length is less than the preferred key lengh we need an update
			return true;
		}

		return false;
	}

	public function knownPasswordData() {
		return array(
			## Set 1 (sha256)
			array( 'yCaxCRg0J3I=:sha256:10000:32:IdNMgnu31vxOYZcZw+cuxHEV32AN0E1MUxlHUhI20iw=', 'asdf' ),
			array( '0365j6hCBrU=:sha256:10000:32:Ob/NnzUsA2H4GoztuLQuIknNnas0sUg/sHU2oUjfOzM=', 'test' ),
			array( '8RGGumgq6B4=:sha256:10000:32:/Ud4zVh9Ipn7GRHj9BKkHK2Xvaz1zrRvjEa07FfHAuw=', 'Hello World' ),
			array( 'XAZQ0Fx45JQ=:sha256:10000:32:SUKiNMCzhZ6BrRBdCXSaBY2w18twvPaKH3Eap98XbEo=', 'Passw0rd' ),
			array( 'hhgIP1sNKs8=:sha256:10000:32:rGVfA+2FLC3UeVEKt1GckpGpknGf9Nx87aietmlonHA=', 'D0g.....................' ),
			array( 'EbZ57a06GGw=:sha256:10000:32:WK+BzCnrW6x6PNSvjYK3km1udl14wY33YsV2lt42Gek=', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
			## Set 2 (whirlpool)
			array( '4faSJAfABg8=:whirlpool:10000:64:ZC/qLHX0D24wPuznFwb+I4UBkE18JyZ9K1bGOo9J+IUtsuVHDvGhJXMSwvXuxFySuBLofzkjk0ITgcmGYp20yw==', 'asdf' ),
			array( 'QSwzOfVFWmY=:whirlpool:10000:64:Pxkr8D5dVFf/qD0LCmz3angHOeFOBBX6y0UBMAoUdr8IqE2OMe7yXD4rDO2rbdLas152HvUgIwl9LyagTuY89Q==', 'test' ),
			array( 'IgzraxBdmdM=:whirlpool:10000:64:uPwBse+hg2EFQ0s5wxCymC4O3KUfetSI+PRf6362pc8Cl7TlwKoh4KZAl2HukRiqBRXnhUUyl/NGjpveQJC3+w==', 'Hello World' ),
			array( 'rYNd9nHdKVs=:whirlpool:10000:64:V56yyy4XwyRISVhPcGeWpNpYFL4j/5wPEAWxUHDKe3grsE/HzzxVRKdTcO8ny2gttvQ9ofpcS4Ru2oUS7m2ALw==', 'Passw0rd' ),
			array( '2N0JKZRnNjQ=:whirlpool:10000:64:Og83KC5PP0Dxl5XnJ8RIywVF96Wm+GZrAtniE9eDj/znYqJ/JqqK+YDEqLrjaE8nOuozWeBwRd8Gx9KgHCiMzA==', 'D0g.....................' ),
			array( 'PYMVMwXUNn8=:whirlpool:10000:64:htNdMzShGUzqghNGh5K4ihP0K1XbCzDd6qs7X+HfEKUXLAEs06MmqMwWUSmi7IrgJ8kfF6kUjB+MBZPnSXVnZg==', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
			## Set 3 (50000 sha1)
			array( 'zzet1WmGu+M=:sha1:50000:20:l+kWWTrjOjM6J1EObmDjzgyN1lE=', 'asdf' ),
			array( '9l/3BOJW4II=:sha1:50000:20:P00yS3YRcPUCn2yyI9/jlTaC9JA=', 'test' ),
			array( 'kXQjQwZsMY0=:sha1:50000:20:tPFfeEVbdAF9Bq24rD8CBiTaaf0=', 'Hello World' ),
			array( 'XCvqIz5QFjc=:sha1:50000:20:siG9gkUUNka7P9uvBo8dA7ZCZlE=', 'Passw0rd' ),
			array( '9buA3LvsLog=:sha1:50000:20:mAgZxNlL+4dWC8JCsJlq/5Tjn50=', 'D0g.....................' ),
			array( 'FnhgbiEVecw=:sha1:50000:20:0OU+1R0AM7v/0mlULaz366rTgz4=', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
		);
	}

}
