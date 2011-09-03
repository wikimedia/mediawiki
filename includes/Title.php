<?php
/**
 * See title.txt
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
 */

/**
 * Represents a title within MediaWiki.
 * Optionally may contain an interwiki designation or namespace.
 * @note This class can fetch various kinds of data from the database;
 *       however, it does so inefficiently.
 *
 * @internal documentation reviewed 15 Mar 2010
 */
class Title {
	/** @name Static cache variables */
	// @{
	static private $titleCache = array();
	// @}

	/**
	 * Title::newFromText maintains a cache to avoid expensive re-normalization of
	 * commonly used titles. On a batch operation this can become a memory leak
	 * if not bounded. After hitting this many titles reset the cache.
	 */
	const CACHE_MAX = 1000;

	/**
	 * Used to be GAID_FOR_UPDATE define. Used with getArticleID() and friends
	 * to use the master DB
	 */
	const GAID_FOR_UPDATE = 1;


	/**
	 * @name Private member variables
	 * Please use the accessor functions instead.
	 * @private
	 */
	// @{

	var $mTextform = '';              // /< Text form (spaces not underscores) of the main part
	var $mUrlform = '';               // /< URL-encoded form of the main part
	var $mDbkeyform = '';             // /< Main part with underscores
	var $mUserCaseDBKey;              // /< DB key with the initial letter in the case specified by the user
	var $mNamespace = NS_MAIN;        // /< Namespace index, i.e. one of the NS_xxxx constants
	var $mInterwiki = '';             // /< Interwiki prefix (or null string)
	var $mFragment;                   // /< Title fragment (i.e. the bit after the #)
	var $mArticleID = -1;             // /< Article ID, fetched from the link cache on demand
	var $mLatestID = false;           // /< ID of most recent revision
	var $mRestrictions = array();     // /< Array of groups allowed to edit this article
	var $mOldRestrictions = false;
	var $mCascadeRestriction;         ///< Cascade restrictions on this page to included templates and images?
	var $mCascadingRestrictions;      // Caching the results of getCascadeProtectionSources
	var $mRestrictionsExpiry = array(); ///< When do the restrictions on this page expire?
	var $mHasCascadingRestrictions;   ///< Are cascading restrictions in effect on this page?
	var $mCascadeSources;             ///< Where are the cascading restrictions coming from on this page?
	var $mRestrictionsLoaded = false; ///< Boolean for initialisation on demand
	var $mPrefixedText;               ///< Text form including namespace/interwiki, initialised on demand
	var $mTitleProtection;            ///< Cached value for getTitleProtection (create protection)
	# Don't change the following default, NS_MAIN is hardcoded in several
	# places.  See bug 696.
	var $mDefaultNamespace = NS_MAIN; // /< Namespace index when there is no namespace
									  # Zero except in {{transclusion}} tags
	var $mWatched = null;             // /< Is $wgUser watching this page? null if unfilled, accessed through userIsWatching()
	var $mLength = -1;                // /< The page length, 0 for special pages
	var $mRedirect = null;            // /< Is the article at this title a redirect?
	var $mNotificationTimestamp = array(); // /< Associative array of user ID -> timestamp/false
	var $mBacklinkCache = null;       // /< Cache of links to this title
	// @}


	/**
	 * Constructor
	 */
	/*protected*/ function __construct() { }

	/**
	 * Create a new Title from a prefixed DB key
	 *
	 * @param $key String the database key, which has underscores
	 *	instead of spaces, possibly including namespace and
	 *	interwiki prefixes
	 * @return Title, or NULL on an error
	 */
	public static function newFromDBkey( $key ) {
		$t = new Title();
		$t->mDbkeyform = $key;
		if ( $t->secureAndSplit() ) {
			return $t;
		} else {
			return null;
		}
	}

	/**
	 * Create a new Title from text, such as what one would find in a link. De-
	 * codes any HTML entities in the text.
	 *
	 * @param $text String the link text; spaces, prefixes, and an
	 *   initial ':' indicating the main namespace are accepted.
	 * @param $defaultNamespace Int the namespace to use if none is speci-
	 *   fied by a prefix.  If you want to force a specific namespace even if
	 *   $text might begin with a namespace prefix, use makeTitle() or
	 *   makeTitleSafe().
	 * @return Title, or null on an error.
	 */
	public static function newFromText( $text, $defaultNamespace = NS_MAIN ) {
		if ( is_object( $text ) ) {
			throw new MWException( 'Title::newFromText given an object' );
		}

		/**
		 * Wiki pages often contain multiple links to the same page.
		 * Title normalization and parsing can become expensive on
		 * pages with many links, so we can save a little time by
		 * caching them.
		 *
		 * In theory these are value objects and won't get changed...
		 */
		if ( $defaultNamespace == NS_MAIN && isset( Title::$titleCache[$text] ) ) {
			return Title::$titleCache[$text];
		}

		# Convert things like &eacute; &#257; or &#x3017; into normalized (bug 14952) text
		$filteredText = Sanitizer::decodeCharReferencesAndNormalize( $text );

		$t = new Title();
		$t->mDbkeyform = str_replace( ' ', '_', $filteredText );
		$t->mDefaultNamespace = $defaultNamespace;

		static $cachedcount = 0 ;
		if ( $t->secureAndSplit() ) {
			if ( $defaultNamespace == NS_MAIN ) {
				if ( $cachedcount >= self::CACHE_MAX ) {
					# Avoid memory leaks on mass operations...
					Title::$titleCache = array();
					$cachedcount = 0;
				}
				$cachedcount++;
				Title::$titleCache[$text] =& $t;
			}
			return $t;
		} else {
			$ret = null;
			return $ret;
		}
	}

	/**
	 * THIS IS NOT THE FUNCTION YOU WANT. Use Title::newFromText().
	 *
	 * Example of wrong and broken code:
	 * $title = Title::newFromURL( $wgRequest->getVal( 'title' ) );
	 *
	 * Example of right code:
	 * $title = Title::newFromText( $wgRequest->getVal( 'title' ) );
	 *
	 * Create a new Title from URL-encoded text. Ensures that
	 * the given title's length does not exceed the maximum.
	 *
	 * @param $url String the title, as might be taken from a URL
	 * @return Title the new object, or NULL on an error
	 */
	public static function newFromURL( $url ) {
		global $wgLegalTitleChars;
		$t = new Title();

		# For compatibility with old buggy URLs. "+" is usually not valid in titles,
		# but some URLs used it as a space replacement and they still come
		# from some external search tools.
		if ( strpos( $wgLegalTitleChars, '+' ) === false ) {
			$url = str_replace( '+', ' ', $url );
		}

		$t->mDbkeyform = str_replace( ' ', '_', $url );
		if ( $t->secureAndSplit() ) {
			return $t;
		} else {
			return null;
		}
	}

	/**
	 * Create a new Title from an article ID
	 *
	 * @param $id Int the page_id corresponding to the Title to create
	 * @param $flags Int use Title::GAID_FOR_UPDATE to use master
	 * @return Title the new object, or NULL on an error
	 */
	public static function newFromID( $id, $flags = 0 ) {
		$db = ( $flags & self::GAID_FOR_UPDATE ) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_SLAVE );
		$row = $db->selectRow( 'page', '*', array( 'page_id' => $id ), __METHOD__ );
		if ( $row !== false ) {
			$title = Title::newFromRow( $row );
		} else {
			$title = null;
		}
		return $title;
	}

	/**
	 * Make an array of titles from an array of IDs
	 *
	 * @param $ids Array of Int Array of IDs
	 * @return Array of Titles
	 */
	public static function newFromIDs( $ids ) {
		if ( !count( $ids ) ) {
			return array();
		}
		$dbr = wfGetDB( DB_SLAVE );

		$res = $dbr->select(
			'page',
			array(
				'page_namespace', 'page_title', 'page_id',
				'page_len', 'page_is_redirect', 'page_latest',
			),
			array( 'page_id' => $ids ),
			__METHOD__
		);

		$titles = array();
		foreach ( $res as $row ) {
			$titles[] = Title::newFromRow( $row );
		}
		return $titles;
	}

	/**
	 * Make a Title object from a DB row
	 *
	 * @param $row Object database row (needs at least page_title,page_namespace)
	 * @return Title corresponding Title
	 */
	public static function newFromRow( $row ) {
		$t = self::makeTitle( $row->page_namespace, $row->page_title );
		$t->loadFromRow( $row );
		return $t;
	}

	/**
	 * Load Title object fields from a DB row.
	 * If false is given, the title will be treated as non-existing.
	 *
	 * @param $row Object|false database row
	 * @return void
	 */
	public function loadFromRow( $row ) {
		if ( $row ) { // page found
			if ( isset( $row->page_id ) )
				$this->mArticleID = (int)$row->page_id;
			if ( isset( $row->page_len ) )
				$this->mLength = (int)$row->page_len;
			if ( isset( $row->page_is_redirect ) )
				$this->mRedirect = (bool)$row->page_is_redirect;
			if ( isset( $row->page_latest ) )
				$this->mLatestID = (int)$row->page_latest;
		} else { // page not found
			$this->mArticleID = 0;
			$this->mLength = 0;
			$this->mRedirect = false;
			$this->mLatestID = 0;
		}
	}

	/**
	 * Create a new Title from a namespace index and a DB key.
	 * It's assumed that $ns and $title are *valid*, for instance when
	 * they came directly from the database or a special page name.
	 * For convenience, spaces are converted to underscores so that
	 * eg user_text fields can be used directly.
	 *
	 * @param $ns Int the namespace of the article
	 * @param $title String the unprefixed database key form
	 * @param $fragment String the link fragment (after the "#")
	 * @param $interwiki String the interwiki prefix
	 * @return Title the new object
	 */
	public static function &makeTitle( $ns, $title, $fragment = '', $interwiki = '' ) {
		$t = new Title();
		$t->mInterwiki = $interwiki;
		$t->mFragment = $fragment;
		$t->mNamespace = $ns = intval( $ns );
		$t->mDbkeyform = str_replace( ' ', '_', $title );
		$t->mArticleID = ( $ns >= 0 ) ? -1 : 0;
		$t->mUrlform = wfUrlencode( $t->mDbkeyform );
		$t->mTextform = str_replace( '_', ' ', $title );
		return $t;
	}

	/**
	 * Create a new Title from a namespace index and a DB key.
	 * The parameters will be checked for validity, which is a bit slower
	 * than makeTitle() but safer for user-provided data.
	 *
	 * @param $ns Int the namespace of the article
	 * @param $title String database key form
	 * @param $fragment String the link fragment (after the "#")
	 * @param $interwiki String interwiki prefix
	 * @return Title the new object, or NULL on an error
	 */
	public static function makeTitleSafe( $ns, $title, $fragment = '', $interwiki = '' ) {
		$t = new Title();
		$t->mDbkeyform = Title::makeName( $ns, $title, $fragment, $interwiki );
		if ( $t->secureAndSplit() ) {
			return $t;
		} else {
			return null;
		}
	}

	/**
	 * Create a new Title for the Main Page
	 *
	 * @return Title the new object
	 */
	public static function newMainPage() {
		$title = Title::newFromText( wfMsgForContent( 'mainpage' ) );
		// Don't give fatal errors if the message is broken
		if ( !$title ) {
			$title = Title::newFromText( 'Main Page' );
		}
		return $title;
	}

	/**
	 * Extract a redirect destination from a string and return the
	 * Title, or null if the text doesn't contain a valid redirect
	 * This will only return the very next target, useful for
	 * the redirect table and other checks that don't need full recursion
	 *
	 * @param $text String: Text with possible redirect
	 * @return Title: The corresponding Title
	 */
	public static function newFromRedirect( $text ) {
		return self::newFromRedirectInternal( $text );
	}

	/**
	 * Extract a redirect destination from a string and return the
	 * Title, or null if the text doesn't contain a valid redirect
	 * This will recurse down $wgMaxRedirects times or until a non-redirect target is hit
	 * in order to provide (hopefully) the Title of the final destination instead of another redirect
	 *
	 * @param $text String Text with possible redirect
	 * @return Title
	 */
	public static function newFromRedirectRecurse( $text ) {
		$titles = self::newFromRedirectArray( $text );
		return $titles ? array_pop( $titles ) : null;
	}

	/**
	 * Extract a redirect destination from a string and return an
	 * array of Titles, or null if the text doesn't contain a valid redirect
	 * The last element in the array is the final destination after all redirects
	 * have been resolved (up to $wgMaxRedirects times)
	 *
	 * @param $text String Text with possible redirect
	 * @return Array of Titles, with the destination last
	 */
	public static function newFromRedirectArray( $text ) {
		global $wgMaxRedirects;
		$title = self::newFromRedirectInternal( $text );
		if ( is_null( $title ) ) {
			return null;
		}
		// recursive check to follow double redirects
		$recurse = $wgMaxRedirects;
		$titles = array( $title );
		while ( --$recurse > 0 ) {
			if ( $title->isRedirect() ) {
				$article = new Article( $title, 0 );
				$newtitle = $article->getRedirectTarget();
			} else {
				break;
			}
			// Redirects to some special pages are not permitted
			if ( $newtitle instanceOf Title && $newtitle->isValidRedirectTarget() ) {
				// the new title passes the checks, so make that our current title so that further recursion can be checked
				$title = $newtitle;
				$titles[] = $newtitle;
			} else {
				break;
			}
		}
		return $titles;
	}

	/**
	 * Really extract the redirect destination
	 * Do not call this function directly, use one of the newFromRedirect* functions above
	 *
	 * @param $text String Text with possible redirect
	 * @return Title
	 */
	protected static function newFromRedirectInternal( $text ) {
		global $wgMaxRedirects;
		if ( $wgMaxRedirects < 1 ) {
			//redirects are disabled, so quit early
			return null;
		}
		$redir = MagicWord::get( 'redirect' );
		$text = trim( $text );
		if ( $redir->matchStartAndRemove( $text ) ) {
			// Extract the first link and see if it's usable
			// Ensure that it really does come directly after #REDIRECT
			// Some older redirects included a colon, so don't freak about that!
			$m = array();
			if ( preg_match( '!^\s*:?\s*\[{2}(.*?)(?:\|.*?)?\]{2}!', $text, $m ) ) {
				// Strip preceding colon used to "escape" categories, etc.
				// and URL-decode links
				if ( strpos( $m[1], '%' ) !== false ) {
					// Match behavior of inline link parsing here;
					$m[1] = rawurldecode( ltrim( $m[1], ':' ) );
				}
				$title = Title::newFromText( $m[1] );
				// If the title is a redirect to bad special pages or is invalid, return null
				if ( !$title instanceof Title || !$title->isValidRedirectTarget() ) {
					return null;
				}
				return $title;
			}
		}
		return null;
	}

# ----------------------------------------------------------------------------
#	Static functions
# ----------------------------------------------------------------------------

	/**
	 * Get the prefixed DB key associated with an ID
	 *
	 * @param $id Int the page_id of the article
	 * @return Title an object representing the article, or NULL if no such article was found
	 */
	public static function nameOf( $id ) {
		$dbr = wfGetDB( DB_SLAVE );

		$s = $dbr->selectRow(
			'page',
			array( 'page_namespace', 'page_title' ),
			array( 'page_id' => $id ),
			__METHOD__
		);
		if ( $s === false ) {
			return null;
		}

		$n = self::makeName( $s->page_namespace, $s->page_title );
		return $n;
	}

	/**
	 * Get a regex character class describing the legal characters in a link
	 *
	 * @return String the list of characters, not delimited
	 */
	public static function legalChars() {
		global $wgLegalTitleChars;
		return $wgLegalTitleChars;
	}

	/**
	 * Get a string representation of a title suitable for
	 * including in a search index
	 *
	 * @param $ns Int a namespace index
	 * @param $title String text-form main part
	 * @return String a stripped-down title string ready for the search index
	 */
	public static function indexTitle( $ns, $title ) {
		global $wgContLang;

		$lc = SearchEngine::legalSearchChars() . '&#;';
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
		return trim( $t );
	}

	/**
	 * Make a prefixed DB key from a DB key and a namespace index
	 *
	 * @param $ns Int numerical representation of the namespace
	 * @param $title String the DB key form the title
	 * @param $fragment String The link fragment (after the "#")
	 * @param $interwiki String The interwiki prefix
	 * @return String the prefixed form of the title
	 */
	public static function makeName( $ns, $title, $fragment = '', $interwiki = '' ) {
		global $wgContLang;

		$namespace = $wgContLang->getNsText( $ns );
		$name = $namespace == '' ? $title : "$namespace:$title";
		if ( strval( $interwiki ) != '' ) {
			$name = "$interwiki:$name";
		}
		if ( strval( $fragment ) != '' ) {
			$name .= '#' . $fragment;
		}
		return $name;
	}

	/**
	 * Determine whether the object refers to a page within
	 * this project.
	 *
	 * @return Bool TRUE if this is an in-project interwiki link or a wikilink, FALSE otherwise
	 */
	public function isLocal() {
		if ( $this->mInterwiki != '' ) {
			return Interwiki::fetch( $this->mInterwiki )->isLocal();
		} else {
			return true;
		}
	}

	/**
	 * Determine whether the object refers to a page within
	 * this project and is transcludable.
	 *
	 * @return Bool TRUE if this is transcludable
	 */
	public function isTrans() {
		if ( $this->mInterwiki == '' ) {
			return false;
		}

		return Interwiki::fetch( $this->mInterwiki )->isTranscludable();
	}

	/**
	 * Returns the DB name of the distant wiki which owns the object.
	 *
	 * @return String the DB name
	 */
	public function getTransWikiID() {
		if ( $this->mInterwiki == '' ) {
			return false;
		}

		return Interwiki::fetch( $this->mInterwiki )->getWikiID();
	}

	/**
	 * Escape a text fragment, say from a link, for a URL
	 *
	 * @param $fragment string containing a URL or link fragment (after the "#")
	 * @return String: escaped string
	 */
	static function escapeFragmentForURL( $fragment ) {
		# Note that we don't urlencode the fragment.  urlencoded Unicode
		# fragments appear not to work in IE (at least up to 7) or in at least
		# one version of Opera 9.x.  The W3C validator, for one, doesn't seem
		# to care if they aren't encoded.
		return Sanitizer::escapeId( $fragment, 'noninitial' );
	}

# ----------------------------------------------------------------------------
#	Other stuff
# ----------------------------------------------------------------------------

	/** Simple accessors */
	/**
	 * Get the text form (spaces not underscores) of the main part
	 *
	 * @return String Main part of the title
	 */
	public function getText() { return $this->mTextform; }

	/**
	 * Get the URL-encoded form of the main part
	 *
	 * @return String Main part of the title, URL-encoded
	 */
	public function getPartialURL() { return $this->mUrlform; }

	/**
	 * Get the main part with underscores
	 *
	 * @return String: Main part of the title, with underscores
	 */
	public function getDBkey() { return $this->mDbkeyform; }

	/**
	 * Get the namespace index, i.e. one of the NS_xxxx constants.
	 *
	 * @return Integer: Namespace index
	 */
	public function getNamespace() { return $this->mNamespace; }

	/**
	 * Get the namespace text
	 *
	 * @return String: Namespace text
	 */
	public function getNsText() {
		global $wgContLang;

		if ( $this->mInterwiki != '' ) {
			// This probably shouldn't even happen. ohh man, oh yuck.
			// But for interwiki transclusion it sometimes does.
			// Shit. Shit shit shit.
			//
			// Use the canonical namespaces if possible to try to
			// resolve a foreign namespace.
			if ( MWNamespace::exists( $this->mNamespace ) ) {
				return MWNamespace::getCanonicalName( $this->mNamespace );
			}
		}

		if ( $wgContLang->needsGenderDistinction() &&
				MWNamespace::hasGenderDistinction( $this->mNamespace ) ) {
			$gender = GenderCache::singleton()->getGenderOf( $this->getText(), __METHOD__ );
			return $wgContLang->getGenderNsText( $this->mNamespace, $gender );
		}

		return $wgContLang->getNsText( $this->mNamespace );
	}

	/**
	 * Get the DB key with the initial letter case as specified by the user
	 *
	 * @return String DB key
	 */
	function getUserCaseDBKey() {
		return $this->mUserCaseDBKey;
	}

	/**
	 * Get the namespace text of the subject (rather than talk) page
	 *
	 * @return String Namespace text
	 */
	public function getSubjectNsText() {
		global $wgContLang;
		return $wgContLang->getNsText( MWNamespace::getSubject( $this->mNamespace ) );
	}

	/**
	 * Get the namespace text of the talk page
	 *
	 * @return String Namespace text
	 */
	public function getTalkNsText() {
		global $wgContLang;
		return( $wgContLang->getNsText( MWNamespace::getTalk( $this->mNamespace ) ) );
	}

	/**
	 * Could this title have a corresponding talk page?
	 *
	 * @return Bool TRUE or FALSE
	 */
	public function canTalk() {
		return( MWNamespace::canTalk( $this->mNamespace ) );
	}

	/**
	 * Get the interwiki prefix (or null string)
	 *
	 * @return String Interwiki prefix
	 */
	public function getInterwiki() { return $this->mInterwiki; }

	/**
	 * Get the Title fragment (i.e.\ the bit after the #) in text form
	 *
	 * @return String Title fragment
	 */
	public function getFragment() { return $this->mFragment; }

	/**
	 * Get the fragment in URL form, including the "#" character if there is one
	 * @return String Fragment in URL form
	 */
	public function getFragmentForURL() {
		if ( $this->mFragment == '' ) {
			return '';
		} else {
			return '#' . Title::escapeFragmentForURL( $this->mFragment );
		}
	}

	/**
	 * Get the default namespace index, for when there is no namespace
	 *
	 * @return Int Default namespace index
	 */
	public function getDefaultNamespace() { return $this->mDefaultNamespace; }

	/**
	 * Get title for search index
	 *
	 * @return String a stripped-down title string ready for the
	 *  search index
	 */
	public function getIndexTitle() {
		return Title::indexTitle( $this->mNamespace, $this->mTextform );
	}

	/**
	 * Get the prefixed database key form
	 *
	 * @return String the prefixed title, with underscores and
	 *  any interwiki and namespace prefixes
	 */
	public function getPrefixedDBkey() {
		$s = $this->prefix( $this->mDbkeyform );
		$s = str_replace( ' ', '_', $s );
		return $s;
	}

	/**
	 * Get the prefixed title with spaces.
	 * This is the form usually used for display
	 *
	 * @return String the prefixed title, with spaces
	 */
	public function getPrefixedText() {
		// @todo FIXME: Bad usage of empty() ?
		if ( empty( $this->mPrefixedText ) ) {
			$s = $this->prefix( $this->mTextform );
			$s = str_replace( '_', ' ', $s );
			$this->mPrefixedText = $s;
		}
		return $this->mPrefixedText;
	}

	/**
	 * Return the prefixed title with spaces _without_ the interwiki prefix
	 * 
	 * @return \type{\string} the title, prefixed by the namespace but not by the interwiki prefix, with spaces
	 */
	public function getSemiPrefixedText() {
		if ( !isset( $this->mSemiPrefixedText ) ){
			$s = ( $this->mNamespace === NS_MAIN ? '' : $this->getNsText() . ':' ) . $this->mTextform;
			$s = str_replace( '_', ' ', $s );
			$this->mSemiPrefixedText = $s;
		}
		return $this->mSemiPrefixedText; 
	}

	/**
	 * Get the prefixed title with spaces, plus any fragment
	 * (part beginning with '#')
	 *
	 * @return String the prefixed title, with spaces and the fragment, including '#'
	 */
	public function getFullText() {
		$text = $this->getPrefixedText();
		if ( $this->mFragment != '' ) {
			$text .= '#' . $this->mFragment;
		}
		return $text;
	}

	/**
	 * Get the base page name, i.e. the leftmost part before any slashes
	 *
	 * @return String Base name
	 */
	public function getBaseText() {
		if ( !MWNamespace::hasSubpages( $this->mNamespace ) ) {
			return $this->getText();
		}

		$parts = explode( '/', $this->getText() );
		# Don't discard the real title if there's no subpage involved
		if ( count( $parts ) > 1 ) {
			unset( $parts[count( $parts ) - 1] );
		}
		return implode( '/', $parts );
	}

	/**
	 * Get the lowest-level subpage name, i.e. the rightmost part after any slashes
	 *
	 * @return String Subpage name
	 */
	public function getSubpageText() {
		if ( !MWNamespace::hasSubpages( $this->mNamespace ) ) {
			return( $this->mTextform );
		}
		$parts = explode( '/', $this->mTextform );
		return( $parts[count( $parts ) - 1] );
	}

	/**
	 * Get a URL-encoded form of the subpage text
	 *
	 * @return String URL-encoded subpage name
	 */
	public function getSubpageUrlForm() {
		$text = $this->getSubpageText();
		$text = wfUrlencode( str_replace( ' ', '_', $text ) );
		return( $text );
	}

	/**
	 * Get a URL-encoded title (not an actual URL) including interwiki
	 *
	 * @return String the URL-encoded form
	 */
	public function getPrefixedURL() {
		$s = $this->prefix( $this->mDbkeyform );
		$s = wfUrlencode( str_replace( ' ', '_', $s ) );
		return $s;
	}

	/**
	 * Get a real URL referring to this title, with interwiki link and
	 * fragment
	 *
	 * @param $query \twotypes{\string,\array} an optional query string, not used for interwiki
	 *   links. Can be specified as an associative array as well, e.g.,
	 *   array( 'action' => 'edit' ) (keys and values will be URL-escaped).
	 * @param $variant String language variant of url (for sr, zh..)
	 * @return String the URL
	 */
	public function getFullURL( $query = '', $variant = false ) {
		# Hand off all the decisions on urls to getLocalURL
		$url = $this->getLocalURL( $query, $variant );

		# Expand the url to make it a full url. Note that getLocalURL has the
		# potential to output full urls for a variety of reasons, so we use
		# wfExpandUrl instead of simply prepending $wgServer
		$url = wfExpandUrl( $url, PROTO_RELATIVE );

		# Finally, add the fragment.
		$url .= $this->getFragmentForURL();

		wfRunHooks( 'GetFullURL', array( &$this, &$url, $query ) );
		return $url;
	}

	/**
	 * Get a URL with no fragment or server name.  If this page is generated
	 * with action=render, $wgServer is prepended.
	 *
	 * @param $query Mixed: an optional query string; if not specified,
	 *   $wgArticlePath will be used.  Can be specified as an associative array
	 *   as well, e.g., array( 'action' => 'edit' ) (keys and values will be
	 *   URL-escaped).
	 * @param $variant String language variant of url (for sr, zh..)
	 * @return String the URL
	 */
	public function getLocalURL( $query = '', $variant = false ) {
		global $wgArticlePath, $wgScript, $wgServer, $wgRequest;
		global $wgVariantArticlePath, $wgContLang;

		if ( is_array( $query ) ) {
			$query = wfArrayToCGI( $query );
		}

		$interwiki = Interwiki::fetch( $this->mInterwiki );
		if ( $interwiki ) {
			$namespace = $this->getNsText();
			if ( $namespace != '' ) {
				# Can this actually happen? Interwikis shouldn't be parsed.
				# Yes! It can in interwiki transclusion. But... it probably shouldn't.
				$namespace .= ':';
			}
			$url = $interwiki->getURL( $namespace . $this->getDBkey() );
			$url = wfAppendQuery( $url, $query );
		} else {
			$dbkey = wfUrlencode( $this->getPrefixedDBkey() );
			if ( $query == '' ) {
				if ( $variant != false && $wgContLang->hasVariants() ) {
					if ( !$wgVariantArticlePath ) {
						$variantArticlePath =  "$wgScript?title=$1&variant=$2"; // default
					} else {
						$variantArticlePath = $wgVariantArticlePath;
					}
					$url = str_replace( '$2', urlencode( $variant ), $variantArticlePath );
					$url = str_replace( '$1', $dbkey, $url  );
				} else {
					$url = str_replace( '$1', $dbkey, $wgArticlePath );
					wfRunHooks( 'GetLocalURL::Article', array( &$this, &$url ) );
				}
			} else {
				global $wgActionPaths;
				$url = false;
				$matches = array();
				if ( !empty( $wgActionPaths ) &&
					preg_match( '/^(.*&|)action=([^&]*)(&(.*)|)$/', $query, $matches ) )
				{
					$action = urldecode( $matches[2] );
					if ( isset( $wgActionPaths[$action] ) ) {
						$query = $matches[1];
						if ( isset( $matches[4] ) ) {
							$query .= $matches[4];
						}
						$url = str_replace( '$1', $dbkey, $wgActionPaths[$action] );
						if ( $query != '' ) {
							$url = wfAppendQuery( $url, $query );
						}
					}
				}

				if ( $url === false ) {
					if ( $query == '-' ) {
						$query = '';
					}
					$url = "{$wgScript}?title={$dbkey}&{$query}";
				}
			}

			wfRunHooks( 'GetLocalURL::Internal', array( &$this, &$url, $query, $variant ) );

			// @todo FIXME: This causes breakage in various places when we
			// actually expected a local URL and end up with dupe prefixes.
			if ( $wgRequest->getVal( 'action' ) == 'render' ) {
				$url = $wgServer . $url;
			}
		}
		wfRunHooks( 'GetLocalURL', array( &$this, &$url, $query, $variant ) );
		return $url;
	}

	/**
	 * Get a URL that's the simplest URL that will be valid to link, locally,
	 * to the current Title.  It includes the fragment, but does not include
	 * the server unless action=render is used (or the link is external).  If
	 * there's a fragment but the prefixed text is empty, we just return a link
	 * to the fragment.
	 *
	 * The result obviously should not be URL-escaped, but does need to be
	 * HTML-escaped if it's being output in HTML.
	 *
	 * @param $query Array of Strings An associative array of key => value pairs for the
	 *   query string.  Keys and values will be escaped.
	 * @param $variant String language variant of URL (for sr, zh..).  Ignored
	 *   for external links.  Default is "false" (same variant as current page,
	 *   for anonymous users).
	 * @return String the URL
	 */
	public function getLinkUrl( $query = array(), $variant = false ) {
		wfProfileIn( __METHOD__ );
		if ( $this->isExternal() ) {
			$ret = $this->getFullURL( $query );
		} elseif ( $this->getPrefixedText() === '' && $this->getFragment() !== '' ) {
			$ret = $this->getFragmentForURL();
		} else {
			$ret = $this->getLocalURL( $query, $variant ) . $this->getFragmentForURL();
		}
		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Get an HTML-escaped version of the URL form, suitable for
	 * using in a link, without a server name or fragment
	 *
	 * @param $query String an optional query string
	 * @return String the URL
	 */
	public function escapeLocalURL( $query = '' ) {
		return htmlspecialchars( $this->getLocalURL( $query ) );
	}

	/**
	 * Get an HTML-escaped version of the URL form, suitable for
	 * using in a link, including the server name and fragment
	 *
	 * @param $query String an optional query string
	 * @return String the URL
	 */
	public function escapeFullURL( $query = '' ) {
		return htmlspecialchars( $this->getFullURL( $query ) );
	}
	
	/**
	 * HTML-escaped version of getCanonicalURL()
	 */
	public function escapeCanonicalURL( $query = '', $variant = false ) {
		return htmlspecialchars( $this->getCanonicalURL( $query, $variant ) );
	}

	/**
	 * Get the URL form for an internal link.
	 * - Used in various Squid-related code, in case we have a different
	 * internal hostname for the server from the exposed one.
	 * 
	 * This uses $wgInternalServer to qualify the path, or $wgServer
	 * if $wgInternalServer is not set. If the server variable used is
	 * protocol-relative, the URL will be expanded to http://
	 *
	 * @param $query String an optional query string
	 * @param $variant String language variant of url (for sr, zh..)
	 * @return String the URL
	 */
	public function getInternalURL( $query = '', $variant = false ) {
		if ( $this->isExternal( ) ) {
			$server = '';
		} else {
			global $wgInternalServer, $wgServer;
			$server = $wgInternalServer !== false ? $wgInternalServer : $wgServer;
		}
		$url = wfExpandUrl( $server . $this->getLocalURL( $query, $variant ), PROTO_HTTP );
		wfRunHooks( 'GetInternalURL', array( &$this, &$url, $query ) );
		return $url;
	}

	/**
	 * Get the URL for a canonical link, for use in things like IRC and
	 * e-mail notifications. Uses $wgCanonicalServer and the
	 * GetCanonicalURL hook.
	 * 
	 * NOTE: Unlike getInternalURL(), the canonical URL includes the fragment
	 * 
	 * @param $query string An optional query string
	 * @param $variant string Language variant of URL (for sr, zh, ...)
	 * @return string The URL
	 */
	public function getCanonicalURL( $query = '', $variant = false ) {
		global $wgCanonicalServer;
		$url = wfExpandUrl( $this->getLocalURL( $query, $variant ) . $this->getFragmentForURL(), PROTO_CANONICAL );
		wfRunHooks( '', array( &$this, &$url, $query ) );
		return $url;
	}

	/**
	 * Get the edit URL for this Title
	 *
	 * @return String the URL, or a null string if this is an
	 *  interwiki link
	 */
	public function getEditURL() {
		if ( $this->mInterwiki != '' ) {
			return '';
		}
		$s = $this->getLocalURL( 'action=edit' );

		return $s;
	}

	/**
	 * Get the HTML-escaped displayable text form.
	 * Used for the title field in <a> tags.
	 *
	 * @return String the text, including any prefixes
	 */
	public function getEscapedText() {
		return htmlspecialchars( $this->getPrefixedText() );
	}

	/**
	 * Is this Title interwiki?
	 *
	 * @return Bool
	 */
	public function isExternal() {
		return ( $this->mInterwiki != '' );
	}

	/**
	 * Is this page "semi-protected" - the *only* protection is autoconfirm?
	 *
	 * @param $action String Action to check (default: edit)
	 * @return Bool
	 */
	public function isSemiProtected( $action = 'edit' ) {
		if ( $this->exists() ) {
			$restrictions = $this->getRestrictions( $action );
			if ( count( $restrictions ) > 0 ) {
				foreach ( $restrictions as $restriction ) {
					if ( strtolower( $restriction ) != 'autoconfirmed' ) {
						return false;
					}
				}
			} else {
				# Not protected
				return false;
			}
			return true;
		} else {
			# If it doesn't exist, it can't be protected
			return false;
		}
	}

	/**
	 * Does the title correspond to a protected article?
	 *
	 * @param $action String the action the page is protected from,
	 * by default checks all actions.
	 * @return Bool
	 */
	public function isProtected( $action = '' ) {
		global $wgRestrictionLevels;

		$restrictionTypes = $this->getRestrictionTypes();

		# Special pages have inherent protection
		if( $this->getNamespace() == NS_SPECIAL ) {
			return true;
		}

		# Check regular protection levels
		foreach ( $restrictionTypes as $type ) {
			if ( $action == $type || $action == '' ) {
				$r = $this->getRestrictions( $type );
				foreach ( $wgRestrictionLevels as $level ) {
					if ( in_array( $level, $r ) && $level != '' ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Is this a conversion table for the LanguageConverter?
	 *
	 * @return Bool
	 */
	public function isConversionTable() {
		if(
			$this->getNamespace() == NS_MEDIAWIKI &&
			strpos( $this->getText(), 'Conversiontable' ) !== false
		)
		{
			return true;
		}

		return false;
	}

	/**
	 * Is $wgUser watching this page?
	 *
	 * @return Bool
	 */
	public function userIsWatching() {
		global $wgUser;

		if ( is_null( $this->mWatched ) ) {
			if ( NS_SPECIAL == $this->mNamespace || !$wgUser->isLoggedIn() ) {
				$this->mWatched = false;
			} else {
				$this->mWatched = $wgUser->isWatched( $this );
			}
		}
		return $this->mWatched;
	}

	/**
	 * Can $wgUser perform $action on this page?
	 * This skips potentially expensive cascading permission checks
	 * as well as avoids expensive error formatting
	 *
	 * Suitable for use for nonessential UI controls in common cases, but
	 * _not_ for functional access control.
	 *
	 * May provide false positives, but should never provide a false negative.
	 *
	 * @param $action String action that permission needs to be checked for
	 * @return Bool
	 */
	public function quickUserCan( $action ) {
		return $this->userCan( $action, false );
	}

	/**
	 * Determines if $user is unable to edit this page because it has been protected
	 * by $wgNamespaceProtection.
	 *
	 * @param $user User object to check permissions
	 * @return Bool
	 */
	public function isNamespaceProtected( User $user ) {
		global $wgNamespaceProtection;

		if ( isset( $wgNamespaceProtection[$this->mNamespace] ) ) {
			foreach ( (array)$wgNamespaceProtection[$this->mNamespace] as $right ) {
				if ( $right != '' && !$user->isAllowed( $right ) ) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Can $wgUser perform $action on this page?
	 *
	 * @param $action String action that permission needs to be checked for
	 * @param $doExpensiveQueries Bool Set this to false to avoid doing unnecessary queries.
	 * @return Bool
	 */
	public function userCan( $action, $doExpensiveQueries = true ) {
		global $wgUser;
		return ( $this->getUserPermissionsErrorsInternal( $action, $wgUser, $doExpensiveQueries, true ) === array() );
	}

	/**
	 * Can $user perform $action on this page?
	 *
	 * @todo FIXME: This *does not* check throttles (User::pingLimiter()).
	 *
	 * @param $action String action that permission needs to be checked for
	 * @param $user User to check
	 * @param $doExpensiveQueries Bool Set this to false to avoid doing unnecessary queries by
	 *   skipping checks for cascading protections and user blocks.
	 * @param $ignoreErrors Array of Strings Set this to a list of message keys whose corresponding errors may be ignored.
	 * @return Array of arguments to wfMsg to explain permissions problems.
	 */
	public function getUserPermissionsErrors( $action, $user, $doExpensiveQueries = true, $ignoreErrors = array() ) {
		$errors = $this->getUserPermissionsErrorsInternal( $action, $user, $doExpensiveQueries );

		// Remove the errors being ignored.
		foreach ( $errors as $index => $error ) {
			$error_key = is_array( $error ) ? $error[0] : $error;

			if ( in_array( $error_key, $ignoreErrors ) ) {
				unset( $errors[$index] );
			}
		}

		return $errors;
	}

	/**
	 * Permissions checks that fail most often, and which are easiest to test.
	 *
	 * @param $action String the action to check
	 * @param $user User user to check
	 * @param $errors Array list of current errors
	 * @param $doExpensiveQueries Boolean whether or not to perform expensive queries
	 * @param $short Boolean short circuit on first error
	 *
	 * @return Array list of errors
	 */
	private function checkQuickPermissions( $action, $user, $errors, $doExpensiveQueries, $short ) {
		$ns = $this->getNamespace();

		if ( $action == 'create' ) {
			if ( ( $this->isTalkPage() && !$user->isAllowed( 'createtalk', $ns ) ) ||
				 ( !$this->isTalkPage() && !$user->isAllowed( 'createpage', $ns ) ) ) {
				$errors[] = $user->isAnon() ? array( 'nocreatetext' ) : array( 'nocreate-loggedin' );
			}
		} elseif ( $action == 'move' ) {
			if ( !$user->isAllowed( 'move-rootuserpages', $ns )
					&& $ns == NS_USER && !$this->isSubpage() ) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = array( 'cant-move-user-page' );
			}

			// Check if user is allowed to move files if it's a file
			if ( $ns == NS_FILE && !$user->isAllowed( 'movefile', $ns ) ) {
				$errors[] = array( 'movenotallowedfile' );
			}

			if ( !$user->isAllowed( 'move', $ns) ) {
				// User can't move anything

				$userCanMove = in_array( 'move', User::getGroupPermissions(
					array( 'user' ), $ns ), true );
				$autoconfirmedCanMove = in_array( 'move', User::getGroupPermissions(
					array( 'autoconfirmed' ), $ns ), true );

				if ( $user->isAnon() && ( $userCanMove || $autoconfirmedCanMove ) ) {
					// custom message if logged-in users without any special rights can move
					$errors[] = array( 'movenologintext' );
				} else {
					$errors[] = array( 'movenotallowed' );
				}
			}
		} elseif ( $action == 'move-target' ) {
			if ( !$user->isAllowed( 'move', $ns ) ) {
				// User can't move anything
				$errors[] = array( 'movenotallowed' );
			} elseif ( !$user->isAllowed( 'move-rootuserpages', $ns )
					&& $ns == NS_USER && !$this->isSubpage() ) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = array( 'cant-move-to-user-page' );
			}
		} elseif ( !$user->isAllowed( $action, $ns ) ) {
			// We avoid expensive display logic for quickUserCan's and such
			$groups = false;
			if ( !$short ) {
				$groups = array_map( array( 'User', 'makeGroupLinkWiki' ),
					User::getGroupsWithPermission( $action, $ns ) );
			}

			if ( $groups ) {
				global $wgLang;
				$return = array(
					'badaccess-groups',
					$wgLang->commaList( $groups ),
					count( $groups )
				);
			} else {
				$return = array( 'badaccess-group0' );
			}
			$errors[] = $return;
		}

		return $errors;
	}

	/**
	 * Add the resulting error code to the errors array
	 *
	 * @param $errors Array list of current errors
	 * @param $result Mixed result of errors
	 *
	 * @return Array list of errors
	 */
	private function resultToError( $errors, $result ) {
		if ( is_array( $result ) && count( $result ) && !is_array( $result[0] ) ) {
			// A single array representing an error
			$errors[] = $result;
		} elseif ( is_array( $result ) && is_array( $result[0] ) ) {
			// A nested array representing multiple errors
			$errors = array_merge( $errors, $result );
		} elseif ( $result !== '' && is_string( $result ) ) {
			// A string representing a message-id
			$errors[] = array( $result );
		} elseif ( $result === false ) {
			// a generic "We don't want them to do that"
			$errors[] = array( 'badaccess-group0' );
		}
		return $errors;
	}

	/**
	 * Check various permission hooks
	 *
	 * @param $action String the action to check
	 * @param $user User user to check
	 * @param $errors Array list of current errors
	 * @param $doExpensiveQueries Boolean whether or not to perform expensive queries
	 * @param $short Boolean short circuit on first error
	 *
	 * @return Array list of errors
	 */
	private function checkPermissionHooks( $action, $user, $errors, $doExpensiveQueries, $short ) {
		// Use getUserPermissionsErrors instead
		$result = '';
		if ( !wfRunHooks( 'userCan', array( &$this, &$user, $action, &$result ) ) ) {
			return $result ? array() : array( array( 'badaccess-group0' ) );
		}
		// Check getUserPermissionsErrors hook
		if ( !wfRunHooks( 'getUserPermissionsErrors', array( &$this, &$user, $action, &$result ) ) ) {
			$errors = $this->resultToError( $errors, $result );
		}
		// Check getUserPermissionsErrorsExpensive hook
		if ( $doExpensiveQueries && !( $short && count( $errors ) > 0 ) &&
			 !wfRunHooks( 'getUserPermissionsErrorsExpensive', array( &$this, &$user, $action, &$result ) ) ) {
			$errors = $this->resultToError( $errors, $result );
		}

		return $errors;
	}

	/**
	 * Check permissions on special pages & namespaces
	 *
	 * @param $action String the action to check
	 * @param $user User user to check
	 * @param $errors Array list of current errors
	 * @param $doExpensiveQueries Boolean whether or not to perform expensive queries
	 * @param $short Boolean short circuit on first error
	 *
	 * @return Array list of errors
	 */
	private function checkSpecialsAndNSPermissions( $action, $user, $errors, $doExpensiveQueries, $short ) {
		# Only 'createaccount' and 'execute' can be performed on
		# special pages, which don't actually exist in the DB.
		$specialOKActions = array( 'createaccount', 'execute' );
		if ( NS_SPECIAL == $this->mNamespace && !in_array( $action, $specialOKActions ) ) {
			$errors[] = array( 'ns-specialprotected' );
		}

		# Check $wgNamespaceProtection for restricted namespaces
		if ( $this->isNamespaceProtected( $user ) ) {
			$ns = $this->mNamespace == NS_MAIN ?
				wfMsg( 'nstab-main' ) : $this->getNsText();
			$errors[] = $this->mNamespace == NS_MEDIAWIKI ?
				array( 'protectedinterface' ) : array( 'namespaceprotected',  $ns );
		}

		return $errors;
	}

	/**
	 * Check CSS/JS sub-page permissions
	 *
	 * @param $action String the action to check
	 * @param $user User user to check
	 * @param $errors Array list of current errors
	 * @param $doExpensiveQueries Boolean whether or not to perform expensive queries
	 * @param $short Boolean short circuit on first error
	 *
	 * @return Array list of errors
	 */
	private function checkCSSandJSPermissions( $action, $user, $errors, $doExpensiveQueries, $short ) {
		# Protect css/js subpages of user pages
		# XXX: this might be better using restrictions
		# XXX: right 'editusercssjs' is deprecated, for backward compatibility only
		if ( $action != 'patrol' && !$user->isAllowed( 'editusercssjs' )
				&& !preg_match( '/^' . preg_quote( $user->getName(), '/' ) . '\//', $this->mTextform ) ) {
			if ( $this->isCssSubpage() && !$user->isAllowed( 'editusercss' ) ) {
				$errors[] = array( 'customcssprotected' );
			} elseif ( $this->isJsSubpage() && !$user->isAllowed( 'edituserjs' ) ) {
				$errors[] = array( 'customjsprotected' );
			}
		}

		return $errors;
	}

	/**
	 * Check against page_restrictions table requirements on this
	 * page. The user must possess all required rights for this
	 * action.
	 *
	 * @param $action String the action to check
	 * @param $user User user to check
	 * @param $errors Array list of current errors
	 * @param $doExpensiveQueries Boolean whether or not to perform expensive queries
	 * @param $short Boolean short circuit on first error
	 *
	 * @return Array list of errors
	 */
	private function checkPageRestrictions( $action, $user, $errors, $doExpensiveQueries, $short ) {
		foreach ( $this->getRestrictions( $action ) as $right ) {
			// Backwards compatibility, rewrite sysop -> protect
			if ( $right == 'sysop' ) {
				$right = 'protect';
			}
			if ( $right != '' && !$user->isAllowed( $right, $this->mNamespace ) ) {
				// Users with 'editprotected' permission can edit protected pages
				if ( $action == 'edit' && $user->isAllowed( 'editprotected', $this->mNamespace ) ) {
					// Users with 'editprotected' permission cannot edit protected pages
					// with cascading option turned on.
					if ( $this->mCascadeRestriction ) {
						$errors[] = array( 'protectedpagetext', $right );
					}
				} else {
					$errors[] = array( 'protectedpagetext', $right );
				}
			}
		}

		return $errors;
	}

	/**
	 * Check restrictions on cascading pages.
	 *
	 * @param $action String the action to check
	 * @param $user User to check
	 * @param $errors Array list of current errors
	 * @param $doExpensiveQueries Boolean whether or not to perform expensive queries
	 * @param $short Boolean short circuit on first error
	 *
	 * @return Array list of errors
	 */
	private function checkCascadingSourcesRestrictions( $action, $user, $errors, $doExpensiveQueries, $short ) {
		if ( $doExpensiveQueries && !$this->isCssJsSubpage() ) {
			# We /could/ use the protection level on the source page, but it's
			# fairly ugly as we have to establish a precedence hierarchy for pages
			# included by multiple cascade-protected pages. So just restrict
			# it to people with 'protect' permission, as they could remove the
			# protection anyway.
			list( $cascadingSources, $restrictions ) = $this->getCascadeProtectionSources();
			# Cascading protection depends on more than this page...
			# Several cascading protected pages may include this page...
			# Check each cascading level
			# This is only for protection restrictions, not for all actions
			if ( isset( $restrictions[$action] ) ) {
				foreach ( $restrictions[$action] as $right ) {
					$right = ( $right == 'sysop' ) ? 'protect' : $right;
					if ( $right != '' && !$user->isAllowed( $right, $this->mNamespace ) ) {
						$pages = '';
						foreach ( $cascadingSources as $page )
							$pages .= '* [[:' . $page->getPrefixedText() . "]]\n";
						$errors[] = array( 'cascadeprotected', count( $cascadingSources ), $pages );
					}
				}
			}
		}

		return $errors;
	}

	/**
	 * Check action permissions not already checked in checkQuickPermissions
	 *
	 * @param $action String the action to check
	 * @param $user User to check
	 * @param $errors Array list of current errors
	 * @param $doExpensiveQueries Boolean whether or not to perform expensive queries
	 * @param $short Boolean short circuit on first error
	 *
	 * @return Array list of errors
	 */
	private function checkActionPermissions( $action, $user, $errors, $doExpensiveQueries, $short ) {
		if ( $action == 'protect' ) {
			if ( $this->getUserPermissionsErrors( 'edit', $user ) != array() ) {
				// If they can't edit, they shouldn't protect.
				$errors[] = array( 'protect-cantedit' );
			}
		} elseif ( $action == 'create' ) {
			$title_protection = $this->getTitleProtection();
			if( $title_protection ) {
				if( $title_protection['pt_create_perm'] == 'sysop' ) {
					$title_protection['pt_create_perm'] = 'protect'; // B/C
				}
				if( $title_protection['pt_create_perm'] == '' ||
						!$user->isAllowed( $title_protection['pt_create_perm'],
						$this->mNamespace ) ) {
					$errors[] = array( 'titleprotected', User::whoIs( $title_protection['pt_user'] ), $title_protection['pt_reason'] );
				}
			}
		} elseif ( $action == 'move' ) {
			// Check for immobile pages
			if ( !MWNamespace::isMovable( $this->mNamespace ) ) {
				// Specific message for this case
				$errors[] = array( 'immobile-source-namespace', $this->getNsText() );
			} elseif ( !$this->isMovable() ) {
				// Less specific message for rarer cases
				$errors[] = array( 'immobile-source-page' );
			}
		} elseif ( $action == 'move-target' ) {
			if ( !MWNamespace::isMovable( $this->mNamespace ) ) {
				$errors[] = array( 'immobile-target-namespace', $this->getNsText() );
			} elseif ( !$this->isMovable() ) {
				$errors[] = array( 'immobile-target-page' );
			}
		}
		return $errors;
	}

	/**
	 * Check that the user isn't blocked from editting.
	 *
	 * @param $action String the action to check
	 * @param $user User to check
	 * @param $errors Array list of current errors
	 * @param $doExpensiveQueries Boolean whether or not to perform expensive queries
	 * @param $short Boolean short circuit on first error
	 *
	 * @return Array list of errors
	 */
	private function checkUserBlock( $action, $user, $errors, $doExpensiveQueries, $short ) {
		if( !$doExpensiveQueries ) {
			return $errors;
		}

		global $wgContLang, $wgLang, $wgEmailConfirmToEdit;

		if ( $wgEmailConfirmToEdit && !$user->isEmailConfirmed() && $action != 'createaccount' ) {
			$errors[] = array( 'confirmedittext' );
		}

		if ( in_array( $action, array( 'read', 'createaccount', 'unblock' ) ) ){
			// Edit blocks should not affect reading.
			// Account creation blocks handled at userlogin.
			// Unblocking handled in SpecialUnblock
		} elseif( ( $action == 'edit' || $action == 'create' ) && !$user->isBlockedFrom( $this ) ){
			// Don't block the user from editing their own talk page unless they've been
			// explicitly blocked from that too.
		} elseif( $user->isBlocked() && $user->mBlock->prevents( $action ) !== false ) {
			$block = $user->mBlock;

			// This is from OutputPage::blockedPage
			// Copied at r23888 by werdna

			$id = $user->blockedBy();
			$reason = $user->blockedFor();
			if ( $reason == '' ) {
				$reason = wfMsg( 'blockednoreason' );
			}
			$ip = $user->getRequest()->getIP();

			if ( is_numeric( $id ) ) {
				$name = User::whoIs( $id );
			} else {
				$name = $id;
			}

			$link = '[[' . $wgContLang->getNsText( NS_USER ) . ":{$name}|{$name}]]";
			$blockid = $block->getId();
			$blockExpiry = $user->mBlock->mExpiry;
			$blockTimestamp = $wgLang->timeanddate( wfTimestamp( TS_MW, $user->mBlock->mTimestamp ), true );
			if ( $blockExpiry == 'infinity' ) {
				$blockExpiry = wfMessage( 'infiniteblock' )->text();
			} else {
				$blockExpiry = $wgLang->timeanddate( wfTimestamp( TS_MW, $blockExpiry ), true );
			}

			$intended = strval( $user->mBlock->getTarget() );

			$errors[] = array( ( $block->mAuto ? 'autoblockedtext' : 'blockedtext' ), $link, $reason, $ip, $name,
				$blockid, $blockExpiry, $intended, $blockTimestamp );
		}

		return $errors;
	}

	/**
	 * Can $user perform $action on this page? This is an internal function,
	 * which checks ONLY that previously checked by userCan (i.e. it leaves out
	 * checks on wfReadOnly() and blocks)
	 *
	 * @param $action String action that permission needs to be checked for
	 * @param $user User to check
	 * @param $doExpensiveQueries Bool Set this to false to avoid doing unnecessary queries.
	 * @param $short Bool Set this to true to stop after the first permission error.
	 * @return Array of arrays of the arguments to wfMsg to explain permissions problems.
	 */
	protected function getUserPermissionsErrorsInternal( $action, $user, $doExpensiveQueries = true, $short = false ) {
		wfProfileIn( __METHOD__ );

		$errors = array();
		$checks = array(
			'checkQuickPermissions',
			'checkPermissionHooks',
			'checkSpecialsAndNSPermissions',
			'checkCSSandJSPermissions',
			'checkPageRestrictions',
			'checkCascadingSourcesRestrictions',
			'checkActionPermissions',
			'checkUserBlock'
		);

		while( count( $checks ) > 0 &&
			   !( $short && count( $errors ) > 0 ) ) {
			$method = array_shift( $checks );
			$errors = $this->$method( $action, $user, $errors, $doExpensiveQueries, $short );
		}

		wfProfileOut( __METHOD__ );
		return $errors;
	}

	/**
	 * Is this title subject to title protection?
	 * Title protection is the one applied against creation of such title.
	 *
	 * @return Mixed An associative array representing any existent title
	 *   protection, or false if there's none.
	 */
	private function getTitleProtection() {
		// Can't protect pages in special namespaces
		if ( $this->getNamespace() < 0 ) {
			return false;
		}

		// Can't protect pages that exist.
		if ( $this->exists() ) {
			return false;
		}

		if ( !isset( $this->mTitleProtection ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'protected_titles', '*',
				array( 'pt_namespace' => $this->getNamespace(), 'pt_title' => $this->getDBkey() ),
				__METHOD__ );

			// fetchRow returns false if there are no rows.
			$this->mTitleProtection = $dbr->fetchRow( $res );
		}
		return $this->mTitleProtection;
	}

	/**
	 * Update the title protection status
	 *
	 * @param $create_perm String Permission required for creation
	 * @param $reason String Reason for protection
	 * @param $expiry String Expiry timestamp
	 * @return boolean true
	 */
	public function updateTitleProtection( $create_perm, $reason, $expiry ) {
		global $wgUser, $wgContLang;

		if ( $create_perm == implode( ',', $this->getRestrictions( 'create' ) )
			&& $expiry == $this->mRestrictionsExpiry['create'] ) {
			// No change
			return true;
		}

		list ( $namespace, $title ) = array( $this->getNamespace(), $this->getDBkey() );

		$dbw = wfGetDB( DB_MASTER );

		$encodedExpiry = $dbw->encodeExpiry( $expiry );

		$expiry_description = '';
		if ( $encodedExpiry != $dbw->getInfinity() ) {
			$expiry_description = ' (' . wfMsgForContent( 'protect-expiring', $wgContLang->timeanddate( $expiry ),
				$wgContLang->date( $expiry ) , $wgContLang->time( $expiry ) ) . ')';
		} else {
			$expiry_description .= ' (' . wfMsgForContent( 'protect-expiry-indefinite' ) . ')';
		}

		# Update protection table
		if ( $create_perm != '' ) {
			$this->mTitleProtection = array(
					'pt_namespace' => $namespace,
					'pt_title' => $title,
					'pt_create_perm' => $create_perm,
					'pt_timestamp' => $dbw->encodeExpiry( wfTimestampNow() ),
					'pt_expiry' => $encodedExpiry,
					'pt_user' => $wgUser->getId(),
					'pt_reason' => $reason,
				);
			$dbw->replace( 'protected_titles', array( array( 'pt_namespace', 'pt_title' ) ),
				$this->mTitleProtection, __METHOD__	);
		} else {
			$dbw->delete( 'protected_titles', array( 'pt_namespace' => $namespace,
				'pt_title' => $title ), __METHOD__ );
			$this->mTitleProtection = false;
		}

		# Update the protection log
		if ( $dbw->affectedRows() ) {
			$log = new LogPage( 'protect' );

			if ( $create_perm ) {
				$params = array( "[create=$create_perm] $expiry_description", '' );
				$log->addEntry( ( isset( $this->mRestrictions['create'] ) && $this->mRestrictions['create'] ) ? 'modify' : 'protect', $this, trim( $reason ), $params );
			} else {
				$log->addEntry( 'unprotect', $this, $reason );
			}
		}

		return true;
	}

	/**
	 * Remove any title protection due to page existing
	 */
	public function deleteTitleProtection() {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->delete(
			'protected_titles',
			array( 'pt_namespace' => $this->getNamespace(), 'pt_title' => $this->getDBkey() ),
			__METHOD__
		);
		$this->mTitleProtection = false;
	}

	/**
	 * Would anybody with sufficient privileges be able to move this page?
	 * Some pages just aren't movable.
	 *
	 * @return Bool TRUE or FALSE
	 */
	public function isMovable() {
		if ( !MWNamespace::isMovable( $this->getNamespace() ) || $this->getInterwiki() != '' ) {
			// Interwiki title or immovable namespace. Hooks don't get to override here
			return false;
		}
		
		$result = true;
		wfRunHooks( 'TitleIsMovable', array( $this, &$result ) );
		return $result;
	}

	/**
	 * Can $wgUser read this page?
	 *
	 * @return Bool
	 * @todo fold these checks into userCan()
	 */
	public function userCanRead() {
		global $wgUser, $wgGroupPermissions;

		static $useShortcut = null;

		# Initialize the $useShortcut boolean, to determine if we can skip quite a bit of code below
		if ( is_null( $useShortcut ) ) {
			global $wgRevokePermissions;
			$useShortcut = true;
			if ( empty( $wgGroupPermissions['*']['read'] ) ) {
				# Not a public wiki, so no shortcut
				$useShortcut = false;
			} elseif ( !empty( $wgRevokePermissions ) ) {
				/**
				 * Iterate through each group with permissions being revoked (key not included since we don't care
				 * what the group name is), then check if the read permission is being revoked. If it is, then
				 * we don't use the shortcut below since the user might not be able to read, even though anon
				 * reading is allowed.
				 */
				foreach ( $wgRevokePermissions as $perms ) {
					if ( !empty( $perms['read'] ) ) {
						# We might be removing the read right from the user, so no shortcut
						$useShortcut = false;
						break;
					}
				}
			}
		}

		$result = null;
		wfRunHooks( 'userCan', array( &$this, &$wgUser, 'read', &$result ) );
		if ( $result !== null ) {
			return $result;
		}

		# Shortcut for public wikis, allows skipping quite a bit of code
		if ( $useShortcut ) {
			return true;
		}

		if ( $wgUser->isAllowed( 'read' ) ) {
			return true;
		} else {
			global $wgWhitelistRead;

			# Always grant access to the login page.
			# Even anons need to be able to log in.
			if ( $this->isSpecial( 'Userlogin' ) || $this->isSpecial( 'ChangePassword' ) ) {
				return true;
			}

			# Bail out if there isn't whitelist
			if ( !is_array( $wgWhitelistRead ) ) {
				return false;
			}

			# Check for explicit whitelisting
			$name = $this->getPrefixedText();
			$dbName = $this->getPrefixedDBKey();
			// Check with and without underscores
			if ( in_array( $name, $wgWhitelistRead, true ) || in_array( $dbName, $wgWhitelistRead, true ) )
				return true;

			# Old settings might have the title prefixed with
			# a colon for main-namespace pages
			if ( $this->getNamespace() == NS_MAIN ) {
				if ( in_array( ':' . $name, $wgWhitelistRead ) ) {
					return true;
				}
			}

			# If it's a special page, ditch the subpage bit and check again
			if ( $this->getNamespace() == NS_SPECIAL ) {
				$name = $this->getDBkey();
				list( $name, /* $subpage */ ) = SpecialPageFactory::resolveAlias( $name );
				if ( $name === false ) {
					# Invalid special page, but we show standard login required message
					return false;
				}

				$pure = SpecialPage::getTitleFor( $name )->getPrefixedText();
				if ( in_array( $pure, $wgWhitelistRead, true ) ) {
					return true;
				}
			}

		}
		return false;
	}

	/**
	 * Is this the mainpage?
	 * @note Title::newFromText seams to be sufficiently optimized by the title
	 * cache that we don't need to over-optimize by doing direct comparisons and
	 * acidentally creating new bugs where $title->equals( Title::newFromText() )
	 * ends up reporting something differently than $title->isMainPage();
	 *
	 * @return Bool
	 */
	public function isMainPage() {
		return $this->equals( Title::newMainPage() );
	}

	/**
	 * Is this a talk page of some sort?
	 *
	 * @return Bool
	 */
	public function isTalkPage() {
		return MWNamespace::isTalk( $this->getNamespace() );
	}

	/**
	 * Is this a subpage?
	 *
	 * @return Bool
	 */
	public function isSubpage() {
		return MWNamespace::hasSubpages( $this->mNamespace )
			? strpos( $this->getText(), '/' ) !== false
			: false;
	}

	/**
	 * Does this have subpages?  (Warning, usually requires an extra DB query.)
	 *
	 * @return Bool
	 */
	public function hasSubpages() {
		if ( !MWNamespace::hasSubpages( $this->mNamespace ) ) {
			# Duh
			return false;
		}

		# We dynamically add a member variable for the purpose of this method
		# alone to cache the result.  There's no point in having it hanging
		# around uninitialized in every Title object; therefore we only add it
		# if needed and don't declare it statically.
		if ( isset( $this->mHasSubpages ) ) {
			return $this->mHasSubpages;
		}

		$subpages = $this->getSubpages( 1 );
		if ( $subpages instanceof TitleArray ) {
			return $this->mHasSubpages = (bool)$subpages->count();
		}
		return $this->mHasSubpages = false;
	}

	/**
	 * Get all subpages of this page.
	 *
	 * @param $limit Int maximum number of subpages to fetch; -1 for no limit
	 * @return mixed TitleArray, or empty array if this page's namespace
	 *  doesn't allow subpages
	 */
	public function getSubpages( $limit = -1 ) {
		if ( !MWNamespace::hasSubpages( $this->getNamespace() ) ) {
			return array();
		}

		$dbr = wfGetDB( DB_SLAVE );
		$conds['page_namespace'] = $this->getNamespace();
		$conds[] = 'page_title ' . $dbr->buildLike( $this->getDBkey() . '/', $dbr->anyString() );
		$options = array();
		if ( $limit > -1 ) {
			$options['LIMIT'] = $limit;
		}
		return $this->mSubpages = TitleArray::newFromResult(
			$dbr->select( 'page',
				array( 'page_id', 'page_namespace', 'page_title', 'page_is_redirect' ),
				$conds,
				__METHOD__,
				$options
			)
		);
	}

	/**
	 * Does that page contain wikitext, or it is JS, CSS or whatever?
	 *
	 * @return Bool
	 */
	public function isWikitextPage() {
		$retval = !$this->isCssOrJsPage() && !$this->isCssJsSubpage();
		wfRunHooks( 'TitleIsWikitextPage', array( $this, &$retval ) );
		return $retval;
	}

	/**
	 * Could this page contain custom CSS or JavaScript, based
	 * on the title?
	 *
	 * @return Bool
	 */
	public function isCssOrJsPage() {
		$retval = $this->mNamespace == NS_MEDIAWIKI
			&& preg_match( '!\.(?:css|js)$!u', $this->mTextform ) > 0;
		wfRunHooks( 'TitleIsCssOrJsPage', array( $this, &$retval ) );
		return $retval;
	}

	/**
	 * Is this a .css or .js subpage of a user page?
	 * @return Bool
	 */
	public function isCssJsSubpage() {
		return ( NS_USER == $this->mNamespace and preg_match( "/\\/.*\\.(?:css|js)$/", $this->mTextform ) );
	}

	/**
	 * Is this a *valid* .css or .js subpage of a user page?
	 *
	 * @return Bool
	 * @deprecated since 1.17
	 */
	public function isValidCssJsSubpage() {
		return $this->isCssJsSubpage();
	}

	/**
	 * Trim down a .css or .js subpage title to get the corresponding skin name
	 *
	 * @return string containing skin name from .css or .js subpage title
	 */
	public function getSkinFromCssJsSubpage() {
		$subpage = explode( '/', $this->mTextform );
		$subpage = $subpage[ count( $subpage ) - 1 ];
		return( str_replace( array( '.css', '.js' ), array( '', '' ), $subpage ) );
	}

	/**
	 * Is this a .css subpage of a user page?
	 *
	 * @return Bool
	 */
	public function isCssSubpage() {
		return ( NS_USER == $this->mNamespace && preg_match( "/\\/.*\\.css$/", $this->mTextform ) );
	}

	/**
	 * Is this a .js subpage of a user page?
	 *
	 * @return Bool
	 */
	public function isJsSubpage() {
		return ( NS_USER == $this->mNamespace && preg_match( "/\\/.*\\.js$/", $this->mTextform ) );
	}

	/**
	 * Protect css subpages of user pages: can $wgUser edit
	 * this page?
	 *
	 * @deprecated in 1.19; will be removed in 1.20. Use getUserPermissionsErrors() instead.
	 * @return Bool
	 */
	public function userCanEditCssSubpage() {
		global $wgUser;
		wfDeprecated( __METHOD__ );
		return ( ( $wgUser->isAllowedAll( 'editusercssjs', 'editusercss' ) )
			|| preg_match( '/^' . preg_quote( $wgUser->getName(), '/' ) . '\//', $this->mTextform ) );
	}

	/**
	 * Protect js subpages of user pages: can $wgUser edit
	 * this page?
	 *
	 * @deprecated in 1.19; will be removed in 1.20. Use getUserPermissionsErrors() instead.
	 * @return Bool
	 */
	public function userCanEditJsSubpage() {
		global $wgUser;
		wfDeprecated( __METHOD__ );
		return ( ( $wgUser->isAllowedAll( 'editusercssjs', 'edituserjs' ) )
			   || preg_match( '/^' . preg_quote( $wgUser->getName(), '/' ) . '\//', $this->mTextform ) );
	}

	/**
	 * Cascading protection: Return true if cascading restrictions apply to this page, false if not.
	 *
	 * @return Bool If the page is subject to cascading restrictions.
	 */
	public function isCascadeProtected() {
		list( $sources, /* $restrictions */ ) = $this->getCascadeProtectionSources( false );
		return ( $sources > 0 );
	}

	/**
	 * Cascading protection: Get the source of any cascading restrictions on this page.
	 *
	 * @param $getPages Bool Whether or not to retrieve the actual pages
	 *        that the restrictions have come from.
	 * @return Mixed Array of Title objects of the pages from which cascading restrictions
	 *     have come, false for none, or true if such restrictions exist, but $getPages
	 *     was not set.  The restriction array is an array of each type, each of which
	 *     contains a array of unique groups.
	 */
	public function getCascadeProtectionSources( $getPages = true ) {
		global $wgContLang;
		$pagerestrictions = array();

		if ( isset( $this->mCascadeSources ) && $getPages ) {
			return array( $this->mCascadeSources, $this->mCascadingRestrictions );
		} elseif ( isset( $this->mHasCascadingRestrictions ) && !$getPages ) {
			return array( $this->mHasCascadingRestrictions, $pagerestrictions );
		}

		wfProfileIn( __METHOD__ );

		$dbr = wfGetDB( DB_SLAVE );

		if ( $this->getNamespace() == NS_FILE ) {
			$tables = array( 'imagelinks', 'page_restrictions' );
			$where_clauses = array(
				'il_to' => $this->getDBkey(),
				'il_from=pr_page',
				'pr_cascade' => 1
			);
		} else {
			$tables = array( 'templatelinks', 'page_restrictions' );
			$where_clauses = array(
				'tl_namespace' => $this->getNamespace(),
				'tl_title' => $this->getDBkey(),
				'tl_from=pr_page',
				'pr_cascade' => 1
			);
		}

		if ( $getPages ) {
			$cols = array( 'pr_page', 'page_namespace', 'page_title',
						   'pr_expiry', 'pr_type', 'pr_level' );
			$where_clauses[] = 'page_id=pr_page';
			$tables[] = 'page';
		} else {
			$cols = array( 'pr_expiry' );
		}

		$res = $dbr->select( $tables, $cols, $where_clauses, __METHOD__ );

		$sources = $getPages ? array() : false;
		$now = wfTimestampNow();
		$purgeExpired = false;

		foreach ( $res as $row ) {
			$expiry = $wgContLang->formatExpiry( $row->pr_expiry, TS_MW );
			if ( $expiry > $now ) {
				if ( $getPages ) {
					$page_id = $row->pr_page;
					$page_ns = $row->page_namespace;
					$page_title = $row->page_title;
					$sources[$page_id] = Title::makeTitle( $page_ns, $page_title );
					# Add groups needed for each restriction type if its not already there
					# Make sure this restriction type still exists

					if ( !isset( $pagerestrictions[$row->pr_type] ) ) {
						$pagerestrictions[$row->pr_type] = array();
					}

					if ( isset( $pagerestrictions[$row->pr_type] ) &&
						 !in_array( $row->pr_level, $pagerestrictions[$row->pr_type] ) ) {
						$pagerestrictions[$row->pr_type][] = $row->pr_level;
					}
				} else {
					$sources = true;
				}
			} else {
				// Trigger lazy purge of expired restrictions from the db
				$purgeExpired = true;
			}
		}
		if ( $purgeExpired ) {
			Title::purgeExpiredRestrictions();
		}

		if ( $getPages ) {
			$this->mCascadeSources = $sources;
			$this->mCascadingRestrictions = $pagerestrictions;
		} else {
			$this->mHasCascadingRestrictions = $sources;
		}

		wfProfileOut( __METHOD__ );
		return array( $sources, $pagerestrictions );
	}

	/**
	 * Returns cascading restrictions for the current article
	 *
	 * @return Boolean
	 */
	function areRestrictionsCascading() {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}

		return $this->mCascadeRestriction;
	}

	/**
	 * Loads a string into mRestrictions array
	 *
	 * @param $res Resource restrictions as an SQL result.
	 * @param $oldFashionedRestrictions String comma-separated list of page
	 *        restrictions from page table (pre 1.10)
	 */
	private function loadRestrictionsFromResultWrapper( $res, $oldFashionedRestrictions = null ) {
		$rows = array();

		foreach ( $res as $row ) {
			$rows[] = $row;
		}

		$this->loadRestrictionsFromRows( $rows, $oldFashionedRestrictions );
	}

	/**
	 * Compiles list of active page restrictions from both page table (pre 1.10)
	 * and page_restrictions table for this existing page.
	 * Public for usage by LiquidThreads.
	 *
	 * @param $rows array of db result objects
	 * @param $oldFashionedRestrictions string comma-separated list of page
	 *        restrictions from page table (pre 1.10)
	 */
	public function loadRestrictionsFromRows( $rows, $oldFashionedRestrictions = null ) {
		global $wgContLang;
		$dbr = wfGetDB( DB_SLAVE );

		$restrictionTypes = $this->getRestrictionTypes();

		foreach ( $restrictionTypes as $type ) {
			$this->mRestrictions[$type] = array();
			$this->mRestrictionsExpiry[$type] = $wgContLang->formatExpiry( '', TS_MW );
		}

		$this->mCascadeRestriction = false;

		# Backwards-compatibility: also load the restrictions from the page record (old format).

		if ( $oldFashionedRestrictions === null ) {
			$oldFashionedRestrictions = $dbr->selectField( 'page', 'page_restrictions',
				array( 'page_id' => $this->getArticleId() ), __METHOD__ );
		}

		if ( $oldFashionedRestrictions != '' ) {

			foreach ( explode( ':', trim( $oldFashionedRestrictions ) ) as $restrict ) {
				$temp = explode( '=', trim( $restrict ) );
				if ( count( $temp ) == 1 ) {
					// old old format should be treated as edit/move restriction
					$this->mRestrictions['edit'] = explode( ',', trim( $temp[0] ) );
					$this->mRestrictions['move'] = explode( ',', trim( $temp[0] ) );
				} else {
					$this->mRestrictions[$temp[0]] = explode( ',', trim( $temp[1] ) );
				}
			}

			$this->mOldRestrictions = true;

		}

		if ( count( $rows ) ) {
			# Current system - load second to make them override.
			$now = wfTimestampNow();
			$purgeExpired = false;

			# Cycle through all the restrictions.
			foreach ( $rows as $row ) {

				// Don't take care of restrictions types that aren't allowed
				if ( !in_array( $row->pr_type, $restrictionTypes ) )
					continue;

				// This code should be refactored, now that it's being used more generally,
				// But I don't really see any harm in leaving it in Block for now -werdna
				$expiry = $wgContLang->formatExpiry( $row->pr_expiry, TS_MW );

				// Only apply the restrictions if they haven't expired!
				if ( !$expiry || $expiry > $now ) {
					$this->mRestrictionsExpiry[$row->pr_type] = $expiry;
					$this->mRestrictions[$row->pr_type] = explode( ',', trim( $row->pr_level ) );

					$this->mCascadeRestriction |= $row->pr_cascade;
				} else {
					// Trigger a lazy purge of expired restrictions
					$purgeExpired = true;
				}
			}

			if ( $purgeExpired ) {
				Title::purgeExpiredRestrictions();
			}
		}

		$this->mRestrictionsLoaded = true;
	}

	/**
	 * Load restrictions from the page_restrictions table
	 *
	 * @param $oldFashionedRestrictions String comma-separated list of page
	 *        restrictions from page table (pre 1.10)
	 */
	public function loadRestrictions( $oldFashionedRestrictions = null ) {
		global $wgContLang;
		if ( !$this->mRestrictionsLoaded ) {
			if ( $this->exists() ) {
				$dbr = wfGetDB( DB_SLAVE );

				$res = $dbr->select(
					'page_restrictions',
					'*',
					array( 'pr_page' => $this->getArticleId() ),
					__METHOD__
				);

				$this->loadRestrictionsFromResultWrapper( $res, $oldFashionedRestrictions );
			} else {
				$title_protection = $this->getTitleProtection();

				if ( $title_protection ) {
					$now = wfTimestampNow();
					$expiry = $wgContLang->formatExpiry( $title_protection['pt_expiry'], TS_MW );

					if ( !$expiry || $expiry > $now ) {
						// Apply the restrictions
						$this->mRestrictionsExpiry['create'] = $expiry;
						$this->mRestrictions['create'] = explode( ',', trim( $title_protection['pt_create_perm'] ) );
					} else { // Get rid of the old restrictions
						Title::purgeExpiredRestrictions();
						$this->mTitleProtection = false;
					}
				} else {
					$this->mRestrictionsExpiry['create'] = $wgContLang->formatExpiry( '', TS_MW );
				}
				$this->mRestrictionsLoaded = true;
			}
		}
	}

	/**
	 * Purge expired restrictions from the page_restrictions table
	 */
	static function purgeExpiredRestrictions() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete(
			'page_restrictions',
			array( 'pr_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ),
			__METHOD__
		);

		$dbw->delete(
			'protected_titles',
			array( 'pt_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ),
			__METHOD__
		);
	}

	/**
	 * Accessor/initialisation for mRestrictions
	 *
	 * @param $action String action that permission needs to be checked for
	 * @return Array of Strings the array of groups allowed to edit this article
	 */
	public function getRestrictions( $action ) {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}
		return isset( $this->mRestrictions[$action] )
				? $this->mRestrictions[$action]
				: array();
	}

	/**
	 * Get the expiry time for the restriction against a given action
	 *
	 * @return String|Bool 14-char timestamp, or 'infinity' if the page is protected forever
	 * 	or not protected at all, or false if the action is not recognised.
	 */
	public function getRestrictionExpiry( $action ) {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}
		return isset( $this->mRestrictionsExpiry[$action] ) ? $this->mRestrictionsExpiry[$action] : false;
	}

	/**
	 * Is there a version of this page in the deletion archive?
	 *
	 * @param $includeSuppressed Boolean Include suppressed revisions?
	 * @return Int the number of archived revisions
	 */
	public function isDeleted( $includeSuppressed = false ) {
		if ( $this->getNamespace() < 0 ) {
			$n = 0;
		} else {
			$dbr = wfGetDB( DB_SLAVE );
			$conditions = array( 'ar_namespace' => $this->getNamespace(), 'ar_title' => $this->getDBkey() );

			if( !$includeSuppressed ) {
				$suppressedTextBits = Revision::DELETED_TEXT | Revision::DELETED_RESTRICTED;
				$conditions[] = $dbr->bitAnd('ar_deleted', $suppressedTextBits ) .
				' != ' . $suppressedTextBits;
			}

			$n = $dbr->selectField( 'archive', 'COUNT(*)',
				$conditions,
				__METHOD__
			);
			if ( $this->getNamespace() == NS_FILE ) {
				$fconditions = array( 'fa_name' => $this->getDBkey() );
				if( !$includeSuppressed ) {
					$suppressedTextBits = File::DELETED_FILE | File::DELETED_RESTRICTED;
					$fconditions[] = $dbr->bitAnd('fa_deleted', $suppressedTextBits ) .
					' != ' . $suppressedTextBits;
				}

				$n += $dbr->selectField( 'filearchive',
					'COUNT(*)',
					$fconditions,
					__METHOD__
				);
			}
		}
		return (int)$n;
	}

	/**
	 * Is there a version of this page in the deletion archive?
	 *
	 * @return Boolean
	 */
	public function isDeletedQuick() {
		if ( $this->getNamespace() < 0 ) {
			return false;
		}
		$dbr = wfGetDB( DB_SLAVE );
		$deleted = (bool)$dbr->selectField( 'archive', '1',
			array( 'ar_namespace' => $this->getNamespace(), 'ar_title' => $this->getDBkey() ),
			__METHOD__
		);
		if ( !$deleted && $this->getNamespace() == NS_FILE ) {
			$deleted = (bool)$dbr->selectField( 'filearchive', '1',
				array( 'fa_name' => $this->getDBkey() ),
				__METHOD__
			);
		}
		return $deleted;
	}

	/**
	 * Get the article ID for this Title from the link cache,
	 * adding it if necessary
	 *
	 * @param $flags Int a bit field; may be Title::GAID_FOR_UPDATE to select
	 *  for update
	 * @return Int the ID
	 */
	public function getArticleID( $flags = 0 ) {
		if ( $this->getNamespace() < 0 ) {
			return $this->mArticleID = 0;
		}
		$linkCache = LinkCache::singleton();
		if ( $flags & self::GAID_FOR_UPDATE ) {
			$oldUpdate = $linkCache->forUpdate( true );
			$linkCache->clearLink( $this );
			$this->mArticleID = $linkCache->addLinkObj( $this );
			$linkCache->forUpdate( $oldUpdate );
		} else {
			if ( -1 == $this->mArticleID ) {
				$this->mArticleID = $linkCache->addLinkObj( $this );
			}
		}
		return $this->mArticleID;
	}

	/**
	 * Is this an article that is a redirect page?
	 * Uses link cache, adding it if necessary
	 *
	 * @param $flags Int a bit field; may be Title::GAID_FOR_UPDATE to select for update
	 * @return Bool
	 */
	public function isRedirect( $flags = 0 ) {
		if ( !is_null( $this->mRedirect ) ) {
			return $this->mRedirect;
		}
		# Calling getArticleID() loads the field from cache as needed
		if ( !$this->getArticleID( $flags ) ) {
			return $this->mRedirect = false;
		}
		$linkCache = LinkCache::singleton();
		$this->mRedirect = (bool)$linkCache->getGoodLinkFieldObj( $this, 'redirect' );

		return $this->mRedirect;
	}

	/**
	 * What is the length of this page?
	 * Uses link cache, adding it if necessary
	 *
	 * @param $flags Int a bit field; may be Title::GAID_FOR_UPDATE to select for update
	 * @return Int
	 */
	public function getLength( $flags = 0 ) {
		if ( $this->mLength != -1 ) {
			return $this->mLength;
		}
		# Calling getArticleID() loads the field from cache as needed
		if ( !$this->getArticleID( $flags ) ) {
			return $this->mLength = 0;
		}
		$linkCache = LinkCache::singleton();
		$this->mLength = intval( $linkCache->getGoodLinkFieldObj( $this, 'length' ) );

		return $this->mLength;
	}

	/**
	 * What is the page_latest field for this page?
	 *
	 * @param $flags Int a bit field; may be Title::GAID_FOR_UPDATE to select for update
	 * @return Int or 0 if the page doesn't exist
	 */
	public function getLatestRevID( $flags = 0 ) {
		if ( $this->mLatestID !== false ) {
			return intval( $this->mLatestID );
		}
		# Calling getArticleID() loads the field from cache as needed
		if ( !$this->getArticleID( $flags ) ) {
			return $this->mLatestID = 0;
		}
		$linkCache = LinkCache::singleton();
		$this->mLatestID = intval( $linkCache->getGoodLinkFieldObj( $this, 'revision' ) );

		return $this->mLatestID;
	}

	/**
	 * This clears some fields in this object, and clears any associated
	 * keys in the "bad links" section of the link cache.
	 *
	 * - This is called from Article::doEdit() and Article::insertOn() to allow
	 * loading of the new page_id. It's also called from
	 * Article::doDeleteArticle()
	 *
	 * @param $newid Int the new Article ID
	 */
	public function resetArticleID( $newid ) {
		$linkCache = LinkCache::singleton();
		$linkCache->clearLink( $this );

		if ( $newid === false ) {
			$this->mArticleID = -1;
		} else {
			$this->mArticleID = intval( $newid );
		}
		$this->mRestrictionsLoaded = false;
		$this->mRestrictions = array();
		$this->mRedirect = null;
		$this->mLength = -1;
		$this->mLatestID = false;
	}

	/**
	 * Updates page_touched for this page; called from LinksUpdate.php
	 *
	 * @return Bool true if the update succeded
	 */
	public function invalidateCache() {
		if ( wfReadOnly() ) {
			return;
		}
		$dbw = wfGetDB( DB_MASTER );
		$success = $dbw->update(
			'page',
			array( 'page_touched' => $dbw->timestamp() ),
			$this->pageCond(),
			__METHOD__
		);
		HTMLFileCache::clearFileCache( $this );
		return $success;
	}

	/**
	 * Prefix some arbitrary text with the namespace or interwiki prefix
	 * of this object
	 *
	 * @param $name String the text
	 * @return String the prefixed text
	 * @private
	 */
	private function prefix( $name ) {
		$p = '';
		if ( $this->mInterwiki != '' ) {
			$p = $this->mInterwiki . ':';
		}

		if ( 0 != $this->mNamespace ) {
			$p .= $this->getNsText() . ':';
		}
		return $p . $name;
	}

	/**
	 * Returns a simple regex that will match on characters and sequences invalid in titles.
	 * Note that this doesn't pick up many things that could be wrong with titles, but that
	 * replacing this regex with something valid will make many titles valid.
	 *
	 * @return String regex string
	 */
	static function getTitleInvalidRegex() {
		static $rxTc = false;
		if ( !$rxTc ) {
			# Matching titles will be held as illegal.
			$rxTc = '/' .
				# Any character not allowed is forbidden...
				'[^' . Title::legalChars() . ']' .
				# URL percent encoding sequences interfere with the ability
				# to round-trip titles -- you can't link to them consistently.
				'|%[0-9A-Fa-f]{2}' .
				# XML/HTML character references produce similar issues.
				'|&[A-Za-z0-9\x80-\xff]+;' .
				'|&#[0-9]+;' .
				'|&#x[0-9A-Fa-f]+;' .
				'/S';
		}

		return $rxTc;
	}

	/**
	 * Capitalize a text string for a title if it belongs to a namespace that capitalizes
	 *
	 * @param $text String containing title to capitalize
	 * @param $ns int namespace index, defaults to NS_MAIN
	 * @return String containing capitalized title
	 */
	public static function capitalize( $text, $ns = NS_MAIN ) {
		global $wgContLang;

		if ( MWNamespace::isCapitalized( $ns ) ) {
			return $wgContLang->ucfirst( $text );
		} else {
			return $text;
		}
	}

	/**
	 * Secure and split - main initialisation function for this object
	 *
	 * Assumes that mDbkeyform has been set, and is urldecoded
	 * and uses underscores, but not otherwise munged.  This function
	 * removes illegal characters, splits off the interwiki and
	 * namespace prefixes, sets the other forms, and canonicalizes
	 * everything.
	 *
	 * @return Bool true on success
	 */
	private function secureAndSplit() {
		global $wgContLang, $wgLocalInterwiki;

		# Initialisation
		$this->mInterwiki = $this->mFragment = '';
		$this->mNamespace = $this->mDefaultNamespace; # Usually NS_MAIN

		$dbkey = $this->mDbkeyform;

		# Strip Unicode bidi override characters.
		# Sometimes they slip into cut-n-pasted page titles, where the
		# override chars get included in list displays.
		$dbkey = preg_replace( '/\xE2\x80[\x8E\x8F\xAA-\xAE]/S', '', $dbkey );

		# Clean up whitespace
		# Note: use of the /u option on preg_replace here will cause
		# input with invalid UTF-8 sequences to be nullified out in PHP 5.2.x,
		# conveniently disabling them.
		$dbkey = preg_replace( '/[ _\xA0\x{1680}\x{180E}\x{2000}-\x{200A}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+/u', '_', $dbkey );
		$dbkey = trim( $dbkey, '_' );

		if ( $dbkey == '' ) {
			return false;
		}

		if ( false !== strpos( $dbkey, UTF8_REPLACEMENT ) ) {
			# Contained illegal UTF-8 sequences or forbidden Unicode chars.
			return false;
		}

		$this->mDbkeyform = $dbkey;

		# Initial colon indicates main namespace rather than specified default
		# but should not create invalid {ns,title} pairs such as {0,Project:Foo}
		if ( ':' == $dbkey[0] ) {
			$this->mNamespace = NS_MAIN;
			$dbkey = substr( $dbkey, 1 ); # remove the colon but continue processing
			$dbkey = trim( $dbkey, '_' ); # remove any subsequent whitespace
		}

		# Namespace or interwiki prefix
		$firstPass = true;
		$prefixRegexp = "/^(.+?)_*:_*(.*)$/S";
		do {
			$m = array();
			if ( preg_match( $prefixRegexp, $dbkey, $m ) ) {
				$p = $m[1];
				if ( ( $ns = $wgContLang->getNsIndex( $p ) ) !== false ) {
					# Ordinary namespace
					$dbkey = $m[2];
					$this->mNamespace = $ns;
					# For Talk:X pages, check if X has a "namespace" prefix
					if ( $ns == NS_TALK && preg_match( $prefixRegexp, $dbkey, $x ) ) {
						if ( $wgContLang->getNsIndex( $x[1] ) ) {
							# Disallow Talk:File:x type titles...
							return false;
						} elseif ( Interwiki::isValidInterwiki( $x[1] ) ) {
							# Disallow Talk:Interwiki:x type titles...
							return false;
						}
					}
				} elseif ( Interwiki::isValidInterwiki( $p ) ) {
					if ( !$firstPass ) {
						# Can't make a local interwiki link to an interwiki link.
						# That's just crazy!
						return false;
					}

					# Interwiki link
					$dbkey = $m[2];
					$this->mInterwiki = $wgContLang->lc( $p );

					# Redundant interwiki prefix to the local wiki
					if ( $wgLocalInterwiki !== false
						&& 0 == strcasecmp( $this->mInterwiki, $wgLocalInterwiki ) )
					{
						if ( $dbkey == '' ) {
							# Can't have an empty self-link
							return false;
						}
						$this->mInterwiki = '';
						$firstPass = false;
						# Do another namespace split...
						continue;
					}

					# If there's an initial colon after the interwiki, that also
					# resets the default namespace
					if ( $dbkey !== '' && $dbkey[0] == ':' ) {
						$this->mNamespace = NS_MAIN;
						$dbkey = substr( $dbkey, 1 );
					}
				}
				# If there's no recognized interwiki or namespace,
				# then let the colon expression be part of the title.
			}
			break;
		} while ( true );

		# We already know that some pages won't be in the database!
		if ( $this->mInterwiki != '' || NS_SPECIAL == $this->mNamespace ) {
			$this->mArticleID = 0;
		}
		$fragment = strstr( $dbkey, '#' );
		if ( false !== $fragment ) {
			$this->setFragment( $fragment );
			$dbkey = substr( $dbkey, 0, strlen( $dbkey ) - strlen( $fragment ) );
			# remove whitespace again: prevents "Foo_bar_#"
			# becoming "Foo_bar_"
			$dbkey = preg_replace( '/_*$/', '', $dbkey );
		}

		# Reject illegal characters.
		$rxTc = self::getTitleInvalidRegex();
		if ( preg_match( $rxTc, $dbkey ) ) {
			return false;
		}

		# Pages with "/./" or "/../" appearing in the URLs will often be un-
		# reachable due to the way web browsers deal with 'relative' URLs.
		# Also, they conflict with subpage syntax.  Forbid them explicitly.
		if ( strpos( $dbkey, '.' ) !== false &&
			 ( $dbkey === '.' || $dbkey === '..' ||
			   strpos( $dbkey, './' ) === 0  ||
			   strpos( $dbkey, '../' ) === 0 ||
			   strpos( $dbkey, '/./' ) !== false ||
			   strpos( $dbkey, '/../' ) !== false  ||
			   substr( $dbkey, -2 ) == '/.' ||
			   substr( $dbkey, -3 ) == '/..' ) )
		{
			return false;
		}

		# Magic tilde sequences? Nu-uh!
		if ( strpos( $dbkey, '~~~' ) !== false ) {
			return false;
		}

		# Limit the size of titles to 255 bytes. This is typically the size of the
		# underlying database field. We make an exception for special pages, which
		# don't need to be stored in the database, and may edge over 255 bytes due
		# to subpage syntax for long titles, e.g. [[Special:Block/Long name]]
		if ( ( $this->mNamespace != NS_SPECIAL && strlen( $dbkey ) > 255 ) ||
		  strlen( $dbkey ) > 512 )
		{
			return false;
		}

		# Normally, all wiki links are forced to have an initial capital letter so [[foo]]
		# and [[Foo]] point to the same place.  Don't force it for interwikis, since the
		# other site might be case-sensitive.
		$this->mUserCaseDBKey = $dbkey;
		if ( $this->mInterwiki == '' ) {
			$dbkey = self::capitalize( $dbkey, $this->mNamespace );
		}

		# Can't make a link to a namespace alone... "empty" local links can only be
		# self-links with a fragment identifier.
		if ( $dbkey == '' && $this->mInterwiki == '' && $this->mNamespace != NS_MAIN ) {
			return false;
		}

		// Allow IPv6 usernames to start with '::' by canonicalizing IPv6 titles.
		// IP names are not allowed for accounts, and can only be referring to
		// edits from the IP. Given '::' abbreviations and caps/lowercaps,
		// there are numerous ways to present the same IP. Having sp:contribs scan
		// them all is silly and having some show the edits and others not is
		// inconsistent. Same for talk/userpages. Keep them normalized instead.
		$dbkey = ( $this->mNamespace == NS_USER || $this->mNamespace == NS_USER_TALK )
			? IP::sanitizeIP( $dbkey )
			: $dbkey;

		// Any remaining initial :s are illegal.
		if ( $dbkey !== '' && ':' == $dbkey { 0 } ) {
			return false;
		}

		# Fill fields
		$this->mDbkeyform = $dbkey;
		$this->mUrlform = wfUrlencode( $dbkey );

		$this->mTextform = str_replace( '_', ' ', $dbkey );

		return true;
	}

	/**
	 * Set the fragment for this title. Removes the first character from the
	 * specified fragment before setting, so it assumes you're passing it with
	 * an initial "#".
	 *
	 * Deprecated for public use, use Title::makeTitle() with fragment parameter.
	 * Still in active use privately.
	 *
	 * @param $fragment String text
	 */
	public function setFragment( $fragment ) {
		$this->mFragment = str_replace( '_', ' ', substr( $fragment, 1 ) );
	}

	public function setInterwiki( $interwiki ) {
		$this->mInterwiki = $interwiki;
	}

	/**
	 * Get a Title object associated with the talk page of this article
	 *
	 * @return Title the object for the talk page
	 */
	public function getTalkPage() {
		return Title::makeTitle( MWNamespace::getTalk( $this->getNamespace() ), $this->getDBkey() );
	}

	/**
	 * Get a title object associated with the subject page of this
	 * talk page
	 *
	 * @return Title the object for the subject page
	 */
	public function getSubjectPage() {
		// Is this the same title?
		$subjectNS = MWNamespace::getSubject( $this->getNamespace() );
		if ( $this->getNamespace() == $subjectNS ) {
			return $this;
		}
		return Title::makeTitle( $subjectNS, $this->getDBkey() );
	}

	/**
	 * Get an array of Title objects linking to this Title
	 * Also stores the IDs in the link cache.
	 *
	 * WARNING: do not use this function on arbitrary user-supplied titles!
	 * On heavily-used templates it will max out the memory.
	 *
	 * @param $options Array: may be FOR UPDATE
	 * @param $table String: table name
	 * @param $prefix String: fields prefix
	 * @return Array of Title objects linking here
	 */
	public function getLinksTo( $options = array(), $table = 'pagelinks', $prefix = 'pl' ) {
		$linkCache = LinkCache::singleton();

		if ( count( $options ) > 0 ) {
			$db = wfGetDB( DB_MASTER );
		} else {
			$db = wfGetDB( DB_SLAVE );
		}

		$res = $db->select(
			array( 'page', $table ),
			array( 'page_namespace', 'page_title', 'page_id', 'page_len', 'page_is_redirect', 'page_latest' ),
			array(
				"{$prefix}_from=page_id",
				"{$prefix}_namespace" => $this->getNamespace(),
				"{$prefix}_title"     => $this->getDBkey() ),
			__METHOD__,
			$options
		);

		$retVal = array();
		if ( $db->numRows( $res ) ) {
			foreach ( $res as $row ) {
				$titleObj = Title::makeTitle( $row->page_namespace, $row->page_title );
				if ( $titleObj ) {
					$linkCache->addGoodLinkObj( $row->page_id, $titleObj, $row->page_len, $row->page_is_redirect, $row->page_latest );
					$retVal[] = $titleObj;
				}
			}
		}
		return $retVal;
	}

	/**
	 * Get an array of Title objects using this Title as a template
	 * Also stores the IDs in the link cache.
	 *
	 * WARNING: do not use this function on arbitrary user-supplied titles!
	 * On heavily-used templates it will max out the memory.
	 *
	 * @param $options Array: may be FOR UPDATE
	 * @return Array of Title the Title objects linking here
	 */
	public function getTemplateLinksTo( $options = array() ) {
		return $this->getLinksTo( $options, 'templatelinks', 'tl' );
	}

	/**
	 * Get an array of Title objects referring to non-existent articles linked from this page
	 *
	 * @todo check if needed (used only in SpecialBrokenRedirects.php, and should use redirect table in this case)
	 * @return Array of Title the Title objects
	 */
	public function getBrokenLinksFrom() {
		if ( $this->getArticleId() == 0 ) {
			# All links from article ID 0 are false positives
			return array();
		}

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			array( 'page', 'pagelinks' ),
			array( 'pl_namespace', 'pl_title' ),
			array(
				'pl_from' => $this->getArticleId(),
				'page_namespace IS NULL'
			),
			__METHOD__, array(),
			array(
				'page' => array(
					'LEFT JOIN',
					array( 'pl_namespace=page_namespace', 'pl_title=page_title' )
				)
			)
		);

		$retVal = array();
		foreach ( $res as $row ) {
			$retVal[] = Title::makeTitle( $row->pl_namespace, $row->pl_title );
		}
		return $retVal;
	}


	/**
	 * Get a list of URLs to purge from the Squid cache when this
	 * page changes
	 *
	 * @return Array of String the URLs
	 */
	public function getSquidURLs() {
		global $wgContLang;

		$urls = array(
			$this->getInternalURL(),
			$this->getInternalURL( 'action=history' )
		);

		// purge variant urls as well
		if ( $wgContLang->hasVariants() ) {
			$variants = $wgContLang->getVariants();
			foreach ( $variants as $vCode ) {
				$urls[] = $this->getInternalURL( '', $vCode );
			}
		}

		return $urls;
	}

	/**
	 * Purge all applicable Squid URLs
	 */
	public function purgeSquid() {
		global $wgUseSquid;
		if ( $wgUseSquid ) {
			$urls = $this->getSquidURLs();
			$u = new SquidUpdate( $urls );
			$u->doUpdate();
		}
	}

	/**
	 * Move this page without authentication
	 *
	 * @param $nt Title the new page Title
	 * @return Mixed true on success, getUserPermissionsErrors()-like array on failure
	 */
	public function moveNoAuth( &$nt ) {
		return $this->moveTo( $nt, false );
	}

	/**
	 * Check whether a given move operation would be valid.
	 * Returns true if ok, or a getUserPermissionsErrors()-like array otherwise
	 *
	 * @param $nt Title the new title
	 * @param $auth Bool indicates whether $wgUser's permissions
	 *  should be checked
	 * @param $reason String is the log summary of the move, used for spam checking
	 * @return Mixed True on success, getUserPermissionsErrors()-like array on failure
	 */
	public function isValidMoveOperation( &$nt, $auth = true, $reason = '' ) {
		global $wgUser;

		$errors = array();
		if ( !$nt ) {
			// Normally we'd add this to $errors, but we'll get
			// lots of syntax errors if $nt is not an object
			return array( array( 'badtitletext' ) );
		}
		if ( $this->equals( $nt ) ) {
			$errors[] = array( 'selfmove' );
		}
		if ( !$this->isMovable() ) {
			$errors[] = array( 'immobile-source-namespace', $this->getNsText() );
		}
		if ( $nt->getInterwiki() != '' ) {
			$errors[] = array( 'immobile-target-namespace-iw' );
		}
		if ( !$nt->isMovable() ) {
			$errors[] = array( 'immobile-target-namespace', $nt->getNsText() );
		}

		$oldid = $this->getArticleID();
		$newid = $nt->getArticleID();

		if ( strlen( $nt->getDBkey() ) < 1 ) {
			$errors[] = array( 'articleexists' );
		}
		if ( ( $this->getDBkey() == '' ) ||
			 ( !$oldid ) ||
			 ( $nt->getDBkey() == '' ) ) {
			$errors[] = array( 'badarticleerror' );
		}

		// Image-specific checks
		if ( $this->getNamespace() == NS_FILE ) {
			$errors = array_merge( $errors, $this->validateFileMoveOperation( $nt ) );
		}

		if ( $nt->getNamespace() == NS_FILE && $this->getNamespace() != NS_FILE ) {
			$errors[] = array( 'nonfile-cannot-move-to-file' );
		}

		if ( $auth ) {
			$errors = wfMergeErrorArrays( $errors,
				$this->getUserPermissionsErrors( 'move', $wgUser ),
				$this->getUserPermissionsErrors( 'edit', $wgUser ),
				$nt->getUserPermissionsErrors( 'move-target', $wgUser ),
				$nt->getUserPermissionsErrors( 'edit', $wgUser ) );
		}

		$match = EditPage::matchSummarySpamRegex( $reason );
		if ( $match !== false ) {
			// This is kind of lame, won't display nice
			$errors[] = array( 'spamprotectiontext' );
		}

		$err = null;
		if ( !wfRunHooks( 'AbortMove', array( $this, $nt, $wgUser, &$err, $reason ) ) ) {
			$errors[] = array( 'hookaborted', $err );
		}

		# The move is allowed only if (1) the target doesn't exist, or
		# (2) the target is a redirect to the source, and has no history
		# (so we can undo bad moves right after they're done).

		if ( 0 != $newid ) { # Target exists; check for validity
			if ( !$this->isValidMoveTarget( $nt ) ) {
				$errors[] = array( 'articleexists' );
			}
		} else {
			$tp = $nt->getTitleProtection();
			$right = ( $tp['pt_create_perm'] == 'sysop' ) ? 'protect' : $tp['pt_create_perm'];
			if ( $tp and !$wgUser->isAllowed( $right ) ) {
				$errors[] = array( 'cantmove-titleprotected' );
			}
		}
		if ( empty( $errors ) ) {
			return true;
		}
		return $errors;
	}

	/**
	 * Check if the requested move target is a valid file move target
	 * @param Title $nt Target title
	 * @return array List of errors
	 */
	protected function validateFileMoveOperation( $nt ) {
		global $wgUser;

		$errors = array();

		if ( $nt->getNamespace() != NS_FILE ) {
			$errors[] = array( 'imagenocrossnamespace' );
		}

		$file = wfLocalFile( $this );
		if ( $file->exists() ) {
			if ( $nt->getText() != wfStripIllegalFilenameChars( $nt->getText() ) ) {
				$errors[] = array( 'imageinvalidfilename' );
			}
			if ( !File::checkExtensionCompatibility( $file, $nt->getDBkey() ) ) {
				$errors[] = array( 'imagetypemismatch' );
			}
		}

		$destFile = wfLocalFile( $nt );
		if ( !$wgUser->isAllowed( 'reupload-shared' ) && !$destFile->exists() && wfFindFile( $nt ) ) {
			$errors[] = array( 'file-exists-sharedrepo' );
		}

		return $errors;
	}

	/**
	 * Move a title to a new location
	 *
	 * @param $nt Title the new title
	 * @param $auth Bool indicates whether $wgUser's permissions
	 *  should be checked
	 * @param $reason String the reason for the move
	 * @param $createRedirect Bool Whether to create a redirect from the old title to the new title.
	 *  Ignored if the user doesn't have the suppressredirect right.
	 * @return Mixed true on success, getUserPermissionsErrors()-like array on failure
	 */
	public function moveTo( &$nt, $auth = true, $reason = '', $createRedirect = true ) {
		global $wgEnableInterwikiTemplatesTracking, $wgGlobalDatabase;

		$err = $this->isValidMoveOperation( $nt, $auth, $reason );
		if ( is_array( $err ) ) {
			return $err;
		}

		// If it is a file, move it first. It is done before all other moving stuff is
		// done because it's hard to revert
		$dbw = wfGetDB( DB_MASTER );
		if ( $this->getNamespace() == NS_FILE ) {
			$file = wfLocalFile( $this );
			if ( $file->exists() ) {
				$status = $file->move( $nt );
				if ( !$status->isOk() ) {
					return $status->getErrorsArray();
				}
			}
		}

		$dbw->begin(); # If $file was a LocalFile, its transaction would have closed our own.
		$pageid = $this->getArticleID( self::GAID_FOR_UPDATE );
		$protected = $this->isProtected();
		$pageCountChange = ( $createRedirect ? 1 : 0 ) - ( $nt->exists() ? 1 : 0 );

		// Do the actual move
		$err = $this->moveOverExistingRedirect( $nt, $reason, $createRedirect );
		if ( is_array( $err ) ) {
			# @todo FIXME: What about the File we have already moved?
			$dbw->rollback();
			return $err;
		}

		$redirid = $this->getArticleID();

		// Refresh the sortkey for this row.  Be careful to avoid resetting
		// cl_timestamp, which may disturb time-based lists on some sites.
		$prefixes = $dbw->select(
			'categorylinks',
			array( 'cl_sortkey_prefix', 'cl_to' ),
			array( 'cl_from' => $pageid ),
			__METHOD__
		);
		foreach ( $prefixes as $prefixRow ) {
			$prefix = $prefixRow->cl_sortkey_prefix;
			$catTo = $prefixRow->cl_to;
			$dbw->update( 'categorylinks',
				array(
					'cl_sortkey' => Collation::singleton()->getSortKey(
						$nt->getCategorySortkey( $prefix ) ),
					'cl_timestamp=cl_timestamp' ),
				array(
					'cl_from' => $pageid,
					'cl_to' => $catTo ),
				__METHOD__
			);
		}

		if ( $wgEnableInterwikiTemplatesTracking && $wgGlobalDatabase ) {
			$dbw2 = wfGetDB( DB_MASTER, array(), $wgGlobalDatabase );
			$dbw2->update( 'globaltemplatelinks',
						array(  'gtl_from_namespace' => $nt->getNamespace(),
								'gtl_from_title' => $nt->getText() ),
						array ( 'gtl_from_page' => $pageid ),
						__METHOD__ );
		}

		if ( $protected ) {
			# Protect the redirect title as the title used to be...
			$dbw->insertSelect( 'page_restrictions', 'page_restrictions',
				array(
					'pr_page'    => $redirid,
					'pr_type'    => 'pr_type',
					'pr_level'   => 'pr_level',
					'pr_cascade' => 'pr_cascade',
					'pr_user'    => 'pr_user',
					'pr_expiry'  => 'pr_expiry'
				),
				array( 'pr_page' => $pageid ),
				__METHOD__,
				array( 'IGNORE' )
			);
			# Update the protection log
			$log = new LogPage( 'protect' );
			$comment = wfMsgForContent( 'prot_1movedto2', $this->getPrefixedText(), $nt->getPrefixedText() );
			if ( $reason ) {
				$comment .= wfMsgForContent( 'colon-separator' ) . $reason;
			}
			// @todo FIXME: $params?
			$log->addEntry( 'move_prot', $nt, $comment, array( $this->getPrefixedText() ) );
		}

		# Update watchlists
		$oldnamespace = $this->getNamespace() & ~1;
		$newnamespace = $nt->getNamespace() & ~1;
		$oldtitle = $this->getDBkey();
		$newtitle = $nt->getDBkey();

		if ( $oldnamespace != $newnamespace || $oldtitle != $newtitle ) {
			WatchedItem::duplicateEntries( $this, $nt );
		}

		# Update search engine
		$u = new SearchUpdate( $pageid, $nt->getPrefixedDBkey() );
		$u->doUpdate();
		$u = new SearchUpdate( $redirid, $this->getPrefixedDBkey(), '' );
		$u->doUpdate();

		$dbw->commit();

		# Update site_stats
		if ( $this->isContentPage() && !$nt->isContentPage() ) {
			# No longer a content page
			# Not viewed, edited, removing
			$u = new SiteStatsUpdate( 0, 1, -1, $pageCountChange );
		} elseif ( !$this->isContentPage() && $nt->isContentPage() ) {
			# Now a content page
			# Not viewed, edited, adding
			$u = new SiteStatsUpdate( 0, 1, + 1, $pageCountChange );
		} elseif ( $pageCountChange ) {
			# Redirect added
			$u = new SiteStatsUpdate( 0, 0, 0, 1 );
		} else {
			# Nothing special
			$u = false;
		}
		if ( $u ) {
			$u->doUpdate();
		}

		# Update message cache for interface messages
		if ( $this->getNamespace() == NS_MEDIAWIKI ) {
			# @bug 17860: old article can be deleted, if this the case,
			# delete it from message cache
			if ( $this->getArticleID() === 0 ) {
				MessageCache::singleton()->replace( $this->getDBkey(), false );
			} else {
				$oldarticle = new Article( $this );
				MessageCache::singleton()->replace( $this->getDBkey(), $oldarticle->getContent() );
			}
		}
		if ( $nt->getNamespace() == NS_MEDIAWIKI ) {
			$newarticle = new Article( $nt );
			MessageCache::singleton()->replace( $nt->getDBkey(), $newarticle->getContent() );
		}

		global $wgUser;
		wfRunHooks( 'TitleMoveComplete', array( &$this, &$nt, &$wgUser, $pageid, $redirid ) );
		return true;
	}

	/**
	 * Move page to a title which is either a redirect to the
	 * source page or nonexistent
	 *
	 * @param $nt Title the page to move to, which should be a redirect or nonexistent
	 * @param $reason String The reason for the move
	 * @param $createRedirect Bool Whether to leave a redirect at the old title.  Ignored
	 *   if the user doesn't have the suppressredirect right
	 */
	private function moveOverExistingRedirect( &$nt, $reason = '', $createRedirect = true ) {
		global $wgUser, $wgContLang, $wgEnableInterwikiTemplatesTracking, $wgGlobalDatabase;

		$moveOverRedirect = $nt->exists();

		$commentMsg = ( $moveOverRedirect ? '1movedto2_redir' : '1movedto2' );
		$comment = wfMsgForContent( $commentMsg, $this->getPrefixedText(), $nt->getPrefixedText() );

		if ( $reason ) {
			$comment .= wfMsgForContent( 'colon-separator' ) . $reason;
		}
		# Truncate for whole multibyte characters.
		$comment = $wgContLang->truncate( $comment, 255 );

		$oldid = $this->getArticleID();
		$latest = $this->getLatestRevID();

		$dbw = wfGetDB( DB_MASTER );

		if ( $moveOverRedirect ) {
			$rcts = $dbw->timestamp( $nt->getEarliestRevTime() );

			$newid = $nt->getArticleID();
			$newns = $nt->getNamespace();
			$newdbk = $nt->getDBkey();

			# Delete the old redirect. We don't save it to history since
			# by definition if we've got here it's rather uninteresting.
			# We have to remove it so that the next step doesn't trigger
			# a conflict on the unique namespace+title index...
			$dbw->delete( 'page', array( 'page_id' => $newid ), __METHOD__ );
			if ( !$dbw->cascadingDeletes() ) {
				$dbw->delete( 'revision', array( 'rev_page' => $newid ), __METHOD__ );
				global $wgUseTrackbacks;
				if ( $wgUseTrackbacks ) {
					$dbw->delete( 'trackbacks', array( 'tb_page' => $newid ), __METHOD__ );
				}
				$dbw->delete( 'pagelinks', array( 'pl_from' => $newid ), __METHOD__ );
				$dbw->delete( 'imagelinks', array( 'il_from' => $newid ), __METHOD__ );
				$dbw->delete( 'categorylinks', array( 'cl_from' => $newid ), __METHOD__ );
				$dbw->delete( 'templatelinks', array( 'tl_from' => $newid ), __METHOD__ );
				$dbw->delete( 'externallinks', array( 'el_from' => $newid ), __METHOD__ );
				$dbw->delete( 'langlinks', array( 'll_from' => $newid ), __METHOD__ );
				$dbw->delete( 'redirect', array( 'rd_from' => $newid ), __METHOD__ );
				$dbw->delete( 'page_props', array( 'pp_page' => $newid ), __METHOD__ );
			}
			// If the target page was recently created, it may have an entry in recentchanges still
			$dbw->delete( 'recentchanges',
				array( 'rc_timestamp' => $rcts, 'rc_namespace' => $newns, 'rc_title' => $newdbk, 'rc_new' => 1 ),
				__METHOD__
			);
			
			 if ( $wgEnableInterwikiTemplatesTracking && $wgGlobalDatabase ) {
				$dbw2 = wfGetDB( DB_MASTER, array(), $wgGlobalDatabase );
				$dbw2->delete( 'globaltemplatelinks',
							array(  'gtl_from_wiki' => wfGetID(),
									'gtl_from_page' => $newid ),
							__METHOD__ );
			}
		}

		# Save a null revision in the page's history notifying of the move
		$nullRevision = Revision::newNullRevision( $dbw, $oldid, $comment, true );
		if ( !is_object( $nullRevision ) ) {
			throw new MWException( 'No valid null revision produced in ' . __METHOD__ );
		}
		$nullRevId = $nullRevision->insertOn( $dbw );

		$now = wfTimestampNow();
		# Change the name of the target page:
		$dbw->update( 'page',
			/* SET */ array(
				'page_touched'   => $dbw->timestamp( $now ),
				'page_namespace' => $nt->getNamespace(),
				'page_title'     => $nt->getDBkey(),
				'page_latest'    => $nullRevId,
			),
			/* WHERE */ array( 'page_id' => $oldid ),
			__METHOD__
		);
		$nt->resetArticleID( $oldid );

		$article = new Article( $nt );
		wfRunHooks( 'NewRevisionFromEditComplete',
			array( $article, $nullRevision, $latest, $wgUser ) );
		$article->setCachedLastEditTime( $now );

		# Recreate the redirect, this time in the other direction.
		if ( $createRedirect || !$wgUser->isAllowed( 'suppressredirect' ) ) {
			$mwRedir = MagicWord::get( 'redirect' );
			$redirectText = $mwRedir->getSynonym( 0 ) . ' [[' . $nt->getPrefixedText() . "]]\n";
			$redirectArticle = new Article( $this );
			$newid = $redirectArticle->insertOn( $dbw );
			if ( $newid ) { // sanity
				$redirectRevision = new Revision( array(
					'page'    => $newid,
					'comment' => $comment,
					'text'    => $redirectText ) );
				$redirectRevision->insertOn( $dbw );
				$redirectArticle->updateRevisionOn( $dbw, $redirectRevision, 0 );

				wfRunHooks( 'NewRevisionFromEditComplete',
					array( $redirectArticle, $redirectRevision, false, $wgUser ) );

				# Now, we record the link from the redirect to the new title.
				# It should have no other outgoing links...
				$dbw->delete( 'pagelinks', array( 'pl_from' => $newid ), __METHOD__ );
				$dbw->insert( 'pagelinks',
					array(
						'pl_from'      => $newid,
						'pl_namespace' => $nt->getNamespace(),
						'pl_title'     => $nt->getDBkey() ),
					__METHOD__ );
			}
			$redirectSuppressed = false;
		} else {
			$this->resetArticleID( 0 );
			$redirectSuppressed = true;
		}

		# Log the move
		$log = new LogPage( 'move' );
		$logType = ( $moveOverRedirect ? 'move_redir' : 'move' );
		$log->addEntry( $logType, $this, $reason, array( 1 => $nt->getPrefixedText(), 2 => $redirectSuppressed ) );

		# Purge caches for old and new titles
		if ( $moveOverRedirect ) {
			# A simple purge is enough when moving over a redirect
			$nt->purgeSquid();
		} else {
			# Purge caches as per article creation, including any pages that link to this title
			Article::onArticleCreate( $nt );
		}
		$this->purgeSquid();
	}

	/**
	 * Move this page's subpages to be subpages of $nt
	 *
	 * @param $nt Title Move target
	 * @param $auth bool Whether $wgUser's permissions should be checked
	 * @param $reason string The reason for the move
	 * @param $createRedirect bool Whether to create redirects from the old subpages to
	 *     the new ones Ignored if the user doesn't have the 'suppressredirect' right
	 * @return mixed array with old page titles as keys, and strings (new page titles) or
	 *     arrays (errors) as values, or an error array with numeric indices if no pages
	 *     were moved
	 */
	public function moveSubpages( $nt, $auth = true, $reason = '', $createRedirect = true ) {
		global $wgMaximumMovedPages;
		// Check permissions
		if ( !$this->userCan( 'move-subpages' ) ) {
			return array( 'cant-move-subpages' );
		}
		// Do the source and target namespaces support subpages?
		if ( !MWNamespace::hasSubpages( $this->getNamespace() ) ) {
			return array( 'namespace-nosubpages',
				MWNamespace::getCanonicalName( $this->getNamespace() ) );
		}
		if ( !MWNamespace::hasSubpages( $nt->getNamespace() ) ) {
			return array( 'namespace-nosubpages',
				MWNamespace::getCanonicalName( $nt->getNamespace() ) );
		}

		$subpages = $this->getSubpages( $wgMaximumMovedPages + 1 );
		$retval = array();
		$count = 0;
		foreach ( $subpages as $oldSubpage ) {
			$count++;
			if ( $count > $wgMaximumMovedPages ) {
				$retval[$oldSubpage->getPrefixedTitle()] =
						array( 'movepage-max-pages',
							$wgMaximumMovedPages );
				break;
			}

			// We don't know whether this function was called before
			// or after moving the root page, so check both
			// $this and $nt
			if ( $oldSubpage->getArticleId() == $this->getArticleId() ||
					$oldSubpage->getArticleID() == $nt->getArticleId() )
			{
				// When moving a page to a subpage of itself,
				// don't move it twice
				continue;
			}
			$newPageName = preg_replace(
					'#^' . preg_quote( $this->getDBkey(), '#' ) . '#',
					StringUtils::escapeRegexReplacement( $nt->getDBkey() ), # bug 21234
					$oldSubpage->getDBkey() );
			if ( $oldSubpage->isTalkPage() ) {
				$newNs = $nt->getTalkPage()->getNamespace();
			} else {
				$newNs = $nt->getSubjectPage()->getNamespace();
			}
			# Bug 14385: we need makeTitleSafe because the new page names may
			# be longer than 255 characters.
			$newSubpage = Title::makeTitleSafe( $newNs, $newPageName );

			$success = $oldSubpage->moveTo( $newSubpage, $auth, $reason, $createRedirect );
			if ( $success === true ) {
				$retval[$oldSubpage->getPrefixedText()] = $newSubpage->getPrefixedText();
			} else {
				$retval[$oldSubpage->getPrefixedText()] = $success;
			}
		}
		return $retval;
	}

	/**
	 * Checks if this page is just a one-rev redirect.
	 * Adds lock, so don't use just for light purposes.
	 *
	 * @return Bool
	 */
	public function isSingleRevRedirect() {
		$dbw = wfGetDB( DB_MASTER );
		# Is it a redirect?
		$row = $dbw->selectRow( 'page',
			array( 'page_is_redirect', 'page_latest', 'page_id' ),
			$this->pageCond(),
			__METHOD__,
			array( 'FOR UPDATE' )
		);
		# Cache some fields we may want
		$this->mArticleID = $row ? intval( $row->page_id ) : 0;
		$this->mRedirect = $row ? (bool)$row->page_is_redirect : false;
		$this->mLatestID = $row ? intval( $row->page_latest ) : false;
		if ( !$this->mRedirect ) {
			return false;
		}
		# Does the article have a history?
		$row = $dbw->selectField( array( 'page', 'revision' ),
			'rev_id',
			array( 'page_namespace' => $this->getNamespace(),
				'page_title' => $this->getDBkey(),
				'page_id=rev_page',
				'page_latest != rev_id'
			),
			__METHOD__,
			array( 'FOR UPDATE' )
		);
		# Return true if there was no history
		return ( $row === false );
	}

	/**
	 * Checks if $this can be moved to a given Title
	 * - Selects for update, so don't call it unless you mean business
	 *
	 * @param $nt Title the new title to check
	 * @return Bool
	 */
	public function isValidMoveTarget( $nt ) {
		# Is it an existing file?
		if ( $nt->getNamespace() == NS_FILE ) {
			$file = wfLocalFile( $nt );
			if ( $file->exists() ) {
				wfDebug( __METHOD__ . ": file exists\n" );
				return false;
			}
		}
		# Is it a redirect with no history?
		if ( !$nt->isSingleRevRedirect() ) {
			wfDebug( __METHOD__ . ": not a one-rev redirect\n" );
			return false;
		}
		# Get the article text
		$rev = Revision::newFromTitle( $nt );
		$text = $rev->getText();
		# Does the redirect point to the source?
		# Or is it a broken self-redirect, usually caused by namespace collisions?
		$m = array();
		if ( preg_match( "/\\[\\[\\s*([^\\]\\|]*)]]/", $text, $m ) ) {
			$redirTitle = Title::newFromText( $m[1] );
			if ( !is_object( $redirTitle ) ||
				( $redirTitle->getPrefixedDBkey() != $this->getPrefixedDBkey() &&
				$redirTitle->getPrefixedDBkey() != $nt->getPrefixedDBkey() ) ) {
				wfDebug( __METHOD__ . ": redirect points to other page\n" );
				return false;
			}
		} else {
			# Fail safe
			wfDebug( __METHOD__ . ": failsafe\n" );
			return false;
		}
		return true;
	}

	/**
	 * Can this title be added to a user's watchlist?
	 *
	 * @return Bool TRUE or FALSE
	 */
	public function isWatchable() {
		return !$this->isExternal() && MWNamespace::isWatchable( $this->getNamespace() );
	}

	/**
	 * Get categories to which this Title belongs and return an array of
	 * categories' names.
	 *
	 * @return Array of parents in the form:
	 *	  $parent => $currentarticle
	 */
	public function getParentCategories() {
		global $wgContLang;

		$data = array();

		$titleKey = $this->getArticleId();

		if ( $titleKey === 0 ) {
			return $data;
		}

		$dbr = wfGetDB( DB_SLAVE );

		$res = $dbr->select( 'categorylinks', '*',
			array(
				'cl_from' => $titleKey,
			),
			__METHOD__,
			array()
		);

		if ( $dbr->numRows( $res ) > 0 ) {
			foreach ( $res as $row ) {
				// $data[] = Title::newFromText($wgContLang->getNSText ( NS_CATEGORY ).':'.$row->cl_to);
				$data[$wgContLang->getNSText( NS_CATEGORY ) . ':' . $row->cl_to] = $this->getFullText();
			}
		}
		return $data;
	}

	/**
	 * Get a tree of parent categories
	 *
	 * @param $children Array with the children in the keys, to check for circular refs
	 * @return Array Tree of parent categories
	 */
	public function getParentCategoryTree( $children = array() ) {
		$stack = array();
		$parents = $this->getParentCategories();

		if ( $parents ) {
			foreach ( $parents as $parent => $current ) {
				if ( array_key_exists( $parent, $children ) ) {
					# Circular reference
					$stack[$parent] = array();
				} else {
					$nt = Title::newFromText( $parent );
					if ( $nt ) {
						$stack[$parent] = $nt->getParentCategoryTree( $children + array( $parent => 1 ) );
					}
				}
			}
		}

		return $stack;
	}

	/**
	 * Get an associative array for selecting this title from
	 * the "page" table
	 *
	 * @return Array suitable for the $where parameter of DB::select()
	 */
	public function pageCond() {
		if ( $this->mArticleID > 0 ) {
			// PK avoids secondary lookups in InnoDB, shouldn't hurt other DBs
			return array( 'page_id' => $this->mArticleID );
		} else {
			return array( 'page_namespace' => $this->mNamespace, 'page_title' => $this->mDbkeyform );
		}
	}

	/**
	 * Get the revision ID of the previous revision
	 *
	 * @param $revId Int Revision ID. Get the revision that was before this one.
	 * @param $flags Int Title::GAID_FOR_UPDATE
	 * @return Int|Bool Old revision ID, or FALSE if none exists
	 */
	public function getPreviousRevisionID( $revId, $flags = 0 ) {
		$db = ( $flags & self::GAID_FOR_UPDATE ) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_SLAVE );
		return $db->selectField( 'revision', 'rev_id',
			array(
				'rev_page' => $this->getArticleId( $flags ),
				'rev_id < ' . intval( $revId )
			),
			__METHOD__,
			array( 'ORDER BY' => 'rev_id DESC' )
		);
	}

	/**
	 * Get the revision ID of the next revision
	 *
	 * @param $revId Int Revision ID. Get the revision that was after this one.
	 * @param $flags Int Title::GAID_FOR_UPDATE
	 * @return Int|Bool Next revision ID, or FALSE if none exists
	 */
	public function getNextRevisionID( $revId, $flags = 0 ) {
		$db = ( $flags & self::GAID_FOR_UPDATE ) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_SLAVE );
		return $db->selectField( 'revision', 'rev_id',
			array(
				'rev_page' => $this->getArticleId( $flags ),
				'rev_id > ' . intval( $revId )
			),
			__METHOD__,
			array( 'ORDER BY' => 'rev_id' )
		);
	}

	/**
	 * Get the first revision of the page
	 *
	 * @param $flags Int Title::GAID_FOR_UPDATE
	 * @return Revision|Null if page doesn't exist
	 */
	public function getFirstRevision( $flags = 0 ) {
		$pageId = $this->getArticleId( $flags );
		if ( $pageId ) {
			$db = ( $flags & self::GAID_FOR_UPDATE ) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_SLAVE );
			$row = $db->selectRow( 'revision', '*',
				array( 'rev_page' => $pageId ),
				__METHOD__,
				array( 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => 1 )
			);
			if ( $row ) {
				return new Revision( $row );
			}
		}
		return null;
	}

	/**
	 * Get the oldest revision timestamp of this page
	 *
	 * @param $flags Int Title::GAID_FOR_UPDATE
	 * @return String: MW timestamp
	 */
	public function getEarliestRevTime( $flags = 0 ) {
		$rev = $this->getFirstRevision( $flags );
		return $rev ? $rev->getTimestamp() : null;
	}

	/**
	 * Check if this is a new page
	 *
	 * @return bool
	 */
	public function isNewPage() {
		$dbr = wfGetDB( DB_SLAVE );
		return (bool)$dbr->selectField( 'page', 'page_is_new', $this->pageCond(), __METHOD__ );
	}

	/**
	 * Get the number of revisions between the given revision.
	 * Used for diffs and other things that really need it.
	 *
	 * @param $old int|Revision Old revision or rev ID (first before range)
	 * @param $new int|Revision New revision or rev ID (first after range)
	 * @return Int Number of revisions between these revisions.
	 */
	public function countRevisionsBetween( $old, $new ) {
		if ( !( $old instanceof Revision ) ) {
			$old = Revision::newFromTitle( $this, (int)$old );
		}
		if ( !( $new instanceof Revision ) ) {
			$new = Revision::newFromTitle( $this, (int)$new );
		}
		if ( !$old || !$new ) {
			return 0; // nothing to compare
		}
		$dbr = wfGetDB( DB_SLAVE );
		return (int)$dbr->selectField( 'revision', 'count(*)',
			array(
				'rev_page' => $this->getArticleId(),
				'rev_timestamp > ' . $dbr->addQuotes( $dbr->timestamp( $old->getTimestamp() ) ),
				'rev_timestamp < ' . $dbr->addQuotes( $dbr->timestamp( $new->getTimestamp() ) )
			),
			__METHOD__
		);
	}

	/**
	 * Get the number of authors between the given revision IDs.
	 * Used for diffs and other things that really need it.
	 *
	 * @param $old int|Revision Old revision or rev ID (first before range)
	 * @param $new int|Revision New revision or rev ID (first after range)
	 * @param $limit Int Maximum number of authors
	 * @return Int Number of revision authors between these revisions.
	 */
	public function countAuthorsBetween( $old, $new, $limit ) {
		if ( !( $old instanceof Revision ) ) {
			$old = Revision::newFromTitle( $this, (int)$old );
		}
		if ( !( $new instanceof Revision ) ) {
			$new = Revision::newFromTitle( $this, (int)$new );
		}
		if ( !$old || !$new ) {
			return 0; // nothing to compare
		}
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'revision', 'DISTINCT rev_user_text',
			array(
				'rev_page' => $this->getArticleID(),
				'rev_timestamp > ' . $dbr->addQuotes( $dbr->timestamp( $old->getTimestamp() ) ),
				'rev_timestamp < ' . $dbr->addQuotes( $dbr->timestamp( $new->getTimestamp() ) )
			), __METHOD__,
			array( 'LIMIT' => $limit + 1 ) // add one so caller knows it was truncated
		);
		return (int)$dbr->numRows( $res );
	}

	/**
	 * Compare with another title.
	 *
	 * @param $title Title
	 * @return Bool
	 */
	public function equals( Title $title ) {
		// Note: === is necessary for proper matching of number-like titles.
		return $this->getInterwiki() === $title->getInterwiki()
			&& $this->getNamespace() == $title->getNamespace()
			&& $this->getDBkey() === $title->getDBkey();
	}

	/**
	 * Check if this title is a subpage of another title
	 *
	 * @param $title Title
	 * @return Bool
	 */
	public function isSubpageOf( Title $title ) {
		return $this->getInterwiki() === $title->getInterwiki()
			&& $this->getNamespace() == $title->getNamespace()
			&& strpos( $this->getDBkey(), $title->getDBkey() . '/' ) === 0;
	}

	/**
	 * Callback for usort() to do title sorts by (namespace, title)
	 *
	 * @param $a Title
	 * @param $b Title
	 *
	 * @return Integer: result of string comparison, or namespace comparison
	 */
	public static function compare( $a, $b ) {
		if ( $a->getNamespace() == $b->getNamespace() ) {
			return strcmp( $a->getText(), $b->getText() );
		} else {
			return $a->getNamespace() - $b->getNamespace();
		}
	}

	/**
	 * Return a string representation of this title
	 *
	 * @return String representation of this title
	 */
	public function __toString() {
		return $this->getPrefixedText();
	}

	/**
	 * Check if page exists.  For historical reasons, this function simply
	 * checks for the existence of the title in the page table, and will
	 * thus return false for interwiki links, special pages and the like.
	 * If you want to know if a title can be meaningfully viewed, you should
	 * probably call the isKnown() method instead.
	 *
	 * @return Bool
	 */
	public function exists() {
		return $this->getArticleId() != 0;
	}

	/**
	 * Should links to this title be shown as potentially viewable (i.e. as
	 * "bluelinks"), even if there's no record by this title in the page
	 * table?
	 *
	 * This function is semi-deprecated for public use, as well as somewhat
	 * misleadingly named.  You probably just want to call isKnown(), which
	 * calls this function internally.
	 *
	 * (ISSUE: Most of these checks are cheap, but the file existence check
	 * can potentially be quite expensive.  Including it here fixes a lot of
	 * existing code, but we might want to add an optional parameter to skip
	 * it and any other expensive checks.)
	 *
	 * @return Bool
	 */
	public function isAlwaysKnown() {
		if ( $this->mInterwiki != '' ) {
			return true;  // any interwiki link might be viewable, for all we know
		}
		switch( $this->mNamespace ) {
			case NS_MEDIA:
			case NS_FILE:
				// file exists, possibly in a foreign repo
				return (bool)wfFindFile( $this );
			case NS_SPECIAL:
				// valid special page
				return SpecialPageFactory::exists( $this->getDBkey() );
			case NS_MAIN:
				// selflink, possibly with fragment
				return $this->mDbkeyform == '';
			case NS_MEDIAWIKI:
				// known system message
				return $this->getDefaultMessageText() !== false;
			default:
				return false;
		}
	}

	/**
	 * Does this title refer to a page that can (or might) be meaningfully
	 * viewed?  In particular, this function may be used to determine if
	 * links to the title should be rendered as "bluelinks" (as opposed to
	 * "redlinks" to non-existent pages).
	 *
	 * @return Bool
	 */
	public function isKnown() {
		return $this->isAlwaysKnown() || $this->exists();
	}

	/**
	 * Does this page have source text?
	 *
	 * @return Boolean
	 */
	public function hasSourceText() {
		if ( $this->exists() ) {
			return true;
		}

		if ( $this->mNamespace == NS_MEDIAWIKI ) {
			// If the page doesn't exist but is a known system message, default
			// message content will be displayed, same for language subpages
			return $this->getDefaultMessageText() !== false;
		}

		return false;
	}

	/**
	 * Get the default message text or false if the message doesn't exist
	 *
	 * @return String or false
	 */
	public function getDefaultMessageText() {
		global $wgContLang;

		if ( $this->getNamespace() != NS_MEDIAWIKI ) { // Just in case
			return false;
		}

		list( $name, $lang ) = MessageCache::singleton()->figureMessage( $wgContLang->lcfirst( $this->getText() ) );
		$message = wfMessage( $name )->inLanguage( $lang )->useDatabase( false );

		if ( $message->exists() ) {
			return $message->plain();
		} else {
			return false;
		}
	}

	/**
	 * Is this in a namespace that allows actual pages?
	 *
	 * @return Bool
	 * @internal note -- uses hardcoded namespace index instead of constants
	 */
	public function canExist() {
		return $this->mNamespace >= 0 && $this->mNamespace != NS_MEDIA;
	}

	/**
	 * Update page_touched timestamps and send squid purge messages for
	 * pages linking to this title.	May be sent to the job queue depending
	 * on the number of links. Typically called on create and delete.
	 */
	public function touchLinks() {
		$u = new HTMLCacheUpdate( $this, 'pagelinks' );
		$u->doUpdate();

		if ( $this->getNamespace() == NS_CATEGORY ) {
			$u = new HTMLCacheUpdate( $this, 'categorylinks' );
			$u->doUpdate();
		}
	}

	/**
	 * Get the last touched timestamp
	 *
	 * @param $db DatabaseBase: optional db
	 * @return String last-touched timestamp
	 */
	public function getTouched( $db = null ) {
		$db = isset( $db ) ? $db : wfGetDB( DB_SLAVE );
		$touched = $db->selectField( 'page', 'page_touched', $this->pageCond(), __METHOD__ );
		return $touched;
	}

	/**
	 * Get the timestamp when this page was updated since the user last saw it.
	 *
	 * @param $user User
	 * @return String|Null
	 */
	public function getNotificationTimestamp( $user = null ) {
		global $wgUser, $wgShowUpdatedMarker;
		// Assume current user if none given
		if ( !$user ) {
			$user = $wgUser;
		}
		// Check cache first
		$uid = $user->getId();
		// avoid isset here, as it'll return false for null entries
		if ( array_key_exists( $uid, $this->mNotificationTimestamp ) ) {
			return $this->mNotificationTimestamp[$uid];
		}
		if ( !$uid || !$wgShowUpdatedMarker ) {
			return $this->mNotificationTimestamp[$uid] = false;
		}
		// Don't cache too much!
		if ( count( $this->mNotificationTimestamp ) >= self::CACHE_MAX ) {
			$this->mNotificationTimestamp = array();
		}
		$dbr = wfGetDB( DB_SLAVE );
		$this->mNotificationTimestamp[$uid] = $dbr->selectField( 'watchlist',
			'wl_notificationtimestamp',
			array( 'wl_namespace' => $this->getNamespace(),
				'wl_title' => $this->getDBkey(),
				'wl_user' => $user->getId()
			),
			__METHOD__
		);
		return $this->mNotificationTimestamp[$uid];
	}

	/**
	 * Get the trackback URL for this page
	 *
	 * @return String Trackback URL
	 */
	public function trackbackURL() {
		global $wgScriptPath, $wgServer, $wgScriptExtension;

		return "$wgServer$wgScriptPath/trackback$wgScriptExtension?article="
			. htmlspecialchars( urlencode( $this->getPrefixedDBkey() ) );
	}

	/**
	 * Get the trackback RDF for this page
	 *
	 * @return String Trackback RDF
	 */
	public function trackbackRDF() {
		$url = htmlspecialchars( $this->getFullURL() );
		$title = htmlspecialchars( $this->getText() );
		$tburl = $this->trackbackURL();

		// Autodiscovery RDF is placed in comments so HTML validator
		// won't barf. This is a rather icky workaround, but seems
		// frequently used by this kind of RDF thingy.
		//
		// Spec: http://www.sixapart.com/pronet/docs/trackback_spec
		return "<!--
<rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
		 xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
		 xmlns:trackback=\"http://madskills.com/public/xml/rss/module/trackback/\">
<rdf:Description
   rdf:about=\"$url\"
   dc:identifier=\"$url\"
   dc:title=\"$title\"
   trackback:ping=\"$tburl\" />
</rdf:RDF>
-->";
	}

	/**
	 * Generate strings used for xml 'id' names in monobook tabs
	 *
	 * @param $prepend string defaults to 'nstab-'
	 * @return String XML 'id' name
	 */
	public function getNamespaceKey( $prepend = 'nstab-' ) {
		global $wgContLang;
		// Gets the subject namespace if this title
		$namespace = MWNamespace::getSubject( $this->getNamespace() );
		// Checks if cononical namespace name exists for namespace
		if ( MWNamespace::exists( $this->getNamespace() ) ) {
			// Uses canonical namespace name
			$namespaceKey = MWNamespace::getCanonicalName( $namespace );
		} else {
			// Uses text of namespace
			$namespaceKey = $this->getSubjectNsText();
		}
		// Makes namespace key lowercase
		$namespaceKey = $wgContLang->lc( $namespaceKey );
		// Uses main
		if ( $namespaceKey == '' ) {
			$namespaceKey = 'main';
		}
		// Changes file to image for backwards compatibility
		if ( $namespaceKey == 'file' ) {
			$namespaceKey = 'image';
		}
		return $prepend . $namespaceKey;
	}

	/**
	 * Returns true if this is a special page.
	 *
	 * @return boolean
	 */
	public function isSpecialPage() {
		return $this->getNamespace() == NS_SPECIAL;
	}

	/**
	 * Returns true if this title resolves to the named special page
	 *
	 * @param $name String The special page name
	 * @return boolean
	 */
	public function isSpecial( $name ) {
		if ( $this->getNamespace() == NS_SPECIAL ) {
			list( $thisName, /* $subpage */ ) = SpecialPageFactory::resolveAlias( $this->getDBkey() );
			if ( $name == $thisName ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * If the Title refers to a special page alias which is not the local default, resolve
	 * the alias, and localise the name as necessary.  Otherwise, return $this
	 *
	 * @return Title
	 */
	public function fixSpecialName() {
		if ( $this->getNamespace() == NS_SPECIAL ) {
			list( $canonicalName, /*...*/ ) = SpecialPageFactory::resolveAlias( $this->mDbkeyform );
			if ( $canonicalName ) {
				$localName = SpecialPageFactory::getLocalNameFor( $canonicalName );
				if ( $localName != $this->mDbkeyform ) {
					return Title::makeTitle( NS_SPECIAL, $localName );
				}
			}
		}
		return $this;
	}

	/**
	 * Is this Title in a namespace which contains content?
	 * In other words, is this a content page, for the purposes of calculating
	 * statistics, etc?
	 *
	 * @return Boolean
	 */
	public function isContentPage() {
		return MWNamespace::isContent( $this->getNamespace() );
	}

	/**
	 * Get all extant redirects to this Title
	 *
	 * @param $ns Int|Null Single namespace to consider; NULL to consider all namespaces
	 * @return Array of Title redirects to this title
	 */
	public function getRedirectsHere( $ns = null ) {
		$redirs = array();

		$dbr = wfGetDB( DB_SLAVE );
		$where = array(
			'rd_namespace' => $this->getNamespace(),
			'rd_title' => $this->getDBkey(),
			'rd_from = page_id'
		);
		if ( !is_null( $ns ) ) {
			$where['page_namespace'] = $ns;
		}

		$res = $dbr->select(
			array( 'redirect', 'page' ),
			array( 'page_namespace', 'page_title' ),
			$where,
			__METHOD__
		);

		foreach ( $res as $row ) {
			$redirs[] = self::newFromRow( $row );
		}
		return $redirs;
	}

	/**
	 * Check if this Title is a valid redirect target
	 *
	 * @return Bool
	 */
	public function isValidRedirectTarget() {
		global $wgInvalidRedirectTargets;

		// invalid redirect targets are stored in a global array, but explicity disallow Userlogout here
		if ( $this->isSpecial( 'Userlogout' ) ) {
			return false;
		}

		foreach ( $wgInvalidRedirectTargets as $target ) {
			if ( $this->isSpecial( $target ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Get a backlink cache object
	 *
	 * @return object BacklinkCache
	 */
	function getBacklinkCache() {
		if ( is_null( $this->mBacklinkCache ) ) {
			$this->mBacklinkCache = new BacklinkCache( $this );
		}
		return $this->mBacklinkCache;
	}

	/**
	 * Whether the magic words __INDEX__ and __NOINDEX__ function for  this page.
	 *
	 * @return Boolean
	 */
	public function canUseNoindex() {
		global $wgContentNamespaces, $wgExemptFromUserRobotsControl;

		$bannedNamespaces = is_null( $wgExemptFromUserRobotsControl )
			? $wgContentNamespaces
			: $wgExemptFromUserRobotsControl;

		return !in_array( $this->mNamespace, $bannedNamespaces );

	}

	/**
	 * Returns restriction types for the current Title
	 *
	 * @return array applicable restriction types
	 */
	public function getRestrictionTypes() {
		if ( $this->getNamespace() == NS_SPECIAL ) {
			return array();
		}

		$types = self::getFilteredRestrictionTypes( $this->exists() );

		if ( $this->getNamespace() != NS_FILE ) {
			# Remove the upload restriction for non-file titles
			$types = array_diff( $types, array( 'upload' ) );
		}

		wfRunHooks( 'TitleGetRestrictionTypes', array( $this, &$types ) );

		wfDebug( __METHOD__ . ': applicable restriction types for ' .
			$this->getPrefixedText() . ' are ' . implode( ',', $types ) . "\n" );

		return $types;
	}
	/**
	 * Get a filtered list of all restriction types supported by this wiki.
	 * @param bool $exists True to get all restriction types that apply to
	 * titles that do exist, False for all restriction types that apply to
	 * titles that do not exist
	 * @return array
	 */
	public static function getFilteredRestrictionTypes( $exists = true ) {
		global $wgRestrictionTypes;
		$types = $wgRestrictionTypes;
		if ( $exists ) {
			# Remove the create restriction for existing titles
			$types = array_diff( $types, array( 'create' ) );
		} else {
			# Only the create and upload restrictions apply to non-existing titles
			$types = array_intersect( $types, array( 'create', 'upload' ) );
		}
		return $types;
	}

	/**
	 * Returns the raw sort key to be used for categories, with the specified
	 * prefix.  This will be fed to Collation::getSortKey() to get a
	 * binary sortkey that can be used for actual sorting.
	 *
	 * @param $prefix string The prefix to be used, specified using
	 *   {{defaultsort:}} or like [[Category:Foo|prefix]].  Empty for no
	 *   prefix.
	 * @return string
	 */
	public function getCategorySortkey( $prefix = '' ) {
		$unprefixed = $this->getText();

		// Anything that uses this hook should only depend
		// on the Title object passed in, and should probably
		// tell the users to run updateCollations.php --force
		// in order to re-sort existing category relations.
		wfRunHooks( 'GetDefaultSortkey', array( $this, &$unprefixed ) );
		if ( $prefix !== '' ) {
			# Separate with a line feed, so the unprefixed part is only used as
			# a tiebreaker when two pages have the exact same prefix.
			# In UCA, tab is the only character that can sort above LF
			# so we strip both of them from the original prefix.
			$prefix = strtr( $prefix, "\n\t", '  ' );
			return "$prefix\n$unprefixed";
		}
		return $unprefixed;
	}

	/**
	 * Get the language in which the content of this page is written.
	 * Defaults to $wgContLang, but in certain cases it can be e.g.
	 * $wgLang (such as special pages, which are in the user language).
	 *
	 * @since 1.18
	 * @return object Language
	 */
	public function getPageLanguage() {
		global $wgLang;
		if ( $this->getNamespace() == NS_SPECIAL ) {
			// special pages are in the user language
			return $wgLang;
		} elseif ( $this->isRedirect() ) {
			// the arrow on a redirect page is aligned according to the user language
			return $wgLang;
		} elseif ( $this->isCssOrJsPage() ) {
			// css/js should always be LTR and is, in fact, English
			return wfGetLangObj( 'en' );
		} elseif ( $this->getNamespace() == NS_MEDIAWIKI ) {
			// Parse mediawiki messages with correct target language
			list( /* $unused */, $lang ) = MessageCache::singleton()->figureMessage( $this->getText() );
			return wfGetLangObj( $lang );
		}
		global $wgContLang;
		// If nothing special, it should be in the wiki content language
		$pageLang = $wgContLang;
		// Hook at the end because we don't want to override the above stuff
		wfRunHooks( 'PageContentLanguage', array( $this, &$pageLang, $wgLang ) );
		return wfGetLangObj( $pageLang );
	}
}
