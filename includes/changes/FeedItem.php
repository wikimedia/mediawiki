<?php
/**
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
 * A base class for outputting syndication feeds (e.g. RSS and other formats).
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
	/** #@- */
}
