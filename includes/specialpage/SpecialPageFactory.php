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
use MediaWiki\Linker\LinkRenderer;

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
 * SpecialPageFactory::$list. To remove a core static special page at runtime, use
 * a SpecialPage_initList hook.
 *
 * @ingroup SpecialPage
 * @since 1.17
 */
class SpecialPageFactory {
	/**
	 * List of special page names to the subclass of SpecialPage which handles them.
	 */
	private static $coreList = [
		// Maintenance Reports
		'BrokenRedirects' => 'BrokenRedirectsPage',
		'Deadendpages' => 'DeadendPagesPage',
		'DoubleRedirects' => 'DoubleRedirectsPage',
		'Longpages' => 'LongPagesPage',
		'Ancientpages' => 'AncientPagesPage',
		'Lonelypages' => 'LonelyPagesPage',
		'Fewestrevisions' => 'FewestrevisionsPage',
		'Withoutinterwiki' => 'WithoutInterwikiPage',
		'Protectedpages' => 'SpecialProtectedpages',
		'Protectedtitles' => 'SpecialProtectedtitles',
		'Shortpages' => 'ShortPagesPage',
		'Uncategorizedcategories' => 'UncategorizedCategoriesPage',
		'Uncategorizedimages' => 'UncategorizedImagesPage',
		'Uncategorizedpages' => 'UncategorizedPagesPage',
		'Uncategorizedtemplates' => 'UncategorizedTemplatesPage',
		'Unusedcategories' => 'UnusedCategoriesPage',
		'Unusedimages' => 'UnusedimagesPage',
		'Unusedtemplates' => 'UnusedtemplatesPage',
		'Unwatchedpages' => 'UnwatchedpagesPage',
		'Wantedcategories' => 'WantedCategoriesPage',
		'Wantedfiles' => 'WantedFilesPage',
		'Wantedpages' => 'WantedPagesPage',
		'Wantedtemplates' => 'WantedTemplatesPage',

		// List of pages
		'Allpages' => 'SpecialAllPages',
		'Prefixindex' => 'SpecialPrefixindex',
		'Categories' => 'SpecialCategories',
		'Listredirects' => 'ListredirectsPage',
		'PagesWithProp' => 'SpecialPagesWithProp',
		'TrackingCategories' => 'SpecialTrackingCategories',

		// Authentication
		'Userlogin' => 'SpecialUserLogin',
		'Userlogout' => 'SpecialUserLogout',
		'CreateAccount' => 'SpecialCreateAccount',
		'LinkAccounts' => 'SpecialLinkAccounts',
		'UnlinkAccounts' => 'SpecialUnlinkAccounts',
		'ChangeCredentials' => 'SpecialChangeCredentials',
		'RemoveCredentials' => 'SpecialRemoveCredentials',

		// Users and rights
		'Activeusers' => 'SpecialActiveUsers',
		'Block' => 'SpecialBlock',
		'Unblock' => 'SpecialUnblock',
		'BlockList' => 'SpecialBlockList',
		'AutoblockList' => 'SpecialAutoblockList',
		'ChangePassword' => 'SpecialChangePassword',
		'BotPasswords' => 'SpecialBotPasswords',
		'PasswordReset' => 'SpecialPasswordReset',
		'DeletedContributions' => 'DeletedContributionsPage',
		'Preferences' => 'SpecialPreferences',
		'ResetTokens' => 'SpecialResetTokens',
		'Contributions' => 'SpecialContributions',
		'Listgrouprights' => 'SpecialListGroupRights',
		'Listgrants' => 'SpecialListGrants',
		'Listusers' => 'SpecialListUsers',
		'Listadmins' => 'SpecialListAdmins',
		'Listbots' => 'SpecialListBots',
		'Userrights' => 'UserrightsPage',
		'EditWatchlist' => 'SpecialEditWatchlist',

		// Recent changes and logs
		'Newimages' => 'SpecialNewFiles',
		'Log' => 'SpecialLog',
		'Watchlist' => 'SpecialWatchlist',
		'Newpages' => 'SpecialNewpages',
		'Recentchanges' => 'SpecialRecentChanges',
		'Recentchangeslinked' => 'SpecialRecentChangesLinked',
		'Tags' => 'SpecialTags',

		// Media reports and uploads
		'Listfiles' => 'SpecialListFiles',
		'Filepath' => 'SpecialFilepath',
		'MediaStatistics' => 'MediaStatisticsPage',
		'MIMEsearch' => 'MIMEsearchPage',
		'FileDuplicateSearch' => 'FileDuplicateSearchPage',
		'Upload' => 'SpecialUpload',
		'UploadStash' => 'SpecialUploadStash',
		'ListDuplicatedFiles' => 'ListDuplicatedFilesPage',

		// Data and tools
		'ApiSandbox' => 'SpecialApiSandbox',
		'Statistics' => 'SpecialStatistics',
		'Allmessages' => 'SpecialAllMessages',
		'Version' => 'SpecialVersion',
		'Lockdb' => 'SpecialLockdb',
		'Unlockdb' => 'SpecialUnlockdb',

		// Redirecting special pages
		'LinkSearch' => 'LinkSearchPage',
		'Randompage' => 'RandomPage',
		'RandomInCategory' => 'SpecialRandomInCategory',
		'Randomredirect' => 'SpecialRandomredirect',
		'Randomrootpage' => 'SpecialRandomrootpage',
		'GoToInterwiki' => 'SpecialGoToInterwiki',

		// High use pages
		'Mostlinkedcategories' => 'MostlinkedCategoriesPage',
		'Mostimages' => 'MostimagesPage',
		'Mostinterwikis' => 'MostinterwikisPage',
		'Mostlinked' => 'MostlinkedPage',
		'Mostlinkedtemplates' => 'MostlinkedTemplatesPage',
		'Mostcategories' => 'MostcategoriesPage',
		'Mostrevisions' => 'MostrevisionsPage',

		// Page tools
		'ComparePages' => 'SpecialComparePages',
		'Export' => 'SpecialExport',
		'Import' => 'SpecialImport',
		'Undelete' => 'SpecialUndelete',
		'Whatlinkshere' => 'SpecialWhatLinksHere',
		'MergeHistory' => 'SpecialMergeHistory',
		'ExpandTemplates' => 'SpecialExpandTemplates',

		// Other
		'Booksources' => 'SpecialBookSources',

		// Unlisted / redirects
		'ApiHelp' => 'SpecialApiHelp',
		'Blankpage' => 'SpecialBlankpage',
		'Diff' => 'SpecialDiff',
		'EditTags' => 'SpecialEditTags',
		'Emailuser' => 'SpecialEmailUser',
		'Movepage' => 'MovePageForm',
		'Mycontributions' => 'SpecialMycontributions',
		'MyLanguage' => 'SpecialMyLanguage',
		'Mypage' => 'SpecialMypage',
		'Mytalk' => 'SpecialMytalk',
		'Myuploads' => 'SpecialMyuploads',
		'AllMyUploads' => 'SpecialAllMyUploads',
		'PermanentLink' => 'SpecialPermanentLink',
		'Redirect' => 'SpecialRedirect',
		'Revisiondelete' => 'SpecialRevisionDelete',
		'RunJobs' => 'SpecialRunJobs',
		'Specialpages' => 'SpecialSpecialpages',
	];

	private static $list;
	private static $aliases;

	/**
	 * Reset the internal list of special pages. Useful when changing $wgSpecialPages after
	 * the internal list has already been initialized, e.g. during testing.
	 */
	public static function resetList() {
		self::$list = null;
		self::$aliases = null;
	}

	/**
	 * Returns a list of canonical special page names.
	 * May be used to iterate over all registered special pages.
	 *
	 * @return string[]
	 */
	public static function getNames() {
		return array_keys( self::getPageList() );
	}

	/**
	 * Get the special page list as an array
	 *
	 * @deprecated since 1.24, use getNames() instead.
	 * @return array
	 */
	public static function getList() {
		wfDeprecated( __FUNCTION__, '1.24' );
		return self::getPageList();
	}

	/**
	 * Get the special page list as an array
	 *
	 * @return array
	 */
	private static function getPageList() {
		global $wgSpecialPages;
		global $wgDisableInternalSearch, $wgEmailAuthentication;
		global $wgEnableEmail, $wgEnableJavaScriptTest;
		global $wgPageLanguageUseDB, $wgContentHandlerUseDB;

		if ( !is_array( self::$list ) ) {

			self::$list = self::$coreList;

			if ( !$wgDisableInternalSearch ) {
				self::$list['Search'] = 'SpecialSearch';
			}

			if ( $wgEmailAuthentication ) {
				self::$list['Confirmemail'] = 'EmailConfirmation';
				self::$list['Invalidateemail'] = 'EmailInvalidation';
			}

			if ( $wgEnableEmail ) {
				self::$list['ChangeEmail'] = 'SpecialChangeEmail';
			}

			if ( $wgEnableJavaScriptTest ) {
				self::$list['JavaScriptTest'] = 'SpecialJavaScriptTest';
			}

			if ( $wgPageLanguageUseDB ) {
				self::$list['PageLanguage'] = 'SpecialPageLanguage';
			}
			if ( $wgContentHandlerUseDB ) {
				self::$list['ChangeContentModel'] = 'SpecialChangeContentModel';
			}

			// Add extension special pages
			self::$list = array_merge( self::$list, $wgSpecialPages );

			// This hook can be used to disable unwanted core special pages
			// or conditionally register special pages.
			Hooks::run( 'SpecialPage_initList', [ &self::$list ] );

		}

		return self::$list;
	}

	/**
	 * Initialise and return the list of special page aliases. Returns an array where
	 * the key is an alias, and the value is the canonical name of the special page.
	 * All registered special pages are guaranteed to map to themselves.
	 * @return array
	 */
	private static function getAliasList() {
		if ( is_null( self::$aliases ) ) {
			global $wgContLang;
			$aliases = $wgContLang->getSpecialPageAliases();
			$pageList = self::getPageList();

			self::$aliases = [];
			$keepAlias = [];

			// Force every canonical name to be an alias for itself.
			foreach ( $pageList as $name => $stuff ) {
				$caseFoldedAlias = $wgContLang->caseFold( $name );
				self::$aliases[$caseFoldedAlias] = $name;
				$keepAlias[$caseFoldedAlias] = 'canonical';
			}

			// Check for $aliases being an array since Language::getSpecialPageAliases can return null
			if ( is_array( $aliases ) ) {
				foreach ( $aliases as $realName => $aliasList ) {
					$aliasList = array_values( $aliasList );
					foreach ( $aliasList as $i => $alias ) {
						$caseFoldedAlias = $wgContLang->caseFold( $alias );

						if ( isset( self::$aliases[$caseFoldedAlias] ) &&
							$realName === self::$aliases[$caseFoldedAlias]
						) {
							// Ignore same-realName conflicts
							continue;
						}

						if ( !isset( $keepAlias[$caseFoldedAlias] ) ) {
							self::$aliases[$caseFoldedAlias] = $realName;
							if ( !$i ) {
								$keepAlias[$caseFoldedAlias] = 'first';
							}
						} elseif ( !$i ) {
							wfWarn( "First alias '$alias' for $realName conflicts with " .
								"{$keepAlias[$caseFoldedAlias]} alias for " .
								self::$aliases[$caseFoldedAlias]
							);
						}
					}
				}
			}
		}

		return self::$aliases;
	}

	/**
	 * Given a special page name with a possible subpage, return an array
	 * where the first element is the special page name and the second is the
	 * subpage.
	 *
	 * @param string $alias
	 * @return array Array( String, String|null ), or array( null, null ) if the page is invalid
	 */
	public static function resolveAlias( $alias ) {
		global $wgContLang;
		$bits = explode( '/', $alias, 2 );

		$caseFoldedAlias = $wgContLang->caseFold( $bits[0] );
		$caseFoldedAlias = str_replace( ' ', '_', $caseFoldedAlias );
		$aliases = self::getAliasList();
		if ( isset( $aliases[$caseFoldedAlias] ) ) {
			$name = $aliases[$caseFoldedAlias];
		} else {
			return [ null, null ];
		}

		if ( !isset( $bits[1] ) ) { // T4087
			$par = null;
		} else {
			$par = $bits[1];
		}

		return [ $name, $par ];
	}

	/**
	 * Check if a given name exist as a special page or as a special page alias
	 *
	 * @param string $name Name of a special page
	 * @return bool True if a special page exists with this name
	 */
	public static function exists( $name ) {
		list( $title, /*...*/ ) = self::resolveAlias( $name );

		$specialPageList = self::getPageList();
		return isset( $specialPageList[$title] );
	}

	/**
	 * Find the object with a given name and return it (or NULL)
	 *
	 * @param string $name Special page name, may be localised and/or an alias
	 * @return SpecialPage|null SpecialPage object or null if the page doesn't exist
	 */
	public static function getPage( $name ) {
		list( $realName, /*...*/ ) = self::resolveAlias( $name );

		$specialPageList = self::getPageList();

		if ( isset( $specialPageList[$realName] ) ) {
			$rec = $specialPageList[$realName];

			if ( is_callable( $rec ) ) {
				// Use callback to instantiate the special page
				$page = call_user_func( $rec );
			} elseif ( is_string( $rec ) ) {
				$className = $rec;
				$page = new $className;
			} elseif ( is_array( $rec ) ) {
				$className = array_shift( $rec );
				// @deprecated, officially since 1.18, unofficially since forever
				wfDeprecated( "Array syntax for \$wgSpecialPages is deprecated ($className), " .
					"define a subclass of SpecialPage instead.", '1.18' );
				$page = ObjectFactory::getObjectFromSpec( [
					'class' => $className,
					'args' => $rec,
					'closure_expansion' => false,
				] );
			} elseif ( $rec instanceof SpecialPage ) {
				$page = $rec; // XXX: we should deep clone here
			} else {
				$page = null;
			}

			if ( $page instanceof SpecialPage ) {
				return $page;
			} else {
				// It's not a classname, nor a callback, nor a legacy constructor array,
				// nor a special page object. Give up.
				wfLogWarning( "Cannot instantiate special page $realName: bad spec!" );
				return null;
			}

		} else {
			return null;
		}
	}

	/**
	 * Return categorised listable special pages which are available
	 * for the current user, and everyone.
	 *
	 * @param User $user User object to check permissions, $wgUser will be used
	 *        if not provided
	 * @return array ( string => Specialpage )
	 */
	public static function getUsablePages( User $user = null ) {
		$pages = [];
		if ( $user === null ) {
			global $wgUser;
			$user = $wgUser;
		}
		foreach ( self::getPageList() as $name => $rec ) {
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
	 * @return array ( string => Specialpage )
	 */
	public static function getRegularPages() {
		$pages = [];
		foreach ( self::getPageList() as $name => $rec ) {
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
	 * @param User|null $user User object to use or null for $wgUser
	 * @return array ( string => Specialpage )
	 */
	public static function getRestrictedPages( User $user = null ) {
		$pages = [];
		if ( $user === null ) {
			global $wgUser;
			$user = $wgUser;
		}
		foreach ( self::getPageList() as $name => $rec ) {
			$page = self::getPage( $name );
			if (
				$page->isListed()
				&& $page->isRestricted()
				&& $page->userCanExecute( $user )
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
	 * @param Title $title
	 * @param IContextSource $context
	 * @param bool $including Bool output is being captured for use in {{special:whatever}}
	 * @param LinkRenderer|null $linkRenderer (since 1.28)
	 *
	 * @return bool|Title
	 */
	public static function executePath( Title &$title, IContextSource &$context, $including = false,
		LinkRenderer $linkRenderer = null
	) {
		// @todo FIXME: Redirects broken due to this call
		$bits = explode( '/', $title->getDBkey(), 2 );
		$name = $bits[0];
		if ( !isset( $bits[1] ) ) { // T4087
			$par = null;
		} else {
			$par = $bits[1];
		}

		$page = self::getPage( $name );
		if ( !$page ) {
			$context->getOutput()->setArticleRelated( false );
			$context->getOutput()->setRobotPolicy( 'noindex,nofollow' );

			global $wgSend404Code;
			if ( $wgSend404Code ) {
				$context->getOutput()->setStatusCode( 404 );
			}

			$context->getOutput()->showErrorPage( 'nosuchspecialpage', 'nospecialpagetext' );

			return false;
		}

		if ( !$including ) {
			// Narrow DB query expectations for this HTTP request
			$trxLimits = $context->getConfig()->get( 'TrxProfilerLimits' );
			$trxProfiler = Profiler::instance()->getTransactionProfiler();
			if ( $context->getRequest()->wasPosted() && !$page->doesWrites() ) {
				$trxProfiler->setExpectations( $trxLimits['POST-nonwrite'], __METHOD__ );
				$context->getRequest()->markAsSafeRequest();
			}
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
				$title = $page->getPageTitle( $par );
				$url = $title->getFullURL( $query );
				$context->getOutput()->redirect( $url );

				return $title;
			} else {
				$context->setTitle( $page->getPageTitle( $par ) );
			}
		} elseif ( !$page->isIncludable() ) {
			return false;
		}

		$page->including( $including );
		if ( $linkRenderer ) {
			$page->setLinkRenderer( $linkRenderer );
		}

		// Execute special page
		$page->run( $par );

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
	 * @param Title $title
	 * @param IContextSource $context
	 * @param LinkRenderer|null $linkRenderer (since 1.28)
	 * @return string HTML fragment
	 */
	public static function capturePath(
		Title $title, IContextSource $context, LinkRenderer $linkRenderer = null
	) {
		global $wgTitle, $wgOut, $wgRequest, $wgUser, $wgLang;
		$main = RequestContext::getMain();

		// Save current globals and main context
		$glob = [
			'title' => $wgTitle,
			'output' => $wgOut,
			'request' => $wgRequest,
			'user' => $wgUser,
			'language' => $wgLang,
		];
		$ctx = [
			'title' => $main->getTitle(),
			'output' => $main->getOutput(),
			'request' => $main->getRequest(),
			'user' => $main->getUser(),
			'language' => $main->getLanguage(),
		];

		// Override
		$wgTitle = $title;
		$wgOut = $context->getOutput();
		$wgRequest = $context->getRequest();
		$wgUser = $context->getUser();
		$wgLang = $context->getLanguage();
		$main->setTitle( $title );
		$main->setOutput( $context->getOutput() );
		$main->setRequest( $context->getRequest() );
		$main->setUser( $context->getUser() );
		$main->setLanguage( $context->getLanguage() );

		// The useful part
		$ret = self::executePath( $title, $context, true, $linkRenderer );

		// Restore old globals and context
		$wgTitle = $glob['title'];
		$wgOut = $glob['output'];
		$wgRequest = $glob['request'];
		$wgUser = $glob['user'];
		$wgLang = $glob['language'];
		$main->setTitle( $ctx['title'] );
		$main->setOutput( $ctx['output'] );
		$main->setRequest( $ctx['request'] );
		$main->setUser( $ctx['user'] );
		$main->setLanguage( $ctx['language'] );

		return $ret;
	}

	/**
	 * Get the local name for a specified canonical name
	 *
	 * @param string $name
	 * @param string|bool $subpage
	 * @return string
	 */
	public static function getLocalNameFor( $name, $subpage = false ) {
		global $wgContLang;
		$aliases = $wgContLang->getSpecialPageAliases();
		$aliasList = self::getAliasList();

		// Find the first alias that maps back to $name
		if ( isset( $aliases[$name] ) ) {
			$found = false;
			foreach ( $aliases[$name] as $alias ) {
				$caseFoldedAlias = $wgContLang->caseFold( $alias );
				$caseFoldedAlias = str_replace( ' ', '_', $caseFoldedAlias );
				if ( isset( $aliasList[$caseFoldedAlias] ) &&
					$aliasList[$caseFoldedAlias] === $name
				) {
					$name = $alias;
					$found = true;
					break;
				}
			}
			if ( !$found ) {
				wfWarn( "Did not find a usable alias for special page '$name'. " .
					"It seems all defined aliases conflict?" );
			}
		} else {
			// Check if someone misspelled the correct casing
			if ( is_array( $aliases ) ) {
				foreach ( $aliases as $n => $values ) {
					if ( strcasecmp( $name, $n ) === 0 ) {
						wfWarn( "Found alias defined for $n when searching for " .
							"special page aliases for $name. Case mismatch?" );
						return self::getLocalNameFor( $n, $subpage );
					}
				}
			}

			wfWarn( "Did not find alias for special page '$name'. " .
				"Perhaps no aliases are defined for it?" );
		}

		if ( $subpage !== false && !is_null( $subpage ) ) {
			// Make sure it's in dbkey form
			$subpage = str_replace( ' ', '_', $subpage );
			$name = "$name/$subpage";
		}

		return $wgContLang->ucfirst( $name );
	}

	/**
	 * Get a title for a given alias
	 *
	 * @param string $alias
	 * @return Title|null Title or null if there is no such alias
	 */
	public static function getTitleForAlias( $alias ) {
		list( $name, $subpage ) = self::resolveAlias( $alias );
		if ( $name != null ) {
			return SpecialPage::getTitleFor( $name, $subpage );
		} else {
			return null;
		}
	}
}
