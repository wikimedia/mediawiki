<?php
/**
 * Search engine result
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
 * @todo FIXME: This class is horribly factored. It would probably be better to
 * have a useful base class to which you pass some standard information, then
 * let the fancy self-highlighters extend that.
 * @ingroup Search
 */
class SearchResult {

	/**
	 * @var Revision
	 */
	protected $mRevision = null;

	/**
	 * @var File
	 */
	protected $mImage = null;

	/**
	 * @var Title
	 */
	protected $mTitle;

	/**
	 * @var String
	 */
	protected $mText;

	/**
	 * Return a new SearchResult and initializes it with a title.
	 *
	 * @param $title Title
	 * @return SearchResult
	 */
	public static function newFromTitle( $title ) {
		$result = new self();
		$result->initFromTitle( $title );
		return $result;
	}

	/**
	 * Return a new SearchResult and initializes it with a row.
	 *
	 * @param $row object
	 * @return SearchResult
	 */
	public static function newFromRow( $row ) {
		$result = new self();
		$result->initFromRow( $row );
		return $result;
	}

	public function __construct( $row = null ) {
		if ( !is_null( $row ) ) {
			// Backwards compatibility with pre-1.17 callers
			$this->initFromRow( $row );
		}
	}

	/**
	 * Initialize from a database row. Makes a Title and passes that to
	 * initFromTitle.
	 *
	 * @param $row object
	 */
	protected function initFromRow( $row ) {
		$this->initFromTitle( Title::makeTitle( $row->page_namespace, $row->page_title ) );
	}

	/**
	 * Initialize from a Title and if possible initializes a corresponding
	 * Revision and File.
	 *
	 * @param $title Title
	 */
	protected function initFromTitle( $title ) {
		$this->mTitle = $title;
		if ( !is_null( $this->mTitle ) ) {
			$id = false;
			wfRunHooks( 'SearchResultInitFromTitle', array( $title, &$id ) );
			$this->mRevision = Revision::newFromTitle(
				$this->mTitle, $id, Revision::READ_NORMAL );
			if ( $this->mTitle->getNamespace() === NS_FILE ) {
				$this->mImage = wfFindFile( $this->mTitle );
			}
		}
	}

	/**
	 * Check if this is result points to an invalid title
	 *
	 * @return Boolean
	 */
	function isBrokenTitle() {
		return is_null( $this->mTitle );
	}

	/**
	 * Check if target page is missing, happens when index is out of date
	 *
	 * @return Boolean
	 */
	function isMissingRevision() {
		return !$this->mRevision && !$this->mImage;
	}

	/**
	 * @return Title
	 */
	function getTitle() {
		return $this->mTitle;
	}

	/**
	 * Get the file for this page, if one exists
	 * @return File|null
	 */
	function getFile() {
		return $this->mImage;
	}

	/**
	 * @return float|null if not supported
	 */
	function getScore() {
		return null;
	}

	/**
	 * Lazy initialization of article text from DB
	 */
	protected function initText() {
		if ( !isset( $this->mText ) ) {
			if ( $this->mRevision != null ) {
				$this->mText = SearchEngine::create()
					->getTextFromContent( $this->mTitle, $this->mRevision->getContent() );
			} else { // TODO: can we fetch raw wikitext for commons images?
				$this->mText = '';
			}
		}
	}

	/**
	 * @param array $terms terms to highlight
	 * @return String: highlighted text snippet, null (and not '') if not supported
	 */
	function getTextSnippet( $terms ) {
		global $wgAdvancedSearchHighlighting;
		$this->initText();

		// TODO: make highliter take a content object. Make ContentHandler a factory for SearchHighliter.
		list( $contextlines, $contextchars ) = SearchEngine::userHighlightPrefs();
		$h = new SearchHighlighter();
		if ( $wgAdvancedSearchHighlighting ) {
			return $h->highlightText( $this->mText, $terms, $contextlines, $contextchars );
		} else {
			return $h->highlightSimple( $this->mText, $terms, $contextlines, $contextchars );
		}
	}

	/**
	 * @return String: highlighted title, '' if not supported
	 */
	function getTitleSnippet() {
		return '';
	}

	/**
	 * @return String: highlighted redirect name (redirect to this page), '' if none or not supported
	 */
	function getRedirectSnippet() {
		return '';
	}

	/**
	 * @return Title object for the redirect to this page, null if none or not supported
	 */
	function getRedirectTitle() {
		return null;
	}

	/**
	 * @return string highlighted relevant section name, null if none or not supported
	 */
	function getSectionSnippet() {
		return '';
	}

	/**
	 * @return Title object (pagename+fragment) for the section, null if none or not supported
	 */
	function getSectionTitle() {
		return null;
	}

	/**
	 * @return String: timestamp
	 */
	function getTimestamp() {
		if ( $this->mRevision ) {
			return $this->mRevision->getTimestamp();
		} elseif ( $this->mImage ) {
			return $this->mImage->getTimestamp();
		}
		return '';
	}

	/**
	 * @return Integer: number of words
	 */
	function getWordCount() {
		$this->initText();
		return str_word_count( $this->mText );
	}

	/**
	 * @return Integer: size in bytes
	 */
	function getByteSize() {
		$this->initText();
		return strlen( $this->mText );
	}

	/**
	 * @return Boolean if hit has related articles
	 */
	function hasRelated() {
		return false;
	}

	/**
	 * @return String: interwiki prefix of the title (return iw even if title is broken)
	 */
	function getInterwikiPrefix() {
		return '';
	}

	/**
	 * @return string interwiki namespace of the title (since we likely can't resolve it locally)
	 */
	function getInterwikiNamespaceText() {
		return '';
	}

	/**
	 * Did this match file contents (eg: PDF/DJVU)?
	 */
	function isFileMatch() {
		return false;
	}
}
