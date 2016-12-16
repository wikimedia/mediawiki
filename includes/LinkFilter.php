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
	const VERSION = 1;

	/**
	 * Check whether $content contains a link to $filterEntry
	 *
	 * @param Content $content Content to check
	 * @param string $filterEntry Domainparts, see makeRegex() for more details
	 * @return int 0 if no match or 1 if there's at least one match
	 */
	static function matchEntry( Content $content, $filterEntry ) {
		if ( !( $content instanceof TextContent ) ) {
			// TODO: handle other types of content too.
			//      Maybe create ContentHandler::matchFilter( LinkFilter ).
			//      Think about a common base class for LinkFilter and MagicWord.
			return 0;
		}

		$text = $content->getNativeData();

		$regex = LinkFilter::makeRegex( $filterEntry );
		return preg_match( $regex, $text );
	}

	/**
	 * Builds a regex pattern for $filterEntry.
	 *
	 * @todo This doesn't match the rest of it.
	 * @param string $filterEntry URL, if it begins with "*.", it'll be
	 *        replaced to match any subdomain
	 * @return string Regex pattern, for preg_match()
	 */
	private static function makeRegex( $filterEntry ) {
		$regex = '!http://';
		if ( substr( $filterEntry, 0, 2 ) == '*.' ) {
			$regex .= '(?:[A-Za-z0-9.-]+\.|)';
			$filterEntry = substr( $filterEntry, 2 );
		}
		$regex .= preg_quote( $filterEntry, '!' ) . '!Si';
		return $regex;
	}

	/**
	 * Canonicalize a hostname for el_index
	 * @param string $hose
	 * @return string
	 */
	private static function indexifyHost( $host ) {
		// Canonicalize.
		$host = rawurldecode( $host );
		if ( is_callable( 'idn_to_utf8' ) ) {
			$host = idn_to_utf8( $host );
		}
		$host = preg_replace_callback(
			'<[^a-zA-Z0-9\\-._~!$&\'()*+,;=]>',
			function ( $m ) {
				return rawurlencode( $m[0] );
			},
			strtolower( $host )
		);

		// IPv6? RFC 3986 syntax.
		if ( preg_match( '/^\[([0-9a-f:*]+)\]$/', rawurldecode( $host ), $m ) ) {
			$ip = $m[1];
			if ( IP::isValid( $ip ) ) {
				return 'V6.' . implode( '.', explode( ':', IP::sanitizeIP( $ip ) ) ) . '.';
			}
			if ( substr( $ip, -2 ) === ':*' ) {
				$cutIp = substr( $ip, 0, -2 );
				if ( IP::isValid( "{$cutIp}::" ) ) {
					$ct = count( explode( ':', $ip ) ) - 1;
					return 'V6.' .
						implode( '.', array_slice( explode( ':', IP::sanitizeIP( "{$cutIp}::" ) ), 0, $ct ) ) .
						'.*.';
				}
				if ( IP::isValid( "{$cutIp}:1" ) ) {
					return 'V6.' .
						substr( implode( '.', explode( ':', IP::sanitizeIP( "{$cutIp}:1" ) ) ), 0, -1 ) .
						'*.';
				}
			}
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
	 * @since 1.29
	 * @param string $url
	 * @return string[] Usually one entry, but might be two in case of
	 *  protocol-relative URLs. Empty array on error.
	 */
	public static function makeIndexes( $url ) {
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
	 *
	 * Asterisks in any other location are considered invalid.
	 *
	 * @since 1.29
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

		// The constant prefix is smaller than el_index_60, so we make a range query on
		// "$index <= el_index_60 < $index+1", where $index+1 is calculated
		// by treating $index as a big-endian base-256 number.
		$indexEnd = $index;
		while ( --$l >= 0 ) {
			if ( $indexEnd[$l] === "\xff" ) {
				// FF + 1 = 00 with a carry
				$indexEnd[$l] === "\x00";
			} else {
				// No carry, stop here.
				$indexEnd[$l] = chr( ord( $indexEnd[$l] ) + 1 );
				break;
			}
		}

		// Overflow?
		if ( $l < 0 ) {
			return [
				"{$p}_index_60 >= " . $db->addQuotes( $index ),
				"{$p}_index" . $db->buildLike( $like ),
			];
		}

		return [
			"{$p}_index_60 >= " . $db->addQuotes( $index ),
			"{$p}_index_60 < " . $db->addQuotes( $indexEnd ),
			"{$p}_index" . $db->buildLike( $like ),
		];
	}

	/**
	 * Make an array to be used for calls to Database::buildLike(), which
	 * will match the specified string. There are several kinds of filter entry:
	 *     *.domain.com    -  Produces http://com.domain.%, matches domain.com
	 *                        and www.domain.com
	 *     domain.com      -  Produces http://com.domain./%, matches domain.com
	 *                        or domain.com/ but not www.domain.com
	 *     *.domain.com/x  -  Produces http://com.domain.%/x%, matches
	 *                        www.domain.com/xy
	 *     domain.com/x    -  Produces http://com.domain./x%, matches
	 *                        domain.com/xy but not www.domain.com/xy
	 *
	 * Asterisks in any other location are considered invalid.
	 *
	 * This function does the same as wfMakeUrlIndexes(), except it also takes care
	 * of adding wildcards
	 *
	 * @note You probably want self::getQueryConditions() instead
	 * @param string $filterEntry Domainparts
	 * @param string $protocol Protocol (default http://)
	 * @return array|bool Array to be passed to Database::buildLike() or false on error
	 */
	public static function makeLikeArray( $filterEntry, $protocol = 'http://' ) {
		$db = wfGetDB( DB_REPLICA );

		$target = $protocol . $filterEntry;
		$bits = wfParseUrl( $target );
		if ( !$bits ) {
			return false;
		}

		$subdomains = false;
		if ( $bits['scheme'] === 'mailto' && strpos( $bits['host'], '@' ) ) {
			// Email address with domain and non-empty local part
			$mailparts = explode( '@', $bits['host'], 2 );
			if ( $mailparts[0] === '*' ) {
				$subdomains = true;
				$bits['host'] = self::indexifyHost( $mailparts[1] ) . '@';
			} else {
				$bits['host'] = self::indexifyHost( $mailparts[1] ) . '@' . $mailparts[0];
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
