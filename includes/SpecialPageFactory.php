<?php
/**
 * Factory for handling the special page list and generating SpecialPage objects.
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
 * @defgroup SpecialPage SpecialPage
 */

/**
 * Factory for handling the special page list and generating SpecialPage objects.
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
		'Listredirects'             => 'ListredirectsPage',
		'PagesWithProp'             => 'SpecialPagesWithProp',

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
		'ResetTokens'               => 'SpecialResetTokens',
		'Contributions'             => 'SpecialContributions',
		'Listgrouprights'           => 'SpecialListGroupRights',
		'Listusers'                 => 'SpecialListUsers',
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

		// Data and tools
		'Statistics'                => 'SpecialStatistics',
		'Allmessages'               => 'SpecialAllmessages',
		'Version'                   => 'SpecialVersion',
		'Lockdb'                    => 'SpecialLockdb',
		'Unlockdb'                  => 'SpecialUnlockdb',

		// Redirecting special pages
		'LinkSearch'                => 'LinkSearchPage',
		'Randompage'                => 'Randompage',
		'RandomInCategory'          => 'SpecialRandomInCategory',
		'Randomredirect'            => 'SpecialRandomredirect',

		// High use pages
		'Mostlinkedcategories'      => 'MostlinkedCategoriesPage',
		'Mostimages'                => 'MostimagesPage',
		'Mostinterwikis'            => 'MostinterwikisPage',
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
		'Emailuser'                 => 'SpecialEmailUser',
		'Movepage'                  => 'MovePageForm',
		'Mycontributions'           => 'SpecialMycontributions',
		'Mypage'                    => 'SpecialMypage',
		'Mytalk'                    => 'SpecialMytalk',
		'Myuploads'                 => 'SpecialMyuploads',
		'AllMyUploads'              => 'SpecialAllMyUploads',
		'PermanentLink'             => 'SpecialPermanentLink',
		'Redirect'                  => 'SpecialRedirect',
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
		global $wgEnableEmail, $wgEnableJavaScriptTest;

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

			if ( $wgEnableEmail ) {
				self::$mList['ChangeEmail'] = 'SpecialChangeEmail';
			}

			if ( $wgEnableJavaScriptTest ) {
				self::$mList['JavaScriptTest'] = 'SpecialJavaScriptTest';
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
	 * aliases; the first in the array is the canonical alias.  All registered special
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
	 * @deprecated since 1.21 Override SpecialPage::getGroupName
	 */
	public static function setGroup( $page, $group ) {
		wfDeprecated( __METHOD__, '1.21' );

		global $wgSpecialPageGroups;
		$name = is_object( $page ) ? $page->getName() : $page;
		$wgSpecialPageGroups[$name] = $group;
	}

	/**
	 * Get the group that the special page belongs in on Special:SpecialPage
	 *
	 * @param $page SpecialPage
	 * @return String
	 * @deprecated since 1.21 Use SpecialPage::getFinalGroupName
	 */
	public static function getGroup( &$page ) {
		wfDeprecated( __METHOD__, '1.21' );

		return $page->getFinalGroupName();
	}

	/**
	 * Check if a given name exist as a special page or as a special page alias
	 *
	 * @param string $name name of a special page
	 * @return Boolean: true if a special page exists with this name
	 */
	public static function exists( $name ) {
		list( $title, /*...*/ ) = self::resolveAlias( $name );
		return property_exists( self::getList(), $title );
	}

	/**
	 * Find the object with a given name and return it (or NULL)
	 *
	 * @param string $name Special page name, may be localised and/or an alias
	 * @return SpecialPage|null SpecialPage object or null if the page doesn't exist
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
	 * @param $user User object to check permissions, $wgUser will be used
	 *              if not provided
	 * @return Array( String => Specialpage )
	 */
	public static function getUsablePages( User $user = null ) {
		$pages = array();
		if ( $user === null ) {
			global $wgUser;
			$user = $wgUser;
		}
		foreach ( self::getList() as $name => $rec ) {
			$page = self::getPage( $name );
			if ( $page ) { // not null
				$page->setContext( RequestContext::getMain() );
				if ( $page->isListed()
					&& ( !$page->isRestricted() || $page->userCanExecute( $user ) )
				) {
					$pages[$name] = $page;
				}
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
	 * @param $context        IContextSource
	 * @param $including      Bool output is being captured for use in {{special:whatever}}
	 *
	 * @return bool
	 */
	public static function executePath( Title &$title, IContextSource &$context, $including = false ) {
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
			$context->getOutput()->setArticleRelated( false );
			$context->getOutput()->setRobotPolicy( 'noindex,nofollow' );

			global $wgSend404Code;
			if ( $wgSend404Code ) {
				$context->getOutput()->setStatusCode( 404 );
			}

			$context->getOutput()->showErrorPage( 'nosuchspecialpage', 'nospecialpagetext' );
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
			if ( $name != $page->getLocalName() && !$context->getRequest()->wasPosted() ) {
				$query = $context->getRequest()->getQueryValues();
				unset( $query['title'] );
				$title = $page->getTitle( $par );
				$url = $title->getFullURL( $query );
				$context->getOutput()->redirect( $url );
				wfProfileOut( __METHOD__ );
				return $title;
			} else {
				$context->setTitle( $page->getTitle( $par ) );
			}

		} elseif ( !$page->isIncludable() ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$page->including( $including );

		// Execute special page
		$profName = 'Special:' . $page->getName();
		wfProfileIn( $profName );
		$page->run( $par );
		wfProfileOut( $profName );
		wfProfileOut( __METHOD__ );
		return true;
	}

	/**
	 * Just like executePath() but will override global variables and execute
	 * the page in "inclusion" mode. Returns true if the execution was
	 * successful or false if there was no such special page, or a title object
	 * if it was a redirect.
	 *
	 * Also saves the current $wgTitle, $wgOut, $wgRequest, $wgUser and $wgLang
	 * variables so that the special page will get the context it'd expect on a
	 * normal request, and then restores them to their previous values after.
	 *
	 * @param $title Title
	 * @param $context IContextSource
	 *
	 * @return String: HTML fragment
	 */
	static function capturePath( Title $title, IContextSource $context ) {
		global $wgOut, $wgTitle, $wgRequest, $wgUser, $wgLang;

		// Save current globals
		$oldTitle = $wgTitle;
		$oldOut = $wgOut;
		$oldRequest = $wgRequest;
		$oldUser = $wgUser;
		$oldLang = $wgLang;

		// Set the globals to the current context
		$wgTitle = $title;
		$wgOut = $context->getOutput();
		$wgRequest = $context->getRequest();
		$wgUser = $context->getUser();
		$wgLang = $context->getLanguage();

		// The useful part
		$ret = self::executePath( $title, $context, true );

		// And restore the old globals
		$wgTitle = $oldTitle;
		$wgOut = $oldOut;
		$wgRequest = $oldRequest;
		$wgUser = $oldUser;
		$wgLang = $oldLang;

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
