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

namespace MediaWiki\WikiMap;

/**
 * Reference to a locally-hosted wiki
 *
 * @ingroup Site
 */
class WikiReference {
	/** @var string wgCanonicalServer, e.g. 'https://en.example.org' */
	private string $mCanonicalServer;
	/** @var string wgServer, may be protocol-relative, e.g. '//en.example.org' */
	private string $mServer;
	/** @var string wgArticlepath, e.g. '/wiki/$1' */
	private string $mPath;

	/**
	 * @param string $canonicalServer
	 * @param string $path
	 * @param null|string $server
	 */
	public function __construct( string $canonicalServer, string $path, ?string $server = null ) {
		$this->mCanonicalServer = $canonicalServer;
		$this->mPath = $path;
		$this->mServer = $server ?? $canonicalServer;
	}

	/**
	 * Get the canonical server (i.e. $wgCanonicalServer)
	 *
	 * @return string E.g. "https://en.example.org".
	 */
	public function getCanonicalServer() {
		return $this->mCanonicalServer;
	}

	/**
	 * Extract the server name from wgCanonicalServer.
	 *
	 * @return string Hostname, e.g. "en.example.org".
	 */
	public function getDisplayName() {
		// If the server spec is invalid, there's no sensible thing to do here,
		// so just return the canonical server as-is.
		return parse_url( $this->mCanonicalServer, PHP_URL_HOST ) ?: $this->mCanonicalServer;
	}

	/**
	 * Create a full canonical URL to a page on the given wiki
	 *
	 * @param string $page Page name (must be normalised before calling this function!)
	 * @param string|null $fragmentId
	 * @return string URL E.g. "https://en.example.org/wiki/Foo#Bar".
	 */
	public function getCanonicalUrl( $page, $fragmentId = null ) {
		return $this->mCanonicalServer . $this->getLocalUrl( $page, $fragmentId );
	}

	/**
	 * Alias for getCanonicalUrl(), for backwards compatibility.
	 *
	 * @param string $page
	 * @param string|null $fragmentId
	 * @return string E.g. "https://en.example.org/wiki/Foo#Bar".
	 */
	public function getUrl( $page, $fragmentId = null ) {
		return $this->getCanonicalUrl( $page, $fragmentId );
	}

	/**
	 * Create a full URL like Title::getFullURL() to a page, based on $wgServer.
	 *
	 * This is similar to what Title::getFullURL() would produce when called locally on the wiki,
	 * and may differ from the canonical URL, depending on site configuration, as it uses
	 * $wgServer instead of $wgCanonicalServer.
	 *
	 * @param string $page Page name (must be normalized before calling this function!)
	 * @param string|null $fragmentId
	 * @return string URL E.g. "//en.example.org/wiki/Foo#Bar".
	 */
	public function getFullUrl( $page, $fragmentId = null ) {
		return $this->mServer . $this->getLocalUrl( $page, $fragmentId );
	}

	/**
	 * Helper function for getUrl()
	 *
	 * @todo FIXME: This may be generalized...
	 *
	 * @param string $page Page name (must be normalised before calling this function!
	 *  May contain a hash frament already.)
	 * @param string|null $fragmentId
	 * @return string Relative URL, e.g. "/wiki/Foo#Bar".
	 */
	private function getLocalUrl( $page, $fragmentId = null ) {
		$page = wfUrlencode( str_replace( ' ', '_', $page ) );

		if ( is_string( $fragmentId ) && $fragmentId !== '' ) {
			$page .= '#' . wfUrlencode( $fragmentId );
		}

		return str_replace( '$1', $page, $this->mPath );
	}
}
