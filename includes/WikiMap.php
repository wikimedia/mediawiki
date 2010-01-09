<?php

/**
 * Helper tools for dealing with other locally-hosted wikis
 */

class WikiMap {
	static function getWiki( $wikiID ) {
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
	
	// Convenience functions from GlobalBlocking
	static function getWikiName( $wiki_id ) {
		// We can give more info than just the wiki id!
		$wiki = WikiMap::getWiki( $wiki_id );
			
		if ($wiki) {
			return $wiki->getDisplayName();
		}
		return $wiki_id;
	}
	
	static function foreignUserLink( $wiki_id, $user, $text=null ) {
		return self::makeForeignLink( $wiki_id, "User:$user", $text );
	}
	
	static function makeForeignLink( $wiki_id, $page, $text=null ) {
		global $wgUser;
		$sk = $wgUser->getSkin();

		if ( !$text )
			$text=$page;

		$url = self::getForeignURL( $wiki_id, $page );
		if ( $url === false )
			return false;

		return $sk->makeExternalLink( $url, $text );
	}
	
	static function getForeignURL( $wiki_id, $page ) {
		$wiki = WikiMap::getWiki( $wiki_id );
		
		if ($wiki)
			return $wiki->getUrl( $page );
			
		return false;
	}
}

class WikiReference {
	private $mMinor; ///< 'en', 'meta', 'mediawiki', etc
	private $mMajor; ///< 'wiki', 'wiktionary', etc
	private $mServer; ///< server override, 'www.mediawiki.org'
	private $mPath;   ///< path override, '/wiki/$1'

	function __construct( $major, $minor, $server, $path ) {
		$this->mMajor = $major;
		$this->mMinor = $minor;
		$this->mServer = $server;
		$this->mPath = $path;
	}

	function getHostname() {
		$prefixes = array( 'http://', 'https://' );
		foreach ( $prefixes as $prefix ) {
			if ( substr( $this->mServer, 0, strlen( $prefix ) ) ) {
				return substr( $this->mServer, strlen( $prefix ) );
			}
		}
		throw new MWException( "Invalid hostname for wiki {$this->mMinor}.{$this->mMajor}" );
	}

	/**
	 * pretty it up
	 */
	function getDisplayName() {
		$url = $this->getUrl( '' );
		$url = preg_replace( '!^https?://!', '', $url );
		$url = preg_replace( '!/index\.php(\?title=|/)$!', '/', $url );
		$url = preg_replace( '!/wiki/$!', '/', $url );
		$url = preg_replace( '!/$!', '', $url );
		return $url;
	}

	private function getLocalUrl( $page ) {
		// FIXME: this may be generalized...
		return str_replace( '$1', wfUrlEncode( str_replace( ' ', '_', $page ) ), $this->mPath );
	}

	function getUrl( $page ) {
		return
			$this->mServer . 
			$this->getLocalUrl( $page );
	}
}
