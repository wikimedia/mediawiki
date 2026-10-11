<?php

/**
 * Copyright © 2004 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Feed;

use MediaWiki\Html\TemplateParser;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Request\WebRequest;
use Wikimedia\Http\HttpHeader;

/**
 * Class to support the outputting of syndication feeds in Atom and RSS format.
 *
 * @stable to extend
 * @ingroup Feed
 */
abstract class ChannelFeed extends FeedItem {

	/** @var TemplateParser */
	protected $templateParser;

	/**
	 * @stable to call
	 *
	 * @param string $title Feed's title
	 * @param string $description
	 * @param string $url URL uniquely designating the feed.
	 * @param string $date Feed's date
	 * @param string $author Author's user name
	 * @param string $comments
	 */
	public function __construct(
		$title, $description, $url, $date = '', $author = '', $comments = ''
	) {
		parent::__construct( $title, $description, $url, $date, $author, $comments );
		$this->templateParser = new TemplateParser();
	}

	/**
	 * Generate Header of the feed
	 *
	 * Example: <code>print "<feed>";</code>
	 * @param OutputPage $output
	 * @stable to override
	 * @since 1.46
	 */
	abstract public function outputHeader( $output ): void;

	/**
	 * Generate an item
	 *
	 * Example: <code>print "<item>...</item>";</code>
	 * @param FeedItem $item
	 * @param OutputPage $output
	 * @stable to override
	 * @since 1.46
	 */
	abstract public function outputItem( FeedItem $item, $output ): void;

	/**
	 * Generate Footer of the feed
	 *
	 * Example: <code>print "</feed>";</code>
	 * @param OutputPage $output
	 * @stable to override
	 * @since 1.46
	 */
	abstract public function outputFooter( $output ): void;

	/**
	 * Setup and send HTTP headers. Don't send any content;
	 * content might end up being cached and re-sent with
	 * these same headers later.
	 *
	 * @param OutputPage $output
	 * @since 1.46
	 */
	public function sendHttpHeaders( $output ): void {
		$varyOnXFP = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::VaryOnXFP );
		# We take over from $wgOut, excepting its cache header info
		$output->disable();
		$mimetype = $this->contentType( $output->getRequest() );
		HttpHeader::send( "Content-type: $mimetype; charset=UTF-8" );
		// @todo Maybe set a CSP header here at some point as defense in depth.
		// need to figure out how that interacts with browser display of article
		// snippets.

		// Set a sensible filename
		$mimeAnalyzer = MediaWikiServices::getInstance()->getMimeAnalyzer();
		$ext = $mimeAnalyzer->getExtensionFromMimeTypeOrNull( $mimetype ) ?? 'xml';
		HttpHeader::send( "Content-Disposition: inline; filename=\"feed.{$ext}\"" );

		if ( $varyOnXFP ) {
			$output->addVaryHeader( 'X-Forwarded-Proto' );
		}
		$output->sendCacheControl();
	}

	/**
	 * Return an internet media type to be sent in the headers.
	 *
	 * @param WebRequest $request
	 */
	private function contentType( $request ): string {
		$ctype = $request->getVal( 'ctype', 'application/xml' );
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
	 *
	 * @param OutputPage $output
	 * @since 1.46
	 */
	protected function outputXmlHeader( $output ): void {
		$this->sendHttpHeaders( $output );
		echo '<?xml version="1.0"?>' . "\n";
	}
}
