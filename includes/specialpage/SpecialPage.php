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
 * @todo Turn this into a real ContextSource
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
	private $mRestriction;

	// Listed in Special:Specialpages?
	private $mListed;

	// Function name called by the default execute()
	private $mFunction;

	// File which needs to be included before the function above can be called
	private $mFile;

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
	 * @param callable|bool $function Function called by execute(). By default
	 *   it is constructed from $name
	 * @param string $file File which is included by execute(). It is also
	 *   constructed from $name by default
	 * @param bool $includable Whether the page can be included in normal pages
	 */
	public function __construct(
		$name = '', $restriction = '', $listed = true,
		$function = false, $file = 'default', $includable = false
	) {
		$this->mName = $name;
		$this->mRestriction = $restriction;
		$this->mListed = $listed;
		$this->mIncludable = $includable;
		if ( !$function ) {
			$this->mFunction = 'wfSpecial' . $name;
		} else {
			$this->mFunction = $function;
		}
		if ( $file === 'default' ) {
			$this->mFile = __DIR__ . "/specials/Special$name.php";
		} else {
			$this->mFile = $file;
		}
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

	/**
	 * Get the file which will be included by SpecialPage::execute() if your extension is
	 * still stuck in the past and hasn't overridden the execute() method.  No modern code
	 * should want or need to know this.
	 * @return string
	 * @deprecated since 1.18
	 */
	function getFile() {
		wfDeprecated( __METHOD__, '1.18' );

		return $this->mFile;
	}

	// @todo FIXME: Decide which syntax to use for this, and stick to it
	/**
	 * Whether this special page is listed in Special:SpecialPages
	 * @since r3583 (v1.3)
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
	 * If the user is not logged in, throws UserNotLoggedIn error.
	 *
	 * Default error message includes a link to Special:Userlogin with properly set 'returnto' query
	 * parameter.
	 *
	 * @since 1.23
	 * @param string|Message $reasonMsg [optional] Passed on to UserNotLoggedIn constructor. Strings
	 *     will be used as message keys. If a string is given, the message will also receive a
	 *     formatted login link (generated using the 'loginreqlink' message) as first parameter. If a
	 *     Message is given, it will be passed on verbatim.
	 * @param string|Message $titleMsg [optional] Passed on to UserNotLoggedIn constructor. Strings
	 *     will be used as message keys.
	 * @throws UserNotLoggedIn
	 */
	public function requireLogin( $reasonMsg = null, $titleMsg = null ) {
		if ( $this->getUser()->isAnon() ) {
			// Use default messages if not given or explicit null passed
			if ( !$reasonMsg ) {
				$reasonMsg = 'exception-nologin-text-manual';
			}
			if ( !$titleMsg ) {
				$titleMsg = 'exception-nologin';
			}

			// Convert to Messages with current context
			if ( is_string( $reasonMsg ) ) {
				$loginreqlink = Linker::linkKnown(
					SpecialPage::getTitleFor( 'Userlogin' ),
					$this->msg( 'loginreqlink' )->escaped(),
					array(),
					array( 'returnto' => $this->getPageTitle()->getPrefixedText() )
				);
				$reasonMsg = $this->msg( $reasonMsg )->rawParams( $loginreqlink );
			}
			if ( is_string( $titleMsg ) ) {
				$titleMsg = $this->msg( $titleMsg );
			}

			throw new UserNotLoggedIn( $reasonMsg, $titleMsg );
		}
	}

	/**
	 * Sets headers - this should be called from the execute() method of all derived classes!
	 */
	function setHeaders() {
		$out = $this->getOutput();
		$out->setArticleRelated( false );
		$out->setRobotPolicy( $this->getRobotPolicy() );
		$out->setPageTitle( $this->getDescription() );
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
		wfRunHooks( 'SpecialPageBeforeExecute', array( $this, $subPage ) );

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
		wfRunHooks( 'SpecialPageAfterExecute', array( $this, $subPage ) );
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
	 * Checks user permissions, calls the function given in mFunction
	 *
	 * This must be overridden by subclasses; it will be made abstract in a future version
	 *
	 * @param string|null $subPage
	 */
	public function execute( $subPage ) {
		$this->setHeaders();
		$this->checkPermissions();

		$func = $this->mFunction;
		// only load file if the function does not exist
		if ( !is_callable( $func ) && $this->mFile ) {
			require_once $this->mFile;
		}
		$this->outputHeader();
		call_user_func( $func, $subPage, $this );
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
	 * @deprecated in 1.23, use SpecialPage::getPageTitle
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
	 * @deprecated since 1.19 Use getLanguage instead
	 * @return Language
	 * @since 1.18
	 */
	public function getLang() {
		wfDeprecated( __METHOD__, '1.19' );

		return $this->getLanguage();
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
		global $wgFeedClasses;

		$feedTemplate = wfScript( 'api' );

		foreach ( $wgFeedClasses as $format => $class ) {
			$theseParams = $params + array( 'feedformat' => $format );
			$url = wfAppendQuery( $feedTemplate, $theseParams );
			$this->getOutput()->addFeedLink( $format, $url );
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
		global $wgSpecialPageGroups;
		$name = $this->getName();

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
			if ( $group === '-' && isset( $wgSpecialPageGroups[$name] ) ) {
				$group = $wgSpecialPageGroups[$name];
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
