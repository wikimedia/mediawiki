<?php
/**
 * Checks for validity of requested URL's extension.
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
 * Internet Explorer derives a cache filename from a URL, and then in certain
 * circumstances, uses the extension of the resulting file to determine the
 * content type of the data, ignoring the Content-Type header.
 *
 * This can be a problem, especially when non-HTML content is sent by MediaWiki,
 * and Internet Explorer interprets it as HTML, exposing an XSS vulnerability.
 *
 * Usually the script filename (e.g. api.php) is present in the URL, and this
 * makes Internet Explorer think the extension is a harmless script extension.
 * But Internet Explorer 6 and earlier allows the script extension to be
 * obscured by encoding the dot as "%2E".
 *
 * This class contains functions which help in detecting and dealing with this
 * situation.
 *
 * Checking the URL for a bad extension is somewhat complicated due to the fact
 * that CGI doesn't provide a standard method to determine the URL. Instead it
 * is necessary to pass a subset of $_SERVER variables, which we then attempt
 * to use to guess parts of the URL.
 */
class IEUrlExtension {
	/**
	 * Check a subset of $_SERVER (or the whole of $_SERVER if you like)
	 * to see if it indicates that the request was sent with a bad file
	 * extension. Returns true if the request should be denied or modified,
	 * false otherwise. The relevant $_SERVER elements are:
	 *
	 *   - SERVER_SOFTWARE
	 *   - REQUEST_URI
	 *   - QUERY_STRING
	 *   - PATH_INFO
	 *
	 * If the a variable is unset in $_SERVER, it should be unset in $vars.
	 *
	 * @param array $vars A subset of $_SERVER.
	 * @param array $extWhitelist Extensions which are allowed, assumed harmless.
	 * @return bool
	 */
	public static function areServerVarsBad( $vars, $extWhitelist = [] ) {
		// Check QUERY_STRING or REQUEST_URI
		if ( isset( $vars['SERVER_SOFTWARE'] )
			&& isset( $vars['REQUEST_URI'] )
			&& self::haveUndecodedRequestUri( $vars['SERVER_SOFTWARE'] ) )
		{
			$urlPart = $vars['REQUEST_URI'];
		} elseif ( isset( $vars['QUERY_STRING'] ) ) {
			$urlPart = $vars['QUERY_STRING'];
		} else {
			$urlPart = '';
		}

		if ( self::isUrlExtensionBad( $urlPart, $extWhitelist ) ) {
			return true;
		}

		// Some servers have PATH_INFO but not REQUEST_URI, so we check both
		// to be on the safe side.
		if ( isset( $vars['PATH_INFO'] )
			&& self::isUrlExtensionBad( $vars['PATH_INFO'], $extWhitelist ) )
		{
			return true;
		}

		// All checks passed
		return false;
	}

	/**
	 * Given a right-hand portion of a URL, determine whether IE would detect
	 * a potentially harmful file extension.
	 *
	 * @param string $urlPart The right-hand portion of a URL
	 * @param array $extWhitelist An array of file extensions which may occur in this
	 *    URL, and which should be allowed.
	 * @return bool
	 */
	public static function isUrlExtensionBad( $urlPart, $extWhitelist = [] ) {
		if ( strval( $urlPart ) === '' ) {
			return false;
		}

		$extension = self::findIE6Extension( $urlPart );
		if ( strval( $extension ) === '' ) {
			// No extension or empty extension
			return false;
		}

		if ( in_array( $extension, [ 'php', 'php5' ] ) ) {
			// Script extension, OK
			return false;
		}
		if ( in_array( $extension, $extWhitelist ) ) {
			// Whitelisted extension
			return false;
		}

		if ( !preg_match( '/^[a-zA-Z0-9_-]+$/', $extension ) ) {
			// Non-alphanumeric extension, unlikely to be registered.
			// The regex above is known to match all registered file extensions
			// in a default Windows XP installation. It's important to allow
			// extensions with ampersands and percent signs, since that reduces
			// the number of false positives substantially.
			return false;
		}

		// Possibly bad extension
		return true;
	}

	/**
	 * Returns a variant of $url which will pass isUrlExtensionBad() but has the
	 * same GET parameters, or false if it can't figure one out.
	 * @param $url
	 * @param $extWhitelist array
	 * @return bool|string
	 */
	public static function fixUrlForIE6( $url, $extWhitelist = [] ) {
		$questionPos = strpos( $url, '?' );
		if ( $questionPos === false ) {
			$beforeQuery = $url . '?';
			$query = '';
		} elseif ( $questionPos === strlen( $url ) - 1 ) {
			$beforeQuery = $url;
			$query = '';
		} else {
			$beforeQuery = substr( $url, 0, $questionPos + 1 );
			$query = substr( $url, $questionPos + 1 );
		}

		// Multiple question marks cause problems. Encode the second and
		// subsequent question mark.
		$query = str_replace( '?', '%3E', $query );
		// Append an invalid path character so that IE6 won't see the end of the
		// query string as an extension
		$query .= '&*';
		// Put the URL back together
		$url = $beforeQuery . $query;
		if ( self::isUrlExtensionBad( $url, $extWhitelist ) ) {
			// Avoid a redirect loop
			return false;
		}
		return $url;
	}

	/**
	 * Determine what extension IE6 will infer from a certain query string.
	 * If the URL has an extension before the question mark, IE6 will use
	 * that and ignore the query string, but per the comment at
	 * isPathInfoBad() we don't have a reliable way to determine the URL,
	 * so isPathInfoBad() just passes in the query string for $url.
	 * All entry points have safe extensions (php, php5) anyway, so
	 * checking the query string is possibly overly paranoid but never
	 * insecure.
	 *
	 * The criteria for finding an extension are as follows:
	 * - a possible extension is a dot followed by one or more characters not
	 *   in <>\"/:|?.#
	 * - if we find a possible extension followed by the end of the string or
	 *   a #, that's our extension
	 * - if we find a possible extension followed by a ?, that's our extension
	 *    - UNLESS it's exe, dll or cgi, in which case we ignore it and continue
	 *      searching for another possible extension
	 * - if we find a possible extension followed by a dot or another illegal
	 *   character, we ignore it and continue searching
	 *
	 * @param string $url URL
	 * @return mixed Detected extension (string), or false if none found
	 */
	public static function findIE6Extension( $url ) {
		$pos = 0;
		$hashPos = strpos( $url, '#' );
		if ( $hashPos !== false ) {
			$urlLength = $hashPos;
		} else {
			$urlLength = strlen( $url );
		}
		$remainingLength = $urlLength;
		while ( $remainingLength > 0 ) {
			// Skip ahead to the next dot
			$pos += strcspn( $url, '.', $pos, $remainingLength );
			if ( $pos >= $urlLength ) {
				// End of string, we're done
				return false;
			}

			// We found a dot. Skip past it
			$pos++;
			$remainingLength = $urlLength - $pos;

			// Check for illegal characters in our prospective extension,
			// or for another dot
			$nextPos = $pos + strcspn( $url, "<>\\\"/:|?*.", $pos, $remainingLength );
			if ( $nextPos >= $urlLength ) {
				// No illegal character or next dot
				// We have our extension
				return substr( $url, $pos, $urlLength - $pos );
			}
			if ( $url[$nextPos] === '?' ) {
				// We've found a legal extension followed by a question mark
				// If the extension is NOT exe, dll or cgi, return it
				$extension = substr( $url, $pos, $nextPos - $pos );
				if ( strcasecmp( $extension, 'exe' ) && strcasecmp( $extension, 'dll' ) &&
					strcasecmp( $extension, 'cgi' ) )
				{
					return $extension;
				}
				// Else continue looking
			}
			// We found an illegal character or another dot
			// Skip to that character and continue the loop
			$pos = $nextPos;
			$remainingLength = $urlLength - $pos;
		}
		return false;
	}

	/**
	 * When passed the value of $_SERVER['SERVER_SOFTWARE'], this function
	 * returns true if that server is known to have a REQUEST_URI variable
	 * with %2E not decoded to ".". On such a server, it is possible to detect
	 * whether the script filename has been obscured.
	 *
	 * The function returns false if the server is not known to have this
	 * behavior. Microsoft IIS in particular is known to decode escaped script
	 * filenames.
	 *
	 * SERVER_SOFTWARE typically contains either a plain string such as "Zeus",
	 * or a specification in the style of a User-Agent header, such as
	 * "Apache/1.3.34 (Unix) mod_ssl/2.8.25 OpenSSL/0.9.8a PHP/4.4.2"
	 *
	 * @param $serverSoftware
	 * @return bool
	 *
	 */
	public static function haveUndecodedRequestUri( $serverSoftware ) {
		static $whitelist = [
			'Apache',
			'Zeus',
			'LiteSpeed' ];
		if ( preg_match( '/^(.*?)($|\/| )/', $serverSoftware, $m ) ) {
			return in_array( $m[1], $whitelist );
		} else {
			return false;
		}
	}

}
