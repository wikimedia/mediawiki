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
	/**
	 * Query parameteres added by redirects
	 */
	var $mAddedRedirectParams = array();
	/**
	 * List of special pages, followed by parameters.
	 * If the only parameter is a string, that is the page name.
	 * Otherwise, it is an array. The format is one of:
	 ** array( 'SpecialPage', name, right )
	 ** array( 'IncludableSpecialPage', name, right, listed? )
	 ** array( 'UnlistedSpecialPage', name, right )
	 ** array( 'SpecialRedirectToSpecial', name, page to redirect to, special page param, ... )
	 */
	static public $mList = array(
		# Maintenance Reports
		'BrokenRedirects'           => array( 'SpecialPage', 'BrokenRedirects' ),
		'Deadendpages'              => array( 'SpecialPage', 'Deadendpages' ),
		'DoubleRedirects'           => array( 'SpecialPage', 'DoubleRedirects' ),
		'Longpages'                 => array( 'SpecialPage', 'Longpages' ),
		'Ancientpages'              => array( 'SpecialPage', 'Ancientpages' ),
		'Lonelypages'               => array( 'SpecialPage', 'Lonelypages' ),
		'Fewestrevisions'           => array( 'SpecialPage', 'Fewestrevisions' ),
		'Withoutinterwiki'          => array( 'SpecialPage', 'Withoutinterwiki' ),
		'Protectedpages'            => array( 'SpecialPage', 'Protectedpages' ),
		'Protectedtitles'           => array( 'SpecialPage', 'Protectedtitles' ),
		'Shortpages'                => array( 'SpecialPage', 'Shortpages' ),
		'Uncategorizedcategories'   => array( 'SpecialPage', 'Uncategorizedcategories' ),
		'Uncategorizedimages'       => array( 'SpecialPage', 'Uncategorizedimages' ),
		'Uncategorizedpages'        => array( 'SpecialPage', 'Uncategorizedpages' ),
		'Uncategorizedtemplates'    => array( 'SpecialPage', 'Uncategorizedtemplates' ),
		'Unusedcategories'          => array( 'SpecialPage', 'Unusedcategories' ),
		'Unusedimages'              => array( 'SpecialPage', 'Unusedimages' ),
		'Unusedtemplates'           => array( 'SpecialPage', 'Unusedtemplates' ),
		'Unwatchedpages'            => array( 'SpecialPage', 'Unwatchedpages', 'unwatchedpages' ),
		'Wantedcategories'          => array( 'SpecialPage', 'Wantedcategories' ),
		'Wantedfiles'               => array( 'SpecialPage', 'Wantedfiles' ),
		'Wantedpages'               => array( 'IncludableSpecialPage', 'Wantedpages' ),
		'Wantedtemplates'           => array( 'SpecialPage', 'Wantedtemplates' ),

		# List of pages
		'Allpages'                  => 'SpecialAllpages',
		'Prefixindex'               => 'SpecialPrefixindex',
		'Categories'                => array( 'SpecialPage', 'Categories' ),
		'Disambiguations'           => array( 'SpecialPage', 'Disambiguations' ),
		'Listredirects'             => array( 'SpecialPage', 'Listredirects' ),

		# Login/create account
		'Userlogin'                 => array( 'SpecialPage', 'Userlogin' ),
		'CreateAccount'             => array( 'SpecialRedirectToSpecial', 'CreateAccount', 'Userlogin', 'signup', array( 'uselang' ) ),

		# Users and rights
		'Blockip'                   => array( 'SpecialPage', 'Blockip', 'block' ),
		'Ipblocklist'               => 'IPUnblockForm',
		'Unblock'                   => array( 'SpecialRedirectToSpecial', 'Unblock', 'Ipblocklist', false, array( 'uselang', 'ip', 'id' ), array( 'action' => 'unblock' ) ),
		'Resetpass'                 => 'SpecialResetpass',
		'DeletedContributions'      => 'DeletedContributionsPage',
		'Preferences'               => 'SpecialPreferences',
		'Contributions'             => 'SpecialContributions',
		'Listgrouprights'           => 'SpecialListGroupRights',
		'Listusers'                 => array( 'SpecialPage', 'Listusers' ),
		'Activeusers'               => 'SpecialActiveUsers',
		'Userrights'                => 'UserrightsPage',

		# Recent changes and logs
		'Newimages'                 => array( 'IncludableSpecialPage', 'Newimages' ),
		'Log'                       => 'SpecialLog',
		'Watchlist'                 => array( 'SpecialPage', 'Watchlist' ),
		'Newpages'                  => 'SpecialNewpages',
		'Recentchanges'             => 'SpecialRecentchanges',
		'Recentchangeslinked'       => 'SpecialRecentchangeslinked',
		'Tags'                      => 'SpecialTags',

		# Media reports and uploads
		'Listfiles'                 => array( 'SpecialPage', 'Listfiles' ),
		'Filepath'                  => 'SpecialFilepath',
		'MIMEsearch'                => array( 'SpecialPage', 'MIMEsearch' ),
		'FileDuplicateSearch'       => array( 'SpecialPage', 'FileDuplicateSearch' ),
		'Upload'                    => 'SpecialUpload',

		# Wiki data and tools
		'Statistics'                => 'SpecialStatistics',
		'Allmessages'               => 'SpecialAllmessages',
		'Version'                   => 'SpecialVersion',
		'Lockdb'                    => 'SpecialLockdb',
		'Unlockdb'                  => 'SpecialUnlockdb',

		# Redirecting special pages
		'LinkSearch'                => array( 'SpecialPage', 'LinkSearch' ),
		'Randompage'                => 'Randompage',
		'Randomredirect'            => 'SpecialRandomredirect',

		# High use pages
		'Mostlinkedcategories'      => array( 'SpecialPage', 'Mostlinkedcategories' ),
		'Mostimages'                => array( 'SpecialPage', 'Mostimages' ),
		'Mostlinked'                => array( 'SpecialPage', 'Mostlinked' ),
		'Mostlinkedtemplates'       => array( 'SpecialPage', 'Mostlinkedtemplates' ),
		'Mostcategories'            => array( 'SpecialPage', 'Mostcategories' ),
		'Mostrevisions'             => array( 'SpecialPage', 'Mostrevisions' ),

		# Page tools
		'ComparePages'              => 'SpecialComparePages',
		'Export'                    => 'SpecialExport',
		'Import'                    => 'SpecialImport',
		'Undelete'                  => 'UndeleteForm',
		'Whatlinkshere'             => 'SpecialWhatlinkshere',
		'MergeHistory'              => 'SpecialMergeHistory',

		# Other
		'Booksources'               => 'SpecialBookSources',

		# Unlisted / redirects
		'Blankpage'                 => 'SpecialBlankpage',
		'Blockme'                   => 'SpecialBlockme',
		'Emailuser'                 => 'SpecialEmailUser',
		'Listadmins'                => array( 'SpecialRedirectToSpecial', 'Listadmins', 'Listusers', 'sysop' ),
		'Listbots'                  => array( 'SpecialRedirectToSpecial', 'Listbots', 'Listusers', 'bot' ),
		'Movepage'                  => array( 'UnlistedSpecialPage', 'Movepage' ),
		'Mycontributions'           => 'SpecialMycontributions',
		'Mypage'                    => 'SpecialMypage',
		'Mytalk'                    => 'SpecialMytalk',
		'Revisiondelete'            => 'SpecialRevisionDelete',
		'RevisionMove'              => 'SpecialRevisionMove',
		'Specialpages'              => 'SpecialSpecialpages',
		'Userlogout'                => 'SpecialUserlogout',
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
		global $wgEnableSelenium;

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
			self::$mList['Confirmemail'] = 'EmailConfirmation';
			self::$mList['Invalidateemail'] = 'EmailInvalidation';
		}

		if ( $wgEnableSelenium ) {
			self::$mList['Selenium'] = 'SpecialSelenium';
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
	 *
	 * @param $alias String
	 * @return String or false
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
	 *
	 * @param $alias String
	 * @return Array
	 */
	static function resolveAliasWithSubpage( $alias ) {
		$bits = explode( '/', $alias, 2 );
		$name = self::resolveAlias( $bits[0] );
		if( !isset( $bits[1] ) ) { // bug 2087
			$par = null;
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
	 * @param $page SpecialPage
	 * Deprecated in 1.7, warnings in 1.17, might be removed in 1.20
	 */
	static function addPage( &$page ) {
		wfDeprecated( __METHOD__ );
		if ( !self::$mListInitialised ) {
			self::initList();
		}
		self::$mList[$page->mName] = $page;
	}

	/**
	 * Add a page to a certain display group for Special:SpecialPages
	 *
	 * @param $page Mixed: SpecialPage or string
	 * @param $group String
	 */
	static function setGroup( $page, $group ) {
		global $wgSpecialPageGroups;
		$name = is_object($page) ? $page->mName : $page;
		$wgSpecialPageGroups[$name] = $group;
	}

	/**
	 * Add a page to a certain display group for Special:SpecialPages
	 *
	 * @param $page SpecialPage
	 */
	static function getGroup( &$page ) {
		global $wgSpecialPageGroups;
		static $specialPageGroupsCache = array();
		if( isset($specialPageGroupsCache[$page->mName]) ) {
			return $specialPageGroupsCache[$page->mName];
		}
		$group = wfMsg('specialpages-specialpagegroup-'.strtolower($page->mName));
		if( $group == ''
		 || wfEmptyMsg('specialpages-specialpagegroup-'.strtolower($page->mName), $group ) ) {
			$group = isset($wgSpecialPageGroups[$page->mName])
				? $wgSpecialPageGroups[$page->mName]
				: '-';
		}
		if( $group == '-' ) $group = 'other';
		$specialPageGroupsCache[$page->mName] = $group;
		return $group;
	}

	/**
	 * Remove a special page from the list
	 * Formerly used to disable expensive or dangerous special pages. The
	 * preferred method is now to add a SpecialPage_initList hook.
	 */
	static function removePage( $name ) {
		if ( !self::$mListInitialised ) {
			self::initList();
		}
		unset( self::$mList[$name] );
	}

	/**
	 * Check if a given name exist as a special page or as a special page alias
	 *
	 * @param $name String: name of a special page
	 * @return Boolean: true if a special page exists with this name
	 */
	static function exists( $name ) {
		global $wgContLang;
		if ( !self::$mListInitialised ) {
			self::initList();
		}
		if( !self::$mAliases ) {
			self::initAliasList();
		}

		# Remove special pages inline parameters:
		$bits = explode( '/', $name );
		$name = $wgContLang->caseFold($bits[0]);

		return
			array_key_exists( $name, self::$mList )
			or array_key_exists( $name, self::$mAliases )
		;
	}

	/**
	 * Find the object with a given name and return it (or NULL)
	 *
	 * @param $name String
	 * @return SpecialPage object or null if the page doesn't exist
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
			return null;
		}
	}

	/**
	 * Get a special page with a given localised name, or NULL if there
	 * is no such special page.
	 *
	 * @return SpecialPage object or null if the page doesn't exist
	 */
	static function getPageByAlias( $alias ) {
		$realName = self::resolveAlias( $alias );
		if ( $realName ) {
			return self::getPage( $realName );
		} else {
			return null;
		}
	}

	/**
	 * Return categorised listable special pages which are available
	 * for the current user, and everyone.
	 *
	 * @return Associative array mapping page's name to its SpecialPage object
	 */
	static function getUsablePages() {
		global $wgUser;
		if ( !self::$mListInitialised ) {
			self::initList();
		}
		$pages = array();

		foreach ( self::$mList as $name => $rec ) {
			$page = self::getPage( $name );
			if ( $page->isListed()
				&& (
					!$page->isRestricted()
					|| $page->userCanExecute( $wgUser )
				)
			) {
				$pages[$name] = $page;
			}
		}
		return $pages;
	}

	/**
	 * Return categorised listable special pages for all users
	 *
	 * @return Associative array mapping page's name to its SpecialPage object
	 */
	static function getRegularPages() {
		if ( !self::$mListInitialised ) {
			self::initList();
		}
		$pages = array();

		foreach ( self::$mList as $name => $rec ) {
			$page = self::getPage( $name );
			if ( $page->isListed() && !$page->isRestricted() ) {
				$pages[$name] = $page;
			}
		}
		return $pages;
	}

	/**
	 * Return categorised listable special pages which are available
	 * for the current user, but not for everyone
	 *
	 * @return Associative array mapping page's name to its SpecialPage object
	 */
	static function getRestrictedPages() {
		global $wgUser;
		if( !self::$mListInitialised ) {
			self::initList();
		}
		$pages = array();

		foreach( self::$mList as $name => $rec ) {
			$page = self::getPage( $name );
			if(
				$page->isListed()
				&& $page->isRestricted()
				&& $page->userCanExecute( $wgUser )
			) {
				$pages[$name] = $page;
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
			$par = null;
		} else {
			$par = $bits[1];
		}
		$page = SpecialPage::getPageByAlias( $name );
		# Nonexistent?
		if ( !$page ) {
			if ( !$including ) {
				$wgOut->setArticleRelated( false );
				$wgOut->setRobotPolicy( 'noindex,nofollow' );
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
	 *
	 * @return String: HTML fragment
	 */
	static function capturePath( &$title ) {
		global $wgOut, $wgTitle;

		$oldTitle = $wgTitle;
		$oldOut = $wgOut;
		$wgOut = new OutputPage;
		$wgOut->setTitle( $title );

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
	 *
	 * @param $name String
	 * @param $subpage Mixed: boolean false, or string
	 *
	 * @return String
	 */
	static function getLocalNameFor( $name, $subpage = false ) {
		global $wgContLang;
		$aliases = $wgContLang->getSpecialPageAliases();
		if ( isset( $aliases[$name][0] ) ) {
			$name = $aliases[$name][0];
		} else {
			// Try harder in case someone misspelled the correct casing
			$found = false;
			foreach ( $aliases as $n => $values ) {
				if ( strcasecmp( $name, $n ) === 0 ) {
					wfWarn( "Found alias defined for $n when searching for" .
						"special page aliases for $name. Case mismatch?" );
					$name = $values[0];
					$found = true;
					break;
				}
			}
			if ( !$found ) {
				wfWarn( "Did not find alias for special page '$name'." . 
					"Perhaps no aliases are defined for it?" );
			}
		}
		if ( $subpage !== false && !is_null( $subpage ) ) {
			$name = "$name/$subpage";
		}
		return $wgContLang->ucfirst( $name );
	}

	/**
	 * Get a localised Title object for a specified special page name
	 *
	 * @return Title object
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
	 *
	 * @return Title object or null if the page doesn't exist
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
	 *
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
	 * @param $name String: name of the special page, as seen in links and URLs
	 * @param $restriction String: user right required, e.g. "block" or "delete"
	 * @param $listed Boolean: whether the page is listed in Special:Specialpages
	 * @param $function Callback: function called by execute(). By default it is constructed from $name
	 * @param $file String: file which is included by execute(). It is also constructed from $name by default
	 * @param $includable Boolean: whether the page can be included in normal pages
	 */
	function SpecialPage( $name = '', $restriction = '', $listed = true, $function = false, $file = 'default', $includable = false ) {
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
	function name( $x = null ) { return wfSetVar( $this->mName, $x ); }
	function restrictions( $x = null) {
		# Use the one below this
		wfDeprecated( __METHOD__ );
		return wfSetVar( $this->mRestriction, $x );
	}
	function restriction( $x = null) { return wfSetVar( $this->mRestriction, $x ); }
	function listed( $x = null) { return wfSetVar( $this->mListed, $x ); }
	function func( $x = null) { return wfSetVar( $this->mFunction, $x ); }
	function file( $x = null) { return wfSetVar( $this->mFile, $x ); }
	function includable( $x = null ) { return wfSetVar( $this->mIncludable, $x ); }
	function including( $x = null ) { return wfSetVar( $this->mIncluding, $x ); }
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
	public function userCanExecute( $user ) {
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
		global $wgOut, $wgContLang;

		if( $summaryMessageKey == '' ) {
			$msg = $wgContLang->lc( $this->name() ) . '-summary';
		} else {
			$msg = $summaryMessageKey;
		}
		$out = wfMsgNoTrans( $msg );
		if ( ! wfEmptyMsg( $msg, $out ) and  $out !== '' and ! $this->including() ) {
			$wgOut->wrapWikiMsg( "<div class='mw-specialpage-summary'>\n$1\n</div>", $msg );
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
	 * @return String
	 */
	function getRedirectQuery() {
		global $wgRequest;
		$params = array();
		foreach( $this->mAllowedRedirectParams as $arg ) {
			if( ( $val = $wgRequest->getVal( $arg, null ) ) !== null )
				$params[] = $arg . '=' . $val;
		}
		
		foreach( $this->mAddedRedirectParams as $arg => $val ) {
			$params[] = $arg . '=' . $val;
		}
		
		return count( $params ) ? implode( '&', $params ) : false;
	}
}

/**
 * Shortcut to construct a special page which is unlisted by default
 * @ingroup SpecialPage
 */
class UnlistedSpecialPage extends SpecialPage
{
	function UnlistedSpecialPage( $name, $restriction = '', $function = false, $file = 'default' ) {
		SpecialPage::SpecialPage( $name, $restriction, false, $function, $file );
	}
}

/**
 * Shortcut to construct an includable special  page
 * @ingroup SpecialPage
 */
class IncludableSpecialPage extends SpecialPage
{
	function IncludableSpecialPage( $name, $restriction = '', $listed = true, $function = false, $file = 'default' ) {
		SpecialPage::SpecialPage( $name, $restriction, $listed, $function, $file, true );
	}
}

/**
 * Shortcut to construct a special page alias.
 * @ingroup SpecialPage
 */
class SpecialRedirectToSpecial extends UnlistedSpecialPage {
	var $redirName, $redirSubpage;

	function __construct( $name, $redirName, $redirSubpage = false, $allowedRedirectParams = array(), $addedRedirectParams = array() ) {
		parent::__construct( $name );
		$this->redirName = $redirName;
		$this->redirSubpage = $redirSubpage;
		$this->mAllowedRedirectParams = $allowedRedirectParams;
		$this->mAddedRedirectParams = $addedRedirectParams;
	}

	function getRedirect( $subpage ) {
		if ( $this->redirSubpage === false ) {
			return SpecialPage::getTitleFor( $this->redirName, $subpage );
		} else {
			return SpecialPage::getTitleFor( $this->redirName, $this->redirSubpage );
		}
	}
}

/** SpecialMypage, SpecialMytalk and SpecialMycontributions special pages
 * are used to get user independant links pointing to the user page, talk
 * page and list of contributions.
 * This can let us cache a single copy of any generated content for all
 * users.
 */

/**
 * Shortcut to construct a special page pointing to current user user's page.
 * @ingroup SpecialPage
 */
class SpecialMypage extends UnlistedSpecialPage {
	function __construct() {
		parent::__construct( 'Mypage' );
		$this->mAllowedRedirectParams = array( 'action' , 'preload' , 'editintro', 'section' );
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
class SpecialMytalk extends UnlistedSpecialPage {
	function __construct() {
		parent::__construct( 'Mytalk' );
		$this->mAllowedRedirectParams = array( 'action' , 'preload' , 'editintro', 'section' );
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
class SpecialMycontributions extends UnlistedSpecialPage {
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
