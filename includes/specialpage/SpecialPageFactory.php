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

namespace MediaWiki\Special;

use Config;
use Hooks;
use IContextSource;
use Language;
use MediaWiki\Linker\LinkRenderer;
use Profiler;
use RequestContext;
use SpecialPage;
use Title;
use User;

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
 * @note There are two classes called SpecialPageFactory.  You should use this first one, in
 * namespace MediaWiki\Special, which is a service.  \SpecialPageFactory is a deprecated collection
 * of static methods that forwards to the global service.
 *
 * @ingroup SpecialPage
 * @since 1.17
 */
class SpecialPageFactory {
	/**
	 * List of special page names to the subclass of SpecialPage which handles them.
	 * @todo Make this a const when we drop HHVM support (T192166).  It can still be private in PHP
	 * 7.1.
	 */
	private static $coreList = [
		// Maintenance Reports
		'BrokenRedirects' => \BrokenRedirectsPage::class,
		'Deadendpages' => \DeadendPagesPage::class,
		'DoubleRedirects' => \DoubleRedirectsPage::class,
		'Longpages' => \LongPagesPage::class,
		'Ancientpages' => \AncientPagesPage::class,
		'Lonelypages' => \LonelyPagesPage::class,
		'Fewestrevisions' => \FewestrevisionsPage::class,
		'Withoutinterwiki' => \WithoutInterwikiPage::class,
		'Protectedpages' => \SpecialProtectedpages::class,
		'Protectedtitles' => \SpecialProtectedtitles::class,
		'Shortpages' => \ShortPagesPage::class,
		'Uncategorizedcategories' => \UncategorizedCategoriesPage::class,
		'Uncategorizedimages' => \UncategorizedImagesPage::class,
		'Uncategorizedpages' => \UncategorizedPagesPage::class,
		'Uncategorizedtemplates' => \UncategorizedTemplatesPage::class,
		'Unusedcategories' => \UnusedCategoriesPage::class,
		'Unusedimages' => \UnusedimagesPage::class,
		'Unusedtemplates' => \UnusedtemplatesPage::class,
		'Unwatchedpages' => \UnwatchedpagesPage::class,
		'Wantedcategories' => \WantedCategoriesPage::class,
		'Wantedfiles' => \WantedFilesPage::class,
		'Wantedpages' => \WantedPagesPage::class,
		'Wantedtemplates' => \WantedTemplatesPage::class,

		// List of pages
		'Allpages' => \SpecialAllPages::class,
		'Prefixindex' => \SpecialPrefixindex::class,
		'Categories' => \SpecialCategories::class,
		'Listredirects' => \ListredirectsPage::class,
		'PagesWithProp' => \SpecialPagesWithProp::class,
		'TrackingCategories' => \SpecialTrackingCategories::class,

		// Authentication
		'Userlogin' => \SpecialUserLogin::class,
		'Userlogout' => \SpecialUserLogout::class,
		'CreateAccount' => \SpecialCreateAccount::class,
		'LinkAccounts' => \SpecialLinkAccounts::class,
		'UnlinkAccounts' => \SpecialUnlinkAccounts::class,
		'ChangeCredentials' => \SpecialChangeCredentials::class,
		'RemoveCredentials' => \SpecialRemoveCredentials::class,

		// Users and rights
		'Activeusers' => \SpecialActiveUsers::class,
		'Block' => \SpecialBlock::class,
		'Unblock' => \SpecialUnblock::class,
		'BlockList' => \SpecialBlockList::class,
		'AutoblockList' => \SpecialAutoblockList::class,
		'ChangePassword' => \SpecialChangePassword::class,
		'BotPasswords' => \SpecialBotPasswords::class,
		'PasswordReset' => \SpecialPasswordReset::class,
		'DeletedContributions' => \DeletedContributionsPage::class,
		'Preferences' => \SpecialPreferences::class,
		'ResetTokens' => \SpecialResetTokens::class,
		'Contributions' => \SpecialContributions::class,
		'Listgrouprights' => \SpecialListGroupRights::class,
		'Listgrants' => \SpecialListGrants::class,
		'Listusers' => \SpecialListUsers::class,
		'Listadmins' => \SpecialListAdmins::class,
		'Listbots' => \SpecialListBots::class,
		'Userrights' => \UserrightsPage::class,
		'EditWatchlist' => \SpecialEditWatchlist::class,
		'PasswordPolicies' => \SpecialPasswordPolicies::class,

		// Recent changes and logs
		'Newimages' => \SpecialNewFiles::class,
		'Log' => \SpecialLog::class,
		'Watchlist' => \SpecialWatchlist::class,
		'Newpages' => \SpecialNewpages::class,
		'Recentchanges' => \SpecialRecentChanges::class,
		'Recentchangeslinked' => \SpecialRecentChangesLinked::class,
		'Tags' => \SpecialTags::class,

		// Media reports and uploads
		'Listfiles' => \SpecialListFiles::class,
		'Filepath' => \SpecialFilepath::class,
		'MediaStatistics' => \MediaStatisticsPage::class,
		'MIMEsearch' => \MIMEsearchPage::class,
		'FileDuplicateSearch' => \FileDuplicateSearchPage::class,
		'Upload' => \SpecialUpload::class,
		'UploadStash' => \SpecialUploadStash::class,
		'ListDuplicatedFiles' => \ListDuplicatedFilesPage::class,

		// Data and tools
		'ApiSandbox' => \SpecialApiSandbox::class,
		'Statistics' => \SpecialStatistics::class,
		'Allmessages' => \SpecialAllMessages::class,
		'Version' => \SpecialVersion::class,
		'Lockdb' => \SpecialLockdb::class,
		'Unlockdb' => \SpecialUnlockdb::class,

		// Redirecting special pages
		'LinkSearch' => \LinkSearchPage::class,
		'Randompage' => \RandomPage::class,
		'RandomInCategory' => \SpecialRandomInCategory::class,
		'Randomredirect' => \SpecialRandomredirect::class,
		'Randomrootpage' => \SpecialRandomrootpage::class,
		'GoToInterwiki' => \SpecialGoToInterwiki::class,

		// High use pages
		'Mostlinkedcategories' => \MostlinkedCategoriesPage::class,
		'Mostimages' => \MostimagesPage::class,
		'Mostinterwikis' => \MostinterwikisPage::class,
		'Mostlinked' => \MostlinkedPage::class,
		'Mostlinkedtemplates' => \MostlinkedTemplatesPage::class,
		'Mostcategories' => \MostcategoriesPage::class,
		'Mostrevisions' => \MostrevisionsPage::class,

		// Page tools
		'ComparePages' => \SpecialComparePages::class,
		'Export' => \SpecialExport::class,
		'Import' => \SpecialImport::class,
		'Undelete' => \SpecialUndelete::class,
		'Whatlinkshere' => \SpecialWhatLinksHere::class,
		'MergeHistory' => \SpecialMergeHistory::class,
		'ExpandTemplates' => \SpecialExpandTemplates::class,

		// Other
		'Booksources' => \SpecialBookSources::class,

		// Unlisted / redirects
		'ApiHelp' => \SpecialApiHelp::class,
		'Blankpage' => \SpecialBlankpage::class,
		'Diff' => \SpecialDiff::class,
		'EditTags' => \SpecialEditTags::class,
		'Emailuser' => \SpecialEmailUser::class,
		'Movepage' => \MovePageForm::class,
		'Mycontributions' => \SpecialMycontributions::class,
		'MyLanguage' => \SpecialMyLanguage::class,
		'Mypage' => \SpecialMypage::class,
		'Mytalk' => \SpecialMytalk::class,
		'Myuploads' => \SpecialMyuploads::class,
		'AllMyUploads' => \SpecialAllMyUploads::class,
		'PermanentLink' => \SpecialPermanentLink::class,
		'Redirect' => \SpecialRedirect::class,
		'Revisiondelete' => \SpecialRevisionDelete::class,
		'RunJobs' => \SpecialRunJobs::class,
		'Specialpages' => \SpecialSpecialpages::class,
		'PageData' => \SpecialPageData::class,
	];

	/** @var array Special page name => class name */
	private $list;

	/** @var array */
	private $aliases;

	/** @var Config */
	private $config;

	/** @var Language */
	private $contLang;

	/**
	 * @param Config $config
	 * @param Language $contLang
	 */
	public function __construct( Config $config, Language $contLang ) {
		$this->config = $config;
		$this->contLang = $contLang;
	}

	/**
	 * Returns a list of canonical special page names.
	 * May be used to iterate over all registered special pages.
	 *
	 * @return string[]
	 */
	public function getNames() : array {
		return array_keys( $this->getPageList() );
	}

	/**
	 * Get the special page list as an array
	 *
	 * @return array
	 */
	private function getPageList() : array {
		if ( !is_array( $this->list ) ) {
			$this->list = self::$coreList;

			if ( !$this->config->get( 'DisableInternalSearch' ) ) {
				$this->list['Search'] = \SpecialSearch::class;
			}

			if ( $this->config->get( 'EmailAuthentication' ) ) {
				$this->list['Confirmemail'] = \EmailConfirmation::class;
				$this->list['Invalidateemail'] = \EmailInvalidation::class;
			}

			if ( $this->config->get( 'EnableEmail' ) ) {
				$this->list['ChangeEmail'] = \SpecialChangeEmail::class;
			}

			if ( $this->config->get( 'EnableJavaScriptTest' ) ) {
				$this->list['JavaScriptTest'] = \SpecialJavaScriptTest::class;
			}

			if ( $this->config->get( 'PageLanguageUseDB' ) ) {
				$this->list['PageLanguage'] = \SpecialPageLanguage::class;
			}
			if ( $this->config->get( 'ContentHandlerUseDB' ) ) {
				$this->list['ChangeContentModel'] = \SpecialChangeContentModel::class;
			}

			// Add extension special pages
			$this->list = array_merge( $this->list, $this->config->get( 'SpecialPages' ) );

			// This hook can be used to disable unwanted core special pages
			// or conditionally register special pages.
			Hooks::run( 'SpecialPage_initList', [ &$this->list ] );

		}

		return $this->list;
	}

	/**
	 * Initialise and return the list of special page aliases. Returns an array where
	 * the key is an alias, and the value is the canonical name of the special page.
	 * All registered special pages are guaranteed to map to themselves.
	 * @return array
	 */
	private function getAliasList() : array {
		if ( is_null( $this->aliases ) ) {
			$aliases = $this->contLang->getSpecialPageAliases();
			$pageList = $this->getPageList();

			$this->aliases = [];
			$keepAlias = [];

			// Force every canonical name to be an alias for itself.
			foreach ( $pageList as $name => $stuff ) {
				$caseFoldedAlias = $this->contLang->caseFold( $name );
				$this->aliases[$caseFoldedAlias] = $name;
				$keepAlias[$caseFoldedAlias] = 'canonical';
			}

			// Check for $aliases being an array since Language::getSpecialPageAliases can return null
			if ( is_array( $aliases ) ) {
				foreach ( $aliases as $realName => $aliasList ) {
					$aliasList = array_values( $aliasList );
					foreach ( $aliasList as $i => $alias ) {
						$caseFoldedAlias = $this->contLang->caseFold( $alias );

						if ( isset( $this->aliases[$caseFoldedAlias] ) &&
							$realName === $this->aliases[$caseFoldedAlias]
						) {
							// Ignore same-realName conflicts
							continue;
						}

						if ( !isset( $keepAlias[$caseFoldedAlias] ) ) {
							$this->aliases[$caseFoldedAlias] = $realName;
							if ( !$i ) {
								$keepAlias[$caseFoldedAlias] = 'first';
							}
						} elseif ( !$i ) {
							wfWarn( "First alias '$alias' for $realName conflicts with " .
								"{$keepAlias[$caseFoldedAlias]} alias for " .
								$this->aliases[$caseFoldedAlias]
							);
						}
					}
				}
			}
		}

		return $this->aliases;
	}

	/**
	 * Given a special page name with a possible subpage, return an array
	 * where the first element is the special page name and the second is the
	 * subpage.
	 *
	 * @param string $alias
	 * @return array Array( String, String|null ), or array( null, null ) if the page is invalid
	 */
	public function resolveAlias( $alias ) {
		$bits = explode( '/', $alias, 2 );

		$caseFoldedAlias = $this->contLang->caseFold( $bits[0] );
		$caseFoldedAlias = str_replace( ' ', '_', $caseFoldedAlias );
		$aliases = $this->getAliasList();
		if ( !isset( $aliases[$caseFoldedAlias] ) ) {
			return [ null, null ];
		}
		$name = $aliases[$caseFoldedAlias];
		$par = $bits[1] ?? null; // T4087

		return [ $name, $par ];
	}

	/**
	 * Check if a given name exist as a special page or as a special page alias
	 *
	 * @param string $name Name of a special page
	 * @return bool True if a special page exists with this name
	 */
	public function exists( $name ) {
		list( $title, /*...*/ ) = $this->resolveAlias( $name );

		$specialPageList = $this->getPageList();
		return isset( $specialPageList[$title] );
	}

	/**
	 * Find the object with a given name and return it (or NULL)
	 *
	 * @param string $name Special page name, may be localised and/or an alias
	 * @return SpecialPage|null SpecialPage object or null if the page doesn't exist
	 */
	public function getPage( $name ) {
		list( $realName, /*...*/ ) = $this->resolveAlias( $name );

		$specialPageList = $this->getPageList();

		if ( isset( $specialPageList[$realName] ) ) {
			$rec = $specialPageList[$realName];

			if ( is_callable( $rec ) ) {
				// Use callback to instantiate the special page
				$page = $rec();
			} elseif ( is_string( $rec ) ) {
				$className = $rec;
				$page = new $className;
			} elseif ( $rec instanceof SpecialPage ) {
				$page = $rec; // XXX: we should deep clone here
			} else {
				$page = null;
			}

			if ( $page instanceof SpecialPage ) {
				return $page;
			}

			// It's not a classname, nor a callback, nor a legacy constructor array,
			// nor a special page object. Give up.
			wfLogWarning( "Cannot instantiate special page $realName: bad spec!" );
		}

		return null;
	}

	/**
	 * Return categorised listable special pages which are available
	 * for the current user, and everyone.
	 *
	 * @param User $user User object to check permissions
	 *  provided
	 * @return array ( string => Specialpage )
	 */
	public function getUsablePages( User $user ) : array {
		$pages = [];
		foreach ( $this->getPageList() as $name => $rec ) {
			$page = $this->getPage( $name );
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
	public function getRegularPages() : array {
		$pages = [];
		foreach ( $this->getPageList() as $name => $rec ) {
			$page = $this->getPage( $name );
			if ( $page && $page->isListed() && !$page->isRestricted() ) {
				$pages[$name] = $page;
			}
		}

		return $pages;
	}

	/**
	 * Return categorised listable special pages which are available
	 * for the current user, but not for everyone
	 *
	 * @param User $user User object to use
	 * @return array ( string => Specialpage )
	 */
	public function getRestrictedPages( User $user ) : array {
		$pages = [];
		foreach ( $this->getPageList() as $name => $rec ) {
			$page = $this->getPage( $name );
			if ( $page
				&& $page->isListed()
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
	 * @param Title &$title
	 * @param IContextSource &$context
	 * @param bool $including Bool output is being captured for use in {{special:whatever}}
	 * @param LinkRenderer|null $linkRenderer (since 1.28)
	 *
	 * @return bool|Title
	 */
	public function executePath( Title &$title, IContextSource &$context, $including = false,
		LinkRenderer $linkRenderer = null
	) {
		// @todo FIXME: Redirects broken due to this call
		$bits = explode( '/', $title->getDBkey(), 2 );
		$name = $bits[0];
		$par = $bits[1] ?? null; // T4087

		$page = $this->getPage( $name );
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
			}

			$context->setTitle( $page->getPageTitle( $par ) );
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
	public function capturePath(
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
		if ( $main->canUseWikiPage() ) {
			$ctx['wikipage'] = $main->getWikiPage();
		}

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
		$ret = $this->executePath( $title, $context, true, $linkRenderer );

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
		if ( isset( $ctx['wikipage'] ) ) {
			$main->setWikiPage( $ctx['wikipage'] );
		}

		return $ret;
	}

	/**
	 * Get the local name for a specified canonical name
	 *
	 * @param string $name
	 * @param string|bool $subpage
	 * @return string
	 */
	public function getLocalNameFor( $name, $subpage = false ) {
		$aliases = $this->contLang->getSpecialPageAliases();
		$aliasList = $this->getAliasList();

		// Find the first alias that maps back to $name
		if ( isset( $aliases[$name] ) ) {
			$found = false;
			foreach ( $aliases[$name] as $alias ) {
				$caseFoldedAlias = $this->contLang->caseFold( $alias );
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
						return $this->getLocalNameFor( $n, $subpage );
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

		return $this->contLang->ucfirst( $name );
	}

	/**
	 * Get a title for a given alias
	 *
	 * @param string $alias
	 * @return Title|null Title or null if there is no such alias
	 */
	public function getTitleForAlias( $alias ) {
		list( $name, $subpage ) = $this->resolveAlias( $alias );
		if ( $name != null ) {
			return SpecialPage::getTitleFor( $name, $subpage );
		}

		return null;
	}
}
