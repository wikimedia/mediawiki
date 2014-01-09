<?php
/**
 * A service for generating HTML links for %MediaWiki pages.
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
 * A service for generating HTML links to wiki pages.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 */
class HtmlPageLinkRenderer implements PageLinkRenderer {

	/**
	 * @var TitleFormatter
	 */
	protected $formatter;

	/**
	 * @var string
	 */
	protected $baseUrl;

	/**
	 * @param TitleFormatter $formatter formatter for generating the target title string
	 * @param string $baseUrl (currently unused, pending refactoring of Linker)
	 */
	public function __construct( TitleFormatter $formatter, $baseUrl = '' ) {
		$this->formatter = $formatter;
		$this->baseUrl = $baseUrl;
	}

	/**
	 * @see PageLinkRenderer::getTargetFormat()
	 *
	 * @return string 'text/html'
	 */
	public function getTargetFormat() {
		return 'text/html';
	}

	/**
	 * @see PageLinkRenderer::renderLink()
	 *
	 * @param TitleValue $page The link's target
	 * @param string $text The link's surface text (plain text, no html allowed)
	 *
	 * @return string HTML code of the link
	 */
	public function renderLink( TitleValue $page, $text ) {
		$name = $this->formatter->format( $page );

		// TODO: move the logic implemented by Linker here, and
		// re-implement Linker to use a HtmlPageLinkRenderer.
		$link = Linker::link( Title::newFromText( $name ), htmlspecialchars( $text ) );
		return $link;
	}
}
 