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
 * @addtogroup SpecialPage
 */

/**
 * @access private
 */

/**
 * Parent special page class, also static functions for handling the special
 * page list
 */
class SpecialPage
{
	/**#@+
	 * @access private
	 */
	/**
	 * The canonical name of this special page
	 * Also used for the default <h1> heading, @see getDescription()
	 */
	var $mName;
	/**
	 * The local name of this special page
	 */
	var $mLocalName;
	/**
	 * Minimum user level required to access this page, or "" for anyone.
	 * Also used to categorise the pages in Special:Specialpages
	 */
	var $mRestriction;
	/**
	 * Listed in Special:Specialpages?
	 */
	var $mListed;
	/**
	 * Function name called by the default execute()
	 */
	var $mFunction;
	/**
	 * File which needs to be included before the function above can be called
	 */
	var $mFile;
	/**
	 * Whether or not this special page is being included from an article
	 */
	var $mIncluding;
	/**
	 * Whether the special page can be included in an article
	 */
	var $mIncludable;
	/**
	 * Query parameters that can be passed through redirects
	 */
	var $mAllowedRedirectParams = array();

	static public $mList = array(
		'DoubleRedirects'           => array( 'SpecialPage', 'DoubleRedirects' ),
		'BrokenRedirects'           => array( 'SpecialPage', 'BrokenRedirects' ),
		'Disambiguations'           => array( 'SpecialPage', 'Disambiguations' ),

		'Userlogin'                 => array( 'SpecialPage', 'Userlogin' ),
		'Userlogout'                => array( 'UnlistedSpecialPage', 'Userlogout' ),
		'Preferences'               => array( 'SpecialPage', 'Preferences' ),
		'Watchlist'                 => array( 'SpecialPage', 'Watchlist' ),

		'Recentchanges'             => array( 'IncludableSpecialPage', 'Recentchanges' ),
		'Upload'                    => array( 'SpecialPage', 'Upload' ),
		'Imagelist'                 => array( 'SpecialPage', 'Imagelist' ),
		'Newimages'                 => array( 'IncludableSpecialPage', 'Newimages' ),
		'Listusers'                 => array( 'SpecialPage', 'Listusers' ),
		'Statistics'                => array( 'SpecialPage', 'Statistics' ),
		'Randompage'                => array( 'SpecialPage', 'Randompage' ),
		'Lonelypages'               => array( 'SpecialPage', 'Lonelypages' ),
		'Uncategorizedpages'        => array( 'SpecialPage', 'Uncategorizedpages' ),
		'Uncategorizedcategories'   => array( 'SpecialPage', 'Uncategorizedcategories' ),
		'Uncategorizedimages'       => array( 'SpecialPage', 'Uncategorizedimages' ),
		'Unusedcategories'          => array( 'SpecialPage', 'Unusedcategories' ),
		'Unusedimages'              => array( 'SpecialPage', 'Unusedimages' ),
		'Wantedpages'               => array( 'IncludableSpecialPage', 'Wantedpages' ),
		'Wantedcategories'          => array( 'SpecialPage', 'Wantedcategories' ),
		'Mostlinked'                => array( 'SpecialPage', 'Mostlinked' ),
		'Mostlinkedcategories'      => array( 'SpecialPage', 'Mostlinkedcategories' ),
		'Mostcategories'            => array( 'SpecialPage', 'Mostcategories' ),
		'Mostimages'                => array( 'SpecialPage', 'Mostimages' ),
		'Mostrevisions'             => array( 'SpecialPage', 'Mostrevisions' ),
		'Shortpages'                => array( 'SpecialPage', 'Shortpages' ),
		'Longpages'                 => array( 'SpecialPage', 'Longpages' ),
		'Newpages'                  => array( 'IncludableSpecialPage', 'Newpages' ),
		'Ancientpages'              => array( 'SpecialPage', 'Ancientpages' ),
		'Deadendpages'              => array( 'SpecialPage', 'Deadendpages' ),
		'Protectedpages'            => array( 'SpecialPage', 'Protectedpages' ),
		'Allpages'                  => array( 'IncludableSpecialPage', 'Allpages' ),
		'Prefixindex'               => array( 'IncludableSpecialPage', 'Prefixindex' ) ,
		'Ipblocklist'               => array( 'SpecialPage', 'Ipblocklist' ),
		'Specialpages'              => array( 'UnlistedSpecialPage', 'Specialpages' ),
		'Contributions'             => array( 'SpecialPage', 'Contributions' ),
		'Emailuser'                 => array( 'UnlistedSpecialPage', 'Emailuser' ),
		'Whatlinkshere'             => array( 'UnlistedSpecialPage', 'Whatlinkshere' ),
		'Recentchangeslinked'       => array( 'UnlistedSpecialPage', 'Recentchangeslinked' ),
		'Movepage'                  => array( 'UnlistedSpecialPage', 'Movepage' ),
		'Blockme'                   => array( 'UnlistedSpecialPage', 'Blockme' ),
		'Resetpass'                 => array( 'UnlistedSpecialPage', 'Resetpass' ),
		'Booksources'               => 'SpecialBookSources',
		'Categories'                => array( 'SpecialPage', 'Categories' ),
		'Export'                    => array( 'SpecialPage', 'Export' ),
		'Version'                   => array( 'SpecialPage', 'Version' ),
		'Allmessages'               => array( 'SpecialPage', 'Allmessages' ),
		'Log'                       => array( 'SpecialPage', 'Log' ),
		'Blockip'                   => array( 'SpecialPage', 'Blockip', 'block' ),
		'Undelete'                  => array( 'SpecialPage', 'Undelete', 'deletedhistory' ),
		'Import'                    => array( 'SpecialPage', "Import", 'import' ),
		'Lockdb'                    => array( 'SpecialPage', 'Lockdb', 'siteadmin' ),
		'Unlockdb'                  => array( 'SpecialPage', 'Unlockdb', 'siteadmin' ),
		'Userrights'                => array( 'SpecialPage', 'Userrights', 'userrights' ),
		'MIMEsearch'                => array( 'SpecialPage', 'MIMEsearch' ),
		'Unwatchedpages'            => array( 'SpecialPage', 'Unwatchedpages', 'unwatchedpages' ),
		'Listredirects'             => array( 'SpecialPage', 'Listredirects' ),
		'Revisiondelete'            => array( 'SpecialPage', 'Revisiondelete', 'deleterevision' ),
		'Unusedtemplates'           => array( 'SpecialPage', 'Unusedtemplates' ),
		'Randomredirect'            => array( 'SpecialPage', 'Randomredirect' ),

		'Mypage'                    => array( 'SpecialMypage' ),
		'Mytalk'                    => array( 'SpecialMytalk' ),
		'Mycontributions'           => array( 'SpecialMycontributions' ),
		'Listadmins'                => array( 'SpecialRedirectToSpecial', 'Listadmins', 'Listusers', 'sysop' ),
	);

	static public $mAliases;
	static public $mListInitialised = false;

	/**#@-*/

	/**
	 * Initialise the special page list
	 * This must be called before accessing SpecialPage::$mList
	 */
	static function initList() {
		global $wgSpecialPages;
		global $wgDisableCounters, $wgDisableInternalSearch, $wgEmailAuthentication;

		if ( self::$mListInitialised ) {
			return;
		}
		wfProfileIn( __METHOD__ );
		
		# Better to set this now, to avoid infinite recursion in carelessly written hooks
		self::$mListInitialised = true;

		if( !$wgDisableCounters ) {
			self::$mList['Popularpages'] = array( 'SpecialPage', 'Popularpages' );
		}

		if( !$wgDisableInternalSearch ) {
			self::$mList['Search'] = array( 'SpecialPage', 'Search' );
		}

		if( $wgEmailAuthentication ) {
			self::$mList['Confirmemail'] = array( 'UnlistedSpecialPage', 'Confirmemail' );
		}

		# Add extension special pages
		self::$mList = array_merge( self::$mList, $wgSpecialPages );

		# Run hooks
		# This hook can be used to remove undesired built-in special pages
		wfRunHooks( 'SpecialPage_initList', array( &self::$mList ) );
		wfProfileOut( __METHOD__ );
	}

	static function initAliasList() {
		if ( !is_null( self::$mAliases ) ) {
			return;
		}

		global $wgContLang;
		$aliases = $wgContLang->getSpecialPageAliases();
		$missingPages = self::$mList;
		self::$mAliases = array();
		foreach ( $aliases as $realName => $aliasList ) {
			foreach ( $aliasList as $alias ) {
				self::$mAliases[$wgContLang->caseFold( $alias )] = $realName;
			}
			unset( $missingPages[$realName] );
		}
		foreach ( $missingPages as $name => $stuff ) {
			self::$mAliases[$wgContLang->caseFold( $name )] = $name;
		}
	}

	/**
	 * Given a special page alias, return the special page name.
	 * Returns false if there is no such alias.
	 */
	static function resolveAlias( $alias ) {
		global $wgContLang;

		if ( !self::$mListInitialised ) self::initList();
		if ( is_null( self::$mAliases ) ) self::initAliasList();
		$caseFoldedAlias = $wgContLang->caseFold( $alias );
		if ( isset( self::$mAliases[$caseFoldedAlias] ) ) {
			return self::$mAliases[$caseFoldedAlias];
		} else {
			return false;
		}
	}

	/**
	 * Given a special page name with a possible subpage, return an array 
	 * where the first element is the special page name and the second is the
	 * subpage.
	 */
	static function resolveAliasWithSubpage( $alias ) {
		$bits = explode( '/', $alias, 2 );
		$name = self::resolveAlias( $bits[0] );
		if( !isset( $bits[1] ) ) { // bug 2087
			$par = NULL;
		} else {
			$par = $bits[1];
		}
		return array( $name, $par );
	}

	/**
	 * Add a page to the list of valid special pages. This used to be the preferred 
	 * method for adding special pages in extensions. It's now suggested that you add 
	 * an associative record to $wgSpecialPages. This avoids autoloading SpecialPage.
	 *
	 * @param mixed $page Must either be an array specifying a class name and 
	 *                    constructor parameters, or an object. The object,
	 *                    when constructed, must have an execute() method which
	 *                    sends HTML to $wgOut.
	 * @static
	 */
	static function addPage( &$page ) {
		if ( !self::$mListInitialised ) {
			self::initList();
		}
		self::$mList[$page->mName] = $page;
	}

	/**
	 * Remove a special page from the list
	 * Formerly used to disable expensive or dangerous special pages. The 
	 * preferred method is now to add a SpecialPage_initList hook.
	 * 
	 * @static
	 */
	static function removePage( $name ) {
		if ( !self::$mListInitialised ) {
			self::initList();
		}
		unset( self::$mList[$name] );
	}

	/**
	 * Find the object with a given name and return it (or NULL)
	 * @static
	 * @param string $name
	 */
	static function getPage( $name ) {
		if ( !self::$mListInitialised ) {
			self::initList();
		}
		if ( array_key_exists( $name, self::$mList ) ) {
			$rec = self::$mList[$name];
			if ( is_string( $rec ) ) {
				$className = $rec;
				self::$mList[$name] = new $className;
			} elseif ( is_array( $rec ) ) {
				$className = array_shift( $rec );
				self::$mList[$name] = wfCreateObject( $className, $rec );
			}
			return self::$mList[$name];
		} else {
			return NULL;
		}
	}

	/**
	 * Get a special page with a given localised name, or NULL if there
	 * is no such special page.
	 */
	static function getPageByAlias( $alias ) {
		$realName = self::resolveAlias( $alias );
		if ( $realName ) {
			return self::getPage( $realName );
		} else {
			return NULL;
		}
	}

	/**
	 * Return categorised listable special pages for all users
	 * @static
	 */
	static function getRegularPages() {
		if ( !self::$mListInitialised ) {
			self::initList();
		}
		$pages = array();

		foreach ( self::$mList as $name => $rec ) {
			$page = self::getPage( $name );
			if ( $page->isListed() && $page->getRestriction() == '' ) {
				$pages[$name] = $page;
			}
		}
		return $pages;
	}

	/**
	 * Return categorised listable special pages which are available
	 * for the current user, but not for everyone
	 * @static
	 */
	static function getRestrictedPages() {
		global $wgUser;
		if ( !self::$mListInitialised ) {
			self::initList();
		}
		$pages = array();

		foreach ( self::$mList as $name => $rec ) {
			$page = self::getPage( $name );
			if ( $page->isListed() ) {
				$restriction = $page->getRestriction();
				if ( $restriction != '' && $wgUser->isAllowed( $restriction ) ) {
					$pages[$name] = $page;
				}
			}
		}
		return $pages;
	}

	/**
	 * Execute a special page path.
	 * The path	may contain parameters, e.g. Special:Name/Params
	 * Extracts the special page name and call the execute method, passing the parameters
	 *
	 * Returns a title object if the page is redirected, false if there was no such special
	 * page, and true if it was successful.
	 *
	 * @param $title          a title object
	 * @param $including      output is being captured for use in {{special:whatever}}
	 */
	static function executePath( &$title, $including = false ) {
		global $wgOut, $wgTitle, $wgRequest;
		wfProfileIn( __METHOD__ );

		# FIXME: redirects broken due to this call
		$bits = explode( '/', $title->getDBkey(), 2 );
		$name = $bits[0];
		if( !isset( $bits[1] ) ) { // bug 2087
			$par = NULL;
		} else {
			$par = $bits[1];
		}
		$page = SpecialPage::getPageByAlias( $name );

		# Nonexistent?
		if ( !$page ) {
			if ( !$including ) {
				$wgOut->setArticleRelated( false );
				$wgOut->setRobotpolicy( 'noindex,nofollow' );
				$wgOut->setStatusCode( 404 );
				$wgOut->showErrorPage( 'nosuchspecialpage', 'nospecialpagetext' );
			}
			wfProfileOut( __METHOD__ );
			return false;
		}

		# Check for redirect
		if ( !$including ) {
			$redirect = $page->getRedirect( $par );
			if ( $redirect ) {
				$query = $page->getRedirectQuery();
				$url = $redirect->getFullUrl( $query );
				$wgOut->redirect( $url );
				wfProfileOut( __METHOD__ );
				return $redirect;
			}
		}

		# Redirect to canonical alias for GET commands
		# Not for POST, we'd lose the post data, so it's best to just distribute 
		# the request. Such POST requests are possible for old extensions that 
		# generate self-links without being aware that their default name has 
		# changed.
		if ( !$including && $name != $page->getLocalName() && !$wgRequest->wasPosted() ) {
			$query = $_GET;
			unset( $query['title'] );
			$query = wfArrayToCGI( $query );
			$title = $page->getTitle( $par );
			$url = $title->getFullUrl( $query );
			$wgOut->redirect( $url );
			wfProfileOut( __METHOD__ );
			return $redirect;
		}

		if ( $including && !$page->includable() ) {
			wfProfileOut( __METHOD__ );
			return false;
		} elseif ( !$including ) {
			$wgTitle = $page->getTitle();
		}
		$page->including( $including );

		// Execute special page
		$profName = 'Special:' . $page->getName();
		wfProfileIn( $profName );
		$page->execute( $par );
		wfProfileOut( $profName );
		wfProfileOut( __METHOD__ );
		return true;
	}

	/**
	 * Just like executePath() except it returns the HTML instead of outputting it
	 * Returns false if there was no such special page, or a title object if it was
	 * a redirect.
	 * @static
	 */
	static function capturePath( &$title ) {
		global $wgOut, $wgTitle;

		$oldTitle = $wgTitle;
		$oldOut = $wgOut;
		$wgOut = new OutputPage;

		$ret = SpecialPage::executePath( $title, true );
		if ( $ret === true ) {
			$ret = $wgOut->getHTML();
		}
		$wgTitle = $oldTitle;
		$wgOut = $oldOut;
		return $ret;
	}

	/**
	 * Get the local name for a specified canonical name
	 */
	static function getLocalNameFor( $name, $subpage = false ) {
		global $wgContLang;
		$aliases = $wgContLang->getSpecialPageAliases();
		if ( isset( $aliases[$name][0] ) ) {
			$name = $aliases[$name][0];
		}
		if ( $subpage !== false && !is_null( $subpage ) ) {
			$name = "$name/$subpage";
		}
		return $name;
	}

	/**
	 * Get a localised Title object for a specified special page name
	 */
	static function getTitleFor( $name, $subpage = false ) {
		$name = self::getLocalNameFor( $name, $subpage );
		if ( $name ) {
			return Title::makeTitle( NS_SPECIAL, $name );
		} else {
			throw new MWException( "Invalid special page name \"$name\"" );
		}
	}

	/**
	 * Get a localised Title object for a page name with a possibly unvalidated subpage
	 */
	static function getSafeTitleFor( $name, $subpage = false ) {
		$name = self::getLocalNameFor( $name, $subpage );
		if ( $name ) {
			return Title::makeTitleSafe( NS_SPECIAL, $name );
		} else {
			return null;
		}
	}

	/**
	 * Get a title for a given alias
	 * @return Title or null if there is no such alias
	 */
	static function getTitleForAlias( $alias ) {
		$name = self::resolveAlias( $alias );
		if ( $name ) {
			return self::getTitleFor( $name );
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
	 *     If you override execute(), you can recover the default behaviour with userCanExecute()
	 *     and displayRestrictionError()
	 *
	 * @param string $name Name of the special page, as seen in links and URLs
	 * @param string $restriction User right required, e.g. "block" or "delete"
	 * @param boolean $listed Whether the page is listed in Special:Specialpages
	 * @param string $function Function called by execute(). By default it is constructed from $name
	 * @param string $file File which is included by execute(). It is also constructed from $name by default
	 */
	function SpecialPage( $name = '', $restriction = '', $listed = true, $function = false, $file = 'default', $includable = false ) {
		$this->mName = $name;
		$this->mRestriction = $restriction;
		$this->mListed = $listed;
		$this->mIncludable = $includable;
		if ( $function == false ) {
			$this->mFunction = 'wfSpecial'.$name;
		} else {
			$this->mFunction = $function;
		}
		if ( $file === 'default' ) {
			$this->mFile = dirname(__FILE__) . "/Special{$name}.php";
		} else {
			$this->mFile = $file;
		}
	}

	/**#@+
	  * Accessor
	  *
	  * @deprecated
	  */
	function getName() { return $this->mName; }
	function getRestriction() { return $this->mRestriction; }
	function getFile() { return $this->mFile; }
	function isListed() { return $this->mListed; }
	/**#@-*/

	/**#@+
	  * Accessor and mutator
	  */
	function name( $x = NULL ) { return wfSetVar( $this->mName, $x ); }
	function restrictions( $x = NULL) { return wfSetVar( $this->mRestrictions, $x ); }
	function listed( $x = NULL) { return wfSetVar( $this->mListed, $x ); }
	function func( $x = NULL) { return wfSetVar( $this->mFunction, $x ); }
	function file( $x = NULL) { return wfSetVar( $this->mFile, $x ); }
	function includable( $x = NULL ) { return wfSetVar( $this->mIncludable, $x ); }
	function including( $x = NULL ) { return wfSetVar( $this->mIncluding, $x ); }
	/**#@-*/

	/**
	 * Get the localised name of the special page
	 */
	function getLocalName() {
		if ( !isset( $this->mLocalName ) ) {
			$this->mLocalName = self::getLocalNameFor( $this->mName );
		}
		return $this->mLocalName;
	}

	/**
	 * Checks if the given user (identified by an object) can execute this
	 * special page (as defined by $mRestriction)
	 */
	function userCanExecute( &$user ) {
		return $user->isAllowed( $this->mRestriction );
	}

	/**
	 * Output an error message telling the user what access level they have to have
	 */
	function displayRestrictionError() {
		global $wgOut;
		$wgOut->permissionRequired( $this->mRestriction );
	}

	/**
	 * Sets headers - this should be called from the execute() method of all derived classes!
	 */
	function setHeaders() {
		global $wgOut;
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotPolicy( "noindex,nofollow" );
		$wgOut->setPageTitle( $this->getDescription() );
	}

	/**
	 * Default execute method
	 * Checks user permissions, calls the function given in mFunction
	 *
	 * This may be overridden by subclasses. 
	 */
	function execute( $par ) {
		global $wgUser;

		$this->setHeaders();

		if ( $this->userCanExecute( $wgUser ) ) {
			$func = $this->mFunction;
			// only load file if the function does not exist
			if(!function_exists($func) and $this->mFile) {
				require_once( $this->mFile );
			}
			# FIXME: these hooks are broken for extensions and anything else that subclasses SpecialPage. 
			if ( wfRunHooks( 'SpecialPageExecuteBeforeHeader', array( &$this, &$par, &$func ) ) )
				$this->outputHeader();
			if ( ! wfRunHooks( 'SpecialPageExecuteBeforePage', array( &$this, &$par, &$func ) ) )
				return;
			$func( $par, $this );
			if ( ! wfRunHooks( 'SpecialPageExecuteAfterPage', array( &$this, &$par, &$func ) ) )
				return;
		} else {
			$this->displayRestrictionError();
		}
	}

	function outputHeader() {
		global $wgOut, $wgContLang;

		$msg = $wgContLang->lc( $this->name() ) . '-summary';
		$out = wfMsg( $msg );
		if ( ! wfEmptyMsg( $msg, $out ) and  $out !== '' and ! $this->including() )
			$wgOut->addWikiText( $out );

	}

	# Returns the name that goes in the <h1> in the special page itself, and also the name that
	# will be listed in Special:Specialpages
	#
	# Derived classes can override this, but usually it is easier to keep the default behaviour.
	# Messages can be added at run-time, see MessageCache.php
	function getDescription() {
		return wfMsg( strtolower( $this->mName ) );
	}

	/**
	 * Get a self-referential title object
	 */
	function getTitle( $subpage = false) {
		return self::getTitleFor( $this->mName, $subpage );
	}

	/**
	 * Set whether this page is listed in Special:Specialpages, at run-time
	 */
	function setListed( $listed ) {
		return wfSetVar( $this->mListed, $listed );
	}

	/**
	 * If the special page is a redirect, then get the Title object it redirects to. 
	 * False otherwise.
	 */
	function getRedirect( $subpage ) {
		return false;
	}

	/**
	 * Return part of the request string for a special redirect page
	 * This allows passing, e.g. action=history to Special:Mypage, etc.
	 *
	 * @return string
	 */
	function getRedirectQuery() {
		global $wgRequest;
		$params = array();
		foreach( $this->mAllowedRedirectParams as $arg ) {
			if( $val = $wgRequest->getVal( $arg, false ) )
				$params[] = $arg . '=' . $val;
		}
		
		return count( $params ) ? implode( '&', $params ) : false;
	}
}

/**
 * Shortcut to construct a special page which is unlisted by default
 */
class UnlistedSpecialPage extends SpecialPage
{
	function UnlistedSpecialPage( $name, $restriction = '', $function = false, $file = 'default' ) {
		SpecialPage::SpecialPage( $name, $restriction, false, $function, $file );
	}
}

/**
 * Shortcut to construct an includable special  page
 */
class IncludableSpecialPage extends SpecialPage
{
	function IncludableSpecialPage( $name, $restriction = '', $listed = true, $function = false, $file = 'default' ) {
		SpecialPage::SpecialPage( $name, $restriction, $listed, $function, $file, true );
	}
}

class SpecialRedirectToSpecial extends UnlistedSpecialPage {
	var $redirName, $redirSubpage;

	function __construct( $name, $redirName, $redirSubpage = false, $redirectParams = array() ) {
		parent::__construct( $name );
		$this->redirName = $redirName;
		$this->redirSubpage = $redirSubpage;
		$this->mAllowedRedirectParams = $redirectParams;
	}

	function getRedirect( $subpage ) {
		if ( $this->redirSubpage === false ) {
			return SpecialPage::getTitleFor( $this->redirName, $subpage );
		} else {
			return SpecialPage::getTitleFor( $this->redirName, $this->redirSubpage );
		}
	}
}

class SpecialMypage extends UnlistedSpecialPage {
	function __construct() {
		parent::__construct( 'Mypage' );
		$this->mAllowedRedirectParams = array( 'action' );
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

class SpecialMytalk extends UnlistedSpecialPage {
	function __construct() {
		parent::__construct( 'Mytalk' );
		$this->mAllowedRedirectParams = array( 'action' );
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

class SpecialMycontributions extends UnlistedSpecialPage {
	function __construct() {
		parent::__construct(  'Mycontributions' );
	}

	function getRedirect( $subpage ) {
		global $wgUser;
		return SpecialPage::getTitleFor( 'Contributions', $wgUser->getName() );
	}
}

?>
