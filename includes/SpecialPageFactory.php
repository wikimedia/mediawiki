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
 * Factory for handling the special page list and generating SpecialPage objects
 * @ingroup SpecialPage
 * @since 1.17
 */
class SpecialPageFactory {

	/**
	 * List of special page names to the subclass of SpecialPage which handles them.
	 */
	private static $mList = array(
		// Maintenance Reports
		'BrokenRedirects'           => 'BrokenRedirectsPage',
		'Deadendpages'              => 'DeadendpagesPage',
		'DoubleRedirects'           => 'DoubleRedirectsPage',
		'Longpages'                 => 'LongpagesPage',
		'Ancientpages'              => 'AncientpagesPage',
		'Lonelypages'               => 'LonelypagesPage',
		'Fewestrevisions'           => 'FewestrevisionsPage',
		'Withoutinterwiki'          => 'WithoutinterwikiPage',
		'Protectedpages'            => 'SpecialProtectedpages',
		'Protectedtitles'           => 'SpecialProtectedtitles',
		'Shortpages'                => 'ShortpagesPage',
		'Uncategorizedcategories'   => 'UncategorizedcategoriesPage',
		'Uncategorizedimages'       => 'UncategorizedimagesPage',
		'Uncategorizedpages'        => 'UncategorizedpagesPage',
		'Uncategorizedtemplates'    => 'UncategorizedtemplatesPage',
		'Unusedcategories'          => 'UnusedcategoriesPage',
		'Unusedimages'              => 'UnusedimagesPage',
		'Unusedtemplates'           => 'UnusedtemplatesPage',
		'Unwatchedpages'            => 'UnwatchedpagesPage',
		'Wantedcategories'          => 'WantedcategoriesPage',
		'Wantedfiles'               => 'WantedfilesPage',
		'Wantedpages'               => 'WantedpagesPage',
		'Wantedtemplates'           => 'WantedtemplatesPage',

		// List of pages
		'Allpages'                  => 'SpecialAllpages',
		'Prefixindex'               => 'SpecialPrefixindex',
		'Categories'                => 'SpecialCategories',
		'Disambiguations'           => 'DisambiguationsPage',
		'Listredirects'             => 'ListredirectsPage',

		// Login/create account
		'Userlogin'                 => 'LoginForm',
		'CreateAccount'             => 'SpecialCreateAccount',

		// Users and rights
		'Block'                     => 'SpecialBlock',
		'Unblock'                   => 'SpecialUnblock',
		'BlockList'                 => 'SpecialBlockList',
		'ChangePassword'            => 'SpecialChangePassword',
		'PasswordReset'             => 'SpecialPasswordReset',
		'DeletedContributions'      => 'DeletedContributionsPage',
		'Preferences'               => 'SpecialPreferences',
		'Contributions'             => 'SpecialContributions',
		'Listgrouprights'           => 'SpecialListGroupRights',
		'Listusers'                 => 'SpecialListUsers' ,
		'Listadmins'                => 'SpecialListAdmins',
		'Listbots'                  => 'SpecialListBots',
		'Activeusers'               => 'SpecialActiveUsers',
		'Userrights'                => 'UserrightsPage',
		'EditWatchlist'             => 'SpecialEditWatchlist',

		// Recent changes and logs
		'Newimages'                 => 'SpecialNewFiles',
		'Log'                       => 'SpecialLog',
		'Watchlist'                 => 'SpecialWatchlist',
		'Newpages'                  => 'SpecialNewpages',
		'Recentchanges'             => 'SpecialRecentchanges',
		'Recentchangeslinked'       => 'SpecialRecentchangeslinked',
		'Tags'                      => 'SpecialTags',

		// Media reports and uploads
		'Listfiles'                 => 'SpecialListFiles',
		'Filepath'                  => 'SpecialFilepath',
		'MIMEsearch'                => 'MIMEsearchPage',
		'FileDuplicateSearch'       => 'FileDuplicateSearchPage',
		'Upload'                    => 'SpecialUpload',
		'UploadStash'               => 'SpecialUploadStash',

		// Wiki data and tools
		'Statistics'                => 'SpecialStatistics',
		'Allmessages'               => 'SpecialAllmessages',
		'Version'                   => 'SpecialVersion',
		'Lockdb'                    => 'SpecialLockdb',
		'Unlockdb'                  => 'SpecialUnlockdb',

		// Redirecting special pages
		'LinkSearch'                => 'LinkSearchPage',
		'Randompage'                => 'Randompage',
		'Randomredirect'            => 'SpecialRandomredirect',

		// High use pages
		'Mostlinkedcategories'      => 'MostlinkedCategoriesPage',
		'Mostimages'                => 'MostimagesPage',
		'Mostlinked'                => 'MostlinkedPage',
		'Mostlinkedtemplates'       => 'MostlinkedTemplatesPage',
		'Mostcategories'            => 'MostcategoriesPage',
		'Mostrevisions'             => 'MostrevisionsPage',

		// Page tools
		'ComparePages'              => 'SpecialComparePages',
		'Export'                    => 'SpecialExport',
		'Import'                    => 'SpecialImport',
		'Undelete'                  => 'SpecialUndelete',
		'Whatlinkshere'             => 'SpecialWhatlinkshere',
		'MergeHistory'              => 'SpecialMergeHistory',

		// Other
		'Booksources'               => 'SpecialBookSources',

		// Unlisted / redirects
		'Blankpage'                 => 'SpecialBlankpage',
		'Blockme'                   => 'SpecialBlockme',
		'Emailuser'                 => 'SpecialEmailUser',
		'Movepage'                  => 'MovePageForm',
		'Mycontributions'           => 'SpecialMycontributions',
		'Mypage'                    => 'SpecialMypage',
		'Mytalk'                    => 'SpecialMytalk',
		'Myuploads'                 => 'SpecialMyuploads',
		'PermanentLink'             => 'SpecialPermanentLink',
		'Revisiondelete'            => 'SpecialRevisionDelete',
		'Specialpages'              => 'SpecialSpecialpages',
		'Userlogout'                => 'SpecialUserlogout',
	);

	private static $mAliases;

	/**
	 * Initialise the special page list
	 * This must be called before accessing SpecialPage::$mList
	 *
	 * @return array
	 */
	static function getList() {
		global $wgSpecialPages;
		global $wgDisableCounters, $wgDisableInternalSearch, $wgEmailAuthentication;

		if ( !is_object( self::$mList ) ) {
			wfProfileIn( __METHOD__ );

			if ( !$wgDisableCounters ) {
				self::$mList['Popularpages'] = 'PopularpagesPage';
			}

			if ( !$wgDisableInternalSearch ) {
				self::$mList['Search'] = 'SpecialSearch';
			}

			if ( $wgEmailAuthentication ) {
				self::$mList['Confirmemail'] = 'EmailConfirmation';
				self::$mList['Invalidateemail'] = 'EmailInvalidation';
			}

			// Add extension special pages
			self::$mList = array_merge( self::$mList, $wgSpecialPages );

			// Run hooks
			// This hook can be used to remove undesired built-in special pages
			wfRunHooks( 'SpecialPage_initList', array( &self::$mList ) );

			// Cast to object: func()[$key] doesn't work, but func()->$key does
			settype( self::$mList, 'object' );

			wfProfileOut( __METHOD__ );
		}
		return self::$mList;
	}

	/**
	 * Initialise and return the list of special page aliases.  Returns an object with
	 * properties which can be accessed $obj->pagename - each property is an array of
	 * aliases; the first in the array is the cannonical alias.  All registered special
	 * pages are guaranteed to have a property entry, and for that property array to
	 * contain at least one entry (English fallbacks will be added if necessary).
	 * @return Object
	 */
	static function getAliasList() {
		if ( !is_object( self::$mAliases ) ) {
			global $wgContLang;
			$aliases = $wgContLang->getSpecialPageAliases();

			// Objects are passed by reference by default, need to create a copy
			$missingPages = clone self::getList();

			self::$mAliases = array();
			foreach ( $aliases as $realName => $aliasList ) {
				foreach ( $aliasList as $alias ) {
					self::$mAliases[$wgContLang->caseFold( $alias )] = $realName;
				}
				unset( $missingPages->$realName );
			}
			foreach ( $missingPages as $name => $stuff ) {
				self::$mAliases[$wgContLang->caseFold( $name )] = $name;
			}

			// Cast to object: func()[$key] doesn't work, but func()->$key does
			self::$mAliases = (object)self::$mAliases;
		}
		return self::$mAliases;
	}

	/**
	 * Given a special page name with a possible subpage, return an array
	 * where the first element is the special page name and the second is the
	 * subpage.
	 *
	 * @param $alias String
	 * @return Array( String, String|null ), or array( null, null ) if the page is invalid
	 */
	public static function resolveAlias( $alias ) {
		global $wgContLang;
		$bits = explode( '/', $alias, 2 );

		$caseFoldedAlias = $wgContLang->caseFold( $bits[0] );
		$caseFoldedAlias = str_replace( ' ', '_', $caseFoldedAlias );
		if ( isset( self::getAliasList()->$caseFoldedAlias ) ) {
			$name = self::getAliasList()->$caseFoldedAlias;
		} else {
			return array( null, null );
		}

		if ( !isset( $bits[1] ) ) { // bug 2087
			$par = null;
		} else {
			$par = $bits[1];
		}

		return array( $name, $par );
	}

	/**
	 * Add a page to a certain display group for Special:SpecialPages
	 *
	 * @param $page Mixed: SpecialPage or string
	 * @param $group String
	 */
	public static function setGroup( $page, $group ) {
		global $wgSpecialPageGroups;
		$name = is_object( $page ) ? $page->mName : $page;
		$wgSpecialPageGroups[$name] = $group;
	}

	/**
	 * Add a page to a certain display group for Special:SpecialPages
	 *
	 * @param $page SpecialPage
	 */
	public static function getGroup( &$page ) {
		global $wgSpecialPageGroups;
		static $specialPageGroupsCache = array();
		if ( isset( $specialPageGroupsCache[$page->mName] ) ) {
			return $specialPageGroupsCache[$page->mName];
		}
		$msg = wfMessage( 'specialpages-specialpagegroup-' . strtolower( $page->mName ) );
		if ( !$msg->isBlank() ) {
			$group = $msg->text();
		} else {
			$group = isset( $wgSpecialPageGroups[$page->mName] )
				? $wgSpecialPageGroups[$page->mName]
				: '-';
		}
		if ( $group == '-' ) {
			$group = 'other';
		}
		$specialPageGroupsCache[$page->mName] = $group;
		return $group;
	}

	/**
	 * Check if a given name exist as a special page or as a special page alias
	 *
	 * @param $name String: name of a special page
	 * @return Boolean: true if a special page exists with this name
	 */
	public static function exists( $name ) {
		list( $title, /*...*/ ) = self::resolveAlias( $name );
		return property_exists( self::getList(), $title );
	}

	/**
	 * Find the object with a given name and return it (or NULL)
	 *
	 * @param $name String Special page name, may be localised and/or an alias
	 * @return SpecialPage object or null if the page doesn't exist
	 */
	public static function getPage( $name ) {
		list( $realName, /*...*/ ) = self::resolveAlias( $name );
		if ( property_exists( self::getList(), $realName ) ) {
			$rec = self::getList()->$realName;
			if ( is_string( $rec ) ) {
				$className = $rec;
				return new $className;
			} elseif ( is_array( $rec ) ) {
				// @deprecated, officially since 1.18, unofficially since forever
				wfDebug( "Array syntax for \$wgSpecialPages is deprecated, define a subclass of SpecialPage instead." );
				$className = array_shift( $rec );
				self::getList()->$realName = MWFunction::newObj( $className, $rec );
			}
			return self::getList()->$realName;
		} else {
			return null;
		}
	}

	/**
	 * Return categorised listable special pages which are available
	 * for the current user, and everyone.
	 *
	 * @return Array( String => Specialpage )
	 */
	public static function getUsablePages() {
		global $wgUser;
		$pages = array();
		foreach ( self::getList() as $name => $rec ) {
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
	 * @return Array( String => Specialpage )
	 */
	public static function getRegularPages() {
		$pages = array();
		foreach ( self::getList() as $name => $rec ) {
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
	 * @return Array( String => Specialpage )
	 */
	public static function getRestrictedPages() {
		global $wgUser;
		$pages = array();
		foreach ( self::getList() as $name => $rec ) {
			$page = self::getPage( $name );
			if (
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
	 * The path may contain parameters, e.g. Special:Name/Params
	 * Extracts the special page name and call the execute method, passing the parameters
	 *
	 * Returns a title object if the page is redirected, false if there was no such special
	 * page, and true if it was successful.
	 *
	 * @param $title          Title object
	 * @param $context        RequestContext
	 * @param $including      Bool output is being captured for use in {{special:whatever}}
	 *
	 * @return bool
	 */
	public static function executePath( Title &$title, RequestContext &$context, $including = false ) {
		wfProfileIn( __METHOD__ );

		// @todo FIXME: Redirects broken due to this call
		$bits = explode( '/', $title->getDBkey(), 2 );
		$name = $bits[0];
		if ( !isset( $bits[1] ) ) { // bug 2087
			$par = null;
		} else {
			$par = $bits[1];
		}
		$page = self::getPage( $name );
		// Nonexistent?
		if ( !$page ) {
			$context->output->setArticleRelated( false );
			$context->output->setRobotPolicy( 'noindex,nofollow' );
			$context->output->setStatusCode( 404 );
			$context->output->showErrorPage( 'nosuchspecialpage', 'nospecialpagetext' );
			wfProfileOut( __METHOD__ );
			return false;
		}

		// Page exists, set the context
		$page->setContext( $context );

		if ( !$including ) {
			// Redirect to canonical alias for GET commands
			// Not for POST, we'd lose the post data, so it's best to just distribute
			// the request. Such POST requests are possible for old extensions that
			// generate self-links without being aware that their default name has
			// changed.
			if ( $name != $page->getLocalName() && !$context->request->wasPosted() ) {
				$query = $context->request->getQueryValues();
				unset( $query['title'] );
				$query = wfArrayToCGI( $query );
				$title = $page->getTitle( $par );
				$url = $title->getFullUrl( $query );
				$context->output->redirect( $url );
				wfProfileOut( __METHOD__ );
				return $title;
			} else {
				$context->title = $page->getTitle();
			}

		} elseif ( !$page->isIncludable() ) {
			wfProfileOut( __METHOD__ );
			return false;
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
	 * @param $title Title
	 *
	 * @return String: HTML fragment
	 */
	static function capturePath( &$title ) {
		global $wgOut, $wgTitle, $wgRequest;

		$oldTitle = $wgTitle;
		$oldOut = $wgOut;
		$oldRequest = $wgRequest;

		// Don't want special pages interpreting ?feed=atom parameters.
		$wgRequest = new FauxRequest( array() );

		$context = new RequestContext;
		$context->setTitle( $title );
		$context->setRequest( $wgRequest );
		$wgOut = $context->getOutput();

		$ret = self::executePath( $title, $context, true );
		if ( $ret === true ) {
			$ret = $wgOut->getHTML();
		}
		$wgTitle = $oldTitle;
		$wgOut = $oldOut;
		$wgRequest = $oldRequest;
		return $ret;
	}

	/**
	 * Get the local name for a specified canonical name
	 *
	 * @param $name String
	 * @param $subpage String|Bool
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
					wfWarn( "Found alias defined for $n when searching for " .
						"special page aliases for $name. Case mismatch?" );
					$name = $values[0];
					$found = true;
					break;
				}
			}
			if ( !$found ) {
				wfWarn( "Did not find alias for special page '$name'. " .
					"Perhaps no aliases are defined for it?" );
			}
		}
		if ( $subpage !== false && !is_null( $subpage ) ) {
			$name = "$name/$subpage";
		}
		return $wgContLang->ucfirst( $name );
	}

	/**
	 * Get a title for a given alias
	 *
	 * @param $alias String
	 *
	 * @return Title or null if there is no such alias
	 */
	static function getTitleForAlias( $alias ) {
		$name = self::resolveAlias( $alias );
		if ( $name ) {
			return SpecialPage::getTitleFor( $name );
		} else {
			return null;
		}
	}
}
