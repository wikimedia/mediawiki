<?php
/**
 * Representation of a title within %MediaWiki.
 *
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
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\MediaWikiServices;

/**
 * Represents a title within MediaWiki.
 * Optionally may contain an interwiki designation or namespace.
 * @note This class can fetch various kinds of data from the database;
 *       however, it does so inefficiently.
 * @note Consider using a TitleValue object instead. TitleValue is more lightweight
 *       and does not rely on global state or the database.
 */
class Title implements LinkTarget {
	/** @var HashBagOStuff */
	static private $titleCache = null;

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

	/** @var string Text form (spaces not underscores) of the main part */
	public $mTextform = '';

	/** @var string URL-encoded form of the main part */
	public $mUrlform = '';

	/** @var string Main part with underscores */
	public $mDbkeyform = '';

	/** @var string Database key with the initial letter in the case specified by the user */
	protected $mUserCaseDBKey;

	/** @var int Namespace index, i.e. one of the NS_xxxx constants */
	public $mNamespace = NS_MAIN;

	/** @var string Interwiki prefix */
	public $mInterwiki = '';

	/** @var bool Was this Title created from a string with a local interwiki prefix? */
	private $mLocalInterwiki = false;

	/** @var string Title fragment (i.e. the bit after the #) */
	public $mFragment = '';

	/** @var int Article ID, fetched from the link cache on demand */
	public $mArticleID = -1;

	/** @var bool|int ID of most recent revision */
	protected $mLatestID = false;

	/**
	 * @var bool|string ID of the page's content model, i.e. one of the
	 *   CONTENT_MODEL_XXX constants
	 */
	private $mContentModel = false;

	/**
	 * @var bool If a content model was forced via setContentModel()
	 *   this will be true to avoid having other code paths reset it
	 */
	private $mForcedContentModel = false;

	/** @var int Estimated number of revisions; null of not loaded */
	private $mEstimateRevisions;

	/** @var array Array of groups allowed to edit this article */
	public $mRestrictions = [];

	/** @var string|bool */
	protected $mOldRestrictions = false;

	/** @var bool Cascade restrictions on this page to included templates and images? */
	public $mCascadeRestriction;

	/** Caching the results of getCascadeProtectionSources */
	public $mCascadingRestrictions;

	/** @var array When do the restrictions on this page expire? */
	protected $mRestrictionsExpiry = [];

	/** @var bool Are cascading restrictions in effect on this page? */
	protected $mHasCascadingRestrictions;

	/** @var array Where are the cascading restrictions coming from on this page? */
	public $mCascadeSources;

	/** @var bool Boolean for initialisation on demand */
	public $mRestrictionsLoaded = false;

	/** @var string Text form including namespace/interwiki, initialised on demand */
	protected $mPrefixedText = null;

	/** @var mixed Cached value for getTitleProtection (create protection) */
	public $mTitleProtection;

	/**
	 * @var int Namespace index when there is no namespace. Don't change the
	 *   following default, NS_MAIN is hardcoded in several places. See bug 696.
	 *   Zero except in {{transclusion}} tags.
	 */
	public $mDefaultNamespace = NS_MAIN;

	/** @var int The page length, 0 for special pages */
	protected $mLength = -1;

	/** @var null Is the article at this title a redirect? */
	public $mRedirect = null;

	/** @var array Associative array of user ID -> timestamp/false */
	private $mNotificationTimestamp = [];

	/** @var bool Whether a page has any subpages */
	private $mHasSubpages;

	/** @var bool The (string) language code of the page's language and content code. */
	private $mPageLanguage = false;

	/** @var string|bool|null The page language code from the database, null if not saved in
	 * the database or false if not loaded, yet. */
	private $mDbPageLanguage = false;

	/** @var TitleValue A corresponding TitleValue object */
	private $mTitleValue = null;

	/** @var bool Would deleting this page be a big deletion? */
	private $mIsBigDeletion = null;
	// @}

	/**
	 * B/C kludge: provide a TitleParser for use by Title.
	 * Ideally, Title would have no methods that need this.
	 * Avoid usage of this singleton by using TitleValue
	 * and the associated services when possible.
	 *
	 * @return TitleFormatter
	 */
	private static function getTitleFormatter() {
		return MediaWikiServices::getInstance()->getTitleFormatter();
	}

	/**
	 * B/C kludge: provide an InterwikiLookup for use by Title.
	 * Ideally, Title would have no methods that need this.
	 * Avoid usage of this singleton by using TitleValue
	 * and the associated services when possible.
	 *
	 * @return InterwikiLookup
	 */
	private static function getInterwikiLookup() {
		return MediaWikiServices::getInstance()->getInterwikiLookup();
	}

	/**
	 * @access protected
	 */
	function __construct() {
	}

	/**
	 * Create a new Title from a prefixed DB key
	 *
	 * @param string $key The database key, which has underscores
	 *	instead of spaces, possibly including namespace and
	 *	interwiki prefixes
	 * @return Title|null Title, or null on an error
	 */
	public static function newFromDBkey( $key ) {
		$t = new Title();
		$t->mDbkeyform = $key;

		try {
			$t->secureAndSplit();
			return $t;
		} catch ( MalformedTitleException $ex ) {
			return null;
		}
	}

	/**
	 * Create a new Title from a TitleValue
	 *
	 * @param TitleValue $titleValue Assumed to be safe.
	 *
	 * @return Title
	 */
	public static function newFromTitleValue( TitleValue $titleValue ) {
		return self::newFromLinkTarget( $titleValue );
	}

	/**
	 * Create a new Title from a LinkTarget
	 *
	 * @param LinkTarget $linkTarget Assumed to be safe.
	 *
	 * @return Title
	 */
	public static function newFromLinkTarget( LinkTarget $linkTarget ) {
		if ( $linkTarget instanceof Title ) {
			// Special case if it's already a Title object
			return $linkTarget;
		}
		return self::makeTitle(
			$linkTarget->getNamespace(),
			$linkTarget->getText(),
			$linkTarget->getFragment(),
			$linkTarget->getInterwiki()
		);
	}

	/**
	 * Create a new Title from text, such as what one would find in a link. De-
	 * codes any HTML entities in the text.
	 *
	 * @param string|int|null $text The link text; spaces, prefixes, and an
	 *   initial ':' indicating the main namespace are accepted.
	 * @param int $defaultNamespace The namespace to use if none is specified
	 *   by a prefix.  If you want to force a specific namespace even if
	 *   $text might begin with a namespace prefix, use makeTitle() or
	 *   makeTitleSafe().
	 * @throws InvalidArgumentException
	 * @return Title|null Title or null on an error.
	 */
	public static function newFromText( $text, $defaultNamespace = NS_MAIN ) {
		// DWIM: Integers can be passed in here when page titles are used as array keys.
		if ( $text !== null && !is_string( $text ) && !is_int( $text ) ) {
			throw new InvalidArgumentException( '$text must be a string.' );
		}
		if ( $text === null ) {
			return null;
		}

		try {
			return Title::newFromTextThrow( strval( $text ), $defaultNamespace );
		} catch ( MalformedTitleException $ex ) {
			return null;
		}
	}

	/**
	 * Like Title::newFromText(), but throws MalformedTitleException when the title is invalid,
	 * rather than returning null.
	 *
	 * The exception subclasses encode detailed information about why the title is invalid.
	 *
	 * @see Title::newFromText
	 *
	 * @since 1.25
	 * @param string $text Title text to check
	 * @param int $defaultNamespace
	 * @throws MalformedTitleException If the title is invalid
	 * @return Title
	 */
	public static function newFromTextThrow( $text, $defaultNamespace = NS_MAIN ) {
		if ( is_object( $text ) ) {
			throw new MWException( '$text must be a string, given an object' );
		}

		$titleCache = self::getTitleCache();

		// Wiki pages often contain multiple links to the same page.
		// Title normalization and parsing can become expensive on pages with many
		// links, so we can save a little time by caching them.
		// In theory these are value objects and won't get changed...
		if ( $defaultNamespace == NS_MAIN ) {
			$t = $titleCache->get( $text );
			if ( $t ) {
				return $t;
			}
		}

		// Convert things like &eacute; &#257; or &#x3017; into normalized (bug 14952) text
		$filteredText = Sanitizer::decodeCharReferencesAndNormalize( $text );

		$t = new Title();
		$t->mDbkeyform = strtr( $filteredText, ' ', '_' );
		$t->mDefaultNamespace = intval( $defaultNamespace );

		$t->secureAndSplit();
		if ( $defaultNamespace == NS_MAIN ) {
			$titleCache->set( $text, $t );
		}
		return $t;
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
	 * @param string $url The title, as might be taken from a URL
	 * @return Title|null The new object, or null on an error
	 */
	public static function newFromURL( $url ) {
		$t = new Title();

		# For compatibility with old buggy URLs. "+" is usually not valid in titles,
		# but some URLs used it as a space replacement and they still come
		# from some external search tools.
		if ( strpos( self::legalChars(), '+' ) === false ) {
			$url = strtr( $url, '+', ' ' );
		}

		$t->mDbkeyform = strtr( $url, ' ', '_' );

		try {
			$t->secureAndSplit();
			return $t;
		} catch ( MalformedTitleException $ex ) {
			return null;
		}
	}

	/**
	 * @return HashBagOStuff
	 */
	private static function getTitleCache() {
		if ( self::$titleCache == null ) {
			self::$titleCache = new HashBagOStuff( [ 'maxKeys' => self::CACHE_MAX ] );
		}
		return self::$titleCache;
	}

	/**
	 * Returns a list of fields that are to be selected for initializing Title
	 * objects or LinkCache entries. Uses $wgContentHandlerUseDB to determine
	 * whether to include page_content_model.
	 *
	 * @return array
	 */
	protected static function getSelectFields() {
		global $wgContentHandlerUseDB, $wgPageLanguageUseDB;

		$fields = [
			'page_namespace', 'page_title', 'page_id',
			'page_len', 'page_is_redirect', 'page_latest',
		];

		if ( $wgContentHandlerUseDB ) {
			$fields[] = 'page_content_model';
		}

		if ( $wgPageLanguageUseDB ) {
			$fields[] = 'page_lang';
		}

		return $fields;
	}

	/**
	 * Create a new Title from an article ID
	 *
	 * @param int $id The page_id corresponding to the Title to create
	 * @param int $flags Use Title::GAID_FOR_UPDATE to use master
	 * @return Title|null The new object, or null on an error
	 */
	public static function newFromID( $id, $flags = 0 ) {
		$db = ( $flags & self::GAID_FOR_UPDATE ) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_REPLICA );
		$row = $db->selectRow(
			'page',
			self::getSelectFields(),
			[ 'page_id' => $id ],
			__METHOD__
		);
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
	 * @param int[] $ids Array of IDs
	 * @return Title[] Array of Titles
	 */
	public static function newFromIDs( $ids ) {
		if ( !count( $ids ) ) {
			return [];
		}
		$dbr = wfGetDB( DB_REPLICA );

		$res = $dbr->select(
			'page',
			self::getSelectFields(),
			[ 'page_id' => $ids ],
			__METHOD__
		);

		$titles = [];
		foreach ( $res as $row ) {
			$titles[] = Title::newFromRow( $row );
		}
		return $titles;
	}

	/**
	 * Make a Title object from a DB row
	 *
	 * @param stdClass $row Object database row (needs at least page_title,page_namespace)
	 * @return Title Corresponding Title
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
	 * @param stdClass|bool $row Database row
	 */
	public function loadFromRow( $row ) {
		if ( $row ) { // page found
			if ( isset( $row->page_id ) ) {
				$this->mArticleID = (int)$row->page_id;
			}
			if ( isset( $row->page_len ) ) {
				$this->mLength = (int)$row->page_len;
			}
			if ( isset( $row->page_is_redirect ) ) {
				$this->mRedirect = (bool)$row->page_is_redirect;
			}
			if ( isset( $row->page_latest ) ) {
				$this->mLatestID = (int)$row->page_latest;
			}
			if ( !$this->mForcedContentModel && isset( $row->page_content_model ) ) {
				$this->mContentModel = strval( $row->page_content_model );
			} elseif ( !$this->mForcedContentModel ) {
				$this->mContentModel = false; # initialized lazily in getContentModel()
			}
			if ( isset( $row->page_lang ) ) {
				$this->mDbPageLanguage = (string)$row->page_lang;
			}
			if ( isset( $row->page_restrictions ) ) {
				$this->mOldRestrictions = $row->page_restrictions;
			}
		} else { // page not found
			$this->mArticleID = 0;
			$this->mLength = 0;
			$this->mRedirect = false;
			$this->mLatestID = 0;
			if ( !$this->mForcedContentModel ) {
				$this->mContentModel = false; # initialized lazily in getContentModel()
			}
		}
	}

	/**
	 * Create a new Title from a namespace index and a DB key.
	 * It's assumed that $ns and $title are *valid*, for instance when
	 * they came directly from the database or a special page name.
	 * For convenience, spaces are converted to underscores so that
	 * eg user_text fields can be used directly.
	 *
	 * @param int $ns The namespace of the article
	 * @param string $title The unprefixed database key form
	 * @param string $fragment The link fragment (after the "#")
	 * @param string $interwiki The interwiki prefix
	 * @return Title The new object
	 */
	public static function makeTitle( $ns, $title, $fragment = '', $interwiki = '' ) {
		$t = new Title();
		$t->mInterwiki = $interwiki;
		$t->mFragment = $fragment;
		$t->mNamespace = $ns = intval( $ns );
		$t->mDbkeyform = strtr( $title, ' ', '_' );
		$t->mArticleID = ( $ns >= 0 ) ? -1 : 0;
		$t->mUrlform = wfUrlencode( $t->mDbkeyform );
		$t->mTextform = strtr( $title, '_', ' ' );
		$t->mContentModel = false; # initialized lazily in getContentModel()
		return $t;
	}

	/**
	 * Create a new Title from a namespace index and a DB key.
	 * The parameters will be checked for validity, which is a bit slower
	 * than makeTitle() but safer for user-provided data.
	 *
	 * @param int $ns The namespace of the article
	 * @param string $title Database key form
	 * @param string $fragment The link fragment (after the "#")
	 * @param string $interwiki Interwiki prefix
	 * @return Title|null The new object, or null on an error
	 */
	public static function makeTitleSafe( $ns, $title, $fragment = '', $interwiki = '' ) {
		if ( !MWNamespace::exists( $ns ) ) {
			return null;
		}

		$t = new Title();
		$t->mDbkeyform = Title::makeName( $ns, $title, $fragment, $interwiki, true );

		try {
			$t->secureAndSplit();
			return $t;
		} catch ( MalformedTitleException $ex ) {
			return null;
		}
	}

	/**
	 * Create a new Title for the Main Page
	 *
	 * @return Title The new object
	 */
	public static function newMainPage() {
		$title = Title::newFromText( wfMessage( 'mainpage' )->inContentLanguage()->text() );
		// Don't give fatal errors if the message is broken
		if ( !$title ) {
			$title = Title::newFromText( 'Main Page' );
		}
		return $title;
	}

	/**
	 * Get the prefixed DB key associated with an ID
	 *
	 * @param int $id The page_id of the article
	 * @return Title|null An object representing the article, or null if no such article was found
	 */
	public static function nameOf( $id ) {
		$dbr = wfGetDB( DB_REPLICA );

		$s = $dbr->selectRow(
			'page',
			[ 'page_namespace', 'page_title' ],
			[ 'page_id' => $id ],
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
	 * @return string The list of characters, not delimited
	 */
	public static function legalChars() {
		global $wgLegalTitleChars;
		return $wgLegalTitleChars;
	}

	/**
	 * Returns a simple regex that will match on characters and sequences invalid in titles.
	 * Note that this doesn't pick up many things that could be wrong with titles, but that
	 * replacing this regex with something valid will make many titles valid.
	 *
	 * @deprecated since 1.25, use MediaWikiTitleCodec::getTitleInvalidRegex() instead
	 *
	 * @return string Regex string
	 */
	static function getTitleInvalidRegex() {
		wfDeprecated( __METHOD__, '1.25' );
		return MediaWikiTitleCodec::getTitleInvalidRegex();
	}

	/**
	 * Utility method for converting a character sequence from bytes to Unicode.
	 *
	 * Primary usecase being converting $wgLegalTitleChars to a sequence usable in
	 * javascript, as PHP uses UTF-8 bytes where javascript uses Unicode code units.
	 *
	 * @param string $byteClass
	 * @return string
	 */
	public static function convertByteClassToUnicodeClass( $byteClass ) {
		$length = strlen( $byteClass );
		// Input token queue
		$x0 = $x1 = $x2 = '';
		// Decoded queue
		$d0 = $d1 = $d2 = '';
		// Decoded integer codepoints
		$ord0 = $ord1 = $ord2 = 0;
		// Re-encoded queue
		$r0 = $r1 = $r2 = '';
		// Output
		$out = '';
		// Flags
		$allowUnicode = false;
		for ( $pos = 0; $pos < $length; $pos++ ) {
			// Shift the queues down
			$x2 = $x1;
			$x1 = $x0;
			$d2 = $d1;
			$d1 = $d0;
			$ord2 = $ord1;
			$ord1 = $ord0;
			$r2 = $r1;
			$r1 = $r0;
			// Load the current input token and decoded values
			$inChar = $byteClass[$pos];
			if ( $inChar == '\\' ) {
				if ( preg_match( '/x([0-9a-fA-F]{2})/A', $byteClass, $m, 0, $pos + 1 ) ) {
					$x0 = $inChar . $m[0];
					$d0 = chr( hexdec( $m[1] ) );
					$pos += strlen( $m[0] );
				} elseif ( preg_match( '/[0-7]{3}/A', $byteClass, $m, 0, $pos + 1 ) ) {
					$x0 = $inChar . $m[0];
					$d0 = chr( octdec( $m[0] ) );
					$pos += strlen( $m[0] );
				} elseif ( $pos + 1 >= $length ) {
					$x0 = $d0 = '\\';
				} else {
					$d0 = $byteClass[$pos + 1];
					$x0 = $inChar . $d0;
					$pos += 1;
				}
			} else {
				$x0 = $d0 = $inChar;
			}
			$ord0 = ord( $d0 );
			// Load the current re-encoded value
			if ( $ord0 < 32 || $ord0 == 0x7f ) {
				$r0 = sprintf( '\x%02x', $ord0 );
			} elseif ( $ord0 >= 0x80 ) {
				// Allow unicode if a single high-bit character appears
				$r0 = sprintf( '\x%02x', $ord0 );
				$allowUnicode = true;
			} elseif ( strpos( '-\\[]^', $d0 ) !== false ) {
				$r0 = '\\' . $d0;
			} else {
				$r0 = $d0;
			}
			// Do the output
			if ( $x0 !== '' && $x1 === '-' && $x2 !== '' ) {
				// Range
				if ( $ord2 > $ord0 ) {
					// Empty range
				} elseif ( $ord0 >= 0x80 ) {
					// Unicode range
					$allowUnicode = true;
					if ( $ord2 < 0x80 ) {
						// Keep the non-unicode section of the range
						$out .= "$r2-\\x7F";
					}
				} else {
					// Normal range
					$out .= "$r2-$r0";
				}
				// Reset state to the initial value
				$x0 = $x1 = $d0 = $d1 = $r0 = $r1 = '';
			} elseif ( $ord2 < 0x80 ) {
				// ASCII character
				$out .= $r2;
			}
		}
		if ( $ord1 < 0x80 ) {
			$out .= $r1;
		}
		if ( $ord0 < 0x80 ) {
			$out .= $r0;
		}
		if ( $allowUnicode ) {
			$out .= '\u0080-\uFFFF';
		}
		return $out;
	}

	/**
	 * Make a prefixed DB key from a DB key and a namespace index
	 *
	 * @param int $ns Numerical representation of the namespace
	 * @param string $title The DB key form the title
	 * @param string $fragment The link fragment (after the "#")
	 * @param string $interwiki The interwiki prefix
	 * @param bool $canonicalNamespace If true, use the canonical name for
	 *   $ns instead of the localized version.
	 * @return string The prefixed form of the title
	 */
	public static function makeName( $ns, $title, $fragment = '', $interwiki = '',
		$canonicalNamespace = false
	) {
		global $wgContLang;

		if ( $canonicalNamespace ) {
			$namespace = MWNamespace::getCanonicalName( $ns );
		} else {
			$namespace = $wgContLang->getNsText( $ns );
		}
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
	 * Escape a text fragment, say from a link, for a URL
	 *
	 * @param string $fragment Containing a URL or link fragment (after the "#")
	 * @return string Escaped string
	 */
	static function escapeFragmentForURL( $fragment ) {
		# Note that we don't urlencode the fragment.  urlencoded Unicode
		# fragments appear not to work in IE (at least up to 7) or in at least
		# one version of Opera 9.x.  The W3C validator, for one, doesn't seem
		# to care if they aren't encoded.
		return Sanitizer::escapeId( $fragment, 'noninitial' );
	}

	/**
	 * Callback for usort() to do title sorts by (namespace, title)
	 *
	 * @param LinkTarget $a
	 * @param LinkTarget $b
	 *
	 * @return int Result of string comparison, or namespace comparison
	 */
	public static function compare( LinkTarget $a, LinkTarget $b ) {
		if ( $a->getNamespace() == $b->getNamespace() ) {
			return strcmp( $a->getText(), $b->getText() );
		} else {
			return $a->getNamespace() - $b->getNamespace();
		}
	}

	/**
	 * Determine whether the object refers to a page within
	 * this project (either this wiki or a wiki with a local
	 * interwiki, see https://www.mediawiki.org/wiki/Manual:Interwiki_table#iw_local )
	 *
	 * @return bool True if this is an in-project interwiki link or a wikilink, false otherwise
	 */
	public function isLocal() {
		if ( $this->isExternal() ) {
			$iw = self::getInterwikiLookup()->fetch( $this->mInterwiki );
			if ( $iw ) {
				return $iw->isLocal();
			}
		}
		return true;
	}

	/**
	 * Is this Title interwiki?
	 *
	 * @return bool
	 */
	public function isExternal() {
		return $this->mInterwiki !== '';
	}

	/**
	 * Get the interwiki prefix
	 *
	 * Use Title::isExternal to check if a interwiki is set
	 *
	 * @return string Interwiki prefix
	 */
	public function getInterwiki() {
		return $this->mInterwiki;
	}

	/**
	 * Was this a local interwiki link?
	 *
	 * @return bool
	 */
	public function wasLocalInterwiki() {
		return $this->mLocalInterwiki;
	}

	/**
	 * Determine whether the object refers to a page within
	 * this project and is transcludable.
	 *
	 * @return bool True if this is transcludable
	 */
	public function isTrans() {
		if ( !$this->isExternal() ) {
			return false;
		}

		return self::getInterwikiLookup()->fetch( $this->mInterwiki )->isTranscludable();
	}

	/**
	 * Returns the DB name of the distant wiki which owns the object.
	 *
	 * @return string The DB name
	 */
	public function getTransWikiID() {
		if ( !$this->isExternal() ) {
			return false;
		}

		return self::getInterwikiLookup()->fetch( $this->mInterwiki )->getWikiID();
	}

	/**
	 * Get a TitleValue object representing this Title.
	 *
	 * @note Not all valid Titles have a corresponding valid TitleValue
	 * (e.g. TitleValues cannot represent page-local links that have a
	 * fragment but no title text).
	 *
	 * @return TitleValue|null
	 */
	public function getTitleValue() {
		if ( $this->mTitleValue === null ) {
			try {
				$this->mTitleValue = new TitleValue(
					$this->getNamespace(),
					$this->getDBkey(),
					$this->getFragment(),
					$this->getInterwiki()
				);
			} catch ( InvalidArgumentException $ex ) {
				wfDebug( __METHOD__ . ': Can\'t create a TitleValue for [[' .
					$this->getPrefixedText() . ']]: ' . $ex->getMessage() . "\n" );
			}
		}

		return $this->mTitleValue;
	}

	/**
	 * Get the text form (spaces not underscores) of the main part
	 *
	 * @return string Main part of the title
	 */
	public function getText() {
		return $this->mTextform;
	}

	/**
	 * Get the URL-encoded form of the main part
	 *
	 * @return string Main part of the title, URL-encoded
	 */
	public function getPartialURL() {
		return $this->mUrlform;
	}

	/**
	 * Get the main part with underscores
	 *
	 * @return string Main part of the title, with underscores
	 */
	public function getDBkey() {
		return $this->mDbkeyform;
	}

	/**
	 * Get the DB key with the initial letter case as specified by the user
	 *
	 * @return string DB key
	 */
	function getUserCaseDBKey() {
		if ( !is_null( $this->mUserCaseDBKey ) ) {
			return $this->mUserCaseDBKey;
		} else {
			// If created via makeTitle(), $this->mUserCaseDBKey is not set.
			return $this->mDbkeyform;
		}
	}

	/**
	 * Get the namespace index, i.e. one of the NS_xxxx constants.
	 *
	 * @return int Namespace index
	 */
	public function getNamespace() {
		return $this->mNamespace;
	}

	/**
	 * Get the page's content model id, see the CONTENT_MODEL_XXX constants.
	 *
	 * @param int $flags A bit field; may be Title::GAID_FOR_UPDATE to select for update
	 * @return string Content model id
	 */
	public function getContentModel( $flags = 0 ) {
		if ( !$this->mForcedContentModel
			&& ( !$this->mContentModel || $flags === Title::GAID_FOR_UPDATE )
			&& $this->getArticleID( $flags )
		) {
			$linkCache = LinkCache::singleton();
			$linkCache->addLinkObj( $this ); # in case we already had an article ID
			$this->mContentModel = $linkCache->getGoodLinkFieldObj( $this, 'model' );
		}

		if ( !$this->mContentModel ) {
			$this->mContentModel = ContentHandler::getDefaultModelFor( $this );
		}

		return $this->mContentModel;
	}

	/**
	 * Convenience method for checking a title's content model name
	 *
	 * @param string $id The content model ID (use the CONTENT_MODEL_XXX constants).
	 * @return bool True if $this->getContentModel() == $id
	 */
	public function hasContentModel( $id ) {
		return $this->getContentModel() == $id;
	}

	/**
	 * Set a proposed content model for the page for permissions
	 * checking. This does not actually change the content model
	 * of a title!
	 *
	 * Additionally, you should make sure you've checked
	 * ContentHandler::canBeUsedOn() first.
	 *
	 * @since 1.28
	 * @param string $model CONTENT_MODEL_XXX constant
	 */
	public function setContentModel( $model ) {
		$this->mContentModel = $model;
		$this->mForcedContentModel = true;
	}

	/**
	 * Get the namespace text
	 *
	 * @return string Namespace text
	 */
	public function getNsText() {
		if ( $this->isExternal() ) {
			// This probably shouldn't even happen,
			// but for interwiki transclusion it sometimes does.
			// Use the canonical namespaces if possible to try to
			// resolve a foreign namespace.
			if ( MWNamespace::exists( $this->mNamespace ) ) {
				return MWNamespace::getCanonicalName( $this->mNamespace );
			}
		}

		try {
			$formatter = self::getTitleFormatter();
			return $formatter->getNamespaceName( $this->mNamespace, $this->mDbkeyform );
		} catch ( InvalidArgumentException $ex ) {
			wfDebug( __METHOD__ . ': ' . $ex->getMessage() . "\n" );
			return false;
		}
	}

	/**
	 * Get the namespace text of the subject (rather than talk) page
	 *
	 * @return string Namespace text
	 */
	public function getSubjectNsText() {
		global $wgContLang;
		return $wgContLang->getNsText( MWNamespace::getSubject( $this->mNamespace ) );
	}

	/**
	 * Get the namespace text of the talk page
	 *
	 * @return string Namespace text
	 */
	public function getTalkNsText() {
		global $wgContLang;
		return $wgContLang->getNsText( MWNamespace::getTalk( $this->mNamespace ) );
	}

	/**
	 * Could this title have a corresponding talk page?
	 *
	 * @return bool
	 */
	public function canTalk() {
		return MWNamespace::canTalk( $this->mNamespace );
	}

	/**
	 * Is this in a namespace that allows actual pages?
	 *
	 * @return bool
	 */
	public function canExist() {
		return $this->mNamespace >= NS_MAIN;
	}

	/**
	 * Can this title be added to a user's watchlist?
	 *
	 * @return bool
	 */
	public function isWatchable() {
		return !$this->isExternal() && MWNamespace::isWatchable( $this->getNamespace() );
	}

	/**
	 * Returns true if this is a special page.
	 *
	 * @return bool
	 */
	public function isSpecialPage() {
		return $this->getNamespace() == NS_SPECIAL;
	}

	/**
	 * Returns true if this title resolves to the named special page
	 *
	 * @param string $name The special page name
	 * @return bool
	 */
	public function isSpecial( $name ) {
		if ( $this->isSpecialPage() ) {
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
		if ( $this->isSpecialPage() ) {
			list( $canonicalName, $par ) = SpecialPageFactory::resolveAlias( $this->mDbkeyform );
			if ( $canonicalName ) {
				$localName = SpecialPageFactory::getLocalNameFor( $canonicalName, $par );
				if ( $localName != $this->mDbkeyform ) {
					return Title::makeTitle( NS_SPECIAL, $localName );
				}
			}
		}
		return $this;
	}

	/**
	 * Returns true if the title is inside the specified namespace.
	 *
	 * Please make use of this instead of comparing to getNamespace()
	 * This function is much more resistant to changes we may make
	 * to namespaces than code that makes direct comparisons.
	 * @param int $ns The namespace
	 * @return bool
	 * @since 1.19
	 */
	public function inNamespace( $ns ) {
		return MWNamespace::equals( $this->getNamespace(), $ns );
	}

	/**
	 * Returns true if the title is inside one of the specified namespaces.
	 *
	 * @param int|int[] $namespaces,... The namespaces to check for
	 * @return bool
	 * @since 1.19
	 */
	public function inNamespaces( /* ... */ ) {
		$namespaces = func_get_args();
		if ( count( $namespaces ) > 0 && is_array( $namespaces[0] ) ) {
			$namespaces = $namespaces[0];
		}

		foreach ( $namespaces as $ns ) {
			if ( $this->inNamespace( $ns ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns true if the title has the same subject namespace as the
	 * namespace specified.
	 * For example this method will take NS_USER and return true if namespace
	 * is either NS_USER or NS_USER_TALK since both of them have NS_USER
	 * as their subject namespace.
	 *
	 * This is MUCH simpler than individually testing for equivalence
	 * against both NS_USER and NS_USER_TALK, and is also forward compatible.
	 * @since 1.19
	 * @param int $ns
	 * @return bool
	 */
	public function hasSubjectNamespace( $ns ) {
		return MWNamespace::subjectEquals( $this->getNamespace(), $ns );
	}

	/**
	 * Is this Title in a namespace which contains content?
	 * In other words, is this a content page, for the purposes of calculating
	 * statistics, etc?
	 *
	 * @return bool
	 */
	public function isContentPage() {
		return MWNamespace::isContent( $this->getNamespace() );
	}

	/**
	 * Would anybody with sufficient privileges be able to move this page?
	 * Some pages just aren't movable.
	 *
	 * @return bool
	 */
	public function isMovable() {
		if ( !MWNamespace::isMovable( $this->getNamespace() ) || $this->isExternal() ) {
			// Interwiki title or immovable namespace. Hooks don't get to override here
			return false;
		}

		$result = true;
		Hooks::run( 'TitleIsMovable', [ $this, &$result ] );
		return $result;
	}

	/**
	 * Is this the mainpage?
	 * @note Title::newFromText seems to be sufficiently optimized by the title
	 * cache that we don't need to over-optimize by doing direct comparisons and
	 * accidentally creating new bugs where $title->equals( Title::newFromText() )
	 * ends up reporting something differently than $title->isMainPage();
	 *
	 * @since 1.18
	 * @return bool
	 */
	public function isMainPage() {
		return $this->equals( Title::newMainPage() );
	}

	/**
	 * Is this a subpage?
	 *
	 * @return bool
	 */
	public function isSubpage() {
		return MWNamespace::hasSubpages( $this->mNamespace )
			? strpos( $this->getText(), '/' ) !== false
			: false;
	}

	/**
	 * Is this a conversion table for the LanguageConverter?
	 *
	 * @return bool
	 */
	public function isConversionTable() {
		// @todo ConversionTable should become a separate content model.

		return $this->getNamespace() == NS_MEDIAWIKI &&
			strpos( $this->getText(), 'Conversiontable/' ) === 0;
	}

	/**
	 * Does that page contain wikitext, or it is JS, CSS or whatever?
	 *
	 * @return bool
	 */
	public function isWikitextPage() {
		return $this->hasContentModel( CONTENT_MODEL_WIKITEXT );
	}

	/**
	 * Could this page contain custom CSS or JavaScript for the global UI.
	 * This is generally true for pages in the MediaWiki namespace having CONTENT_MODEL_CSS
	 * or CONTENT_MODEL_JAVASCRIPT.
	 *
	 * This method does *not* return true for per-user JS/CSS. Use isCssJsSubpage()
	 * for that!
	 *
	 * Note that this method should not return true for pages that contain and
	 * show "inactive" CSS or JS.
	 *
	 * @return bool
	 * @todo FIXME: Rename to isSiteConfigPage() and remove deprecated hook
	 */
	public function isCssOrJsPage() {
		$isCssOrJsPage = NS_MEDIAWIKI == $this->mNamespace
			&& ( $this->hasContentModel( CONTENT_MODEL_CSS )
				|| $this->hasContentModel( CONTENT_MODEL_JAVASCRIPT ) );

		# @note This hook is also called in ContentHandler::getDefaultModel.
		#   It's called here again to make sure hook functions can force this
		#   method to return true even outside the MediaWiki namespace.

		Hooks::run( 'TitleIsCssOrJsPage', [ $this, &$isCssOrJsPage ], '1.25' );

		return $isCssOrJsPage;
	}

	/**
	 * Is this a .css or .js subpage of a user page?
	 * @return bool
	 * @todo FIXME: Rename to isUserConfigPage()
	 */
	public function isCssJsSubpage() {
		return ( NS_USER == $this->mNamespace && $this->isSubpage()
				&& ( $this->hasContentModel( CONTENT_MODEL_CSS )
					|| $this->hasContentModel( CONTENT_MODEL_JAVASCRIPT ) ) );
	}

	/**
	 * Trim down a .css or .js subpage title to get the corresponding skin name
	 *
	 * @return string Containing skin name from .css or .js subpage title
	 */
	public function getSkinFromCssJsSubpage() {
		$subpage = explode( '/', $this->mTextform );
		$subpage = $subpage[count( $subpage ) - 1];
		$lastdot = strrpos( $subpage, '.' );
		if ( $lastdot === false ) {
			return $subpage; # Never happens: only called for names ending in '.css' or '.js'
		}
		return substr( $subpage, 0, $lastdot );
	}

	/**
	 * Is this a .css subpage of a user page?
	 *
	 * @return bool
	 */
	public function isCssSubpage() {
		return ( NS_USER == $this->mNamespace && $this->isSubpage()
			&& $this->hasContentModel( CONTENT_MODEL_CSS ) );
	}

	/**
	 * Is this a .js subpage of a user page?
	 *
	 * @return bool
	 */
	public function isJsSubpage() {
		return ( NS_USER == $this->mNamespace && $this->isSubpage()
			&& $this->hasContentModel( CONTENT_MODEL_JAVASCRIPT ) );
	}

	/**
	 * Is this a talk page of some sort?
	 *
	 * @return bool
	 */
	public function isTalkPage() {
		return MWNamespace::isTalk( $this->getNamespace() );
	}

	/**
	 * Get a Title object associated with the talk page of this article
	 *
	 * @return Title The object for the talk page
	 */
	public function getTalkPage() {
		return Title::makeTitle( MWNamespace::getTalk( $this->getNamespace() ), $this->getDBkey() );
	}

	/**
	 * Get a title object associated with the subject page of this
	 * talk page
	 *
	 * @return Title The object for the subject page
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
	 * Get the other title for this page, if this is a subject page
	 * get the talk page, if it is a subject page get the talk page
	 *
	 * @since 1.25
	 * @throws MWException
	 * @return Title
	 */
	public function getOtherPage() {
		if ( $this->isSpecialPage() ) {
			throw new MWException( 'Special pages cannot have other pages' );
		}
		if ( $this->isTalkPage() ) {
			return $this->getSubjectPage();
		} else {
			return $this->getTalkPage();
		}
	}

	/**
	 * Get the default namespace index, for when there is no namespace
	 *
	 * @return int Default namespace index
	 */
	public function getDefaultNamespace() {
		return $this->mDefaultNamespace;
	}

	/**
	 * Get the Title fragment (i.e.\ the bit after the #) in text form
	 *
	 * Use Title::hasFragment to check for a fragment
	 *
	 * @return string Title fragment
	 */
	public function getFragment() {
		return $this->mFragment;
	}

	/**
	 * Check if a Title fragment is set
	 *
	 * @return bool
	 * @since 1.23
	 */
	public function hasFragment() {
		return $this->mFragment !== '';
	}

	/**
	 * Get the fragment in URL form, including the "#" character if there is one
	 * @return string Fragment in URL form
	 */
	public function getFragmentForURL() {
		if ( !$this->hasFragment() ) {
			return '';
		} else {
			return '#' . Title::escapeFragmentForURL( $this->getFragment() );
		}
	}

	/**
	 * Set the fragment for this title. Removes the first character from the
	 * specified fragment before setting, so it assumes you're passing it with
	 * an initial "#".
	 *
	 * Deprecated for public use, use Title::makeTitle() with fragment parameter,
	 * or Title::createFragmentTarget().
	 * Still in active use privately.
	 *
	 * @private
	 * @param string $fragment Text
	 */
	public function setFragment( $fragment ) {
		$this->mFragment = strtr( substr( $fragment, 1 ), '_', ' ' );
	}

	/**
	 * Creates a new Title for a different fragment of the same page.
	 *
	 * @since 1.27
	 * @param string $fragment
	 * @return Title
	 */
	public function createFragmentTarget( $fragment ) {
		return self::makeTitle(
			$this->getNamespace(),
			$this->getText(),
			$fragment,
			$this->getInterwiki()
		);

	}

	/**
	 * Prefix some arbitrary text with the namespace or interwiki prefix
	 * of this object
	 *
	 * @param string $name The text
	 * @return string The prefixed text
	 */
	private function prefix( $name ) {
		$p = '';
		if ( $this->isExternal() ) {
			$p = $this->mInterwiki . ':';
		}

		if ( 0 != $this->mNamespace ) {
			$p .= $this->getNsText() . ':';
		}
		return $p . $name;
	}

	/**
	 * Get the prefixed database key form
	 *
	 * @return string The prefixed title, with underscores and
	 *  any interwiki and namespace prefixes
	 */
	public function getPrefixedDBkey() {
		$s = $this->prefix( $this->mDbkeyform );
		$s = strtr( $s, ' ', '_' );
		return $s;
	}

	/**
	 * Get the prefixed title with spaces.
	 * This is the form usually used for display
	 *
	 * @return string The prefixed title, with spaces
	 */
	public function getPrefixedText() {
		if ( $this->mPrefixedText === null ) {
			$s = $this->prefix( $this->mTextform );
			$s = strtr( $s, '_', ' ' );
			$this->mPrefixedText = $s;
		}
		return $this->mPrefixedText;
	}

	/**
	 * Return a string representation of this title
	 *
	 * @return string Representation of this title
	 */
	public function __toString() {
		return $this->getPrefixedText();
	}

	/**
	 * Get the prefixed title with spaces, plus any fragment
	 * (part beginning with '#')
	 *
	 * @return string The prefixed title, with spaces and the fragment, including '#'
	 */
	public function getFullText() {
		$text = $this->getPrefixedText();
		if ( $this->hasFragment() ) {
			$text .= '#' . $this->getFragment();
		}
		return $text;
	}

	/**
	 * Get the root page name text without a namespace, i.e. the leftmost part before any slashes
	 *
	 * @par Example:
	 * @code
	 * Title::newFromText('User:Foo/Bar/Baz')->getRootText();
	 * # returns: 'Foo'
	 * @endcode
	 *
	 * @return string Root name
	 * @since 1.20
	 */
	public function getRootText() {
		if ( !MWNamespace::hasSubpages( $this->mNamespace ) ) {
			return $this->getText();
		}

		return strtok( $this->getText(), '/' );
	}

	/**
	 * Get the root page name title, i.e. the leftmost part before any slashes
	 *
	 * @par Example:
	 * @code
	 * Title::newFromText('User:Foo/Bar/Baz')->getRootTitle();
	 * # returns: Title{User:Foo}
	 * @endcode
	 *
	 * @return Title Root title
	 * @since 1.20
	 */
	public function getRootTitle() {
		return Title::makeTitle( $this->getNamespace(), $this->getRootText() );
	}

	/**
	 * Get the base page name without a namespace, i.e. the part before the subpage name
	 *
	 * @par Example:
	 * @code
	 * Title::newFromText('User:Foo/Bar/Baz')->getBaseText();
	 * # returns: 'Foo/Bar'
	 * @endcode
	 *
	 * @return string Base name
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
	 * Get the base page name title, i.e. the part before the subpage name
	 *
	 * @par Example:
	 * @code
	 * Title::newFromText('User:Foo/Bar/Baz')->getBaseTitle();
	 * # returns: Title{User:Foo/Bar}
	 * @endcode
	 *
	 * @return Title Base title
	 * @since 1.20
	 */
	public function getBaseTitle() {
		return Title::makeTitle( $this->getNamespace(), $this->getBaseText() );
	}

	/**
	 * Get the lowest-level subpage name, i.e. the rightmost part after any slashes
	 *
	 * @par Example:
	 * @code
	 * Title::newFromText('User:Foo/Bar/Baz')->getSubpageText();
	 * # returns: "Baz"
	 * @endcode
	 *
	 * @return string Subpage name
	 */
	public function getSubpageText() {
		if ( !MWNamespace::hasSubpages( $this->mNamespace ) ) {
			return $this->mTextform;
		}
		$parts = explode( '/', $this->mTextform );
		return $parts[count( $parts ) - 1];
	}

	/**
	 * Get the title for a subpage of the current page
	 *
	 * @par Example:
	 * @code
	 * Title::newFromText('User:Foo/Bar/Baz')->getSubpage("Asdf");
	 * # returns: Title{User:Foo/Bar/Baz/Asdf}
	 * @endcode
	 *
	 * @param string $text The subpage name to add to the title
	 * @return Title Subpage title
	 * @since 1.20
	 */
	public function getSubpage( $text ) {
		return Title::makeTitleSafe( $this->getNamespace(), $this->getText() . '/' . $text );
	}

	/**
	 * Get a URL-encoded form of the subpage text
	 *
	 * @return string URL-encoded subpage name
	 */
	public function getSubpageUrlForm() {
		$text = $this->getSubpageText();
		$text = wfUrlencode( strtr( $text, ' ', '_' ) );
		return $text;
	}

	/**
	 * Get a URL-encoded title (not an actual URL) including interwiki
	 *
	 * @return string The URL-encoded form
	 */
	public function getPrefixedURL() {
		$s = $this->prefix( $this->mDbkeyform );
		$s = wfUrlencode( strtr( $s, ' ', '_' ) );
		return $s;
	}

	/**
	 * Helper to fix up the get{Canonical,Full,Link,Local,Internal}URL args
	 * get{Canonical,Full,Link,Local,Internal}URL methods accepted an optional
	 * second argument named variant. This was deprecated in favor
	 * of passing an array of option with a "variant" key
	 * Once $query2 is removed for good, this helper can be dropped
	 * and the wfArrayToCgi moved to getLocalURL();
	 *
	 * @since 1.19 (r105919)
	 * @param array|string $query
	 * @param bool $query2
	 * @return string
	 */
	private static function fixUrlQueryArgs( $query, $query2 = false ) {
		if ( $query2 !== false ) {
			wfDeprecated( "Title::get{Canonical,Full,Link,Local,Internal}URL " .
				"method called with a second parameter is deprecated. Add your " .
				"parameter to an array passed as the first parameter.", "1.19" );
		}
		if ( is_array( $query ) ) {
			$query = wfArrayToCgi( $query );
		}
		if ( $query2 ) {
			if ( is_string( $query2 ) ) {
				// $query2 is a string, we will consider this to be
				// a deprecated $variant argument and add it to the query
				$query2 = wfArrayToCgi( [ 'variant' => $query2 ] );
			} else {
				$query2 = wfArrayToCgi( $query2 );
			}
			// If we have $query content add a & to it first
			if ( $query ) {
				$query .= '&';
			}
			// Now append the queries together
			$query .= $query2;
		}
		return $query;
	}

	/**
	 * Get a real URL referring to this title, with interwiki link and
	 * fragment
	 *
	 * @see self::getLocalURL for the arguments.
	 * @see wfExpandUrl
	 * @param array|string $query
	 * @param bool $query2
	 * @param string $proto Protocol type to use in URL
	 * @return string The URL
	 */
	public function getFullURL( $query = '', $query2 = false, $proto = PROTO_RELATIVE ) {
		$query = self::fixUrlQueryArgs( $query, $query2 );

		# Hand off all the decisions on urls to getLocalURL
		$url = $this->getLocalURL( $query );

		# Expand the url to make it a full url. Note that getLocalURL has the
		# potential to output full urls for a variety of reasons, so we use
		# wfExpandUrl instead of simply prepending $wgServer
		$url = wfExpandUrl( $url, $proto );

		# Finally, add the fragment.
		$url .= $this->getFragmentForURL();

		Hooks::run( 'GetFullURL', [ &$this, &$url, $query ] );
		return $url;
	}

	/**
	 * Get a URL with no fragment or server name (relative URL) from a Title object.
	 * If this page is generated with action=render, however,
	 * $wgServer is prepended to make an absolute URL.
	 *
	 * @see self::getFullURL to always get an absolute URL.
	 * @see self::getLinkURL to always get a URL that's the simplest URL that will be
	 *  valid to link, locally, to the current Title.
	 * @see self::newFromText to produce a Title object.
	 *
	 * @param string|array $query An optional query string,
	 *   not used for interwiki links. Can be specified as an associative array as well,
	 *   e.g., array( 'action' => 'edit' ) (keys and values will be URL-escaped).
	 *   Some query patterns will trigger various shorturl path replacements.
	 * @param array $query2 An optional secondary query array. This one MUST
	 *   be an array. If a string is passed it will be interpreted as a deprecated
	 *   variant argument and urlencoded into a variant= argument.
	 *   This second query argument will be added to the $query
	 *   The second parameter is deprecated since 1.19. Pass it as a key,value
	 *   pair in the first parameter array instead.
	 *
	 * @return string String of the URL.
	 */
	public function getLocalURL( $query = '', $query2 = false ) {
		global $wgArticlePath, $wgScript, $wgServer, $wgRequest;

		$query = self::fixUrlQueryArgs( $query, $query2 );

		$interwiki = self::getInterwikiLookup()->fetch( $this->mInterwiki );
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
				$url = str_replace( '$1', $dbkey, $wgArticlePath );
				Hooks::run( 'GetLocalURL::Article', [ &$this, &$url ] );
			} else {
				global $wgVariantArticlePath, $wgActionPaths, $wgContLang;
				$url = false;
				$matches = [];

				if ( !empty( $wgActionPaths )
					&& preg_match( '/^(.*&|)action=([^&]*)(&(.*)|)$/', $query, $matches )
				) {
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

				if ( $url === false
					&& $wgVariantArticlePath
					&& preg_match( '/^variant=([^&]*)$/', $query, $matches )
					&& $this->getPageLanguage()->equals( $wgContLang )
					&& $this->getPageLanguage()->hasVariants()
				) {
					$variant = urldecode( $matches[1] );
					if ( $this->getPageLanguage()->hasVariant( $variant ) ) {
						// Only do the variant replacement if the given variant is a valid
						// variant for the page's language.
						$url = str_replace( '$2', urlencode( $variant ), $wgVariantArticlePath );
						$url = str_replace( '$1', $dbkey, $url );
					}
				}

				if ( $url === false ) {
					if ( $query == '-' ) {
						$query = '';
					}
					$url = "{$wgScript}?title={$dbkey}&{$query}";
				}
			}

			Hooks::run( 'GetLocalURL::Internal', [ &$this, &$url, $query ] );

			// @todo FIXME: This causes breakage in various places when we
			// actually expected a local URL and end up with dupe prefixes.
			if ( $wgRequest->getVal( 'action' ) == 'render' ) {
				$url = $wgServer . $url;
			}
		}
		Hooks::run( 'GetLocalURL', [ &$this, &$url, $query ] );
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
	 * @param array $query
	 * @param bool $query2
	 * @param string|int|bool $proto A PROTO_* constant on how the URL should be expanded,
	 *                               or false (default) for no expansion
	 * @see self::getLocalURL for the arguments.
	 * @return string The URL
	 */
	public function getLinkURL( $query = '', $query2 = false, $proto = false ) {
		if ( $this->isExternal() || $proto !== false ) {
			$ret = $this->getFullURL( $query, $query2, $proto );
		} elseif ( $this->getPrefixedText() === '' && $this->hasFragment() ) {
			$ret = $this->getFragmentForURL();
		} else {
			$ret = $this->getLocalURL( $query, $query2 ) . $this->getFragmentForURL();
		}
		return $ret;
	}

	/**
	 * Get the URL form for an internal link.
	 * - Used in various CDN-related code, in case we have a different
	 * internal hostname for the server from the exposed one.
	 *
	 * This uses $wgInternalServer to qualify the path, or $wgServer
	 * if $wgInternalServer is not set. If the server variable used is
	 * protocol-relative, the URL will be expanded to http://
	 *
	 * @see self::getLocalURL for the arguments.
	 * @return string The URL
	 */
	public function getInternalURL( $query = '', $query2 = false ) {
		global $wgInternalServer, $wgServer;
		$query = self::fixUrlQueryArgs( $query, $query2 );
		$server = $wgInternalServer !== false ? $wgInternalServer : $wgServer;
		$url = wfExpandUrl( $server . $this->getLocalURL( $query ), PROTO_HTTP );
		Hooks::run( 'GetInternalURL', [ &$this, &$url, $query ] );
		return $url;
	}

	/**
	 * Get the URL for a canonical link, for use in things like IRC and
	 * e-mail notifications. Uses $wgCanonicalServer and the
	 * GetCanonicalURL hook.
	 *
	 * NOTE: Unlike getInternalURL(), the canonical URL includes the fragment
	 *
	 * @see self::getLocalURL for the arguments.
	 * @return string The URL
	 * @since 1.18
	 */
	public function getCanonicalURL( $query = '', $query2 = false ) {
		$query = self::fixUrlQueryArgs( $query, $query2 );
		$url = wfExpandUrl( $this->getLocalURL( $query ) . $this->getFragmentForURL(), PROTO_CANONICAL );
		Hooks::run( 'GetCanonicalURL', [ &$this, &$url, $query ] );
		return $url;
	}

	/**
	 * Get the edit URL for this Title
	 *
	 * @return string The URL, or a null string if this is an interwiki link
	 */
	public function getEditURL() {
		if ( $this->isExternal() ) {
			return '';
		}
		$s = $this->getLocalURL( 'action=edit' );

		return $s;
	}

	/**
	 * Can $user perform $action on this page?
	 * This skips potentially expensive cascading permission checks
	 * as well as avoids expensive error formatting
	 *
	 * Suitable for use for nonessential UI controls in common cases, but
	 * _not_ for functional access control.
	 *
	 * May provide false positives, but should never provide a false negative.
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check (since 1.19); $wgUser will be used if not provided.
	 * @return bool
	 */
	public function quickUserCan( $action, $user = null ) {
		return $this->userCan( $action, $user, false );
	}

	/**
	 * Can $user perform $action on this page?
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check (since 1.19); $wgUser will be used if not
	 *   provided.
	 * @param string $rigor Same format as Title::getUserPermissionsErrors()
	 * @return bool
	 */
	public function userCan( $action, $user = null, $rigor = 'secure' ) {
		if ( !$user instanceof User ) {
			global $wgUser;
			$user = $wgUser;
		}

		return !count( $this->getUserPermissionsErrorsInternal( $action, $user, $rigor, true ) );
	}

	/**
	 * Can $user perform $action on this page?
	 *
	 * @todo FIXME: This *does not* check throttles (User::pingLimiter()).
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check
	 * @param string $rigor One of (quick,full,secure)
	 *   - quick  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - full   : does cheap and expensive checks possibly from a replica DB
	 *   - secure : does cheap and expensive checks, using the master as needed
	 * @param array $ignoreErrors Array of Strings Set this to a list of message keys
	 *   whose corresponding errors may be ignored.
	 * @return array Array of arrays of the arguments to wfMessage to explain permissions problems.
	 */
	public function getUserPermissionsErrors(
		$action, $user, $rigor = 'secure', $ignoreErrors = []
	) {
		$errors = $this->getUserPermissionsErrorsInternal( $action, $user, $rigor );

		// Remove the errors being ignored.
		foreach ( $errors as $index => $error ) {
			$errKey = is_array( $error ) ? $error[0] : $error;

			if ( in_array( $errKey, $ignoreErrors ) ) {
				unset( $errors[$index] );
			}
			if ( $errKey instanceof MessageSpecifier && in_array( $errKey->getKey(), $ignoreErrors ) ) {
				unset( $errors[$index] );
			}
		}

		return $errors;
	}

	/**
	 * Permissions checks that fail most often, and which are easiest to test.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as Title::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	private function checkQuickPermissions( $action, $user, $errors, $rigor, $short ) {
		if ( !Hooks::run( 'TitleQuickPermissions',
			[ $this, $user, $action, &$errors, ( $rigor !== 'quick' ), $short ] )
		) {
			return $errors;
		}

		if ( $action == 'create' ) {
			if (
				( $this->isTalkPage() && !$user->isAllowed( 'createtalk' ) ) ||
				( !$this->isTalkPage() && !$user->isAllowed( 'createpage' ) )
			) {
				$errors[] = $user->isAnon() ? [ 'nocreatetext' ] : [ 'nocreate-loggedin' ];
			}
		} elseif ( $action == 'move' ) {
			if ( !$user->isAllowed( 'move-rootuserpages' )
					&& $this->mNamespace == NS_USER && !$this->isSubpage() ) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = [ 'cant-move-user-page' ];
			}

			// Check if user is allowed to move files if it's a file
			if ( $this->mNamespace == NS_FILE && !$user->isAllowed( 'movefile' ) ) {
				$errors[] = [ 'movenotallowedfile' ];
			}

			// Check if user is allowed to move category pages if it's a category page
			if ( $this->mNamespace == NS_CATEGORY && !$user->isAllowed( 'move-categorypages' ) ) {
				$errors[] = [ 'cant-move-category-page' ];
			}

			if ( !$user->isAllowed( 'move' ) ) {
				// User can't move anything
				$userCanMove = User::groupHasPermission( 'user', 'move' );
				$autoconfirmedCanMove = User::groupHasPermission( 'autoconfirmed', 'move' );
				if ( $user->isAnon() && ( $userCanMove || $autoconfirmedCanMove ) ) {
					// custom message if logged-in users without any special rights can move
					$errors[] = [ 'movenologintext' ];
				} else {
					$errors[] = [ 'movenotallowed' ];
				}
			}
		} elseif ( $action == 'move-target' ) {
			if ( !$user->isAllowed( 'move' ) ) {
				// User can't move anything
				$errors[] = [ 'movenotallowed' ];
			} elseif ( !$user->isAllowed( 'move-rootuserpages' )
					&& $this->mNamespace == NS_USER && !$this->isSubpage() ) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = [ 'cant-move-to-user-page' ];
			} elseif ( !$user->isAllowed( 'move-categorypages' )
					&& $this->mNamespace == NS_CATEGORY ) {
				// Show category page-specific message only if the user can move other pages
				$errors[] = [ 'cant-move-to-category-page' ];
			}
		} elseif ( !$user->isAllowed( $action ) ) {
			$errors[] = $this->missingPermissionError( $action, $short );
		}

		return $errors;
	}

	/**
	 * Add the resulting error code to the errors array
	 *
	 * @param array $errors List of current errors
	 * @param array $result Result of errors
	 *
	 * @return array List of errors
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
			$errors[] = [ $result ];
		} elseif ( $result instanceof MessageSpecifier ) {
			// A message specifier representing an error
			$errors[] = [ $result ];
		} elseif ( $result === false ) {
			// a generic "We don't want them to do that"
			$errors[] = [ 'badaccess-group0' ];
		}
		return $errors;
	}

	/**
	 * Check various permission hooks
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as Title::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	private function checkPermissionHooks( $action, $user, $errors, $rigor, $short ) {
		// Use getUserPermissionsErrors instead
		$result = '';
		if ( !Hooks::run( 'userCan', [ &$this, &$user, $action, &$result ] ) ) {
			return $result ? [] : [ [ 'badaccess-group0' ] ];
		}
		// Check getUserPermissionsErrors hook
		if ( !Hooks::run( 'getUserPermissionsErrors', [ &$this, &$user, $action, &$result ] ) ) {
			$errors = $this->resultToError( $errors, $result );
		}
		// Check getUserPermissionsErrorsExpensive hook
		if (
			$rigor !== 'quick'
			&& !( $short && count( $errors ) > 0 )
			&& !Hooks::run( 'getUserPermissionsErrorsExpensive', [ &$this, &$user, $action, &$result ] )
		) {
			$errors = $this->resultToError( $errors, $result );
		}

		return $errors;
	}

	/**
	 * Check permissions on special pages & namespaces
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as Title::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	private function checkSpecialsAndNSPermissions( $action, $user, $errors, $rigor, $short ) {
		# Only 'createaccount' can be performed on special pages,
		# which don't actually exist in the DB.
		if ( NS_SPECIAL == $this->mNamespace && $action !== 'createaccount' ) {
			$errors[] = [ 'ns-specialprotected' ];
		}

		# Check $wgNamespaceProtection for restricted namespaces
		if ( $this->isNamespaceProtected( $user ) ) {
			$ns = $this->mNamespace == NS_MAIN ?
				wfMessage( 'nstab-main' )->text() : $this->getNsText();
			$errors[] = $this->mNamespace == NS_MEDIAWIKI ?
				[ 'protectedinterface', $action ] : [ 'namespaceprotected', $ns, $action ];
		}

		return $errors;
	}

	/**
	 * Check CSS/JS sub-page permissions
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as Title::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	private function checkCSSandJSPermissions( $action, $user, $errors, $rigor, $short ) {
		# Protect css/js subpages of user pages
		# XXX: this might be better using restrictions
		# XXX: right 'editusercssjs' is deprecated, for backward compatibility only
		if ( $action != 'patrol' && !$user->isAllowed( 'editusercssjs' ) ) {
			if ( preg_match( '/^' . preg_quote( $user->getName(), '/' ) . '\//', $this->mTextform ) ) {
				if ( $this->isCssSubpage() && !$user->isAllowedAny( 'editmyusercss', 'editusercss' ) ) {
					$errors[] = [ 'mycustomcssprotected', $action ];
				} elseif ( $this->isJsSubpage() && !$user->isAllowedAny( 'editmyuserjs', 'edituserjs' ) ) {
					$errors[] = [ 'mycustomjsprotected', $action ];
				}
			} else {
				if ( $this->isCssSubpage() && !$user->isAllowed( 'editusercss' ) ) {
					$errors[] = [ 'customcssprotected', $action ];
				} elseif ( $this->isJsSubpage() && !$user->isAllowed( 'edituserjs' ) ) {
					$errors[] = [ 'customjsprotected', $action ];
				}
			}
		}

		return $errors;
	}

	/**
	 * Check against page_restrictions table requirements on this
	 * page. The user must possess all required rights for this
	 * action.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as Title::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	private function checkPageRestrictions( $action, $user, $errors, $rigor, $short ) {
		foreach ( $this->getRestrictions( $action ) as $right ) {
			// Backwards compatibility, rewrite sysop -> editprotected
			if ( $right == 'sysop' ) {
				$right = 'editprotected';
			}
			// Backwards compatibility, rewrite autoconfirmed -> editsemiprotected
			if ( $right == 'autoconfirmed' ) {
				$right = 'editsemiprotected';
			}
			if ( $right == '' ) {
				continue;
			}
			if ( !$user->isAllowed( $right ) ) {
				$errors[] = [ 'protectedpagetext', $right, $action ];
			} elseif ( $this->mCascadeRestriction && !$user->isAllowed( 'protect' ) ) {
				$errors[] = [ 'protectedpagetext', 'protect', $action ];
			}
		}

		return $errors;
	}

	/**
	 * Check restrictions on cascading pages.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as Title::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	private function checkCascadingSourcesRestrictions( $action, $user, $errors, $rigor, $short ) {
		if ( $rigor !== 'quick' && !$this->isCssJsSubpage() ) {
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
					// Backwards compatibility, rewrite sysop -> editprotected
					if ( $right == 'sysop' ) {
						$right = 'editprotected';
					}
					// Backwards compatibility, rewrite autoconfirmed -> editsemiprotected
					if ( $right == 'autoconfirmed' ) {
						$right = 'editsemiprotected';
					}
					if ( $right != '' && !$user->isAllowedAll( 'protect', $right ) ) {
						$pages = '';
						foreach ( $cascadingSources as $page ) {
							$pages .= '* [[:' . $page->getPrefixedText() . "]]\n";
						}
						$errors[] = [ 'cascadeprotected', count( $cascadingSources ), $pages, $action ];
					}
				}
			}
		}

		return $errors;
	}

	/**
	 * Check action permissions not already checked in checkQuickPermissions
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as Title::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	private function checkActionPermissions( $action, $user, $errors, $rigor, $short ) {
		global $wgDeleteRevisionsLimit, $wgLang;

		if ( $action == 'protect' ) {
			if ( count( $this->getUserPermissionsErrorsInternal( 'edit', $user, $rigor, true ) ) ) {
				// If they can't edit, they shouldn't protect.
				$errors[] = [ 'protect-cantedit' ];
			}
		} elseif ( $action == 'create' ) {
			$title_protection = $this->getTitleProtection();
			if ( $title_protection ) {
				if ( $title_protection['permission'] == ''
					|| !$user->isAllowed( $title_protection['permission'] )
				) {
					$errors[] = [
						'titleprotected',
						User::whoIs( $title_protection['user'] ),
						$title_protection['reason']
					];
				}
			}
		} elseif ( $action == 'move' ) {
			// Check for immobile pages
			if ( !MWNamespace::isMovable( $this->mNamespace ) ) {
				// Specific message for this case
				$errors[] = [ 'immobile-source-namespace', $this->getNsText() ];
			} elseif ( !$this->isMovable() ) {
				// Less specific message for rarer cases
				$errors[] = [ 'immobile-source-page' ];
			}
		} elseif ( $action == 'move-target' ) {
			if ( !MWNamespace::isMovable( $this->mNamespace ) ) {
				$errors[] = [ 'immobile-target-namespace', $this->getNsText() ];
			} elseif ( !$this->isMovable() ) {
				$errors[] = [ 'immobile-target-page' ];
			}
		} elseif ( $action == 'delete' ) {
			$tempErrors = $this->checkPageRestrictions( 'edit', $user, [], $rigor, true );
			if ( !$tempErrors ) {
				$tempErrors = $this->checkCascadingSourcesRestrictions( 'edit',
					$user, $tempErrors, $rigor, true );
			}
			if ( $tempErrors ) {
				// If protection keeps them from editing, they shouldn't be able to delete.
				$errors[] = [ 'deleteprotected' ];
			}
			if ( $rigor !== 'quick' && $wgDeleteRevisionsLimit
				&& !$this->userCan( 'bigdelete', $user ) && $this->isBigDeletion()
			) {
				$errors[] = [ 'delete-toobig', $wgLang->formatNum( $wgDeleteRevisionsLimit ) ];
			}
		}
		return $errors;
	}

	/**
	 * Check that the user isn't blocked from editing.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as Title::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	private function checkUserBlock( $action, $user, $errors, $rigor, $short ) {
		global $wgEmailConfirmToEdit, $wgBlockDisablesLogin;
		// Account creation blocks handled at userlogin.
		// Unblocking handled in SpecialUnblock
		if ( $rigor === 'quick' || in_array( $action, [ 'createaccount', 'unblock' ] ) ) {
			return $errors;
		}

		// Optimize for a very common case
		if ( $action === 'read' && !$wgBlockDisablesLogin ) {
			return $errors;
		}

		if ( $wgEmailConfirmToEdit && !$user->isEmailConfirmed() ) {
			$errors[] = [ 'confirmedittext' ];
		}

		$useSlave = ( $rigor !== 'secure' );
		if ( ( $action == 'edit' || $action == 'create' )
			&& !$user->isBlockedFrom( $this, $useSlave )
		) {
			// Don't block the user from editing their own talk page unless they've been
			// explicitly blocked from that too.
		} elseif ( $user->isBlocked() && $user->getBlock()->prevents( $action ) !== false ) {
			// @todo FIXME: Pass the relevant context into this function.
			$errors[] = $user->getBlock()->getPermissionsError( RequestContext::getMain() );
		}

		return $errors;
	}

	/**
	 * Check that the user is allowed to read this page.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as Title::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	private function checkReadPermissions( $action, $user, $errors, $rigor, $short ) {
		global $wgWhitelistRead, $wgWhitelistReadRegexp;

		$whitelisted = false;
		if ( User::isEveryoneAllowed( 'read' ) ) {
			# Shortcut for public wikis, allows skipping quite a bit of code
			$whitelisted = true;
		} elseif ( $user->isAllowed( 'read' ) ) {
			# If the user is allowed to read pages, he is allowed to read all pages
			$whitelisted = true;
		} elseif ( $this->isSpecial( 'Userlogin' )
			|| $this->isSpecial( 'PasswordReset' )
			|| $this->isSpecial( 'Userlogout' )
		) {
			# Always grant access to the login page.
			# Even anons need to be able to log in.
			$whitelisted = true;
		} elseif ( is_array( $wgWhitelistRead ) && count( $wgWhitelistRead ) ) {
			# Time to check the whitelist
			# Only do these checks is there's something to check against
			$name = $this->getPrefixedText();
			$dbName = $this->getPrefixedDBkey();

			// Check for explicit whitelisting with and without underscores
			if ( in_array( $name, $wgWhitelistRead, true ) || in_array( $dbName, $wgWhitelistRead, true ) ) {
				$whitelisted = true;
			} elseif ( $this->getNamespace() == NS_MAIN ) {
				# Old settings might have the title prefixed with
				# a colon for main-namespace pages
				if ( in_array( ':' . $name, $wgWhitelistRead ) ) {
					$whitelisted = true;
				}
			} elseif ( $this->isSpecialPage() ) {
				# If it's a special page, ditch the subpage bit and check again
				$name = $this->getDBkey();
				list( $name, /* $subpage */ ) = SpecialPageFactory::resolveAlias( $name );
				if ( $name ) {
					$pure = SpecialPage::getTitleFor( $name )->getPrefixedText();
					if ( in_array( $pure, $wgWhitelistRead, true ) ) {
						$whitelisted = true;
					}
				}
			}
		}

		if ( !$whitelisted && is_array( $wgWhitelistReadRegexp ) && !empty( $wgWhitelistReadRegexp ) ) {
			$name = $this->getPrefixedText();
			// Check for regex whitelisting
			foreach ( $wgWhitelistReadRegexp as $listItem ) {
				if ( preg_match( $listItem, $name ) ) {
					$whitelisted = true;
					break;
				}
			}
		}

		if ( !$whitelisted ) {
			# If the title is not whitelisted, give extensions a chance to do so...
			Hooks::run( 'TitleReadWhitelist', [ $this, $user, &$whitelisted ] );
			if ( !$whitelisted ) {
				$errors[] = $this->missingPermissionError( $action, $short );
			}
		}

		return $errors;
	}

	/**
	 * Get a description array when the user doesn't have the right to perform
	 * $action (i.e. when User::isAllowed() returns false)
	 *
	 * @param string $action The action to check
	 * @param bool $short Short circuit on first error
	 * @return array List of errors
	 */
	private function missingPermissionError( $action, $short ) {
		// We avoid expensive display logic for quickUserCan's and such
		if ( $short ) {
			return [ 'badaccess-group0' ];
		}

		$groups = array_map( [ 'User', 'makeGroupLinkWiki' ],
			User::getGroupsWithPermission( $action ) );

		if ( count( $groups ) ) {
			global $wgLang;
			return [
				'badaccess-groups',
				$wgLang->commaList( $groups ),
				count( $groups )
			];
		} else {
			return [ 'badaccess-group0' ];
		}
	}

	/**
	 * Can $user perform $action on this page? This is an internal function,
	 * with multiple levels of checks depending on performance needs; see $rigor below.
	 * It does not check wfReadOnly().
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check
	 * @param string $rigor One of (quick,full,secure)
	 *   - quick  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - full   : does cheap and expensive checks possibly from a replica DB
	 *   - secure : does cheap and expensive checks, using the master as needed
	 * @param bool $short Set this to true to stop after the first permission error.
	 * @return array Array of arrays of the arguments to wfMessage to explain permissions problems.
	 */
	protected function getUserPermissionsErrorsInternal(
		$action, $user, $rigor = 'secure', $short = false
	) {
		if ( $rigor === true ) {
			$rigor = 'secure'; // b/c
		} elseif ( $rigor === false ) {
			$rigor = 'quick'; // b/c
		} elseif ( !in_array( $rigor, [ 'quick', 'full', 'secure' ] ) ) {
			throw new Exception( "Invalid rigor parameter '$rigor'." );
		}

		# Read has special handling
		if ( $action == 'read' ) {
			$checks = [
				'checkPermissionHooks',
				'checkReadPermissions',
				'checkUserBlock', // for wgBlockDisablesLogin
			];
		# Don't call checkSpecialsAndNSPermissions or checkCSSandJSPermissions
		# here as it will lead to duplicate error messages. This is okay to do
		# since anywhere that checks for create will also check for edit, and
		# those checks are called for edit.
		} elseif ( $action == 'create' ) {
			$checks = [
				'checkQuickPermissions',
				'checkPermissionHooks',
				'checkPageRestrictions',
				'checkCascadingSourcesRestrictions',
				'checkActionPermissions',
				'checkUserBlock'
			];
		} else {
			$checks = [
				'checkQuickPermissions',
				'checkPermissionHooks',
				'checkSpecialsAndNSPermissions',
				'checkCSSandJSPermissions',
				'checkPageRestrictions',
				'checkCascadingSourcesRestrictions',
				'checkActionPermissions',
				'checkUserBlock'
			];
		}

		$errors = [];
		while ( count( $checks ) > 0 &&
				!( $short && count( $errors ) > 0 ) ) {
			$method = array_shift( $checks );
			$errors = $this->$method( $action, $user, $errors, $rigor, $short );
		}

		return $errors;
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
			$types = array_diff( $types, [ 'create' ] );
		} else {
			# Only the create and upload restrictions apply to non-existing titles
			$types = array_intersect( $types, [ 'create', 'upload' ] );
		}
		return $types;
	}

	/**
	 * Returns restriction types for the current Title
	 *
	 * @return array Applicable restriction types
	 */
	public function getRestrictionTypes() {
		if ( $this->isSpecialPage() ) {
			return [];
		}

		$types = self::getFilteredRestrictionTypes( $this->exists() );

		if ( $this->getNamespace() != NS_FILE ) {
			# Remove the upload restriction for non-file titles
			$types = array_diff( $types, [ 'upload' ] );
		}

		Hooks::run( 'TitleGetRestrictionTypes', [ $this, &$types ] );

		wfDebug( __METHOD__ . ': applicable restrictions to [[' .
			$this->getPrefixedText() . ']] are {' . implode( ',', $types ) . "}\n" );

		return $types;
	}

	/**
	 * Is this title subject to title protection?
	 * Title protection is the one applied against creation of such title.
	 *
	 * @return array|bool An associative array representing any existent title
	 *   protection, or false if there's none.
	 */
	public function getTitleProtection() {
		// Can't protect pages in special namespaces
		if ( $this->getNamespace() < 0 ) {
			return false;
		}

		// Can't protect pages that exist.
		if ( $this->exists() ) {
			return false;
		}

		if ( $this->mTitleProtection === null ) {
			$dbr = wfGetDB( DB_REPLICA );
			$res = $dbr->select(
				'protected_titles',
				[
					'user' => 'pt_user',
					'reason' => 'pt_reason',
					'expiry' => 'pt_expiry',
					'permission' => 'pt_create_perm'
				],
				[ 'pt_namespace' => $this->getNamespace(), 'pt_title' => $this->getDBkey() ],
				__METHOD__
			);

			// fetchRow returns false if there are no rows.
			$row = $dbr->fetchRow( $res );
			if ( $row ) {
				if ( $row['permission'] == 'sysop' ) {
					$row['permission'] = 'editprotected'; // B/C
				}
				if ( $row['permission'] == 'autoconfirmed' ) {
					$row['permission'] = 'editsemiprotected'; // B/C
				}
				$row['expiry'] = $dbr->decodeExpiry( $row['expiry'] );
			}
			$this->mTitleProtection = $row;
		}
		return $this->mTitleProtection;
	}

	/**
	 * Remove any title protection due to page existing
	 */
	public function deleteTitleProtection() {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->delete(
			'protected_titles',
			[ 'pt_namespace' => $this->getNamespace(), 'pt_title' => $this->getDBkey() ],
			__METHOD__
		);
		$this->mTitleProtection = false;
	}

	/**
	 * Is this page "semi-protected" - the *only* protection levels are listed
	 * in $wgSemiprotectedRestrictionLevels?
	 *
	 * @param string $action Action to check (default: edit)
	 * @return bool
	 */
	public function isSemiProtected( $action = 'edit' ) {
		global $wgSemiprotectedRestrictionLevels;

		$restrictions = $this->getRestrictions( $action );
		$semi = $wgSemiprotectedRestrictionLevels;
		if ( !$restrictions || !$semi ) {
			// Not protected, or all protection is full protection
			return false;
		}

		// Remap autoconfirmed to editsemiprotected for BC
		foreach ( array_keys( $semi, 'autoconfirmed' ) as $key ) {
			$semi[$key] = 'editsemiprotected';
		}
		foreach ( array_keys( $restrictions, 'autoconfirmed' ) as $key ) {
			$restrictions[$key] = 'editsemiprotected';
		}

		return !array_diff( $restrictions, $semi );
	}

	/**
	 * Does the title correspond to a protected article?
	 *
	 * @param string $action The action the page is protected from,
	 * by default checks all actions.
	 * @return bool
	 */
	public function isProtected( $action = '' ) {
		global $wgRestrictionLevels;

		$restrictionTypes = $this->getRestrictionTypes();

		# Special pages have inherent protection
		if ( $this->isSpecialPage() ) {
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
	 * Determines if $user is unable to edit this page because it has been protected
	 * by $wgNamespaceProtection.
	 *
	 * @param User $user User object to check permissions
	 * @return bool
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
	 * Cascading protection: Return true if cascading restrictions apply to this page, false if not.
	 *
	 * @return bool If the page is subject to cascading restrictions.
	 */
	public function isCascadeProtected() {
		list( $sources, /* $restrictions */ ) = $this->getCascadeProtectionSources( false );
		return ( $sources > 0 );
	}

	/**
	 * Determines whether cascading protection sources have already been loaded from
	 * the database.
	 *
	 * @param bool $getPages True to check if the pages are loaded, or false to check
	 * if the status is loaded.
	 * @return bool Whether or not the specified information has been loaded
	 * @since 1.23
	 */
	public function areCascadeProtectionSourcesLoaded( $getPages = true ) {
		return $getPages ? $this->mCascadeSources !== null : $this->mHasCascadingRestrictions !== null;
	}

	/**
	 * Cascading protection: Get the source of any cascading restrictions on this page.
	 *
	 * @param bool $getPages Whether or not to retrieve the actual pages
	 *        that the restrictions have come from and the actual restrictions
	 *        themselves.
	 * @return array Two elements: First is an array of Title objects of the
	 *        pages from which cascading restrictions have come, false for
	 *        none, or true if such restrictions exist but $getPages was not
	 *        set. Second is an array like that returned by
	 *        Title::getAllRestrictions(), or an empty array if $getPages is
	 *        false.
	 */
	public function getCascadeProtectionSources( $getPages = true ) {
		$pagerestrictions = [];

		if ( $this->mCascadeSources !== null && $getPages ) {
			return [ $this->mCascadeSources, $this->mCascadingRestrictions ];
		} elseif ( $this->mHasCascadingRestrictions !== null && !$getPages ) {
			return [ $this->mHasCascadingRestrictions, $pagerestrictions ];
		}

		$dbr = wfGetDB( DB_REPLICA );

		if ( $this->getNamespace() == NS_FILE ) {
			$tables = [ 'imagelinks', 'page_restrictions' ];
			$where_clauses = [
				'il_to' => $this->getDBkey(),
				'il_from=pr_page',
				'pr_cascade' => 1
			];
		} else {
			$tables = [ 'templatelinks', 'page_restrictions' ];
			$where_clauses = [
				'tl_namespace' => $this->getNamespace(),
				'tl_title' => $this->getDBkey(),
				'tl_from=pr_page',
				'pr_cascade' => 1
			];
		}

		if ( $getPages ) {
			$cols = [ 'pr_page', 'page_namespace', 'page_title',
				'pr_expiry', 'pr_type', 'pr_level' ];
			$where_clauses[] = 'page_id=pr_page';
			$tables[] = 'page';
		} else {
			$cols = [ 'pr_expiry' ];
		}

		$res = $dbr->select( $tables, $cols, $where_clauses, __METHOD__ );

		$sources = $getPages ? [] : false;
		$now = wfTimestampNow();

		foreach ( $res as $row ) {
			$expiry = $dbr->decodeExpiry( $row->pr_expiry );
			if ( $expiry > $now ) {
				if ( $getPages ) {
					$page_id = $row->pr_page;
					$page_ns = $row->page_namespace;
					$page_title = $row->page_title;
					$sources[$page_id] = Title::makeTitle( $page_ns, $page_title );
					# Add groups needed for each restriction type if its not already there
					# Make sure this restriction type still exists

					if ( !isset( $pagerestrictions[$row->pr_type] ) ) {
						$pagerestrictions[$row->pr_type] = [];
					}

					if (
						isset( $pagerestrictions[$row->pr_type] )
						&& !in_array( $row->pr_level, $pagerestrictions[$row->pr_type] )
					) {
						$pagerestrictions[$row->pr_type][] = $row->pr_level;
					}
				} else {
					$sources = true;
				}
			}
		}

		if ( $getPages ) {
			$this->mCascadeSources = $sources;
			$this->mCascadingRestrictions = $pagerestrictions;
		} else {
			$this->mHasCascadingRestrictions = $sources;
		}

		return [ $sources, $pagerestrictions ];
	}

	/**
	 * Accessor for mRestrictionsLoaded
	 *
	 * @return bool Whether or not the page's restrictions have already been
	 * loaded from the database
	 * @since 1.23
	 */
	public function areRestrictionsLoaded() {
		return $this->mRestrictionsLoaded;
	}

	/**
	 * Accessor/initialisation for mRestrictions
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @return array Restriction levels needed to take the action. All levels are
	 *     required. Note that restriction levels are normally user rights, but 'sysop'
	 *     and 'autoconfirmed' are also allowed for backwards compatibility. These should
	 *     be mapped to 'editprotected' and 'editsemiprotected' respectively.
	 */
	public function getRestrictions( $action ) {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}
		return isset( $this->mRestrictions[$action] )
				? $this->mRestrictions[$action]
				: [];
	}

	/**
	 * Accessor/initialisation for mRestrictions
	 *
	 * @return array Keys are actions, values are arrays as returned by
	 *     Title::getRestrictions()
	 * @since 1.23
	 */
	public function getAllRestrictions() {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}
		return $this->mRestrictions;
	}

	/**
	 * Get the expiry time for the restriction against a given action
	 *
	 * @param string $action
	 * @return string|bool 14-char timestamp, or 'infinity' if the page is protected forever
	 *     or not protected at all, or false if the action is not recognised.
	 */
	public function getRestrictionExpiry( $action ) {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}
		return isset( $this->mRestrictionsExpiry[$action] ) ? $this->mRestrictionsExpiry[$action] : false;
	}

	/**
	 * Returns cascading restrictions for the current article
	 *
	 * @return bool
	 */
	function areRestrictionsCascading() {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}

		return $this->mCascadeRestriction;
	}

	/**
	 * Compiles list of active page restrictions from both page table (pre 1.10)
	 * and page_restrictions table for this existing page.
	 * Public for usage by LiquidThreads.
	 *
	 * @param array $rows Array of db result objects
	 * @param string $oldFashionedRestrictions Comma-separated list of page
	 *   restrictions from page table (pre 1.10)
	 */
	public function loadRestrictionsFromRows( $rows, $oldFashionedRestrictions = null ) {
		$dbr = wfGetDB( DB_REPLICA );

		$restrictionTypes = $this->getRestrictionTypes();

		foreach ( $restrictionTypes as $type ) {
			$this->mRestrictions[$type] = [];
			$this->mRestrictionsExpiry[$type] = 'infinity';
		}

		$this->mCascadeRestriction = false;

		# Backwards-compatibility: also load the restrictions from the page record (old format).
		if ( $oldFashionedRestrictions !== null ) {
			$this->mOldRestrictions = $oldFashionedRestrictions;
		}

		if ( $this->mOldRestrictions === false ) {
			$this->mOldRestrictions = $dbr->selectField( 'page', 'page_restrictions',
				[ 'page_id' => $this->getArticleID() ], __METHOD__ );
		}

		if ( $this->mOldRestrictions != '' ) {
			foreach ( explode( ':', trim( $this->mOldRestrictions ) ) as $restrict ) {
				$temp = explode( '=', trim( $restrict ) );
				if ( count( $temp ) == 1 ) {
					// old old format should be treated as edit/move restriction
					$this->mRestrictions['edit'] = explode( ',', trim( $temp[0] ) );
					$this->mRestrictions['move'] = explode( ',', trim( $temp[0] ) );
				} else {
					$restriction = trim( $temp[1] );
					if ( $restriction != '' ) { // some old entries are empty
						$this->mRestrictions[$temp[0]] = explode( ',', $restriction );
					}
				}
			}
		}

		if ( count( $rows ) ) {
			# Current system - load second to make them override.
			$now = wfTimestampNow();

			# Cycle through all the restrictions.
			foreach ( $rows as $row ) {

				// Don't take care of restrictions types that aren't allowed
				if ( !in_array( $row->pr_type, $restrictionTypes ) ) {
					continue;
				}

				// This code should be refactored, now that it's being used more generally,
				// But I don't really see any harm in leaving it in Block for now -werdna
				$expiry = $dbr->decodeExpiry( $row->pr_expiry );

				// Only apply the restrictions if they haven't expired!
				if ( !$expiry || $expiry > $now ) {
					$this->mRestrictionsExpiry[$row->pr_type] = $expiry;
					$this->mRestrictions[$row->pr_type] = explode( ',', trim( $row->pr_level ) );

					$this->mCascadeRestriction |= $row->pr_cascade;
				}
			}
		}

		$this->mRestrictionsLoaded = true;
	}

	/**
	 * Load restrictions from the page_restrictions table
	 *
	 * @param string $oldFashionedRestrictions Comma-separated list of page
	 *   restrictions from page table (pre 1.10)
	 */
	public function loadRestrictions( $oldFashionedRestrictions = null ) {
		if ( $this->mRestrictionsLoaded ) {
			return;
		}

		$id = $this->getArticleID();
		if ( $id ) {
			$cache = ObjectCache::getMainWANInstance();
			$rows = $cache->getWithSetCallback(
				// Page protections always leave a new null revision
				$cache->makeKey( 'page-restrictions', $id, $this->getLatestRevID() ),
				$cache::TTL_DAY,
				function ( $curValue, &$ttl, array &$setOpts ) {
					$dbr = wfGetDB( DB_REPLICA );

					$setOpts += Database::getCacheSetOptions( $dbr );

					return iterator_to_array(
						$dbr->select(
							'page_restrictions',
							[ 'pr_type', 'pr_expiry', 'pr_level', 'pr_cascade' ],
							[ 'pr_page' => $this->getArticleID() ],
							__METHOD__
						)
					);
				}
			);

			$this->loadRestrictionsFromRows( $rows, $oldFashionedRestrictions );
		} else {
			$title_protection = $this->getTitleProtection();

			if ( $title_protection ) {
				$now = wfTimestampNow();
				$expiry = wfGetDB( DB_REPLICA )->decodeExpiry( $title_protection['expiry'] );

				if ( !$expiry || $expiry > $now ) {
					// Apply the restrictions
					$this->mRestrictionsExpiry['create'] = $expiry;
					$this->mRestrictions['create'] =
						explode( ',', trim( $title_protection['permission'] ) );
				} else { // Get rid of the old restrictions
					$this->mTitleProtection = false;
				}
			} else {
				$this->mRestrictionsExpiry['create'] = 'infinity';
			}
			$this->mRestrictionsLoaded = true;
		}
	}

	/**
	 * Flush the protection cache in this object and force reload from the database.
	 * This is used when updating protection from WikiPage::doUpdateRestrictions().
	 */
	public function flushRestrictions() {
		$this->mRestrictionsLoaded = false;
		$this->mTitleProtection = null;
	}

	/**
	 * Purge expired restrictions from the page_restrictions table
	 *
	 * This will purge no more than $wgUpdateRowsPerQuery page_restrictions rows
	 */
	static function purgeExpiredRestrictions() {
		if ( wfReadOnly() ) {
			return;
		}

		DeferredUpdates::addUpdate( new AtomicSectionUpdate(
			wfGetDB( DB_MASTER ),
			__METHOD__,
			function ( IDatabase $dbw, $fname ) {
				$config = MediaWikiServices::getInstance()->getMainConfig();
				$ids = $dbw->selectFieldValues(
					'page_restrictions',
					'pr_id',
					[ 'pr_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ],
					$fname,
					[ 'LIMIT' => $config->get( 'UpdateRowsPerQuery' ) ] // T135470
				);
				if ( $ids ) {
					$dbw->delete( 'page_restrictions', [ 'pr_id' => $ids ], $fname );
				}
			}
		) );

		DeferredUpdates::addUpdate( new AtomicSectionUpdate(
			wfGetDB( DB_MASTER ),
			__METHOD__,
			function ( IDatabase $dbw, $fname ) {
				$dbw->delete(
					'protected_titles',
					[ 'pt_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ],
					$fname
				);
			}
		) );
	}

	/**
	 * Does this have subpages?  (Warning, usually requires an extra DB query.)
	 *
	 * @return bool
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
		if ( $this->mHasSubpages === null ) {
			$this->mHasSubpages = false;
			$subpages = $this->getSubpages( 1 );
			if ( $subpages instanceof TitleArray ) {
				$this->mHasSubpages = (bool)$subpages->count();
			}
		}

		return $this->mHasSubpages;
	}

	/**
	 * Get all subpages of this page.
	 *
	 * @param int $limit Maximum number of subpages to fetch; -1 for no limit
	 * @return TitleArray|array TitleArray, or empty array if this page's namespace
	 *  doesn't allow subpages
	 */
	public function getSubpages( $limit = -1 ) {
		if ( !MWNamespace::hasSubpages( $this->getNamespace() ) ) {
			return [];
		}

		$dbr = wfGetDB( DB_REPLICA );
		$conds['page_namespace'] = $this->getNamespace();
		$conds[] = 'page_title ' . $dbr->buildLike( $this->getDBkey() . '/', $dbr->anyString() );
		$options = [];
		if ( $limit > -1 ) {
			$options['LIMIT'] = $limit;
		}
		$this->mSubpages = TitleArray::newFromResult(
			$dbr->select( 'page',
				[ 'page_id', 'page_namespace', 'page_title', 'page_is_redirect' ],
				$conds,
				__METHOD__,
				$options
			)
		);
		return $this->mSubpages;
	}

	/**
	 * Is there a version of this page in the deletion archive?
	 *
	 * @return int The number of archived revisions
	 */
	public function isDeleted() {
		if ( $this->getNamespace() < 0 ) {
			$n = 0;
		} else {
			$dbr = wfGetDB( DB_REPLICA );

			$n = $dbr->selectField( 'archive', 'COUNT(*)',
				[ 'ar_namespace' => $this->getNamespace(), 'ar_title' => $this->getDBkey() ],
				__METHOD__
			);
			if ( $this->getNamespace() == NS_FILE ) {
				$n += $dbr->selectField( 'filearchive', 'COUNT(*)',
					[ 'fa_name' => $this->getDBkey() ],
					__METHOD__
				);
			}
		}
		return (int)$n;
	}

	/**
	 * Is there a version of this page in the deletion archive?
	 *
	 * @return bool
	 */
	public function isDeletedQuick() {
		if ( $this->getNamespace() < 0 ) {
			return false;
		}
		$dbr = wfGetDB( DB_REPLICA );
		$deleted = (bool)$dbr->selectField( 'archive', '1',
			[ 'ar_namespace' => $this->getNamespace(), 'ar_title' => $this->getDBkey() ],
			__METHOD__
		);
		if ( !$deleted && $this->getNamespace() == NS_FILE ) {
			$deleted = (bool)$dbr->selectField( 'filearchive', '1',
				[ 'fa_name' => $this->getDBkey() ],
				__METHOD__
			);
		}
		return $deleted;
	}

	/**
	 * Get the article ID for this Title from the link cache,
	 * adding it if necessary
	 *
	 * @param int $flags A bit field; may be Title::GAID_FOR_UPDATE to select
	 *  for update
	 * @return int The ID
	 */
	public function getArticleID( $flags = 0 ) {
		if ( $this->getNamespace() < 0 ) {
			$this->mArticleID = 0;
			return $this->mArticleID;
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
	 * @param int $flags A bit field; may be Title::GAID_FOR_UPDATE to select for update
	 * @return bool
	 */
	public function isRedirect( $flags = 0 ) {
		if ( !is_null( $this->mRedirect ) ) {
			return $this->mRedirect;
		}
		if ( !$this->getArticleID( $flags ) ) {
			$this->mRedirect = false;
			return $this->mRedirect;
		}

		$linkCache = LinkCache::singleton();
		$linkCache->addLinkObj( $this ); # in case we already had an article ID
		$cached = $linkCache->getGoodLinkFieldObj( $this, 'redirect' );
		if ( $cached === null ) {
			# Trust LinkCache's state over our own
			# LinkCache is telling us that the page doesn't exist, despite there being cached
			# data relating to an existing page in $this->mArticleID. Updaters should clear
			# LinkCache as appropriate, or use $flags = Title::GAID_FOR_UPDATE. If that flag is
			# set, then LinkCache will definitely be up to date here, since getArticleID() forces
			# LinkCache to refresh its data from the master.
			$this->mRedirect = false;
			return $this->mRedirect;
		}

		$this->mRedirect = (bool)$cached;

		return $this->mRedirect;
	}

	/**
	 * What is the length of this page?
	 * Uses link cache, adding it if necessary
	 *
	 * @param int $flags A bit field; may be Title::GAID_FOR_UPDATE to select for update
	 * @return int
	 */
	public function getLength( $flags = 0 ) {
		if ( $this->mLength != -1 ) {
			return $this->mLength;
		}
		if ( !$this->getArticleID( $flags ) ) {
			$this->mLength = 0;
			return $this->mLength;
		}
		$linkCache = LinkCache::singleton();
		$linkCache->addLinkObj( $this ); # in case we already had an article ID
		$cached = $linkCache->getGoodLinkFieldObj( $this, 'length' );
		if ( $cached === null ) {
			# Trust LinkCache's state over our own, as for isRedirect()
			$this->mLength = 0;
			return $this->mLength;
		}

		$this->mLength = intval( $cached );

		return $this->mLength;
	}

	/**
	 * What is the page_latest field for this page?
	 *
	 * @param int $flags A bit field; may be Title::GAID_FOR_UPDATE to select for update
	 * @return int Int or 0 if the page doesn't exist
	 */
	public function getLatestRevID( $flags = 0 ) {
		if ( !( $flags & Title::GAID_FOR_UPDATE ) && $this->mLatestID !== false ) {
			return intval( $this->mLatestID );
		}
		if ( !$this->getArticleID( $flags ) ) {
			$this->mLatestID = 0;
			return $this->mLatestID;
		}
		$linkCache = LinkCache::singleton();
		$linkCache->addLinkObj( $this ); # in case we already had an article ID
		$cached = $linkCache->getGoodLinkFieldObj( $this, 'revision' );
		if ( $cached === null ) {
			# Trust LinkCache's state over our own, as for isRedirect()
			$this->mLatestID = 0;
			return $this->mLatestID;
		}

		$this->mLatestID = intval( $cached );

		return $this->mLatestID;
	}

	/**
	 * This clears some fields in this object, and clears any associated
	 * keys in the "bad links" section of the link cache.
	 *
	 * - This is called from WikiPage::doEditContent() and WikiPage::insertOn() to allow
	 * loading of the new page_id. It's also called from
	 * WikiPage::doDeleteArticleReal()
	 *
	 * @param int $newid The new Article ID
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
		$this->mRestrictions = [];
		$this->mOldRestrictions = false;
		$this->mRedirect = null;
		$this->mLength = -1;
		$this->mLatestID = false;
		$this->mContentModel = false;
		$this->mEstimateRevisions = null;
		$this->mPageLanguage = false;
		$this->mDbPageLanguage = false;
		$this->mIsBigDeletion = null;
	}

	public static function clearCaches() {
		$linkCache = LinkCache::singleton();
		$linkCache->clear();

		$titleCache = self::getTitleCache();
		$titleCache->clear();
	}

	/**
	 * Capitalize a text string for a title if it belongs to a namespace that capitalizes
	 *
	 * @param string $text Containing title to capitalize
	 * @param int $ns Namespace index, defaults to NS_MAIN
	 * @return string Containing capitalized title
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
	 * @throws MalformedTitleException On invalid titles
	 * @return bool True on success
	 */
	private function secureAndSplit() {
		# Initialisation
		$this->mInterwiki = '';
		$this->mFragment = '';
		$this->mNamespace = $this->mDefaultNamespace; # Usually NS_MAIN

		$dbkey = $this->mDbkeyform;

		// @note: splitTitleString() is a temporary hack to allow MediaWikiTitleCodec to share
		//        the parsing code with Title, while avoiding massive refactoring.
		// @todo: get rid of secureAndSplit, refactor parsing code.
		// @note: getTitleParser() returns a TitleParser implementation which does not have a
		//        splitTitleString method, but the only implementation (MediaWikiTitleCodec) does
		$titleCodec = MediaWikiServices::getInstance()->getTitleParser();
		// MalformedTitleException can be thrown here
		$parts = $titleCodec->splitTitleString( $dbkey, $this->getDefaultNamespace() );

		# Fill fields
		$this->setFragment( '#' . $parts['fragment'] );
		$this->mInterwiki = $parts['interwiki'];
		$this->mLocalInterwiki = $parts['local_interwiki'];
		$this->mNamespace = $parts['namespace'];
		$this->mUserCaseDBKey = $parts['user_case_dbkey'];

		$this->mDbkeyform = $parts['dbkey'];
		$this->mUrlform = wfUrlencode( $this->mDbkeyform );
		$this->mTextform = strtr( $this->mDbkeyform, '_', ' ' );

		# We already know that some pages won't be in the database!
		if ( $this->isExternal() || $this->mNamespace == NS_SPECIAL ) {
			$this->mArticleID = 0;
		}

		return true;
	}

	/**
	 * Get an array of Title objects linking to this Title
	 * Also stores the IDs in the link cache.
	 *
	 * WARNING: do not use this function on arbitrary user-supplied titles!
	 * On heavily-used templates it will max out the memory.
	 *
	 * @param array $options May be FOR UPDATE
	 * @param string $table Table name
	 * @param string $prefix Fields prefix
	 * @return Title[] Array of Title objects linking here
	 */
	public function getLinksTo( $options = [], $table = 'pagelinks', $prefix = 'pl' ) {
		if ( count( $options ) > 0 ) {
			$db = wfGetDB( DB_MASTER );
		} else {
			$db = wfGetDB( DB_REPLICA );
		}

		$res = $db->select(
			[ 'page', $table ],
			self::getSelectFields(),
			[
				"{$prefix}_from=page_id",
				"{$prefix}_namespace" => $this->getNamespace(),
				"{$prefix}_title" => $this->getDBkey() ],
			__METHOD__,
			$options
		);

		$retVal = [];
		if ( $res->numRows() ) {
			$linkCache = LinkCache::singleton();
			foreach ( $res as $row ) {
				$titleObj = Title::makeTitle( $row->page_namespace, $row->page_title );
				if ( $titleObj ) {
					$linkCache->addGoodLinkObjFromRow( $titleObj, $row );
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
	 * @param array $options Query option to Database::select()
	 * @return Title[] Array of Title the Title objects linking here
	 */
	public function getTemplateLinksTo( $options = [] ) {
		return $this->getLinksTo( $options, 'templatelinks', 'tl' );
	}

	/**
	 * Get an array of Title objects linked from this Title
	 * Also stores the IDs in the link cache.
	 *
	 * WARNING: do not use this function on arbitrary user-supplied titles!
	 * On heavily-used templates it will max out the memory.
	 *
	 * @param array $options Query option to Database::select()
	 * @param string $table Table name
	 * @param string $prefix Fields prefix
	 * @return array Array of Title objects linking here
	 */
	public function getLinksFrom( $options = [], $table = 'pagelinks', $prefix = 'pl' ) {
		$id = $this->getArticleID();

		# If the page doesn't exist; there can't be any link from this page
		if ( !$id ) {
			return [];
		}

		$db = wfGetDB( DB_REPLICA );

		$blNamespace = "{$prefix}_namespace";
		$blTitle = "{$prefix}_title";

		$res = $db->select(
			[ $table, 'page' ],
			array_merge(
				[ $blNamespace, $blTitle ],
				WikiPage::selectFields()
			),
			[ "{$prefix}_from" => $id ],
			__METHOD__,
			$options,
			[ 'page' => [
				'LEFT JOIN',
				[ "page_namespace=$blNamespace", "page_title=$blTitle" ]
			] ]
		);

		$retVal = [];
		$linkCache = LinkCache::singleton();
		foreach ( $res as $row ) {
			if ( $row->page_id ) {
				$titleObj = Title::newFromRow( $row );
			} else {
				$titleObj = Title::makeTitle( $row->$blNamespace, $row->$blTitle );
				$linkCache->addBadLinkObj( $titleObj );
			}
			$retVal[] = $titleObj;
		}

		return $retVal;
	}

	/**
	 * Get an array of Title objects used on this Title as a template
	 * Also stores the IDs in the link cache.
	 *
	 * WARNING: do not use this function on arbitrary user-supplied titles!
	 * On heavily-used templates it will max out the memory.
	 *
	 * @param array $options May be FOR UPDATE
	 * @return Title[] Array of Title the Title objects used here
	 */
	public function getTemplateLinksFrom( $options = [] ) {
		return $this->getLinksFrom( $options, 'templatelinks', 'tl' );
	}

	/**
	 * Get an array of Title objects referring to non-existent articles linked
	 * from this page.
	 *
	 * @todo check if needed (used only in SpecialBrokenRedirects.php, and
	 *   should use redirect table in this case).
	 * @return Title[] Array of Title the Title objects
	 */
	public function getBrokenLinksFrom() {
		if ( $this->getArticleID() == 0 ) {
			# All links from article ID 0 are false positives
			return [];
		}

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select(
			[ 'page', 'pagelinks' ],
			[ 'pl_namespace', 'pl_title' ],
			[
				'pl_from' => $this->getArticleID(),
				'page_namespace IS NULL'
			],
			__METHOD__, [],
			[
				'page' => [
					'LEFT JOIN',
					[ 'pl_namespace=page_namespace', 'pl_title=page_title' ]
				]
			]
		);

		$retVal = [];
		foreach ( $res as $row ) {
			$retVal[] = Title::makeTitle( $row->pl_namespace, $row->pl_title );
		}
		return $retVal;
	}

	/**
	 * Get a list of URLs to purge from the CDN cache when this
	 * page changes
	 *
	 * @return string[] Array of String the URLs
	 */
	public function getCdnUrls() {
		$urls = [
			$this->getInternalURL(),
			$this->getInternalURL( 'action=history' )
		];

		$pageLang = $this->getPageLanguage();
		if ( $pageLang->hasVariants() ) {
			$variants = $pageLang->getVariants();
			foreach ( $variants as $vCode ) {
				$urls[] = $this->getInternalURL( $vCode );
			}
		}

		// If we are looking at a css/js user subpage, purge the action=raw.
		if ( $this->isJsSubpage() ) {
			$urls[] = $this->getInternalURL( 'action=raw&ctype=text/javascript' );
		} elseif ( $this->isCssSubpage() ) {
			$urls[] = $this->getInternalURL( 'action=raw&ctype=text/css' );
		}

		Hooks::run( 'TitleSquidURLs', [ $this, &$urls ] );
		return $urls;
	}

	/**
	 * @deprecated since 1.27 use getCdnUrls()
	 */
	public function getSquidURLs() {
		return $this->getCdnUrls();
	}

	/**
	 * Purge all applicable CDN URLs
	 */
	public function purgeSquid() {
		DeferredUpdates::addUpdate(
			new CdnCacheUpdate( $this->getCdnUrls() ),
			DeferredUpdates::PRESEND
		);
	}

	/**
	 * Move this page without authentication
	 *
	 * @deprecated since 1.25 use MovePage class instead
	 * @param Title $nt The new page Title
	 * @return array|bool True on success, getUserPermissionsErrors()-like array on failure
	 */
	public function moveNoAuth( &$nt ) {
		wfDeprecated( __METHOD__, '1.25' );
		return $this->moveTo( $nt, false );
	}

	/**
	 * Check whether a given move operation would be valid.
	 * Returns true if ok, or a getUserPermissionsErrors()-like array otherwise
	 *
	 * @deprecated since 1.25, use MovePage's methods instead
	 * @param Title $nt The new title
	 * @param bool $auth Whether to check user permissions (uses $wgUser)
	 * @param string $reason Is the log summary of the move, used for spam checking
	 * @return array|bool True on success, getUserPermissionsErrors()-like array on failure
	 */
	public function isValidMoveOperation( &$nt, $auth = true, $reason = '' ) {
		global $wgUser;

		if ( !( $nt instanceof Title ) ) {
			// Normally we'd add this to $errors, but we'll get
			// lots of syntax errors if $nt is not an object
			return [ [ 'badtitletext' ] ];
		}

		$mp = new MovePage( $this, $nt );
		$errors = $mp->isValidMove()->getErrorsArray();
		if ( $auth ) {
			$errors = wfMergeErrorArrays(
				$errors,
				$mp->checkPermissions( $wgUser, $reason )->getErrorsArray()
			);
		}

		return $errors ?: true;
	}

	/**
	 * Check if the requested move target is a valid file move target
	 * @todo move this to MovePage
	 * @param Title $nt Target title
	 * @return array List of errors
	 */
	protected function validateFileMoveOperation( $nt ) {
		global $wgUser;

		$errors = [];

		$destFile = wfLocalFile( $nt );
		$destFile->load( File::READ_LATEST );
		if ( !$wgUser->isAllowed( 'reupload-shared' )
			&& !$destFile->exists() && wfFindFile( $nt )
		) {
			$errors[] = [ 'file-exists-sharedrepo' ];
		}

		return $errors;
	}

	/**
	 * Move a title to a new location
	 *
	 * @deprecated since 1.25, use the MovePage class instead
	 * @param Title $nt The new title
	 * @param bool $auth Indicates whether $wgUser's permissions
	 *  should be checked
	 * @param string $reason The reason for the move
	 * @param bool $createRedirect Whether to create a redirect from the old title to the new title.
	 *  Ignored if the user doesn't have the suppressredirect right.
	 * @return array|bool True on success, getUserPermissionsErrors()-like array on failure
	 */
	public function moveTo( &$nt, $auth = true, $reason = '', $createRedirect = true ) {
		global $wgUser;
		$err = $this->isValidMoveOperation( $nt, $auth, $reason );
		if ( is_array( $err ) ) {
			// Auto-block user's IP if the account was "hard" blocked
			$wgUser->spreadAnyEditBlock();
			return $err;
		}
		// Check suppressredirect permission
		if ( $auth && !$wgUser->isAllowed( 'suppressredirect' ) ) {
			$createRedirect = true;
		}

		$mp = new MovePage( $this, $nt );
		$status = $mp->move( $wgUser, $reason, $createRedirect );
		if ( $status->isOK() ) {
			return true;
		} else {
			return $status->getErrorsArray();
		}
	}

	/**
	 * Move this page's subpages to be subpages of $nt
	 *
	 * @param Title $nt Move target
	 * @param bool $auth Whether $wgUser's permissions should be checked
	 * @param string $reason The reason for the move
	 * @param bool $createRedirect Whether to create redirects from the old subpages to
	 *     the new ones Ignored if the user doesn't have the 'suppressredirect' right
	 * @return array Array with old page titles as keys, and strings (new page titles) or
	 *     arrays (errors) as values, or an error array with numeric indices if no pages
	 *     were moved
	 */
	public function moveSubpages( $nt, $auth = true, $reason = '', $createRedirect = true ) {
		global $wgMaximumMovedPages;
		// Check permissions
		if ( !$this->userCan( 'move-subpages' ) ) {
			return [ 'cant-move-subpages' ];
		}
		// Do the source and target namespaces support subpages?
		if ( !MWNamespace::hasSubpages( $this->getNamespace() ) ) {
			return [ 'namespace-nosubpages',
				MWNamespace::getCanonicalName( $this->getNamespace() ) ];
		}
		if ( !MWNamespace::hasSubpages( $nt->getNamespace() ) ) {
			return [ 'namespace-nosubpages',
				MWNamespace::getCanonicalName( $nt->getNamespace() ) ];
		}

		$subpages = $this->getSubpages( $wgMaximumMovedPages + 1 );
		$retval = [];
		$count = 0;
		foreach ( $subpages as $oldSubpage ) {
			$count++;
			if ( $count > $wgMaximumMovedPages ) {
				$retval[$oldSubpage->getPrefixedText()] =
						[ 'movepage-max-pages',
							$wgMaximumMovedPages ];
				break;
			}

			// We don't know whether this function was called before
			// or after moving the root page, so check both
			// $this and $nt
			if ( $oldSubpage->getArticleID() == $this->getArticleID()
				|| $oldSubpage->getArticleID() == $nt->getArticleID()
			) {
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
	 * @return bool
	 */
	public function isSingleRevRedirect() {
		global $wgContentHandlerUseDB;

		$dbw = wfGetDB( DB_MASTER );

		# Is it a redirect?
		$fields = [ 'page_is_redirect', 'page_latest', 'page_id' ];
		if ( $wgContentHandlerUseDB ) {
			$fields[] = 'page_content_model';
		}

		$row = $dbw->selectRow( 'page',
			$fields,
			$this->pageCond(),
			__METHOD__,
			[ 'FOR UPDATE' ]
		);
		# Cache some fields we may want
		$this->mArticleID = $row ? intval( $row->page_id ) : 0;
		$this->mRedirect = $row ? (bool)$row->page_is_redirect : false;
		$this->mLatestID = $row ? intval( $row->page_latest ) : false;
		$this->mContentModel = $row && isset( $row->page_content_model )
			? strval( $row->page_content_model )
			: false;

		if ( !$this->mRedirect ) {
			return false;
		}
		# Does the article have a history?
		$row = $dbw->selectField( [ 'page', 'revision' ],
			'rev_id',
			[ 'page_namespace' => $this->getNamespace(),
				'page_title' => $this->getDBkey(),
				'page_id=rev_page',
				'page_latest != rev_id'
			],
			__METHOD__,
			[ 'FOR UPDATE' ]
		);
		# Return true if there was no history
		return ( $row === false );
	}

	/**
	 * Checks if $this can be moved to a given Title
	 * - Selects for update, so don't call it unless you mean business
	 *
	 * @deprecated since 1.25, use MovePage's methods instead
	 * @param Title $nt The new title to check
	 * @return bool
	 */
	public function isValidMoveTarget( $nt ) {
		# Is it an existing file?
		if ( $nt->getNamespace() == NS_FILE ) {
			$file = wfLocalFile( $nt );
			$file->load( File::READ_LATEST );
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
		$rev = Revision::newFromTitle( $nt, false, Revision::READ_LATEST );
		if ( !is_object( $rev ) ) {
			return false;
		}
		$content = $rev->getContent();
		# Does the redirect point to the source?
		# Or is it a broken self-redirect, usually caused by namespace collisions?
		$redirTitle = $content ? $content->getRedirectTarget() : null;

		if ( $redirTitle ) {
			if ( $redirTitle->getPrefixedDBkey() != $this->getPrefixedDBkey() &&
				$redirTitle->getPrefixedDBkey() != $nt->getPrefixedDBkey() ) {
				wfDebug( __METHOD__ . ": redirect points to other page\n" );
				return false;
			} else {
				return true;
			}
		} else {
			# Fail safe (not a redirect after all. strange.)
			wfDebug( __METHOD__ . ": failsafe: database sais " . $nt->getPrefixedDBkey() .
						" is a redirect, but it doesn't contain a valid redirect.\n" );
			return false;
		}
	}

	/**
	 * Get categories to which this Title belongs and return an array of
	 * categories' names.
	 *
	 * @return array Array of parents in the form:
	 *	  $parent => $currentarticle
	 */
	public function getParentCategories() {
		global $wgContLang;

		$data = [];

		$titleKey = $this->getArticleID();

		if ( $titleKey === 0 ) {
			return $data;
		}

		$dbr = wfGetDB( DB_REPLICA );

		$res = $dbr->select(
			'categorylinks',
			'cl_to',
			[ 'cl_from' => $titleKey ],
			__METHOD__
		);

		if ( $res->numRows() > 0 ) {
			foreach ( $res as $row ) {
				// $data[] = Title::newFromText($wgContLang->getNsText ( NS_CATEGORY ).':'.$row->cl_to);
				$data[$wgContLang->getNsText( NS_CATEGORY ) . ':' . $row->cl_to] = $this->getFullText();
			}
		}
		return $data;
	}

	/**
	 * Get a tree of parent categories
	 *
	 * @param array $children Array with the children in the keys, to check for circular refs
	 * @return array Tree of parent categories
	 */
	public function getParentCategoryTree( $children = [] ) {
		$stack = [];
		$parents = $this->getParentCategories();

		if ( $parents ) {
			foreach ( $parents as $parent => $current ) {
				if ( array_key_exists( $parent, $children ) ) {
					# Circular reference
					$stack[$parent] = [];
				} else {
					$nt = Title::newFromText( $parent );
					if ( $nt ) {
						$stack[$parent] = $nt->getParentCategoryTree( $children + [ $parent => 1 ] );
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
	 * @return array Array suitable for the $where parameter of DB::select()
	 */
	public function pageCond() {
		if ( $this->mArticleID > 0 ) {
			// PK avoids secondary lookups in InnoDB, shouldn't hurt other DBs
			return [ 'page_id' => $this->mArticleID ];
		} else {
			return [ 'page_namespace' => $this->mNamespace, 'page_title' => $this->mDbkeyform ];
		}
	}

	/**
	 * Get the revision ID of the previous revision
	 *
	 * @param int $revId Revision ID. Get the revision that was before this one.
	 * @param int $flags Title::GAID_FOR_UPDATE
	 * @return int|bool Old revision ID, or false if none exists
	 */
	public function getPreviousRevisionID( $revId, $flags = 0 ) {
		$db = ( $flags & self::GAID_FOR_UPDATE ) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_REPLICA );
		$revId = $db->selectField( 'revision', 'rev_id',
			[
				'rev_page' => $this->getArticleID( $flags ),
				'rev_id < ' . intval( $revId )
			],
			__METHOD__,
			[ 'ORDER BY' => 'rev_id DESC' ]
		);

		if ( $revId === false ) {
			return false;
		} else {
			return intval( $revId );
		}
	}

	/**
	 * Get the revision ID of the next revision
	 *
	 * @param int $revId Revision ID. Get the revision that was after this one.
	 * @param int $flags Title::GAID_FOR_UPDATE
	 * @return int|bool Next revision ID, or false if none exists
	 */
	public function getNextRevisionID( $revId, $flags = 0 ) {
		$db = ( $flags & self::GAID_FOR_UPDATE ) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_REPLICA );
		$revId = $db->selectField( 'revision', 'rev_id',
			[
				'rev_page' => $this->getArticleID( $flags ),
				'rev_id > ' . intval( $revId )
			],
			__METHOD__,
			[ 'ORDER BY' => 'rev_id' ]
		);

		if ( $revId === false ) {
			return false;
		} else {
			return intval( $revId );
		}
	}

	/**
	 * Get the first revision of the page
	 *
	 * @param int $flags Title::GAID_FOR_UPDATE
	 * @return Revision|null If page doesn't exist
	 */
	public function getFirstRevision( $flags = 0 ) {
		$pageId = $this->getArticleID( $flags );
		if ( $pageId ) {
			$db = ( $flags & self::GAID_FOR_UPDATE ) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_REPLICA );
			$row = $db->selectRow( 'revision', Revision::selectFields(),
				[ 'rev_page' => $pageId ],
				__METHOD__,
				[ 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => 1 ]
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
	 * @param int $flags Title::GAID_FOR_UPDATE
	 * @return string MW timestamp
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
		$dbr = wfGetDB( DB_REPLICA );
		return (bool)$dbr->selectField( 'page', 'page_is_new', $this->pageCond(), __METHOD__ );
	}

	/**
	 * Check whether the number of revisions of this page surpasses $wgDeleteRevisionsLimit
	 *
	 * @return bool
	 */
	public function isBigDeletion() {
		global $wgDeleteRevisionsLimit;

		if ( !$wgDeleteRevisionsLimit ) {
			return false;
		}

		if ( $this->mIsBigDeletion === null ) {
			$dbr = wfGetDB( DB_REPLICA );

			$revCount = $dbr->selectRowCount(
				'revision',
				'1',
				[ 'rev_page' => $this->getArticleID() ],
				__METHOD__,
				[ 'LIMIT' => $wgDeleteRevisionsLimit + 1 ]
			);

			$this->mIsBigDeletion = $revCount > $wgDeleteRevisionsLimit;
		}

		return $this->mIsBigDeletion;
	}

	/**
	 * Get the approximate revision count of this page.
	 *
	 * @return int
	 */
	public function estimateRevisionCount() {
		if ( !$this->exists() ) {
			return 0;
		}

		if ( $this->mEstimateRevisions === null ) {
			$dbr = wfGetDB( DB_REPLICA );
			$this->mEstimateRevisions = $dbr->estimateRowCount( 'revision', '*',
				[ 'rev_page' => $this->getArticleID() ], __METHOD__ );
		}

		return $this->mEstimateRevisions;
	}

	/**
	 * Get the number of revisions between the given revision.
	 * Used for diffs and other things that really need it.
	 *
	 * @param int|Revision $old Old revision or rev ID (first before range)
	 * @param int|Revision $new New revision or rev ID (first after range)
	 * @param int|null $max Limit of Revisions to count, will be incremented to detect truncations
	 * @return int Number of revisions between these revisions.
	 */
	public function countRevisionsBetween( $old, $new, $max = null ) {
		if ( !( $old instanceof Revision ) ) {
			$old = Revision::newFromTitle( $this, (int)$old );
		}
		if ( !( $new instanceof Revision ) ) {
			$new = Revision::newFromTitle( $this, (int)$new );
		}
		if ( !$old || !$new ) {
			return 0; // nothing to compare
		}
		$dbr = wfGetDB( DB_REPLICA );
		$conds = [
			'rev_page' => $this->getArticleID(),
			'rev_timestamp > ' . $dbr->addQuotes( $dbr->timestamp( $old->getTimestamp() ) ),
			'rev_timestamp < ' . $dbr->addQuotes( $dbr->timestamp( $new->getTimestamp() ) )
		];
		if ( $max !== null ) {
			return $dbr->selectRowCount( 'revision', '1',
				$conds,
				__METHOD__,
				[ 'LIMIT' => $max + 1 ] // extra to detect truncation
			);
		} else {
			return (int)$dbr->selectField( 'revision', 'count(*)', $conds, __METHOD__ );
		}
	}

	/**
	 * Get the authors between the given revisions or revision IDs.
	 * Used for diffs and other things that really need it.
	 *
	 * @since 1.23
	 *
	 * @param int|Revision $old Old revision or rev ID (first before range by default)
	 * @param int|Revision $new New revision or rev ID (first after range by default)
	 * @param int $limit Maximum number of authors
	 * @param string|array $options (Optional): Single option, or an array of options:
	 *     'include_old' Include $old in the range; $new is excluded.
	 *     'include_new' Include $new in the range; $old is excluded.
	 *     'include_both' Include both $old and $new in the range.
	 *     Unknown option values are ignored.
	 * @return array|null Names of revision authors in the range; null if not both revisions exist
	 */
	public function getAuthorsBetween( $old, $new, $limit, $options = [] ) {
		if ( !( $old instanceof Revision ) ) {
			$old = Revision::newFromTitle( $this, (int)$old );
		}
		if ( !( $new instanceof Revision ) ) {
			$new = Revision::newFromTitle( $this, (int)$new );
		}
		// XXX: what if Revision objects are passed in, but they don't refer to this title?
		// Add $old->getPage() != $new->getPage() || $old->getPage() != $this->getArticleID()
		// in the sanity check below?
		if ( !$old || !$new ) {
			return null; // nothing to compare
		}
		$authors = [];
		$old_cmp = '>';
		$new_cmp = '<';
		$options = (array)$options;
		if ( in_array( 'include_old', $options ) ) {
			$old_cmp = '>=';
		}
		if ( in_array( 'include_new', $options ) ) {
			$new_cmp = '<=';
		}
		if ( in_array( 'include_both', $options ) ) {
			$old_cmp = '>=';
			$new_cmp = '<=';
		}
		// No DB query needed if $old and $new are the same or successive revisions:
		if ( $old->getId() === $new->getId() ) {
			return ( $old_cmp === '>' && $new_cmp === '<' ) ?
				[] :
				[ $old->getUserText( Revision::RAW ) ];
		} elseif ( $old->getId() === $new->getParentId() ) {
			if ( $old_cmp === '>=' && $new_cmp === '<=' ) {
				$authors[] = $old->getUserText( Revision::RAW );
				if ( $old->getUserText( Revision::RAW ) != $new->getUserText( Revision::RAW ) ) {
					$authors[] = $new->getUserText( Revision::RAW );
				}
			} elseif ( $old_cmp === '>=' ) {
				$authors[] = $old->getUserText( Revision::RAW );
			} elseif ( $new_cmp === '<=' ) {
				$authors[] = $new->getUserText( Revision::RAW );
			}
			return $authors;
		}
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'revision', 'DISTINCT rev_user_text',
			[
				'rev_page' => $this->getArticleID(),
				"rev_timestamp $old_cmp " . $dbr->addQuotes( $dbr->timestamp( $old->getTimestamp() ) ),
				"rev_timestamp $new_cmp " . $dbr->addQuotes( $dbr->timestamp( $new->getTimestamp() ) )
			], __METHOD__,
			[ 'LIMIT' => $limit + 1 ] // add one so caller knows it was truncated
		);
		foreach ( $res as $row ) {
			$authors[] = $row->rev_user_text;
		}
		return $authors;
	}

	/**
	 * Get the number of authors between the given revisions or revision IDs.
	 * Used for diffs and other things that really need it.
	 *
	 * @param int|Revision $old Old revision or rev ID (first before range by default)
	 * @param int|Revision $new New revision or rev ID (first after range by default)
	 * @param int $limit Maximum number of authors
	 * @param string|array $options (Optional): Single option, or an array of options:
	 *     'include_old' Include $old in the range; $new is excluded.
	 *     'include_new' Include $new in the range; $old is excluded.
	 *     'include_both' Include both $old and $new in the range.
	 *     Unknown option values are ignored.
	 * @return int Number of revision authors in the range; zero if not both revisions exist
	 */
	public function countAuthorsBetween( $old, $new, $limit, $options = [] ) {
		$authors = $this->getAuthorsBetween( $old, $new, $limit, $options );
		return $authors ? count( $authors ) : 0;
	}

	/**
	 * Compare with another title.
	 *
	 * @param Title $title
	 * @return bool
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
	 * @param Title $title
	 * @return bool
	 */
	public function isSubpageOf( Title $title ) {
		return $this->getInterwiki() === $title->getInterwiki()
			&& $this->getNamespace() == $title->getNamespace()
			&& strpos( $this->getDBkey(), $title->getDBkey() . '/' ) === 0;
	}

	/**
	 * Check if page exists.  For historical reasons, this function simply
	 * checks for the existence of the title in the page table, and will
	 * thus return false for interwiki links, special pages and the like.
	 * If you want to know if a title can be meaningfully viewed, you should
	 * probably call the isKnown() method instead.
	 *
	 * @param int $flags An optional bit field; may be Title::GAID_FOR_UPDATE to check
	 *   from master/for update
	 * @return bool
	 */
	public function exists( $flags = 0 ) {
		$exists = $this->getArticleID( $flags ) != 0;
		Hooks::run( 'TitleExists', [ $this, &$exists ] );
		return $exists;
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
	 * @return bool
	 */
	public function isAlwaysKnown() {
		$isKnown = null;

		/**
		 * Allows overriding default behavior for determining if a page exists.
		 * If $isKnown is kept as null, regular checks happen. If it's
		 * a boolean, this value is returned by the isKnown method.
		 *
		 * @since 1.20
		 *
		 * @param Title $title
		 * @param bool|null $isKnown
		 */
		Hooks::run( 'TitleIsAlwaysKnown', [ $this, &$isKnown ] );

		if ( !is_null( $isKnown ) ) {
			return $isKnown;
		}

		if ( $this->isExternal() ) {
			return true;  // any interwiki link might be viewable, for all we know
		}

		switch ( $this->mNamespace ) {
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
				return $this->hasSourceText() !== false;
			default:
				return false;
		}
	}

	/**
	 * Does this title refer to a page that can (or might) be meaningfully
	 * viewed?  In particular, this function may be used to determine if
	 * links to the title should be rendered as "bluelinks" (as opposed to
	 * "redlinks" to non-existent pages).
	 * Adding something else to this function will cause inconsistency
	 * since LinkHolderArray calls isAlwaysKnown() and does its own
	 * page existence check.
	 *
	 * @return bool
	 */
	public function isKnown() {
		return $this->isAlwaysKnown() || $this->exists();
	}

	/**
	 * Does this page have source text?
	 *
	 * @return bool
	 */
	public function hasSourceText() {
		if ( $this->exists() ) {
			return true;
		}

		if ( $this->mNamespace == NS_MEDIAWIKI ) {
			// If the page doesn't exist but is a known system message, default
			// message content will be displayed, same for language subpages-
			// Use always content language to avoid loading hundreds of languages
			// to get the link color.
			global $wgContLang;
			list( $name, ) = MessageCache::singleton()->figureMessage(
				$wgContLang->lcfirst( $this->getText() )
			);
			$message = wfMessage( $name )->inLanguage( $wgContLang )->useDatabase( false );
			return $message->exists();
		}

		return false;
	}

	/**
	 * Get the default message text or false if the message doesn't exist
	 *
	 * @return string|bool
	 */
	public function getDefaultMessageText() {
		global $wgContLang;

		if ( $this->getNamespace() != NS_MEDIAWIKI ) { // Just in case
			return false;
		}

		list( $name, $lang ) = MessageCache::singleton()->figureMessage(
			$wgContLang->lcfirst( $this->getText() )
		);
		$message = wfMessage( $name )->inLanguage( $lang )->useDatabase( false );

		if ( $message->exists() ) {
			return $message->plain();
		} else {
			return false;
		}
	}

	/**
	 * Updates page_touched for this page; called from LinksUpdate.php
	 *
	 * @param string $purgeTime [optional] TS_MW timestamp
	 * @return bool True if the update succeeded
	 */
	public function invalidateCache( $purgeTime = null ) {
		if ( wfReadOnly() ) {
			return false;
		} elseif ( $this->mArticleID === 0 ) {
			return true; // avoid gap locking if we know it's not there
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->onTransactionPreCommitOrIdle( function () {
			ResourceLoaderWikiModule::invalidateModuleCache( $this, null, null, wfWikiID() );
		} );

		$conds = $this->pageCond();
		DeferredUpdates::addUpdate(
			new AutoCommitUpdate(
				$dbw,
				__METHOD__,
				function ( IDatabase $dbw, $fname ) use ( $conds, $purgeTime ) {
					$dbTimestamp = $dbw->timestamp( $purgeTime ?: time() );
					$dbw->update(
						'page',
						[ 'page_touched' => $dbTimestamp ],
						$conds + [ 'page_touched < ' . $dbw->addQuotes( $dbTimestamp ) ],
						$fname
					);
					MediaWikiServices::getInstance()->getLinkCache()->invalidateTitle( $this );
				}
			),
			DeferredUpdates::PRESEND
		);

		return true;
	}

	/**
	 * Update page_touched timestamps and send CDN purge messages for
	 * pages linking to this title. May be sent to the job queue depending
	 * on the number of links. Typically called on create and delete.
	 */
	public function touchLinks() {
		DeferredUpdates::addUpdate( new HTMLCacheUpdate( $this, 'pagelinks' ) );
		if ( $this->getNamespace() == NS_CATEGORY ) {
			DeferredUpdates::addUpdate( new HTMLCacheUpdate( $this, 'categorylinks' ) );
		}
	}

	/**
	 * Get the last touched timestamp
	 *
	 * @param IDatabase $db Optional db
	 * @return string Last-touched timestamp
	 */
	public function getTouched( $db = null ) {
		if ( $db === null ) {
			$db = wfGetDB( DB_REPLICA );
		}
		$touched = $db->selectField( 'page', 'page_touched', $this->pageCond(), __METHOD__ );
		return $touched;
	}

	/**
	 * Get the timestamp when this page was updated since the user last saw it.
	 *
	 * @param User $user
	 * @return string|null
	 */
	public function getNotificationTimestamp( $user = null ) {
		global $wgUser;

		// Assume current user if none given
		if ( !$user ) {
			$user = $wgUser;
		}
		// Check cache first
		$uid = $user->getId();
		if ( !$uid ) {
			return false;
		}
		// avoid isset here, as it'll return false for null entries
		if ( array_key_exists( $uid, $this->mNotificationTimestamp ) ) {
			return $this->mNotificationTimestamp[$uid];
		}
		// Don't cache too much!
		if ( count( $this->mNotificationTimestamp ) >= self::CACHE_MAX ) {
			$this->mNotificationTimestamp = [];
		}

		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$watchedItem = $store->getWatchedItem( $user, $this );
		if ( $watchedItem ) {
			$this->mNotificationTimestamp[$uid] = $watchedItem->getNotificationTimestamp();
		} else {
			$this->mNotificationTimestamp[$uid] = false;
		}

		return $this->mNotificationTimestamp[$uid];
	}

	/**
	 * Generate strings used for xml 'id' names in monobook tabs
	 *
	 * @param string $prepend Defaults to 'nstab-'
	 * @return string XML 'id' name
	 */
	public function getNamespaceKey( $prepend = 'nstab-' ) {
		global $wgContLang;
		// Gets the subject namespace if this title
		$namespace = MWNamespace::getSubject( $this->getNamespace() );
		// Checks if canonical namespace name exists for namespace
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
	 * Get all extant redirects to this Title
	 *
	 * @param int|null $ns Single namespace to consider; null to consider all namespaces
	 * @return Title[] Array of Title redirects to this title
	 */
	public function getRedirectsHere( $ns = null ) {
		$redirs = [];

		$dbr = wfGetDB( DB_REPLICA );
		$where = [
			'rd_namespace' => $this->getNamespace(),
			'rd_title' => $this->getDBkey(),
			'rd_from = page_id'
		];
		if ( $this->isExternal() ) {
			$where['rd_interwiki'] = $this->getInterwiki();
		} else {
			$where[] = 'rd_interwiki = ' . $dbr->addQuotes( '' ) . ' OR rd_interwiki IS NULL';
		}
		if ( !is_null( $ns ) ) {
			$where['page_namespace'] = $ns;
		}

		$res = $dbr->select(
			[ 'redirect', 'page' ],
			[ 'page_namespace', 'page_title' ],
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
	 * @return bool
	 */
	public function isValidRedirectTarget() {
		global $wgInvalidRedirectTargets;

		if ( $this->isSpecialPage() ) {
			// invalid redirect targets are stored in a global array, but explicitly disallow Userlogout here
			if ( $this->isSpecial( 'Userlogout' ) ) {
				return false;
			}

			foreach ( $wgInvalidRedirectTargets as $target ) {
				if ( $this->isSpecial( $target ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Get a backlink cache object
	 *
	 * @return BacklinkCache
	 */
	public function getBacklinkCache() {
		return BacklinkCache::get( $this );
	}

	/**
	 * Whether the magic words __INDEX__ and __NOINDEX__ function for  this page.
	 *
	 * @return bool
	 */
	public function canUseNoindex() {
		global $wgExemptFromUserRobotsControl;

		$bannedNamespaces = is_null( $wgExemptFromUserRobotsControl )
			? MWNamespace::getContentNamespaces()
			: $wgExemptFromUserRobotsControl;

		return !in_array( $this->mNamespace, $bannedNamespaces );

	}

	/**
	 * Returns the raw sort key to be used for categories, with the specified
	 * prefix.  This will be fed to Collation::getSortKey() to get a
	 * binary sortkey that can be used for actual sorting.
	 *
	 * @param string $prefix The prefix to be used, specified using
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
		Hooks::run( 'GetDefaultSortkey', [ $this, &$unprefixed ] );
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
	 * Returns the page language code saved in the database, if $wgPageLanguageUseDB is set
	 * to true in LocalSettings.php, otherwise returns false. If there is no language saved in
	 * the db, it will return NULL.
	 *
	 * @return string|null|bool
	 */
	private function getDbPageLanguageCode() {
		global $wgPageLanguageUseDB;

		// check, if the page language could be saved in the database, and if so and
		// the value is not requested already, lookup the page language using LinkCache
		if ( $wgPageLanguageUseDB && $this->mDbPageLanguage === false ) {
			$linkCache = LinkCache::singleton();
			$linkCache->addLinkObj( $this );
			$this->mDbPageLanguage = $linkCache->getGoodLinkFieldObj( $this, 'lang' );
		}

		return $this->mDbPageLanguage;
	}

	/**
	 * Get the language in which the content of this page is written in
	 * wikitext. Defaults to $wgContLang, but in certain cases it can be
	 * e.g. $wgLang (such as special pages, which are in the user language).
	 *
	 * @since 1.18
	 * @return Language
	 */
	public function getPageLanguage() {
		global $wgLang, $wgLanguageCode;
		if ( $this->isSpecialPage() ) {
			// special pages are in the user language
			return $wgLang;
		}

		// Checking if DB language is set
		$dbPageLanguage = $this->getDbPageLanguageCode();
		if ( $dbPageLanguage ) {
			return wfGetLangObj( $dbPageLanguage );
		}

		if ( !$this->mPageLanguage || $this->mPageLanguage[1] !== $wgLanguageCode ) {
			// Note that this may depend on user settings, so the cache should
			// be only per-request.
			// NOTE: ContentHandler::getPageLanguage() may need to load the
			// content to determine the page language!
			// Checking $wgLanguageCode hasn't changed for the benefit of unit
			// tests.
			$contentHandler = ContentHandler::getForTitle( $this );
			$langObj = $contentHandler->getPageLanguage( $this );
			$this->mPageLanguage = [ $langObj->getCode(), $wgLanguageCode ];
		} else {
			$langObj = wfGetLangObj( $this->mPageLanguage[0] );
		}

		return $langObj;
	}

	/**
	 * Get the language in which the content of this page is written when
	 * viewed by user. Defaults to $wgContLang, but in certain cases it can be
	 * e.g. $wgLang (such as special pages, which are in the user language).
	 *
	 * @since 1.20
	 * @return Language
	 */
	public function getPageViewLanguage() {
		global $wgLang;

		if ( $this->isSpecialPage() ) {
			// If the user chooses a variant, the content is actually
			// in a language whose code is the variant code.
			$variant = $wgLang->getPreferredVariant();
			if ( $wgLang->getCode() !== $variant ) {
				return Language::factory( $variant );
			}

			return $wgLang;
		}

		// Checking if DB language is set
		$dbPageLanguage = $this->getDbPageLanguageCode();
		if ( $dbPageLanguage ) {
			$pageLang = wfGetLangObj( $dbPageLanguage );
			$variant = $pageLang->getPreferredVariant();
			if ( $pageLang->getCode() !== $variant ) {
				$pageLang = Language::factory( $variant );
			}

			return $pageLang;
		}

		// @note Can't be cached persistently, depends on user settings.
		// @note ContentHandler::getPageViewLanguage() may need to load the
		//   content to determine the page language!
		$contentHandler = ContentHandler::getForTitle( $this );
		$pageLang = $contentHandler->getPageViewLanguage( $this );
		return $pageLang;
	}

	/**
	 * Get a list of rendered edit notices for this page.
	 *
	 * Array is keyed by the original message key, and values are rendered using parseAsBlock, so
	 * they will already be wrapped in paragraphs.
	 *
	 * @since 1.21
	 * @param int $oldid Revision ID that's being edited
	 * @return array
	 */
	public function getEditNotices( $oldid = 0 ) {
		$notices = [];

		// Optional notice for the entire namespace
		$editnotice_ns = 'editnotice-' . $this->getNamespace();
		$msg = wfMessage( $editnotice_ns );
		if ( $msg->exists() ) {
			$html = $msg->parseAsBlock();
			// Edit notices may have complex logic, but output nothing (T91715)
			if ( trim( $html ) !== '' ) {
				$notices[$editnotice_ns] = Html::rawElement(
					'div',
					[ 'class' => [
						'mw-editnotice',
						'mw-editnotice-namespace',
						Sanitizer::escapeClass( "mw-$editnotice_ns" )
					] ],
					$html
				);
			}
		}

		if ( MWNamespace::hasSubpages( $this->getNamespace() ) ) {
			// Optional notice for page itself and any parent page
			$parts = explode( '/', $this->getDBkey() );
			$editnotice_base = $editnotice_ns;
			while ( count( $parts ) > 0 ) {
				$editnotice_base .= '-' . array_shift( $parts );
				$msg = wfMessage( $editnotice_base );
				if ( $msg->exists() ) {
					$html = $msg->parseAsBlock();
					if ( trim( $html ) !== '' ) {
						$notices[$editnotice_base] = Html::rawElement(
							'div',
							[ 'class' => [
								'mw-editnotice',
								'mw-editnotice-base',
								Sanitizer::escapeClass( "mw-$editnotice_base" )
							] ],
							$html
						);
					}
				}
			}
		} else {
			// Even if there are no subpages in namespace, we still don't want "/" in MediaWiki message keys
			$editnoticeText = $editnotice_ns . '-' . strtr( $this->getDBkey(), '/', '-' );
			$msg = wfMessage( $editnoticeText );
			if ( $msg->exists() ) {
				$html = $msg->parseAsBlock();
				if ( trim( $html ) !== '' ) {
					$notices[$editnoticeText] = Html::rawElement(
						'div',
						[ 'class' => [
							'mw-editnotice',
							'mw-editnotice-page',
							Sanitizer::escapeClass( "mw-$editnoticeText" )
						] ],
						$html
					);
				}
			}
		}

		Hooks::run( 'TitleGetEditNotices', [ $this, $oldid, &$notices ] );
		return $notices;
	}

	/**
	 * @return array
	 */
	public function __sleep() {
		return [
			'mNamespace',
			'mDbkeyform',
			'mFragment',
			'mInterwiki',
			'mLocalInterwiki',
			'mUserCaseDBKey',
			'mDefaultNamespace',
		];
	}

	public function __wakeup() {
		$this->mArticleID = ( $this->mNamespace >= 0 ) ? -1 : 0;
		$this->mUrlform = wfUrlencode( $this->mDbkeyform );
		$this->mTextform = strtr( $this->mDbkeyform, '_', ' ' );
	}

}
