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
		global $wgConf, $IP;

		$wgConf->loadFullData();

		list( $major, $minor ) = $wgConf->siteFromDB( $wikiID );
		if( isset( $major ) ) {
			$server = $wgConf->get( 'wgServer', $wikiID, $major,
				array( 'lang' => $minor, 'site' => $major ) );
			$path = $wgConf->get( 'wgArticlePath', $wikiID, $major,
				array( 'lang' => $minor, 'site' => $major ) );
			return new WikiReference( $major, $minor, $server, $path );
		} else {
			return null;
		}
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
		global $wgUser;
		$sk = $wgUser->getSkin();

		if ( !$text )
			$text = $page;

		$url = self::getForeignURL( $wikiID, $page );
		if ( $url === false )
			return false;

		return $sk->makeExternalLink( $url, $text );
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
		
		if ( $wiki )
			return $wiki->getUrl( $page );
			
		return false;
	}
}

/**
 * Reference to a locally-hosted wiki
 */
class WikiReference {
	private $mMinor; ///< 'en', 'meta', 'mediawiki', etc
	private $mMajor; ///< 'wiki', 'wiktionary', etc
	private $mServer; ///< server override, 'www.mediawiki.org'
	private $mPath;   ///< path override, '/wiki/$1'

	public function __construct( $major, $minor, $server, $path ) {
		$this->mMajor = $major;
		$this->mMinor = $minor;
		$this->mServer = $server;
		$this->mPath = $path;
	}

	public function getHostname() {
		$prefixes = array( 'http://', 'https://' );
		foreach ( $prefixes as $prefix ) {
			if ( substr( $this->mServer, 0, strlen( $prefix ) ) ) {
				return substr( $this->mServer, strlen( $prefix ) );
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
		$url = preg_replace( '!^https?://!', '', $url );
		$url = preg_replace( '!/index\.php(\?title=|/)$!', '/', $url );
		$url = preg_replace( '!/wiki/$!', '/', $url );
		$url = preg_replace( '!/$!', '', $url );
		return $url;
	}

	/**
	 * Helper function for getUrl()
	 *
	 * @todo FIXME: this may be generalized...
	 * @param $page String: page name (must be normalised before calling this function!)
	 * @return String: Url fragment
	 */
	private function getLocalUrl( $page ) {
		return str_replace( '$1', wfUrlEncode( str_replace( ' ', '_', $page ) ), $this->mPath );
	}

	/**
	 * Get a URL to a page on this foreign wiki
	 *
	 * @param $page String: page name (must be normalised before calling this function!)
	 * @return String: Url
	 */
	public function getUrl( $page ) {
		return
			$this->mServer .
			$this->getLocalUrl( $page );
	}
}
