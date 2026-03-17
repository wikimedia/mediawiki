<?php
/**
 * Copyright © 2004 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Feed;

use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * Generate an RSS feed.
 *
 * @ingroup Feed
 */
class RSSFeed extends ChannelFeed {

	/**
	 * Format a date given a timestamp. If a timestamp is not given, nothing is returned
	 *
	 * @param string|int|null $ts Timestamp
	 * @return string|null Date string
	 */
	private function formatTime( $ts ) {
		if ( $ts ) {
			return gmdate( 'D, d M Y H:i:s \G\M\T', (int)wfTimestamp( TS::UNIX, $ts ) );
		}
		return null;
	}

	/**
	 * Output an RSS 2.0 header
	 * @inheritDoc
	 */
	public function outputHeader( $output ): void {
		$this->outputXmlHeader( $output );
		// Manually escaping rather than letting Mustache do it because Mustache
		// uses htmlentities, which does not work with XML
		$templateParams = [
			'title' => $this->getTitle(),
			'url' => $this->xmlEncode(
				$this->urlUtils->expand( $this->getUrlUnescaped(), PROTO_CURRENT ) ?? ''
			),
			'description' => $this->getDescription(),
			'language' => $this->xmlEncode( $this->getLanguage() ),
			'version' => $this->xmlEncode( MW_VERSION ),
			'timestamp' => $this->xmlEncode( $this->formatTime( wfTimestampNow() ) )
		];
		print $this->templateParser->processTemplate( 'RSSHeader', $templateParams );
	}

	/**
	 * Output an RSS 2.0 item
	 * @inheritDoc
	 */
	public function outputItem( FeedItem $item, $output ): void {
		// Manually escaping rather than letting Mustache do it because Mustache
		// uses htmlentities, which does not work with XML
		$templateParams = [
			"title" => $item->getTitle(),
			"url" => $this->xmlEncode(
				$this->urlUtils->expand( $item->getUrlUnescaped(), PROTO_CURRENT ) ?? ''
			),
			"permalink" => $item->rssIsPermalink,
			"uniqueID" => $item->getUniqueID(),
			"description" => $item->getDescription(),
			"date" => $this->xmlEncodeNullable( $this->formatTime( $item->getDate() ) ),
			"author" => $item->getAuthor()
		];
		$comments = $item->getCommentsUnescaped();
		if ( $comments ) {
			$commentsEscaped = $this->xmlEncode(
				$this->urlUtils->expand( $comments, PROTO_CURRENT ) ?? ''
			);
			$templateParams["comments"] = $commentsEscaped;
		}
		print $this->templateParser->processTemplate( 'RSSItem', $templateParams );
	}

	/**
	 * Output an RSS 2.0 footer
	 * @inheritDoc
	 */
	public function outputFooter( $output ): void {
		print "</channel></rss>";
	}
}
