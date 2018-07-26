<?php

/**
 * Utility for parsing a HTTP Accept header value into a weight map. May also be used with
 * other, similar headers like Accept-Language, Accept-Encoding, etc.
 *
 * @license GPL-2.0-or-later
 * @author Daniel Kinzler
 */

namespace Wikimedia\Http;

class HttpAcceptParser {

	/**
	 * Parses an HTTP header into a weight map, that is an associative array
	 * mapping values to their respective weights. Any header name preceding
	 * weight spec is ignored for convenience.
	 *
	 * This implementation is partially based on the code at
	 * http://www.thefutureoftheweb.com/blog/use-accept-language-header
	 *
	 * Note that type parameters and accept extension like the "level" parameter
	 * are not supported, weights are derived from "q" values only.
	 *
	 * @todo If additional type parameters are present, ignore them cleanly.
	 *        At present, they often confuse the result.
	 *
	 * See HTTP/1.1 section 14 for details.
	 *
	 * @param string $rawHeader
	 *
	 * @return array
	 */
	public function parseWeights( $rawHeader ) {
		//FIXME: The code below was copied and adapted from WebRequest::getAcceptLang.
		//       Move this utility class into core for reuse!

		// first, strip header name
		$rawHeader = preg_replace( '/^[-\w]+:\s*/', '', $rawHeader );

		// Return values in lower case
		$rawHeader = strtolower( $rawHeader );

		// Break up string into pieces (values and q factors)
		$value_parse = null;
		preg_match_all( '@([a-z\d*]+([-+/.][a-z\d*]+)*)\s*(;\s*q\s*=\s*(1(\.0{0,3})?|0(\.\d{0,3})?)?)?@',
			$rawHeader, $value_parse );

		if ( !count( $value_parse[1] ) ) {
			return [];
		}

		$values = $value_parse[1];
		$qvalues = $value_parse[4];
		$indices = range( 0, count( $value_parse[1] ) - 1 );

		// Set default q factor to 1
		foreach ( $indices as $index ) {
			if ( $qvalues[$index] === '' ) {
				$qvalues[$index] = 1;
			} elseif ( $qvalues[$index] == 0 ) {
				unset( $values[$index], $qvalues[$index], $indices[$index] );
			} else {
				$qvalues[$index] = (float)$qvalues[$index];
			}
		}

		// Sort list. First by $qvalues, then by order. Reorder $values the same way
		array_multisort( $qvalues, SORT_DESC, SORT_NUMERIC, $indices, $values );

		// Create a list like "en" => 0.8
		$weights = array_combine( $values, $qvalues );

		return $weights;
	}

}
