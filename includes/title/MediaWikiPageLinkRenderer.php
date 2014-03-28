<?php
/**
 * A service for generating links from page titles
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
 * @license GPL 2+
 * @author Daniel Kinzler
 */

/**
 * A service for generating links from page titles.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 */
class MediaWikiPageLinkRenderer implements PageLinkRenderer {

	/**
	 * @var TitleFormatter
	 */
	protected $formatter;

	/**
	 * @var string
	 */
	protected $baseUrl;

	/**
	 * @note $formatter and $baseUrl are currently not used for generating links,
	 * since we still rely on the Linker class to generate the actual HTML.
	 * Once this is reversed so that  Linker becomes a legacy interface to
	 * HtmlPageLinkRenderer, we will be using them, so it seems prudent to
	 * already declare the dependency and inject them.
	 *
	 * @param TitleFormatter $formatter formatter for generating the target title string
	 * @param string $baseUrl (currently unused, pending refactoring of Linker).
	 *        Defaults to $wgArticlePath.
	 */
	public function __construct( TitleFormatter $formatter, $baseUrl = null ) {
		if ( $baseUrl === null ) {
			$baseUrl = $GLOBALS['wgArticlePath'];
		}

		$this->formatter = $formatter;
		$this->baseUrl = $baseUrl;
	}

	/**
	 * Returns the (partial) URL for the given page (including any section identifier).
	 *
	 * @param TitleValue $page The link's target
	 * @param array $params any additional URL parameters.
	 *
	 * @return string
	 */
	public function getPageUrl( TitleValue $page, $params = array() ) {
		//TODO: move the code from Linker::linkUrl here!
		//The below is just a rough estimation!

		$name = $this->formatter->getPrefixedText( $page );
		$name = str_replace( ' ', '_', $name );
		$name = wfUrlencode( $name );

		$url = $this->baseUrl . $name;

		if ( $params ) {
			$separator = ( strpos( $url, '?' ) ) ? '&' : '?';
			$url .= $separator . wfArrayToCgi( $params );
		}

		$fragment = $page->getFragment();
		if ( $fragment !== '' ) {
			$url = $url . '#' . wfUrlencode( $fragment );
		}

		return $url;
	}

	/**
	 * Returns an HTML link to the given page, using the given surface text.
	 *
	 * @param TitleValue $page The link's target
	 * @param string $text The link's surface text (will be derived from $page if not given).
	 *
	 * @return string
	 */
	public function renderHtmlLink( TitleValue $page, $text = null ) {
		if ( $text === null ) {
			$text = $this->formatter->getFullText( $page );
		}

		// TODO: move the logic implemented by Linker here,
		// using $this->formatter and $this->baseUrl, and
		// re-implement Linker to use a HtmlPageLinkRenderer.
		$title = Title::newFromTitleValue( $page );
		$link = Linker::link( $title, htmlspecialchars( $text ) );
		return $link;
	}

	/**
	 * Returns a wikitext link to the given page, using the given surface text.
	 *
	 * @param TitleValue $page The link's target
	 * @param string $text The link's surface text (will be derived from $page if not given).
	 *
	 * @return string
	 */
	public function renderWikitextLink( TitleValue $page, $text = null ) {
		if ( $text === null ) {
			$text = $this->formatter->getFullText( $page );
		}

		$name = $this->formatter->getFullText( $page );

		return '[[:' . $name . '|' . wfEscapeWikiText( $text ) . ']]';
	}
}
