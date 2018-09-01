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

		$text = $content->getNativeData();

		$regex = self::makeRegex( $filterEntry, $protocol );
		return preg_match( $regex, $text );
	}

	/**
	 * Builds a regex pattern for $filterEntry.
	 *
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
	 * @param string $filterEntry Domainparts
	 * @param string $protocol Protocol (default http://)
	 * @return array|bool Array to be passed to Database::buildLike() or false on error
	 */
	public static function makeLikeArray( $filterEntry, $protocol = 'http://' ) {
		$db = wfGetDB( DB_REPLICA );

		$target = $protocol . $filterEntry;
		$bits = wfParseUrl( $target );

		if ( $bits == false ) {
			// Unknown protocol?
			return false;
		}

		if ( substr( $bits['host'], 0, 2 ) == '*.' ) {
			$subdomains = true;
			$bits['host'] = substr( $bits['host'], 2 );
			if ( $bits['host'] == '' ) {
				// We don't want to make a clause that will match everything,
				// that could be dangerous
				return false;
			}
		} else {
			$subdomains = false;
		}

		// Reverse the labels in the hostname, convert to lower case
		// For emails reverse domainpart only
		if ( $bits['scheme'] === 'mailto' && strpos( $bits['host'], '@' ) ) {
			// complete email address
			$mailparts = explode( '@', $bits['host'] );
			$domainpart = strtolower( implode( '.', array_reverse( explode( '.', $mailparts[1] ) ) ) );
			$bits['host'] = $domainpart . '@' . $mailparts[0];
		} elseif ( $bits['scheme'] === 'mailto' ) {
			// domainpart of email address only, do not add '.'
			$bits['host'] = strtolower( implode( '.', array_reverse( explode( '.', $bits['host'] ) ) ) );
		} else {
			$bits['host'] = strtolower( implode( '.', array_reverse( explode( '.', $bits['host'] ) ) ) );
			if ( substr( $bits['host'], -1, 1 ) !== '.' ) {
				$bits['host'] .= '.';
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
