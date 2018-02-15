<?php
/**
 * Basic support for outputting syndication feeds in RSS, other formats.
 *
 * Contain a feed class as well as classes to build rss / atom ... feeds
 * Available feeds are defined in Defines.php
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 * @defgroup Feed Feed
 */

/**
 * A base class for basic support for outputting syndication feeds in RSS and other formats.
 *
 * @ingroup Feed
 */
class FeedItem {
	/** @var Title */
	public $title;

	public $description;

	public $url;

	public $date;

	public $author;

	public $uniqueId;

	public $comments;

	public $rssIsPermalink = false;

	/**
	 * @param string|Title $title Item's title
	 * @param string $description
	 * @param string $url URL uniquely designating the item.
	 * @param string $date Item's date
	 * @param string $author Author's user name
	 * @param string $comments
	 */
	function __construct( $title, $description, $url, $date = '', $author = '', $comments = '' ) {
		$this->title = $title;
		$this->description = $description;
		$this->url = $url;
		$this->uniqueId = $url;
		$this->date = $date;
		$this->author = $author;
		$this->comments = $comments;
	}

	/**
	 * Encode $string so that it can be safely embedded in a XML document
	 *
	 * @param string $string String to encode
	 * @return string
	 */
	public function xmlEncode( $string ) {
		$string = str_replace( "\r\n", "\n", $string );
		$string = preg_replace( '/[\x00-\x08\x0b\x0c\x0e-\x1f]/', '', $string );
		return htmlspecialchars( $string );
	}

	/**
	 * Get the unique id of this item; already xml-encoded
	 * @return string
	 */
	public function getUniqueID() {
		$id = $this->getUniqueIdUnescaped();
		if ( $id ) {
			return $this->xmlEncode( $id );
		}
	}

	/**
	 * Get the unique id of this item, without any escaping
	 * @return string
	 */
	public function getUniqueIdUnescaped() {
		if ( $this->uniqueId ) {
			return wfExpandUrl( $this->uniqueId, PROTO_CURRENT );
		}
	}

	/**
	 * Set the unique id of an item
	 *
	 * @param string $uniqueId Unique id for the item
	 * @param bool $rssIsPermalink Set to true if the guid (unique id) is a permalink (RSS feeds only)
	 */
	public function setUniqueId( $uniqueId, $rssIsPermalink = false ) {
		$this->uniqueId = $uniqueId;
		$this->rssIsPermalink = $rssIsPermalink;
	}

	/**
	 * Get the title of this item; already xml-encoded
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->xmlEncode( $this->title );
	}

	/**
	 * Get the URL of this item; already xml-encoded
	 *
	 * @return string
	 */
	public function getUrl() {
		return $this->xmlEncode( $this->url );
	}

	/** Get the URL of this item without any escaping
	 *
	 * @return string
	 */
	public function getUrlUnescaped() {
		return $this->url;
	}

	/**
	 * Get the description of this item; already xml-encoded
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->xmlEncode( $this->description );
	}

	/**
	 * Get the description of this item without any escaping
	 *
	 * @return string
	 */
	public function getDescriptionUnescaped() {
		return $this->description;
	}

	/**
	 * Get the language of this item
	 *
	 * @return string
	 */
	public function getLanguage() {
		global $wgLanguageCode;
		return LanguageCode::bcp47( $wgLanguageCode );
	}

	/**
	 * Get the date of this item
	 *
	 * @return string
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Get the author of this item; already xml-encoded
	 *
	 * @return string
	 */
	public function getAuthor() {
		return $this->xmlEncode( $this->author );
	}

	/**
	 * Get the author of this item without any escaping
	 *
	 * @return string
	 */
	public function getAuthorUnescaped() {
		return $this->author;
	}

	/**
	 * Get the comment of this item; already xml-encoded
	 *
	 * @return string
	 */
	public function getComments() {
		return $this->xmlEncode( $this->comments );
	}

	/**
	 * Get the comment of this item without any escaping
	 *
	 * @return string
	 */
	public function getCommentsUnescaped() {
		return $this->comments;
	}

	/**
	 * Quickie hack... strip out wikilinks to more legible form from the comment.
	 *
	 * @param string $text Wikitext
	 * @return string
	 */
	public static function stripComment( $text ) {
		return preg_replace( '/\[\[([^]]*\|)?([^]]+)\]\]/', '\2', $text );
	}
	/**#@-*/
}

/**
 * Class to support the outputting of syndication feeds in Atom and RSS format.
 *
 * @ingroup Feed
 */
abstract class ChannelFeed extends FeedItem {

	/** @var TemplateParser */
	protected $templateParser;

	/**
	 * @param string|Title $title Feed's title
	 * @param string $description
	 * @param string $url URL uniquely designating the feed.
	 * @param string $date Feed's date
	 * @param string $author Author's user name
	 * @param string $comments
	 */
	function __construct( $title, $description, $url, $date = '', $author = '', $comments = '' ) {
		parent::__construct( $title, $description, $url, $date, $author, $comments );
		$this->templateParser = new TemplateParser();
	}

	/**
	 * Generate Header of the feed
	 * @par Example:
	 * @code
	 * print "<feed>";
	 * @endcode
	 */
	abstract public function outHeader();

	/**
	 * Generate an item
	 * @par Example:
	 * @code
	 * print "<item>...</item>";
	 * @endcode
	 * @param FeedItem $item
	 */
	abstract public function outItem( $item );

	/**
	 * Generate Footer of the feed
	 * @par Example:
	 * @code
	 * print "</feed>";
	 * @endcode
	 */
	abstract public function outFooter();

	/**
	 * Setup and send HTTP headers. Don't send any content;
	 * content might end up being cached and re-sent with
	 * these same headers later.
	 *
	 * This should be called from the outHeader() method,
	 * but can also be called separately.
	 */
	public function httpHeaders() {
		global $wgOut, $wgVaryOnXFP;

		# We take over from $wgOut, excepting its cache header info
		$wgOut->disable();
		$mimetype = $this->contentType();
		header( "Content-type: $mimetype; charset=UTF-8" );

		// Set a sane filename
		$exts = MediaWiki\MediaWikiServices::getInstance()->getMimeAnalyzer()
			->getExtensionsForType( $mimetype );
		$ext = $exts ? strtok( $exts, ' ' ) : 'xml';
		header( "Content-Disposition: inline; filename=\"feed.{$ext}\"" );

		if ( $wgVaryOnXFP ) {
			$wgOut->addVaryHeader( 'X-Forwarded-Proto' );
		}
		$wgOut->sendCacheControl();
	}

	/**
	 * Return an internet media type to be sent in the headers.
	 *
	 * @return string
	 */
	private function contentType() {
		global $wgRequest;

		$ctype = $wgRequest->getVal( 'ctype', 'application/xml' );
		$allowedctypes = [
			'application/xml',
			'text/xml',
			'application/rss+xml',
			'application/atom+xml'
		];

		return ( in_array( $ctype, $allowedctypes ) ? $ctype : 'application/xml' );
	}

	/**
	 * Output the initial XML headers.
	 */
	protected function outXmlHeader() {
		$this->httpHeaders();
		echo '<?xml version="1.0"?>' . "\n";
	}
}

/**
 * Generate a RSS feed
 *
 * @ingroup Feed
 */
class RSSFeed extends ChannelFeed {

	/**
	 * Format a date given a timestamp. If a timestamp is not given, nothing is returned
	 *
	 * @param int|null $ts Timestamp
	 * @return string|null Date string
	 */
	function formatTime( $ts ) {
		if ( $ts ) {
			return gmdate( 'D, d M Y H:i:s \G\M\T', wfTimestamp( TS_UNIX, $ts ) );
		}
	}

	/**
	 * Output an RSS 2.0 header
	 */
	function outHeader() {
		global $wgVersion;

		$this->outXmlHeader();
		// Manually escaping rather than letting Mustache do it because Mustache
		// uses htmlentities, which does not work with XML
		$templateParams = [
			'title' => $this->getTitle(),
			'url' => $this->xmlEncode( wfExpandUrl( $this->getUrlUnescaped(), PROTO_CURRENT ) ),
			'description' => $this->getDescription(),
			'language' => $this->xmlEncode( $this->getLanguage() ),
			'version' => $this->xmlEncode( $wgVersion ),
			'timestamp' => $this->xmlEncode( $this->formatTime( wfTimestampNow() ) )
		];
		print $this->templateParser->processTemplate( 'RSSHeader', $templateParams );
	}

	/**
	 * Output an RSS 2.0 item
	 * @param FeedItem $item Item to be output
	 */
	function outItem( $item ) {
		// Manually escaping rather than letting Mustache do it because Mustache
		// uses htmlentities, which does not work with XML
		$templateParams = [
			"title" => $item->getTitle(),
			"url" => $this->xmlEncode( wfExpandUrl( $item->getUrlUnescaped(), PROTO_CURRENT ) ),
			"permalink" => $item->rssIsPermalink,
			"uniqueID" => $item->getUniqueID(),
			"description" => $item->getDescription(),
			"date" => $this->xmlEncode( $this->formatTime( $item->getDate() ) ),
			"author" => $item->getAuthor()
		];
		$comments = $item->getCommentsUnescaped();
		if ( $comments ) {
			$commentsEscaped = $this->xmlEncode( wfExpandUrl( $comments, PROTO_CURRENT ) );
			$templateParams["comments"] = $commentsEscaped;
		}
		print $this->templateParser->processTemplate( 'RSSItem', $templateParams );
	}

	/**
	 * Output an RSS 2.0 footer
	 */
	function outFooter() {
		print "</channel></rss>";
	}
}

/**
 * Generate an Atom feed
 *
 * @ingroup Feed
 */
class AtomFeed extends ChannelFeed {
	/**
	 * Format a date given timestamp, if one is given.
	 *
	 * @param string|int|null $timestamp
	 * @return string|null
	 */
	function formatTime( $timestamp ) {
		if ( $timestamp ) {
			// need to use RFC 822 time format at least for rss2.0
			return gmdate( 'Y-m-d\TH:i:s', wfTimestamp( TS_UNIX, $timestamp ) );
		}
	}

	/**
	 * Outputs a basic header for Atom 1.0 feeds.
	 */
	function outHeader() {
		global $wgVersion;
		$this->outXmlHeader();
		// Manually escaping rather than letting Mustache do it because Mustache
		// uses htmlentities, which does not work with XML
		$templateParams = [
			'language' => $this->xmlEncode( $this->getLanguage() ),
			'feedID' => $this->getFeedId(),
			'title' => $this->getTitle(),
			'url' => $this->xmlEncode( wfExpandUrl( $this->getUrlUnescaped(), PROTO_CURRENT ) ),
			'selfUrl' => $this->getSelfUrl(),
			'timestamp' => $this->xmlEncode( $this->formatTime( wfTimestampNow() ) ),
			'description' => $this->getDescription(),
			'version' => $this->xmlEncode( $wgVersion ),
		];
		print $this->templateParser->processTemplate( 'AtomHeader', $templateParams );
	}

	/**
	 * Atom 1.0 requires a unique, opaque IRI as a unique identifier
	 * for every feed we create. For now just use the URL, but who
	 * can tell if that's right? If we put options on the feed, do we
	 * have to change the id? Maybe? Maybe not.
	 *
	 * @return string
	 */
	private function getFeedId() {
		return $this->getSelfUrl();
	}

	/**
	 * Atom 1.0 requests a self-reference to the feed.
	 * @return string
	 */
	private function getSelfUrl() {
		global $wgRequest;
		return htmlspecialchars( $wgRequest->getFullRequestURL() );
	}

	/**
	 * Output a given item.
	 * @param FeedItem $item
	 */
	function outItem( $item ) {
		global $wgMimeType;
		// Manually escaping rather than letting Mustache do it because Mustache
		// uses htmlentities, which does not work with XML
		$templateParams = [
			"uniqueID" => $item->getUniqueID(),
			"title" => $item->getTitle(),
			"mimeType" => $this->xmlEncode( $wgMimeType ),
			"url" => $this->xmlEncode( wfExpandUrl( $item->getUrlUnescaped(), PROTO_CURRENT ) ),
			"date" => $this->xmlEncode( $this->formatTime( $item->getDate() ) ),
			"description" => $item->getDescription(),
			"author" => $item->getAuthor()
		];
		print $this->templateParser->processTemplate( 'AtomItem', $templateParams );
	}

	/**
	 * Outputs the footer for Atom 1.0 feed (basically '\</feed\>').
	 */
	function outFooter() {
		print "</feed>";
	}
}
