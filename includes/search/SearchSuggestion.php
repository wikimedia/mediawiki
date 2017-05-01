<?php

/**
 * Search suggestion
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
 */

/**
 * A search suggestion
 */
class SearchSuggestion {
	/**
	 * @var string the suggestion
	 */
	private $text;

	/**
	 * @var string the suggestion URL
	 */
	private $url;

	/**
	 * @var Title|null the suggested title
	 */
	private $suggestedTitle;

	/**
	 * NOTE: even if suggestedTitle is a redirect suggestedTitleID
	 * is the ID of the target page.
	 * @var int|null the suggested title ID
	 */
	private $suggestedTitleID;

	/**
	 * @var float|null The suggestion score
	 */
	private $score;

	/**
	 * Construct a new suggestion
	 * @param float $score the suggestion score
	 * @param string $text|null the suggestion text
	 * @param Title|null $suggestedTitle the suggested title
	 * @param int|null $suggestedTitleID the suggested title ID
	 */
	public function __construct( $score, $text = null, Title $suggestedTitle = null,
			$suggestedTitleID = null ) {
		$this->score = $score;
		$this->text = $text;
		if ( $suggestedTitle ) {
			$this->setSuggestedTitle( $suggestedTitle );
		}
		$this->suggestedTitleID = $suggestedTitleID;
	}

	/**
	 * The suggestion text
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Set the suggestion text.
	 * @param string $text
	 * @param bool $setTitle Should we also update the title?
	 */
	public function setText( $text, $setTitle = true ) {
		$this->text = $text;
		if ( $setTitle && $text !== '' && $text !== null ) {
			$this->setSuggestedTitle( Title::makeTitle( 0, $text ) );
		}
	}

	/**
	 * Title object in the case this suggestion is based on a title.
	 * May return null if the suggestion is not a Title.
	 * @return Title|null
	 */
	public function getSuggestedTitle() {
		return $this->suggestedTitle;
	}

	/**
	 * Set the suggested title
	 * @param Title|null $title
	 */
	public function setSuggestedTitle( Title $title = null ) {
		$this->suggestedTitle = $title;
		if ( $title !== null ) {
			$this->url = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
		}
	}

	/**
	 * Title ID in the case this suggestion is based on a title.
	 * May return null if the suggestion is not a Title.
	 * @return int|null
	 */
	public function getSuggestedTitleID() {
		return $this->suggestedTitleID;
	}

	/**
	 * Set the suggested title ID
	 * @param int|null $suggestedTitleID
	 */
	public function setSuggestedTitleID( $suggestedTitleID = null ) {
		$this->suggestedTitleID = $suggestedTitleID;
	}

	/**
	 * Suggestion score
	 * @return float Suggestion score
	 */
	public function getScore() {
		return $this->score;
	}

	/**
	 * Set the suggestion score
	 * @param float $score
	 */
	public function setScore( $score ) {
		$this->score = $score;
	}

	/**
	 * Suggestion URL, can be the link to the Title or maybe in the
	 * future a link to the search results for this search suggestion.
	 * @return string Suggestion URL
	 */
	public function getURL() {
		return $this->url;
	}

	/**
	 * Set the suggestion URL
	 * @param string $url
	 */
	public function setURL( $url ) {
		$this->url = $url;
	}

	/**
	 * Create suggestion from Title
	 * @param float $score Suggestions score
	 * @param Title $title
	 * @return SearchSuggestion
	 */
	public static function fromTitle( $score, Title $title ) {
		return new self( $score, $title->getPrefixedText(), $title, $title->getArticleID() );
	}

	/**
	 * Create suggestion from text
	 * Will also create a title if text if not empty.
	 * @param float $score Suggestions score
	 * @param string $text
	 * @return SearchSuggestion
	 */
	public static function fromText( $score, $text ) {
		$suggestion = new self( $score, $text );
		if ( $text ) {
			$suggestion->setSuggestedTitle( Title::makeTitle( 0, $text ) );
		}
		return $suggestion;
	}

}
