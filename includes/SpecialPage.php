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
 */

/**
 *
 */
global $wgSpecialPages;

/**
 * @access private
 */
$wgSpecialPages = array(
	'DoubleRedirects'	=> new UnlistedSpecialPage ( 'DoubleRedirects' ),
	'BrokenRedirects'	=> new UnlistedSpecialPage ( 'BrokenRedirects' ),
	'Disambiguations'	=> new UnlistedSpecialPage ( 'Disambiguations' ),
	
	"Userlogin"         => new SpecialPage( "Userlogin" ),
	"Userlogout"        => new UnlistedSpecialPage( "Userlogout" ),
	"Preferences"       => new SpecialPage( "Preferences" ),
	"Watchlist"         => new SpecialPage( "Watchlist" ),
	"Recentchanges"     => new SpecialPage( "Recentchanges" ),
	"Upload"            => new SpecialPage( "Upload" ),
	"Imagelist"         => new SpecialPage( "Imagelist" ),
	"Newimages"         => new SpecialPage( "Newimages" ),
	"Listusers"         => new SpecialPage( "Listusers" ),
	"Listadmins"        => new SpecialPage( "Listadmins" ),
	"Statistics"        => new SpecialPage( "Statistics" ),
	"Randompage"        => new SpecialPage( "Randompage" ),
	"Lonelypages"       => new SpecialPage( "Lonelypages" ),
	"Uncategorizedpages"=> new SpecialPage( "Uncategorizedpages" ),
	"Unusedimages"      => new SpecialPage( "Unusedimages" )
);
global $wgDisableCounters;
if( !$wgDisableCounters ) {
	$wgSpecialPages["Popularpages"] = new SpecialPage( "Popularpages" );
}
$wgSpecialPages = array_merge($wgSpecialPages, array (
	"Wantedpages"	=> new SpecialPage( "Wantedpages" ),
	"Shortpages"	=> new SpecialPage( "Shortpages" ),
	"Longpages"		=> new SpecialPage( "Longpages" ),
	"Newpages"		=> new SpecialPage( "Newpages" ),
	"Ancientpages"	=> new SpecialPage( "Ancientpages" ),
	"Deadendpages"  => new SpecialPage( "Deadendpages" ),
	"Allpages"		=> new SpecialPage( "Allpages" ),
	"Ipblocklist"	=> new SpecialPage( "Ipblocklist" ),
	"Maintenance"	=> new SpecialPage( "Maintenance" ),
	"Specialpages"  => new UnlistedSpecialPage( "Specialpages" ),
	"Contributions" => new UnlistedSpecialPage( "Contributions" ),
	"Emailuser"		=> new UnlistedSpecialPage( "Emailuser" ),
	"Whatlinkshere" => new UnlistedSpecialPage( "Whatlinkshere" ),
	"Recentchangeslinked" => new UnlistedSpecialPage( "Recentchangeslinked" ),
	"Movepage"		=> new UnlistedSpecialPage( "Movepage" ),
	"Blockme"       => new UnlistedSpecialPage( "Blockme" ),
	"Geo"	=> 	new UnlistedSpecialPage( "Geo" ),
	"Validate"	=>	new UnlistedSpecialPage( "Validate" ),
	"Booksources"	=> new SpecialPage( "Booksources" ),
	"Categories"	=> new SpecialPage( "Categories" ),
	"Export"		=> new SpecialPage( "Export" ),
	"Version"		=> new SpecialPage( "Version" ),
	"Allmessages"	=> new SpecialPage( "Allmessages" ),
	"Search"		=> new UnlistedSpecialPage( "Search" ),
	"Log"           => new SpecialPage( "Log" ),
	"Blockip"		=> new SpecialPage( "Blockip", "sysop" ),
	"Asksql"		=> new SpecialPage( "Asksql", "sysop" ),
	"Undelete"		=> new SpecialPage( "Undelete", "sysop" ),
	"Makesysop"		=> new SpecialPage( "Makesysop", "sysop" ),

# Special:Import is half-written
#	"Import"		=> new SpecialPage( "Import", "sysop" ),

	"Lockdb"		=> new SpecialPage( "Lockdb", "developer" ),
	"Unlockdb"		=> new SpecialPage( "Unlockdb", "developer" ),
//	"Sitesettings"		=> new SpecialPage( "Sitesettings" )
));

/**
 * Parent special page class, also static functions for handling the special
 * page list
 * @package MediaWiki
 */
class SpecialPage
{
	/* private */ var $mName; # The name of the class, used in the URL. Also used for the default
	                          # <h1> heading, see getDescription()
	/* private */ var $mRestriction; # Minimum user level required to access this page, or ""
	                                 # for anyone. Also used to categorise the pages in
									 # Special:Specialpages
	/* private */ var $mListed; # Listed in Special:Specialpages?
	/* private */ var $mFunction; # Function name called by the default execute()
	/* private */ var $mFile; # File which needs to be included before the function above can be called

	# Add a page to the list of valid special pages
	# $obj->execute() must send HTML to $wgOut then return
	# Use this for a special page extension
	/* static */ function addPage( &$obj ) {
		global $wgSpecialPages;
		$wgSpecialPages[$obj->mName] = $obj;
	}

	# Remove a special page from the list
	# Occasionally used to disable expensive or dangerous special pages
	/* static */ function removePage( $name ) {
		global $wgSpecialPages;
		unset( $wgSpecialPages[$name] );
	}

	# Find the object with a given name and return it (or NULL)
	/* static */ function &getPage( $name ) {
		global $wgSpecialPages;
		if ( array_key_exists( $name, $wgSpecialPages ) ) {
			return $wgSpecialPages[$name];
		} else {
			return NULL;
		}
	}

	# Return categorised listable special pages
	# Returns a 2d array where the first index is the restriction name
	/* static */ function getPages() {
		global $wgSpecialPages;
		$pages = array(
		  "" => array(),
		  "sysop" => array(),
		  "developer" => array()
		);

		foreach ( $wgSpecialPages as $name => $page ) {
			if ( $page->isListed() ) {
				$pages[$page->getRestriction()][$page->getName()] =& $wgSpecialPages[$name];
			}
		}
		return $pages;
	}

	# Execute a special page path, which may contain parameters, e.g. Special:Name/Params
	# $title should be a title object
	# Extracts the special page name and call the execute method, passing the parameters
	/* static */ function executePath( &$title ) {
		global $wgSpecialPages, $wgOut, $wgTitle;

		$bits = split( "/", $title->getDBkey(), 2 );
		$name = $bits[0];
		if( empty( $bits[1] ) ) {
			$par = NULL;
		} else {
			$par = $bits[1];
		}

		$page =& SpecialPage::getPage( $name );
		if ( is_null( $page ) ) {
			$wgOut->setArticleRelated( false );
			$wgOut->setRobotpolicy( "noindex,follow" );
			$wgOut->errorpage( "nosuchspecialpage", "nospecialpagetext" );
		} else {
			if($par !== NULL) {
				$wgTitle = Title::makeTitle( NS_SPECIAL, $name );
			} else {
				$wgTitle = $title;
			}

			$page->execute( $par );
		}
	}

	# Default constructor for special pages
	# Derivative classes should call this from their constructor
	#   $name - the name of the special page, as seen in links and URLs
	#   $restriction - the minimum user level required, e.g. "sysop" or "developer".
	#
	#       Note that if the user does not have the required level, an error message will
	#       be displayed by the default execute() method, without the global function ever
	#       being called.
	#
	#       If you override execute(), you can recover the default behaviour with userCanExecute()
	#       and displayRestrictionError()
	#
	#   $listed - whether the page is listed in Special:Specialpages
	#   $function - the function called by execute(). By default it is constructed from $name
	#   $file - the file which is included by execute(). It is also constructed from $name by default
	#
	function SpecialPage( $name = "", $restriction = "", $listed = true, $function = false, $file = "default" ) {
		$this->mName = $name;
		$this->mRestriction = $restriction;
		$this->mListed = $listed;
		if ( $function == false ) {
			$this->mFunction = "wfSpecial{$name}";
		} else {
			$this->mFunction = $function;
		}
		if ( $file === "default" ) {
			$this->mFile = "Special{$name}.php";
		} else {
			$this->mFile = $file;
		}
	}

	# Accessor functions, see the descriptions of the associated variables above
	function getName() { return $this->mName; }
	function getRestriction() { return $this->mRestriction; }
	function isListed() { return $this->mListed; }

	# Checks if the given user (identified by an object) can execute this special page (as
	# defined by $mRestriction)
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

	# Output an error message telling the user what access level they have to have
	function displayRestrictionError() {
		global $wgOut;
		if ( $this->mRestriction == "developer" ) {
			$wgOut->developerRequired();
		} else {
			$wgOut->sysopRequired();
		}
	}

	# Sets headers - this should be called from the execute() method of all derived classes!
	function setHeaders() {
		global $wgOut;
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotPolicy( "noindex,follow" );
		$wgOut->setPageTitle( $this->getDescription() );
	}

	# Default execute method
	# Checks user permissions, calls the function given in mFunction
	function execute( $par ) {
		global $wgUser, $wgOut, $wgTitle;

		$this->setHeaders();

		if ( $this->userCanExecute( $wgUser ) ) {
			if ( $this->mFile ) {
				require_once( $this->mFile );
			}
			$func = $this->mFunction;
			$func( $par );
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

	# Get a self-referential title object
	function getTitle() {
		return Title::makeTitle( NS_SPECIAL, $this->mName );
	}

	# Set whether this page is listed in Special:Specialpages, at run-time
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
	function UnlistedSpecialPage( $name, $restriction = "", $function = false, $file = "default" ) {
		SpecialPage::SpecialPage( $name, $restriction, false, $function, $file );
	}
}
