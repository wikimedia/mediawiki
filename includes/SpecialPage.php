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
 * Parent special page class, also static functions for handling the special
 * page list.
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
	 * Initialise the special page list
	 * This must be called before accessing SpecialPage::$mList
	 * @deprecated since 1.18
	 */
	static function initList() {
		wfDeprecated( __METHOD__, '1.18' );
		// Noop
	}

	/**
	 * @deprecated since 1.18
	 */
	static function initAliasList() {
		wfDeprecated( __METHOD__, '1.18' );
		// Noop
	}

	/**
	 * Given a special page alias, return the special page name.
	 * Returns false if there is no such alias.
	 *
	 * @param $alias String
	 * @return String or false
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function resolveAlias( $alias ) {
		wfDeprecated( __METHOD__, '1.18' );
		list( $name, /*...*/ ) = SpecialPageFactory::resolveAlias( $alias );
		return $name;
	}

	/**
	 * Given a special page name with a possible subpage, return an array
	 * where the first element is the special page name and the second is the
	 * subpage.
	 *
	 * @param $alias String
	 * @return Array
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function resolveAliasWithSubpage( $alias ) {
		return SpecialPageFactory::resolveAlias( $alias );
	}

	/**
	 * Add a page to a certain display group for Special:SpecialPages
	 *
	 * @param $page Mixed: SpecialPage or string
	 * @param $group String
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function setGroup( $page, $group ) {
		wfDeprecated( __METHOD__, '1.18' );
		SpecialPageFactory::setGroup( $page, $group );
	}

	/**
	 * Get the group that the special page belongs in on Special:SpecialPage
	 *
	 * @param $page SpecialPage
	 * @return string
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getGroup( &$page ) {
		wfDeprecated( __METHOD__, '1.18' );
		return SpecialPageFactory::getGroup( $page );
	}

	/**
	 * Remove a special page from the list
	 * Formerly used to disable expensive or dangerous special pages. The
	 * preferred method is now to add a SpecialPage_initList hook.
	 * @deprecated since 1.18
	 *
	 * @param string $name the page to remove
	 */
	static function removePage( $name ) {
		wfDeprecated( __METHOD__, '1.18' );
		unset( SpecialPageFactory::getList()->$name );
	}

	/**
	 * Check if a given name exist as a special page or as a special page alias
	 *
	 * @param string $name name of a special page
	 * @return Boolean: true if a special page exists with this name
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function exists( $name ) {
		wfDeprecated( __METHOD__, '1.18' );
		return SpecialPageFactory::exists( $name );
	}

	/**
	 * Find the object with a given name and return it (or NULL)
	 *
	 * @param $name String
	 * @return SpecialPage object or null if the page doesn't exist
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getPage( $name ) {
		wfDeprecated( __METHOD__, '1.18' );
		return SpecialPageFactory::getPage( $name );
	}

	/**
	 * Get a special page with a given localised name, or NULL if there
	 * is no such special page.
	 *
	 * @param $alias String
	 * @return SpecialPage object or null if the page doesn't exist
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getPageByAlias( $alias ) {
		wfDeprecated( __METHOD__, '1.18' );
		return SpecialPageFactory::getPage( $alias );
	}

	/**
	 * Return categorised listable special pages which are available
	 * for the current user, and everyone.
	 *
	 * @param $user User object to check permissions, $wgUser will be used
	 *              if not provided
	 * @return array Associative array mapping page's name to its SpecialPage object
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getUsablePages( User $user = null ) {
		wfDeprecated( __METHOD__, '1.18' );
		return SpecialPageFactory::getUsablePages( $user );
	}

	/**
	 * Return categorised listable special pages for all users
	 *
	 * @return array Associative array mapping page's name to its SpecialPage object
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getRegularPages() {
		wfDeprecated( __METHOD__, '1.18' );
		return SpecialPageFactory::getRegularPages();
	}

	/**
	 * Return categorised listable special pages which are available
	 * for the current user, but not for everyone
	 *
	 * @return array Associative array mapping page's name to its SpecialPage object
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getRestrictedPages() {
		wfDeprecated( __METHOD__, '1.18' );
		return SpecialPageFactory::getRestrictedPages();
	}

	/**
	 * Execute a special page path.
	 * The path may contain parameters, e.g. Special:Name/Params
	 * Extracts the special page name and call the execute method, passing the parameters
	 *
	 * Returns a title object if the page is redirected, false if there was no such special
	 * page, and true if it was successful.
	 *
	 * @param $title          Title object
	 * @param $context        IContextSource
	 * @param $including      Bool output is being captured for use in {{special:whatever}}
	 * @return Bool
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	public static function executePath( &$title, IContextSource &$context, $including = false ) {
		wfDeprecated( __METHOD__, '1.18' );
		return SpecialPageFactory::executePath( $title, $context, $including );
	}

	/**
	 * Get the local name for a specified canonical name
	 *
	 * @param $name String
	 * @param $subpage Mixed: boolean false, or string
	 *
	 * @return String
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getLocalNameFor( $name, $subpage = false ) {
		wfDeprecated( __METHOD__, '1.18' );
		return SpecialPageFactory::getLocalNameFor( $name, $subpage );
	}

	/**
	 * Get a localised Title object for a specified special page name
	 *
	 * @param $name String
	 * @param string|Bool $subpage subpage string, or false to not use a subpage
	 * @param string $fragment the link fragment (after the "#")
	 * @throws MWException
	 * @return Title object
	 */
	public static function getTitleFor( $name, $subpage = false, $fragment = '' ) {
		$name = SpecialPageFactory::getLocalNameFor( $name, $subpage );
		if ( $name ) {
			return Title::makeTitle( NS_SPECIAL, $name, $fragment );
		} else {
			throw new MWException( "Invalid special page name \"$name\"" );
		}
	}

	/**
	 * Get a localised Title object for a page name with a possibly unvalidated subpage
	 *
	 * @param $name String
	 * @param string|Bool $subpage subpage string, or false to not use a subpage
	 * @return Title object or null if the page doesn't exist
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
	 * Get a title for a given alias
	 *
	 * @param $alias String
	 * @return Title or null if there is no such alias
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getTitleForAlias( $alias ) {
		wfDeprecated( __METHOD__, '1.18' );
		return SpecialPageFactory::getTitleForAlias( $alias );
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
	 * @param Callback|Bool $function Function called by execute(). By default
	 * it is constructed from $name
	 * @param string $file File which is included by execute(). It is also
	 * constructed from $name by default
	 * @param bool $includable Whether the page can be included in normal pages
	 */
	public function __construct(
		$name = '', $restriction = '', $listed = true,
		$function = false, $file = 'default', $includable = false
	) {
		$this->init( $name, $restriction, $listed, $function, $file, $includable );
	}

	/**
	 * Do the real work for the constructor, mainly so __call() can intercept
	 * calls to SpecialPage()
	 * @param string $name Name of the special page, as seen in links and URLs
	 * @param string $restriction User right required, e.g. "block" or "delete"
	 * @param bool $listed Whether the page is listed in Special:Specialpages
	 * @param Callback|Bool $function Function called by execute(). By default
	 * it is constructed from $name
	 * @param string $file File which is included by execute(). It is also
	 * constructed from $name by default
	 * @param bool $includable Whether the page can be included in normal pages
	 */
	private function init( $name, $restriction, $listed, $function, $file, $includable ) {
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
	 * Use PHP's magic __call handler to get calls to the old PHP4 constructor
	 * because PHP E_STRICT yells at you for having __construct() and SpecialPage()
	 *
	 * @param string $fName Name of called method
	 * @param array $a Arguments to the method
	 * @throws MWException
	 * @deprecated since 1.17, call parent::__construct()
	 */
	public function __call( $fName, $a ) {
		// Deprecated messages now, remove in 1.19 or 1.20?
		wfDeprecated( __METHOD__, '1.17' );

		// Sometimes $fName is SpecialPage, sometimes it's specialpage. <3 PHP
		if ( strtolower( $fName ) == 'specialpage' ) {
			$name = isset( $a[0] ) ? $a[0] : '';
			$restriction = isset( $a[1] ) ? $a[1] : '';
			$listed = isset( $a[2] ) ? $a[2] : true;
			$function = isset( $a[3] ) ? $a[3] : false;
			$file = isset( $a[4] ) ? $a[4] : 'default';
			$includable = isset( $a[5] ) ? $a[5] : false;
			$this->init( $name, $restriction, $listed, $function, $file, $includable );
		} else {
			$className = get_class( $this );
			throw new MWException( "Call to undefined method $className::$fName" );
		}
	}

	/**
	 * Get the name of this Special Page.
	 * @return String
	 */
	function getName() {
		return $this->mName;
	}

	/**
	 * Get the permission that a user must have to execute this page
	 * @return String
	 */
	function getRestriction() {
		return $this->mRestriction;
	}

	/**
	 * Get the file which will be included by SpecialPage::execute() if your extension is
	 * still stuck in the past and hasn't overridden the execute() method.  No modern code
	 * should want or need to know this.
	 * @return String
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
	 * @return Bool
	 */
	function isListed() {
		return $this->mListed;
	}
	/**
	 * Set whether this page is listed in Special:Specialpages, at run-time
	 * @since r3583 (v1.3)
	 * @param $listed Bool
	 * @return Bool
	 */
	function setListed( $listed ) {
		return wfSetVar( $this->mListed, $listed );
	}
	/**
	 * Get or set whether this special page is listed in Special:SpecialPages
	 * @since r11308 (v1.6)
	 * @param $x Bool
	 * @return Bool
	 */
	function listed( $x = null ) {
		return wfSetVar( $this->mListed, $x );
	}

	/**
	 * Whether it's allowed to transclude the special page via {{Special:Foo/params}}
	 * @return Bool
	 */
	public function isIncludable() {
		return $this->mIncludable;
	}

	/**
	 * These mutators are very evil, as the relevant variables should not mutate.  So
	 * don't use them.
	 * @param $x Mixed
	 * @return Mixed
	 * @deprecated since 1.18
	 */
	function name( $x = null ) {
		wfDeprecated( __METHOD__, '1.18' );
		return wfSetVar( $this->mName, $x );
	}

	/**
	 * These mutators are very evil, as the relevant variables should not mutate.  So
	 * don't use them.
	 * @param $x Mixed
	 * @return Mixed
	 * @deprecated since 1.18
	 */
	function restriction( $x = null ) {
		wfDeprecated( __METHOD__, '1.18' );
		return wfSetVar( $this->mRestriction, $x );
	}

	/**
	 * These mutators are very evil, as the relevant variables should not mutate.  So
	 * don't use them.
	 * @param $x Mixed
	 * @return Mixed
	 * @deprecated since 1.18
	 */
	function func( $x = null ) {
		wfDeprecated( __METHOD__, '1.18' );
		return wfSetVar( $this->mFunction, $x );
	}

	/**
	 * These mutators are very evil, as the relevant variables should not mutate.  So
	 * don't use them.
	 * @param $x Mixed
	 * @return Mixed
	 * @deprecated since 1.18
	 */
	function file( $x = null ) {
		wfDeprecated( __METHOD__, '1.18' );
		return wfSetVar( $this->mFile, $x );
	}

	/**
	 * These mutators are very evil, as the relevant variables should not mutate.  So
	 * don't use them.
	 * @param $x Mixed
	 * @return Mixed
	 * @deprecated since 1.18
	 */
	function includable( $x = null ) {
		wfDeprecated( __METHOD__, '1.18' );
		return wfSetVar( $this->mIncludable, $x );
	}

	/**
	 * Whether the special page is being evaluated via transclusion
	 * @param $x Bool
	 * @return Bool
	 */
	function including( $x = null ) {
		return wfSetVar( $this->mIncluding, $x );
	}

	/**
	 * Get the localised name of the special page
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
	 * @return Boolean
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
	 * @return Boolean
	 * @since 1.21
	 */
	public function isCached() {
		return false;
	}

	/**
	 * Can be overridden by subclasses with more complicated permissions
	 * schemes.
	 *
	 * @return Boolean: should the page be displayed with the restricted-access
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
	 * @param $user User: the user to check
	 * @return Boolean: does the user have permission to view the page?
	 */
	public function userCanExecute( User $user ) {
		return $user->isAllowed( $this->mRestriction );
	}

	/**
	 * Output an error message telling the user what access level they have to have
	 */
	function displayRestrictionError() {
		throw new PermissionsError( $this->mRestriction );
	}

	/**
	 * Checks if userCanExecute, and if not throws a PermissionsError
	 *
	 * @since 1.19
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
	 * @throws ReadOnlyError
	 */
	public function checkReadOnly() {
		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}
	}

	/**
	 * Sets headers - this should be called from the execute() method of all derived classes!
	 */
	function setHeaders() {
		$out = $this->getOutput();
		$out->setArticleRelated( false );
		$out->setRobotPolicy( "noindex,nofollow" );
		$out->setPageTitle( $this->getDescription() );
	}

	/**
	 * Entry point.
	 *
	 * @since 1.20
	 *
	 * @param $subPage string|null
	 */
	final public function run( $subPage ) {
		/**
		 * Gets called before @see SpecialPage::execute.
		 *
		 * @since 1.20
		 *
		 * @param $special SpecialPage
		 * @param $subPage string|null
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
		 * @param $special SpecialPage
		 * @param $subPage string|null
		 */
		wfRunHooks( 'SpecialPageAfterExecute', array( $this, $subPage ) );
	}

	/**
	 * Gets called before @see SpecialPage::execute.
	 *
	 * @since 1.20
	 *
	 * @param $subPage string|null
	 */
	protected function beforeExecute( $subPage ) {
		// No-op
	}

	/**
	 * Gets called after @see SpecialPage::execute.
	 *
	 * @since 1.20
	 *
	 * @param $subPage string|null
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
	 * @param $subPage string|null
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
	 * @param string $summaryMessageKey message key of the summary
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
	 * default behavior. Messages can be added at run-time, see
	 * MessageCache.php.
	 *
	 * @return String
	 */
	function getDescription() {
		return $this->msg( strtolower( $this->mName ) )->text();
	}

	/**
	 * Get a self-referential title object
	 *
	 * @param $subpage String|Bool
	 * @return Title object
	 */
	function getTitle( $subpage = false ) {
		return self::getTitleFor( $this->mName, $subpage );
	}

	/**
	 * Sets the context this SpecialPage is executed in
	 *
	 * @param $context IContextSource
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
	 * Wrapper around wfMessage that sets the current context.
	 *
	 * @return Message
	 * @see wfMessage
	 */
	public function msg( /* $args */ ) {
		// Note: can't use func_get_args() directly as second or later item in
		// a parameter list until PHP 5.3 or you get a fatal error.
		// Works fine as the first parameter, which appears elsewhere in the
		// code base. Sighhhh.
		$args = func_get_args();
		$message = call_user_func_array( array( $this->getContext(), 'msg' ), $args );
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
	 * @param $params array
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
		$group = '-';

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

/**
 * Special page which uses an HTMLForm to handle processing.  This is mostly a
 * clone of FormAction.  More special pages should be built this way; maybe this could be
 * a new structure for SpecialPages
 */
abstract class FormSpecialPage extends SpecialPage {
	/**
	 * The sub-page of the special page.
	 * @var string
	 */
	protected $par = null;

	/**
	 * Get an HTMLForm descriptor array
	 * @return Array
	 */
	abstract protected function getFormFields();

	/**
	 * Add pre-text to the form
	 * @return String HTML which will be sent to $form->addPreText()
	 */
	protected function preText() {
		return '';
	}

	/**
	 * Add post-text to the form
	 * @return String HTML which will be sent to $form->addPostText()
	 */
	protected function postText() {
		return '';
	}

	/**
	 * Play with the HTMLForm if you need to more substantially
	 * @param $form HTMLForm
	 */
	protected function alterForm( HTMLForm $form ) {
	}

	/**
	 * Get message prefix for HTMLForm
	 *
	 * @since 1.21
	 * @return string
	 */
	protected function getMessagePrefix() {
		return strtolower( $this->getName() );
	}

	/**
	 * Get the HTMLForm to control behavior
	 * @return HTMLForm|null
	 */
	protected function getForm() {
		$this->fields = $this->getFormFields();

		$form = new HTMLForm( $this->fields, $this->getContext(), $this->getMessagePrefix() );
		$form->setSubmitCallback( array( $this, 'onSubmit' ) );
		// If the form is a compact vertical form, then don't output this ugly
		// fieldset surrounding it.
		// XXX Special pages can setDisplayFormat to 'vform' in alterForm(), but that
		// is called after this.
		if ( !$form->isVForm() ) {
			$form->setWrapperLegendMsg( $this->getMessagePrefix() . '-legend' );
		}

		$headerMsg = $this->msg( $this->getMessagePrefix() . '-text' );
		if ( !$headerMsg->isDisabled() ) {
			$form->addHeaderText( $headerMsg->parseAsBlock() );
		}

		// Retain query parameters (uselang etc)
		$params = array_diff_key(
			$this->getRequest()->getQueryValues(), array( 'title' => null ) );
		$form->addHiddenField( 'redirectparams', wfArrayToCgi( $params ) );

		$form->addPreText( $this->preText() );
		$form->addPostText( $this->postText() );
		$this->alterForm( $form );

		// Give hooks a chance to alter the form, adding extra fields or text etc
		wfRunHooks( "Special{$this->getName()}BeforeFormDisplay", array( &$form ) );

		return $form;
	}

	/**
	 * Process the form on POST submission.
	 * @param  $data Array
	 * @return Bool|Array true for success, false for didn't-try, array of errors on failure
	 */
	abstract public function onSubmit( array $data );

	/**
	 * Do something exciting on successful processing of the form, most likely to show a
	 * confirmation message
	 * @since 1.22 Default is to do nothing
	 */
	public function onSuccess() {
	}

	/**
	 * Basic SpecialPage workflow: get a form, send it to the user; get some data back,
	 *
	 * @param string $par Subpage string if one was specified
	 */
	public function execute( $par ) {
		$this->setParameter( $par );
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->checkExecutePermissions( $this->getUser() );

		$form = $this->getForm();
		if ( $form->show() ) {
			$this->onSuccess();
		}
	}

	/**
	 * Maybe do something interesting with the subpage parameter
	 * @param string $par
	 */
	protected function setParameter( $par ) {
		$this->par = $par;
	}

	/**
	 * Called from execute() to check if the given user can perform this action.
	 * Failures here must throw subclasses of ErrorPageError.
	 * @param $user User
	 * @throws UserBlockedError
	 * @return Bool true
	 */
	protected function checkExecutePermissions( User $user ) {
		$this->checkPermissions();

		if ( $this->requiresUnblock() && $user->isBlocked() ) {
			$block = $user->getBlock();
			throw new UserBlockedError( $block );
		}

		if ( $this->requiresWrite() ) {
			$this->checkReadOnly();
		}

		return true;
	}

	/**
	 * Whether this action requires the wiki not to be locked
	 * @return Bool
	 */
	public function requiresWrite() {
		return true;
	}

	/**
	 * Whether this action cannot be executed by a blocked user
	 * @return Bool
	 */
	public function requiresUnblock() {
		return true;
	}
}

/**
 * Shortcut to construct a special page which is unlisted by default
 * @ingroup SpecialPage
 */
class UnlistedSpecialPage extends SpecialPage {
	function __construct( $name, $restriction = '', $function = false, $file = 'default' ) {
		parent::__construct( $name, $restriction, false, $function, $file );
	}

	public function isListed() {
		return false;
	}
}

/**
 * Shortcut to construct an includable special  page
 * @ingroup SpecialPage
 */
class IncludableSpecialPage extends SpecialPage {
	function __construct(
		$name, $restriction = '', $listed = true, $function = false, $file = 'default'
	) {
		parent::__construct( $name, $restriction, $listed, $function, $file, true );
	}

	public function isIncludable() {
		return true;
	}
}

/**
 * Shortcut to construct a special page alias.
 * @ingroup SpecialPage
 */
abstract class RedirectSpecialPage extends UnlistedSpecialPage {

	// Query parameters that can be passed through redirects
	protected $mAllowedRedirectParams = array();

	// Query parameters added by redirects
	protected $mAddedRedirectParams = array();

	public function execute( $par ) {
		$redirect = $this->getRedirect( $par );
		$query = $this->getRedirectQuery();
		// Redirect to a page title with possible query parameters
		if ( $redirect instanceof Title ) {
			$url = $redirect->getFullURL( $query );
			$this->getOutput()->redirect( $url );
			return $redirect;
		} elseif ( $redirect === true ) {
			// Redirect to index.php with query parameters
			$url = wfAppendQuery( wfScript( 'index' ), $query );
			$this->getOutput()->redirect( $url );
			return $redirect;
		} else {
			$class = get_class( $this );
			throw new MWException( "RedirectSpecialPage $class doesn't redirect!" );
		}
	}

	/**
	 * If the special page is a redirect, then get the Title object it redirects to.
	 * False otherwise.
	 *
	 * @param string $par Subpage string
	 * @return Title|bool
	 */
	abstract public function getRedirect( $par );

	/**
	 * Return part of the request string for a special redirect page
	 * This allows passing, e.g. action=history to Special:Mypage, etc.
	 *
	 * @return String
	 */
	public function getRedirectQuery() {
		$params = array();

		foreach ( $this->mAllowedRedirectParams as $arg ) {
			if ( $this->getRequest()->getVal( $arg, null ) !== null ) {
				$params[$arg] = $this->getRequest()->getVal( $arg );
			}
		}

		foreach ( $this->mAddedRedirectParams as $arg => $val ) {
			$params[$arg] = $val;
		}

		return count( $params )
			? $params
			: false;
	}
}

abstract class SpecialRedirectToSpecial extends RedirectSpecialPage {
	// @todo FIXME: Visibility must be declared
	var $redirName, $redirSubpage;

	function __construct(
		$name, $redirName, $redirSubpage = false,
		$allowedRedirectParams = array(), $addedRedirectParams = array()
	) {
		parent::__construct( $name );
		$this->redirName = $redirName;
		$this->redirSubpage = $redirSubpage;
		$this->mAllowedRedirectParams = $allowedRedirectParams;
		$this->mAddedRedirectParams = $addedRedirectParams;
	}

	public function getRedirect( $subpage ) {
		if ( $this->redirSubpage === false ) {
			return SpecialPage::getTitleFor( $this->redirName, $subpage );
		} else {
			return SpecialPage::getTitleFor( $this->redirName, $this->redirSubpage );
		}
	}
}

/**
 * ListAdmins --> ListUsers/sysop
 */
class SpecialListAdmins extends SpecialRedirectToSpecial {
	function __construct() {
		parent::__construct( 'Listadmins', 'Listusers', 'sysop' );
	}
}

/**
 * ListBots --> ListUsers/bot
 */
class SpecialListBots extends SpecialRedirectToSpecial {
	function __construct() {
		parent::__construct( 'Listbots', 'Listusers', 'bot' );
	}
}

/**
 * CreateAccount --> UserLogin/signup
 * @todo FIXME: This (and the rest of the login frontend) needs to die a horrible painful death
 */
class SpecialCreateAccount extends SpecialRedirectToSpecial {
	function __construct() {
		parent::__construct( 'CreateAccount', 'Userlogin', 'signup', array( 'returnto', 'returntoquery', 'uselang' ) );
	}

	// No reason to hide this link on Special:Specialpages
	public function isListed() {
		return true;
	}

	protected function getGroupName() {
		return 'login';
	}
}
/**
 * SpecialMypage, SpecialMytalk and SpecialMycontributions special pages
 * are used to get user independent links pointing to the user page, talk
 * page and list of contributions.
 * This can let us cache a single copy of any generated content for all
 * users.
 */

/**
 * Superclass for any RedirectSpecialPage which redirects the user
 * to a particular article (as opposed to user contributions, logs, etc.).
 *
 * For security reasons these special pages are restricted to pass on
 * the following subset of GET parameters to the target page while
 * removing all others:
 *
 * - useskin, uselang, printable: to alter the appearance of the resulting page
 *
 * - redirect: allows viewing one's user page or talk page even if it is a
 * redirect.
 *
 * - rdfrom: allows redirecting to one's user page or talk page from an
 * external wiki with the "Redirect from..." notice.
 *
 * - limit, offset: Useful for linking to history of one's own user page or
 * user talk page. For example, this would be a link to "the last edit to your
 * user talk page in the year 2010":
 * http://en.wikipedia.org/wiki/Special:MyPage?offset=20110000000000&limit=1&action=history
 *
 * - feed: would allow linking to the current user's RSS feed for their user
 * talk page:
 * http://en.wikipedia.org/w/index.php?title=Special:MyTalk&action=history&feed=rss
 *
 * - preloadtitle: Can be used to provide a default section title for a
 * preloaded new comment on one's own talk page.
 *
 * - summary : Can be used to provide a default edit summary for a preloaded
 * edit to one's own user page or talk page.
 *
 * - preview: Allows showing/hiding preview on first edit regardless of user
 * preference, useful for preloaded edits where you know preview wouldn't be
 * useful.
 *
 * - internaledit, externaledit, mode: Allows forcing the use of the
 * internal/external editor, e.g. to force the internal editor for
 * short/simple preloaded edits.
 *
 * - redlink: Affects the message the user sees if their talk page/user talk
 * page does not currently exist. Avoids confusion for newbies with no user
 * pages over why they got a "permission error" following this link:
 * http://en.wikipedia.org/w/index.php?title=Special:MyPage&redlink=1
 *
 * - debug: determines whether the debug parameter is passed to load.php,
 * which disables reformatting and allows scripts to be debugged. Useful
 * when debugging scripts that manipulate one's own user page or talk page.
 *
 * @par Hook extension:
 * Extensions can add to the redirect parameters list by using the hook
 * RedirectSpecialArticleRedirectParams
 *
 * This hook allows extensions which add GET parameters like FlaggedRevs to
 * retain those parameters when redirecting using special pages.
 *
 * @par Hook extension example:
 * @code
 *	$wgHooks['RedirectSpecialArticleRedirectParams'][] =
 *		'MyExtensionHooks::onRedirectSpecialArticleRedirectParams';
 *	public static function onRedirectSpecialArticleRedirectParams( &$redirectParams ) {
 *		$redirectParams[] = 'stable';
 *		return true;
 *	}
 * @endcode
 * @ingroup SpecialPage
 */
abstract class RedirectSpecialArticle extends RedirectSpecialPage {
	function __construct( $name ) {
		parent::__construct( $name );
		$redirectParams = array(
			'action',
			'redirect', 'rdfrom',
			# Options for preloaded edits
			'preload', 'editintro', 'preloadtitle', 'summary', 'nosummary',
			# Options for overriding user settings
			'preview', 'internaledit', 'externaledit', 'mode', 'minor', 'watchthis',
			# Options for history/diffs
			'section', 'oldid', 'diff', 'dir',
			'limit', 'offset', 'feed',
			# Misc options
			'redlink', 'debug',
			# Options for action=raw; missing ctype can break JS or CSS in some browsers
			'ctype', 'maxage', 'smaxage',
		);

		wfRunHooks( "RedirectSpecialArticleRedirectParams", array( &$redirectParams ) );
		$this->mAllowedRedirectParams = $redirectParams;
	}
}

/**
 * Shortcut to construct a special page pointing to current user user's page.
 * @ingroup SpecialPage
 */
class SpecialMypage extends RedirectSpecialArticle {
	function __construct() {
		parent::__construct( 'Mypage' );
	}

	function getRedirect( $subpage ) {
		if ( strval( $subpage ) !== '' ) {
			return Title::makeTitle( NS_USER, $this->getUser()->getName() . '/' . $subpage );
		} else {
			return Title::makeTitle( NS_USER, $this->getUser()->getName() );
		}
	}
}

/**
 * Shortcut to construct a special page pointing to current user talk page.
 * @ingroup SpecialPage
 */
class SpecialMytalk extends RedirectSpecialArticle {
	function __construct() {
		parent::__construct( 'Mytalk' );
	}

	function getRedirect( $subpage ) {
		if ( strval( $subpage ) !== '' ) {
			return Title::makeTitle( NS_USER_TALK, $this->getUser()->getName() . '/' . $subpage );
		} else {
			return Title::makeTitle( NS_USER_TALK, $this->getUser()->getName() );
		}
	}
}

/**
 * Shortcut to construct a special page pointing to current user contributions.
 * @ingroup SpecialPage
 */
class SpecialMycontributions extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'Mycontributions' );
		$this->mAllowedRedirectParams = array( 'limit', 'namespace', 'tagfilter',
			'offset', 'dir', 'year', 'month', 'feed' );
	}

	function getRedirect( $subpage ) {
		return SpecialPage::getTitleFor( 'Contributions', $this->getUser()->getName() );
	}
}

/**
 * Redirect to Special:Listfiles?user=$wgUser
 */
class SpecialMyuploads extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'Myuploads' );
		$this->mAllowedRedirectParams = array( 'limit', 'ilshowall', 'ilsearch' );
	}

	function getRedirect( $subpage ) {
		return SpecialPage::getTitleFor( 'Listfiles', $this->getUser()->getName() );
	}
}

/**
 * Redirect Special:Listfiles?user=$wgUser&ilshowall=true
 */
class SpecialAllMyUploads extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'AllMyUploads' );
		$this->mAllowedRedirectParams = array( 'limit', 'ilsearch' );
	}

	function getRedirect( $subpage ) {
		$this->mAddedRedirectParams['ilshowall'] = 1;
		return SpecialPage::getTitleFor( 'Listfiles', $this->getUser()->getName() );
	}
}


/**
 * Redirect from Special:PermanentLink/### to index.php?oldid=###
 */
class SpecialPermanentLink extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'PermanentLink' );
		$this->mAllowedRedirectParams = array();
	}

	function getRedirect( $subpage ) {
		$subpage = intval( $subpage );
		if ( $subpage === 0 ) {
			# throw an error page when no subpage was given
			throw new ErrorPageError( 'nopagetitle', 'nopagetext' );
		}
		$this->mAddedRedirectParams['oldid'] = $subpage;
		return true;
	}
}
