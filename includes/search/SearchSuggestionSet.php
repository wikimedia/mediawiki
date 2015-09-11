<?php
/**
 * Search suggestion sets
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

/**
 * A set of SearchSuggestions
 * @ingroup Search
 * @since 1.26
 */
class SearchSuggestionSet implements Iterator {
	/**
	 * @var array of SearchSuggestion
	 */
	private $suggestions;

	/**
	 * @var integer iterator position
	 */
	private $position = 0;

	/**
	 * @param $suggestions array of SearchSuggestion
	 */
	public function __construct( $suggestions ) {
		$this->suggestions = $suggestions;
	}


	/** {@inheritDoc} */
	public function rewind() {
		$this->position = 0;
	}

	/** {@inheritDoc} */
	public function current() {
		return $this->suggestions[$this->position];
	}

	/** {@inheritDoc} */
	public function key() {
		return $this->position;
	}

	/** {@inheritDoc} */
	public function next() {
		++$this->position;
	}

	/** {@inheritDoc} */
	public function valid() {
		return isset($this->suggestions[$this->position]);
	}
}

/**
 * A search suggestion.
 * @ingroup Search
 * @since 1.26
 */
interface SearchSuggestion {
	/**
	 * The suggestion text
	 * @return string
	 */
	public function text();

	/**
	 * Title object in the case this suggestion is based on a title.
	 * May return null if the suggestion is not a Title.
	 * @return Title|null
	 */
	public function getSuggestedTitle();
}

/**
 * A search suggestion based on Title object.
 * @ingroup Search
 * @since 1.26
 */
class SearchTitleSuggestion implements SearchSuggestion {
	/**
	 * @var Title
	 */
	private $title;

	/**
	 * @param $title Title
	 */
	public function __construct( Title $title ) {
		$this->title = $title;
	}

	/**
	 * The underlying Title object
	 * @return Title
	 */
	public function getSuggestedTitle() {
		return $this->title;
	}

	/**
	 * Based on title's prefixed text
	 * {@inheritDoc}
	 */
	public function text() {
		return $this->title->getPrefixedText();
	}
}
