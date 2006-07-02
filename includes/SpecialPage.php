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
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * @access private
 */

/**
 * Parent special page class, also static functions for handling the special
 * page list
 * @package MediaWiki
 */
class SpecialPage
{
	/**#@+
	 * @access private
	 */
	/**
	 * The name of the class, used in the URL.
	 * Also used for the default <h1> heading, @see getDescription()
	 */
	var $mName;
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

	static public $mList = array(
		'DoubleRedirects'	=> array( 'SpecialPage', 'DoubleRedirects' ),
		'BrokenRedirects'	=> array( 'SpecialPage', 'BrokenRedirects' ),
		'Disambiguations'	=> array( 'SpecialPage', 'Disambiguations' ),

		'Userlogin'         => array( 'SpecialPage', 'Userlogin' ),
		'Userlogout'        => array( 'UnlistedSpecialPage', 'Userlogout' ),
		'Preferences'       => array( 'SpecialPage', 'Preferences' ),
		'Watchlist'         => array( 'SpecialPage', 'Watchlist' ),

		'Recentchanges'     => array( 'IncludableSpecialPage', 'Recentchanges' ),
		'Upload'            => array( 'SpecialPage', 'Upload' ),
		'Imagelist'         => array( 'SpecialPage', 'Imagelist' ),
		'Newimages'         => array( 'IncludableSpecialPage', 'Newimages' ),
		'Listusers'         => array( 'SpecialPage', 'Listusers' ),
		'Statistics'        => array( 'SpecialPage', 'Statistics' ),
		'Random'            => array( 'SpecialPage', 'Randompage' ),
		'Lonelypages'       => array( 'SpecialPage', 'Lonelypages' ),
		'Uncategorizedpages'=> array( 'SpecialPage', 'Uncategorizedpages' ),
		'Uncategorizedcategories'=> array( 'SpecialPage', 'Uncategorizedcategories' ),
		'Uncategorizedimages' => array( 'SpecialPage', 'Uncategorizedimages' ),
		'Unusedcategories'	=> array( 'SpecialPage', 'Unusedcategories' ),
		'Unusedimages'      => array( 'SpecialPage', 'Unusedimages' ),
		'Wantedpages'	    => array( 'IncludableSpecialPage', 'Wantedpages' ),
		'Wantedcategories'  => array( 'SpecialPage', 'Wantedcategories' ),
		'Mostlinked'	    => array( 'SpecialPage', 'Mostlinked' ),
		'Mostlinkedcategories' => array( 'SpecialPage', 'Mostlinkedcategories' ),
		'Mostcategories'    => array( 'SpecialPage', 'Mostcategories' ),
		'Mostimages'        => array( 'SpecialPage', 'Mostimages' ),
		'Mostrevisions'     => array( 'SpecialPage', 'Mostrevisions' ),
		'Shortpages'	    => array( 'SpecialPage', 'Shortpages' ),
		'Longpages'		    => array( 'SpecialPage', 'Longpages' ),
		'Newpages'		    => array( 'IncludableSpecialPage', 'Newpages' ),
		'Ancientpages'	    => array( 'SpecialPage', 'Ancientpages' ),
		'Deadendpages'      => array( 'SpecialPage', 'Deadendpages' ),
		'Allpages'		    => array( 'IncludableSpecialPage', 'Allpages' ),
		'Prefixindex'	    => array( 'IncludableSpecialPage', 'Prefixindex' ) ,
		'Ipblocklist'	    => array( 'SpecialPage', 'Ipblocklist' ),
		'Specialpages'      => array( 'UnlistedSpecialPage', 'Specialpages' ),
		'Contributions'     => array( 'UnlistedSpecialPage', 'Contributions' ),
		'Emailuser'		    => array( 'UnlistedSpecialPage', 'Emailuser' ),
		'Whatlinkshere'     => array( 'UnlistedSpecialPage', 'Whatlinkshere' ),
		'Recentchangeslinked' => array( 'UnlistedSpecialPage', 'Recentchangeslinked' ),
		'Movepage'		    => array( 'UnlistedSpecialPage', 'Movepage' ),
		'Blockme'           => array( 'UnlistedSpecialPage', 'Blockme' ),
		'Booksources'	    => array( 'SpecialPage', 'Booksources' ),
		'Categories'	    => array( 'SpecialPage', 'Categories' ),
		'Export'		    => array( 'SpecialPage', 'Export' ),
		'Version'		    => array( 'SpecialPage', 'Version' ),
		'Allmessages'	    => array( 'SpecialPage', 'Allmessages' ),
		'Log'               => array( 'SpecialPage', 'Log' ),
		'Blockip'		    => array( 'SpecialPage', 'Blockip', 'block' ),
		'Undelete'		    => array( 'SpecialPage', 'Undelete', 'deletedhistory' ),
		"Import"		    => array( 'SpecialPage', "Import", 'import' ),
		'Lockdb'		    => array( 'SpecialPage', 'Lockdb', 'siteadmin' ),
		'Unlockdb'		    => array( 'SpecialPage', 'Unlockdb', 'siteadmin' ),
		'Userrights'	    => array( 'SpecialPage', 'Userrights', 'userrights' ),
		'MIMEsearch'        => array( 'SpecialPage', 'MIMEsearch' ),
		'Unwatchedpages'    => array( 'SpecialPage', 'Unwatchedpages', 'unwatchedpages' ),
		'Listredirects'     => array( 'SpecialPage', 'Listredirects' ),
		'Revisiondelete'    => array( 'SpecialPage', 'Revisiondelete', 'deleterevision' ),
		'Unusedtemplates'   => array( 'SpecialPage', 'Unusedtemplates' ),
		'Randomredirect'    => array( 'SpecialPage', 'Randomredirect' ),
	);

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

		# Better to set this now, to avoid infinite recursion in carelessly written hooks
		self::$mListInitialised = true;

		# Run hooks
		# This hook can be used to remove undesired built-in special pages
		wfRunHooks( 'SpecialPage_initList', array( &self::$mList ) );
		wfProfileOut( __METHOD__ );
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
	 * @static
	 * @param string $name
	 * @return mixed Title object if the redirect exists, otherwise NULL
	 */
	static function getRedirect( $name ) {
		global $wgUser;

		$redirects = array(
			'Mypage' => Title::makeTitle( NS_USER, $wgUser->getName() ),
			'Mytalk' => Title::makeTitle( NS_USER_TALK, $wgUser->getName() ),
			'Mycontributions' => Title::makeTitle( NS_SPECIAL, 'Contributions/' . $wgUser->getName() ),
			'Listadmins' => Title::makeTitle( NS_SPECIAL, 'Listusers/sysop' ), # @bug 2832
			'Logs' => Title::makeTitle( NS_SPECIAL, 'Log' ),
			'Randompage' => Title::makeTitle( NS_SPECIAL, 'Random' ),
			'Userlist' => Title::makeTitle( NS_SPECIAL, 'Listusers' )
		);
		wfRunHooks( 'SpecialPageGetRedirect', array( &$redirects ) );

		return isset( $redirects[$name] ) ? $redirects[$name] : null;
	}

	/**
	 * Return part of the request string for a special redirect page
	 * This allows passing, e.g. action=history to Special:Mypage, etc.
	 *
	 * @param $name Name of the redirect page
	 * @return string
	 */
	function getRedirectParams( $name ) {
		global $wgRequest;
		
		$args = array();
		switch( $name ) {
			case 'Mypage':
			case 'Mytalk':
			case 'Randompage':
				$args = array( 'action' );
		}
		
		$params = array();
		foreach( $args as $arg ) {
			if( $val = $wgRequest->getVal( $arg, false ) )
				$params[] = $arg . '=' . $val;
		}
		
		return count( $params ) ? implode( '&', $params ) : false;
	}	

	/**
	 * Return categorised listable special pages
	 * Returns a 2d array where the first index is the restriction name
	 * @static
	 */
	static function getPages() {
		if ( !self::$mListInitialised ) {
			self::initList();
		}
		$pages = array(
		  '' => array(),
		  'sysop' => array(),
		  'developer' => array()
		);

		foreach ( self::$mList as $name => $rec ) {
			$page = self::getPage( $name );
			if ( $page->isListed() ) {
				$pages[$page->getRestriction()][$page->getName()] = $page;
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
	function executePath( &$title, $including = false ) {
		global $wgOut, $wgTitle;
		$fname = 'SpecialPage::executePath';
		wfProfileIn( $fname );

		$bits = split( "/", $title->getDBkey(), 2 );
		$name = $bits[0];
		if( !isset( $bits[1] ) ) { // bug 2087
			$par = NULL;
		} else {
			$par = $bits[1];
		}

		$page = SpecialPage::getPage( $name );
		if ( is_null( $page ) ) {
			if ( $including ) {
				wfProfileOut( $fname );
				return false;
			} else {
				$redir = SpecialPage::getRedirect( $name );
				if ( isset( $redir ) ) {
					if( $par )
						$redir = Title::makeTitle( $redir->getNamespace(), $redir->getText() . '/' . $par );
					$params = SpecialPage::getRedirectParams( $name );
					if( $params ) {
						$url = $redir->getFullUrl( $params );
					} else {
						$url = $redir->getFullUrl();
					}
					$wgOut->redirect( $url );
					$retVal = $redir;
					$wgOut->redirect( $url );
					$retVal = $redir;
				} else {
					$wgOut->setArticleRelated( false );
					$wgOut->setRobotpolicy( 'noindex,nofollow' );
					$wgOut->setStatusCode( 404 );
					$wgOut->showErrorPage( 'nosuchspecialpage', 'nospecialpagetext' );
					$retVal = false;
				}
			}
		} else {
			if ( $including && !$page->includable() ) {
				wfProfileOut( $fname );
				return false;
			} elseif ( !$including ) {
				if($par !== NULL) {
					$wgTitle = Title::makeTitle( NS_SPECIAL, $name );
				} else {
					$wgTitle = $title;
				}
			}
			$page->including( $including );

			$profName = 'Special:' . $page->getName();
			wfProfileIn( $profName );
			$page->execute( $par );
			wfProfileOut( $profName );
			$retVal = true;
		}
		wfProfileOut( $fname );
		return $retVal;
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
	 * @param string $restriction Minimum user level required, e.g. "sysop" or "developer".
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
			$this->mFile = "Special{$name}.php";
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
	 * Checks if the given user (identified by an object) can execute this
	 * special page (as defined by $mRestriction)
	 */
	function userCanExecute( &$user ) {
		if ( $this->mRestriction == "" ) {
			return true;
		} else {
			if ( in_array( $this->mRestriction, $user->getRights() ) ) {
				return true;
			} else {
				return false;
			}
		}
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
	function getTitle() {
		return Title::makeTitle( NS_SPECIAL, $this->mName );
	}

	/**
	 * Set whether this page is listed in Special:Specialpages, at run-time
	 */
	function setListed( $listed ) {
		return wfSetVar( $this->mListed, $listed );
	}

}

/**
 * Shortcut to construct a special page which is unlisted by default
 * @package MediaWiki
 */
class UnlistedSpecialPage extends SpecialPage
{
	function UnlistedSpecialPage( $name, $restriction = '', $function = false, $file = 'default' ) {
		SpecialPage::SpecialPage( $name, $restriction, false, $function, $file );
	}
}

/**
 * Shortcut to construct an includable special  page
 * @package MediaWiki
 */
class IncludableSpecialPage extends SpecialPage
{
	function IncludableSpecialPage( $name, $restriction = '', $listed = true, $function = false, $file = 'default' ) {
		SpecialPage::SpecialPage( $name, $restriction, $listed, $function, $file, true );
	}
}
?>
