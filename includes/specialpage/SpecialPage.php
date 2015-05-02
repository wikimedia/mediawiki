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

/**
 * Parent class for all special pages.
 *
 * Includes some static functions for handling the special page list deprecated
 * in favor of SpecialPageFactory.
 *
 * @ingroup SpecialPage
 */
class SpecialPage {
	// The canonical name of this special page
	// Also used for the default <h1> heading, @see getDescription()
	protected $mName;

	// The local name of this special page
	private $mLocalName;

	// Minimum user level required to access this page, or "" for anyone.
	// Also used to categorise the pages in Special:Specialpages
	protected $mRestriction;

	// Listed in Special:Specialpages?
	private $mListed;

	// Whether or not this special page is being included from an article
	protected $mIncluding;

	// Whether the special page can be included in an article
	protected $mIncludable;

	/**
	 * Current request context
	 * @var IContextSource
	 */
	protected $mContext;

	/**
	 * Get a localised Title object for a specified special page name
	 *
	 * @param string $name
	 * @param string|bool $subpage Subpage string, or false to not use a subpage
	 * @param string $fragment The link fragment (after the "#")
	 * @return Title
	 * @throws MWException
	 */
	public static function getTitleFor( $name, $subpage = false, $fragment = '' ) {
		$name = SpecialPageFactory::getLocalNameFor( $name, $subpage );

		return Title::makeTitle( NS_SPECIAL, $name, $fragment );
	}

	/**
	 * Get a localised Title object for a page name with a possibly unvalidated subpage
	 *
	 * @param string $name
	 * @param string|bool $subpage Subpage string, or false to not use a subpage
	 * @return Title|null Title object or null if the page doesn't exist
	 */
	public static function getSafeTitleFor( $name, $subpage = false ) {
		$name = SpecialPageFactory::getLocalNameFor( $name, $subpage );
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
	 * Get the name of this Special Page.
	 * @return string
	 */
	function getName() {
		return $this->mName;
	}

	/**
	 * Get the permission that a user must have to execute this page
	 * @return string
	 */
	function getRestriction() {
		return $this->mRestriction;
	}

	// @todo FIXME: Decide which syntax to use for this, and stick to it
	/**
	 * Whether this special page is listed in Special:SpecialPages
	 * @since 1.3 (r3583)
	 * @return bool
	 */
	function isListed() {
		return $this->mListed;
	}

	/**
	 * Set whether this page is listed in Special:Specialpages, at run-time
	 * @since 1.3
	 * @param bool $listed
	 * @return bool
	 */
	function setListed( $listed ) {
		return wfSetVar( $this->mListed, $listed );
	}

	/**
	 * Get or set whether this special page is listed in Special:SpecialPages
	 * @since 1.6
	 * @param bool $x
	 * @return bool
	 */
	function listed( $x = null ) {
		return wfSetVar( $this->mListed, $x );
	}

	/**
	 * Whether it's allowed to transclude the special page via {{Special:Foo/params}}
	 * @return bool
	 */
	public function isIncludable() {
		return $this->mIncludable;
	}

	/**
	 * Whether the special page is being evaluated via transclusion
	 * @param bool $x
	 * @return bool
	 */
	function including( $x = null ) {
		return wfSetVar( $this->mIncluding, $x );
	}

	/**
	 * Get the localised name of the special page
	 * @return string
	 */
	function getLocalName() {
		if ( !isset( $this->mLocalName ) ) {
			$this->mLocalName = SpecialPageFactory::getLocalNameFor( $this->mName );
		}

		return $this->mLocalName;
	}

	/**
	 * Is this page expensive (for some definition of expensive)?
	 * Expensive pages are disabled or cached in miser mode.  Originally used
	 * (and still overridden) by QueryPage and subclasses, moved here so that
	 * Special:SpecialPages can safely call it for all special pages.
	 *
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
	 * @return bool Should the page be displayed with the restricted-access
	 *   pages?
	 */
	public function isRestricted() {
		// DWIM: If anons can do something, then it is not restricted
		return $this->mRestriction != '' && !User::groupHasPermission( '*', $this->mRestriction );
	}

	/**
	 * Checks if the given user (identified by an object) can execute this
	 * special page (as defined by $mRestriction).  Can be overridden by sub-
	 * classes with more complicated permissions schemes.
	 *
	 * @param User $user The user to check
	 * @return bool Does the user have permission to view the page?
	 */
	public function userCanExecute( User $user ) {
		return $user->isAllowed( $this->mRestriction );
	}

	/**
	 * Output an error message telling the user what access level they have to have
	 * @throws PermissionsError
	 */
	function displayRestrictionError() {
		throw new PermissionsError( $this->mRestriction );
	}

	/**
	 * Checks if userCanExecute, and if not throws a PermissionsError
	 *
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
		if ( wfReadOnly() ) {
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
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * For example, if a page supports subpages "foo", "bar" and "baz" (as in Special:PageName/foo,
	 * etc.):
	 *
	 *   - `prefixSearchSubpages( "ba" )` should return `array( "bar", "baz" )`
	 *   - `prefixSearchSubpages( "f" )` should return `array( "foo" )`
	 *   - `prefixSearchSubpages( "z" )` should return `array()`
	 *   - `prefixSearchSubpages( "" )` should return `array( foo", "bar", "baz" )`
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		$subpages = $this->getSubpagesForPrefixSearch();
		if ( !$subpages ) {
			return array();
		}

		return self::prefixSearchArray( $search, $limit, $subpages, $offset );
	}

	/**
	 * Return an array of subpages that this special page will accept for prefix
	 * searches. If this method requires a query you might instead want to implement
	 * prefixSearchSubpages() directly so you can support $limit and $offset. This
	 * method is better for static-ish lists of things.
	 *
	 * @return string[] subpages to search from
	 */
	protected function getSubpagesForPrefixSearch() {
		return array();
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
	 */
	function setHeaders() {
		$out = $this->getOutput();
		$out->setArticleRelated( false );
		$out->setRobotPolicy( $this->getRobotPolicy() );
		$out->setPageTitle( $this->getDescription() );
		if ( $this->getConfig()->get( 'UseMediaWikiUIEverywhere' ) ) {
			$out->addModuleStyles( array(
				'mediawiki.ui.input',
				'mediawiki.ui.radio',
				'mediawiki.ui.checkbox',
			) );
		}
	}

	/**
	 * Entry point.
	 *
	 * @since 1.20
	 *
	 * @param string|null $subPage
	 */
	final public function run( $subPage ) {
		/**
		 * Gets called before @see SpecialPage::execute.
		 *
		 * @since 1.20
		 *
		 * @param SpecialPage $this
		 * @param string|null $subPage
		 */
		Hooks::run( 'SpecialPageBeforeExecute', array( $this, $subPage ) );

		$this->beforeExecute( $subPage );
		$this->execute( $subPage );
		$this->afterExecute( $subPage );

		/**
		 * Gets called after @see SpecialPage::execute.
		 *
		 * @since 1.20
		 *
		 * @param SpecialPage $this
		 * @param string|null $subPage
		 */
		Hooks::run( 'SpecialPageAfterExecute', array( $this, $subPage ) );
	}

	/**
	 * Gets called before @see SpecialPage::execute.
	 *
	 * @since 1.20
	 *
	 * @param string|null $subPage
	 */
	protected function beforeExecute( $subPage ) {
		// No-op
	}

	/**
	 * Gets called after @see SpecialPage::execute.
	 *
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
	 * @param string|null $subPage
	 */
	public function execute( $subPage ) {
		$this->setHeaders();
		$this->checkPermissions();
		$this->outputHeader();
	}

	/**
	 * Outputs a summary message on top of special pages
	 * Per default the message key is the canonical name of the special page
	 * May be overridden, i.e. by extensions to stick with the naming conventions
	 * for message keys: 'extensionname-xxx'
	 *
	 * @param string $summaryMessageKey Message key of the summary
	 */
	function outputHeader( $summaryMessageKey = '' ) {
		global $wgContLang;

		if ( $summaryMessageKey == '' ) {
			$msg = $wgContLang->lc( $this->getName() ) . '-summary';
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
	 * @return string
	 */
	function getDescription() {
		return $this->msg( strtolower( $this->mName ) )->text();
	}

	/**
	 * Get a self-referential title object
	 *
	 * @param string|bool $subpage
	 * @return Title
	 * @deprecated since 1.23, use SpecialPage::getPageTitle
	 */
	function getTitle( $subpage = false ) {
		return $this->getPageTitle( $subpage );
	}

	/**
	 * Get a self-referential title object
	 *
	 * @param string|bool $subpage
	 * @return Title
	 * @since 1.23
	 */
	function getPageTitle( $subpage = false ) {
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
		if ( $this->mContext instanceof IContextSource ) {
			return $this->mContext;
		} else {
			wfDebug( __METHOD__ . " called and \$mContext is null. " .
				"Return RequestContext::getMain(); for sanity\n" );

			return RequestContext::getMain();
		}
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
	 * @return Message
	 * @see wfMessage
	 */
	public function msg( /* $args */ ) {
		$message = call_user_func_array(
			array( $this->getContext(), 'msg' ),
			func_get_args()
		);
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

		foreach ( $this->getConfig()->get( 'FeedClasses' ) as $format => $class ) {
			$theseParams = $params + array( 'feedformat' => $format );
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
		global $wgContLang;
		$msg = $this->msg( $wgContLang->lc( $this->getName() ) . '-helppage' );

		if ( !$msg->isDisabled() ) {
			$helpUrl = Skin::makeUrl( $msg->plain() );
			$this->getOutput()->addHelpLink( $helpUrl, true );
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
		$specialPageGroups = $this->getConfig()->get( 'SpecialPageGroups' );

		// Allow overbidding the group from the wiki side
		$msg = $this->msg( 'specialpages-specialpagegroup-' . strtolower( $name ) )->inContentLanguage();
		if ( !$msg->isBlank() ) {
			$group = $msg->text();
		} else {
			// Than use the group from this object
			$group = $this->getGroupName();

			// Group '-' is used as default to have the chance to determine,
			// if the special pages overrides this method,
			// if not overridden, $wgSpecialPageGroups is checked for b/c
			if ( $group === '-' && isset( $specialPageGroups[$name] ) ) {
				$group = $specialPageGroups[$name];
			}
		}

		// never give '-' back, change to 'other'
		if ( $group === '-' ) {
			$group = 'other';
		}

		return $group;
	}

	/**
	 * Under which header this special page is listed in Special:SpecialPages
	 * See messages 'specialpages-group-*' for valid names
	 * This method defaults to group 'other'
	 *
	 * @return string
	 * @since 1.21
	 */
	protected function getGroupName() {
		// '-' used here to determine, if this group is overridden or has a hardcoded 'other'
		// Needed for b/c in getFinalGroupName
		return '-';
	}
}
