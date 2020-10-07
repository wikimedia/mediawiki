<?php
/**
 * Functions to help implement an external link filter for spam control.
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

use Wikimedia\IPUtils;
use Wikimedia\Rdbms\LikeMatch;

/**
 * Some functions to help implement an external link filter for spam control.
 *
 * @todo implement the filter. Currently these are just some functions to help
 * maintenance/cleanupSpam.php remove links to a single specified domain. The
 * next thing is to implement functions for checking a given page against a big
 * list of domains.
 *
 * Another cool thing to do would be a web interface for fast spam removal.
 */
class LinkFilter {
	/**
	 * Increment this when makeIndexes output changes. It'll cause
	 * maintenance/refreshExternallinksIndex.php to run from update.php.
	 */
	public const VERSION = 1;

	/**
	 * Check whether $content contains a link to $filterEntry
	 *
	 * @param Content $content Content to check
	 * @param string $filterEntry Domainparts, see makeRegex() for more details
	 * @param string $protocol 'http://' or 'https://'
	 * @return int 0 if no match or 1 if there's at least one match
	 */
	public static function matchEntry( Content $content, $filterEntry, $protocol = 'http://' ) {
		if ( !( $content instanceof TextContent ) ) {
			// TODO: handle other types of content too.
			//      Maybe create ContentHandler::matchFilter( LinkFilter ).
			//      Think about a common base class for LinkFilter and MagicWord.
			return 0;
		}

		$text = $content->getText();

		$regex = self::makeRegex( $filterEntry, $protocol );
		return preg_match( $regex, $text );
	}

	/**
	 * Builds a regex pattern for $filterEntry.
	 *
	 * @todo This doesn't match the rest of the functionality here.
	 * @param string $filterEntry URL, if it begins with "*.", it'll be
	 *        replaced to match any subdomain
	 * @param string $protocol 'http://' or 'https://'
	 *
	 * @return string Regex pattern, for preg_match()
	 */
	private static function makeRegex( $filterEntry, $protocol ) {
		$regex = '!' . preg_quote( $protocol, '!' );
		if ( substr( $filterEntry, 0, 2 ) == '*.' ) {
			$regex .= '(?:[A-Za-z0-9.-]+\.|)';
			$filterEntry = substr( $filterEntry, 2 );
		}
		$regex .= preg_quote( $filterEntry, '!' ) . '!Si';
		return $regex;
	}

	/**
	 * Indicate whether LinkFilter IDN support is available
	 * @since 1.33
	 * @return bool
	 */
	public static function supportsIDN() {
		return is_callable( 'idn_to_utf8' ) && defined( 'INTL_IDNA_VARIANT_UTS46' );
	}

	/**
	 * Canonicalize a hostname for el_index
	 * @param string $host
	 * @return string
	 */
	private static function indexifyHost( $host ) {
		// NOTE: If you change the output of this method, you'll probably have to increment self::VERSION!

		// Canonicalize.
		$host = rawurldecode( $host );
		if ( $host !== '' && self::supportsIDN() ) {
			// @todo Add a PHP fallback
			$tmp = idn_to_utf8( $host, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46 );
			if ( $tmp !== false ) {
				$host = $tmp;
			}
		}
		$okChars = 'a-zA-Z0-9\\-._~!$&\'()*+,;=';
		if ( StringUtils::isUtf8( $host ) ) {
			// Save a little space by not percent-encoding valid UTF-8 bytes
			$okChars .= '\x80-\xf4';
		}
		$host = preg_replace_callback(
			'<[^' . $okChars . ']>',
			function ( $m ) {
				return rawurlencode( $m[0] );
			},
			strtolower( $host )
		);

		// IPv6? RFC 3986 syntax.
		if ( preg_match( '/^\[([0-9a-f:*]+)\]$/', rawurldecode( $host ), $m ) ) {
			$ip = $m[1];
			if ( IPUtils::isValid( $ip ) ) {
				return 'V6.' . implode( '.', explode( ':', IPUtils::sanitizeIP( $ip ) ) ) . '.';
			}
			if ( substr( $ip, -2 ) === ':*' ) {
				$cutIp = substr( $ip, 0, -2 );
				if ( IPUtils::isValid( "{$cutIp}::" ) ) {
					// Wildcard IP doesn't contain "::", so multiple parts can be wild
					$ct = count( explode( ':', $ip ) ) - 1;
					return 'V6.' .
						implode( '.', array_slice( explode( ':', IPUtils::sanitizeIP( "{$cutIp}::" ) ), 0, $ct ) ) .
						'.*.';
				}
				if ( IPUtils::isValid( "{$cutIp}:1" ) ) {
					// Wildcard IP does contain "::", so only the last part is wild
					return 'V6.' .
						substr( implode( '.', explode( ':', IPUtils::sanitizeIP( "{$cutIp}:1" ) ) ), 0, -1 ) .
						'*.';
				}
			}
		}

		// Regularlize explicit specification of the DNS root.
		// Browsers seem to do this for IPv4 literals too.
		if ( substr( $host, -1 ) === '.' ) {
			$host = substr( $host, 0, -1 );
		}

		// IPv4?
		$b = '(?:0*25[0-5]|0*2[0-4][0-9]|0*1[0-9][0-9]|0*[0-9]?[0-9])';
		if ( preg_match( "/^(?:{$b}\.){3}{$b}$|^(?:{$b}\.){1,3}\*$/", $host ) ) {
			return 'V4.' . implode( '.', array_map( function ( $v ) {
				return $v === '*' ? $v : (int)$v;
			}, explode( '.', $host ) ) ) . '.';
		}

		// Must be a host name.
		return implode( '.', array_reverse( explode( '.', $host ) ) ) . '.';
	}

	/**
	 * Converts a URL into a format for el_index
	 * @since 1.33
	 * @param string $url
	 * @return string[] Usually one entry, but might be two in case of
	 *  protocol-relative URLs. Empty array on error.
	 */
	public static function makeIndexes( $url ) {
		// NOTE: If you change the output of this method, you'll probably have to increment self::VERSION!

		// NOTE: refreshExternallinksIndex.php assumes that only protocol-relative URLs return more
		// than one index, and that the indexes for protocol-relative URLs only vary in the "http://"
		// versus "https://" prefix. If you change that, you'll likely need to update
		// refreshExternallinksIndex.php accordingly.

		$bits = wfParseUrl( $url );
		if ( !$bits ) {
			return [];
		}

		// Reverse the labels in the hostname, convert to lower case, unless it's an IP.
		// For emails turn it into "domain.reversed@localpart"
		if ( $bits['scheme'] == 'mailto' ) {
			$mailparts = explode( '@', $bits['host'], 2 );
			if ( count( $mailparts ) === 2 ) {
				$domainpart = self::indexifyHost( $mailparts[1] );
			} else {
				// No @, assume it's a local part with no domain
				$domainpart = '';
			}
			$bits['host'] = $domainpart . '@' . $mailparts[0];
		} else {
			$bits['host'] = self::indexifyHost( $bits['host'] );
		}

		// Reconstruct the pseudo-URL
		$index = $bits['scheme'] . $bits['delimiter'] . $bits['host'];
		// Leave out user and password. Add the port, path, query and fragment
		if ( isset( $bits['port'] ) ) {
			$index .= ':' . $bits['port'];
		}
		if ( isset( $bits['path'] ) ) {
			$index .= $bits['path'];
		} else {
			$index .= '/';
		}
		if ( isset( $bits['query'] ) ) {
			$index .= '?' . $bits['query'];
		}
		if ( isset( $bits['fragment'] ) ) {
			$index .= '#' . $bits['fragment'];
		}

		if ( $bits['scheme'] == '' ) {
			return [ "http:$index", "https:$index" ];
		} else {
			return [ $index ];
		}
	}

	/**
	 * Return query conditions which will match the specified string. There are
	 * several kinds of filter entry:
	 *
	 *     *.domain.com    -  Matches domain.com and www.domain.com
	 *     domain.com      -  Matches domain.com or domain.com/ but not www.domain.com
	 *     *.domain.com/x  -  Matches domain.com/xy or www.domain.com/xy. Also probably matches
	 *                        domain.com/foobar/xy due to limitations of LIKE syntax.
	 *     domain.com/x    -  Matches domain.com/xy but not www.domain.com/xy
	 *     192.0.2.*       -  Matches any IP in 192.0.2.0/24. Can also have a path appended.
	 *     [2001:db8::*]   -  Matches any IP in 2001:db8::/112. Can also have a path appended.
	 *     [2001:db8:*]    -  Matches any IP in 2001:db8::/32. Can also have a path appended.
	 *     foo@domain.com  -  With protocol 'mailto:', matches the email address foo@domain.com.
	 *     *@domain.com    -  With protocol 'mailto:', matches any email address at domain.com, but
	 *                        not subdomains like foo@mail.domain.com
	 *
	 * Asterisks in any other location are considered invalid.
	 *
	 * @since 1.33
	 * @param string $filterEntry Filter entry, as described above
	 * @param array $options Options are:
	 *   - protocol: (string) Protocol to query (default http://)
	 *   - oneWildcard: (bool) Stop at the first wildcard (default false)
	 *   - prefix: (string) Field prefix (default 'el'). The query will test
	 *     fields '{$prefix}_index' and '{$prefix}_index_60'
	 *   - db: (IDatabase|null) Database to use.
	 * @return array|bool Conditions to be used for the query (to be ANDed) or
	 *  false on error. To determine if the query is constant on the
	 *  el_index_60 field, check whether key 'el_index_60' is set.
	 */
	public static function getQueryConditions( $filterEntry, array $options = [] ) {
		$options += [
			'protocol' => 'http://',
			'oneWildcard' => false,
			'prefix' => 'el',
			'db' => null,
		];

		// First, get the like array
		$like = self::makeLikeArray( $filterEntry, $options['protocol'] );
		if ( $like === false ) {
			return $like;
		}

		// Get the constant prefix (i.e. everything up to the first wildcard)
		$trimmedLike = self::keepOneWildcard( $like );
		if ( $options['oneWildcard'] ) {
			$like = $trimmedLike;
		}
		if ( $trimmedLike[count( $trimmedLike ) - 1] instanceof LikeMatch ) {
			array_pop( $trimmedLike );
		}
		$index = implode( '', $trimmedLike );

		$p = $options['prefix'];
		$db = $options['db'] ?: wfGetDB( DB_REPLICA );

		// Build the query
		$l = strlen( $index );
		if ( $l >= 60 ) {
			// The constant prefix is larger than el_index_60, so we can use a
			// constant comparison.
			return [
				"{$p}_index_60" => substr( $index, 0, 60 ),
				"{$p}_index" . $db->buildLike( $like ),
			];
		}

		// The constant prefix is smaller than el_index_60, so we use a LIKE
		// for a prefix search.
		return [
			"{$p}_index_60" . $db->buildLike( $index, $db->anyString() ),
			"{$p}_index" . $db->buildLike( $like ),
		];
	}

	/**
	 * Make an array to be used for calls to Database::buildLike(), which
	 * will match the specified string.
	 *
	 * This function does the same as LinkFilter::makeIndexes(), except it also takes care
	 * of adding wildcards
	 *
	 * @note You probably want self::getQueryConditions() instead
	 * @param string $filterEntry Filter entry, @see self::getQueryConditions()
	 * @param string $protocol Protocol (default http://)
	 * @return array|bool Array to be passed to Database::buildLike() or false on error
	 */
	public static function makeLikeArray( $filterEntry, $protocol = 'http://' ) {
		$db = wfGetDB( DB_REPLICA );
		$like = [];

		$target = $protocol . $filterEntry;
		$bits = wfParseUrl( $target );
		if ( !$bits ) {
			return false;
		}

		$subdomains = false;
		if ( $bits['scheme'] === 'mailto' && strpos( $bits['host'], '@' ) ) {
			// Email address with domain and non-empty local part
			$mailparts = explode( '@', $bits['host'], 2 );
			$domainpart = self::indexifyHost( $mailparts[1] );
			if ( $mailparts[0] === '*' ) {
				$subdomains = true;
				$bits['host'] = $domainpart . '@';
			} else {
				$bits['host'] = $domainpart . '@' . $mailparts[0];
			}
		} else {
			// Non-email, or email with only a domain part.
			$bits['host'] = self::indexifyHost( $bits['host'] );
			if ( substr( $bits['host'], -3 ) === '.*.' ) {
				$subdomains = true;
				$bits['host'] = substr( $bits['host'], 0, -2 );
			}
		}

		$like[] = $bits['scheme'] . $bits['delimiter'] . $bits['host'];

		if ( $subdomains ) {
			$like[] = $db->anyString();
		}

		if ( isset( $bits['port'] ) ) {
			$like[] = ':' . $bits['port'];
		}
		if ( isset( $bits['path'] ) ) {
			$like[] = $bits['path'];
		} elseif ( !$subdomains ) {
			$like[] = '/';
		}
		if ( isset( $bits['query'] ) ) {
			$like[] = '?' . $bits['query'];
		}
		if ( isset( $bits['fragment'] ) ) {
			$like[] = '#' . $bits['fragment'];
		}

		// Check for stray asterisks: asterisk only allowed at the start of the domain
		foreach ( $like as $likepart ) {
			if ( !( $likepart instanceof LikeMatch ) && strpos( $likepart, '*' ) !== false ) {
				return false;
			}
		}

		if ( !( $like[count( $like ) - 1] instanceof LikeMatch ) ) {
			// Add wildcard at the end if there isn't one already
			$like[] = $db->anyString();
		}

		return $like;
	}

	/**
	 * Filters an array returned by makeLikeArray(), removing everything past first
	 * pattern placeholder.
	 *
	 * @note You probably want self::getQueryConditions() instead
	 * @param array $arr Array to filter
	 * @return array Filtered array
	 */
	public static function keepOneWildcard( $arr ) {
		if ( !is_array( $arr ) ) {
			return $arr;
		}

		foreach ( $arr as $key => $value ) {
			if ( $value instanceof LikeMatch ) {
				return array_slice( $arr, 0, $key + 1 );
			}
		}

		return $arr;
	}
}
