<?php
namespace MediaWiki\Storage;

use Language;
use Title;
use TitleValue;
use User;
use Wikimedia\Rdbms\IDatabase;


/**
 * Represents a title within MediaWiki.
 * Optionally may contain an interwiki designation or namespace.
 *
 * @note This class can fetch various kinds of data from the database;
 *       however, it does so inefficiently.
 * @note Consider using a TitleValue object instead. TitleValue is more lightweight
 *       and does not rely on global state or the database.
 */
interface PageRecord {
	/**
	 * Get a TitleValue object representing this Title.
	 *
	 * @note Not all valid Titles have a corresponding valid TitleValue
	 * (e.g. TitleValues cannot represent page-local links that have a
	 * fragment but no title text).
	 *
	 * @return TitleValue|null
	 */
	public function getTitleValue();

	/**
	 * Get the main part with underscores
	 *
	 * @return string Main part of the title, with underscores
	 */
	public function getDBkey();

	/**
	 * Get the namespace index, i.e. one of the NS_xxxx constants.
	 *
	 * @return int Namespace index
	 */
	public function getNamespace();

	/**
	 * Get the page's content model id, see the CONTENT_MODEL_XXX constants.
	 *
	 * @param int $flags A bit field; may be Title::GAID_FOR_UPDATE to select for update
	 *
	 * @return string Content model id
	 */
	public function getContentModel( $flags = 0 );

	/**
	 * Can this title have a corresponding talk page?
	 *
	 * @deprecated since 1.30, use canHaveTalkPage() instead.
	 *
	 * @return bool True if this title either is a talk page or can have a talk page associated.
	 */
	public function canTalk();

	/**
	 * Returns true if the title is inside the specified namespace.
	 *
	 * Please make use of this instead of comparing to getNamespace()
	 * This function is much more resistant to changes we may make
	 * to namespaces than code that makes direct comparisons.
	 *
	 * @param int $ns The namespace
	 *
	 * @return bool
	 * @since 1.19
	 */
	public function inNamespace( $ns );

	/**
	 * Returns restriction types for the current Title
	 *
	 * @return array Applicable restriction types
	 */
	public function getRestrictionTypes();

	/**
	 * Is this page "semi-protected" - the *only* protection levels are listed
	 * in $wgSemiprotectedRestrictionLevels?
	 *
	 * @param string $action Action to check (default: edit)
	 *
	 * @return bool
	 */
	public function isSemiProtected( $action = 'edit' );

	/**
	 * Does the title correspond to a protected article?
	 *
	 * @param string $action The action the page is protected from,
	 * by default checks all actions.
	 *
	 * @return bool
	 */
	public function isProtected( $action = '' );

	/**
	 * Accessor/initialisation for mRestrictions
	 *
	 * @param string $action Action that permission needs to be checked for
	 *
	 * @return array Restriction levels needed to take the action. All levels are
	 *     required. Note that restriction levels are normally user rights, but 'sysop'
	 *     and 'autoconfirmed' are also allowed for backwards compatibility. These should
	 *     be mapped to 'editprotected' and 'editsemiprotected' respectively.
	 */
	public function getRestrictions( $action );

	/**
	 * Accessor/initialisation for mRestrictions
	 *
	 * @return array Keys are actions, values are arrays as returned by
	 *     Title::getRestrictions()
	 * @since 1.23
	 */
	public function getAllRestrictions();

	/**
	 * Load restrictions from the page_restrictions table
	 *
	 * @param string $oldFashionedRestrictions Comma-separated list of page
	 *   restrictions from page table (pre 1.10)
	 */
	public function loadRestrictions( $oldFashionedRestrictions = null );

	/**
	 * Is there a version of this page in the deletion archive?
	 *
	 * @return int The number of archived revisions
	 */
	public function isDeleted();

	/**
	 * Get the article ID for this Title from the link cache,
	 * adding it if necessary
	 *
	 * @param int $flags A bit field; may be Title::GAID_FOR_UPDATE to select
	 *  for update
	 *
	 * @return int The ID
	 */
	public function getArticleID( $flags = 0 );

	/**
	 * Is this an article that is a redirect page?
	 * Uses link cache, adding it if necessary
	 *
	 * @param int $flags A bit field; may be Title::GAID_FOR_UPDATE to select for update
	 *
	 * @return bool
	 */
	public function isRedirect( $flags = 0 );

	/**
	 * What is the length of this page?
	 * Uses link cache, adding it if necessary
	 *
	 * @param int $flags A bit field; may be Title::GAID_FOR_UPDATE to select for update
	 *
	 * @return int
	 */
	public function getLength( $flags = 0 );

	/**
	 * What is the page_latest field for this page?
	 *
	 * @param int $flags A bit field; may be Title::GAID_FOR_UPDATE to select for update
	 *
	 * @return int Int or 0 if the page doesn't exist
	 */
	public function getLatestRevID( $flags = 0 );

	/**
	 * Compare with another title.
	 *
	 * @param Title $title
	 *
	 * @return bool
	 */
	public function equals( Title $title );

	/**
	 * Check if page exists.  For historical reasons, this function simply
	 * checks for the existence of the title in the page table, and will
	 * thus return false for interwiki links, special pages and the like.
	 * If you want to know if a title can be meaningfully viewed, you should
	 * probably call the isKnown() method instead.
	 *
	 * @param int $flags An optional bit field; may be Title::GAID_FOR_UPDATE to check
	 *   from master/for update
	 *
	 * @return bool
	 */
	public function exists( $flags = 0 );

	/**
	 * Get the last touched timestamp
	 *
	 * @param IDatabase $db Optional db
	 *
	 * @return string|false Last-touched timestamp
	 */
	public function getTouched( $db = null );

	/**
	 * Get the timestamp when this page was updated since the user last saw it.
	 *
	 * @param User $user
	 *
	 * @return string|null
	 */
	public function getNotificationTimestamp( $user = null );

	/**
	 * Get the language in which the content of this page is written in
	 * wikitext. Defaults to $wgContLang, but in certain cases it can be
	 * e.g. $wgLang (such as special pages, which are in the user language).
	 *
	 * @since 1.18
	 * @return Language
	 */
	public function getPageLanguage();
}