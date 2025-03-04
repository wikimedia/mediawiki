<?php

/**
 * Copyright © 2004 Brooke Vibber <bvibber@wikimedia.org>
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

namespace MediaWiki\Feed;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

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
	private function formatTime( $timestamp ) {
		if ( $timestamp ) {
			// need to use RFC 822 time format at least for rss2.0
			return gmdate( 'Y-m-d\TH:i:s', (int)wfTimestamp( TS_UNIX, $timestamp ) );
		}
		return null;
	}

	/**
	 * Outputs a basic header for Atom 1.0 feeds.
	 */
	public function outHeader() {
		$this->outXmlHeader();
		// Manually escaping rather than letting Mustache do it because Mustache
		// uses htmlentities, which does not work with XML
		$templateParams = [
			'language' => $this->xmlEncode( $this->getLanguage() ),
			// Atom 1.0 requires a unique, opaque IRI as a unique identifier
			// for every feed we create. For now just use the URL, but who
			// can tell if that's right? If we put options on the feed, do we
			// have to change the id? Maybe? Maybe not.
			'feedID' => $this->getSelfUrl(),
			'title' => $this->getTitle(),
			'url' => $this->xmlEncode(
				$this->urlUtils->expand( $this->getUrlUnescaped(), PROTO_CURRENT ) ?? ''
			),
			'selfUrl' => $this->getSelfUrl(),
			'timestamp' => $this->xmlEncode( $this->formatTime( wfTimestampNow() ) ),
			'description' => $this->getDescription(),
			'version' => $this->xmlEncode( MW_VERSION ),
		];
		print $this->templateParser->processTemplate( 'AtomHeader', $templateParams );
	}

	/**
	 * Atom 1.0 requests a self-reference to the feed.
	 *
	 * @return string
	 */
	private function getSelfUrl() {
		global $wgRequest;
		return htmlspecialchars( $wgRequest->getFullRequestURL() );
	}

	/**
	 * Output a given item.
	 *
	 * @param FeedItem $item
	 */
	public function outItem( $item ) {
		$mimeType = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::MimeType );
		// Manually escaping rather than letting Mustache do it because Mustache
		// uses htmlentities, which does not work with XML
		$templateParams = [
			"uniqueID" => $item->getUniqueID(),
			"title" => $item->getTitle(),
			"mimeType" => $this->xmlEncode( $mimeType ),
			"url" => $this->xmlEncode(
				$this->urlUtils->expand( $item->getUrlUnescaped(), PROTO_CURRENT ) ?? ''
			),
			"date" => $this->xmlEncodeNullable( $this->formatTime( $item->getDate() ) ),
			"description" => $item->getDescription(),
			"author" => $item->getAuthor()
		];
		print $this->templateParser->processTemplate( 'AtomItem', $templateParams );
	}

	/**
	 * Outputs the footer for Atom 1.0 feed (basically '\</feed\>').
	 */
	public function outFooter() {
		print "</feed>";
	}
}

/** @deprecated class alias since 1.40 */
class_alias( AtomFeed::class, 'AtomFeed' );
