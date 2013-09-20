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
	 * Check whether $content contains a link to $filterEntry
	 *
	 * @param $content Content: content to check
	 * @param string $filterEntry domainparts, see makeRegex() for more details
	 * @return Integer: 0 if no match or 1 if there's at least one match
	 */
	static function matchEntry( Content $content, $filterEntry ) {
		if ( !( $content instanceof TextContent ) ) {
			//TODO: handle other types of content too.
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
	 * @param string $filterEntry URL, if it begins with "*.", it'll be
	 *        replaced to match any subdomain
	 * @return String: regex pattern, for preg_match()
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
	 * Make an array to be used for calls to DatabaseBase::buildLike(), which
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
	 * @param string $filterEntry domainparts
	 * @param $prot        String: protocol
	 * @return Array to be passed to DatabaseBase::buildLike() or false on error
	 */
	public static function makeLikeArray( $filterEntry, $prot = 'http://' ) {
		$db = wfGetDB( DB_MASTER );
		$entryNoStar = $filterEntry;
		if ( substr( $filterEntry, 0, 2 ) == '*.' ) {
			$subdomains = true;
			$entryNoStar = substr( $filterEntry, 2 );
			if ( $entryNoStar == '' ) {
				// We don't want to make a clause that will match everything,
				// that could be dangerous
				return false;
			}
		} else {
			$subdomains = false;
		}
		// No stray asterisks, that could cause confusion
		// It's not simple or efficient to handle it properly so we don't
		// handle it at all.
		if ( strpos( $entryNoStar, '*' ) !== false ) {
			return false;
		}
		// Use wfMakeUrlIndexes to be consistent with the logic used to
		// munge the links on their way into the database.  It has a
		// funny way of handling mailto links, so we work around it.
		if ( $prot == 'mailto:' && !strpos( $filterEntry, '@' ) ) {
			$filterEntry = '@' . $filterEntry;
			$mailtoDomain = true;
		} else {
			$mailtoDomain = false;
		}
		$mungedArray = wfMakeUrlIndexes( $prot . $filterEntry );
		$munged = $mungedArray[0];
		if ( $mailtoDomain ) {
			$munged = str_replace( '.*@', '*', $munged );
			$munged = str_replace( '@.', '@', $munged );
		}
		$munged = str_replace( '*.', '*', $munged );
		// Preserve original trimming of bare '/' path if url
		// had no slashes or just one at the end.
		if ( $subdomains && substr( $munged, -1, 1 ) == '/' ) {
			$slash = strpos( $filterEntry, '/' );
			if ( $slash === false || $slash == strlen( $filterEntry ) - 1 ) {
				$munged = substr( $munged, 0, strlen( $munged ) - 1 );
			}
		}
		$parts = explode( '*', $munged );
		// Seems like we could just use the first return statement
		// and get rid of keepOneWildcard.
		if ( count( $parts ) == 1 ) {
			return array ( $parts[0], $db->anyString() );
		}
		return array ( $parts[0], $db->anyString(), $parts[1], $db->anyString() );
	}

	/**
	 * Filters an array returned by makeLikeArray(), removing everything past
	 * first pattern placeholder.
	 *
	 * @param array $arr array to filter
	 * @return array filtered array
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
