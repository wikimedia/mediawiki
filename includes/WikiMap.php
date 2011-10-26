<?php

/**
 * Helper tools for dealing with other locally-hosted wikis
 */
class WikiMap {

	/**
	 * Get a WikiReference object for $wikiID
	 *
	 * @param $wikiID String: wiki'd id (generally database name)
	 * @return WikiReference object or null if the wiki was not found
	 */
	public static function getWiki( $wikiID ) {
		global $wgConf;

		$wgConf->loadFullData();

		list( $major, $minor ) = $wgConf->siteFromDB( $wikiID );
		if( $major === null ) {
			return null;
		}
		$canonicalServer = $wgConf->get( 'wgCanonicalServer', $wikiID, $major,
			array( 'lang' => $minor, 'site' => $major ) );
		$server = $wgConf->get( 'wgServer', $wikiID, $major,
			array( 'lang' => $minor, 'site' => $major ) );
		$path = $wgConf->get( 'wgArticlePath', $wikiID, $major,
			array( 'lang' => $minor, 'site' => $major ) );
		return new WikiReference( $major, $minor, $canonicalServer, $path, $server );
	}

	/**
	 * Convenience to get the wiki's display name
	 *
	 * @todo We can give more info than just the wiki id!
	 * @param $wikiID String: wiki'd id (generally database name)
	 * @return Wiki's name or $wiki_id if the wiki was not found
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
	 * @param $wikiID String: wiki'd id (generally database name)
	 * @param $user String: user name (must be normalised before calling this function!)
	 * @param $text String: link's text; optional, default to "User:$user"
	 * @return String: HTML link or false if the wiki was not found
	 */
	public static function foreignUserLink( $wikiID, $user, $text=null ) {
		return self::makeForeignLink( $wikiID, "User:$user", $text );
	}

	/**
	 * Convenience to get a link to a page on a foreign wiki
	 *
	 * @param $wikiID String: wiki'd id (generally database name)
	 * @param $page String: page name (must be normalised before calling this function!)
	 * @param $text String: link's text; optional, default to $page
	 * @return String: HTML link or false if the wiki was not found
	 */
	public static function makeForeignLink( $wikiID, $page, $text=null ) {
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
	 * @param $wikiID String: wiki'd id (generally database name)
	 * @param $page String: page name (must be normalised before calling this function!)
	 * @return String: URL or false if the wiki was not found
	 */
	public static function getForeignURL( $wikiID, $page ) {
		$wiki = WikiMap::getWiki( $wikiID );

		if ( $wiki ) {
			return $wiki->getUrl( $page );
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
	private $mCanonicalServer; ///< canonical server URL, e.g. 'http://www.mediawiki.org'
	private $mServer; ///< server URL, may be protocol-relative, e.g. '//www.mediawiki.org'
	private $mPath;   ///< path, '/wiki/$1'

	public function __construct( $major, $minor, $canonicalServer, $path, $server = null ) {
		$this->mMajor = $major;
		$this->mMinor = $minor;
		$this->mCanonicalServer = $canonicalServer;
		$this->mPath = $path;
		$this->mServer = $server === null ? $canonicalServer : $server;
	}

	/**
	 * @return string
	 * @throws MWException
	 */
	public function getHostname() {
		$prefixes = array( 'http://', 'https://' );
		foreach ( $prefixes as $prefix ) {
			if ( substr( $this->mCanonicalServer, 0, strlen( $prefix ) ) ) {
				return substr( $this->mCanonicalServer, strlen( $prefix ) );
			}
		}
		throw new MWException( "Invalid hostname for wiki {$this->mMinor}.{$this->mMajor}" );
	}

	/**
	 * Get the the URL in a way to de displayed to the user
	 * More or less Wikimedia specific
	 *
	 * @return String
	 */
	public function getDisplayName() {
		$url = $this->getUrl( '' );
		$parsed = wfParseUrl( $url );
		if ( $parsed ) {
			return $parsed['host'];
		} else {
			// Invalid URL. There's no sane thing to do here, so just return it
			return $url;
		}
	}

	/**
	 * Helper function for getUrl()
	 *
	 * @todo FIXME: This may be generalized...
	 * @param $page String: page name (must be normalised before calling this function!)
	 * @return String: Url fragment
	 */
	private function getLocalUrl( $page ) {
		return str_replace( '$1', wfUrlEncode( str_replace( ' ', '_', $page ) ), $this->mPath );
	}

	/**
	 * Get a canonical (i.e. based on $wgCanonicalServer) URL to a page on this foreign wiki
	 *
	 * @param $page String: page name (must be normalised before calling this function!)
	 * @return String: Url
	 */
	public function getCanonicalUrl( $page ) {
		return $this->mCanonicalServer . $this->getLocalUrl( $page );
	}

	/**
	 * Alias for getCanonicalUrl(), for backwards compatibility.
	 * @return String
	 */
	public function getUrl( $page ) {
		return $this->getCanonicalUrl( $page );
	}

	/**
	 * Get a URL based on $wgServer, like Title::getFullUrl() would produce
	 * when called locally on the wiki.
	 *
	 * @param $page String: page name (must be normalized before calling this function!)
	 * @return String: URL
	 */
	public function getFullUrl( $page ) {
		return
			$this->mServer .
			$this->getLocalUrl( $page );
	}
}
