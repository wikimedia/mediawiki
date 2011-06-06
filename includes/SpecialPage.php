<?php
/**
 * SpecialPage: handling special pages and lists thereof.
 *
 * To add a special page in an extension, add to $wgSpecialPages either
 * an object instance or an array containing the name and constructor
 * parameters. The latter is preferred for performance reasons.
 *
 * The object instantiated must be either an instance of SpecialPage or a
 * sub-class thereof. It must have an execute() method, which sends the HTML
 * for the special page to $wgOut. The parent class has an execute() method
 * which distributes the call to the historical global functions. Additionally,
 * execute() also checks if the user has the necessary access privileges
 * and bails out if not.
 *
 * To add a core special page, use the similar static list in
 * SpecialPage::$mList. To remove a core static special page at runtime, use
 * a SpecialPage_initList hook.
 *
 * @file
 * @ingroup SpecialPage
 * @defgroup SpecialPage SpecialPage
 */

/**
 * Parent special page class, also static functions for handling the special
 * page list.
 * @ingroup SpecialPage
 */
class SpecialPage {

	// The canonical name of this special page
	// Also used for the default <h1> heading, @see getDescription()
	/*private*/ var $mName;

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
	 * @var RequestContext
	 */
	protected $mContext;

	/**
	 * Initialise the special page list
	 * This must be called before accessing SpecialPage::$mList
	 * @deprecated since 1.18
	 */
	static function initList() {
		// Noop
	}

	/**
	 * @deprecated since 1.18
	 */
	static function initAliasList() {
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
	 * Add a page to the list of valid special pages. This used to be the preferred
	 * method for adding special pages in extensions. It's now suggested that you add
	 * an associative record to $wgSpecialPages. This avoids autoloading SpecialPage.
	 *
	 * @param $page SpecialPage
	 * @deprecated since 1.7, warnings in 1.17, might be removed in 1.20
	 */
	static function addPage( &$page ) {
		wfDeprecated( __METHOD__ );
		SpecialPageFactory::getList()->{$page->mName} = $page;
	}

	/**
	 * Add a page to a certain display group for Special:SpecialPages
	 *
	 * @param $page Mixed: SpecialPage or string
	 * @param $group String
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function setGroup( $page, $group ) {
		return SpecialPageFactory::setGroup( $page, $group );
	}

	/**
	 * Add a page to a certain display group for Special:SpecialPages
	 *
	 * @param $page SpecialPage
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getGroup( &$page ) {
		return SpecialPageFactory::getGroup( $page );
	}

	/**
	 * Remove a special page from the list
	 * Formerly used to disable expensive or dangerous special pages. The
	 * preferred method is now to add a SpecialPage_initList hook.
	 * @deprecated since 1.18
	 */
	static function removePage( $name ) {
		unset( SpecialPageFactory::getList()->$name );
	}

	/**
	 * Check if a given name exist as a special page or as a special page alias
	 *
	 * @param $name String: name of a special page
	 * @return Boolean: true if a special page exists with this name
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function exists( $name ) {
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
		return SpecialPageFactory::getPage( $name );
	}

	/**
	 * Get a special page with a given localised name, or NULL if there
	 * is no such special page.
	 *
	 * @return SpecialPage object or null if the page doesn't exist
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getPageByAlias( $alias ) {
		return SpecialPageFactory::getPage( $alias );
	}

	/**
	 * Return categorised listable special pages which are available
	 * for the current user, and everyone.
	 *
	 * @return Associative array mapping page's name to its SpecialPage object
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getUsablePages() {
		return SpecialPageFactory::getUsablePages();
	}

	/**
	 * Return categorised listable special pages for all users
	 *
	 * @return Associative array mapping page's name to its SpecialPage object
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getRegularPages() {
		return SpecialPageFactory::getRegularPages();
	}

	/**
	 * Return categorised listable special pages which are available
	 * for the current user, but not for everyone
	 *
	 * @return Associative array mapping page's name to its SpecialPage object
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getRestrictedPages() {
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
	 * @param $context        RequestContext
	 * @param $including      Bool output is being captured for use in {{special:whatever}}
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	public static function executePath( &$title, RequestContext &$context, $including = false ) {
		return SpecialPageFactory::executePath( $title, $context, $including );
	}

	/**
	 * Just like executePath() except it returns the HTML instead of outputting it
	 * Returns false if there was no such special page, or a title object if it was
	 * a redirect.
	 *
	 * @return String: HTML fragment
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function capturePath( &$title ) {
		return SpecialPageFactory::capturePath( $title );
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
		return SpecialPageFactory::getLocalNameFor( $name, $subpage );
	}

	/**
	 * Get a localised Title object for a specified special page name
	 *
	 * @return Title object
	 */
	public static function getTitleFor( $name, $subpage = false ) {
		$name = SpecialPageFactory::getLocalNameFor( $name, $subpage );
		if ( $name ) {
			return Title::makeTitle( NS_SPECIAL, $name );
		} else {
			throw new MWException( "Invalid special page name \"$name\"" );
		}
	}

	/**
	 * Get a localised Title object for a page name with a possibly unvalidated subpage
	 *
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
	 * @return Title or null if there is no such alias
	 * @deprecated since 1.18 call SpecialPageFactory method directly
	 */
	static function getTitleForAlias( $alias ) {
		return SpecialPageFactory::getTitleForAlias( $alias );
	}

	/**
	 * Default constructor for special pages
	 * Derivative classes should call this from their constructor
	 *     Note that if the user does not have the required level, an error message will
	 *     be displayed by the default execute() method, without the global function ever
	 *     being called.
	 *
	 *     If you override execute(), you can recover the default behaviour with userCanExecute()
	 *     and displayRestrictionError()
	 *
	 * @param $name String: name of the special page, as seen in links and URLs
	 * @param $restriction String: user right required, e.g. "block" or "delete"
	 * @param $listed Boolean: whether the page is listed in Special:Specialpages
	 * @param $function Callback: function called by execute(). By default it is constructed from $name
	 * @param $file String: file which is included by execute(). It is also constructed from $name by default
	 * @param $includable Boolean: whether the page can be included in normal pages
	 */
	public function __construct( $name = '', $restriction = '', $listed = true, $function = false, $file = 'default', $includable = false ) {
		$this->init( $name, $restriction, $listed, $function, $file, $includable );
	}

	/**
	 * Do the real work for the constructor, mainly so __call() can intercept
	 * calls to SpecialPage()
	 * @see __construct() for param docs
	 */
	private function init( $name, $restriction, $listed, $function, $file, $includable ) {
		$this->mName = $name;
		$this->mRestriction = $restriction;
		$this->mListed = $listed;
		$this->mIncludable = $includable;
		if ( !$function ) {
			$this->mFunction = 'wfSpecial'.$name;
		} else {
			$this->mFunction = $function;
		}
		if ( $file === 'default' ) {
			$this->mFile = dirname(__FILE__) . "/specials/Special$name.php";
		} else {
			$this->mFile = $file;
		}
	}

	/**
	 * Use PHP's magic __call handler to get calls to the old PHP4 constructor
	 * because PHP E_STRICT yells at you for having __construct() and SpecialPage()
	 *
	 * @param $fName String Name of called method
	 * @param $a Array Arguments to the method
	 * @deprecated since 1.17, call parent::__construct()
	 */
	public function __call( $fName, $a ) {
		// Sometimes $fName is SpecialPage, sometimes it's specialpage. <3 PHP
		if( strtolower( $fName ) == 'specialpage' ) {
			// Deprecated messages now, remove in 1.19 or 1.20?
			wfDeprecated( __METHOD__ );

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
	 * @return Bool
	 */
	function setListed( $listed ) {
		return wfSetVar( $this->mListed, $listed );
	}
	/**
	 * Get or set whether this special page is listed in Special:SpecialPages
	 * @since r11308 (v1.6)
	 * @return Bool
	 */
	function listed( $x = null) {
		return wfSetVar( $this->mListed, $x );
	}

	/**
	 * Whether it's allowed to transclude the special page via {{Special:Foo/params}}
	 * @return Bool
	 */
	public function isIncludable(){
		return $this->mIncludable;
	}

	/**
	 * These mutators are very evil, as the relevant variables should not mutate.  So
	 * don't use them.
	 * @deprecated since 1.18
	 */
	function name( $x = null ) { return wfSetVar( $this->mName, $x ); }
	function restriction( $x = null) { return wfSetVar( $this->mRestriction, $x ); }
	function func( $x = null) { return wfSetVar( $this->mFunction, $x ); }
	function file( $x = null) { return wfSetVar( $this->mFile, $x ); }
	function includable( $x = null ) { return wfSetVar( $this->mIncludable, $x ); }

	/**
	 * Whether the special page is being evaluated via transclusion
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
	 * Can be overridden by subclasses with more complicated permissions
	 * schemes.
	 *
	 * @return Boolean: should the page be displayed with the restricted-access
	 *   pages?
	 */
	public function isRestricted() {
		global $wgGroupPermissions;
		// DWIM: If all anons can do something, then it is not restricted
		return $this->mRestriction != '' && empty($wgGroupPermissions['*'][$this->mRestriction]);
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
	 * Sets headers - this should be called from the execute() method of all derived classes!
	 */
	function setHeaders() {
		$out = $this->getOutput();
		$out->setArticleRelated( false );
		$out->setRobotPolicy( "noindex,nofollow" );
		$out->setPageTitle( $this->getDescription() );
	}

	/**
	 * Default execute method
	 * Checks user permissions, calls the function given in mFunction
	 *
	 * This must be overridden by subclasses; it will be made abstract in a future version
	 */
	function execute( $par ) {
		$this->setHeaders();

		if ( $this->userCanExecute( $this->getUser() ) ) {
			$func = $this->mFunction;
			// only load file if the function does not exist
			if(!is_callable($func) and $this->mFile) {
				require_once( $this->mFile );
			}
			$this->outputHeader();
			call_user_func( $func, $par, $this );
		} else {
			$this->displayRestrictionError();
		}
	}

	/**
	 * Outputs a summary message on top of special pages
	 * Per default the message key is the canonical name of the special page
	 * May be overriden, i.e. by extensions to stick with the naming conventions
	 * for message keys: 'extensionname-xxx'
	 *
	 * @param $summaryMessageKey String: message key of the summary
	 */
	function outputHeader( $summaryMessageKey = '' ) {
		global $wgContLang;

		if( $summaryMessageKey == '' ) {
			$msg = $wgContLang->lc( $this->getName() ) . '-summary';
		} else {
			$msg = $summaryMessageKey;
		}
		if ( !wfMessage( $msg )->isBlank() and ! $this->including() ) {
			$this->getOutput()->wrapWikiMsg( "<div class='mw-specialpage-summary'>\n$1\n</div>", $msg );
		}

	}

	/**
	 * Returns the name that goes in the \<h1\> in the special page itself, and
	 * also the name that will be listed in Special:Specialpages
	 *
	 * Derived classes can override this, but usually it is easier to keep the
	 * default behaviour. Messages can be added at run-time, see
	 * MessageCache.php.
	 *
	 * @return String
	 */
	function getDescription() {
		return wfMsg( strtolower( $this->mName ) );
	}

	/**
	 * Get a self-referential title object
	 *
	 * @return Title object
	 */
	function getTitle( $subpage = false ) {
		return self::getTitleFor( $this->mName, $subpage );
	}

	/**
	 * Sets the context this SpecialPage is executed in
	 *
	 * @param $context RequestContext
	 * @since 1.18
	 */
	public function setContext( $context ) {
		$this->mContext = $context;
	}

	/**
	 * Gets the context this SpecialPage is executed in
	 *
	 * @return RequestContext
	 * @since 1.18
	 */
	public function getContext() {
		if ( $this->mContext instanceof RequestContext ) {
			return $this->mContext;
		} else {
			wfDebug( __METHOD__ . " called and \$mContext is null. Return RequestContext::getMain(); for sanity\n" );
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
	 * Shortcut to get the skin being used for this instance
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
	 * Return the full title, including $par
	 *
	 * @return Title
	 * @since 1.18
	 */
	public function getFullTitle() {
		return $this->getContext()->getTitle();
	}

	/**
	 * Wrapper around wfMessage that sets the current context. Currently this
	 * is only the title.
	 *
	 * @see wfMessage
	 */
	public function msg( /* $args */ ) {
		return call_user_func_array( 'wfMessage', func_get_args() )->title( $this->getFullTitle() );
	}

	/**
	 * Adds RSS/atom links
	 *
	 * @param $params array
	 */
	protected function addFeedLinks( $params ) {
		global $wgFeedClasses, $wgOut;

		$feedTemplate = wfScript( 'api' ) . '?';

		foreach( $wgFeedClasses as $format => $class ) {
			$theseParams = $params + array( 'feedformat' => $format );
			$url = $feedTemplate . wfArrayToCGI( $theseParams );
			$wgOut->addFeedLink( $format, $url );
		}
	}
}

/**
 * Special page which uses an HTMLForm to handle processing.  This is mostly a
 * clone of FormAction.  More special pages should be built this way; maybe this could be
 * a new structure for SpecialPages
 */
abstract class FormSpecialPage extends SpecialPage {

	/**
	 * Get an HTMLForm descriptor array
	 * @return Array
	 */
	protected abstract function getFormFields();

	/**
	 * Add pre- or post-text to the form
	 * @return String HTML which will be sent to $form->addPreText()
	 */
	protected function preText() { return ''; }
	protected function postText() { return ''; }

	/**
	 * Play with the HTMLForm if you need to more substantially
	 * @param $form HTMLForm
	 */
	protected function alterForm( HTMLForm $form ) {}

	/**
	 * Get the HTMLForm to control behaviour
	 * @return HTMLForm|null
	 */
	protected function getForm() {
		$this->fields = $this->getFormFields();

		// Give hooks a chance to alter the form, adding extra fields or text etc
		wfRunHooks( "Special{$this->getName()}ModifyFormFields", array( &$this->fields ) );

		$form = new HTMLForm( $this->fields, $this->getContext() );
		$form->setSubmitCallback( array( $this, 'onSubmit' ) );
		$form->setWrapperLegend( wfMessage( strtolower( $this->getName() ) . '-legend' ) );
		$form->addHeaderText( wfMessage( strtolower( $this->getName() ) . '-text' )->parseAsBlock() );

		// Retain query parameters (uselang etc)
		$params = array_diff_key( $this->getRequest()->getQueryValues(), array( 'title' => null ) );
		$form->addHiddenField( 'redirectparams', wfArrayToCGI( $params ) );

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
	public abstract function onSubmit( array $data );

	/**
	 * Do something exciting on successful processing of the form, most likely to show a
	 * confirmation message
	 */
	public abstract function onSuccess();

	/**
	 * Basic SpecialPage workflow: get a form, send it to the user; get some data back,
	 */
	public function execute( $par ) {
		$this->setParameter( $par );
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->userCanExecute( $this->getUser() );

		$form = $this->getForm();
		if ( $form->show() ) {
			$this->onSuccess();
		}
	}

	/**
	 * Maybe do something interesting with the subpage parameter
	 * @param $par String
	 */
	protected function setParameter( $par ){}

	/**
	 * Checks if the given user (identified by an object) can perform this action.  Can be
	 * overridden by sub-classes with more complicated permissions schemes.  Failures here
	 * must throw subclasses of ErrorPageError
	 *
	 * @param $user User: the user to check, or null to use the context user
	 * @throws ErrorPageError
	 */
	public function userCanExecute( User $user ) {
		if ( $this->requiresWrite() && wfReadOnly() ) {
			throw new ReadOnlyError();
		}

		if ( $this->getRestriction() !== null && !$user->isAllowed( $this->getRestriction() ) ) {
			throw new PermissionsError( $this->getRestriction() );
		}

		if ( $this->requiresUnblock() && $user->isBlocked() ) {
			$block = $user->mBlock;
			throw new UserBlockedError( $block );
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
class UnlistedSpecialPage extends SpecialPage
{
	function __construct( $name, $restriction = '', $function = false, $file = 'default' ) {
		parent::__construct( $name, $restriction, false, $function, $file );
	}

	public function isListed(){
		return false;
	}
}

/**
 * Shortcut to construct an includable special  page
 * @ingroup SpecialPage
 */
class IncludableSpecialPage extends SpecialPage
{
	function __construct( $name, $restriction = '', $listed = true, $function = false, $file = 'default' ) {
		parent::__construct( $name, $restriction, $listed, $function, $file, true );
	}

	public function isIncludable(){
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

	// Query parameteres added by redirects
	protected $mAddedRedirectParams = array();

	public function execute( $par ){
		$redirect = $this->getRedirect( $par );
		$query = $this->getRedirectQuery();
		// Redirect to a page title with possible query parameters
		if ( $redirect instanceof Title ) {
			$url = $redirect->getFullUrl( $query );
			$this->getContext()->output->redirect( $url );
			wfProfileOut( __METHOD__ );
			return $redirect;
		// Redirect to index.php with query parameters
		} elseif ( $redirect === true ) {
			global $wgScript;
			$url = $wgScript . '?' . wfArrayToCGI( $query );
			$this->getContext()->output->redirect( $url );
			wfProfileOut( __METHOD__ );
			return $redirect;
		} else {
			$class = __CLASS__;
			throw new MWException( "RedirectSpecialPage $class doesn't redirect!" );
		}
	}

	/**
	 * If the special page is a redirect, then get the Title object it redirects to.
	 * False otherwise.
	 *
	 * @return Title|false
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

		foreach( $this->mAllowedRedirectParams as $arg ) {
			if( $this->getRequest()->getVal( $arg, null ) !== null ){
				$params[$arg] = $this->getRequest()->getVal( $arg );
			}
		}

		foreach( $this->mAddedRedirectParams as $arg => $val ) {
			$params[$arg] = $val;
		}

		return count( $params )
			? $params
			: false;
	}
}

abstract class SpecialRedirectToSpecial extends RedirectSpecialPage {

	var $redirName, $redirSubpage;

	function __construct( $name, $redirName, $redirSubpage = false, $allowedRedirectParams = array(), $addedRedirectParams = array() ) {
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
 * ListAdmins --> ListUsers/admin
 */
class SpecialListAdmins extends SpecialRedirectToSpecial {
	function __construct(){
		parent::__construct( 'ListAdmins', 'ListUsers', 'sysop' );
	}
}

/**
 * ListBots --> ListUsers/admin
 */
class SpecialListBots extends SpecialRedirectToSpecial {
	function __construct(){
		parent::__construct( 'ListAdmins', 'ListUsers', 'bot' );
	}
}

/**
 * CreateAccount --> UserLogin/signup
 * @todo FIXME: This (and the rest of the login frontend) needs to die a horrible painful death
 */
class SpecialCreateAccount extends SpecialRedirectToSpecial {
	function __construct(){
		parent::__construct( 'CreateAccount', 'Userlogin', 'signup', array( 'uselang' ) );
	}
}
/**
 * SpecialMypage, SpecialMytalk and SpecialMycontributions special pages
 * are used to get user independant links pointing to the user page, talk
 * page and list of contributions.
 * This can let us cache a single copy of any generated content for all
 * users.
 */

/**
 * Shortcut to construct a special page pointing to current user user's page.
 * @ingroup SpecialPage
 */
class SpecialMypage extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'Mypage' );
		$this->mAllowedRedirectParams = array( 'action' , 'preload' , 'editintro',
			'section', 'oldid', 'diff', 'dir' );
	}

	function getRedirect( $subpage ) {
		global $wgUser;
		if ( strval( $subpage ) !== '' ) {
			return Title::makeTitle( NS_USER, $wgUser->getName() . '/' . $subpage );
		} else {
			return Title::makeTitle( NS_USER, $wgUser->getName() );
		}
	}
}

/**
 * Shortcut to construct a special page pointing to current user talk page.
 * @ingroup SpecialPage
 */
class SpecialMytalk extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'Mytalk' );
		$this->mAllowedRedirectParams = array( 'action' , 'preload' , 'editintro',
			'section', 'oldid', 'diff', 'dir' );
	}

	function getRedirect( $subpage ) {
		global $wgUser;
		if ( strval( $subpage ) !== '' ) {
			return Title::makeTitle( NS_USER_TALK, $wgUser->getName() . '/' . $subpage );
		} else {
			return Title::makeTitle( NS_USER_TALK, $wgUser->getName() );
		}
	}
}

/**
 * Shortcut to construct a special page pointing to current user contributions.
 * @ingroup SpecialPage
 */
class SpecialMycontributions extends RedirectSpecialPage {
	function __construct() {
		parent::__construct(  'Mycontributions' );
		$this->mAllowedRedirectParams = array( 'limit', 'namespace', 'tagfilter',
			'offset', 'dir', 'year', 'month', 'feed' );
	}

	function getRedirect( $subpage ) {
		global $wgUser;
		return SpecialPage::getTitleFor( 'Contributions', $wgUser->getName() );
	}
}

/**
 * Redirect to Special:Listfiles?user=$wgUser
 */
class SpecialMyuploads extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'Myuploads' );
		$this->mAllowedRedirectParams = array( 'limit' );
	}

	function getRedirect( $subpage ) {
		global $wgUser;
		return SpecialPage::getTitleFor( 'Listfiles', $wgUser->getName() );
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
		$this->mAddedRedirectParams['oldid'] = $subpage;
		return true;
	}
}
