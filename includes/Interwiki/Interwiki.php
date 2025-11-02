<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Interwiki;

/**
 * An interwiki record value object.
 *
 * By default, these represent a row in the `interwiki` database table.
 * See @ref \MediaWiki\Interwiki\ClassicInterwikiLookup for where this is used.
 */
class Interwiki {

	/** @var string The interwiki prefix, (e.g. "Meatball", or the language prefix "de") */
	protected $mPrefix;

	/** @var string The article path URL of the wiki, with "$1" as a placeholder for an article name. */
	protected $mURL;

	/** @var string The URL to the api.php entry point of the wiki. */
	protected $mAPI;

	/** @var string The name of the database (for a connection to be established
	 *    with LBFactory::getMainLB( 'domainId' ))
	 */
	protected $mWikiID;

	/** @var bool Whether the wiki is in this project */
	protected $mLocal;

	/** @var bool Whether interwiki transclusions are allowed */
	protected $mTrans;

	/**
	 * @param string|null $prefix
	 * @param string $url
	 * @param string $api
	 * @param string $wikiId
	 * @param bool|int $local
	 * @param bool|int $trans
	 */
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
	 * Get the URL for a particular title (or with $1 if no title given)
	 *
	 * @param string|null $title What text to put for the article name
	 * @return string The URL
	 * @note Prior to 1.19 The getURL with an argument was broken.
	 *       If you use this arg in an extension that supports MW earlier
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

/** @deprecated class alias since 1.44 */
class_alias( Interwiki::class, 'Interwiki' );
