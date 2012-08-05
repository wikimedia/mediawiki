<?php
/**
 * Class implementing PBKDF2-HMAC password hashing
 */
class Password_TypePBKHM  extends BasePasswordType {

	protected function run( $params, $password ) {
		list( $salt, $hashFunc, $iterations, $dkLength ) = self::params( $params, 4 );
		$salt = base64_decode( $salt );
		if ( !in_array( $hashFunc, hash_algos() ) ) {
			return Status::newFatal( 'password-crypt-nohashfunc', $hashFunc );
		}
		$iterations = intval( $iterations );
		$dkLength = intval( $dkLength );

		// Compute the hash length (#bytes)
		$hashLength = strlen( hash( $hashFunc, null, true ) ); 
		// Calculate the number of blocks to generate to create the correct size of derived key
		$blocks = ceil( $dkLength / $hashLength );

		$derivedKey = '';

		for ( $block = 1; $block <= $blocks; $block++ ) {
			// The first iteration of PBKDF2 uses the salt as the hmac key
			$blockTotal = $blockStep = hash_hmac( $hashFunc, $salt . pack( 'N', $block ), $password, true );
			// Further iterations use the previous hmac's result as the key
			// The result is then XORed into the block's total
			for ( $i = 1; $i < $iterations; $i++ ) {
				$blockStep = hash_hmac( $hashFunc, $blockStep, $password, true );
				$blockTotal ^= $blockStep;
			}
			// The block total is then finally appended to the derived key
			$derivedKey .= $blockTotal;
		}

		// The derived key is then finally truncated to the desired length
		$derivedKey = substr( $derivedKey, 0, $dkLength );

		return base64_encode( $derivedKey );
	}

	protected function cryptParams() {
		global $wgPasswordPbkdf2Hmac;

		// Number of salt bits, never use less than 32 bits (the same as our old 8 hex chars)
		$saltBits = max( 32, $wgPasswordPbkdf2Hmac['saltbits'] );
		// Number of iterations to make, never use less than 1000 iterations
		$iterations = max( 1000, $wgPasswordPbkdf2Hmac['iterations'] );
		// The hash function
		$hashFunc = $wgPasswordPbkdf2Hmac['hash'];
		if ( !in_array( $hashFunc, hash_algos() ) ) {
			return Status::newFatal( 'password-crypt-nohashfunc', $hashFunc );
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

	protected function preferredFormat( $params ) {
		global $wgPasswordPbkdf2Hmac;
		list( $salt, $usedHashFunc, $usedIterations, $dkLength ) = self::params( $params, 4 );
		
		$saltBits = max( 32, $wgPasswordPbkdf2Hmac['saltbits'] );
		if ( strlen( base64_decode( $salt ) ) < ceil( $saltBits / 8 ) ) {
			// This is not preferred if there are less salt bits than configured
			return false;
		}

		// Number of iterations to make, never use less than 1000 iterations
		$iterations = max( 1000, $wgPasswordPbkdf2Hmac['iterations'] );
		if ( $usedIterations < $iterations ) {
			// This is not preferred if there are less iterations than configured
			return false;
		}

		$hashFunc = $wgPasswordPbkdf2Hmac['hash'];
		if ( in_array( $hashFunc, hash_algos() ) && $usedHashFunc !== $hashFunc ) {
			// This is not preferred if the hash function does not match configuration
			return false;
		}

		// The number of bits to output in the derived key
		$hashedBits = $wgPasswordPbkdf2Hmac['hashedbits'];
		if ( !$hashedBits ) {
			// If hashed bits is not set default to the number of bits outputted by the chosen hash function
			$hashedBits = strlen( hash( $hashFunc, null, true ) ) * 8;
		}

		if ( $dkLength < ( $hashedBits / 8 ) ) {
			// If the key length is less than 
			return false;
		}

		return true;
	}

}
