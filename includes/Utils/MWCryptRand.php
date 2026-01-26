<?php
/**
 * @license GPL-2.0-or-later
 * @author Daniel Friesen
 */

namespace MediaWiki\Utils;

/**
 * A cryptographic random generator class used for generating secret keys
 *
 * This is based in part on Drupal code as well as what we used in our own code
 * prior to introduction of this class.
 */
class MWCryptRand {

	/**
	 * Generate a run of cryptographically random data and return
	 * it in hexadecimal string format.
	 *
	 * @param int $chars The number of hex chars of random data to generate
	 * @return string Hexadecimal random data
	 */
	public static function generateHex( $chars ) {
		// hex strings are 2x the length of raw binary so we divide the length in half
		// odd numbers will result in a .5 that leads the generate() being 1 character
		// short, so we use ceil() to ensure that we always have enough bytes
		$bytes = ceil( $chars / 2 );
		// Generate the data and then convert it to a hex string
		$hex = bin2hex( random_bytes( $bytes ) );

		// A bit of paranoia here, the caller asked for a specific length of string
		// here, and it's possible (eg when given an odd number) that we may actually
		// have at least 1 char more than they asked for. Just in case they made this
		// call intending to insert it into a database that does truncation we don't
		// want to give them too much and end up with their database and their live
		// code having two different values because part of what we gave them is truncated
		// hence, we strip out any run of characters longer than what we were asked for.
		return substr( $hex, 0, $chars );
	}
}

/** @deprecated class alias since 1.46 */
class_alias( MWCryptRand::class, 'MWCryptRand' );
