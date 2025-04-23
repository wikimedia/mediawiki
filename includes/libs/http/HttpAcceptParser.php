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
	 * Parse media types from an Accept header and sort them by q-factor.
	 *
	 * Note that this was mostly ported from
	 * https://github.com/arlolra/negotiator/blob/full-parse-access/lib/mediaType.js
	 *
	 * @param string $accept
	 * @return array[]
	 *  - type: (string)
	 *  - subtype: (string)
	 *  - q: (float) q-factor weighting
	 *  - i: (int) index
	 *  - params: (array)
	 */
	public function parseAccept( $accept ): array {
		// FIXME: Allow commas in quotes
		$accepts = explode( ',', $accept );
		$ret = [];

		foreach ( $accepts as $i => $a ) {
			if ( !preg_match( '!^([^\s/;]+)/([^;\s]+)\s*(?:;(.*))?$!D', trim( $a ), $matches ) ) {
				continue;
			}

			$q = 1;
			$params = [];
			if ( isset( $matches[3] ) ) {
				// FIXME: Allow semi-colon in quotes
				$kvps = explode( ';', $matches[3] );
				foreach ( $kvps as $kv ) {
					$kvArray = explode( '=', trim( $kv ), 2 );
					if ( count( $kvArray ) != 2 ) {
						continue;
					}
					[ $key, $val ] = $kvArray;
					$key = strtolower( trim( $key ) );
					$val = trim( $val );
					if ( $key === 'q' ) {
						// FIXME: Spec is stricter about this
						$q = (float)$val;
					} else {
						if ( $val && $val[0] === '"' && $val[ strlen( $val ) - 1 ] === '"' ) {
							$val = substr( $val, 1, strlen( $val ) - 2 );
						}
						$params[$key] = $val;
					}
				}
			}
			$ret[] = [
				'type' => $matches[1],
				'subtype' => $matches[2],
				'q' => $q,
				'i' => $i,
				'params' => $params,
			];
		}

		// Sort list. First by q values, then by order
		usort( $ret, static function ( $a, $b ) {
			if ( $b['q'] > $a['q'] ) {
				return 1;
			} elseif ( $b['q'] === $a['q'] ) {
				return $a['i'] - $b['i'];
			} else {
				return -1;
			}
		} );

		return $ret;
	}

	/**
	 * Parses an HTTP header into a weight map, that is an associative array
	 * mapping values to their respective weights. Any header name preceding
	 * weight spec is ignored for convenience.
	 *
	 * Note that type parameters and accept extension like the "level" parameter
	 * are not supported, weights are derived from "q" values only.
	 *
	 * See RFC 7231 section 5.3.2 for details.
	 *
	 * @param string $rawHeader
	 *
	 * @return array
	 */
	public function parseWeights( $rawHeader ) {
		// first, strip header name
		$rawHeader = preg_replace( '/^[-\w]+:\s*/', '', $rawHeader );

		// Return values in lower case
		$rawHeader = strtolower( $rawHeader );

		$accepts = $this->parseAccept( $rawHeader );

		// Create a list like "en" => 0.8
		return array_reduce( $accepts, static function ( $prev, $next ) {
			$type = "{$next['type']}/{$next['subtype']}";
			$prev[$type] = $next['q'];
			return $prev;
		}, [] );
	}

}
