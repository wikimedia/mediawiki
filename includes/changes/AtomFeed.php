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
 * Generate an Atom feed.
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
