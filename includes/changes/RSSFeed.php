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
 * Generate an RSS feed.
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
