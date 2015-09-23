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
 * Helper tools for dealing with other locally-hosted wikis
 */
class WikiMap {

	/**
	 * Get a WikiReference object for $wikiID
	 *
	 * @param string $wikiID Wiki'd id (generally database name)
	 * @return WikiReference|null WikiReference object or null if the wiki was not found
	 */
	public static function getWiki( $wikiID ) {
		global $wgConf;

		$wgConf->loadFullData();

		list( $major, $minor ) = $wgConf->siteFromDB( $wikiID );
		if ( $major === null ) {
			return null;
		}
		$server = $wgConf->get( 'wgServer', $wikiID, $major,
			array( 'lang' => $minor, 'site' => $major ) );

		$canonicalServer = $wgConf->get( 'wgCanonicalServer', $wikiID, $major,
			array( 'lang' => $minor, 'site' => $major ) );
		if ( $canonicalServer === false || $canonicalServer === null ) {
			$canonicalServer = $server;
		}

		$path = $wgConf->get( 'wgArticlePath', $wikiID, $major,
			array( 'lang' => $minor, 'site' => $major ) );
		return new WikiReference( $major, $minor, $canonicalServer, $path, $server );
	}

	/**
	 * Convenience to get the wiki's display name
	 *
	 * @todo We can give more info than just the wiki id!
	 * @param string $wikiID Wiki'd id (generally database name)
	 * @return string|int Wiki's name or $wiki_id if the wiki was not found
	 */
	public static function getWikiName( $wikiID ) {
		$wiki = WikiMap::getWiki( $wikiID );

		if ( $wiki ) {
			return $wiki->getDisplayName();
		}
		return $wikiID;
	}

	/**
	 * Convenience to get a link to a user page on a foreign wiki
	 *
	 * @param string $wikiID Wiki'd id (generally database name)
	 * @param string $user User name (must be normalised before calling this function!)
	 * @param string $text Link's text; optional, default to "User:$user"
	 * @return string HTML link or false if the wiki was not found
	 */
	public static function foreignUserLink( $wikiID, $user, $text = null ) {
		return self::makeForeignLink( $wikiID, "User:$user", $text );
	}

	/**
	 * Convenience to get a link to a page on a foreign wiki
	 *
	 * @param string $wikiID Wiki'd id (generally database name)
	 * @param string $page Page name (must be normalised before calling this function!)
	 * @param string $text Link's text; optional, default to $page
	 * @return string HTML link or false if the wiki was not found
	 */
	public static function makeForeignLink( $wikiID, $page, $text = null ) {
		if ( !$text ) {
			$text = $page;
		}

		$url = self::getForeignURL( $wikiID, $page );
		if ( $url === false ) {
			return false;
		}

		return Linker::makeExternalLink( $url, $text );
	}

	/**
	 * Convenience to get a url to a page on a foreign wiki
	 *
	 * @param string $wikiID Wiki'd id (generally database name)
	 * @param string $page Page name (must be normalised before calling this function!)
	 * @param string|null $fragmentId
	 *
	 * @return string|bool URL or false if the wiki was not found
	 */
	public static function getForeignURL( $wikiID, $page, $fragmentId = null ) {
		$wiki = WikiMap::getWiki( $wikiID );

		if ( $wiki ) {
			return $wiki->getFullUrl( $page, $fragmentId );
		}

		return false;
	}
}

/**
 * Reference to a locally-hosted wiki
 */
class WikiReference {
	private $mMinor; ///< 'en', 'meta', 'mediawiki', etc
	private $mMajor; ///< 'wiki', 'wiktionary', etc
	private $mCanonicalServer; ///< canonical server URL, e.g. 'https://www.mediawiki.org'
	private $mServer; ///< server URL, may be protocol-relative, e.g. '//www.mediawiki.org'
	private $mPath;   ///< path, '/wiki/$1'

	/**
	 * @param string $major
	 * @param string $minor
	 * @param string $canonicalServer
	 * @param string $path
	 * @param null|string $server
	 */
	public function __construct( $major, $minor, $canonicalServer, $path, $server = null ) {
		$this->mMajor = $major;
		$this->mMinor = $minor;
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
			// Invalid server spec. There's no sane thing to do here, so just return the canonical server name in full
			return $this->mCanonicalServer;
		}
	}

	/**
	 * Helper function for getUrl()
	 *
	 * @todo FIXME: This may be generalized...
	 *
	 * @param string $page Page name (must be normalised before calling this function! May contain a section part.)
	 * @param string|null $fragmentId
	 *
	 * @return string relative URL, without the server part.
	 */
	private function getLocalUrl( $page, $fragmentId = null ) {
		$page = wfUrlEncode( str_replace( ' ', '_', $page ) );

		if ( is_string( $fragmentId ) && $fragmentId !== '' ) {
			$page .= '#' . wfUrlEncode( $fragmentId );
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
