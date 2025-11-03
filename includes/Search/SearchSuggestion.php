<?php

/**
 * Search suggestion
 *
 * @license GPL-2.0-or-later
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;

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
	 * @var Title|null
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
	 * @param float $score the suggestion score
	 * @param string|null $text the suggestion text
	 * @param Title|null $suggestedTitle
	 * @param int|null $suggestedTitleID
	 */
	public function __construct( $score, $text = null, ?Title $suggestedTitle = null,
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
	 * @param Title|null $title
	 */
	public function setSuggestedTitle( ?Title $title = null ) {
		$this->suggestedTitle = $title;
		if ( $title !== null ) {
			$urlUtils = MediaWikiServices::getInstance()->getUrlUtils();
			$this->url = $urlUtils->expand( $title->getFullURL(), PROTO_CURRENT ) ?? false;
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
			$suggestion->setSuggestedTitle( Title::newFromText( $text ) );
		}
		return $suggestion;
	}

}
