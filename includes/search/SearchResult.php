<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Search
 */

use MediaWiki\Title\Title;

/**
 * An abstract base class representing a search engine result
 *
 * @ingroup Search
 */
abstract class SearchResult {
	use SearchResultTrait;

	/**
	 * Return a new SearchResult and initializes it with a title.
	 *
	 * @param Title $title
	 * @param ISearchResultSet|null $parentSet
	 * @return SearchResult
	 */
	public static function newFromTitle( $title, ?ISearchResultSet $parentSet = null ): SearchResult {
		$result = new RevisionSearchResult( $title );
		if ( $parentSet ) {
			$parentSet->augmentResult( $result );
		}
		return $result;
	}

	/**
	 * Check if this is result points to an invalid title
	 *
	 * @return bool
	 */
	abstract public function isBrokenTitle();

	/**
	 * Check if target page is missing, happens when index is out of date
	 *
	 * @return bool
	 */
	abstract public function isMissingRevision();

	/**
	 * @return Title|null
	 */
	abstract public function getTitle();

	/**
	 * Get the file for this page, if one exists
	 * @return File|null
	 */
	abstract public function getFile();

	/**
	 * @param string[] $terms Terms to highlight (this parameter is deprecated and ignored)
	 * @return string Highlighted text snippet, null (and not '') if not supported
	 */
	abstract public function getTextSnippet( $terms = [] );

	/**
	 * @return string Name of the field containing the text snippet, '' if not supported
	 */
	public function getTextSnippetField() {
		return '';
	}

	/**
	 * @return string Highlighted title, '' if not supported
	 */
	abstract public function getTitleSnippet();

	/**
	 * @return string Name of the field containing the title snippet, '' if not supported
	 */
	public function getTitleSnippetField() {
		return '';
	}

	/**
	 * @return string Highlighted redirect name (redirect to this page), '' if none or not supported
	 */
	abstract public function getRedirectSnippet();

	/**
	 * @return string Name of the field containing the redirect snippet, '' if not supported
	 */
	public function getRedirectSnippetField() {
		return '';
	}

	/**
	 * @return Title|null Title object for the redirect to this page, null if none or not supported
	 */
	abstract public function getRedirectTitle();

	/**
	 * @return string Highlighted relevant section name, null if none or not supported
	 */
	abstract public function getSectionSnippet();

	/**
	 * @return string Name of the field containing the section snippet, '' if not supported
	 */
	public function getSectionSnippetField() {
		return '';
	}

	/**
	 * @return Title|null Title object (pagename+fragment) for the section,
	 *  null if none or not supported
	 */
	abstract public function getSectionTitle();

	/**
	 * @return string Highlighted relevant category name or '' if none or not supported
	 */
	abstract public function getCategorySnippet();

	/**
	 * @return string Name of the field containing the category snippet, '' if not supported
	 */
	public function getCategorySnippetField() {
		return '';
	}

	/**
	 * @return string Timestamp
	 */
	abstract public function getTimestamp();

	/**
	 * @return int Number of words
	 */
	abstract public function getWordCount();

	/**
	 * @return int Size in bytes
	 */
	abstract public function getByteSize();

	/**
	 * @return string Interwiki prefix of the title (return iw even if title is broken)
	 */
	abstract public function getInterwikiPrefix();

	/**
	 * @return string Interwiki namespace of the title (since we likely can't resolve it locally)
	 */
	abstract public function getInterwikiNamespaceText();

	/**
	 * Did this match file contents (eg: PDF/DJVU)?
	 * @return bool
	 */
	abstract public function isFileMatch();
}
