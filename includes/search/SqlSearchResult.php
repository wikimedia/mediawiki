<?php

/**
 * Search engine result issued from SearchData search engines.
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
 * @ingroup Search
 */

class SqlSearchResult extends RevisionSearchResult {
	/** @var string[] */
	private $terms;

	/**
	 * SqlSearchResult constructor.
	 * @param Title $title
	 * @param string[] $terms list of parsed terms
	 */
	public function __construct( Title $title, array $terms ) {
		parent::__construct( $title );
		$this->terms = $terms;
	}

	/**
	 * return string[]
	 */
	public function getTermMatches(): array {
		return $this->terms;
	}

	/**
	 * @param array $terms Terms to highlight (this parameter is deprecated)
	 * @return string Highlighted text snippet, null (and not '') if not supported
	 */
	function getTextSnippet( $terms = [] ) {
		global $wgAdvancedSearchHighlighting;
		$this->initText();

		$h = new SearchHighlighter();
		if ( count( $this->terms ) > 0 ) {
			if ( $wgAdvancedSearchHighlighting ) {
				return $h->highlightText( $this->mText, $this->terms );
			} else {
				return $h->highlightSimple( $this->mText, $this->terms );
			}
		} else {
			return $h->highlightNone( $this->mText );
		}
	}

}
