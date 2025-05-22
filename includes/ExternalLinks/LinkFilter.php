<?php
/**
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

namespace MediaWiki\ExternalLinks;

use MediaWiki\Content\Content;
use MediaWiki\Content\TextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeMatch;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\StringUtils\StringUtils;

/**
 * Utilities for formatting and querying the externallinks table.
 *
 * This is primarily used by \MediaWiki\Deferred\LinksUpdate\ExternalLinksTable
 * for managing the storage layer, and by SpecialLinkSearch and ApiQueryExtLinksUsage
 * as query interface.
 *
 * For spam removal and anti-spam meausures based on this, see also:
 * - maintenance/cleanupSpam.php
 * - SpamBlacklist extension
 * - AbuseFilter extension (Special:BlockedExternalDomains, T337431)
 */
class LinkFilter {
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
	 * Build a regex pattern for $filterEntry.
	 *
	 * @todo This doesn't match the rest of the functionality here.
	 * @param string $filterEntry URL, if it begins with "*.", it'll be
	 *        replaced to match any subdomain
	 * @param string $protocol 'http://' or 'https://'
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
	 * Canonicalize a hostname for the externallinks table
	 *
	 * @param string $host
	 * @param bool $reverse whether to reverse the domain name or not
	 * @return string
	 */
	private static function indexifyHost( $host, $reverse = true ) {
		// Canonicalize.
		$host = rawurldecode( $host );
		if ( $host !== '' ) {
			$tmp = idn_to_utf8( $host );
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
			'<[^' . $okChars . ']+>',
			static fn ( $m ) => rawurlencode( $m[0] ),
			strtolower( $host )
		);

		// IPv6? RFC 3986 syntax.
		if ( preg_match( '/^\[([0-9a-f:*]+)\]$/', rawurldecode( $host ), $m ) ) {
			$ip = $m[1];
			if ( IPUtils::isValid( $ip ) ) {
				if ( !$reverse ) {
					return '[' . IPUtils::sanitizeIP( $ip ) . ']';
				}
				return 'V6.' . implode( '.', explode( ':', IPUtils::sanitizeIP( $ip ) ) ) . '.';
			}
			if ( substr( $ip, -2 ) === ':*' ) {
				$cutIp = substr( $ip, 0, -2 );
				if ( IPUtils::isValid( "{$cutIp}::" ) ) {
					// Wildcard IP doesn't contain "::", so multiple parts can be wild
					$ct = count( explode( ':', $ip ) ) - 1;
					if ( !$reverse ) {
						return '[' . IPUtils::sanitizeIP( "{$cutIp}::" ) . ']';
					}
					return 'V6.' .
						implode( '.', array_slice( explode( ':', IPUtils::sanitizeIP( "{$cutIp}::" ) ), 0, $ct ) ) .
						'.*.';
				}
				if ( IPUtils::isValid( "{$cutIp}:1" ) ) {
					// Wildcard IP does contain "::", so only the last part is wild
					if ( !$reverse ) {
						return '[' . IPUtils::sanitizeIP( "{$cutIp}:1" ) . ']';
					}
					return 'V6.' .
						substr( implode( '.', explode( ':', IPUtils::sanitizeIP( "{$cutIp}:1" ) ) ), 0, -1 ) .
						'*.';
				}
			}
		}

		// Regularize explicit specification of the DNS root.
		// Browsers seem to do this for IPv4 literals too.
		if ( substr( $host, -1 ) === '.' ) {
			$host = substr( $host, 0, -1 );
		}

		// IPv4?
		$b = '(?:0*25[0-5]|0*2[0-4][0-9]|0*1[0-9][0-9]|0*[0-9]?[0-9])';
		if ( preg_match( "/^(?:{$b}\.){3}{$b}$|^(?:{$b}\.){1,3}\*$/", $host ) ) {
			if ( !$reverse ) {
				return $host;
			}
			return 'V4.' . implode( '.', array_map( static function ( $v ) {
				return $v === '*' ? $v : (int)$v;
			}, explode( '.', $host ) ) ) . '.';
		}

		// Must be a host name.
		if ( $reverse ) {
			return implode( '.', array_reverse( explode( '.', $host ) ) ) . '.';
		} else {
			return $host;
		}
	}

	/**
	 * Convert given URL to format for the externallinks table
	 *
	 * @since 1.33
	 * @param string $url
	 * @param bool $reverseDomain
	 * @return string[][] One entry. Empty array on error.
	 *  Each entry is an array in form of <host,path>
	 */
	public static function makeIndexes( $url, $reverseDomain = true ) {
		// NOTE: refreshExternallinksIndex.php assumes that only protocol-relative URLs return more
		// than one index, and that the indexes for protocol-relative URLs only vary in the "http://"
		// versus "https://" prefix. If you change that, you'll likely need to update
		// refreshExternallinksIndex.php accordingly.

		$bits = MediaWikiServices::getInstance()->getUrlUtils()->parse( $url );
		if ( !$bits ) {
			return [];
		}

		// URI RFC identifies the email/server part of mailto or news protocol as 'path',
		// while we want to match the email's domain or news server the same way we are
		// matching hosts for other URLs.
		if ( in_array( $bits['scheme'], [ 'mailto', 'news' ] ) ) {
			// (T347574) Only set host if it's not already set (if // is used)
			if ( array_key_exists( 'path', $bits ) ) {
				$bits['host'] = $bits['path'];
			}
			$bits['path'] = '';
		}

		// Reverse the labels in the hostname, convert to lower case, unless it's an IP.
		// For emails turn it into "domain.reversed@localpart"
		if ( $bits['scheme'] == 'mailto' ) {
			$mailparts = explode( '@', $bits['host'], 2 );
			if ( count( $mailparts ) === 2 ) {
				$domainpart = self::indexifyHost( $mailparts[1], $reverseDomain );
			} else {
				// No @, assume it's a local part with no domain
				$domainpart = '';
			}
			if ( $reverseDomain ) {
				$bits['host'] = $domainpart . '@' . $mailparts[0];
			} else {
				$bits['host'] = $mailparts[0] . '@' . $domainpart;
			}
		} else {
			$bits['host'] = self::indexifyHost( $bits['host'], $reverseDomain );
		}

		// Reconstruct the pseudo-URL
		$index = $bits['scheme'] . $bits['delimiter'] . $bits['host'];
		// Leave out user and password. Add the port, path, query and fragment
		if ( isset( $bits['port'] ) ) {
			$index .= ':' . $bits['port'];
		}
		$index2 = $bits['path'] ?? '/';
		if ( isset( $bits['query'] ) ) {
			$index2 .= '?' . $bits['query'];
		}
		if ( isset( $bits['fragment'] ) ) {
			$index2 .= '#' . $bits['fragment'];
		}

		if ( $bits['scheme'] == '' ) {
			return [ [ "https:$index", $index2 ] ];
		} else {
			return [ [ $index, $index2 ] ];
		}
	}

	/**
	 * Converts a set of URLs to be able to compare them with existing indexes
	 * @since 1.41
	 * @param string[] $urls List of URLs to be indexed
	 * @return string[]
	 */
	public static function getIndexedUrlsNonReversed( $urls ) {
		$newLinks = [];
		foreach ( $urls as $url ) {
			$indexes = self::makeIndexes( $url, false );
			if ( !$indexes ) {
				continue;
			}
			foreach ( $indexes as $index ) {
				$newLinks[] = $index[0] . $index[1];
			}
		}
		return $newLinks;
	}

	public static function reverseIndexes( $domainIndex ) {
		$bits = MediaWikiServices::getInstance()->getUrlUtils()->parse( $domainIndex );
		if ( !$bits ) {
			return '';
		}

		// Reverse the labels in the hostname, convert to lower case, unless it's an IP.
		// For emails turn it into "domain.reversed@localpart"
		if ( $bits['scheme'] == 'mailto' ) {
			$mailparts = explode( '@', $bits['path'], 2 );
			if ( count( $mailparts ) === 2 ) {
				$domainpart = rtrim( self::reverseDomain( $mailparts[0] ), '.' );
				$bits['host'] = $mailparts[1] . '@' . $domainpart;
			} else {
				// No @, assume it's a local part with no domain
				$bits['host'] = $mailparts[0];
			}
		} else {
			$bits['host'] = rtrim( self::reverseDomain( $bits['host'] ), '.' );
		}

		$index = $bits['scheme'] . $bits['delimiter'] . $bits['host'];
		if ( isset( $bits['port'] ) && $bits['port'] ) {
			$index .= ':' . $bits['port'];
		}
		return $index;
	}

	private static function reverseDomain( string $domain ): string {
		if ( substr( $domain, 0, 3 ) === 'V6.' ) {
			$ipv6 = str_replace( '.', ':', trim( substr( $domain, 3 ), '.' ) );
			if ( IPUtils::isValid( $ipv6 ) ) {
				return '[' . $ipv6 . ']';
			}
		} elseif ( substr( $domain, 0, 3 ) === 'V4.' ) {
			$ipv4 = trim( substr( $domain, 3 ), '.' );
			if ( IPUtils::isValid( $ipv4 ) ) {
				return $ipv4;
			}
		}
		return self::indexifyHost( $domain );
	}

	/**
	 * Return conditions for the externallinks table from a given filter entry.
	 *
	 * There are several ways you can query:
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
	 *   - protocol: (null, string, array) Protocol to query (default: `http://` and `https://`)
	 *   - oneWildcard: (bool) Stop at the first wildcard (default: false)
	 *   - db: (IReadableDatabase|null) Database for building SQL text.
	 * @return array|false Query conditions (to be ANDed) or false on error.
	 */
	public static function getQueryConditions( $filterEntry, array $options = [] ) {
		$options += [
			'protocol' => [ 'http://', 'https://' ],
			'oneWildcard' => false,
			'db' => null,
		];
		$domainGaps = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::ExternalLinksDomainGaps
		);

		if ( is_string( $options['protocol'] ) ) {
			$options['protocol'] = [ $options['protocol'] ];
		} elseif ( $options['protocol'] === null ) {
			$options['protocol'] = [ 'http://', 'https://' ];
		}

		$domainConditions = [];
		$db = $options['db'] ?: MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		foreach ( $options['protocol'] as $protocol ) {
			$like = self::makeLikeArray( $filterEntry, $protocol );
			if ( $like === false ) {
				continue;
			}
			[ $likeDomain, $likePath ] = $like;
			$trimmedlikeDomain = self::keepOneWildcard( $likeDomain );
			if ( $trimmedlikeDomain[count( $trimmedlikeDomain ) - 1] instanceof LikeMatch ) {
				array_pop( $trimmedlikeDomain );
			}
			$index1 = implode( '', $trimmedlikeDomain );
			if ( $options['oneWildcard'] && $likePath[0] != '/' ) {
				$thisDomainExpr = $db->expr( 'el_to_domain_index', '=', $index1 );
			} else {
				$thisDomainExpr = $db->expr(
					'el_to_domain_index',
					IExpression::LIKE,
					new LikeValue( $index1, $db->anyString() )
				);
			}
			foreach ( $domainGaps[$index1] ?? [] as $from => $to ) {
				$thisDomainExpr = $thisDomainExpr->andExpr( $db->expr( 'el_id', '<', $from )->or( 'el_id', '>', $to ) );
			}
			$domainConditions[] = $thisDomainExpr;
		}
		if ( !$domainConditions ) {
			return false;
		}
		// @phan-suppress-next-line PhanPossiblyUndeclaredVariable
		$trimmedlikePath = self::keepOneWildcard( $likePath );
		if ( $trimmedlikePath[count( $trimmedlikePath ) - 1] instanceof LikeMatch ) {
			array_pop( $trimmedlikePath );
		}
		$index2 = implode( '', $trimmedlikePath );

		return [
			$db->orExpr( $domainConditions ),
			$db->expr( 'el_to_path', IExpression::LIKE, new LikeValue( $index2, $db->anyString() ) ),
		];
	}

	public static function getProtocolPrefix( $protocol ) {
		// Find the right prefix
		$urlProtocols = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::UrlProtocols );
		if ( $protocol && !in_array( $protocol, $urlProtocols ) ) {
			foreach ( $urlProtocols as $p ) {
				if ( str_starts_with( $p, $protocol ) ) {
					$protocol = $p;
					break;
				}
			}

			return $protocol;
		} else {
			return null;
		}
	}

	public static function prepareProtocols() {
		$urlProtocols = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::UrlProtocols );
		$protocols = [ '' ];
		foreach ( $urlProtocols as $p ) {
			if ( $p !== '//' ) {
				$protocols[] = substr( $p, 0, strpos( $p, ':' ) );
			}
		}

		return $protocols;
	}

	/**
	 * Make an array to be used for calls to Database::buildLike(), which
	 * will match the specified string.
	 *
	 * This function does the same as LinkFilter::makeIndexes(), except it also takes care
	 * of adding wildcards
	 *
	 * @note You probably want self::getQueryConditions() instead
	 * @param string $filterEntry Filter entry, see {@link getQueryConditions}
	 * @param string $protocol Protocol (default http://)
	 * @return array|false Array to be passed to Database::buildLike() or false on error
	 */
	public static function makeLikeArray( $filterEntry, $protocol = 'http://' ) {
		$services = MediaWikiServices::getInstance();
		$db = $services->getConnectionProvider()->getReplicaDatabase();
		$likeDomain = [];
		$likePath = [];

		$target = $protocol . $filterEntry;
		$bits = $services->getUrlUtils()->parse( $target );
		if ( !$bits ) {
			return false;
		}

		// URI RFC identifies the email/server part of mailto or news protocol as 'path',
		// while we want to match the email's domain or news server the same way we are
		// matching hosts for other URLs.
		if ( in_array( $bits['scheme'], [ 'mailto', 'news' ] ) ) {
			// (T364743) Only set host if it's not already set (if // is used)
			if ( array_key_exists( 'path', $bits ) ) {
				$bits['host'] = $bits['path'];
			}
			$bits['path'] = '';
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

		$likeDomain[] = $bits['scheme'] . $bits['delimiter'] . $bits['host'];

		if ( $subdomains ) {
			$likeDomain[] = $db->anyString();
		}

		if ( isset( $bits['port'] ) ) {
			$likeDomain[] = ':' . $bits['port'];
		}
		if ( isset( $bits['path'] ) ) {
			$likePath[] = $bits['path'];
		} else {
			$likePath[] = '/';
		}
		if ( isset( $bits['query'] ) ) {
			$likePath[] = '?' . $bits['query'];
		}
		if ( isset( $bits['fragment'] ) ) {
			$likePath[] = '#' . $bits['fragment'];
		}
		$likePath[] = $db->anyString();

		// Check for stray asterisks: asterisk only allowed at the start of the domain
		foreach ( array_merge( $likeDomain, $likePath ) as $likepart ) {
			if ( !( $likepart instanceof LikeMatch ) && strpos( $likepart, '*' ) !== false ) {
				return false;
			}
		}

		return [ $likeDomain, $likePath ];
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
