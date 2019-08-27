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

use MediaWiki\MediaWikiServices;

/**
 * @todo FIXME: This class is horribly factored. It would probably be better to
 * have a useful base class to which you pass some standard information, then
 * let the fancy self-highlighters extend that.
 * @ingroup Search
 */
class SearchResult {
	use SearchResultTrait;

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
	 * @var string
	 */
	protected $mText;

	/**
	 * Return a new SearchResult and initializes it with a title.
	 *
	 * @param Title $title
	 * @param ISearchResultSet|null $parentSet
	 * @return SearchResult
	 */
	public static function newFromTitle( $title, ISearchResultSet $parentSet = null ) {
		$result = new static();
		$result->initFromTitle( $title );
		if ( $parentSet ) {
			$parentSet->augmentResult( $result );
		}
		return $result;
	}

	/**
	 * Initialize from a Title and if possible initializes a corresponding
	 * Revision and File.
	 *
	 * @param Title $title
	 */
	protected function initFromTitle( $title ) {
		$this->mTitle = $title;
		$services = MediaWikiServices::getInstance();
		if ( !is_null( $this->mTitle ) ) {
			$id = false;
			Hooks::run( 'SearchResultInitFromTitle', [ $title, &$id ] );
			$this->mRevision = Revision::newFromTitle(
				$this->mTitle, $id, Revision::READ_NORMAL );
			if ( $this->mTitle->getNamespace() === NS_FILE ) {
				$this->mImage = $services->getRepoGroup()->findFile( $this->mTitle );
			}
		}
	}

	/**
	 * Check if this is result points to an invalid title
	 *
	 * @return bool
	 */
	public function isBrokenTitle() {
		return is_null( $this->mTitle );
	}

	/**
	 * Check if target page is missing, happens when index is out of date
	 *
	 * @return bool
	 */
	public function isMissingRevision() {
		return !$this->mRevision && !$this->mImage;
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	/**
	 * Get the file for this page, if one exists
	 * @return File|null
	 */
	public function getFile() {
		return $this->mImage;
	}

	/**
	 * Lazy initialization of article text from DB
	 */
	protected function initText() {
		if ( !isset( $this->mText ) ) {
			if ( $this->mRevision != null ) {
				$content = $this->mRevision->getContent();
				$this->mText = $content !== null ? $content->getTextForSearchIndex() : '';
			} else { // TODO: can we fetch raw wikitext for commons images?
				$this->mText = '';
			}
		}
	}

	/**
	 * @param string[] $terms Terms to highlight (this parameter is deprecated and ignored)
	 * @return string Highlighted text snippet, null (and not '') if not supported
	 */
	public function getTextSnippet( $terms = [] ) {
		return '';
	}

	/**
	 * @return string Highlighted title, '' if not supported
	 */
	public function getTitleSnippet() {
		return '';
	}

	/**
	 * @return string Highlighted redirect name (redirect to this page), '' if none or not supported
	 */
	public function getRedirectSnippet() {
		return '';
	}

	/**
	 * @return Title|null Title object for the redirect to this page, null if none or not supported
	 */
	public function getRedirectTitle() {
		return null;
	}

	/**
	 * @return string Highlighted relevant section name, null if none or not supported
	 */
	public function getSectionSnippet() {
		return '';
	}

	/**
	 * @return Title|null Title object (pagename+fragment) for the section,
	 *  null if none or not supported
	 */
	public function getSectionTitle() {
		return null;
	}

	/**
	 * @return string Highlighted relevant category name or '' if none or not supported
	 */
	public function getCategorySnippet() {
		return '';
	}

	/**
	 * @return string Timestamp
	 */
	public function getTimestamp() {
		if ( $this->mRevision ) {
			return $this->mRevision->getTimestamp();
		} elseif ( $this->mImage ) {
			return $this->mImage->getTimestamp();
		}
		return '';
	}

	/**
	 * @return int Number of words
	 */
	public function getWordCount() {
		$this->initText();
		return str_word_count( $this->mText );
	}

	/**
	 * @return int Size in bytes
	 */
	public function getByteSize() {
		$this->initText();
		return strlen( $this->mText );
	}

	/**
	 * @return string Interwiki prefix of the title (return iw even if title is broken)
	 */
	public function getInterwikiPrefix() {
		return '';
	}

	/**
	 * @return string Interwiki namespace of the title (since we likely can't resolve it locally)
	 */
	public function getInterwikiNamespaceText() {
		return '';
	}

	/**
	 * Did this match file contents (eg: PDF/DJVU)?
	 * @return bool
	 */
	public function isFileMatch() {
		return false;
	}

}
