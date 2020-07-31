<?php
/**
 * Representation of a title within MediaWiki.
 *
 * See Title.md
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

use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;

/**
 * Represents a title within MediaWiki.
 * Optionally may contain an interwiki designation or namespace.
 * @note This class can fetch various kinds of data from the database;
 *       however, it does so inefficiently.
 * @note Consider using a TitleValue object instead. TitleValue is more lightweight
 *       and does not rely on global state or the database.
 */
class Title implements LinkTarget, IDBAccessObject {
	/** @var MapCacheLRU|null */
	private static $titleCache = null;

	/**
	 * Title::newFromText maintains a cache to avoid expensive re-normalization of
	 * commonly used titles. On a batch operation this can become a memory leak
	 * if not bounded. After hitting this many titles reset the cache.
	 */
	private const CACHE_MAX = 1000;

	/**
	 * Used to be GAID_FOR_UPDATE define(). Used with getArticleID() and friends
	 * to use the master DB and inject it into link cache.
	 * @deprecated since 1.34, use Title::READ_LATEST instead.
	 */
	public const GAID_FOR_UPDATE = 512;

	/**
	 * Flag for use with factory methods like newFromLinkTarget() that have
	 * a $forceClone parameter. If set, the method must return a new instance.
	 * Without this flag, some factory methods may return existing instances.
	 *
	 * @since 1.33
	 */
	public const NEW_CLONE = 'clone';

	/**
	 * @name Private member variables
	 * Please use the accessor functions instead.
	 * @internal
	 */
	// @{

	/** @var string Text form (spaces not underscores) of the main part */
	public $mTextform = '';
	/** @var string URL-encoded form of the main part */
	public $mUrlform = '';
	/** @var string Main part with underscores */
	public $mDbkeyform = '';
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

	/**
	 * @var string|bool Comma-separated set of permission keys
	 * indicating who can move or edit the page from the page table, (pre 1.10) rows.
	 * Edit and move sections are separated by a colon
	 * Example: "edit=autoconfirmed,sysop:move=sysop"
	 */
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

	/**
	 * Text form including namespace/interwiki, initialised on demand
	 *
	 * Only public to share cache with TitleFormatter
	 *
	 * @internal
	 * @var string|null
	 */
	public $prefixedText = null;

	/** @var mixed Cached value for getTitleProtection (create protection) */
	public $mTitleProtection;

	/**
	 * @var int Namespace index when there is no namespace. Don't change the
	 *   following default, NS_MAIN is hardcoded in several places. See T2696.
	 *   Used primarily for {{transclusion}} tags.
	 * @see newFromText()
	 */
	public $mDefaultNamespace = NS_MAIN;

	/** @var int The page length, 0 for special pages */
	protected $mLength = -1;

	/** @var null Is the article at this title a redirect? */
	public $mRedirect = null;

	/** @var bool Whether a page has any subpages */
	private $mHasSubpages;

	/** @var array|null The (string) language code of the page's language and content code. */
	private $mPageLanguage;

	/** @var string|bool|null The page language code from the database, null if not saved in
	 * the database or false if not loaded, yet.
	 */
	private $mDbPageLanguage = false;

	/** @var TitleValue|null A corresponding TitleValue object */
	private $mTitleValue = null;

	/** @var bool|null Would deleting this page be a big deletion? */
	private $mIsBigDeletion = null;

	/** @var bool|null Is the title known to be valid? */
	private $mIsValid = null;
	// @}

	/**
	 * Shorthand for getting a Language Converter for specific language
	 * @param Language $language Language of converter
	 * @return ILanguageConverter
	 */
	private function getLanguageConverter( $language ) : ILanguageConverter {
		return MediaWikiServices::getInstance()->getLanguageConverterFactory()
			->getLanguageConverter( $language );
	}

	/**
	 * Shorthand for getting a Language Converter for page's language
	 * @return ILanguageConverter
	 */
	private function getPageLanguageConverter() : ILanguageConverter {
		return $this->getLanguageConverter( $this->getPageLanguage() );
	}

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

	private function __construct() {
	}

	/**
	 * Create a new Title from a prefixed DB key
	 *
	 * @param string $key The database key, which has underscores
	 * 	instead of spaces, possibly including namespace and
	 * 	interwiki prefixes
	 * @return Title|null Title, or null on an error
	 */
	public static function newFromDBkey( $key ) {
		$t = new self();

		try {
			$t->secureAndSplit( $key );
			return $t;
		} catch ( MalformedTitleException $ex ) {
			return null;
		}
	}

	/**
	 * Returns a Title given a TitleValue.
	 * If the given TitleValue is already a Title instance, that instance is returned,
	 * unless $forceClone is "clone". If $forceClone is "clone" and the given TitleValue
	 * is already a Title instance, that instance is copied using the clone operator.
	 *
	 * @deprecated since 1.34, use newFromLinkTarget or castFromLinkTarget
	 *
	 * @param TitleValue $titleValue Assumed to be safe.
	 * @param string $forceClone set to NEW_CLONE to ensure a fresh instance is returned.
	 *
	 * @return Title
	 */
	public static function newFromTitleValue( TitleValue $titleValue, $forceClone = '' ) {
		return self::newFromLinkTarget( $titleValue, $forceClone );
	}

	/**
	 * Returns a Title given a LinkTarget.
	 * If the given LinkTarget is already a Title instance, that instance is returned,
	 * unless $forceClone is "clone". If $forceClone is "clone" and the given LinkTarget
	 * is already a Title instance, that instance is copied using the clone operator.
	 *
	 * @param LinkTarget $linkTarget Assumed to be safe.
	 * @param string $forceClone set to NEW_CLONE to ensure a fresh instance is returned.
	 *
	 * @return Title
	 */
	public static function newFromLinkTarget( LinkTarget $linkTarget, $forceClone = '' ) {
		if ( $linkTarget instanceof Title ) {
			// Special case if it's already a Title object
			if ( $forceClone === self::NEW_CLONE ) {
				return clone $linkTarget;
			} else {
				return $linkTarget;
			}
		}
		return self::makeTitle(
			$linkTarget->getNamespace(),
			$linkTarget->getText(),
			$linkTarget->getFragment(),
			$linkTarget->getInterwiki()
		);
	}

	/**
	 * Same as newFromLinkTarget, but if passed null, returns null.
	 *
	 * @param LinkTarget|null $linkTarget Assumed to be safe (if not null).
	 *
	 * @return Title|null
	 */
	public static function castFromLinkTarget( $linkTarget ) {
		return $linkTarget ? self::newFromLinkTarget( $linkTarget ) : null;
	}

	/**
	 * Create a new Title from text, such as what one would find in a link.
	 * Decodes any HTML entities in the text.
	 * Titles returned by this method are guaranteed to be valid.
	 * Call canExist() to check if the Title represents an editable page.
	 *
	 * @note The Title instance returned by this method is not guaranteed to be a fresh instance.
	 * It may instead be a cached instance created previously, with references to it remaining
	 * elsewhere.
	 *
	 * @param string|int|null $text The link text; spaces, prefixes, and an
	 *   initial ':' indicating the main namespace are accepted.
	 * @param int $defaultNamespace The namespace to use if none is specified
	 *   by a prefix.  If you want to force a specific namespace even if
	 *   $text might begin with a namespace prefix, use makeTitle() or
	 *   makeTitleSafe().
	 * @throws InvalidArgumentException
	 * @return Title|null Title or null if the Title could not be parsed because
	 *         it is invalid.
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
			return self::newFromTextThrow( (string)$text, (int)$defaultNamespace );
		} catch ( MalformedTitleException $ex ) {
			return null;
		}
	}

	/**
	 * Like Title::newFromText(), but throws MalformedTitleException when the title is invalid,
	 * rather than returning null.
	 *
	 * Titles returned by this method are guaranteed to be valid.
	 * Call canExist() to check if the Title represents an editable page.
	 *
	 * @note The Title instance returned by this method is not guaranteed to be a fresh instance.
	 * It may instead be a cached instance created previously, with references to it remaining
	 * elsewhere.
	 *
	 * @see Title::newFromText
	 *
	 * @since 1.25
	 * @param string $text Title text to check
	 * @param int $defaultNamespace
	 * @throws MalformedTitleException If the title is invalid.
	 * @return Title
	 */
	public static function newFromTextThrow( $text, $defaultNamespace = NS_MAIN ) {
		if ( is_object( $text ) ) {
			throw new MWException( '$text must be a string, given an object' );
		} elseif ( $text === null ) {
			// Legacy code relies on MalformedTitleException being thrown in this case
			// (happens when URL with no title in it is parsed). TODO fix
			throw new MalformedTitleException( 'title-invalid-empty' );
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

		// Convert things like &eacute; &#257; or &#x3017; into normalized (T16952) text
		$filteredText = Sanitizer::decodeCharReferencesAndNormalize( $text );

		$t = new Title();
		$dbKeyForm = strtr( $filteredText, ' ', '_' );

		$t->secureAndSplit( $dbKeyForm, (int)$defaultNamespace );
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

		$dbKeyForm = strtr( $url, ' ', '_' );

		try {
			$t->secureAndSplit( $dbKeyForm );
			return $t;
		} catch ( MalformedTitleException $ex ) {
			return null;
		}
	}

	/**
	 * @return MapCacheLRU
	 */
	private static function getTitleCache() {
		if ( self::$titleCache === null ) {
			self::$titleCache = new MapCacheLRU( self::CACHE_MAX );
		}
		return self::$titleCache;
	}

	/**
	 * Returns a list of fields that are to be selected for initializing Title
	 * objects or LinkCache entries.
	 *
	 * @return array
	 */
	protected static function getSelectFields() {
		global $wgPageLanguageUseDB;

		$fields = [
			'page_namespace', 'page_title', 'page_id',
			'page_len', 'page_is_redirect', 'page_latest',
			'page_content_model',
		];

		if ( $wgPageLanguageUseDB ) {
			$fields[] = 'page_lang';
		}

		return $fields;
	}

	/**
	 * Create a new Title from an article ID
	 *
	 * @param int $id The page_id corresponding to the Title to create
	 * @param int $flags Bitfield of class READ_* constants
	 * @return Title|null The new object, or null on an error
	 */
	public static function newFromID( $id, $flags = 0 ) {
		$flags |= ( $flags & self::GAID_FOR_UPDATE ) ? self::READ_LATEST : 0; // b/c
		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );
		$row = wfGetDB( $index )->selectRow(
			'page',
			self::getSelectFields(),
			[ 'page_id' => $id ],
			__METHOD__,
			$options
		);
		if ( $row !== false ) {
			$title = self::newFromRow( $row );
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
			$titles[] = self::newFromRow( $row );
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
			if ( isset( $row->page_content_model ) ) {
				$this->lazyFillContentModel( $row->page_content_model );
			} else {
				$this->lazyFillContentModel( false ); // lazily-load getContentModel()
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
			$this->lazyFillContentModel( false ); // lazily-load getContentModel()
		}
	}

	/**
	 * Create a new Title from a namespace index and a DB key.
	 *
	 * It's assumed that $ns and $title are safe, for instance when
	 * they came directly from the database or a special page name,
	 * not from user input.
	 *
	 * No validation is applied. For convenience, spaces are normalized
	 * to underscores, so that e.g. user_text fields can be used directly.
	 *
	 * @note This method may return Title objects that are "invalid"
	 * according to the isValid() method. This is usually caused by
	 * configuration changes: e.g. a namespace that was once defined is
	 * no longer configured, or a character that was once allowed in
	 * titles is now forbidden.
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
		$t->mNamespace = $ns = (int)$ns;
		$t->mDbkeyform = strtr( $title, ' ', '_' );
		$t->mArticleID = ( $ns >= 0 ) ? -1 : 0;
		$t->mUrlform = wfUrlencode( $t->mDbkeyform );
		$t->mTextform = strtr( $title, '_', ' ' );
		return $t;
	}

	/**
	 * Create a new Title from a namespace index and a DB key.
	 * The parameters will be checked for validity, which is a bit slower
	 * than makeTitle() but safer for user-provided data.
	 *
	 * The Title object returned by this method is guaranteed to be valid.
	 * Call canExist() to check if the Title represents an editable page.
	 *
	 * @param int $ns The namespace of the article
	 * @param string $title Database key form
	 * @param string $fragment The link fragment (after the "#")
	 * @param string $interwiki Interwiki prefix
	 * @return Title|null The new object, or null on an error
	 */
	public static function makeTitleSafe( $ns, $title, $fragment = '', $interwiki = '' ) {
		// NOTE: ideally, this would just call makeTitle() and then isValid(),
		// but presently, that means more overhead on a potential performance hotspot.

		if ( !MediaWikiServices::getInstance()->getNamespaceInfo()->exists( $ns ) ) {
			return null;
		}

		$t = new Title();
		$dbKeyForm = self::makeName( $ns, $title, $fragment, $interwiki, true );

		try {
			$t->secureAndSplit( $dbKeyForm );
			return $t;
		} catch ( MalformedTitleException $ex ) {
			return null;
		}
	}

	/**
	 * Create a new Title for the Main Page
	 *
	 * This uses the 'mainpage' interface message, which could be specified in
	 * `$wgForceUIMsgAsContentMsg`. If that is the case, then calling this method
	 * will use the user language, which would involve initialising the session
	 * via `RequestContext::getMain()->getLanguage()`. For session-less endpoints,
	 * be sure to pass in a MessageLocalizer (such as your own RequestContext,
	 * or ResourceloaderContext) to prevent an error.
	 *
	 * @note The Title instance returned by this method is not guaranteed to be a fresh instance.
	 * It may instead be a cached instance created previously, with references to it remaining
	 * elsewhere.
	 *
	 * @param MessageLocalizer|null $localizer An optional context to use (since 1.34)
	 * @return Title
	 */
	public static function newMainPage( MessageLocalizer $localizer = null ) {
		if ( $localizer ) {
			$msg = $localizer->msg( 'mainpage' );
		} else {
			$msg = wfMessage( 'mainpage' );
		}

		$title = self::newFromText( $msg->inContentLanguage()->text() );

		// Every page renders at least one link to the Main Page (e.g. sidebar).
		// If the localised value is invalid, don't produce fatal errors that
		// would make the wiki inaccessible (and hard to fix the invalid message).
		// Gracefully fallback...
		if ( !$title ) {
			$title = self::newFromText( 'Main Page' );
		}
		return $title;
	}

	/**
	 * Get the prefixed DB key associated with an ID
	 *
	 * @param int $id The page_id of the article
	 * @return string|null An object representing the article, or null if no such article was found
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

		return self::makeName( $s->page_namespace, $s->page_title );
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
				// @phan-suppress-next-line PhanParamSuspiciousOrder false positive
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
		// @phan-suppress-next-line PhanSuspiciousValueComparison
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
		if ( $canonicalNamespace ) {
			$namespace = MediaWikiServices::getInstance()->getNamespaceInfo()->
				getCanonicalName( $ns );
		} else {
			$namespace = MediaWikiServices::getInstance()->getContentLanguage()->getNsText( $ns );
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
	 * Callback for usort() to do title sorts by (namespace, title)
	 *
	 * @param LinkTarget $a
	 * @param LinkTarget $b
	 *
	 * @return int Result of string comparison, or namespace comparison
	 */
	public static function compare( LinkTarget $a, LinkTarget $b ) {
		return $a->getNamespace() <=> $b->getNamespace()
			?: strcmp( $a->getText(), $b->getText() );
	}

	/**
	 * Returns true if the title is a valid link target, and that it has been
	 * properly normalized. This method checks that the title is syntactically valid,
	 * and that the namespace it refers to exists.
	 *
	 * Titles constructed using newFromText() or makeTitleSafe() are always valid.
	 *
	 * @note Code that wants to check whether the title can represent a page that can
	 * be created and edited should use canExist() instead. Examples of valid titles
	 * that cannot "exist" are Special pages, interwiki links, and on-page section links
	 * that only have the fragment part set.
	 *
	 * @see canExist()
	 *
	 * @return bool
	 */
	public function isValid() {
		if ( $this->mIsValid !== null ) {
			return $this->mIsValid;
		}

		try {
			$text = $this->getFullText();
			$titleCodec = MediaWikiServices::getInstance()->getTitleParser();

			'@phan-var MediaWikiTitleCodec $titleCodec';
			$parts = $titleCodec->splitTitleString( $text, $this->mNamespace );

			// Check that nothing changed!
			// This ensures that $text was already perperly normalized.
			if ( $parts['fragment'] !== $this->mFragment
				|| $parts['interwiki'] !== $this->mInterwiki
				|| $parts['local_interwiki'] !== $this->mLocalInterwiki
				|| $parts['namespace'] !== $this->mNamespace
				|| $parts['dbkey'] !== $this->mDbkeyform
			) {
				$this->mIsValid = false;
				return $this->mIsValid;
			}
		} catch ( MalformedTitleException $ex ) {
			$this->mIsValid = false;
			return $this->mIsValid;
		}

		$this->mIsValid = true;
		return $this->mIsValid;
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
	 * @return string|false The DB name
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
					$this->mNamespace,
					$this->mDbkeyform,
					$this->mFragment,
					$this->mInterwiki
				);
			} catch ( InvalidArgumentException $ex ) {
				wfDebug( __METHOD__ . ': Can\'t create a TitleValue for [[' .
					$this->getPrefixedText() . ']]: ' . $ex->getMessage() );
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
	 * @todo Deprecate this in favor of SlotRecord::getModel()
	 *
	 * @param int $flags Either a bitfield of class READ_* constants or GAID_FOR_UPDATE
	 * @return string Content model id
	 */
	public function getContentModel( $flags = 0 ) {
		if ( $this->mForcedContentModel ) {
			if ( !$this->mContentModel ) {
				throw new RuntimeException( 'Got out of sync; an empty model is being forced' );
			}
			// Content model is locked to the currently loaded one
			return $this->mContentModel;
		}

		if ( DBAccessObjectUtils::hasFlags( $flags, self::READ_LATEST ) ) {
			$this->lazyFillContentModel( $this->loadFieldFromDB( 'page_content_model', $flags ) );
		} elseif (
			( !$this->mContentModel || $flags & self::GAID_FOR_UPDATE ) &&
			$this->getArticleID( $flags )
		) {
			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			$linkCache->addLinkObj( $this ); # in case we already had an article ID
			$this->lazyFillContentModel( $linkCache->getGoodLinkFieldObj( $this, 'model' ) );
		}

		if ( !$this->mContentModel ) {
			$this->lazyFillContentModel( ContentHandler::getDefaultModelFor( $this ) );
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
	 * Set a proposed content model for the page for permissions checking
	 *
	 * This does not actually change the content model of a title in the DB.
	 * It only affects this particular Title instance. The content model is
	 * forced to remain this value until another setContentModel() call.
	 *
	 * ContentHandler::canBeUsedOn() should be checked before calling this
	 * if there is any doubt regarding the applicability of the content model
	 *
	 * @since 1.28
	 * @param string $model CONTENT_MODEL_XXX constant
	 */
	public function setContentModel( $model ) {
		if ( (string)$model === '' ) {
			throw new InvalidArgumentException( "Missing CONTENT_MODEL_* constant" );
		}

		$this->mContentModel = $model;
		$this->mForcedContentModel = true;
	}

	/**
	 * If the content model field is not frozen then update it with a retreived value
	 *
	 * @param string|bool $model CONTENT_MODEL_XXX constant or false
	 */
	private function lazyFillContentModel( $model ) {
		if ( !$this->mForcedContentModel ) {
			$this->mContentModel = ( $model === false ) ? false : (string)$model;
		}
	}

	/**
	 * Get the namespace text
	 *
	 * @return string|false Namespace text
	 */
	public function getNsText() {
		if ( $this->isExternal() ) {
			// This probably shouldn't even happen, except for interwiki transclusion.
			// If possible, use the canonical name for the foreign namespace.
			$nsText = MediaWikiServices::getInstance()->getNamespaceInfo()->
				getCanonicalName( $this->mNamespace );
			if ( $nsText !== false ) {
				return $nsText;
			}
		}

		try {
			$formatter = self::getTitleFormatter();
			return $formatter->getNamespaceName( $this->mNamespace, $this->mDbkeyform );
		} catch ( InvalidArgumentException $ex ) {
			wfDebug( __METHOD__ . ': ' . $ex->getMessage() );
			return false;
		}
	}

	/**
	 * Get the namespace text of the subject (rather than talk) page
	 *
	 * @return string Namespace text
	 */
	public function getSubjectNsText() {
		$services = MediaWikiServices::getInstance();
		return $services->getContentLanguage()->
			getNsText( $services->getNamespaceInfo()->getSubject( $this->mNamespace ) );
	}

	/**
	 * Get the namespace text of the talk page
	 *
	 * @return string Namespace text
	 */
	public function getTalkNsText() {
		$services = MediaWikiServices::getInstance();
		return $services->getContentLanguage()->
			getNsText( $services->getNamespaceInfo()->getTalk( $this->mNamespace ) );
	}

	/**
	 * Can this title have a corresponding talk page?
	 *
	 * False for relative section links (with getText() === ''),
	 * interwiki links (with getInterwiki() !== ''), and pages in NS_SPECIAL.
	 *
	 * @see NamespaceInfo::canHaveTalkPage
	 * @since 1.30
	 *
	 * @return bool True if this title either is a talk page or can have a talk page associated.
	 */
	public function canHaveTalkPage() {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->canHaveTalkPage( $this );
	}

	/**
	 * Can this title represent a page in the wiki's database?
	 *
	 * Titles can exist as pages in the database if they are valid, and they
	 * are not Special pages, interwiki links, or fragment-only links.
	 *
	 * @see isValid()
	 *
	 * @return bool true if and only if this title can be used to perform an edit.
	 */
	public function canExist() {
		// NOTE: Don't use getArticleID(), we don't want to
		// trigger a database query here. This check is supposed to
		// act as an optimization, not add extra cost.
		if ( $this->mArticleID > 0 ) {
			// It exists, so it can exist.
			return true;
		}

		// NOTE: we call the relatively expensive isValid() method further down,
		// but we can bail out early if we already know the title is invalid.
		if ( $this->mIsValid === false ) {
			// It's invalid, so it can't exist.
			return false;
		}

		if ( $this->getNamespace() < NS_MAIN ) {
			// It's a special page, so it can't exist in the database.
			return false;
		}

		if ( $this->isExternal() ) {
			// If it's external, it's not local, so it can't exist.
			return false;
		}

		if ( $this->getText() === '' ) {
			// The title has no text, so it can't exist in the database.
			// It's probably an on-page section link, like "#something".
			return false;
		}

		// Double check that the title is valid.
		return $this->isValid();
	}

	/**
	 * Can this title be added to a user's watchlist?
	 *
	 * False for relative section links (with getText() === ''),
	 * interwiki links (with getInterwiki() !== ''), and pages in NS_SPECIAL.
	 *
	 * @return bool
	 */
	public function isWatchable() {
		$nsInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
		return $this->getText() !== '' && !$this->isExternal() &&
			$nsInfo->isWatchable( $this->mNamespace );
	}

	/**
	 * Returns true if this is a special page.
	 *
	 * @return bool
	 */
	public function isSpecialPage() {
		return $this->mNamespace == NS_SPECIAL;
	}

	/**
	 * Returns true if this title resolves to the named special page
	 *
	 * @param string $name The special page name
	 * @return bool
	 */
	public function isSpecial( $name ) {
		if ( $this->isSpecialPage() ) {
			list( $thisName, /* $subpage */ ) =
				MediaWikiServices::getInstance()->getSpecialPageFactory()->
					resolveAlias( $this->mDbkeyform );
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
			$spFactory = MediaWikiServices::getInstance()->getSpecialPageFactory();
			list( $canonicalName, $par ) = $spFactory->resolveAlias( $this->mDbkeyform );
			if ( $canonicalName ) {
				$localName = $spFactory->getLocalNameFor( $canonicalName, $par );
				if ( $localName != $this->mDbkeyform ) {
					return self::makeTitle( NS_SPECIAL, $localName );
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
		return MediaWikiServices::getInstance()->getNamespaceInfo()->
			equals( $this->mNamespace, $ns );
	}

	/**
	 * Returns true if the title is inside one of the specified namespaces.
	 *
	 * @param int|int[] ...$namespaces The namespaces to check for
	 * @return bool
	 * @since 1.19
	 */
	public function inNamespaces( ...$namespaces ) {
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
		return MediaWikiServices::getInstance()->getNamespaceInfo()->
			subjectEquals( $this->mNamespace, $ns );
	}

	/**
	 * Is this Title in a namespace which contains content?
	 * In other words, is this a content page, for the purposes of calculating
	 * statistics, etc?
	 *
	 * @return bool
	 */
	public function isContentPage() {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->
			isContent( $this->mNamespace );
	}

	/**
	 * Would anybody with sufficient privileges be able to move this page?
	 * Some pages just aren't movable.
	 *
	 * @return bool
	 */
	public function isMovable() {
		if (
			!MediaWikiServices::getInstance()->getNamespaceInfo()->
				isMovable( $this->mNamespace ) || $this->isExternal()
		) {
			// Interwiki title or immovable namespace. Hooks don't get to override here
			return false;
		}

		$result = true;
		Hooks::runner()->onTitleIsMovable( $this, $result );
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
		return $this->equals( self::newMainPage() );
	}

	/**
	 * Is this a subpage?
	 *
	 * @return bool
	 */
	public function isSubpage() {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->
			hasSubpages( $this->mNamespace )
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

		return $this->mNamespace == NS_MEDIAWIKI &&
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
	 * Could this MediaWiki namespace page contain custom CSS, JSON, or JavaScript for the
	 * global UI. This is generally true for pages in the MediaWiki namespace having
	 * CONTENT_MODEL_CSS, CONTENT_MODEL_JSON, or CONTENT_MODEL_JAVASCRIPT.
	 *
	 * This method does *not* return true for per-user JS/JSON/CSS. Use isUserConfigPage()
	 * for that!
	 *
	 * Note that this method should not return true for pages that contain and show
	 * "inactive" CSS, JSON, or JS.
	 *
	 * @return bool
	 * @since 1.31
	 */
	public function isSiteConfigPage() {
		return (
			$this->isSiteCssConfigPage()
			|| $this->isSiteJsonConfigPage()
			|| $this->isSiteJsConfigPage()
		);
	}

	/**
	 * Is this a "config" (.css, .json, or .js) sub-page of a user page?
	 *
	 * @return bool
	 * @since 1.31
	 */
	public function isUserConfigPage() {
		return (
			$this->isUserCssConfigPage()
			|| $this->isUserJsonConfigPage()
			|| $this->isUserJsConfigPage()
		);
	}

	/**
	 * Trim down a .css, .json, or .js subpage title to get the corresponding skin name
	 *
	 * @return string Containing skin name from .css, .json, or .js subpage title
	 * @since 1.31
	 */
	public function getSkinFromConfigSubpage() {
		$subpage = explode( '/', $this->mTextform );
		$subpage = $subpage[count( $subpage ) - 1];
		$lastdot = strrpos( $subpage, '.' );
		if ( $lastdot === false ) {
			return $subpage; # Never happens: only called for names ending in '.css'/'.json'/'.js'
		}
		return substr( $subpage, 0, $lastdot );
	}

	/**
	 * Is this a CSS "config" sub-page of a user page?
	 *
	 * @return bool
	 * @since 1.31
	 */
	public function isUserCssConfigPage() {
		return (
			$this->mNamespace == NS_USER
			&& $this->isSubpage()
			&& $this->hasContentModel( CONTENT_MODEL_CSS )
		);
	}

	/**
	 * Is this a JSON "config" sub-page of a user page?
	 *
	 * @return bool
	 * @since 1.31
	 */
	public function isUserJsonConfigPage() {
		return (
			$this->mNamespace == NS_USER
			&& $this->isSubpage()
			&& $this->hasContentModel( CONTENT_MODEL_JSON )
		);
	}

	/**
	 * Is this a JS "config" sub-page of a user page?
	 *
	 * @return bool
	 * @since 1.31
	 */
	public function isUserJsConfigPage() {
		return (
			$this->mNamespace == NS_USER
			&& $this->isSubpage()
			&& $this->hasContentModel( CONTENT_MODEL_JAVASCRIPT )
		);
	}

	/**
	 * Is this a sitewide CSS "config" page?
	 *
	 * @return bool
	 * @since 1.32
	 */
	public function isSiteCssConfigPage() {
		return (
			$this->mNamespace == NS_MEDIAWIKI
			&& (
				$this->hasContentModel( CONTENT_MODEL_CSS )
				// paranoia - a MediaWiki: namespace page with mismatching extension and content
				// model is probably by mistake and might get handled incorrectly (see e.g. T112937)
				|| substr( $this->mDbkeyform, -4 ) === '.css'
			)
		);
	}

	/**
	 * Is this a sitewide JSON "config" page?
	 *
	 * @return bool
	 * @since 1.32
	 */
	public function isSiteJsonConfigPage() {
		return (
			$this->mNamespace == NS_MEDIAWIKI
			&& (
				$this->hasContentModel( CONTENT_MODEL_JSON )
				// paranoia - a MediaWiki: namespace page with mismatching extension and content
				// model is probably by mistake and might get handled incorrectly (see e.g. T112937)
				|| substr( $this->mDbkeyform, -5 ) === '.json'
			)
		);
	}

	/**
	 * Is this a sitewide JS "config" page?
	 *
	 * @return bool
	 * @since 1.31
	 */
	public function isSiteJsConfigPage() {
		return (
			$this->mNamespace == NS_MEDIAWIKI
			&& (
				$this->hasContentModel( CONTENT_MODEL_JAVASCRIPT )
				// paranoia - a MediaWiki: namespace page with mismatching extension and content
				// model is probably by mistake and might get handled incorrectly (see e.g. T112937)
				|| substr( $this->mDbkeyform, -3 ) === '.js'
			)
		);
	}

	/**
	 * Is this a message which can contain raw HTML?
	 *
	 * @return bool
	 * @since 1.32
	 */
	public function isRawHtmlMessage() {
		global $wgRawHtmlMessages;

		if ( !$this->inNamespace( NS_MEDIAWIKI ) ) {
			return false;
		}
		$message = lcfirst( $this->getRootTitle()->getDBkey() );
		return in_array( $message, $wgRawHtmlMessages, true );
	}

	/**
	 * Is this a talk page of some sort?
	 *
	 * @return bool
	 */
	public function isTalkPage() {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->
			isTalk( $this->mNamespace );
	}

	/**
	 * Get a Title object associated with the talk page of this article
	 *
	 * @deprecated since 1.34, use getTalkPageIfDefined() or NamespaceInfo::getTalkPage()
	 *             with NamespaceInfo::canHaveTalkPage(). Note that the new method will
	 *             throw if asked for the talk page of a section-only link, or of an interwiki
	 *             link.
	 * @return Title The object for the talk page
	 * @throws MWException if $target doesn't have talk pages, e.g. because it's in NS_SPECIAL
	 *         or because it's a relative link, or an interwiki link.
	 */
	public function getTalkPage() {
		// NOTE: The equivalent code in NamespaceInfo is less lenient about producing invalid titles.
		//       Instead of failing on invalid titles, let's just log the issue for now.
		//       See the discussion on T227817.

		// Is this the same title?
		$talkNS = MediaWikiServices::getInstance()->getNamespaceInfo()->getTalk( $this->mNamespace );
		if ( $this->mNamespace == $talkNS ) {
			return $this;
		}

		$title = self::makeTitle( $talkNS, $this->mDbkeyform );

		$this->warnIfPageCannotExist( $title, __METHOD__ );

		return $title;
		// TODO: replace the above with the code below:
		// return self::castFromLinkTarget(
		// MediaWikiServices::getInstance()->getNamespaceInfo()->getTalkPage( $this ) );
	}

	/**
	 * Get a Title object associated with the talk page of this article,
	 * if such a talk page can exist.
	 *
	 * @since 1.30
	 *
	 * @return Title|null The object for the talk page,
	 *         or null if no associated talk page can exist, according to canHaveTalkPage().
	 */
	public function getTalkPageIfDefined() {
		if ( !$this->canHaveTalkPage() ) {
			return null;
		}

		return $this->getTalkPage();
	}

	/**
	 * Get a title object associated with the subject page of this
	 * talk page
	 *
	 * @deprecated since 1.34, use NamespaceInfo::getSubjectPage
	 * @return Title The object for the subject page
	 */
	public function getSubjectPage() {
		// Is this the same title?
		$subjectNS = MediaWikiServices::getInstance()->getNamespaceInfo()
			->getSubject( $this->mNamespace );
		if ( $this->mNamespace == $subjectNS ) {
			return $this;
		}
		// NOTE: The equivalent code in NamespaceInfo is less lenient about producing invalid titles.
		//       Instead of failing on invalid titles, let's just log the issue for now.
		//       See the discussion on T227817.
		$title = self::makeTitle( $subjectNS, $this->mDbkeyform );

		$this->warnIfPageCannotExist( $title, __METHOD__ );

		return $title;
		// TODO: replace the above with the code below:
		// return self::castFromLinkTarget(
		// MediaWikiServices::getInstance()->getNamespaceInfo()->getSubjectPage( $this ) );
	}

	/**
	 * @param Title $title
	 * @param string $method
	 *
	 * @return bool whether a warning was issued
	 */
	private function warnIfPageCannotExist( Title $title, $method ) {
		if ( $this->getText() == '' ) {
			wfLogWarning(
				$method . ': called on empty title ' . $this->getFullText() . ', returning '
				. $title->getFullText()
			);

			return true;
		}

		if ( $this->getInterwiki() !== '' ) {
			wfLogWarning(
				$method . ': called on interwiki title ' . $this->getFullText() . ', returning '
				. $title->getFullText()
			);

			return true;
		}

		return false;
	}

	/**
	 * Get the other title for this page, if this is a subject page
	 * get the talk page, if it is a subject page get the talk page
	 *
	 * @deprecated since 1.34, use NamespaceInfo::getAssociatedPage
	 * @since 1.25
	 * @throws MWException If the page doesn't have an other page
	 * @return Title
	 */
	public function getOtherPage() {
		// NOTE: Depend on the methods in this class instead of their equivalent in NamespaceInfo,
		//       until their semantics has become exactly the same.
		//       See the discussion on T227817.
		if ( $this->isSpecialPage() ) {
			throw new MWException( 'Special pages cannot have other pages' );
		}
		if ( $this->isTalkPage() ) {
			return $this->getSubjectPage();
		} else {
			if ( !$this->canHaveTalkPage() ) {
				throw new MWException( "{$this->getPrefixedText()} does not have an other page" );
			}
			return $this->getTalkPage();
		}
		// TODO: replace the above with the code below:
		// return self::castFromLinkTarget(
		// MediaWikiServices::getInstance()->getNamespaceInfo()->getAssociatedPage( $this ) );
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
	 *
	 * @return string Fragment in URL form
	 */
	public function getFragmentForURL() {
		if ( !$this->hasFragment() ) {
			return '';
		} elseif ( $this->isExternal() ) {
			// Note: If the interwiki is unknown, it's treated as a namespace on the local wiki,
			// so we treat it like a local interwiki.
			$interwiki = self::getInterwikiLookup()->fetch( $this->mInterwiki );
			if ( $interwiki && !$interwiki->isLocal() ) {
				return '#' . Sanitizer::escapeIdForExternalInterwiki( $this->mFragment );
			}
		}

		return '#' . Sanitizer::escapeIdForLink( $this->mFragment );
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
	 * @internal
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
			$this->mNamespace,
			$this->getText(),
			$fragment,
			$this->mInterwiki
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

		if ( $this->mNamespace != 0 ) {
			$nsText = $this->getNsText();

			if ( $nsText === false ) {
				// See T165149. Awkward, but better than erroneously linking to the main namespace.
				$nsText = MediaWikiServices::getInstance()->getContentLanguage()->
					getNsText( NS_SPECIAL ) . ":Badtitle/NS{$this->mNamespace}";
			}

			$p .= $nsText . ':';
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
		if ( $this->prefixedText === null ) {
			$s = $this->prefix( $this->mTextform );
			$s = strtr( $s, '_', ' ' );
			$this->prefixedText = $s;
		}
		return $this->prefixedText;
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
			$text .= '#' . $this->mFragment;
		}
		return $text;
	}

	/**
	 * Finds the first or last subpage divider (slash) in the string.
	 * Any leading sequence of slashes is ignored, since it does not divide
	 * two parts of the string. Considering leading slashes dividers would
	 * result in empty root title or base title (T229443).
	 *
	 * Note that trailing slashes are considered dividers, and empty subpage
	 * names are allowed.
	 *
	 * @param string $text
	 * @param int $dir -1 for the last or +1 for the first divider.
	 *
	 * @return false|int
	 */
	private function findSubpageDivider( $text, $dir ) {
		$top = strlen( $text ) - 1;
		$bottom = 0;

		while ( $bottom < $top && $text[$bottom] === '/' ) {
			$bottom++;
		}

		if ( $top < $bottom ) {
			return false;
		}

		if ( $dir > 0 ) {
			$idx = $bottom;
			while ( $idx <= $top && $text[$idx] !== '/' ) {
				$idx++;
			}
		} else {
			$idx = $top;
			while ( $idx > $bottom && $text[$idx] !== '/' ) {
				$idx--;
			}
		}

		if ( $idx < $bottom || $idx > $top ) {
			return false;
		}

		if ( $idx < 1 ) {
			return false;
		}

		return $idx;
	}

	/**
	 * Whether this Title's namespace has subpages enabled.
	 * @return bool
	 */
	private function hasSubpagesEnabled() {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->
			hasSubpages( $this->mNamespace );
	}

	/**
	 * Get the root page name text without a namespace, i.e. the leftmost part before any slashes
	 *
	 * @note the return value may contain trailing whitespace and is thus
	 * not safe for use with makeTitle or TitleValue.
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
		$text = $this->getText();
		if ( !$this->hasSubpagesEnabled() ) {
			return $text;
		}

		$firstSlashPos = $this->findSubpageDivider( $text, +1 );
		// Don't discard the real title if there's no subpage involved
		if ( $firstSlashPos === false ) {
			return $text;
		}

		return substr( $text, 0, $firstSlashPos );
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
		$title = self::makeTitleSafe( $this->mNamespace, $this->getRootText() );
		Assert::postcondition(
			$title !== null,
			'makeTitleSafe() should always return a Title for the text returned by getRootText().'
		);
		return $title;
	}

	/**
	 * Get the base page name without a namespace, i.e. the part before the subpage name
	 *
	 * @note the return value may contain trailing whitespace and is thus
	 * not safe for use with makeTitle or TitleValue.
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
		$text = $this->getText();
		if ( !$this->hasSubpagesEnabled() ) {
			return $text;
		}

		$lastSlashPos = $this->findSubpageDivider( $text, -1 );
		// Don't discard the real title if there's no subpage involved
		if ( $lastSlashPos === false ) {
			return $text;
		}

		return substr( $text, 0, $lastSlashPos );
	}

	/**
	 * Get the base page name title, i.e. the part before the subpage name.
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
		$title = self::makeTitleSafe( $this->mNamespace, $this->getBaseText() );
		Assert::postcondition(
			$title !== null,
			'makeTitleSafe() should always return a Title for the text returned by getBaseText().'
		);
		return $title;
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
		$text = $this->getText();
		if ( !$this->hasSubpagesEnabled() ) {
			return $text;
		}

		$lastSlashPos = $this->findSubpageDivider( $text, -1 );
		if ( $lastSlashPos === false ) {
			// T256922 - Return the title text if no subpages
			return $text;
		}
		return substr( $text, $lastSlashPos + 1 );
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
	 * @return Title|null Subpage title, or null on an error
	 * @since 1.20
	 */
	public function getSubpage( $text ) {
		return self::makeTitleSafe(
			$this->mNamespace,
			$this->getText() . '/' . $text,
			'',
			$this->mInterwiki
		);
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
	 * @param string|string[]|bool $query2
	 * @return string
	 */
	private static function fixUrlQueryArgs( $query, $query2 = false ) {
		if ( $query2 !== false ) {
			wfDeprecatedMsg( "Title::get{Canonical,Full,Link,Local,Internal}URL " .
				"method called with a second parameter is deprecated since MediaWiki 1.19. " .
				"Add your parameter to an array passed as the first parameter.", "1.19" );
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
	 * @param string|array $query
	 * @param string|string[]|bool $query2
	 * @param string|int|null $proto Protocol type to use in URL
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
		Hooks::runner()->onGetFullURL( $this, $url, $query );
		return $url;
	}

	/**
	 * Get a url appropriate for making redirects based on an untrusted url arg
	 *
	 * This is basically the same as getFullUrl(), but in the case of external
	 * interwikis, we send the user to a landing page, to prevent possible
	 * phishing attacks and the like.
	 *
	 * @note Uses current protocol by default, since technically relative urls
	 *   aren't allowed in redirects per HTTP spec, so this is not suitable for
	 *   places where the url gets cached, as might pollute between
	 *   https and non-https users.
	 * @see self::getLocalURL for the arguments.
	 * @param array|string $query
	 * @param string $proto Protocol type to use in URL
	 * @return string A url suitable to use in an HTTP location header.
	 */
	public function getFullUrlForRedirect( $query = '', $proto = PROTO_CURRENT ) {
		$target = $this;
		if ( $this->isExternal() ) {
			$target = SpecialPage::getTitleFor(
				'GoToInterwiki',
				$this->getPrefixedDBkey()
			);
		}
		return $target->getFullURL( $query, false, $proto );
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
	 *   e.g., [ 'action' => 'edit' ] (keys and values will be URL-escaped).
	 *   Some query patterns will trigger various shorturl path replacements.
	 * @param string|string[]|bool $query2 An optional secondary query array. This one MUST
	 *   be an array. If a string is passed it will be interpreted as a deprecated
	 *   variant argument and urlencoded into a variant= argument.
	 *   This second query argument will be added to the $query
	 *   The second parameter is deprecated since 1.19. Pass it as a key,value
	 *   pair in the first parameter array instead.
	 *
	 * @return string String of the URL.
	 */
	public function getLocalURL( $query = '', $query2 = false ) {
		global $wgArticlePath, $wgScript, $wgServer, $wgRequest, $wgMainPageIsDomainRoot;

		$query = self::fixUrlQueryArgs( $query, $query2 );

		$interwiki = self::getInterwikiLookup()->fetch( $this->mInterwiki );
		if ( $interwiki ) {
			$namespace = $this->getNsText();
			if ( $namespace != '' ) {
				# Can this actually happen? Interwikis shouldn't be parsed.
				# Yes! It can in interwiki transclusion. But... it probably shouldn't.
				$namespace .= ':';
			}
			$url = $interwiki->getURL( $namespace . $this->mDbkeyform );
			$url = wfAppendQuery( $url, $query );
		} else {
			$dbkey = wfUrlencode( $this->getPrefixedDBkey() );
			if ( $query == '' ) {
				$url = str_replace( '$1', $dbkey, $wgArticlePath );
				Hooks::runner()->onGetLocalURL__Article( $this, $url );
			} else {
				global $wgVariantArticlePath, $wgActionPaths;
				$url = false;
				$matches = [];

				$articlePaths = PathRouter::getActionPaths( $wgActionPaths, $wgArticlePath );

				if ( $articlePaths
					&& preg_match( '/^(.*&|)action=([^&]*)(&(.*)|)$/', $query, $matches )
				) {
					$action = urldecode( $matches[2] );
					if ( isset( $articlePaths[$action] ) ) {
						$query = $matches[1];
						if ( isset( $matches[4] ) ) {
							$query .= $matches[4];
						}
						$url = str_replace( '$1', $dbkey, $articlePaths[$action] );
						if ( $query != '' ) {
							$url = wfAppendQuery( $url, $query );
						}
					}
				}

				if ( $url === false
					&& $wgVariantArticlePath
					&& preg_match( '/^variant=([^&]*)$/', $query, $matches )
					&& $this->getPageLanguage()->equals(
						MediaWikiServices::getInstance()->getContentLanguage() )
					&& $this->getPageLanguageConverter()->hasVariants()
				) {
					$variant = urldecode( $matches[1] );
					if ( $this->getPageLanguageConverter()->hasVariant( $variant ) ) {
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
			Hooks::runner()->onGetLocalURL__Internal( $this, $url, $query );

			// @todo FIXME: This causes breakage in various places when we
			// actually expected a local URL and end up with dupe prefixes.
			if ( $wgRequest->getVal( 'action' ) == 'render' ) {
				$url = $wgServer . $url;
			}
		}

		if ( $wgMainPageIsDomainRoot && $this->isMainPage() && $query === '' ) {
			return '/';
		}

		Hooks::runner()->onGetLocalURL( $this, $url, $query );
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
	 * @param string|array $query
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
	 * @param string|array $query
	 * @param string|bool $query2 Deprecated
	 * @return string The URL
	 */
	public function getInternalURL( $query = '', $query2 = false ) {
		global $wgInternalServer, $wgServer;
		$query = self::fixUrlQueryArgs( $query, $query2 );
		$server = $wgInternalServer !== false ? $wgInternalServer : $wgServer;
		$url = wfExpandUrl( $server . $this->getLocalURL( $query ), PROTO_HTTP );
		Hooks::runner()->onGetInternalURL( $this, $url, $query );
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
	 * @param string|array $query
	 * @param string|bool $query2 Deprecated
	 * @return string The URL
	 * @since 1.18
	 */
	public function getCanonicalURL( $query = '', $query2 = false ) {
		$query = self::fixUrlQueryArgs( $query, $query2 );
		$url = wfExpandUrl( $this->getLocalURL( $query ) . $this->getFragmentForURL(), PROTO_CANONICAL );
		Hooks::runner()->onGetCanonicalURL( $this, $url, $query );
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
	 * @param User|null $user User to check (since 1.19); $wgUser will be used if not provided.
	 *
	 * @return bool
	 * @throws Exception
	 *
	 * @deprecated since 1.33,
	 * use MediaWikiServices::getInstance()->getPermissionManager()->quickUserCan(..) instead
	 *
	 */
	public function quickUserCan( $action, $user = null ) {
		wfDeprecated( __METHOD__, '1.33' );
		if ( !$user instanceof User ) {
			global $wgUser;
			$user = $wgUser;
		}
		return MediaWikiServices::getInstance()->getPermissionManager()
			->quickUserCan( $action, $user, $this );
	}

	/**
	 * Can $user perform $action on this page?
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User|null $user User to check (since 1.19); $wgUser will be used if not
	 *   provided.
	 * @param string $rigor Same format as Title::getUserPermissionsErrors()
	 *
	 * @return bool
	 * @throws Exception
	 *
	 * @deprecated since 1.33,
	 * use MediaWikiServices::getInstance()->getPermissionManager()->userCan(..) instead
	 *
	 */
	public function userCan( $action, $user = null, $rigor = PermissionManager::RIGOR_SECURE ) {
		wfDeprecated( __METHOD__, '1.33' );
		if ( !$user instanceof User ) {
			global $wgUser;
			$user = $wgUser;
		}

		// TODO: this is for b/c, eventually will be removed
		if ( $rigor === true ) {
			$rigor = PermissionManager::RIGOR_SECURE; // b/c
		} elseif ( $rigor === false ) {
			$rigor = PermissionManager::RIGOR_QUICK; // b/c
		}

		return MediaWikiServices::getInstance()->getPermissionManager()
			->userCan( $action, $user, $this, $rigor );
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
	 *
	 * @return array[] Array of arrays of the arguments to wfMessage to explain permissions problems.
	 * @throws Exception
	 *
	 * @deprecated since 1.33,
	 * use MediaWikiServices::getInstance()->getPermissionManager()->getPermissionErrors()
	 *
	 */
	public function getUserPermissionsErrors(
		$action, $user, $rigor = PermissionManager::RIGOR_SECURE, $ignoreErrors = []
	) {
		wfDeprecated( __METHOD__, '1.33' );

		// TODO: this is for b/c, eventually will be removed
		if ( $rigor === true ) {
			$rigor = PermissionManager::RIGOR_SECURE; // b/c
		} elseif ( $rigor === false ) {
			$rigor = PermissionManager::RIGOR_QUICK; // b/c
		}

		return MediaWikiServices::getInstance()->getPermissionManager()
			->getPermissionErrors( $action, $user, $this, $rigor, $ignoreErrors );
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

		if ( $this->mNamespace != NS_FILE ) {
			# Remove the upload restriction for non-file titles
			$types = array_diff( $types, [ 'upload' ] );
		}

		Hooks::runner()->onTitleGetRestrictionTypes( $this, $types );

		wfDebug( __METHOD__ . ': applicable restrictions to [[' .
			$this->getPrefixedText() . ']] are {' . implode( ',', $types ) . "}" );

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
		$protection = $this->getTitleProtectionInternal();
		if ( $protection ) {
			if ( $protection['permission'] == 'sysop' ) {
				$protection['permission'] = 'editprotected'; // B/C
			}
			if ( $protection['permission'] == 'autoconfirmed' ) {
				$protection['permission'] = 'editsemiprotected'; // B/C
			}
		}
		return $protection;
	}

	/**
	 * Fetch title protection settings
	 *
	 * To work correctly, $this->loadRestrictions() needs to have access to the
	 * actual protections in the database without munging 'sysop' =>
	 * 'editprotected' and 'autoconfirmed' => 'editsemiprotected'. Other
	 * callers probably want $this->getTitleProtection() instead.
	 *
	 * @return array|bool
	 */
	protected function getTitleProtectionInternal() {
		// Can't protect pages in special namespaces
		if ( $this->mNamespace < 0 ) {
			return false;
		}

		// Can't protect pages that exist.
		if ( $this->exists() ) {
			return false;
		}

		if ( $this->mTitleProtection === null ) {
			$dbr = wfGetDB( DB_REPLICA );
			$commentStore = CommentStore::getStore();
			$commentQuery = $commentStore->getJoin( 'pt_reason' );
			$res = $dbr->select(
				[ 'protected_titles' ] + $commentQuery['tables'],
				[
					'user' => 'pt_user',
					'expiry' => 'pt_expiry',
					'permission' => 'pt_create_perm'
				] + $commentQuery['fields'],
				[ 'pt_namespace' => $this->mNamespace, 'pt_title' => $this->mDbkeyform ],
				__METHOD__,
				[],
				$commentQuery['joins']
			);

			// fetchRow returns false if there are no rows.
			$row = $dbr->fetchRow( $res );
			if ( $row ) {
				$this->mTitleProtection = [
					'user' => $row['user'],
					'expiry' => $dbr->decodeExpiry( $row['expiry'] ),
					'permission' => $row['permission'],
					'reason' => $commentStore->getComment( 'pt_reason', $row )->text,
				];
			} else {
				$this->mTitleProtection = false;
			}
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
			[ 'pt_namespace' => $this->mNamespace, 'pt_title' => $this->mDbkeyform ],
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
	 * @deprecated since 1.34 Don't use this function in new code.
	 * @param User $user User object to check permissions
	 * @return bool
	 */
	public function isNamespaceProtected( User $user ) {
		global $wgNamespaceProtection;

		if ( isset( $wgNamespaceProtection[$this->mNamespace] ) ) {
			$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
			foreach ( (array)$wgNamespaceProtection[$this->mNamespace] as $right ) {
				if ( !$permissionManager->userHasRight( $user, $right ) ) {
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
		list( $isCascadeProtected, ) = $this->getCascadeProtectionSources( false );
		return $isCascadeProtected;
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

		if ( $this->mNamespace == NS_FILE ) {
			$tables = [ 'imagelinks', 'page_restrictions' ];
			$where_clauses = [
				'il_to' => $this->mDbkeyform,
				'il_from=pr_page',
				'pr_cascade' => 1
			];
		} else {
			$tables = [ 'templatelinks', 'page_restrictions' ];
			$where_clauses = [
				'tl_namespace' => $this->mNamespace,
				'tl_title' => $this->mDbkeyform,
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
					$sources[$page_id] = self::makeTitle( $page_ns, $page_title );
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
		return $this->mRestrictions[$action] ?? [];
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
		return $this->mRestrictionsExpiry[$action] ?? false;
	}

	/**
	 * Returns cascading restrictions for the current article
	 *
	 * @return bool
	 */
	public function areRestrictionsCascading() {
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
	 * @param string|null $oldFashionedRestrictions Comma-separated set of permission keys
	 * indicating who can move or edit the page from the page table, (pre 1.10) rows.
	 * Edit and move sections are separated by a colon
	 * Example: "edit=autoconfirmed,sysop:move=sysop"
	 */
	public function loadRestrictionsFromRows( $rows, $oldFashionedRestrictions = null ) {
		// This function will only read rows from a table that we migrated away
		// from before adding READ_LATEST support to loadRestrictions, so we
		// don't need to support reading from DB_MASTER here.
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
			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			$linkCache->addLinkObj( $this ); # in case we already had an article ID
			$this->mOldRestrictions = $linkCache->getGoodLinkFieldObj( $this, 'restrictions' );
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

				$expiry = $dbr->decodeExpiry( $row->pr_expiry );

				// Only apply the restrictions if they haven't expired!
				if ( !$expiry || $expiry > $now ) {
					$this->mRestrictionsExpiry[$row->pr_type] = $expiry;
					$this->mRestrictions[$row->pr_type] = explode( ',', trim( $row->pr_level ) );

					$this->mCascadeRestriction = $this->mCascadeRestriction || $row->pr_cascade;
				}
			}
		}

		$this->mRestrictionsLoaded = true;
	}

	/**
	 * Load restrictions from the page_restrictions table
	 *
	 * @param string|null $oldFashionedRestrictions Comma-separated set of permission keys
	 * indicating who can move or edit the page from the page table, (pre 1.10) rows.
	 * Edit and move sections are separated by a colon
	 * Example: "edit=autoconfirmed,sysop:move=sysop"
	 * @param int $flags A bit field. If self::READ_LATEST is set, skip replicas and read
	 *  from the master DB.
	 */
	public function loadRestrictions( $oldFashionedRestrictions = null, $flags = 0 ) {
		$readLatest = DBAccessObjectUtils::hasFlags( $flags, self::READ_LATEST );
		if ( $this->mRestrictionsLoaded && !$readLatest ) {
			return;
		}

		$id = $this->getArticleID( $flags );
		if ( $id ) {
			$fname = __METHOD__;
			$loadRestrictionsFromDb = function ( IDatabase $dbr ) use ( $fname, $id ) {
				return iterator_to_array(
					$dbr->select(
						'page_restrictions',
						[ 'pr_type', 'pr_expiry', 'pr_level', 'pr_cascade' ],
						[ 'pr_page' => $id ],
						$fname
					)
				);
			};

			if ( $readLatest ) {
				$dbr = wfGetDB( DB_MASTER );
				$rows = $loadRestrictionsFromDb( $dbr );
			} else {
				$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
				$rows = $cache->getWithSetCallback(
					// Page protections always leave a new null revision
					$cache->makeKey( 'page-restrictions', 'v1', $id, $this->getLatestRevID() ),
					$cache::TTL_DAY,
					function ( $curValue, &$ttl, array &$setOpts ) use ( $loadRestrictionsFromDb ) {
						$dbr = wfGetDB( DB_REPLICA );

						$setOpts += Database::getCacheSetOptions( $dbr );
						$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
						if ( $lb->hasOrMadeRecentMasterChanges() ) {
							// @TODO: cleanup Title cache and caller assumption mess in general
							$ttl = WANObjectCache::TTL_UNCACHEABLE;
						}

						return $loadRestrictionsFromDb( $dbr );
					}
				);
			}

			$this->loadRestrictionsFromRows( $rows, $oldFashionedRestrictions );
		} else {
			$title_protection = $this->getTitleProtectionInternal();

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
	public static function purgeExpiredRestrictions() {
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
		if (
			!MediaWikiServices::getInstance()->getNamespaceInfo()->
				hasSubpages( $this->mNamespace )
		) {
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
				$this->mHasSubpages = (bool)$subpages->current();
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
		if (
			!MediaWikiServices::getInstance()->getNamespaceInfo()->
				hasSubpages( $this->mNamespace )
		) {
			return [];
		}

		$dbr = wfGetDB( DB_REPLICA );
		$conds = [ 'page_namespace' => $this->mNamespace ];
		$conds[] = 'page_title ' . $dbr->buildLike( $this->mDbkeyform . '/', $dbr->anyString() );
		$options = [];
		if ( $limit > -1 ) {
			$options['LIMIT'] = $limit;
		}
		return TitleArray::newFromResult(
			$dbr->select( 'page',
				[ 'page_id', 'page_namespace', 'page_title', 'page_is_redirect' ],
				$conds,
				__METHOD__,
				$options
			)
		);
	}

	/**
	 * Is there a version of this page in the deletion archive?
	 *
	 * @return int The number of archived revisions
	 */
	public function isDeleted() {
		if ( $this->mNamespace < 0 ) {
			$n = 0;
		} else {
			$dbr = wfGetDB( DB_REPLICA );

			$n = $dbr->selectField( 'archive', 'COUNT(*)',
				[ 'ar_namespace' => $this->mNamespace, 'ar_title' => $this->mDbkeyform ],
				__METHOD__
			);
			if ( $this->mNamespace == NS_FILE ) {
				$n += $dbr->selectField( 'filearchive', 'COUNT(*)',
					[ 'fa_name' => $this->mDbkeyform ],
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
		if ( $this->mNamespace < 0 ) {
			return false;
		}
		$dbr = wfGetDB( DB_REPLICA );
		$deleted = (bool)$dbr->selectField( 'archive', '1',
			[ 'ar_namespace' => $this->mNamespace, 'ar_title' => $this->mDbkeyform ],
			__METHOD__
		);
		if ( !$deleted && $this->mNamespace == NS_FILE ) {
			$deleted = (bool)$dbr->selectField( 'filearchive', '1',
				[ 'fa_name' => $this->mDbkeyform ],
				__METHOD__
			);
		}
		return $deleted;
	}

	/**
	 * Get the article ID for this Title from the link cache,
	 * adding it if necessary
	 *
	 * @param int $flags Either a bitfield of class READ_* constants or GAID_FOR_UPDATE
	 * @return int The ID
	 */
	public function getArticleID( $flags = 0 ) {
		if ( $this->mNamespace < 0 ) {
			$this->mArticleID = 0;

			return $this->mArticleID;
		}

		if ( $flags & self::GAID_FOR_UPDATE ) {
			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			$oldUpdate = $linkCache->forUpdate( true );
			$linkCache->clearLink( $this );
			$this->mArticleID = $linkCache->addLinkObj( $this );
			$linkCache->forUpdate( $oldUpdate );
		} elseif ( DBAccessObjectUtils::hasFlags( $flags, self::READ_LATEST ) ) {
			// If mArticleID is >0, pageCond() will use it, making it impossible
			// for the call below to return a different result, e.g. after a
			// page move.
			$this->mArticleID = -1;
			$this->mArticleID = (int)$this->loadFieldFromDB( 'page_id', $flags );
		} elseif ( $this->mArticleID == -1 ) {
			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			$this->mArticleID = $linkCache->addLinkObj( $this );
		}

		return $this->mArticleID;
	}

	/**
	 * Is this an article that is a redirect page?
	 * Uses link cache, adding it if necessary
	 *
	 * @param int $flags Either a bitfield of class READ_* constants or GAID_FOR_UPDATE
	 * @return bool
	 */
	public function isRedirect( $flags = 0 ) {
		if ( DBAccessObjectUtils::hasFlags( $flags, self::READ_LATEST ) ) {
			$this->mRedirect = (bool)$this->loadFieldFromDB( 'page_is_redirect', $flags );
		} elseif ( $this->mRedirect === null ) {
			if ( $this->getArticleID( $flags ) ) {
				$linkCache = MediaWikiServices::getInstance()->getLinkCache();
				$linkCache->addLinkObj( $this ); // in case we already had an article ID
				// Note that LinkCache returns null if it thinks the page does not exist;
				// always trust the state of LinkCache over that of this Title instance.
				$this->mRedirect = (bool)$linkCache->getGoodLinkFieldObj( $this, 'redirect' );
			} else {
				$this->mRedirect = false;
			}
		}

		return $this->mRedirect;
	}

	/**
	 * What is the length of this page?
	 * Uses link cache, adding it if necessary
	 *
	 * @param int $flags Either a bitfield of class READ_* constants or GAID_FOR_UPDATE
	 * @return int
	 */
	public function getLength( $flags = 0 ) {
		if ( DBAccessObjectUtils::hasFlags( $flags, self::READ_LATEST ) ) {
			$this->mLength = (int)$this->loadFieldFromDB( 'page_len', $flags );
		} else {
			if ( $this->mLength != -1 ) {
				return $this->mLength;
			} elseif ( !$this->getArticleID( $flags ) ) {
				$this->mLength = 0;
				return $this->mLength;
			}

			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			$linkCache->addLinkObj( $this ); // in case we already had an article ID
			// Note that LinkCache returns null if it thinks the page does not exist;
			// always trust the state of LinkCache over that of this Title instance.
			$this->mLength = (int)$linkCache->getGoodLinkFieldObj( $this, 'length' );
		}

		return $this->mLength;
	}

	/**
	 * What is the page_latest field for this page?
	 *
	 * @param int $flags Either a bitfield of class READ_* constants or GAID_FOR_UPDATE
	 * @return int Int or 0 if the page doesn't exist
	 */
	public function getLatestRevID( $flags = 0 ) {
		if ( DBAccessObjectUtils::hasFlags( $flags, self::READ_LATEST ) ) {
			$this->mLatestID = (int)$this->loadFieldFromDB( 'page_latest', $flags );
		} else {
			if ( $this->mLatestID !== false ) {
				return (int)$this->mLatestID;
			} elseif ( !$this->getArticleID( $flags ) ) {
				$this->mLatestID = 0;

				return $this->mLatestID;
			}

			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			$linkCache->addLinkObj( $this ); // in case we already had an article ID
			// Note that LinkCache returns null if it thinks the page does not exist;
			// always trust the state of LinkCache over that of this Title instance.
			$this->mLatestID = (int)$linkCache->getGoodLinkFieldObj( $this, 'revision' );
		}

		return $this->mLatestID;
	}

	/**
	 * Inject a page ID, reset DB-loaded fields, and clear the link cache for this title
	 *
	 * This can be called on page insertion to allow loading of the new page_id without
	 * having to create a new Title instance. Likewise with deletion.
	 *
	 * This is also used during page moves, to reflect the change in the relationship
	 * between article ID and title text.
	 *
	 * @note This overrides Title::setContentModel()
	 *
	 * @param int|bool $id Page ID, 0 for non-existant, or false for "unknown" (lazy-load)
	 */
	public function resetArticleID( $id ) {
		if ( $id === false ) {
			$this->mArticleID = -1;
		} else {
			$this->mArticleID = (int)$id;
		}
		$this->mRestrictionsLoaded = false;
		$this->mRestrictions = [];
		$this->mOldRestrictions = false;
		$this->mRedirect = null;
		$this->mLength = -1;
		$this->mLatestID = false;
		$this->mContentModel = false;
		$this->mForcedContentModel = false;
		$this->mEstimateRevisions = null;
		$this->mPageLanguage = null;
		$this->mDbPageLanguage = false;
		$this->mIsBigDeletion = null;

		MediaWikiServices::getInstance()->getLinkCache()->clearLink( $this );
	}

	public static function clearCaches() {
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();
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
		$services = MediaWikiServices::getInstance();
		if ( $services->getNamespaceInfo()->isCapitalized( $ns ) ) {
			return $services->getContentLanguage()->ucfirst( $text );
		} else {
			return $text;
		}
	}

	/**
	 * Secure and split - main initialisation function for this object
	 *
	 * Assumes that $text is urldecoded
	 * and uses underscores, but not otherwise munged.  This function
	 * removes illegal characters, splits off the interwiki and
	 * namespace prefixes, sets the other forms, and canonicalizes
	 * everything.
	 *
	 * If this method returns normally, the Title is valid.
	 *
	 * @param string $text
	 * @param int|null $defaultNamespace
	 *
	 * @throws MalformedTitleException On malformed titles
	 */
	private function secureAndSplit( $text, $defaultNamespace = null ) {
		if ( $defaultNamespace === null ) {
			$defaultNamespace = $this->mDefaultNamespace;
		}

		// @note: splitTitleString() is a temporary hack to allow MediaWikiTitleCodec to share
		//        the parsing code with Title, while avoiding massive refactoring.
		// @todo: get rid of secureAndSplit, refactor parsing code.
		// @note: getTitleParser() returns a TitleParser implementation which does not have a
		//        splitTitleString method, but the only implementation (MediaWikiTitleCodec) does
		/** @var MediaWikiTitleCodec $titleCodec */
		$titleCodec = MediaWikiServices::getInstance()->getTitleParser();
		'@phan-var MediaWikiTitleCodec $titleCodec';
		// MalformedTitleException can be thrown here
		$parts = $titleCodec->splitTitleString( $text, $defaultNamespace );

		# Fill fields
		$this->setFragment( '#' . $parts['fragment'] );
		$this->mInterwiki = $parts['interwiki'];
		$this->mLocalInterwiki = $parts['local_interwiki'];
		$this->mNamespace = $parts['namespace'];
		$this->mDefaultNamespace = $defaultNamespace;

		$this->mDbkeyform = $parts['dbkey'];
		$this->mUrlform = wfUrlencode( $this->mDbkeyform );
		$this->mTextform = strtr( $this->mDbkeyform, '_', ' ' );

		// splitTitleString() guarantees that this title is valid.
		$this->mIsValid = true;

		# We already know that some pages won't be in the database!
		if ( $this->isExternal() || $this->isSpecialPage() || $this->mTextform === '' ) {
			$this->mArticleID = 0;
		}
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
				"{$prefix}_namespace" => $this->mNamespace,
				"{$prefix}_title" => $this->mDbkeyform ],
			__METHOD__,
			$options
		);

		$retVal = [];
		if ( $res->numRows() ) {
			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			foreach ( $res as $row ) {
				$titleObj = self::makeTitle( $row->page_namespace, $row->page_title );
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

		$pageQuery = WikiPage::getQueryInfo();
		$res = $db->select(
			[ $table, 'nestpage' => $pageQuery['tables'] ],
			array_merge(
				[ $blNamespace, $blTitle ],
				$pageQuery['fields']
			),
			[ "{$prefix}_from" => $id ],
			__METHOD__,
			$options,
			[ 'nestpage' => [
				'LEFT JOIN',
				[ "page_namespace=$blNamespace", "page_title=$blTitle" ]
			] ] + $pageQuery['joins']
		);

		$retVal = [];
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();
		foreach ( $res as $row ) {
			if ( $row->page_id ) {
				$titleObj = self::newFromRow( $row );
			} else {
				$titleObj = self::makeTitle( $row->$blNamespace, $row->$blTitle );
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
			$retVal[] = self::makeTitle( $row->pl_namespace, $row->pl_title );
		}
		return $retVal;
	}

	/**
	 * Get a list of URLs to purge from the CDN cache when this page changes.
	 *
	 * @deprecated 1.35 Use HtmlCacheUpdater
	 * @return string[] Array of String the URLs
	 */
	public function getCdnUrls() {
		$htmlCache = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		return $htmlCache->getUrls( $this );
	}

	/**
	 * Purge all applicable CDN URLs
	 * @deprecated 1.35 Use HtmlCacheUpdater
	 */
	public function purgeSquid() {
		$htmlCache = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		$htmlCache->purgeTitleUrls( $this, $htmlCache::PURGE_INTENT_TXROUND_REFLECTED );
	}

	/**
	 * Locks the page row and check if this page is single revision redirect
	 *
	 * This updates the cached fields of this instance via Title::loadFromRow()
	 *
	 * @return bool
	 */
	public function isSingleRevRedirect() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		$row = $dbw->selectRow(
			'page',
			self::getSelectFields(),
			$this->pageCond(),
			__METHOD__,
			[ 'FOR UPDATE' ]
		);
		// Update the cached fields
		$this->loadFromRow( $row );

		if ( $this->mRedirect && $this->mLatestID ) {
			$isSingleRevRedirect = !$dbw->selectField(
				'revision',
				'1',
				[ 'rev_page' => $this->mArticleID,  'rev_id != ' . (int)$this->mLatestID ],
				__METHOD__,
				[ 'FOR UPDATE' ]
			);
		} else {
			$isSingleRevRedirect = false;
		}

		$dbw->endAtomic( __METHOD__ );

		return $isSingleRevRedirect;
	}

	/**
	 * Get categories to which this Title belongs and return an array of
	 * categories' names.
	 *
	 * @return array Array of parents in the form:
	 *     $parent => $currentarticle
	 */
	public function getParentCategories() {
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
			$contLang = MediaWikiServices::getInstance()->getContentLanguage();
			foreach ( $res as $row ) {
				// $data[] = Title::newFromText( $contLang->getNsText ( NS_CATEGORY ).':'.$row->cl_to);
				$data[$contLang->getNsText( NS_CATEGORY ) . ':' . $row->cl_to] =
					$this->getFullText();
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
					$nt = self::newFromText( $parent );
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
	 * Get next/previous revision ID relative to another revision ID
	 * @param int $revId Revision ID. Get the revision that was before this one.
	 * @param int $flags Bitfield of class READ_* constants
	 * @param string $dir 'next' or 'prev'
	 * @return int|bool New revision ID, or false if none exists
	 */
	private function getRelativeRevisionID( $revId, $flags, $dir ) {
		$rl = MediaWikiServices::getInstance()->getRevisionLookup();
		$rev = $rl->getRevisionById( $revId, $flags );
		if ( !$rev ) {
			return false;
		}

		$oldRev = ( $dir === 'next' )
			? $rl->getNextRevision( $rev, $flags )
			: $rl->getPreviousRevision( $rev, $flags );

		return $oldRev ? $oldRev->getId() : false;
	}

	/**
	 * Get the revision ID of the previous revision
	 *
	 * @deprecated since 1.34, use RevisionLookup::getPreviousRevision
	 * @param int $revId Revision ID. Get the revision that was before this one.
	 * @param int $flags Bitfield of class READ_* constants
	 * @return int|bool Old revision ID, or false if none exists
	 */
	public function getPreviousRevisionID( $revId, $flags = 0 ) {
		return $this->getRelativeRevisionID( $revId, $flags, 'prev' );
	}

	/**
	 * Get the revision ID of the next revision
	 *
	 * @deprecated since 1.34, use RevisionLookup::getNextRevision
	 * @param int $revId Revision ID. Get the revision that was after this one.
	 * @param int $flags Bitfield of class READ_* constants
	 * @return int|bool Next revision ID, or false if none exists
	 */
	public function getNextRevisionID( $revId, $flags = 0 ) {
		return $this->getRelativeRevisionID( $revId, $flags, 'next' );
	}

	/**
	 * Get the first revision of the page
	 *
	 * @deprecated since 1.35. Use RevisionLookup::getFirstRevision instead.
	 * @param int $flags Bitfield of class READ_* constants
	 * @return Revision|null If page doesn't exist
	 */
	public function getFirstRevision( $flags = 0 ) {
		wfDeprecated( __METHOD__, '1.35' );
		$flags |= ( $flags & self::GAID_FOR_UPDATE ) ? self::READ_LATEST : 0; // b/c
		$rev = MediaWikiServices::getInstance()
			->getRevisionLookup()
			->getFirstRevision( $this, $flags );
		return $rev ? new Revision( $rev ) : null;
	}

	/**
	 * Get the oldest revision timestamp of this page
	 *
	 * @deprecated since 1.35. Use RevisionLookup::getFirstRevision instead.
	 * @param int $flags Bitfield of class READ_* constants
	 * @return string|null MW timestamp
	 */
	public function getEarliestRevTime( $flags = 0 ) {
		$rev = MediaWikiServices::getInstance()
			->getRevisionLookup()
			->getFirstRevision( $this, $flags );
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
	 * @deprecated since 1.35 Use RevisionStore::countRevisionsBetween instead.
	 *
	 * @param int|Revision $old Old revision or rev ID (first before range)
	 * @param int|Revision $new New revision or rev ID (first after range)
	 * @param int|null $max Limit of Revisions to count, will be incremented to detect truncations
	 * @return int Number of revisions between these revisions.
	 */
	public function countRevisionsBetween( $old, $new, $max = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		if ( !( $old instanceof Revision ) ) {
			$old = Revision::newFromTitle( $this, (int)$old );
		}
		if ( !( $new instanceof Revision ) ) {
			$new = Revision::newFromTitle( $this, (int)$new );
		}
		if ( !$old || !$new ) {
			return 0; // nothing to compare
		}
		return MediaWikiServices::getInstance()
			->getRevisionStore()
			->countRevisionsBetween(
				$this->getArticleID(),
				$old->getRevisionRecord(),
				$new->getRevisionRecord(),
				$max
			);
	}

	/**
	 * Get the authors between the given revisions or revision IDs.
	 * Used for diffs and other things that really need it.
	 *
	 * @since 1.23
	 * @deprecated since 1.35 Use RevisionStore::getAuthorsBetween instead.
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
		wfDeprecated( __METHOD__, '1.35' );
		if ( !( $old instanceof Revision ) ) {
			$old = Revision::newFromTitle( $this, (int)$old );
		}
		if ( !( $new instanceof Revision ) ) {
			$new = Revision::newFromTitle( $this, (int)$new );
		}
		try {
			$users = MediaWikiServices::getInstance()
				->getRevisionStore()
				->getAuthorsBetween(
					$this->getArticleID(),
					$old->getRevisionRecord(),
					$new->getRevisionRecord(),
					null,
					$limit,
					$options
				);
			return array_map( function ( UserIdentity $user ) {
				return $user->getName();
			}, $users );
		} catch ( InvalidArgumentException $e ) {
			return null; // b/c
		}
	}

	/**
	 * Get the number of authors between the given revisions or revision IDs.
	 * Used for diffs and other things that really need it.
	 *
	 * @deprecated since 1.35 Use RevisionStore::countAuthorsBetween instead.
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
		wfDeprecated( __METHOD__, '1.35' );
		$authors = $this->getAuthorsBetween( $old, $new, $limit, $options );
		return $authors ? count( $authors ) : 0;
	}

	/**
	 * Compare with another title.
	 *
	 * @param LinkTarget $title
	 * @return bool
	 */
	public function equals( LinkTarget $title ) {
		// Note: === is necessary for proper matching of number-like titles.
		return $this->mInterwiki === $title->getInterwiki()
			&& $this->mNamespace == $title->getNamespace()
			&& $this->mDbkeyform === $title->getDBkey();
	}

	/**
	 * Check if this title is a subpage of another title
	 *
	 * @param Title $title
	 * @return bool
	 */
	public function isSubpageOf( Title $title ) {
		return $this->mInterwiki === $title->mInterwiki
			&& $this->mNamespace == $title->mNamespace
			&& strpos( $this->mDbkeyform, $title->mDbkeyform . '/' ) === 0;
	}

	/**
	 * Check if page exists.  For historical reasons, this function simply
	 * checks for the existence of the title in the page table, and will
	 * thus return false for interwiki links, special pages and the like.
	 * If you want to know if a title can be meaningfully viewed, you should
	 * probably call the isKnown() method instead.
	 *
	 * @param int $flags Either a bitfield of class READ_* constants or GAID_FOR_UPDATE
	 * @return bool
	 */
	public function exists( $flags = 0 ) {
		$exists = $this->getArticleID( $flags ) != 0;
		Hooks::runner()->onTitleExists( $this, $exists );
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
		Hooks::runner()->onTitleIsAlwaysKnown( $this, $isKnown );

		if ( $isKnown !== null ) {
			return $isKnown;
		}

		if ( $this->isExternal() ) {
			return true; // any interwiki link might be viewable, for all we know
		}

		$services = MediaWikiServices::getInstance();
		switch ( $this->mNamespace ) {
			case NS_MEDIA:
			case NS_FILE:
				// file exists, possibly in a foreign repo
				return (bool)$services->getRepoGroup()->findFile( $this );
			case NS_SPECIAL:
				// valid special page
				return $services->getSpecialPageFactory()->exists( $this->mDbkeyform );
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
			$services = MediaWikiServices::getInstance();
			// If the page doesn't exist but is a known system message, default
			// message content will be displayed, same for language subpages-
			// Use always content language to avoid loading hundreds of languages
			// to get the link color.
			$contLang = $services->getContentLanguage();
			list( $name, ) = $services->getMessageCache()->figureMessage(
				$contLang->lcfirst( $this->getText() )
			);
			$message = wfMessage( $name )->inLanguage( $contLang )->useDatabase( false );
			return $message->exists();
		}

		return false;
	}

	/**
	 * Get the default (plain) message contents for an page that overrides an
	 * interface message key.
	 *
	 * Primary use cases:
	 *
	 * - Article:
	 *    - Show default when viewing the page. The Article::getSubstituteContent
	 *      method displays the default message content, instead of the
	 *      'noarticletext' placeholder message normally used.
	 *
	 * - EditPage:
	 *    - Title of edit page. When creating an interface message override,
	 *      the editor is told they are "Editing the page", instead of
	 *      "Creating the page". (EditPage::setHeaders)
	 *    - Edit notice. The 'translateinterface' edit notice is shown when creating
	 *      or editing an interface message override. (EditPage::showIntro)
	 *    - Opening the editor. The contents of the localisation message are used
	 *      as contents of the editor when creating a new page in the MediaWiki
	 *      namespace. This simplifies the process for editors when "changing"
	 *      an interface message by creating an override. (EditPage::getContentObject)
	 *    - Showing a diff. The left-hand side of a diff when an editor is
	 *      previewing their changes before saving the creation of a page in the
	 *      MediaWiki namespace. (EditPage::showDiff)
	 *    - Disallowing a save. When attempting to create a MediaWiki-namespace
	 *      page with the proposed content matching the interface message default,
	 *      the save is rejected, the same way we disallow blank pages from being
	 *      created. (EditPage::internalAttemptSave)
	 *
	 * - ApiEditPage:
	 *    - Default content, when using the 'prepend' or 'append' feature.
	 *
	 * - SkinTemplate:
	 *    - Label the create action as "Edit", if the page can be an override.
	 *
	 * @return string|bool
	 */
	public function getDefaultMessageText() {
		if ( $this->mNamespace != NS_MEDIAWIKI ) { // Just in case
			return false;
		}

		list( $name, $lang ) = MediaWikiServices::getInstance()->getMessageCache()->figureMessage(
			MediaWikiServices::getInstance()->getContentLanguage()->lcfirst( $this->getText() )
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
	 * @param string|null $purgeTime [optional] TS_MW timestamp
	 * @return bool True if the update succeeded
	 */
	public function invalidateCache( $purgeTime = null ) {
		if ( wfReadOnly() ) {
			return false;
		}
		if ( $this->mArticleID === 0 ) {
			// avoid gap locking if we know it's not there
			return true;
		}

		$conds = $this->pageCond();
		DeferredUpdates::addUpdate(
			new AutoCommitUpdate(
				wfGetDB( DB_MASTER ),
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

					ResourceLoaderWikiModule::invalidateModuleCache(
						$this, null, null, $dbw->getDomainID() );
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
		$jobs = [];
		$jobs[] = HTMLCacheUpdateJob::newForBacklinks(
			$this,
			'pagelinks',
			[ 'causeAction' => 'page-touch' ]
		);
		if ( $this->mNamespace == NS_CATEGORY ) {
			$jobs[] = HTMLCacheUpdateJob::newForBacklinks(
				$this,
				'categorylinks',
				[ 'causeAction' => 'category-touch' ]
			);
		}

		JobQueueGroup::singleton()->lazyPush( $jobs );
	}

	/**
	 * Get the last touched timestamp
	 *
	 * @param IDatabase|null $db
	 * @return string|false Last-touched timestamp
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
	 * @deprecated since 1.35
	 *
	 * @param User|null $user (null defaults to global $wgUser, and is deprecated since 1.35)
	 * @return string|bool|null String timestamp, false if not watched, null if nothing is unseen
	 */
	public function getNotificationTimestamp( $user = null ) {
		// Assume current user if none given
		if ( !$user ) {
			wfDeprecated( __METHOD__ . ' without passing a $user parameter', '1.35' );
			global $wgUser;
			$user = $wgUser;
		}

		return MediaWikiServices::getInstance()
			->getWatchlistNotificationManager()
			->getTitleNotificationTimestamp( $user, $this );
	}

	/**
	 * Generate strings used for xml 'id' names in monobook tabs
	 *
	 * @param string $prepend Defaults to 'nstab-'
	 * @return string XML 'id' name
	 */
	public function getNamespaceKey( $prepend = 'nstab-' ) {
		// Gets the subject namespace of this title
		$nsInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
		$subjectNS = $nsInfo->getSubject( $this->mNamespace );
		// Prefer canonical namespace name for HTML IDs
		$namespaceKey = $nsInfo->getCanonicalName( $subjectNS );
		if ( $namespaceKey === false ) {
			// Fallback to localised text
			$namespaceKey = $this->getSubjectNsText();
		}
		// Makes namespace key lowercase
		$namespaceKey = MediaWikiServices::getInstance()->getContentLanguage()->lc( $namespaceKey );
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
			'rd_namespace' => $this->mNamespace,
			'rd_title' => $this->mDbkeyform,
			'rd_from = page_id'
		];
		if ( $this->isExternal() ) {
			$where['rd_interwiki'] = $this->mInterwiki;
		} else {
			$where[] = 'rd_interwiki = ' . $dbr->addQuotes( '' ) . ' OR rd_interwiki IS NULL';
		}
		if ( $ns !== null ) {
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
	 * Whether the magic words __INDEX__ and __NOINDEX__ function for this page.
	 *
	 * @return bool
	 */
	public function canUseNoindex() {
		global $wgExemptFromUserRobotsControl;

		$bannedNamespaces = $wgExemptFromUserRobotsControl ??
			MediaWikiServices::getInstance()->getNamespaceInfo()->getContentNamespaces();

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
		Hooks::runner()->onGetDefaultSortkey( $this, $unprefixed );
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
			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			$linkCache->addLinkObj( $this );
			$this->mDbPageLanguage = $linkCache->getGoodLinkFieldObj( $this, 'lang' );
		}

		return $this->mDbPageLanguage;
	}

	/**
	 * Get the language in which the content of this page is written in
	 * wikitext. Defaults to content language, but in certain cases it can be
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
			$contentHandler = MediaWikiServices::getInstance()
				->getContentHandlerFactory()
				->getContentHandler( $this->getContentModel() );
			$langObj = $contentHandler->getPageLanguage( $this );
			$this->mPageLanguage = [ $langObj->getCode(), $wgLanguageCode ];
		} else {
			$langObj = MediaWikiServices::getInstance()->getLanguageFactory()
				->getLanguage( $this->mPageLanguage[0] );
		}

		return $langObj;
	}

	/**
	 * Get the language in which the content of this page is written when
	 * viewed by user. Defaults to content language, but in certain cases it can be
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
			$variant = $this->getLanguageConverter( $wgLang )->getPreferredVariant();
			if ( $wgLang->getCode() !== $variant ) {
				return MediaWikiServices::getInstance()->getLanguageFactory()
					->getLanguage( $variant );
			}

			return $wgLang;
		}

		// Checking if DB language is set
		$dbPageLanguage = $this->getDbPageLanguageCode();
		if ( $dbPageLanguage ) {
			$pageLang = wfGetLangObj( $dbPageLanguage );
			$variant = $this->getLanguageConverter( $pageLang )->getPreferredVariant();
			if ( $pageLang->getCode() !== $variant ) {
				$pageLang = MediaWikiServices::getInstance()->getLanguageFactory()
					->getLanguage( $variant );
			}

			return $pageLang;
		}

		// @note Can't be cached persistently, depends on user settings.
		// @note ContentHandler::getPageViewLanguage() may need to load the
		//   content to determine the page language!
		$contentHandler = MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( $this->getContentModel() );
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
		$editnotice_ns = 'editnotice-' . $this->mNamespace;
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

		if (
			MediaWikiServices::getInstance()->getNamespaceInfo()->
				hasSubpages( $this->mNamespace )
		) {
			// Optional notice for page itself and any parent page
			$editnotice_base = $editnotice_ns;
			foreach ( explode( '/', $this->mDbkeyform ) as $part ) {
				$editnotice_base .= '-' . $part;
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
			$editnoticeText = $editnotice_ns . '-' . strtr( $this->mDbkeyform, '/', '-' );
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

		Hooks::runner()->onTitleGetEditNotices( $this, $oldid, $notices );
		return $notices;
	}

	/**
	 * @param string $field
	 * @param int $flags Bitfield of class READ_* constants
	 * @return string|bool
	 */
	private function loadFieldFromDB( $field, $flags ) {
		if ( !in_array( $field, self::getSelectFields(), true ) ) {
			return false; // field does not exist
		}

		$flags |= ( $flags & self::GAID_FOR_UPDATE ) ? self::READ_LATEST : 0; // b/c
		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );

		return wfGetDB( $index )->selectField(
			'page',
			$field,
			$this->pageCond(),
			__METHOD__,
			$options
		);
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
			'mDefaultNamespace',
		];
	}

	public function __wakeup() {
		$this->mArticleID = ( $this->mNamespace >= 0 ) ? -1 : 0;
		$this->mUrlform = wfUrlencode( $this->mDbkeyform );
		$this->mTextform = strtr( $this->mDbkeyform, '_', ' ' );
	}

}
