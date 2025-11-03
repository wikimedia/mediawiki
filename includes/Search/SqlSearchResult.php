<?php
/**
 * Search engine result issued from SearchData search engines.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Search
 */

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;

class SqlSearchResult extends RevisionSearchResult {
	/** @var string[] */
	private $terms;

	/**
	 * @param Title $title
	 * @param string[] $terms list of parsed terms
	 */
	public function __construct( Title $title, array $terms ) {
		parent::__construct( $title );
		$this->terms = $terms;
	}

	/**
	 * @return string[]
	 */
	public function getTermMatches(): array {
		return $this->terms;
	}

	/**
	 * @param array $terms Terms to highlight (this parameter is deprecated)
	 * @return string Highlighted text snippet, null (and not '') if not supported
	 */
	public function getTextSnippet( $terms = [] ) {
		$advancedSearchHighlighting = MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::AdvancedSearchHighlighting );
		$this->initText();

		$h = new SearchHighlighter();
		if ( count( $this->terms ) > 0 ) {
			if ( $advancedSearchHighlighting ) {
				return $h->highlightText( $this->mText, $this->terms );
			} else {
				return $h->highlightSimple( $this->mText, $this->terms );
			}
		} else {
			return $h->highlightNone( $this->mText );
		}
	}

}
