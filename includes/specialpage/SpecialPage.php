<?php
/**
 * Parent class for all special pages.
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
 * @ingroup SpecialPage
 */

namespace MediaWiki\SpecialPage;

use MediaWiki\Auth\AuthManager;
use MediaWiki\Config\Config;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\ReadOnlyError;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\Language\RawMessage;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Navigation\PagerNavigationBuilder;
use MediaWiki\Output\OutputPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Request\WebRequest;
use MediaWiki\Skin\Skin;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\User;
use MessageLocalizer;
use MWCryptRand;
use SearchEngineFactory;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

/**
 * Parent class for all special pages.
 *
 * Includes some static functions for handling the special page list deprecated
 * in favor of SpecialPageFactory.
 *
 * @stable to extend
 *
 * @ingroup SpecialPage
 */
class SpecialPage implements MessageLocalizer {
	/**
	 * @var string The canonical name of this special page
	 * Also used as the message key for the default <h1> heading,
	 * @see getDescription()
	 */
	protected $mName;

	/** @var string The local name of this special page */
	private $mLocalName;

	/**
	 * @var string Minimum user level required to access this page, or "" for anyone.
	 * Also used to categorise the pages in Special:Specialpages
	 */
	protected $mRestriction;

	/** @var bool Listed in Special:Specialpages? */
	private $mListed;

	/** @var bool Whether or not this special page is being included from an article */
	protected $mIncluding;

	/** @var bool Whether the special page can be included in an article */
	protected $mIncludable;

	/**
	 * Current request context
	 * @var IContextSource
	 */
	protected $mContext;

	/** @var Language|null */
	private $contentLanguage;

	/**
	 * @var LinkRenderer|null
	 */
	private $linkRenderer = null;

	/** @var HookContainer|null */
	private $hookContainer;
	/** @var HookRunner|null */
	private $hookRunner;

	/** @var AuthManager|null */
	private $authManager = null;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/**
	 * Get the users preferred search page.
	 *
	 * It will fall back to Special:Search if the preference points to a page
	 * that doesn't exist or is not defined.
	 *
	 * @since 1.38
	 * @param User $user Search page can be customized by user preference.
	 * @return Title
	 */
	public static function newSearchPage( User $user ) {
		// Try user preference first
		$userOptionsManager = MediaWikiServices::getInstance()->getUserOptionsManager();
		$title = $userOptionsManager->getOption( $user, 'search-special-page' );
		if ( $title ) {
			$page = self::getTitleFor( $title );
			$factory = MediaWikiServices::getInstance()->getSpecialPageFactory();
			if ( $factory->exists( $page->getText() ) ) {
				return $page;
			}
		}
		return self::getTitleFor( 'Search' );
	}

	/**
	 * Get a localised Title object for a specified special page name
	 * If you don't need a full Title object, consider using TitleValue through
	 * getTitleValueFor() below.
	 *
	 * @since 1.9
	 * @since 1.21 $fragment parameter added
	 *
	 * @param string $name
	 * @param string|false|null $subpage Subpage string, or false/null to not use a subpage
	 * @param string $fragment The link fragment (after the "#")
	 * @return Title
	 */
	public static function getTitleFor( $name, $subpage = false, $fragment = '' ) {
		return Title::newFromLinkTarget(
			self::getTitleValueFor( $name, $subpage, $fragment )
		);
	}

	/**
	 * Get a localised TitleValue object for a specified special page name
	 *
	 * @since 1.28
	 * @param string $name
	 * @param string|false|null $subpage Subpage string, or false/null to not use a subpage
	 * @param string $fragment The link fragment (after the "#")
	 * @return TitleValue
	 */
	public static function getTitleValueFor( $name, $subpage = false, $fragment = '' ) {
		$name = MediaWikiServices::getInstance()->getSpecialPageFactory()->
			getLocalNameFor( $name, $subpage );

		return new TitleValue( NS_SPECIAL, $name, $fragment );
	}

	/**
	 * Get a localised Title object for a page name with a possibly unvalidated subpage
	 *
	 * @param string $name
	 * @param string|false $subpage Subpage string, or false to not use a subpage
	 * @return Title|null Title object or null if the page doesn't exist
	 */
	public static function getSafeTitleFor( $name, $subpage = false ) {
		$name = MediaWikiServices::getInstance()->getSpecialPageFactory()->
			getLocalNameFor( $name, $subpage );
		if ( $name ) {
			return Title::makeTitleSafe( NS_SPECIAL, $name );
		} else {
			return null;
		}
	}

	/**
	 * Default constructor for special pages
	 * Derivative classes should call this from their constructor
	 *     Note that if the user does not have the required level, an error message will
	 *     be displayed by the default execute() method, without the global function ever
	 *     being called.
	 *
	 *     If you override execute(), you can recover the default behavior with userCanExecute()
	 *     and displayRestrictionError()
	 *
	 * @stable to call
	 *
	 * @param string $name Name of the special page, as seen in links and URLs
	 * @param string $restriction User right required, e.g. "block" or "delete"
	 * @param bool $listed Whether the page is listed in Special:Specialpages
	 * @param callable|bool $function Unused
	 * @param string $file Unused
	 * @param bool $includable Whether the page can be included in normal pages
	 */
	public function __construct(
		$name = '', $restriction = '', $listed = true,
		$function = false, $file = '', $includable = false
	) {
		$this->mName = $name;
		$this->mRestriction = $restriction;
		$this->mListed = $listed;
		$this->mIncludable = $includable;
	}

	/**
	 * Get the canonical, unlocalized name of this special page without namespace.
	 * @return string
	 */
	public function getName() {
		return $this->mName;
	}

	/**
	 * Get the permission that a user must have to execute this page
	 * @return string
	 */
	public function getRestriction() {
		return $this->mRestriction;
	}

	// @todo FIXME: Decide which syntax to use for this, and stick to it

	/**
	 * Whether this special page is listed in Special:SpecialPages
	 * @stable to override
	 * @since 1.3 (r3583)
	 * @return bool
	 */
	public function isListed() {
		return $this->mListed;
	}

	/**
	 * Whether it's allowed to transclude the special page via {{Special:Foo/params}}
	 * @stable to override
	 * @return bool
	 */
	public function isIncludable() {
		return $this->mIncludable;
	}

	/**
	 * How long to cache page when it is being included.
	 *
	 * @note If cache time is not 0, then the current user becomes an anon
	 *   if you want to do any per-user customizations, than this method
	 *   must be overridden to return 0.
	 * @since 1.26
	 * @stable to override
	 * @return int Time in seconds, 0 to disable caching altogether,
	 *  false to use the parent page's cache settings
	 */
	public function maxIncludeCacheTime() {
		return $this->getConfig()->get( MainConfigNames::MiserMode ) ? $this->getCacheTTL() : 0;
	}

	/**
	 * @stable to override
	 * @return int Seconds that this page can be cached
	 */
	protected function getCacheTTL() {
		return 60 * 60;
	}

	/**
	 * Whether the special page is being evaluated via transclusion
	 * @param bool|null $x
	 * @return bool
	 */
	public function including( $x = null ) {
		return wfSetVar( $this->mIncluding, $x );
	}

	/**
	 * Get the localised name of the special page
	 * @stable to override
	 * @return string
	 */
	public function getLocalName() {
		if ( $this->mLocalName === null ) {
			$this->mLocalName = $this->getSpecialPageFactory()->getLocalNameFor( $this->mName );
		}

		return $this->mLocalName;
	}

	/**
	 * Is this page expensive (for some definition of expensive)?
	 * Expensive pages are disabled or cached in miser mode.  Originally used
	 * (and still overridden) by QueryPage and subclasses, moved here so that
	 * Special:SpecialPages can safely call it for all special pages.
	 *
	 * @stable to override
	 * @return bool
	 */
	public function isExpensive() {
		return false;
	}

	/**
	 * Is this page cached?
	 * Expensive pages are cached or disabled in miser mode.
	 * Used by QueryPage and subclasses, moved here so that
	 * Special:SpecialPages can safely call it for all special pages.
	 *
	 * @stable to override
	 * @return bool
	 * @since 1.21
	 */
	public function isCached() {
		return false;
	}

	/**
	 * Can be overridden by subclasses with more complicated permissions
	 * schemes.
	 *
	 * @stable to override
	 * @return bool Should the page be displayed with the restricted-access
	 *   pages?
	 */
	public function isRestricted() {
		// DWIM: If anons can do something, then it is not restricted
		return $this->mRestriction != '' && !MediaWikiServices::getInstance()
			->getGroupPermissionsLookup()
			->groupHasPermission( '*', $this->mRestriction );
	}

	/**
	 * Checks if the given user (identified by an object) can execute this
	 * special page (as defined by $mRestriction).  Can be overridden by sub-
	 * classes with more complicated permissions schemes.
	 *
	 * @stable to override
	 * @param User $user The user to check
	 * @return bool Does the user have permission to view the page?
	 */
	public function userCanExecute( User $user ) {
		return MediaWikiServices::getInstance()
			->getPermissionManager()
			->userHasRight( $user, $this->mRestriction );
	}

	/**
	 * Utility function for authorizing an action to be performed by the special
	 * page. User blocks and rate limits are enforced implicitly.
	 *
	 * @see Authority::authorizeAction.
	 *
	 * @param ?string $action If not given, the action returned by
	 *        getRestriction() will be used.
	 *
	 * @return PermissionStatus
	 */
	protected function authorizeAction( ?string $action = null ): PermissionStatus {
		$action ??= $this->getRestriction();

		if ( !$action ) {
			return PermissionStatus::newGood();
		}

		$status = PermissionStatus::newEmpty();
		$this->getAuthority()->authorizeAction( $action, $status );
		return $status;
	}

	/**
	 * Output an error message telling the user what access level they have to have
	 * @stable to override
	 * @throws PermissionsError
	 * @return never
	 */
	protected function displayRestrictionError() {
		throw new PermissionsError( $this->mRestriction );
	}

	/**
	 * Checks if userCanExecute, and if not throws a PermissionsError
	 *
	 * @stable to override
	 * @since 1.19
	 * @return void
	 * @throws PermissionsError
	 */
	public function checkPermissions() {
		if ( !$this->userCanExecute( $this->getUser() ) ) {
			$this->displayRestrictionError();
		}
	}

	/**
	 * If the wiki is currently in readonly mode, throws a ReadOnlyError
	 *
	 * @since 1.19
	 * @return void
	 * @throws ReadOnlyError
	 */
	public function checkReadOnly() {
		// Can not inject the ReadOnlyMode as it would break the installer since
		// it instantiates SpecialPageFactory before the DB (via ParserFactory for message parsing)
		if ( MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() ) {
			throw new ReadOnlyError;
		}
	}

	/**
	 * If the user is not logged in, throws UserNotLoggedIn error
	 *
	 * The user will be redirected to Special:Userlogin with the given message as an error on
	 * the form.
	 *
	 * @since 1.23
	 * @param string $reasonMsg [optional] Message key to be displayed on login page
	 * @param string $titleMsg [optional] Passed on to UserNotLoggedIn constructor
	 * @throws UserNotLoggedIn
	 */
	public function requireLogin(
		$reasonMsg = 'exception-nologin-text', $titleMsg = 'exception-nologin'
	) {
		if ( $this->getUser()->isAnon() ) {
			throw new UserNotLoggedIn( $reasonMsg, $titleMsg );
		}
	}

	/**
	 * If the user is not logged in or is a temporary user, throws UserNotLoggedIn
	 *
	 * @since 1.39
	 * @param string $reasonMsg [optional] Message key to be displayed on login page
	 * @param string $titleMsg [optional] Passed on to UserNotLoggedIn constructor. Default 'exception-nologin'
	 *    which is used when $titleMsg is null.
	 * @param bool $alwaysRedirectToLoginPage [optional] Should the redirect always go to Special:UserLogin?
	 *    If false (the default), the redirect will be to Special:CreateAccount when the user is logged in to
	 *    a temporary account.
	 * @throws UserNotLoggedIn
	 */
	public function requireNamedUser(
		$reasonMsg = 'exception-nologin-text', $titleMsg = 'exception-nologin', bool $alwaysRedirectToLoginPage = false
	) {
		if ( !$this->getUser()->isNamed() ) {
			throw new UserNotLoggedIn( $reasonMsg, $titleMsg, [], $alwaysRedirectToLoginPage );
		}
	}

	/**
	 * Tells if the special page does something security-sensitive and needs extra defense against
	 * a stolen account (e.g. a reauthentication). What exactly that will mean is decided by the
	 * authentication framework.
	 * @stable to override
	 * @return string|false False or the argument for AuthManager::securitySensitiveOperationStatus().
	 *   Typically a special page needing elevated security would return its name here.
	 */
	protected function getLoginSecurityLevel() {
		return false;
	}

	/**
	 * Record preserved POST data after a reauthentication.
	 *
	 * This is called from checkLoginSecurityLevel() when returning from the
	 * redirect for reauthentication, if the redirect had been served in
	 * response to a POST request.
	 *
	 * The base SpecialPage implementation does nothing. If your subclass uses
	 * getLoginSecurityLevel() or checkLoginSecurityLevel(), it should probably
	 * implement this to do something with the data.
	 *
	 * @note Call self::setAuthManager from special page constructor when overriding
	 *
	 * @stable to override
	 * @since 1.32
	 * @param array $data
	 */
	protected function setReauthPostData( array $data ) {
	}

	/**
	 * Verifies that the user meets the security level, possibly reauthenticating them in the process.
	 *
	 * This should be used when the page does something security-sensitive and needs extra defense
	 * against a stolen account (e.g. a reauthentication). The authentication framework will make
	 * an extra effort to make sure the user account is not compromised. What that exactly means
	 * will depend on the system and user settings; e.g. the user might be required to log in again
	 * unless their last login happened recently, or they might be given a second-factor challenge.
	 *
	 * Calling this method will result in one if these actions:
	 * - return true: all good.
	 * - return false and set a redirect: caller should abort; the redirect will take the user
	 *   to the login page for reauthentication, and back.
	 * - throw an exception if there is no way for the user to meet the requirements without using
	 *   a different access method (e.g. this functionality is only available from a specific IP).
	 *
	 * Note that this does not in any way check that the user is authorized to use this special page
	 * (use checkPermissions() for that).
	 *
	 * @param string|null $level A security level. Can be an arbitrary string, defaults to the page
	 *   name.
	 * @return bool False means a redirect to the reauthentication page has been set and processing
	 *   of the special page should be aborted.
	 * @throws ErrorPageError If the security level cannot be met, even with reauthentication.
	 */
	protected function checkLoginSecurityLevel( $level = null ) {
		$level = $level ?: $this->getName();
		$key = 'SpecialPage:reauth:' . $this->getName();
		$request = $this->getRequest();

		$securityStatus = $this->getAuthManager()->securitySensitiveOperationStatus( $level );
		if ( $securityStatus === AuthManager::SEC_OK ) {
			$uniqueId = $request->getVal( 'postUniqueId' );
			if ( $uniqueId ) {
				$key .= ':' . $uniqueId;
				$session = $request->getSession();
				$data = $session->getSecret( $key );
				if ( $data ) {
					$session->remove( $key );
					$this->setReauthPostData( $data );
				}
			}
			return true;
		} elseif ( $securityStatus === AuthManager::SEC_REAUTH ) {
			$title = self::getTitleFor( 'Userlogin' );
			$queryParams = $request->getQueryValues();

			if ( $request->wasPosted() ) {
				$data = array_diff_assoc( $request->getValues(), $request->getQueryValues() );
				if ( $data ) {
					// unique ID in case the same special page is open in multiple browser tabs
					$uniqueId = MWCryptRand::generateHex( 6 );
					$key .= ':' . $uniqueId;
					$queryParams['postUniqueId'] = $uniqueId;
					$session = $request->getSession();
					$session->persist(); // Just in case
					$session->setSecret( $key, $data );
				}
			}

			$query = [
				'returnto' => $this->getFullTitle()->getPrefixedDBkey(),
				'returntoquery' => wfArrayToCgi( array_diff_key( $queryParams, [ 'title' => true ] ) ),
				'force' => $level,
			];
			$url = $title->getFullURL( $query, false, PROTO_HTTPS );

			$this->getOutput()->redirect( $url );
			return false;
		}

		$titleMessage = wfMessage( 'specialpage-securitylevel-not-allowed-title' );
		$errorMessage = wfMessage( 'specialpage-securitylevel-not-allowed' );
		throw new ErrorPageError( $titleMessage, $errorMessage );
	}

	/**
	 * Set the injected AuthManager from the special page constructor
	 *
	 * @since 1.36
	 * @param AuthManager $authManager
	 */
	final protected function setAuthManager( AuthManager $authManager ) {
		$this->authManager = $authManager;
	}

	/**
	 * @note Call self::setAuthManager from special page constructor when using
	 *
	 * @since 1.36
	 * @return AuthManager
	 */
	final protected function getAuthManager(): AuthManager {
		if ( $this->authManager === null ) {
			// Fallback if not provided
			// TODO Change to wfWarn in a future release
			$this->authManager = MediaWikiServices::getInstance()->getAuthManager();
		}
		return $this->authManager;
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * For example, if a page supports subpages "foo", "bar" and "baz" (as in Special:PageName/foo,
	 * etc.):
	 *
	 *   - `prefixSearchSubpages( "ba" )` should return `[ "bar", "baz" ]`
	 *   - `prefixSearchSubpages( "f" )` should return `[ "foo" ]`
	 *   - `prefixSearchSubpages( "z" )` should return `[]`
	 *   - `prefixSearchSubpages( "" )` should return `[ foo", "bar", "baz" ]`
	 *
	 * @stable to override
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		$subpages = $this->getSubpagesForPrefixSearch();
		if ( !$subpages ) {
			return [];
		}

		return self::prefixSearchArray( $search, $limit, $subpages, $offset );
	}

	/**
	 * Return an array of subpages that this special page will accept for prefix
	 * searches. If this method requires a query you might instead want to implement
	 * prefixSearchSubpages() directly so you can support $limit and $offset. This
	 * method is better for static-ish lists of things.
	 *
	 * @stable to override
	 * @return string[] subpages to search from
	 */
	protected function getSubpagesForPrefixSearch() {
		return [];
	}

	/**
	 * Return an array of strings representing page titles that are discoverable to end users via UI.
	 *
	 * @since 1.39
	 * @stable to call or override
	 * @return string[] strings representing page titles that can be rendered by skins if required.
	 */
	public function getAssociatedNavigationLinks() {
		return [];
	}

	/**
	 * Perform a regular substring search for prefixSearchSubpages
	 * @since 1.36 Added $searchEngineFactory parameter
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @param SearchEngineFactory|null $searchEngineFactory Provide the service
	 * @return string[] Matching subpages
	 */
	protected function prefixSearchString(
		$search,
		$limit,
		$offset,
		?SearchEngineFactory $searchEngineFactory = null
	) {
		$title = Title::newFromText( $search );
		if ( !$title || !$title->canExist() ) {
			// No prefix suggestion in special and media namespace
			return [];
		}

		$searchEngine = $searchEngineFactory
			? $searchEngineFactory->create()
			// Fallback if not provided
			// TODO Change to wfWarn in a future release
			: MediaWikiServices::getInstance()->newSearchEngine();
		$searchEngine->setLimitOffset( $limit, $offset );
		$searchEngine->setNamespaces( [] );
		$result = $searchEngine->defaultPrefixSearch( $search );
		return array_map( static function ( Title $t ) {
			return $t->getPrefixedText();
		}, $result );
	}

	/**
	 * Helper function for implementations of prefixSearchSubpages() that
	 * filter the values in memory (as opposed to making a query).
	 *
	 * @since 1.24
	 * @param string $search
	 * @param int $limit
	 * @param array $subpages
	 * @param int $offset
	 * @return string[]
	 */
	protected static function prefixSearchArray( $search, $limit, array $subpages, $offset ) {
		$escaped = preg_quote( $search, '/' );
		return array_slice( preg_grep( "/^$escaped/i",
			array_slice( $subpages, $offset ) ), 0, $limit );
	}

	/**
	 * Sets headers - this should be called from the execute() method of all derived classes!
	 * @stable to override
	 */
	protected function setHeaders() {
		$out = $this->getOutput();
		$out->setArticleRelated( false );
		$out->setRobotPolicy( $this->getRobotPolicy() );
		$title = $this->getDescription();
		// T343849
		if ( is_string( $title ) ) {
			wfDeprecated( "string return from {$this->getName()}::getDescription()", '1.41' );
			$title = ( new RawMessage( '$1' ) )->rawParams( $title );
		}
		$out->setPageTitleMsg( $title );
	}

	/**
	 * Entry point.
	 *
	 * @since 1.20
	 *
	 * @param string|null $subPage
	 */
	final public function run( $subPage ) {
		$scope = LoggerFactory::getContext()->addScoped( [
			'context.special_page_name' => $this->getName(),
			'context.special_page_subpage' => $subPage ?? '',
		] );
		if ( !$this->getHookRunner()->onSpecialPageBeforeExecute( $this, $subPage ) ) {
			return;
		}

		if ( $this->beforeExecute( $subPage ) === false ) {
			return;
		}
		$this->execute( $subPage );
		$this->afterExecute( $subPage );

		$this->getHookRunner()->onSpecialPageAfterExecute( $this, $subPage );
	}

	/**
	 * Gets called before @see SpecialPage::execute.
	 * Return false to prevent calling execute() (since 1.27+).
	 *
	 * @stable to override
	 * @since 1.20
	 *
	 * @param string|null $subPage
	 * @return bool|void
	 */
	protected function beforeExecute( $subPage ) {
		// No-op
	}

	/**
	 * Gets called after @see SpecialPage::execute.
	 *
	 * @stable to override
	 * @since 1.20
	 *
	 * @param string|null $subPage
	 */
	protected function afterExecute( $subPage ) {
		// No-op
	}

	/**
	 * Default execute method
	 * Checks user permissions
	 *
	 * This must be overridden by subclasses; it will be made abstract in a future version
	 *
	 * @stable to override
	 *
	 * @param string|null $subPage
	 */
	public function execute( $subPage ) {
		$this->setHeaders();
		$this->checkPermissions();
		$securityLevel = $this->getLoginSecurityLevel();
		if ( $securityLevel !== false && !$this->checkLoginSecurityLevel( $securityLevel ) ) {
			return;
		}
		$this->outputHeader();
	}

	/**
	 * Outputs a summary message on top of special pages
	 * By default the message key is the canonical name of the special page
	 * May be overridden, i.e. by extensions to stick with the naming conventions
	 * for message keys: 'extensionname-xxx'
	 *
	 * @stable to override
	 *
	 * @param string $summaryMessageKey Message key of the summary
	 */
	protected function outputHeader( $summaryMessageKey = '' ) {
		if ( $summaryMessageKey == '' ) {
			$msg = strtolower( $this->getName() ) . '-summary';
		} else {
			$msg = $summaryMessageKey;
		}
		if ( !$this->msg( $msg )->isDisabled() && !$this->including() ) {
			$this->getOutput()->wrapWikiMsg(
				"<div class='mw-specialpage-summary'>\n$1\n</div>", $msg );
		}
	}

	/**
	 * Returns the name that goes in the \<h1\> in the special page itself, and
	 * also the name that will be listed in Special:Specialpages
	 *
	 * Derived classes can override this, but usually it is easier to keep the
	 * default behavior.
	 *
	 * Returning a string from this method has been deprecated since 1.41.
	 *
	 * @stable to override
	 *
	 * @return string|Message
	 */
	public function getDescription() {
		return $this->msg( strtolower( $this->mName ) );
	}

	/**
	 * Similar to getDescription, but takes into account subpages and designed for display
	 * in tabs.
	 *
	 * @since 1.39
	 * @stable to override if special page has complex parameter handling. Use default message keys
	 * where possible.
	 *
	 * @param string $path (optional)
	 * @return string
	 */
	public function getShortDescription( string $path = '' ): string {
		$lowerPath = strtolower( str_replace( '/', '-', $path ) );
		$shortKey = 'special-tab-' . $lowerPath;
		$shortKey .= '-short';
		$msgShort = $this->msg( $shortKey );
		return $msgShort->text();
	}

	/**
	 * Get a self-referential title object
	 *
	 * @param string|false|null $subpage
	 * @return Title
	 * @since 1.23
	 */
	public function getPageTitle( $subpage = false ) {
		return self::getTitleFor( $this->mName, $subpage );
	}

	/**
	 * Sets the context this SpecialPage is executed in
	 *
	 * @param IContextSource $context
	 * @since 1.18
	 */
	public function setContext( $context ) {
		$this->mContext = $context;
	}

	/**
	 * Gets the context this SpecialPage is executed in
	 *
	 * @return IContextSource|RequestContext
	 * @since 1.18
	 */
	public function getContext() {
		if ( !( $this->mContext instanceof IContextSource ) ) {
			wfDebug( __METHOD__ . " called and \$mContext is null. " .
				"Using RequestContext::getMain()" );

			$this->mContext = RequestContext::getMain();
		}
		return $this->mContext;
	}

	/**
	 * Get the WebRequest being used for this instance
	 *
	 * @return WebRequest
	 * @since 1.18
	 */
	public function getRequest() {
		return $this->getContext()->getRequest();
	}

	/**
	 * Get the OutputPage being used for this instance
	 *
	 * @return OutputPage
	 * @since 1.18
	 */
	public function getOutput() {
		return $this->getContext()->getOutput();
	}

	/**
	 * Shortcut to get the User executing this instance
	 *
	 * @return User
	 * @since 1.18
	 */
	public function getUser() {
		return $this->getContext()->getUser();
	}

	/**
	 * Shortcut to get the Authority executing this instance
	 *
	 * @return Authority
	 * @since 1.36
	 */
	public function getAuthority(): Authority {
		return $this->getContext()->getAuthority();
	}

	/**
	 * Shortcut to get the skin being used for this instance
	 *
	 * @return Skin
	 * @since 1.18
	 */
	public function getSkin() {
		return $this->getContext()->getSkin();
	}

	/**
	 * Shortcut to get user's language
	 *
	 * @return Language
	 * @since 1.19
	 */
	public function getLanguage() {
		return $this->getContext()->getLanguage();
	}

	/**
	 * Shortcut to get content language
	 *
	 * @return Language
	 * @since 1.36
	 */
	final public function getContentLanguage(): Language {
		if ( $this->contentLanguage === null ) {
			// Fallback if not provided
			// TODO Change to wfWarn in a future release
			$this->contentLanguage = MediaWikiServices::getInstance()->getContentLanguage();
		}
		return $this->contentLanguage;
	}

	/**
	 * Set content language
	 *
	 * @internal For factory only
	 * @param Language $contentLanguage
	 * @since 1.36
	 */
	final public function setContentLanguage( Language $contentLanguage ) {
		$this->contentLanguage = $contentLanguage;
	}

	/**
	 * Shortcut to get main config object
	 * @return Config
	 * @since 1.24
	 */
	public function getConfig() {
		return $this->getContext()->getConfig();
	}

	/**
	 * Return the full title, including $par
	 *
	 * @return Title
	 * @since 1.18
	 */
	public function getFullTitle() {
		return $this->getContext()->getTitle();
	}

	/**
	 * Return the robot policy. Derived classes that override this can change
	 * the robot policy set by setHeaders() from the default 'noindex,nofollow'.
	 *
	 * @return string
	 * @since 1.23
	 */
	protected function getRobotPolicy() {
		return 'noindex,nofollow';
	}

	/**
	 * Wrapper around wfMessage that sets the current context.
	 *
	 * @since 1.16
	 * @param string|string[]|MessageSpecifier $key
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return Message
	 * @see wfMessage
	 */
	public function msg( $key, ...$params ) {
		$message = $this->getContext()->msg( $key, ...$params );
		// RequestContext passes context to wfMessage, and the language is set from
		// the context, but setting the language for Message class removes the
		// interface message status, which breaks for example usernameless gender
		// invocations. Restore the flag when not including special page in content.
		if ( $this->including() ) {
			$message->setInterfaceMessageFlag( false );
		}

		return $message;
	}

	/**
	 * Adds RSS/atom links
	 *
	 * @param array $params
	 */
	protected function addFeedLinks( $params ) {
		$feedTemplate = wfScript( 'api' );

		foreach ( $this->getConfig()->get( MainConfigNames::FeedClasses ) as $format => $class ) {
			$theseParams = $params + [ 'feedformat' => $format ];
			$url = wfAppendQuery( $feedTemplate, $theseParams );
			$this->getOutput()->addFeedLink( $format, $url );
		}
	}

	/**
	 * Adds help link with an icon via page indicators.
	 * Link target can be overridden by a local message containing a wikilink:
	 * the message key is: lowercase special page name + '-helppage'.
	 * @param string $to Target MediaWiki.org page title or encoded URL.
	 * @param bool $overrideBaseUrl Whether $url is a full URL, to avoid MW.o.
	 * @since 1.25
	 */
	public function addHelpLink( $to, $overrideBaseUrl = false ) {
		if ( $this->including() ) {
			return;
		}

		$msg = $this->msg( strtolower( $this->getName() ) . '-helppage' );

		if ( !$msg->isDisabled() ) {
			$title = Title::newFromText( $msg->plain() );
			if ( $title instanceof Title ) {
				$this->getOutput()->addHelpLink( $title->getLocalURL(), true );
			}
		} else {
			$this->getOutput()->addHelpLink( $to, $overrideBaseUrl );
		}
	}

	/**
	 * Get the group that the special page belongs in on Special:SpecialPage
	 * Use this method, instead of getGroupName to allow customization
	 * of the group name from the wiki side
	 *
	 * @return string Group of this special page
	 * @since 1.21
	 */
	public function getFinalGroupName() {
		$name = $this->getName();

		// Allow overriding the group from the wiki side
		$msg = $this->msg( 'specialpages-specialpagegroup-' . strtolower( $name ) )->inContentLanguage();
		if ( !$msg->isBlank() ) {
			$group = $msg->text();
		} else {
			// Than use the group from this object
			$group = $this->getGroupName();
		}

		return $group;
	}

	/**
	 * Indicates whether POST requests to this special page require write access to the wiki.
	 *
	 * Subclasses must override this method to return true if any of the operations that
	 * they perform on POST requests are not "safe" per RFC 7231 section 4.2.1. A subclass's
	 * operation is "safe" if it is essentially read-only, i.e. the client does not request
	 * nor expect any state change that would be observable in the responses to future requests.
	 *
	 * Implementations of this method must always return the same value, regardless of the
	 * parameters passed to the constructor or system state.
	 *
	 * When handling GET/HEAD requests, subclasses should only perform "safe" operations.
	 * Note that some subclasses might only perform "safe" operations even for POST requests,
	 * particularly in the case where large input parameters are required.
	 *
	 * @stable to override
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function doesWrites() {
		return false;
	}

	/**
	 * Under which header this special page is listed in Special:SpecialPages
	 * See messages 'specialpages-group-*' for valid names
	 * This method defaults to group 'other'
	 *
	 * @stable to override
	 *
	 * @return string
	 * @since 1.21
	 */
	protected function getGroupName() {
		return 'other';
	}

	/**
	 * Call wfTransactionalTimeLimit() if this request was POSTed
	 * @since 1.26
	 */
	protected function useTransactionalTimeLimit() {
		if ( $this->getRequest()->wasPosted() ) {
			wfTransactionalTimeLimit();
		}
	}

	/**
	 * @since 1.28
	 * @return LinkRenderer
	 */
	public function getLinkRenderer(): LinkRenderer {
		if ( $this->linkRenderer === null ) {
			// TODO Inject the service
			$this->linkRenderer = MediaWikiServices::getInstance()->getLinkRendererFactory()
				->create();
		}
		return $this->linkRenderer;
	}

	/**
	 * @since 1.28
	 * @param LinkRenderer $linkRenderer
	 */
	public function setLinkRenderer( LinkRenderer $linkRenderer ) {
		$this->linkRenderer = $linkRenderer;
	}

	/**
	 * Generate (prev x| next x) (20|50|100...) type links for paging
	 *
	 * @param int $offset
	 * @param int $limit
	 * @param array $query Optional URL query parameter string
	 * @param bool $atend Optional param for specified if this is the last page
	 * @param string|false $subpage Optional param for specifying subpage
	 * @return string
	 */
	protected function buildPrevNextNavigation(
		$offset,
		$limit,
		array $query = [],
		$atend = false,
		$subpage = false
	) {
		$navBuilder = new PagerNavigationBuilder( $this );
		$navBuilder
			->setPage( $this->getPageTitle( $subpage ) )
			->setLinkQuery( [ 'limit' => $limit, 'offset' => $offset ] + $query )
			->setLimitLinkQueryParam( 'limit' )
			->setCurrentLimit( $limit )
			->setPrevTooltipMsg( 'prevn-title' )
			->setNextTooltipMsg( 'nextn-title' )
			->setLimitTooltipMsg( 'shown-title' );

		if ( $offset > 0 ) {
			$navBuilder->setPrevLinkQuery( [ 'offset' => (string)max( $offset - $limit, 0 ) ] );
		}
		if ( !$atend ) {
			$navBuilder->setNextLinkQuery( [ 'offset' => (string)( $offset + $limit ) ] );
		}

		return $navBuilder->getHtml();
	}

	/**
	 * @since 1.35
	 * @internal
	 * @param HookContainer $hookContainer
	 */
	public function setHookContainer( HookContainer $hookContainer ) {
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * @since 1.35
	 * @return HookContainer
	 */
	protected function getHookContainer() {
		if ( !$this->hookContainer ) {
			$this->hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		}
		return $this->hookContainer;
	}

	/**
	 * @internal This is for use by core only. Hook interfaces may be removed
	 *   without notice.
	 * @since 1.35
	 * @return HookRunner
	 */
	protected function getHookRunner() {
		if ( !$this->hookRunner ) {
			$this->hookRunner = new HookRunner( $this->getHookContainer() );
		}
		return $this->hookRunner;
	}

	/**
	 * @internal For factory only
	 * @since 1.36
	 * @param SpecialPageFactory $specialPageFactory
	 */
	final public function setSpecialPageFactory( SpecialPageFactory $specialPageFactory ) {
		$this->specialPageFactory = $specialPageFactory;
	}

	/**
	 * @since 1.36
	 * @return SpecialPageFactory
	 */
	final protected function getSpecialPageFactory(): SpecialPageFactory {
		if ( !$this->specialPageFactory ) {
			// Fallback if not provided
			// TODO Change to wfWarn in a future release
			$this->specialPageFactory = MediaWikiServices::getInstance()->getSpecialPageFactory();
		}
		return $this->specialPageFactory;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialPage::class, 'SpecialPage' );
