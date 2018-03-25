<?php

namespace Wikimedia\Http;

/**
 * Utility for negotiating a value from a set of supported values using a preference list.
 * This is intended for use with HTTP headers like Accept, Accept-Language, Accept-Encoding, etc.
 * See RFC 2616 section 14 for details.
 *
 * To use this with a request header, first parse the header value into an array of weights
 * using HttpAcceptParser, then call getBestSupportedKey.
 *
 * @license GPL-2.0-or-later
 * @author Daniel Kinzler
 * @author Thiemo Kreuz
 */
class HttpAcceptNegotiator {

	/**
	 * @var string[]
	 */
	private $supportedValues;

	/**
	 * @var string
	 */
	private $defaultValue;

	/**
	 * @param string[] $supported A list of supported values.
	 */
	public function __construct( array $supported ) {
		$this->supportedValues = $supported;
		$this->defaultValue = reset( $supported );
	}

	/**
	 * Returns the best supported key from the given weight map. Of the keys from the
	 * $weights parameter that are also in the list of supported values supplied to
	 * the constructor, this returns the key that has the highest weight associated
	 * with it. If two keys have the same weight, the more specific key is preferred,
	 * as required by RFC2616 section 14. Keys that map to 0 or false are ignored.
	 * If no matching key is found, $default is returned.
	 *
	 * @param float[] $weights An associative array mapping accepted values to their
	 *              respective weights.
	 *
	 * @param null|string $default The value to return if non of the keys in $weights
	 *              is supported (null per default).
	 *
	 * @return null|string The best supported key from the $weights parameter.
	 */
	public function getBestSupportedKey( array $weights, $default = null ) {
		// Make sure we correctly bias against wildcards and ranges, see RFC2616, section 14.
		foreach ( $weights as $name => &$weight ) {
			if ( $name === '*' || $name === '*/*' ) {
				$weight -= 0.000002;
			} elseif ( substr( $name, -2 ) === '/*' ) {
				$weight -= 0.000001;
			}
		}

		// Sort $weights by value and...
		asort( $weights );

		// remove any keys with values equal to 0 or false (HTTP/1.1 section 3.9)
		$weights = array_filter( $weights );

		// ...use the ordered list of keys
		$preferences = array_reverse( array_keys( $weights ) );

		$value = $this->getFirstSupportedValue( $preferences, $default );
		return $value;
	}

	/**
	 * Returns the first supported value from the given preference list. Of the values from
	 * the $preferences parameter that are also in the list of supported values supplied
	 * to the constructor, this returns the value that has the lowest index in the list.
	 * If no such value is found, $default is returned.
	 *
	 * @param string[] $preferences A list of acceptable values, in order of preference.
	 *
	 * @param null|string $default The value to return if non of the keys in $weights
	 *              is supported (null per default).
	 *
	 * @return null|string The best supported key from the $weights parameter.
	 */
	public function getFirstSupportedValue( array $preferences, $default = null ) {
		foreach ( $preferences as $value ) {
			foreach ( $this->supportedValues as $supported ) {
				if ( $this->valueMatches( $value, $supported ) ) {
					return $supported;
				}
			}
		}

		return $default;
	}

	/**
	 * Returns true if the given acceptable value matches the given supported value,
	 * according to the HTTP specification. The following rules are used:
	 *
	 * - comparison is case-insensitive
	 * - if $accepted and $supported are equal, they match
	 * - if $accepted is `*` or `*` followed by `/*`, it matches any $supported value.
	 * - if both $accepted and $supported contain a `/`, and $accepted ends with `/*`,
	 *   they match if the part before the first `/` is equal.
	 *
	 * @param string $accepted An accepted value (may contain wildcards)
	 * @param string  $supported A supported value.
	 *
	 * @return bool Whether the given supported value matches the given accepted value.
	 */
	private function valueMatches( $accepted, $supported ) {
		// RDF 2045: MIME types are case insensitive.
		// full match
		if ( strcasecmp( $accepted, $supported ) === 0 ) {
			return true;
		}

		// wildcard match (HTTP/1.1 section 14.1, 14.2, 14.3)
		if ( $accepted === '*' || $accepted === '*/*' ) {
			return true;
		}

		// wildcard match (HTTP/1.1 section 14.1)
		if ( substr( $accepted, -2 ) === '/*'
			&& strncasecmp( $accepted, $supported, strlen( $accepted ) - 2 ) === 0
		) {
			return true;
		}

		return false;
	}

}
