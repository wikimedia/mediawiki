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

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\DatabaseDomain;

/**
 * Helper tools for dealing with other locally-hosted wikis.
 */
class WikiMap {

	/**
	 * Get a WikiReference object for $wikiID
	 *
	 * @param string $wikiID Wiki'd id (generally database name)
	 * @return WikiReference|null WikiReference object or null if the wiki was not found
	 */
	public static function getWiki( $wikiID ) {
		$wikiReference = self::getWikiReferenceFromWgConf( $wikiID );
		if ( $wikiReference ) {
			return $wikiReference;
		}

		// Try sites, if $wgConf failed
		return self::getWikiWikiReferenceFromSites( $wikiID );
	}

	/**
	 * @param string $wikiID
	 * @return WikiReference|null WikiReference object or null if the wiki was not found
	 */
	private static function getWikiReferenceFromWgConf( $wikiID ) {
		global $wgConf;

		$wgConf->loadFullData();

		list( $major, $minor ) = $wgConf->siteFromDB( $wikiID );
		if ( $major === null ) {
			return null;
		}
		$server = $wgConf->get( 'wgServer', $wikiID, $major,
			[ 'lang' => $minor, 'site' => $major ] );

		$canonicalServer = $wgConf->get( 'wgCanonicalServer', $wikiID, $major,
			[ 'lang' => $minor, 'site' => $major ] );
		if ( $canonicalServer === false || $canonicalServer === null ) {
			$canonicalServer = $server;
		}

		$path = $wgConf->get( 'wgArticlePath', $wikiID, $major,
			[ 'lang' => $minor, 'site' => $major ] );

		// If we don't have a canonical server or a path containing $1, the
		// WikiReference isn't going to function properly. Just return null in
		// that case.
		if ( !is_string( $canonicalServer ) || !is_string( $path ) || strpos( $path, '$1' ) === false ) {
			return null;
		}

		return new WikiReference( $canonicalServer, $path, $server );
	}

	/**
	 * @param string $wikiID
	 * @return WikiReference|null WikiReference object or null if the wiki was not found
	 */
	private static function getWikiWikiReferenceFromSites( $wikiID ) {
		$siteLookup = MediaWikiServices::getInstance()->getSiteLookup();
		$site = $siteLookup->getSite( $wikiID );

		if ( !$site instanceof MediaWikiSite ) {
			// Abort if not a MediaWikiSite, as this is about Wikis
			return null;
		}

		$urlParts = wfParseUrl( $site->getPageUrl() );
		if ( $urlParts === false || !isset( $urlParts['path'] ) || !isset( $urlParts['host'] ) ) {
			// We can't create a meaningful WikiReference without URLs
			return null;
		}

		// XXX: Check whether path contains a $1?
		$path = $urlParts['path'];
		if ( isset( $urlParts['query'] ) ) {
			$path .= '?' . $urlParts['query'];
		}

		$canonicalServer = $urlParts['scheme'] ?? 'http';
		$canonicalServer .= '://' . $urlParts['host'];

		return new WikiReference( $canonicalServer, $path );
	}

	/**
	 * Convenience to get the wiki's display name
	 *
	 * @todo We can give more info than just the wiki id!
	 * @param string $wikiID Wiki'd id (generally database name)
	 * @return string|int Wiki's name or $wiki_id if the wiki was not found
	 */
	public static function getWikiName( $wikiID ) {
		$wiki = self::getWiki( $wikiID );

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
	 * @param string|null $text Link's text; optional, default to "User:$user"
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
	 * @param string|null $text Link's text; optional, default to $page
	 * @return string|false HTML link or false if the wiki was not found
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
		$wiki = self::getWiki( $wikiID );

		if ( $wiki ) {
			return $wiki->getFullUrl( $page, $fragmentId );
		}

		return false;
	}

	/**
	 * Get canonical server info for all local wikis in the map that have one
	 *
	 * @return array Map of (local wiki ID => map of (url,parts))
	 * @since 1.30
	 */
	public static function getCanonicalServerInfoForAllWikis() {
		$cache = MediaWikiServices::getInstance()->getLocalServerObjectCache();

		return $cache->getWithSetCallback(
			$cache->makeGlobalKey( 'wikimap', 'canonical-urls' ),
			$cache::TTL_DAY,
			function () {
				global $wgLocalDatabases, $wgCanonicalServer;

				$infoMap = [];
				// Make sure at least the current wiki is set, for simple configurations.
				// This also makes it the first in the map, which is useful for common cases.
				$wikiId = self::getWikiIdFromDbDomain( self::getCurrentWikiDbDomain() );
				$infoMap[$wikiId] = [
					'url' => $wgCanonicalServer,
					'parts' => wfParseUrl( $wgCanonicalServer )
				];

				foreach ( $wgLocalDatabases as $wikiId ) {
					$wikiReference = self::getWiki( $wikiId );
					if ( $wikiReference ) {
						$url = $wikiReference->getCanonicalServer();
						$infoMap[$wikiId] = [ 'url' => $url, 'parts' => wfParseUrl( $url ) ];
					}
				}

				return $infoMap;
			}
		);
	}

	/**
	 * @param string $url
	 * @return bool|string Wiki ID or false
	 * @since 1.30
	 */
	public static function getWikiFromUrl( $url ) {
		global $wgCanonicalServer;

		if ( strpos( $url, "$wgCanonicalServer/" ) === 0 ) {
			// Optimisation: Handle the the common case.
			// (Duplicates self::getCanonicalServerInfoForAllWikis)
			return self::getWikiIdFromDbDomain( self::getCurrentWikiDbDomain() );
		}

		$urlPartsCheck = wfParseUrl( $url );
		if ( $urlPartsCheck === false ) {
			return false;
		}

		static $relevantKeys = [ 'host' => 1, 'port' => 1 ];
		$urlPartsCheck = array_intersect_key( $urlPartsCheck, $relevantKeys );

		foreach ( self::getCanonicalServerInfoForAllWikis() as $wikiId => $info ) {
			$urlParts = $info['parts'];
			if ( $urlParts === false ) {
				continue; // sanity
			}

			$urlParts = array_intersect_key( $urlParts, $relevantKeys );
			if ( $urlParts == $urlPartsCheck ) {
				return $wikiId;
			}
		}

		return false;
	}

	/**
	 * Get the wiki ID of a database domain
	 *
	 * This is like DatabaseDomain::getId() without encoding (for legacy reasons) and
	 * without the schema if it is the generic installer default of "mediawiki"
	 *
	 * @see $wgDBmwschema
	 * @see PostgresInstaller
	 *
	 * @param string|DatabaseDomain $domain
	 * @return string
	 * @since 1.31
	 */
	public static function getWikiIdFromDbDomain( $domain ) {
		$domain = DatabaseDomain::newFromId( $domain );
		// Since the schema was not always part of the wiki ID, try to maintain backwards
		// compatibility with some common cases. Assume that if the DB domain schema is just
		// the installer default then it is probably the case that the schema is the same for
		// all wikis in the farm. Historically, any wiki farm had to make the database/prefix
		// combination unique per wiki. Ommit the schema if it does not seem wiki specific.
		if ( !in_array( $domain->getSchema(), [ null, 'mediawiki' ], true ) ) {
			// This means a site admin may have specifically taylored the schemas.
			// Domain IDs might use the form <DB>-<project>- or <DB>-<project>-<language>_,
			// meaning that the schema portion must be accounted for to disambiguate wikis.
			return "{$domain->getDatabase()}-{$domain->getSchema()}-{$domain->getTablePrefix()}";
		}
		// Note that if this wiki ID is passed a a domain ID to LoadBalancer, then it can
		// handle the schema by assuming the generic "mediawiki" schema if needed.
		return strlen( $domain->getTablePrefix() )
			? "{$domain->getDatabase()}-{$domain->getTablePrefix()}"
			: (string)$domain->getDatabase();
	}

	/**
	 * @return DatabaseDomain Database domain of the current wiki
	 * @since 1.33
	 */
	public static function getCurrentWikiDbDomain() {
		global $wgDBname, $wgDBmwschema, $wgDBprefix;
		// Avoid invoking LBFactory to avoid any chance of recursion
		return new DatabaseDomain( $wgDBname, $wgDBmwschema, (string)$wgDBprefix );
	}

	/**
	 * @param DatabaseDomain|string $domain
	 * @return bool Whether $domain matches the DB domain of the current wiki
	 * @since 1.33
	 */
	public static function isCurrentWikiDbDomain( $domain ) {
		return self::getCurrentWikiDbDomain()->equals( $domain );
	}

	/**
	 * @param string $wikiId
	 * @return bool Whether $wikiId matches the wiki ID of the current wiki
	 * @since 1.33
	 */
	public static function isCurrentWikiId( $wikiId ) {
		return ( self::getWikiIdFromDbDomain( self::getCurrentWikiDbDomain() ) === $wikiId );
	}
}
