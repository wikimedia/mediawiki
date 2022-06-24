<?php
/**
 * Search index updater
 *
 * See deferred.txt
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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageStore;

/**
 * Database independent search index updater
 *
 * @ingroup Search
 */
class SearchUpdate implements DeferrableUpdate {
	/** @var int Page id being updated */
	private $id = 0;

	/** @var PageIdentity The page we're updating */
	private $page;

	/** @var Content|null Content of the page (not text) */
	private $content;

	/** @var ExistingPageRecord|null */
	private $latestPage = null;

	/**
	 * @param int $id Page id to update
	 * @param PageIdentity $page Page to update
	 * @param Content|null $c Content of the page to update.
	 */
	public function __construct( $id, $page, $c = null ) {
		$this->page = $page;

		$this->id = $id;
		// is_string() check is back-compat for ApprovedRevs
		if ( is_string( $c ) ) {
			wfDeprecated( __METHOD__ . " with a string for the content", '1.34' );
			$c = new TextContent( $c );
		} elseif ( is_bool( $c ) ) {
			wfDeprecated( __METHOD__ . " with a boolean for the content", '1.34' );
			$c = null;
		}
		$this->content = $c;
	}

	/**
	 * Perform actual update for the entry
	 */
	public function doUpdate() {
		$services = MediaWikiServices::getInstance();
		$config = $services->getSearchEngineConfig();

		if ( $config->getConfig()->get( MainConfigNames::DisableSearchUpdate ) || !$this->id ) {
			LoggerFactory::getInstance( "search" )
				->debug( "Skipping update: search updates disabled by config" );
			return;
		}

		$seFactory = $services->getSearchEngineFactory();
		foreach ( $config->getSearchTypes() as $type ) {
			$search = $seFactory->create( $type );
			if ( !$search->supports( 'search-update' ) ) {
				continue;
			}

			$normalTitle = $this->getNormalizedTitle( $search );

			if ( $this->getLatestPage() === null ) {
				$search->delete( $this->id, $normalTitle );
				continue;
			} elseif ( $this->content === null ) {
				$search->updateTitle( $this->id, $normalTitle );
				continue;
			}

			$text = $this->content !== null ? $this->content->getTextForSearchIndex() : '';
			$text = $this->updateText( $text, $search );

			# Perform the actual update
			$search->update( $this->id, $normalTitle, $search->normalizeText( $text ) );
		}
	}

	/**
	 * Clean text for indexing. Only really suitable for indexing in databases.
	 * If you're using a real search engine, you'll probably want to override
	 * this behavior and do something nicer with the original wikitext.
	 * @param string $text
	 * @param SearchEngine|null $se Search engine
	 * @return string
	 */
	public function updateText( $text, SearchEngine $se = null ) {
		$services = MediaWikiServices::getInstance();
		$contLang = $services->getContentLanguage();
		# Language-specific strip/conversion
		$text = $contLang->normalizeForSearch( $text );
		$se = $se ?: $services->newSearchEngine();
		$lc = $se->legalSearchChars() . '&#;';

		# Strip HTML markup
		$text = preg_replace( "/<\\/?\\s*[A-Za-z][^>]*?>/",
			' ', $contLang->lc( " " . $text . " " ) );
		$text = preg_replace( "/(^|\\n)==\\s*([^\\n]+)\\s*==(\\s)/sD",
			"\\1\\2 \\2 \\2\\3", $text ); # Emphasize headings

		# Strip external URLs
		$uc = "A-Za-z0-9_\\/:.,~%\\-+&;#?!=()@\\x80-\\xFF";
		$protos = "http|https|ftp|mailto|news|gopher";
		$pat = "/(^|[^\\[])({$protos}):[{$uc}]+([^{$uc}]|$)/";
		$text = preg_replace( $pat, "\\1 \\3", $text );

		$p1 = "/([^\\[])\\[({$protos}):[{$uc}]+]/";
		$p2 = "/([^\\[])\\[({$protos}):[{$uc}]+\\s+([^\\]]+)]/";
		$text = preg_replace( $p1, "\\1 ", $text );
		$text = preg_replace( $p2, "\\1 \\3 ", $text );

		# Internal image links
		$pat2 = "/\\[\\[image:([{$uc}]+)\\.(gif|png|jpg|jpeg)([^{$uc}])/i";
		$text = preg_replace( $pat2, " \\1 \\3", $text );

		$text = preg_replace( "/([^{$lc}])([{$lc}]+)]]([a-z]+)/",
			"\\1\\2 \\2\\3", $text ); # Handle [[game]]s

		# Strip all remaining non-search characters
		$text = preg_replace( "/[^{$lc}]+/", " ", $text );

		/**
		 * Handle 's, s'
		 *
		 *   $text = preg_replace( "/([{$lc}]+)'s /", "\\1 \\1's ", $text );
		 *   $text = preg_replace( "/([{$lc}]+)s' /", "\\1s ", $text );
		 *
		 * These tail-anchored regexps are very slow. The worst case comes
		 * when Japanese or Chinese text (ie, no word spacing) is written on
		 * a wiki configured for Western UTF-8 mode. The Unicode characters are
		 * expanded to hex codes and the "words" are very long paragraph-length
		 * monstrosities. On a large page the above regexps may take over 20
		 * seconds *each* on a 1GHz-level processor.
		 *
		 * Following are reversed versions which are consistently fast
		 * (about 3 milliseconds on 1GHz-level processor).
		 */
		$text = strrev( preg_replace( "/ s'([{$lc}]+)/", " s'\\1 \\1", strrev( $text ) ) );
		$text = strrev( preg_replace( "/ 's([{$lc}]+)/", " s\\1", strrev( $text ) ) );

		# Strip wiki '' and '''
		$text = preg_replace( "/''[']*/", " ", $text );

		return $text;
	}

	/**
	 * Get ExistingPageRecord for the SearchUpdate $id using PageStore::READ_LATEST
	 * and ensure using the same ExistingPageRecord object if there are multiple
	 * SearchEngine types.
	 *
	 * Returns null if a page has been deleted or is not found.
	 *
	 * @return ExistingPageRecord|null
	 */
	private function getLatestPage() {
		if ( !isset( $this->latestPage ) ) {
			$this->latestPage = MediaWikiServices::getInstance()->getPageStore()
				->getPageById( $this->id, PageStore::READ_LATEST );
		}

		return $this->latestPage;
	}

	/**
	 * Get a normalized string representation of a title suitable for
	 * including in a search index
	 *
	 * @param SearchEngine $search
	 * @return string A stripped-down title string ready for the search index
	 */
	private function getNormalizedTitle( SearchEngine $search ) {
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$ns = $this->page->getNamespace();
		$title = str_replace( '_', ' ', $this->page->getDBkey() );

		$lc = $search->legalSearchChars() . '&#;';
		$t = $contLang->normalizeForSearch( $title );
		$t = preg_replace( "/[^{$lc}]+/", ' ', $t );
		$t = $contLang->lc( $t );

		# Handle 's, s'
		$t = preg_replace( "/([{$lc}]+)'s( |$)/", "\\1 \\1's ", $t );
		$t = preg_replace( "/([{$lc}]+)s'( |$)/", "\\1s ", $t );

		$t = preg_replace( "/\\s+/", ' ', $t );

		if ( $ns === NS_FILE ) {
			$t = preg_replace( "/ (png|gif|jpg|jpeg|ogg)$/", "", $t );
		}

		return $search->normalizeText( trim( $t ) );
	}
}
