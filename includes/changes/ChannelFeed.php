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
	 * @param string|Title $title Feed's title
	 * @param string $description
	 * @param string $url URL uniquely designating the feed.
	 * @param string $date Feed's date
	 * @param string $author Author's user name
	 * @param string $comments
	 *
	 */
	public function __construct(
		$title, $description, $url, $date = '', $author = '', $comments = ''
	) {
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
		$mimeAnalyzer = MediaWiki\MediaWikiServices::getInstance()->getMimeAnalyzer();
		$ext = $mimeAnalyzer->getExtensionFromMimeTypeOrNull( $mimetype ) ?? 'xml';
		header( "Content-Disposition: inline; filename=\"feed.{$ext}\"" );

		if ( $wgVaryOnXFP ) {
			$wgOut->addVaryHeader( 'X-Forwarded-Proto' );
		}
		$wgOut->sendCacheControl();
	}

	/**
	 * Return an internet media type to be sent in the headers.
	 *
	 * @stable to override
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
