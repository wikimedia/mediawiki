<?php
/**
 * SpecialPage: handling special pages and lists thereof
 * $wgSpecialPages is a list of all SpecialPage objects. These objects are
 * either instances of SpecialPage or a sub-class thereof. They have an
 * execute() method, which sends the HTML for the special page to $wgOut.
 * The parent class has an execute() method which distributes the call to
 * the historical global functions. Additionally, execute() also checks if the
 * user has the necessary access privileges and bails out if not.
 *
 * To add a special page at run-time, use SpecialPage::addPage().
 * DO NOT manipulate this array at run-time.
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */


/**
 * @access private
 */
$wgSpecialPages = array(
	'DoubleRedirects'	=> new SpecialPage ( 'DoubleRedirects' ),
	'BrokenRedirects'	=> new SpecialPage ( 'BrokenRedirects' ),
	'Disambiguations'	=> new SpecialPage ( 'Disambiguations' ),

	'Userlogin'         => new SpecialPage( 'Userlogin' ),
	'Userlogout'        => new UnlistedSpecialPage( 'Userlogout' ),
	'Preferences'       => new SpecialPage( 'Preferences' ),
	'Watchlist'         => new SpecialPage( 'Watchlist' ),

	'Recentchanges'     => new IncludableSpecialPage( 'Recentchanges' ),
	'Upload'            => new SpecialPage( 'Upload' ),
	'Imagelist'         => new SpecialPage( 'Imagelist' ),
	'Newimages'         => new SpecialPage( 'Newimages' ),
	'Listusers'         => new SpecialPage( 'Listusers' ),
	'Statistics'        => new SpecialPage( 'Statistics' ),
	'Random'        => new SpecialPage( 'Randompage' ),
	'Lonelypages'       => new SpecialPage( 'Lonelypages' ),
	'Uncategorizedpages'=> new SpecialPage( 'Uncategorizedpages' ),
	'Uncategorizedcategories'=> new SpecialPage( 'Uncategorizedcategories' ),
	'Unusedcategories'	=> new SpecialPage( 'Unusedcategories' ),
	'Unusedimages'      => new SpecialPage( 'Unusedimages' ),
	'Wantedpages'	=> new SpecialPage( 'Wantedpages' ),
	'Mostlinked'	=> new SpecialPage( 'Mostlinked' ),
	'Shortpages'	=> new SpecialPage( 'Shortpages' ),
	'Longpages'		=> new SpecialPage( 'Longpages' ),
	'Newpages'		=> new IncludableSpecialPage( 'Newpages' ),
	'Ancientpages'	=> new SpecialPage( 'Ancientpages' ),
	'Deadendpages'  => new SpecialPage( 'Deadendpages' ),
	'Allpages'		=> new IncludableSpecialPage( 'Allpages' ),
	'Ipblocklist'	=> new SpecialPage( 'Ipblocklist' ),
	'Specialpages'  => new UnlistedSpecialPage( 'Specialpages' ),
	'Contributions' => new UnlistedSpecialPage( 'Contributions' ),
	'Emailuser'		=> new UnlistedSpecialPage( 'Emailuser' ),
	'Whatlinkshere' => new UnlistedSpecialPage( 'Whatlinkshere' ),
	'Recentchangeslinked' => new UnlistedSpecialPage( 'Recentchangeslinked' ),
	'Movepage'		=> new UnlistedSpecialPage( 'Movepage' ),
	'Blockme'       => new UnlistedSpecialPage( 'Blockme' ),
	'Booksources'	=> new SpecialPage( 'Booksources' ),
	'Categories'	=> new SpecialPage( 'Categories' ),
	'Export'		=> new SpecialPage( 'Export' ),
	'Version'		=> new SpecialPage( 'Version' ),
	'Allmessages'	=> new SpecialPage( 'Allmessages' ),
	'Log'           => new SpecialPage( 'Log' ),
	'Blockip'		=> new SpecialPage( 'Blockip', 'block' ),
	'Undelete'		=> new SpecialPage( 'Undelete', 'delete' ),
	"Import"		=> new SpecialPage( "Import", 'import' ),
	'Lockdb'		=> new SpecialPage( 'Lockdb', 'siteadmin' ),
	'Unlockdb'		=> new SpecialPage( 'Unlockdb', 'siteadmin' ),
	'Userrights'	=> new SpecialPage( 'Userrights', 'userrights' ),
);

if ( $wgUseValidation )
	$wgSpecialPages['Validate'] = new SpecialPage( 'Validate' );

if( !$wgDisableCounters ) {
	$wgSpecialPages['Popularpages'] = new SpecialPage( 'Popularpages' );
}

if( !$wgDisableInternalSearch ) {
	$wgSpecialPages['Search'] = new UnlistedSpecialPage( 'Search' );
}

if( $wgEmailAuthentication ) {
	$wgSpecialPages['Confirmemail'] = new UnlistedSpecialPage( 'Confirmemail' );
}

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


	/**#@-*/


	/**
	 * Add a page to the list of valid special pages
	 * $obj->execute() must send HTML to $wgOut then return
	 * Use this for a special page extension
	 * @static
	 */
	function addPage( &$obj ) {
		global $wgSpecialPages;
		$wgSpecialPages[$obj->mName] = $obj;
	}

	/**
	 * Remove a special page from the list
	 * Occasionally used to disable expensive or dangerous special pages
	 * @static
	 */
	function removePage( $name ) {
		global $wgSpecialPages;
		unset( $wgSpecialPages[$name] );
	}

	/**
	 * Find the object with a given name and return it (or NULL)
	 * @static
	 * @param string $name
	 */
	function getPage( $name ) {
		global $wgSpecialPages;
		if ( array_key_exists( $name, $wgSpecialPages ) ) {
			return $wgSpecialPages[$name];
		} else {
			return NULL;
		}
	}

	/**
	 * @static
	 * @param string $name
	 * @return mixed Title object if the redirect exists, otherwise NULL
	 */
	function getRedirect( $name ) {
		global $wgUser;
		switch ( $name ) {
			case 'Mypage':
				return Title::makeTitle( NS_USER, $wgUser->getName() );
			case 'Mytalk':
				return Title::makeTitle( NS_USER_TALK, $wgUser->getName() );
			case 'Mycontributions':
				return Title::makeTitle( NS_SPECIAL, 'Contributions/' . $wgUser->getName() );
			case 'Listadmins':
				return Title::makeTitle( NS_SPECIAL, 'Listusers/sysop' ); # @bug 2832
			case 'Randompage':
				return Title::makeTitle( NS_SPECIAL, 'Random' );
			default:
				return NULL;
		}
	}

	/**
	 * Return categorised listable special pages
	 * Returns a 2d array where the first index is the restriction name
	 * @static
	 */
	function getPages() {
		global $wgSpecialPages;
		$pages = array(
		  '' => array(),
		  'sysop' => array(),
		  'developer' => array()
		);

		foreach ( $wgSpecialPages as $name => $page ) {
			if ( $page->isListed() ) {
				$pages[$page->getRestriction()][$page->getName()] =& $wgSpecialPages[$name];
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
		global $wgSpecialPages, $wgOut, $wgTitle;

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
				return false;
			} else {
				$redir = SpecialPage::getRedirect( $name );
				if ( isset( $redir ) ) {
					if ( isset( $par ) )
						$wgOut->redirect( $redir->getFullURL() . '/' . $par );
					else
						$wgOut->redirect( $redir->getFullURL() );
					$retVal = $redir;
				} else {
					$wgOut->setArticleRelated( false );
					$wgOut->setRobotpolicy( "noindex,follow" );
					$wgOut->errorpage( "nosuchspecialpage", "nospecialpagetext" );
					$retVal = false;
				}
			}
		} else {
			if ( $including && !$page->includable() ) {
				return false;
			}
			if($par !== NULL) {
				$wgTitle = Title::makeTitle( NS_SPECIAL, $name );
			} else {
				$wgTitle = $title;
			}
			$page->including( $including );

			$page->execute( $par );
			$retVal = true;
		}
		return $retVal;
	}

	/**
	 * Just like executePath() except it returns the HTML instead of outputting it
	 * Returns false if there was no such special page, or a title object if it was
	 * a redirect.
	 * @static
	 */
	function capturePath( &$title ) {
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

	# Accessor functions, see the descriptions of the associated variables above
	function getName() { return $this->mName; }
	function getRestriction() { return $this->mRestriction; }
	function isListed() { return $this->mListed; }
	function getFile() { return $this->mFile; }
	function including( $x = NULL ) { return wfSetVar( $this->mIncluding, $x ); }
	function includable( $x = NULL ) { return wfSetVar( $this->mIncludable, $x ); }

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
		$wgOut->setRobotPolicy( "noindex,follow" );
		$wgOut->setPageTitle( $this->getDescription() );
	}

	/**
	 * Default execute method
	 * Checks user permissions, calls the function given in mFunction
	 */
	function execute( $par ) {
		global $wgUser, $wgOut, $wgTitle;

		$this->setHeaders();

		if ( $this->userCanExecute( $wgUser ) ) {
			if ( $this->mFile ) {
				require_once( $this->mFile );
			}
			$func = $this->mFunction;
			$func( $par, $this );
		} else {
			$this->displayRestrictionError();
		}
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
