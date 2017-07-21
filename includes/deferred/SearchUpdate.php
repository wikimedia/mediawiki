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

use MediaWiki\MediaWikiServices;

/**
 * Database independant search index updater
 *
 * @ingroup Search
 */
class SearchUpdate implements DeferrableUpdate {
	/** @var int Page id being updated */
	private $id = 0;

	/** @var Title Title we're updating */
	private $title;

	/** @var Content|bool Content of the page (not text) */
	private $content;

	/** @var WikiPage **/
	private $page;

	/**
	 * @param int $id Page id to update
	 * @param Title|string $title Title of page to update
	 * @param Content|string|bool $c Content of the page to update. Default: false.
	 *  If a Content object, text will be gotten from it. String is for back-compat.
	 *  Passing false tells the backend to just update the title, not the content
	 */
	public function __construct( $id, $title, $c = false ) {
		if ( is_string( $title ) ) {
			$nt = Title::newFromText( $title );
		} else {
			$nt = $title;
		}

		if ( $nt ) {
			$this->id = $id;
			// is_string() check is back-compat for ApprovedRevs
			if ( is_string( $c ) ) {
				$this->content = new TextContent( $c );
			} else {
				$this->content = $c ?: false;
			}
			$this->title = $nt;
		} else {
			wfDebug( "SearchUpdate object created with invalid title '$title'\n" );
		}
	}

	/**
	 * Perform actual update for the entry
	 */
	public function doUpdate() {
		$config = MediaWikiServices::getInstance()->getSearchEngineConfig();

		if ( $config->getConfig()->get( 'DisableSearchUpdate' ) || !$this->id ) {
			return;
		}

		$seFactory = MediaWikiServices::getInstance()->getSearchEngineFactory();
		foreach ( $config->getSearchTypes() as $type ) {
			$search = $seFactory->create( $type );
			if ( !$search->supports( 'search-update' ) ) {
				continue;
			}

			$normalTitle = $this->getNormalizedTitle( $search );

			if ( $this->getLatestPage() === null ) {
				$search->delete( $this->id, $normalTitle );
				continue;
			} elseif ( $this->content === false ) {
				$search->updateTitle( $this->id, $normalTitle );
				continue;
			}

			$text = $search->getTextFromContent( $this->title, $this->content );
			if ( !$search->textAlreadyUpdatedForIndex() ) {
				$text = $this->updateText( $text, $search );
			}

			# Perform the actual update
			$search->update( $this->id, $normalTitle, $search->normalizeText( $text ) );
		}
	}

	/**
	 * Clean text for indexing. Only really suitable for indexing in databases.
	 * If you're using a real search engine, you'll probably want to override
	 * this behavior and do something nicer with the original wikitext.
	 * @param string $text
	 * @param SearchEngine $se Search engine
	 * @return string
	 */
	public function updateText( $text, SearchEngine $se = null ) {
		global $wgContLang;

		# Language-specific strip/conversion
		$text = $wgContLang->normalizeForSearch( $text );
		$se = $se ?: MediaWikiServices::getInstance()->newSearchEngine();
		$lc = $se->legalSearchChars() . '&#;';

		$text = preg_replace( "/<\\/?\\s*[A-Za-z][^>]*?>/",
			' ', $wgContLang->lc( " " . $text . " " ) ); # Strip HTML markup
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
		 * These tail-anchored regexps are insanely slow. The worst case comes
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
	 * Get WikiPage for the SearchUpdate $id using WikiPage::READ_LATEST
	 * and ensure using the same WikiPage object if there are multiple
	 * SearchEngine types.
	 *
	 * Returns null if a page has been deleted or is not found.
	 *
	 * @return WikiPage|null
	 */
	private function getLatestPage() {
		if ( !isset( $this->page ) ) {
			$this->page = WikiPage::newFromID( $this->id, WikiPage::READ_LATEST );
		}

		return $this->page;
	}

	/**
	 * Get a normalized string representation of a title suitable for
	 * including in a search index
	 *
	 * @param SearchEngine $search
	 * @return string A stripped-down title string ready for the search index
	 */
	private function getNormalizedTitle( SearchEngine $search ) {
		global $wgContLang;

		$ns = $this->title->getNamespace();
		$title = $this->title->getText();

		$lc = $search->legalSearchChars() . '&#;';
		$t = $wgContLang->normalizeForSearch( $title );
		$t = preg_replace( "/[^{$lc}]+/", ' ', $t );
		$t = $wgContLang->lc( $t );

		# Handle 's, s'
		$t = preg_replace( "/([{$lc}]+)'s( |$)/", "\\1 \\1's ", $t );
		$t = preg_replace( "/([{$lc}]+)s'( |$)/", "\\1s ", $t );

		$t = preg_replace( "/\\s+/", ' ', $t );

		if ( $ns == NS_FILE ) {
			$t = preg_replace( "/ (png|gif|jpg|jpeg|ogg)$/", "", $t );
		}

		return $search->normalizeText( trim( $t ) );
	}
}
