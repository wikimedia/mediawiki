<?php
global $wgSpecialPages, $wgWhitelistAccount;

$wgSpecialPages = array(
	"Userlogin"         => new SpecialPage( "Userlogin" ),
	"Userlogout"        => new UnlistedSpecialPage( "Userlogout" ),
	"Preferences"       => new SpecialPage( "Preferences" ),
	"Watchlist"         => new SpecialPage( "Watchlist" ),
	"Recentchanges"     => new SpecialPage( "Recentchanges" ),
	"Upload"            => new SpecialPage( "Upload" ),
	"Imagelist"         => new SpecialPage( "Imagelist" ),
	"Listusers"         => new SpecialPage( "Listusers" ),
	"Listadmins"        => new SpecialPage( "Listadmins" ),
	"Statistics"        => new SpecialPage( "Statistics" ),
	"Randompage"        => new SpecialPage( "Randompage" ),
	"Lonelypages"       => new SpecialPage( "Lonelypages" ),
	"Uncategorizedpages"=> new SpecialPage( "Uncategorizedpages" ),
	"Unusedimages"      => new SpecialPage( "Unusedimages" )
);
global $wgDisableCounters;
if( !$wgDisableCounters )
{
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
	"Booksources"	=> new SpecialPage( "Booksources" ),
	"Categories"	=> new SpecialPage( "Categories" ),
	"Export"		=> new SpecialPage( "Export" ),
	"Version"		=> new SpecialPage( "Version" ),
	"Allmessages"	=> new SpecialPage( "Allmessages" ),
	"Search"		=> new UnlistedSpecialPage( "Search" ),
	"Blockip"		=> new SpecialPage( "Blockip", "sysop" ),
	"Asksql"		=> new SpecialPage( "Asksql", "sysop" ),
	"Undelete"		=> new SpecialPage( "Undelete", "sysop" ),
	"Makesysop"		=> new SpecialPage( "Makesysop", "sysop" ),
	"Import"		=> new SpecialPage( "Import", "sysop" ),
	"Lockdb"		=> new SpecialPage( "Lockdb", "developer" ),
	"Unlockdb"		=> new SpecialPage( "Unlockdb", "developer" )
));

class SpecialPage
{
	/* private */ var $mName, $mRestriction, $mListed, $mFunction, $mFile;
	
	/* static */ function addPage( &$obj ) {
		global $wgSpecialPages;
		$wgSpecialPages[$obj->mName] = $obj;
	}

	/* static */ function removePage( $name ) {
		global $wgSpecialPages;
		unset( $wgSpecialPages[$name] );
	}

	/* static */ function &getPage( $name ) {
		global $wgSpecialPages;
		if ( array_key_exists( $name, $wgSpecialPages ) ) {
			return $wgSpecialPages[$name];
		} else {
			return NULL;
		}
	}

	# Return categorised listable special pages
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

	# Execute a special page path, which may contain slashes
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

	function getName() { return $this->mName; }
	function getRestriction() { return $this->mRestriction; }
	function isListed() { return $this->mListed; }

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

	function displayRestrictionError() {
		global $wgOut;
		if ( $this->mRestriction == "developer" ) {
			$wgOut->developerRequired();
		} else {
			$wgOut->sysopRequired();
		}
	}
	
	function setHeaders() {
		global $wgOut;
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotPolicy( "noindex,follow" );
		$wgOut->setPageTitle( $this->getDescription() );
	}

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

	function getDescription() {
		return wfMsg( strtolower( $this->mName ) );
	}

	function getTitle() {
		return Title::makeTitle( NS_SPECIAL, $this->mName );
	}

	function setListed( $listed ) {
		return wfSetVar( $this->mListed, $listed );
	}
}

class UnlistedSpecialPage extends SpecialPage
{
	function UnlistedSpecialPage( $name, $restriction = "", $function = false, $file = "default" ) {
		SpecialPage::SpecialPage( $name, $restriction, false, $function, $file );
	}
}
