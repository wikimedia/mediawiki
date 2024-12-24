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

namespace MediaWiki\Title;

use InvalidArgumentException;
use MediaWiki\Cache\LinkCache;
use MediaWiki\Context\RequestContext;
use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWiki\Deferred\AutoCommitUpdate;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Exception\MWException;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\JobQueue\Jobs\HTMLCacheUpdateJob;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageStoreRecord;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Request\PathRouter;
use MediaWiki\ResourceLoader\WikiModule;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Utils\MWTimestamp;
use MessageLocalizer;
use RuntimeException;
use stdClass;
use Stringable;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\Parsoid\Core\LinkTarget as ParsoidLinkTarget;
use Wikimedia\Parsoid\Core\LinkTargetTrait;
use Wikimedia\Rdbms\DBAccessObjectUtils;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Represents a title within MediaWiki.
 * Optionally may contain an interwiki designation or namespace.
 * @note This class can fetch various kinds of data from the database;
 *       however, it does so inefficiently.
 * @note Consider using a TitleValue object instead. TitleValue is more lightweight
 *       and does not rely on global state or the database.
 */
class Title implements Stringable, LinkTarget, PageIdentity {
	use WikiAwareEntityTrait;
	use LinkTargetTrait;

	/** @var MapCacheLRU|null */
	private static $titleCache = null;

	/**
	 * Cached instance of the main page title, to speed up isMainPage() checks.
	 * @var Title|null
	 */
	private static ?Title $cachedMainPage = null;

	/**
	 * Title::newFromText maintains a cache to avoid expensive re-normalization of
	 * commonly used titles. On a batch operation this can become a memory leak
	 * if not bounded.
	 */
	private const CACHE_MAX = 1000;

	/**
	 * Flag for use with factory methods like newFromLinkTarget() that have
	 * a $forceClone parameter. If set, the method must return a new instance.
	 * Without this flag, some factory methods may return existing instances.as
	 *
	 * @since 1.33
	 */
	public const NEW_CLONE = 'clone';

	/** @var string Text form (spaces not underscores) of the main part */
	private $mTextform = '';

	/** @var string URL-encoded form of the main part */
	private $mUrlform = '';

	/** @var string Main part with underscores */
	private $mDbkeyform = '';

	/** @var int Namespace index, i.e. one of the NS_xxxx constants */
	private $mNamespace = NS_MAIN;

	/** @var string Interwiki prefix */
	private $mInterwiki = '';

	/** @var bool Was this Title created from a string with a local interwiki prefix? */
	private $mLocalInterwiki = false;

	/** @var string Title fragment (i.e. the bit after the #) */
	private $mFragment = '';

	/***************************************************************************/
	// region   Private member variables
	/** @name   Private member variables
	 * Please use the accessor functions instead.
	 * @internal
	 * @{
	 */

	/** @var int Article ID, fetched from the link cache on demand */
	public $mArticleID = -1;

	/** @var int|false ID of most recent revision */
	protected $mLatestID = false;

	/**
	 * @var string|false ID of the page's content model, i.e. one of the
	 *   CONTENT_MODEL_XXX constants
	 */
	private $mContentModel = false;

	/**
	 * @var bool If a content model was forced via setContentModel()
	 *   this will be true to avoid having other code paths reset it
	 */
	private $mForcedContentModel = false;

	/** @var int|null Estimated number of revisions; null of not loaded */
	private $mEstimateRevisions;

	/**
	 * Text form including namespace/interwiki, initialised on demand
	 *
	 * Only public to share cache with TitleFormatter
	 *
	 * @internal
	 * @var string|null
	 */
	public $prefixedText = null;

	/**
	 * Namespace to assume when no namespace was passed to factory methods.
	 * This must be NS_MAIN, as it's hardcoded in several places. See T2696.
	 * Used primarily for {{transclusion}} tags.
	 */
	private const DEFAULT_NAMESPACE = NS_MAIN;

	/** @var int The page length, 0 for special pages */
	protected $mLength = -1;

	/** @var null|bool Is the article at this title a redirect? */
	public $mRedirect = null;

	/** @var bool Whether a page has any subpages */
	private $mHasSubpages;

	/** @var array|null The (string) language code of the page's language and content code. */
	private $mPageLanguage;

	/** @var string|false|null The page language code from the database, null if not saved in
	 * the database or false if not loaded, yet.
	 */
	private $mDbPageLanguage = false;

	/** @var TitleValue|null */
	private $mTitleValue = null;

	/** @var bool|null Would deleting this page be a big deletion? */
	private $mIsBigDeletion = null;

	/** @var bool|null Is the title known to be valid? */
	private $mIsValid = null;

	/** @var string|null The key of this instance in the internal Title instance cache */
	private $mInstanceCacheKey = null;

	// endregion -- end of private member variables
	/** @} */
	/***************************************************************************/

	/**
	 * Shorthand for getting a Language Converter for specific language
	 * @param Language $language Language of converter
	 * @return ILanguageConverter
	 */
	private function getLanguageConverter( $language ): ILanguageConverter {
		return MediaWikiServices::getInstance()->getLanguageConverterFactory()
			->getLanguageConverter( $language );
	}

	/**
	 * Shorthand for getting a Language Converter for page's language
	 */
	private function getPageLanguageConverter(): ILanguageConverter {
		return $this->getLanguageConverter( $this->getPageLanguage() );
	}

	/**
	 * Shorthand for getting a database connection provider
	 */
	private function getDbProvider(): IConnectionProvider {
		return MediaWikiServices::getInstance()->getConnectionProvider();
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
		} catch ( MalformedTitleException ) {
			return null;
		}
	}

	/**
	 * Returns a Title given a LinkTarget.
	 * If the given LinkTarget is already a Title instance, that instance is returned,
	 * unless $forceClone is "clone". If $forceClone is "clone" and the given LinkTarget
	 * is already a Title instance, that instance is copied using the clone operator.
	 *
	 * @since 1.27
	 * @param ParsoidLinkTarget $linkTarget Assumed to be safe.
	 * @param string $forceClone set to NEW_CLONE to ensure a fresh instance is returned.
	 * @return Title
	 */
	public static function newFromLinkTarget( ParsoidLinkTarget $linkTarget, $forceClone = '' ) {
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
	 * Same as newFromLinkTarget(), but if passed null, returns null.
	 *
	 * @since 1.34
	 * @param ParsoidLinkTarget|null $linkTarget Assumed to be safe (if not null).
	 * @return Title|null
	 */
	public static function castFromLinkTarget( ?ParsoidLinkTarget $linkTarget ) {
		if ( !$linkTarget ) {
			return null;
		}
		return self::newFromLinkTarget( $linkTarget );
	}

	/**
	 * Return a Title for a given PageIdentity. If $pageIdentity is a Title,
	 * that Title is returned unchanged.
	 *
	 * @since 1.41
	 * @param PageIdentity $pageIdentity
	 * @return Title
	 */
	public static function newFromPageIdentity( PageIdentity $pageIdentity ): Title {
		return self::newFromPageReference( $pageIdentity );
	}

	/**
	 * Same as newFromPageIdentity(), but if passed null, returns null.
	 *
	 * @since 1.36
	 * @param PageIdentity|null $pageIdentity
	 * @return Title|null
	 */
	public static function castFromPageIdentity( ?PageIdentity $pageIdentity ): ?Title {
		return self::castFromPageReference( $pageIdentity );
	}

	/**
	 * Return a Title for a given Reference. If $pageReference is a Title,
	 * that Title is returned unchanged.
	 *
	 * @since 1.41
	 * @param PageReference $pageReference
	 * @return Title
	 */
	public static function newFromPageReference( PageReference $pageReference ): Title {
		if ( $pageReference instanceof Title ) {
			return $pageReference;
		}

		$pageReference->assertWiki( self::LOCAL );
		$title = self::makeTitle( $pageReference->getNamespace(), $pageReference->getDBkey() );

		if ( $pageReference instanceof PageIdentity ) {
			$title->mArticleID = $pageReference->getId();
		}
		return $title;
	}

	/**
	 * Same as newFromPageReference(), but if passed null, returns null.
	 *
	 * @since 1.37
	 * @param PageReference|null $pageReference
	 * @return Title|null
	 */
	public static function castFromPageReference( ?PageReference $pageReference ): ?Title {
		if ( !$pageReference ) {
			return null;
		}
		return self::newFromPageReference( $pageReference );
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
	 * @return Title|null Title or null if the Title could not be parsed because
	 *         it is invalid.
	 */
	public static function newFromText( $text, $defaultNamespace = NS_MAIN ) {
		// DWIM: Integers can be passed in here when page titles are used as array keys.
		if ( $text !== null && !is_string( $text ) && !is_int( $text ) ) {
			throw new InvalidArgumentException( '$text must be a string.' );
		}
		if ( $text === null || $text === '' ) {
			return null;
		}

		try {
			return self::newFromTextThrow( (string)$text, (int)$defaultNamespace );
		} catch ( MalformedTitleException ) {
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
			throw new InvalidArgumentException( '$text must be a string, given an object' );
		} elseif ( $text === null ) {
			// Legacy code relies on MalformedTitleException being thrown in this case
			//  TODO: fix(happens when URL with no title in it is parsed).
			throw new MalformedTitleException( 'title-invalid-empty' );
		}

		$titleCache = self::getTitleCache();

		// Wiki pages often contain multiple links to the same page.
		// Title normalization and parsing can become expensive on pages with many
		// links, so we can save a little time by caching them.
		if ( $defaultNamespace === NS_MAIN ) {
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
		if ( $defaultNamespace === NS_MAIN ) {
			$t->mInstanceCacheKey = $text;
			$titleCache->set( $text, $t );
		}
		return $t;
	}

	/**
	 * Removes this instance from the internal title cache, so it can be modified in-place
	 * without polluting the cache (see T281337).
	 */
	private function uncache() {
		if ( $this->mInstanceCacheKey !== null ) {
			$titleCache = self::getTitleCache();
			$titleCache->clear( $this->mInstanceCacheKey );
			$this->mInstanceCacheKey = null;
		}
	}

	/**
	 * THIS IS NOT THE FUNCTION YOU WANT. Use Title::newFromText().
	 *
	 * Example of wrong and broken code:
	 * $title = Title::newFromURL( $request->getText( 'title' ) );
	 *
	 * Example of right code:
	 * $title = Title::newFromText( $request->getText( 'title' ) );
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
		if ( !str_contains( self::legalChars(), '+' ) ) {
			$url = strtr( $url, '+', ' ' );
		}

		$dbKeyForm = strtr( $url, ' ', '_' );

		try {
			$t->secureAndSplit( $dbKeyForm );
			return $t;
		} catch ( MalformedTitleException ) {
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
	 * Create a new Title from an article ID
	 *
	 * @param int $id The page_id corresponding to the Title to create
	 * @param int $flags Bitfield of IDBAccessObject::READ_* constants
	 * @return Title|null The new object, or null on an error
	 */
	public static function newFromID( $id, $flags = 0 ) {
		$pageStore = MediaWikiServices::getInstance()->getPageStore();
		$dbr = DBAccessObjectUtils::getDBFromRecency(
			MediaWikiServices::getInstance()->getConnectionProvider(),
			$flags
		);
		$row = $dbr->newSelectQueryBuilder()
			->select( $pageStore->getSelectFields() )
			->from( 'page' )
			->where( [ 'page_id' => $id ] )
			->recency( $flags )
			->caller( __METHOD__ )->fetchRow();
		if ( $row !== false ) {
			$title = self::newFromRow( $row );
		} else {
			$title = null;
		}

		return $title;
	}

	/**
	 * Make a Title object from a DB row
	 *
	 * @param stdClass $row Object database row (needs at least page_title,page_namespace)
	 * @return Title
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
	 * @param stdClass|false $row Database row
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
		$t->mFragment = self::normalizeFragment( $fragment );
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
		} catch ( MalformedTitleException ) {
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
	 * be sure to pass in a MessageLocalizer (such as your own RequestContext or
	 * ResourceLoader Context) to prevent an error.
	 *
	 * @note The Title instance returned by this method is not guaranteed to be a fresh instance.
	 * It may instead be a cached instance created previously, with references to it remaining
	 * elsewhere.
	 *
	 * @param MessageLocalizer|null $localizer An optional context to use (since 1.34)
	 * @return Title
	 */
	public static function newMainPage( ?MessageLocalizer $localizer = null ) {
		static $recursionGuard = false;

		$title = null;

		if ( !$recursionGuard ) {
			$msg = $localizer ? $localizer->msg( 'mainpage' ) : wfMessage( 'mainpage' );

			$recursionGuard = true;
			$title = self::newFromText( $msg->inContentLanguage()->text() );
			$recursionGuard = false;
		}

		// Every page renders at least one link to the Main Page (e.g. sidebar).
		// Don't produce fatal errors that would make the wiki inaccessible, and hard to fix the
		// invalid message.
		//
		// Fallback scenarios:
		// * Recursion guard
		//   If the message contains a bare local interwiki (T297571), then
		//   Title::newFromText via TitleParser::splitTitleString can get back here.
		// * Invalid title
		//   If the 'mainpage' message contains something that is invalid,  Title::newFromText
		//   will return null.

		return $title ?? self::makeTitle( NS_MAIN, 'Main Page' );
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
		$d0 = $d1 = '';
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
			$d1 = $d0;
			$ord2 = $ord1;
			$ord1 = $ord0;
			$r2 = $r1;
			$r1 = $r0;
			// Load the current input token and decoded values
			$inChar = $byteClass[$pos];
			if ( $inChar === '\\' ) {
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
					$pos++;
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
				// @phan-suppress-next-line PhanPluginRedundantAssignmentInLoop
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
		if ( $namespace === false ) {
			// See T165149. Awkward, but better than erroneously linking to the main namespace.
			$namespace = self::makeName( NS_SPECIAL, "Badtitle/NS$ns", '', '', $canonicalNamespace );
		}
		$name = $namespace === '' ? $title : "$namespace:$title";
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
	 * @param LinkTarget|PageReference $a
	 * @param LinkTarget|PageReference $b
	 *
	 * @return int Result of string comparison, or namespace comparison
	 */
	public static function compare( $a, $b ) {
		return $a->getNamespace() <=> $b->getNamespace()
			?: strcmp( $a->getDBkey(), $b->getDBkey() );
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
			// Optimization: Avoid Title::getFullText because that involves GenderCache
			// and (unbatched) database queries. For validation, canonical namespace suffices.
			$text = self::makeName( $this->mNamespace, $this->mDbkeyform, $this->mFragment, $this->mInterwiki, true );
			$titleParser = MediaWikiServices::getInstance()->getTitleParser();

			$parts = $titleParser->splitTitleString( $text, $this->mNamespace );

			// Check that nothing changed!
			// This ensures that $text was already properly normalized.
			if ( $parts['fragment'] !== $this->mFragment
				|| $parts['interwiki'] !== $this->mInterwiki
				|| $parts['local_interwiki'] !== $this->mLocalInterwiki
				|| $parts['namespace'] !== $this->mNamespace
				|| $parts['dbkey'] !== $this->mDbkeyform
			) {
				$this->mIsValid = false;
				return $this->mIsValid;
			}
		} catch ( MalformedTitleException ) {
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
	 * Get the interwiki prefix
	 *
	 * Use Title::isExternal to check if a interwiki is set
	 *
	 * @return string Interwiki prefix
	 */
	public function getInterwiki(): string {
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
	public function getText(): string {
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
	public function getDBkey(): string {
		return $this->mDbkeyform;
	}

	/**
	 * Get the namespace index, i.e. one of the NS_xxxx constants.
	 *
	 * @return int Namespace index
	 */
	public function getNamespace(): int {
		return $this->mNamespace;
	}

	/**
	 * @param int $flags
	 *
	 * @return bool Whether $flags indicates that the latest information should be
	 *         read from the primary database, bypassing caches.
	 */
	private function shouldReadLatest( int $flags ) {
		return ( $flags & ( IDBAccessObject::READ_LATEST ) ) > 0;
	}

	/**
	 * Get the page's content model id, see the CONTENT_MODEL_XXX constants.
	 *
	 * @todo Deprecate this in favor of SlotRecord::getModel()
	 *
	 * @param int $flags A bitfield of IDBAccessObject::READ_* constants
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

		if ( $this->shouldReadLatest( $flags ) || !$this->mContentModel ) {
			$this->lazyFillContentModel( $this->getFieldFromPageStore( 'page_content_model', $flags ) );
		}

		if ( !$this->mContentModel ) {
			$slotRoleregistry = MediaWikiServices::getInstance()->getSlotRoleRegistry();
			$mainSlotHandler = $slotRoleregistry->getRoleHandler( 'main' );
			$this->lazyFillContentModel( $mainSlotHandler->getDefaultModel( $this ) );
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
	 * @warning This must only be used if the caller controls the further use of
	 * this Title object, to avoid other code unexpectedly using the new value.
	 *
	 * @since 1.28
	 * @param string $model CONTENT_MODEL_XXX constant
	 */
	public function setContentModel( $model ) {
		if ( (string)$model === '' ) {
			throw new InvalidArgumentException( "Missing CONTENT_MODEL_* constant" );
		}

		$this->uncache();
		$this->mContentModel = $model;
		$this->mForcedContentModel = true;
	}

	/**
	 * If the content model field is not frozen then update it with a retrieved value
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
	 * @return string|false Namespace name with underscores (not spaces), e.g. 'User_talk'
	 */
	public function getNsText() {
		if ( $this->isExternal() ) {
			// This probably shouldn't even happen, except for interwiki transclusion.
			// If possible, use the canonical name for the foreign namespace.
			if ( $this->mNamespace === NS_MAIN ) {
				// Optimisation
				return '';
			} else {
				$nsText = MediaWikiServices::getInstance()->getNamespaceInfo()->
					getCanonicalName( $this->mNamespace );
				if ( $nsText !== false ) {
					return $nsText;
				}
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
	 * @return string Namespace name with underscores (not spaces)
	 */
	public function getSubjectNsText() {
		$services = MediaWikiServices::getInstance();
		return $services->getContentLanguage()->
			getNsText( $services->getNamespaceInfo()->getSubject( $this->mNamespace ) );
	}

	/**
	 * Get the namespace text of the talk page
	 *
	 * @return string Namespace name with underscores (not spaces)
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
	public function canExist(): bool {
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
	 * Returns true if this is a special page.
	 *
	 * @return bool
	 */
	public function isSpecialPage() {
		return $this->mNamespace === NS_SPECIAL;
	}

	/**
	 * Returns true if this title resolves to the named special page
	 *
	 * @param string $name The special page name
	 * @return bool
	 */
	public function isSpecial( $name ) {
		if ( $this->isSpecialPage() ) {
			[ $thisName, /* $subpage */ ] =
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
			[ $canonicalName, $par ] = $spFactory->resolveAlias( $this->mDbkeyform );
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
	 * @param int $ns The namespace
	 * @return bool
	 * @since 1.19
	 */
	public function inNamespace( int $ns ): bool {
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
		$services = MediaWikiServices::getInstance();
		if (
			!$services->getNamespaceInfo()->
				isMovable( $this->mNamespace ) || $this->isExternal()
		) {
			// Interwiki title or immovable namespace. Hooks don't get to override here
			return false;
		}

		$result = true;
		( new HookRunner( $services->getHookContainer() ) )->onTitleIsMovable( $this, $result );
		return $result;
	}

	/**
	 * Is this the mainpage?
	 * @see T302186
	 *
	 * @since 1.18
	 * @return bool
	 */
	public function isMainPage() {
		self::$cachedMainPage ??= self::newMainPage();
		return $this->equals( self::$cachedMainPage );
	}

	/**
	 * Is this a subpage?
	 *
	 * @return bool
	 */
	public function isSubpage() {
		return MediaWikiServices::getInstance()
				->getNamespaceInfo()
				->hasSubpages( $this->mNamespace )
			&& str_contains( $this->getText(), '/' );
	}

	/**
	 * Is this a conversion table for the LanguageConverter?
	 *
	 * @return bool
	 */
	public function isConversionTable() {
		// @todo ConversionTable should become a separate content model.
		// @todo And the prefix should be localized, too!

		return $this->mNamespace === NS_MEDIAWIKI &&
			str_starts_with( $this->getText(), 'Conversiontable/' );
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
	 * CONTENT_MODEL_CSS, CONTENT_MODEL_JSON, CONTENT_MODEL_JAVASCRIPT or CONTENT_MODEL_VUE.
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
	 * Is this a "config" (.css, .json, or .js) subpage of a user page?
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
		$text = $this->getText();
		$lastSlashPos = $this->findSubpageDivider( $text, -1 );
		if ( $lastSlashPos === false ) {
			return '';
		}

		$lastDot = strrpos( $text, '.', $lastSlashPos );
		if ( $lastDot === false ) {
			return '';
		}

		return substr( $text, $lastSlashPos + 1, $lastDot - $lastSlashPos - 1 );
	}

	/**
	 * Is this a CSS "config" subpage of a user page?
	 *
	 * @return bool
	 * @since 1.31
	 */
	public function isUserCssConfigPage() {
		return (
			$this->mNamespace === NS_USER
			&& $this->isSubpage()
			&& $this->hasContentModel( CONTENT_MODEL_CSS )
		);
	}

	/**
	 * Is this a JSON "config" subpage of a user page?
	 *
	 * @return bool
	 * @since 1.31
	 */
	public function isUserJsonConfigPage() {
		return (
			$this->mNamespace === NS_USER
			&& $this->isSubpage()
			&& $this->hasContentModel( CONTENT_MODEL_JSON )
		);
	}

	/**
	 * Is this a JS "config" subpage of a user page?
	 *
	 * @return bool
	 * @since 1.31
	 */
	public function isUserJsConfigPage() {
		return (
			$this->mNamespace === NS_USER
			&& $this->isSubpage()
			&& ( $this->hasContentModel( CONTENT_MODEL_JAVASCRIPT ) ||
				$this->hasContentModel( CONTENT_MODEL_VUE )
			)
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
			$this->mNamespace === NS_MEDIAWIKI
			&& (
				$this->hasContentModel( CONTENT_MODEL_CSS )
				// paranoia - a MediaWiki: namespace page with mismatching extension and content
				// model is probably by mistake and might get handled incorrectly (see e.g. T112937)
				|| str_ends_with( $this->mDbkeyform, '.css' )
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
			$this->mNamespace === NS_MEDIAWIKI
			&& (
				$this->hasContentModel( CONTENT_MODEL_JSON )
				// paranoia - a MediaWiki: namespace page with mismatching extension and content
				// model is probably by mistake and might get handled incorrectly (see e.g. T112937)
				|| str_ends_with( $this->mDbkeyform, '.json' )
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
			$this->mNamespace === NS_MEDIAWIKI
			&& (
				(
					$this->hasContentModel( CONTENT_MODEL_JAVASCRIPT )
					// paranoia - a MediaWiki: namespace page with mismatching extension and content
					// model is probably by mistake and might get handled incorrectly (see e.g. T112937)
					|| str_ends_with( $this->mDbkeyform, '.js' )
				) || (
					$this->hasContentModel( CONTENT_MODEL_VUE )
					|| str_ends_with( $this->mDbkeyform, '.vue' )
				)
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
	 * Get the Title fragment (i.e.\ the bit after the #) in text form
	 *
	 * Use Title::hasFragment to check for a fragment
	 *
	 * @return string Title fragment
	 */
	public function getFragment(): string {
		return $this->mFragment;
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
	 * @warning This must only be used if the caller controls the further use of
	 * this Title object, to avoid other code unexpectedly using the new value.
	 *
	 * @param string $fragment Text
	 */
	public function setFragment( $fragment ) {
		$this->uncache();
		$this->mFragment = self::normalizeFragment( $fragment );
	}

	/**
	 * Creates a new Title for a different fragment of the same page.
	 *
	 * @since 1.27
	 * @param string $fragment
	 * @return Title
	 */
	public function createFragmentTarget( string $fragment ): self {
		return self::makeTitle(
			$this->mNamespace,
			$this->getText(),
			$fragment,
			$this->mInterwiki
		);
	}

	/**
	 * Normalizes fragment part of the title.
	 *
	 * @param string $fragment
	 * @return string
	 */
	private static function normalizeFragment( $fragment ) {
		if ( str_starts_with( $fragment, '#' ) ) {
			$fragment = substr( $fragment, 1 );
		}
		return strtr( $fragment, '_', ' ' );
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
	public function __toString(): string {
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
		if ( $dir > 0 ) {
			// Skip leading slashes, but keep the last one when there is nothing but slashes
			$bottom = strspn( $text, '/', 0, -1 );
			$idx = strpos( $text, '/', $bottom );
		} else {
			// Any slash from the end can be a divider, as subpage names can be empty
			$idx = strrpos( $text, '/' );
		}

		// The first character can never be a divider, as that would result in an empty base
		return $idx === 0 ? false : $idx;
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
	 * @return Title
	 * @since 1.20
	 */
	public function getRootTitle() {
		$title = self::makeTitleSafe( $this->mNamespace, $this->getRootText() );

		if ( !$title ) {
			if ( !$this->isValid() ) {
				// If the title wasn't valid in the first place, we can't expect
				// to successfully parse it. T290194
				return $this;
			}

			Assert::postcondition(
				$title !== null,
				'makeTitleSafe() should always return a Title for the text ' .
					'returned by getRootText().'
			);
		}

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
	 * @return Title
	 * @since 1.20
	 */
	public function getBaseTitle() {
		$title = self::makeTitleSafe( $this->mNamespace, $this->getBaseText() );

		if ( !$title ) {
			if ( !$this->isValid() ) {
				// If the title wasn't valid in the first place, we can't expect
				// to successfully parse it. T290194
				return $this;
			}

			Assert::postcondition(
				$title !== null,
				'makeTitleSafe() should always return a Title for the text ' .
					'returned by getBaseText().'
			);
		}

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
	 * Get a real URL referring to this title, with interwiki link and
	 * fragment
	 *
	 * @see self::getLocalURL for the arguments.
	 * @see \MediaWiki\Utils\UrlUtils::expand()
	 * @param string|array $query
	 * @param false $query2 deprecated since MW 1.19; ignored since MW 1.41
	 * @param string|int|null $proto Protocol type to use in URL
	 * @return string The URL
	 */
	public function getFullURL( $query = '', $query2 = false, $proto = PROTO_RELATIVE ) {
		$services = MediaWikiServices::getInstance();

		$query = is_array( $query ) ? wfArrayToCgi( $query ) : $query;

		# Hand off all the decisions on urls to getLocalURL
		$url = $this->getLocalURL( $query );

		# Expand the url to make it a full url. Note that getLocalURL has the
		# potential to output full urls for a variety of reasons, so we use
		# UrlUtils::expand() instead of simply prepending $wgServer
		$url = (string)$services->getUrlUtils()->expand( $url, $proto );

		# Finally, add the fragment.
		$url .= $this->getFragmentForURL();
		( new HookRunner( $services->getHookContainer() ) )->onGetFullURL( $this, $url, $query );
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
	 *
	 * @return string
	 */
	public function getLocalURL( $query = '' ) {
		global $wgArticlePath, $wgScript, $wgMainPageIsDomainRoot;

		$query = is_array( $query ) ? wfArrayToCgi( $query ) : $query;

		$services = MediaWikiServices::getInstance();
		$hookRunner = new HookRunner( $services->getHookContainer() );
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
				if ( $wgMainPageIsDomainRoot && $this->isMainPage() ) {
					$url = '/';
				} else {
					$url = str_replace( '$1', $dbkey, $wgArticlePath );
				}
				$hookRunner->onGetLocalURL__Article( $this, $url );
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
					&& $this->getPageLanguage()->equals( $services->getContentLanguage() )
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
			$hookRunner->onGetLocalURL__Internal( $this, $url, $query );
		}

		$hookRunner->onGetLocalURL( $this, $url, $query );
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
	 * @param string|string[]|false $query2 deprecated since MW 1.19; ignored since MW 1.41
	 * @param string|int|false $proto A PROTO_* constant on how the URL should be expanded,
	 *                               or false (default) for no expansion
	 * @see self::getLocalURL for the arguments.
	 * @return string The URL
	 */
	public function getLinkURL( $query = '', $query2 = false, $proto = false ) {
		if ( $this->isExternal() || $proto !== false ) {
			$ret = $this->getFullURL( $query, false, $proto );
		} elseif ( $this->getPrefixedText() === '' && $this->hasFragment() ) {
			$ret = $this->getFragmentForURL();
		} else {
			$ret = $this->getLocalURL( $query ) . $this->getFragmentForURL();
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
	 * @return string The URL
	 */
	public function getInternalURL( $query = '' ) {
		global $wgInternalServer, $wgServer;
		$services = MediaWikiServices::getInstance();

		$query = is_array( $query ) ? wfArrayToCgi( $query ) : $query;

		$server = $wgInternalServer !== false ? $wgInternalServer : $wgServer;
		$url = (string)$services->getUrlUtils()->expand( $server . $this->getLocalURL( $query ), PROTO_HTTP );
		( new HookRunner( $services->getHookContainer() ) )
			->onGetInternalURL( $this, $url, $query );
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
	 * @return string The URL
	 * @since 1.18
	 */
	public function getCanonicalURL( $query = '' ) {
		$services = MediaWikiServices::getInstance();

		$query = is_array( $query ) ? wfArrayToCgi( $query ) : $query;

		$url = (string)$services->getUrlUtils()->expand(
			$this->getLocalURL( $query ) . $this->getFragmentForURL(),
			PROTO_CANONICAL
		);
		( new HookRunner( $services->getHookContainer() ) )
			->onGetCanonicalURL( $this, $url, $query );
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
	 * Is this title subject to title protection?
	 * Title protection is the one applied against creation of such title.
	 *
	 * @deprecated since 1.37, use RestrictionStore::getCreateProtection() instead;
	 *   hard-deprecated since 1.43
	 *
	 * @return array|bool An associative array representing any existent title
	 *   protection, or false if there's none.
	 */
	public function getTitleProtection() {
		wfDeprecated( __METHOD__, '1.37' );
		return MediaWikiServices::getInstance()->getRestrictionStore()->getCreateProtection( $this )
			?: false;
	}

	/**
	 * Remove any title protection due to page existing
	 *
	 * @deprecated since 1.37, do not use (this is only for WikiPage::onArticleCreate)
	 *   hard-deprecated since 1.44
	 */
	public function deleteTitleProtection() {
		wfDeprecated( __METHOD__, '1.37' );
		MediaWikiServices::getInstance()->getRestrictionStore()->deleteCreateProtection( $this );
	}

	/**
	 * Load restrictions from the page_restrictions table
	 *
	 * @deprecated since 1.37, no public replacement; hard-deprecated since 1.43
	 *
	 * @param int $flags A bit field. If IDBAccessObject::READ_LATEST is set, skip replicas and read
	 *  from the primary DB.
	 */
	public function loadRestrictions( $flags = 0 ) {
		wfDeprecated( __METHOD__, '1.37' );
		MediaWikiServices::getInstance()->getRestrictionStore()->loadRestrictions( $this, $flags );
	}

	/**
	 * Flush the protection cache in this object and force reload from the database.
	 * This is used when updating protection from WikiPage::doUpdateRestrictions().
	 *
	 * @deprecated since 1.37, now internal; hard-deprecated since 1.43
	 */
	public function flushRestrictions() {
		wfDeprecated( __METHOD__, '1.37' );
		MediaWikiServices::getInstance()->getRestrictionStore()->flushRestrictions( $this );
	}

	/**
	 * Purge expired restrictions from the page_restrictions table
	 *
	 * This will purge no more than $wgUpdateRowsPerQuery page_restrictions rows
	 */
	public static function purgeExpiredRestrictions() {
		if ( MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() ) {
			return;
		}

		DeferredUpdates::addUpdate( new AutoCommitUpdate(
			MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase(),
			__METHOD__,
			static function ( IDatabase $dbw, $fname ) {
				$config = MediaWikiServices::getInstance()->getMainConfig();
				$ids = $dbw->newSelectQueryBuilder()
					->select( 'pr_id' )
					->from( 'page_restrictions' )
					->where( $dbw->expr( 'pr_expiry', '<', $dbw->timestamp() ) )
					->limit( $config->get( MainConfigNames::UpdateRowsPerQuery ) ) // T135470
					->caller( $fname )->fetchFieldValues();
				if ( $ids ) {
					$dbw->newDeleteQueryBuilder()
						->deleteFrom( 'page_restrictions' )
						->where( [ 'pr_id' => $ids ] )
						->caller( $fname )->execute();
				}

				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'protected_titles' )
					->where( $dbw->expr( 'pt_expiry', '<', $dbw->timestamp() ) )
					->caller( $fname )->execute();
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
			$subpages = $this->getSubpages( 1 );
			$this->mHasSubpages = $subpages instanceof TitleArrayFromResult && $subpages->count();
		}

		return $this->mHasSubpages;
	}

	/**
	 * Get all subpages of this page.
	 *
	 * @param int $limit Maximum number of subpages to fetch; -1 for no limit
	 * @return TitleArrayFromResult|array TitleArrayFromResult, or empty array if this page's namespace
	 *  doesn't allow subpages
	 */
	public function getSubpages( $limit = -1 ) {
		if (
			!MediaWikiServices::getInstance()->getNamespaceInfo()->
				hasSubpages( $this->mNamespace )
		) {
			return [];
		}

		$services = MediaWikiServices::getInstance();
		$pageStore = $services->getPageStore();
		$titleFactory = $services->getTitleFactory();
		$query = $pageStore->newSelectQueryBuilder()
			->fields( $pageStore->getSelectFields() )
			->whereTitlePrefix( $this->getNamespace(), $this->getDBkey() . '/' )
			->caller( __METHOD__ );
		if ( $limit > -1 ) {
			$query->limit( $limit );
		}

		return $titleFactory->newTitleArrayFromResult( $query->fetchResultSet() );
	}

	/**
	 * Is there a version of this page in the deletion archive?
	 *
	 * @deprecated since 1.36. Use self::getDeletedEditsCount()
	 * @return int The number of archived revisions
	 */
	public function isDeleted() {
		return $this->getDeletedEditsCount();
	}

	/**
	 * Is there a version of this page in the deletion archive?
	 *
	 * @since 1.36
	 * @return int The number of archived revisions
	 */
	public function getDeletedEditsCount() {
		if ( $this->mNamespace < 0 ) {
			return 0;
		}

		$dbr = $this->getDbProvider()->getReplicaDatabase();
		$n = (int)$dbr->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'archive' )
			->where( [ 'ar_namespace' => $this->mNamespace, 'ar_title' => $this->mDbkeyform ] )
			->caller( __METHOD__ )->fetchField();
		if ( $this->mNamespace === NS_FILE ) {
			$n += $dbr->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'filearchive' )
				->where( [ 'fa_name' => $this->mDbkeyform ] )
				->caller( __METHOD__ )->fetchField();
		}
		return $n;
	}

	/**
	 * Is there a version of this page in the deletion archive?
	 *
	 * @deprecated since 1.36, Use self::hasDeletedEdits()
	 * @return bool
	 */
	public function isDeletedQuick() {
		return $this->hasDeletedEdits();
	}

	/**
	 * Is there a version of this page in the deletion archive?
	 *
	 * @since 1.36
	 * @return bool
	 */
	public function hasDeletedEdits() {
		if ( $this->mNamespace < 0 ) {
			return false;
		}
		$dbr = $this->getDbProvider()->getReplicaDatabase();
		$deleted = (bool)$dbr->newSelectQueryBuilder()
			->select( '1' )
			->from( 'archive' )
			->where( [ 'ar_namespace' => $this->mNamespace, 'ar_title' => $this->mDbkeyform ] )
			->caller( __METHOD__ )->fetchField();
		if ( !$deleted && $this->mNamespace === NS_FILE ) {
			$deleted = (bool)$dbr->newSelectQueryBuilder()
				->select( '1' )
				->from( 'filearchive' )
				->where( [ 'fa_name' => $this->mDbkeyform ] )
				->caller( __METHOD__ )->fetchField();
		}
		return $deleted;
	}

	/**
	 * Get the article ID for this Title from the link cache,
	 * adding it if necessary
	 *
	 * @param int $flags A bitfield of IDBAccessObject::READ_* constants
	 * @return int The ID
	 */
	public function getArticleID( $flags = 0 ) {
		if ( $this->mArticleID === -1 && !$this->canExist() ) {
			$this->mArticleID = 0;

			return $this->mArticleID;
		}

		if ( $this->mArticleID === -1 || $this->shouldReadLatest( $flags ) ) {
			$this->mArticleID = (int)$this->getFieldFromPageStore( 'page_id', $flags );
		}

		return $this->mArticleID;
	}

	/**
	 * Is this an article that is a redirect page?
	 * Uses link cache, adding it if necessary.
	 *
	 * This is intended to provide fast access to page_is_redirect for linking.
	 * In rare cases, there might not be a valid target in the redirect table
	 * even though this function returns true.
	 *
	 * To find a redirect target, just call WikiPage::getRedirectTarget() and
	 * check if it returns null, there's no need to call this first.
	 *
	 * @param int $flags A bitfield of IDBAccessObject::READ_* constants
	 * @return bool
	 */
	public function isRedirect( $flags = 0 ) {
		if ( $this->shouldReadLatest( $flags ) || $this->mRedirect === null ) {
			$this->mRedirect = (bool)$this->getFieldFromPageStore( 'page_is_redirect', $flags );
		}

		return $this->mRedirect;
	}

	/**
	 * What is the length of this page?
	 * Uses link cache, adding it if necessary
	 *
	 * @param int $flags A bitfield of IDBAccessObject::READ_* constants
	 * @return int
	 */
	public function getLength( $flags = 0 ) {
		if ( $this->shouldReadLatest( $flags ) || $this->mLength < 0 ) {
			$this->mLength = (int)$this->getFieldFromPageStore( 'page_len', $flags );
		}

		if ( $this->mLength < 0 ) {
			$this->mLength = 0;
		}

		return $this->mLength;
	}

	/**
	 * What is the page_latest field for this page?
	 *
	 * @param int $flags A bitfield of IDBAccessObject::READ_* constants
	 * @return int Int or 0 if the page doesn't exist
	 */
	public function getLatestRevID( $flags = 0 ) {
		if ( $this->shouldReadLatest( $flags ) || $this->mLatestID === false ) {
			$this->mLatestID = (int)$this->getFieldFromPageStore( 'page_latest', $flags );
		}

		if ( !$this->mLatestID ) {
			$this->mLatestID = 0;
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
	 * @param int|bool $id Page ID, 0 for non-existent, or false for "unknown" (lazy-load)
	 */
	public function resetArticleID( $id ) {
		if ( $id === false ) {
			$this->mArticleID = -1;
		} else {
			$this->mArticleID = (int)$id;
		}
		$this->mRedirect = null;
		$this->mLength = -1;
		$this->mLatestID = false;
		$this->mContentModel = false;
		$this->mForcedContentModel = false;
		$this->mEstimateRevisions = null;
		$this->mPageLanguage = null;
		$this->mDbPageLanguage = false;
		$this->mIsBigDeletion = null;

		$this->uncache();
		MediaWikiServices::getInstance()->getLinkCache()->clearLink( $this );
		MediaWikiServices::getInstance()->getRestrictionStore()->flushRestrictions( $this );
	}

	public static function clearCaches() {
		if ( MediaWikiServices::hasInstance() ) {
			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			$linkCache->clear();
		}

		// Reset cached main page instance (T395214).
		self::$cachedMainPage = null;

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
		$defaultNamespace ??= self::DEFAULT_NAMESPACE;

		// @note: splitTitleString() is a temporary hack to allow TitleParser to share
		//        the parsing code with Title, while avoiding massive refactoring.
		// @todo: get rid of secureAndSplit, refactor parsing code.
		$titleParser = MediaWikiServices::getInstance()->getTitleParser();
		// MalformedTitleException can be thrown here
		$parts = $titleParser->splitTitleString( $text, $defaultNamespace );

		# Fill fields
		$this->setFragment( '#' . $parts['fragment'] );
		$this->mInterwiki = $parts['interwiki'];
		$this->mLocalInterwiki = $parts['local_interwiki'];
		$this->mNamespace = $parts['namespace'];

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
	 * @return Title[]
	 */
	public function getLinksTo( $options = [], $table = 'pagelinks', $prefix = 'pl' ) {
		if ( count( $options ) > 0 ) {
			$db = $this->getDbProvider()->getPrimaryDatabase();
		} else {
			$db = $this->getDbProvider()->getReplicaDatabase();
		}

		$linksMigration = MediaWikiServices::getInstance()->getLinksMigration();
		if ( isset( $linksMigration::$mapping[$table] ) ) {
			$titleConds = $linksMigration->getLinksConditions( $table, $this );
		} else {
			$titleConds = [
				"{$prefix}_namespace" => $this->mNamespace,
				"{$prefix}_title" => $this->mDbkeyform
			];
		}

		$res = $db->newSelectQueryBuilder()
			->select( LinkCache::getSelectFields() )
			->from( $table )
			->join( 'page', null, "{$prefix}_from=page_id" )
			->where( $titleConds )
			->options( $options )
			->caller( __METHOD__ )
			->fetchResultSet();

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
	 * @return Title[]
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
	 * @return Title[] List of Titles linking here
	 */
	public function getLinksFrom( $options = [], $table = 'pagelinks', $prefix = 'pl' ) {
		$id = $this->getArticleID();

		# If the page doesn't exist; there can't be any link from this page
		if ( !$id ) {
			return [];
		}

		$db = $this->getDbProvider()->getReplicaDatabase();
		$linksMigration = MediaWikiServices::getInstance()->getLinksMigration();

		$queryBuilder = $db->newSelectQueryBuilder();
		if ( isset( $linksMigration::$mapping[$table] ) ) {
			[ $blNamespace, $blTitle ] = $linksMigration->getTitleFields( $table );
			$linktargetQueryInfo = $linksMigration->getQueryInfo( $table );
			$queryBuilder->queryInfo( $linktargetQueryInfo );
		} else {
			$blNamespace = "{$prefix}_namespace";
			$blTitle = "{$prefix}_title";
			$queryBuilder->select( [ $blNamespace, $blTitle ] )
				->from( $table );
		}

		$pageQuery = WikiPage::getQueryInfo();
		$res = $queryBuilder
			->where( [ "{$prefix}_from" => $id ] )
			->leftJoin( 'page', null, [ "page_namespace=$blNamespace", "page_title=$blTitle" ] )
			->fields( $pageQuery['fields'] )
			->options( $options )
			->caller( __METHOD__ )
			->fetchResultSet();

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
	 * @return Title[]
	 */
	public function getTemplateLinksFrom( $options = [] ) {
		return $this->getLinksFrom( $options, 'templatelinks', 'tl' );
	}

	/**
	 * Locks the page row and check if this page is single revision redirect
	 *
	 * This updates the cached fields of this instance via Title::loadFromRow()
	 *
	 * @return bool
	 */
	public function isSingleRevRedirect() {
		$dbw = $this->getDbProvider()->getPrimaryDatabase();
		$dbw->startAtomic( __METHOD__ );
		$pageStore = MediaWikiServices::getInstance()->getPageStore();

		$row = $dbw->newSelectQueryBuilder()
			->select( $pageStore->getSelectFields() )
			->from( 'page' )
			->where( $this->pageCond() )
			->caller( __METHOD__ )->fetchRow();
		// Update the cached fields
		$this->loadFromRow( $row );

		if ( $this->mRedirect && $this->mLatestID ) {
			$isSingleRevRedirect = !$dbw->newSelectQueryBuilder()
				->select( '1' )
				->forUpdate()
				->from( 'revision' )
				->where( [ 'rev_page' => $this->mArticleID, $dbw->expr( 'rev_id', '!=', (int)$this->mLatestID ) ] )
				->caller( __METHOD__ )->fetchField();
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
	 * @return string[] Array of parents in the form:
	 *     $parent => $currentarticle
	 */
	public function getParentCategories() {
		$data = [];

		$titleKey = $this->getArticleID();

		if ( $titleKey === 0 ) {
			return $data;
		}

		$migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::CategoryLinksSchemaMigrationStage
		);

		$dbr = $this->getDbProvider()->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->from( 'categorylinks' )
			->where( [ 'cl_from' => $titleKey ] );

		if ( $migrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$queryBuilder->select( 'cl_to' );
		} else {
			$queryBuilder->field( 'lt_title', 'cl_to' )
				->join( 'linktarget', null, 'cl_target_id = lt_id' )
				->where( [ 'lt_namespace' => NS_CATEGORY ] );
		}
		$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

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
	 * Check if this is a new page.
	 *
	 * @note This returns false if the page does not exist.
	 * @param int $flags one of the READ_XXX constants.
	 *
	 * @return bool
	 */
	public function isNewPage( $flags = IDBAccessObject::READ_NORMAL ) {
		// NOTE: we rely on PHP casting "0" to false here.
		return (bool)$this->getFieldFromPageStore( 'page_is_new', $flags );
	}

	/**
	 * Check whether the number of revisions of this page surpasses $wgDeleteRevisionsLimit
	 * @deprecated since 1.37 External callers shouldn't need to know about this.
	 *
	 * @return bool
	 */
	public function isBigDeletion() {
		global $wgDeleteRevisionsLimit;

		if ( !$wgDeleteRevisionsLimit ) {
			return false;
		}

		if ( $this->mIsBigDeletion === null ) {
			$dbr = $this->getDbProvider()->getReplicaDatabase();
			$revCount = $dbr->newSelectQueryBuilder()
				->select( '1' )
				->from( 'revision' )
				->where( [ 'rev_page' => $this->getArticleID() ] )
				->limit( $wgDeleteRevisionsLimit + 1 )
				->caller( __METHOD__ )->fetchRowCount();

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
			$dbr = $this->getDbProvider()->getReplicaDatabase();
			$this->mEstimateRevisions = $dbr->newSelectQueryBuilder()
				->select( '*' )
				->from( 'revision' )
				->where( [ 'rev_page' => $this->getArticleID() ] )
				->caller( __METHOD__ )
				->estimateRowCount();
		}

		return $this->mEstimateRevisions;
	}

	/**
	 * Compares with another Title.
	 *
	 * A Title object is considered equal to another Title if it has the same text,
	 * the same interwiki prefix, and the same namespace.
	 *
	 * @note This is different from {@see LinkTarget::isSameLinkAs} which also compares the fragment
	 * part.
	 *
	 * @phpcs:disable MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param Title|object $other
	 *
	 * @return bool
	 */
	public function equals( object $other ) {
		// NOTE: In contrast to isSameLinkAs(), this ignores the fragment part!
		// NOTE: In contrast to isSamePageAs(), this ignores the page ID!
		// NOTE: === is necessary for proper matching of number-like titles
		return $other instanceof Title
			&& $this->getInterwiki() === $other->getInterwiki()
			&& $this->getNamespace() === $other->getNamespace()
			&& $this->getDBkey() === $other->getDBkey();
	}

	/**
	 * @inheritDoc
	 * @since 1.36
	 */
	public function isSamePageAs( PageReference $other ): bool {
		// NOTE: keep in sync with PageReferenceValue::isSamePageAs()!
		return $this->getWikiId() === $other->getWikiId()
			&& $this->getNamespace() === $other->getNamespace()
			&& $this->getDBkey() === $other->getDBkey();
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
			&& str_starts_with( $this->mDbkeyform, $title->mDbkeyform . '/' );
	}

	/**
	 * Check if page exists.  For historical reasons, this function simply
	 * checks for the existence of the title in the page table, and will
	 * thus return false for interwiki links, special pages and the like.
	 * If you want to know if a title can be meaningfully viewed, you should
	 * probably call the isKnown() method instead.
	 *
	 * @param int $flags A bitfield of IDBAccessObject::READ_* constants
	 * @return bool
	 */
	public function exists( $flags = 0 ): bool {
		$exists = $this->getArticleID( $flags ) != 0;
		( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )->onTitleExists( $this, $exists );
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

		$services = MediaWikiServices::getInstance();
		( new HookRunner( $services->getHookContainer() ) )->onTitleIsAlwaysKnown( $this, $isKnown );

		if ( $isKnown !== null ) {
			return $isKnown;
		}

		if ( $this->isExternal() ) {
			return true; // any interwiki link might be viewable, for all we know
		}

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

		if ( $this->mNamespace === NS_MEDIAWIKI ) {
			$services = MediaWikiServices::getInstance();
			// If the page doesn't exist but is a known system message, default
			// message content will be displayed, same for language subpages-
			// Use always content language to avoid loading hundreds of languages
			// to get the link color.
			$contLang = $services->getContentLanguage();
			[ $name, ] = $services->getMessageCache()->figureMessage(
				$contLang->lcfirst( $this->getText() )
			);
			$message = wfMessage( $name )->inLanguage( $contLang )->useDatabase( false );
			return $message->exists();
		}

		return false;
	}

	/**
	 * Get the default (plain) message contents for a page that overrides an
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
	 *      created. (EditPage using DefaultTextConstraint)
	 *
	 * - ApiEditPage:
	 *    - Default content, when using the 'prepend' or 'append' feature.
	 *
	 * - SkinTemplate:
	 *    - Label the create action as "Edit", if the page can be an override.
	 *
	 * @return string|false
	 */
	public function getDefaultMessageText() {
		$message = $this->getDefaultSystemMessage();

		return $message ? $message->plain() : false;
	}

	/**
	 * Same as getDefaultMessageText, but returns a Message object.
	 *
	 * @see ::getDefaultMessageText
	 *
	 * @return ?Message
	 */
	public function getDefaultSystemMessage(): ?Message {
		if ( $this->mNamespace !== NS_MEDIAWIKI ) { // Just in case
			return null;
		}

		[ $name, $lang ] = MediaWikiServices::getInstance()->getMessageCache()->figureMessage(
			MediaWikiServices::getInstance()->getContentLanguage()->lcfirst( $this->getText() )
		);

		if ( wfMessage( $name )->inLanguage( $lang )->useDatabase( false )->exists() ) {
			return wfMessage( $name )->inLanguage( $lang );
		} else {
			return null;
		}
	}

	/**
	 * Updates page_touched for this page; called from LinksUpdate.php
	 *
	 * @param string|null $purgeTime [optional] TS_MW timestamp
	 * @return bool True if the update succeeded
	 */
	public function invalidateCache( $purgeTime = null ) {
		if ( MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() ) {
			return false;
		}
		if ( $this->mArticleID === 0 ) {
			// avoid gap locking if we know it's not there
			return true;
		}

		$conds = $this->pageCond();

		// Periodically recompute page_random (T309477). This mitigates bias on
		// Special:Random due deleted pages leaving "gaps" in the distribution.
		//
		// Optimization: Update page_random only for 10% of updates.
		// Optimization: Do this outside the main transaction to avoid locking for too long.
		// Optimization: Update page_random alongside page_touched to avoid extra database writes.
		DeferredUpdates::addUpdate(
			new AutoCommitUpdate(
				$this->getDbProvider()->getPrimaryDatabase(),
				__METHOD__,
				function ( IDatabase $dbw, $fname ) use ( $conds, $purgeTime ) {
					$dbTimestamp = $dbw->timestamp( $purgeTime ?: time() );
					$update = $dbw->newUpdateQueryBuilder()
						->update( 'page' )
						->set( [ 'page_touched' => $dbTimestamp ] )
						->where( $conds )
						->andWhere( $dbw->expr( 'page_touched', '<', $dbTimestamp ) );

					if ( mt_rand( 1, 10 ) === 1 ) {
						$update->andSet( [ 'page_random' => wfRandom() ] );
					}

					$update->caller( $fname )->execute();

					MediaWikiServices::getInstance()->getLinkCache()->invalidateTitle( $this );

					WikiModule::invalidateModuleCache(
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
		$jobs[] = HTMLCacheUpdateJob::newForBacklinks(
			$this,
			'existencelinks',
			[ 'causeAction' => 'existence-touch' ]
		);
		if ( $this->mNamespace === NS_CATEGORY ) {
			$jobs[] = HTMLCacheUpdateJob::newForBacklinks(
				$this,
				'categorylinks',
				[ 'causeAction' => 'category-touch' ]
			);
		}

		MediaWikiServices::getInstance()->getJobQueueGroup()->lazyPush( $jobs );
	}

	/**
	 * Get the last touched timestamp
	 *
	 * @param int $flags one of the READ_XXX constants.
	 * @return string|false Last-touched timestamp
	 */
	public function getTouched( int $flags = IDBAccessObject::READ_NORMAL ) {
		$touched = $this->getFieldFromPageStore( 'page_touched', $flags );
		return $touched ? MWTimestamp::convert( TS_MW, $touched ) : false;
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
	 * @return Title[]
	 */
	public function getRedirectsHere( $ns = null ) {
		$redirs = [];

		$queryBuilder = $this->getDbProvider()->getReplicaDatabase()->newSelectQueryBuilder()
			->select( [ 'page_namespace', 'page_title' ] )
			->from( 'redirect' )
			->join( 'page', null, 'rd_from = page_id' )
			->where( [
				'rd_namespace' => $this->mNamespace,
				'rd_title' => $this->mDbkeyform,
				'rd_interwiki' => $this->isExternal() ? $this->mInterwiki : '',
			] );

		if ( $ns !== null ) {
			$queryBuilder->andWhere( [ 'page_namespace' => $ns ] );
		}

		$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

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
			foreach ( [ 'Userlogout', ...$wgInvalidRedirectTargets ] as $target ) {
				if ( $this->isSpecial( $target ) ) {
					return false;
				}
			}
			return true;
		}

		// relative section links are not valid redirect targets (T278367)
		return $this->getDBkey() !== '' && $this->isValid();
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
		( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
			->onGetDefaultSortkey( $this, $unprefixed );
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
	 * to true in LocalSettings.php, otherwise returns null. If there is no language saved in
	 * the database, it will return null.
	 *
	 * @param int $flags
	 *
	 * @return ?string
	 */
	private function getDbPageLanguageCode( int $flags = 0 ): ?string {
		global $wgPageLanguageUseDB;

		// check, if the page language could be saved in the database, and if so and
		// the value is not requested already, lookup the page language using PageStore
		if ( $wgPageLanguageUseDB && $this->mDbPageLanguage === false ) {
			$this->mDbPageLanguage = $this->getFieldFromPageStore( 'page_lang', $flags ) ?: null;
		}

		return $this->mDbPageLanguage ?: null;
	}

	/**
	 * Returns the Language object from the page language code saved in the database.
	 * If $wgPageLanguageUseDB is set to false or there is no language saved in the database
	 * or the language code in the database is invalid or unsupported, it will return null.
	 *
	 * @return Language|null
	 */
	private function getDbPageLanguage(): ?Language {
		$languageCode = $this->getDbPageLanguageCode();
		if ( $languageCode === null ) {
			return null;
		}
		$services = MediaWikiServices::getInstance();
		if ( !$services->getLanguageNameUtils()->isKnownLanguageTag( $languageCode ) ) {
			return null;
		}
		return $services->getLanguageFactory()->getLanguage( $languageCode );
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
		global $wgLanguageCode;
		if ( $this->isSpecialPage() ) {
			// special pages are in the user language
			return RequestContext::getMain()->getLanguage();
		}

		// Checking if DB language is set
		$dbPageLanguage = $this->getDbPageLanguage();
		if ( $dbPageLanguage ) {
			return $dbPageLanguage;
		}

		$services = MediaWikiServices::getInstance();
		if ( !$this->mPageLanguage || $this->mPageLanguage[1] !== $wgLanguageCode ) {
			// Note that this may depend on user settings, so the cache should
			// be only per-request.
			// NOTE: ContentHandler::getPageLanguage() may need to load the
			// content to determine the page language!
			// Checking $wgLanguageCode hasn't changed for the benefit of unit
			// tests.
			$contentHandler = $services->getContentHandlerFactory()
				->getContentHandler( $this->getContentModel() );
			$langObj = $contentHandler->getPageLanguage( $this );
			$this->mPageLanguage = [ $langObj->getCode(), $wgLanguageCode ];
		} else {
			$langObj = $services->getLanguageFactory()
				->getLanguage( $this->mPageLanguage[0] );
		}

		return $langObj;
	}

	/**
	 * Get the language in which the content of this page is written when
	 * viewed by user. Defaults to content language, but in certain cases it can be
	 * e.g. the user language (such as special pages).
	 *
	 * @deprecated since 1.42 Use ParserOutput::getLanguage instead. See also OutputPage::getContLangForJS.
	 *   Hard-deprecated since 1.43.
	 * @since 1.20
	 * @return Language
	 */
	public function getPageViewLanguage() {
		wfDeprecated( __METHOD__, '1.42' );
		$services = MediaWikiServices::getInstance();

		if ( $this->isSpecialPage() ) {
			// If the user chooses a variant, the content is actually
			// in a language whose code is the variant code.
			$userLang = RequestContext::getMain()->getLanguage();
			$variant = $this->getLanguageConverter( $userLang )->getPreferredVariant();
			if ( $userLang->getCode() !== $variant ) {
				return $services->getLanguageFactory()
					->getLanguage( $variant );
			}

			return $userLang;
		}

		// Checking if DB language is set
		$pageLang = $this->getDbPageLanguage();
		if ( $pageLang ) {
			$variant = $this->getLanguageConverter( $pageLang )->getPreferredVariant();
			if ( $pageLang->getCode() !== $variant ) {
				return $services->getLanguageFactory()
					->getLanguage( $variant );
			}

			return $pageLang;
		}

		// @note Can't be cached persistently, depends on user settings.
		// @note ContentHandler::getPageViewLanguage() may need to load the
		//   content to determine the page language!
		$contentHandler = $services->getContentHandlerFactory()
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
	 * @return string[]
	 */
	public function getEditNotices( $oldid = 0 ) {
		$notices = [];

		$editnotice_base = 'editnotice-' . $this->mNamespace;
		// Optional notice for the entire namespace
		$messages = [ $editnotice_base => 'namespace' ];

		if (
			MediaWikiServices::getInstance()->getNamespaceInfo()->
				hasSubpages( $this->mNamespace )
		) {
			// Optional notice for page itself and any parent page
			foreach ( explode( '/', $this->mDbkeyform ) as $part ) {
				$editnotice_base .= '-' . $part;
				$messages[$editnotice_base] = 'base';
			}
		} else {
			// Even if there are no subpages in namespace, we still don't want "/" in MediaWiki message keys
			$messages[$editnotice_base . '-' . strtr( $this->mDbkeyform, '/', '-' )] = 'page';
		}

		foreach ( $messages as $editnoticeText => $class ) {
			// The following messages are used here:
			// * editnotice-0
			// * editnotice-0-Title
			// * editnotice-0-Title-Subpage
			// * editnotice-…
			$msg = wfMessage( $editnoticeText )->page( $this );
			if ( $msg->exists() ) {
				$html = $msg->parseAsBlock();
				// Edit notices may have complex logic, but output nothing (T91715)
				if ( trim( $html ) !== '' ) {
					$notices[$editnoticeText] = Html::rawElement(
						'div',
						[ 'class' => [
							'mw-editnotice',
							// The following classes are used here:
							// * mw-editnotice-namespace
							// * mw-editnotice-base
							// * mw-editnotice-page
							"mw-editnotice-$class",
							// The following classes are used here:
							// * mw-editnotice-0
							// * mw-editnotice-…
							Sanitizer::escapeClass( "mw-$editnoticeText" )
						] ],
						$html
					);
				}
			}
		}

		( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
			->onTitleGetEditNotices( $this, $oldid, $notices );
		return $notices;
	}

	/**
	 * @param string $field
	 * @param int $flags Bitfield of IDBAccessObject::READ_* constants
	 * @return string|false
	 */
	private function getFieldFromPageStore( $field, $flags ) {
		$pageStore = MediaWikiServices::getInstance()->getPageStore();

		if ( !in_array( $field, $pageStore->getSelectFields(), true ) ) {
			throw new InvalidArgumentException( "Unknown field: $field" );
		}

		if ( $flags === IDBAccessObject::READ_NORMAL && $this->mArticleID === 0 ) {
			// page does not exist
			return false;
		}

		if ( !$this->canExist() ) {
			return false;
		}

		$page = $pageStore->getPageByReference( $this, $flags );

		if ( $page instanceof PageStoreRecord ) {
			return $page->getField( $field );
		} else {
			// The page record failed to load, remember the page as non-existing.
			// Note that this can happen even if a page ID was known before under some
			// rare circumstances, if this method is called with the READ_LATEST bit set
			// and the page has been deleted since the ID had initially been determined.
			$this->mArticleID = 0;
			return false;
		}
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
		];
	}

	public function __wakeup() {
		$this->mArticleID = ( $this->mNamespace >= 0 ) ? -1 : 0;
		$this->mUrlform = wfUrlencode( $this->mDbkeyform );
		$this->mTextform = strtr( $this->mDbkeyform, '_', ' ' );
	}

	public function __clone() {
		$this->mInstanceCacheKey = null;
	}

	/**
	 * Returns false to indicate that this Title belongs to the local wiki.
	 *
	 * @note The behavior of this method is considered undefined for interwiki links.
	 * At the moment, this method always returns false. But this may change in the future.
	 *
	 * @since 1.36
	 * @return string|false Always self::LOCAL
	 */
	public function getWikiId() {
		return self::LOCAL;
	}

	/**
	 * Returns the page ID.
	 *
	 * If this ID is 0, this means the page does not exist.
	 *
	 * @see getArticleID()
	 * @since 1.36, since 1.35.6 as an alias of getArticleID()
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 *
	 * @throws PreconditionException if this Title instance does not represent a proper page,
	 *         that is, if it is a section link, interwiki link, link to a special page, or such.
	 * @throws PreconditionException if $wikiId is not false.
	 *
	 * @return int
	 */
	public function getId( $wikiId = self::LOCAL ): int {
		$this->assertWiki( $wikiId );
		$this->assertProperPage();
		return $this->getArticleID();
	}

	/**
	 * Code that requires this Title to be a "proper page" in the sense
	 * defined by PageIdentity should call this method.
	 *
	 * For the purpose of the Title class, a proper page is one that can
	 * exist in the page table. That is, a Title represents a proper page
	 * if canExist() returns true.
	 *
	 * @see canExist()
	 *
	 * @throws PreconditionException
	 */
	private function assertProperPage() {
		Assert::precondition(
			$this->canExist(),
			'This Title instance does not represent a proper page, but merely a link target.'
		);
	}

	/**
	 * Returns the page represented by this Title as a ProperPageIdentity.
	 * The ProperPageIdentity returned by this method is guaranteed to be immutable.
	 * If this Title does not represent a proper page, an exception is thrown.
	 *
	 * It is preferred to use this method rather than using the Title as a PageIdentity directly.
	 *
	 * @return ProperPageIdentity
	 * @throws PreconditionException if the page is not a proper page, that is, if it is a section
	 *         link, interwiki link, link to a special page, or such.
	 * @since 1.36
	 */
	public function toPageIdentity(): ProperPageIdentity {
		// TODO: replace individual member fields with a PageIdentityValue that is always present

		$this->assertProperPage();

		return new PageIdentityValue(
			$this->getId(),
			$this->getNamespace(),
			$this->getDBkey(),
			$this->getWikiId()
		);
	}

	/**
	 * Returns the page represented by this Title as a ProperPageRecord.
	 * The PageRecord returned by this method is guaranteed to be immutable,
	 * the page is guaranteed to exist.
	 *
	 * @note For now, this method queries the database on every call.
	 * @since 1.36
	 *
	 * @param int $flags A bitfield of IDBAccessObject::READ_* constants
	 *
	 * @return ExistingPageRecord
	 * @throws PreconditionException if the page does not exist, or is not a proper page,
	 *         that is, if it is a section link, interwiki link, link to a special page, or such.
	 */
	public function toPageRecord( $flags = 0 ): ExistingPageRecord {
		// TODO: Cache this? Construct is more efficiently?

		$this->assertProperPage();

		Assert::precondition(
			$this->exists( $flags ),
			'This Title instance does not represent an existing page: ' . $this
		);

		return new PageStoreRecord(
			(object)[
				'page_id' => $this->getArticleID( $flags ),
				'page_namespace' => $this->getNamespace(),
				'page_title' => $this->getDBkey(),
				'page_wiki_id' => $this->getWikiId(),
				'page_latest' => $this->getLatestRevID( $flags ),
				'page_is_new' => $this->isNewPage( $flags ),
				'page_is_redirect' => $this->isRedirect( $flags ),
				'page_touched' => $this->getTouched( $flags ),
				'page_lang' => $this->getDbPageLanguageCode( $flags ),
			],
			PageIdentity::LOCAL
		);
	}

}
