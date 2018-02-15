<?php
/**
 * Interwiki table entry.
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

/**
 * Value object for representing interwiki records.
 */
class Interwiki {

	/** @var string The interwiki prefix, (e.g. "Meatball", or the language prefix "de") */
	protected $mPrefix;

	/** @var string The URL of the wiki, with "$1" as a placeholder for an article name. */
	protected $mURL;

	/** @var string The URL of the file api.php */
	protected $mAPI;

	/** @var string The name of the database (for a connection to be established
	 *    with wfGetLB( 'wikiid' ))
	 */
	protected $mWikiID;

	/** @var bool Whether the wiki is in this project */
	protected $mLocal;

	/** @var bool Whether interwiki transclusions are allowed */
	protected $mTrans;

	public function __construct( $prefix = null, $url = '', $api = '', $wikiId = '', $local = 0,
		$trans = 0
	) {
		$this->mPrefix = $prefix;
		$this->mURL = $url;
		$this->mAPI = $api;
		$this->mWikiID = $wikiId;
		$this->mLocal = (bool)$local;
		$this->mTrans = (bool)$trans;
	}

	/**
	 * Check whether an interwiki prefix exists
	 *
	 * @deprecated since 1.28, use InterwikiLookup instead
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return bool Whether it exists
	 */
	public static function isValidInterwiki( $prefix ) {
		return MediaWikiServices::getInstance()->getInterwikiLookup()->isValidInterwiki( $prefix );
	}

	/**
	 * Fetch an Interwiki object
	 *
	 * @deprecated since 1.28, use InterwikiLookup instead
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return Interwiki|null|bool
	 */
	public static function fetch( $prefix ) {
		return MediaWikiServices::getInstance()->getInterwikiLookup()->fetch( $prefix );
	}

	/**
	 * Purge the cache (local and persistent) for an interwiki prefix.
	 *
	 * @param string $prefix
	 * @since 1.26
	 */
	public static function invalidateCache( $prefix ) {
		MediaWikiServices::getInstance()->getInterwikiLookup()->invalidateCache( $prefix );
	}

	/**
	 * Returns all interwiki prefix definitions.
	 *
	 * @deprecated since 1.28, unused. Use InterwikiLookup instead.
	 *
	 * @param string|null $local If set, limits output to local/non-local interwikis
	 * @return array[] List of interwiki rows
	 * @since 1.19
	 */
	public static function getAllPrefixes( $local = null ) {
		return MediaWikiServices::getInstance()->getInterwikiLookup()->getAllPrefixes( $local );
	}

	/**
	 * Get the URL for a particular title (or with $1 if no title given)
	 *
	 * @param string $title What text to put for the article name
	 * @return string The URL
	 * @note Prior to 1.19 The getURL with an argument was broken.
	 *       If you if you use this arg in an extension that supports MW earlier
	 *       than 1.19 please wfUrlencode and substitute $1 on your own.
	 */
	public function getURL( $title = null ) {
		$url = $this->mURL;
		if ( $title !== null ) {
			$url = str_replace( "$1", wfUrlencode( $title ), $url );
		}

		return $url;
	}

	/**
	 * Get the API URL for this wiki
	 *
	 * @return string The URL
	 */
	public function getAPI() {
		return $this->mAPI;
	}

	/**
	 * Get the DB name for this wiki
	 *
	 * @return string The DB name
	 */
	public function getWikiID() {
		return $this->mWikiID;
	}

	/**
	 * Is this a local link from a sister project, or is
	 * it something outside, like Google
	 *
	 * @return bool
	 */
	public function isLocal() {
		return $this->mLocal;
	}

	/**
	 * Can pages from this wiki be transcluded?
	 * Still requires $wgEnableScaryTransclusion
	 *
	 * @return bool
	 */
	public function isTranscludable() {
		return $this->mTrans;
	}

	/**
	 * Get the name for the interwiki site
	 *
	 * @return string
	 */
	public function getName() {
		$msg = wfMessage( 'interwiki-name-' . $this->mPrefix )->inContentLanguage();

		return !$msg->exists() ? '' : $msg->text();
	}

	/**
	 * Get a description for this interwiki
	 *
	 * @return string
	 */
	public function getDescription() {
		$msg = wfMessage( 'interwiki-desc-' . $this->mPrefix )->inContentLanguage();

		return !$msg->exists() ? '' : $msg->text();
	}

}
