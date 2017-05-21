<?php
/**
 * Tools for dealing with other locally-hosted wikis.
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
 * Reference to a locally-hosted wiki
 */
class WikiReference {
	private $mCanonicalServer; ///< canonical server URL, e.g. 'https://www.mediawiki.org'
	private $mServer; ///< server URL, may be protocol-relative, e.g. '//www.mediawiki.org'
	private $mPath; ///< path, '/wiki/$1'

	/**
	 * @param string $canonicalServer
	 * @param string $path
	 * @param null|string $server
	 */
	public function __construct( $canonicalServer, $path, $server = null ) {
		$this->mCanonicalServer = $canonicalServer;
		$this->mPath = $path;
		$this->mServer = $server === null ? $canonicalServer : $server;
	}

	/**
	 * Get the URL in a way to be displayed to the user
	 * More or less Wikimedia specific
	 *
	 * @return string
	 */
	public function getDisplayName() {
		$parsed = wfParseUrl( $this->mCanonicalServer );
		if ( $parsed ) {
			return $parsed['host'];
		} else {
			// Invalid server spec.
			// There's no sane thing to do here, so just return the canonical server name in full.
			return $this->mCanonicalServer;
		}
	}

	/**
	 * Helper function for getUrl()
	 *
	 * @todo FIXME: This may be generalized...
	 *
	 * @param string $page Page name (must be normalised before calling this function!
	 *  May contain a section part.)
	 * @param string|null $fragmentId
	 *
	 * @return string relative URL, without the server part.
	 */
	private function getLocalUrl( $page, $fragmentId = null ) {
		$page = wfUrlencode( str_replace( ' ', '_', $page ) );

		if ( is_string( $fragmentId ) && $fragmentId !== '' ) {
			$page .= '#' . wfUrlencode( $fragmentId );
		}

		return str_replace( '$1', $page, $this->mPath );
	}

	/**
	 * Get a canonical (i.e. based on $wgCanonicalServer) URL to a page on this foreign wiki
	 *
	 * @param string $page Page name (must be normalised before calling this function!)
	 * @param string|null $fragmentId
	 *
	 * @return string Url
	 */
	public function getCanonicalUrl( $page, $fragmentId = null ) {
		return $this->mCanonicalServer . $this->getLocalUrl( $page, $fragmentId );
	}

	/**
	 * Get a canonical server URL
	 * @return string
	 */
	public function getCanonicalServer() {
		return $this->mCanonicalServer;
	}

	/**
	 * Alias for getCanonicalUrl(), for backwards compatibility.
	 * @param string $page
	 * @param string|null $fragmentId
	 *
	 * @return string
	 */
	public function getUrl( $page, $fragmentId = null ) {
		return $this->getCanonicalUrl( $page, $fragmentId );
	}

	/**
	 * Get a URL based on $wgServer, like Title::getFullURL() would produce
	 * when called locally on the wiki.
	 *
	 * @param string $page Page name (must be normalized before calling this function!)
	 * @param string|null $fragmentId
	 *
	 * @return string URL
	 */
	public function getFullUrl( $page, $fragmentId = null ) {
		return $this->mServer .
			$this->getLocalUrl( $page, $fragmentId );
	}
}
