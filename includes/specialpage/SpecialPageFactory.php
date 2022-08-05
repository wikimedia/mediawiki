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

namespace MediaWiki\SpecialPage;

use IContextSource;
use Language;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use Profiler;
use RequestContext;
use SpecialPage;
use Title;
use TitleFactory;
use User;
use Wikimedia\ObjectFactory\ObjectFactory;

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
	private const CORE_LIST = [
		// Maintenance Reports
		'BrokenRedirects' => [
			'class' => \SpecialBrokenRedirects::class,
			'services' => [
				'ContentHandlerFactory',
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'Deadendpages' => [
			'class' => \SpecialDeadendPages::class,
			'services' => [
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
			]
		],
		'DoubleRedirects' => [
			'class' => \SpecialDoubleRedirects::class,
			'services' => [
				'ContentHandlerFactory',
				'LinkBatchFactory',
				'DBLoadBalancer',
			]
		],
		'Longpages' => [
			'class' => \SpecialLongPages::class,
			'services' => [
				// Same as for Shortpages
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'Ancientpages' => [
			'class' => \SpecialAncientPages::class,
			'services' => [
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
			]
		],
		'Lonelypages' => [
			'class' => \SpecialLonelyPages::class,
			'services' => [
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
				'LinksMigration',
			]
		],
		'Fewestrevisions' => [
			'class' => \SpecialFewestRevisions::class,
			'services' => [
				// Same as for Mostrevisions
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
			]
		],
		'Withoutinterwiki' => [
			'class' => \SpecialWithoutInterwiki::class,
			'services' => [
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
			]
		],
		'Protectedpages' => [
			'class' => \SpecialProtectedpages::class,
			'services' => [
				'LinkBatchFactory',
				'DBLoadBalancer',
				'CommentStore',
				'UserCache',
				'RowCommentFormatter',
				'RestrictionStore',
			]
		],
		'Protectedtitles' => [
			'class' => \SpecialProtectedtitles::class,
			'services' => [
				'LinkBatchFactory',
				'DBLoadBalancer',
			]
		],
		'Shortpages' => [
			'class' => \SpecialShortPages::class,
			'services' => [
				// Same as for Longpages
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'Uncategorizedcategories' => [
			'class' => \SpecialUncategorizedCategories::class,
			'services' => [
				// Same as for SpecialUncategorizedPages and SpecialUncategorizedTemplates
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
			]
		],
		'Uncategorizedimages' => [
			'class' => \SpecialUncategorizedImages::class,
			'services' => [
				'DBLoadBalancer',
			]
		],
		'Uncategorizedpages' => [
			'class' => \SpecialUncategorizedPages::class,
			'services' => [
				// Same as for SpecialUncategorizedCategories and SpecialUncategorizedTemplates
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
			]
		],
		'Uncategorizedtemplates' => [
			'class' => \SpecialUncategorizedTemplates::class,
			'services' => [
				// Same as for SpecialUncategorizedCategories and SpecialUncategorizedPages
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
			]
		],
		'Unusedcategories' => [
			'class' => \SpecialUnusedCategories::class,
			'services' => [
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'Unusedimages' => [
			'class' => \SpecialUnusedImages::class,
			'services' => [
				'DBLoadBalancer',
			]
		],
		'Unusedtemplates' => [
			'class' => \SpecialUnusedTemplates::class,
			'services' => [
				'DBLoadBalancer',
				'LinksMigration',
			]
		],
		'Unwatchedpages' => [
			'class' => \SpecialUnwatchedPages::class,
			'services' => [
				'LinkBatchFactory',
				'DBLoadBalancer',
				'LanguageConverterFactory',
			]
		],
		'Wantedcategories' => [
			'class' => \SpecialWantedCategories::class,
			'services' => [
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
			]
		],
		'Wantedfiles' => [
			'class' => \WantedFilesPage::class,
			'services' => [
				'RepoGroup',
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'Wantedpages' => [
			'class' => \WantedPagesPage::class,
			'services' => [
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'Wantedtemplates' => [
			'class' => \SpecialWantedTemplates::class,
			'services' => [
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LinksMigration',
			]
		],

		// List of pages
		'Allpages' => [
			'class' => \SpecialAllPages::class,
			'services' => [
				'DBLoadBalancer',
				'SearchEngineFactory',
			]
		],
		'Prefixindex' => [
			'class' => \SpecialPrefixindex::class,
			'services' => [
				'DBLoadBalancer',
				'LinkCache',
			]
		],
		'Categories' => [
			'class' => \SpecialCategories::class,
			'services' => [
				'LinkBatchFactory',
				'DBLoadBalancer',
			]
		],
		'Listredirects' => [
			'class' => \SpecialListRedirects::class,
			'services' => [
				'LinkBatchFactory',
				'DBLoadBalancer',
				'WikiPageFactory',
				'RedirectLookup'
			]
		],
		'PagesWithProp' => [
			'class' => \SpecialPagesWithProp::class,
			'services' => [
				'DBLoadBalancer',
			]
		],
		'TrackingCategories' => [
			'class' => \SpecialTrackingCategories::class,
			'services' => [
				'LinkBatchFactory',
				'TrackingCategories',
			]
		],

		// Authentication
		'Userlogin' => [
			'class' => \SpecialUserLogin::class,
			'services' => [
				'AuthManager',
			]
		],
		'Userlogout' => [
			'class' => \SpecialUserLogout::class,
		],
		'CreateAccount' => [
			'class' => \SpecialCreateAccount::class,
			'services' => [
				'AuthManager',
			]
		],
		'LinkAccounts' => [
			'class' => \SpecialLinkAccounts::class,
			'services' => [
				'AuthManager',
			]
		],
		'UnlinkAccounts' => [
			'class' => \SpecialUnlinkAccounts::class,
			'services' => [
				'AuthManager',
			]
		],
		'ChangeCredentials' => [
			'class' => \SpecialChangeCredentials::class,
			'services' => [
				'AuthManager',
			]
		],
		'RemoveCredentials' => [
			'class' => \SpecialRemoveCredentials::class,
			'services' => [
				'AuthManager',
			]
		],

		// Users and rights
		'Activeusers' => [
			'class' => \SpecialActiveUsers::class,
			'services' => [
				'LinkBatchFactory',
				'DBLoadBalancer',
				'UserGroupManager',
			]
		],
		'Block' => [
			'class' => \SpecialBlock::class,
			'services' => [
				'BlockUtils',
				'BlockPermissionCheckerFactory',
				'BlockUserFactory',
				'UserNameUtils',
				'UserNamePrefixSearch',
				'BlockActionInfo',
				'TitleFormatter',
				'NamespaceInfo'
			]
		],
		'Unblock' => [
			'class' => \SpecialUnblock::class,
			'services' => [
				'UnblockUserFactory',
				'BlockUtils',
				'UserNameUtils',
				'UserNamePrefixSearch',
			]
		],
		'BlockList' => [
			'class' => \SpecialBlockList::class,
			'services' => [
				'LinkBatchFactory',
				'BlockRestrictionStore',
				'DBLoadBalancer',
				'CommentStore',
				'BlockUtils',
				'BlockActionInfo',
				'RowCommentFormatter',
			],
		],
		'AutoblockList' => [
			'class' => \SpecialAutoblockList::class,
			'services' => [
				'LinkBatchFactory',
				'BlockRestrictionStore',
				'DBLoadBalancer',
				'CommentStore',
				'BlockUtils',
				'BlockActionInfo',
				'RowCommentFormatter',
			],
		],
		'ChangePassword' => [
			'class' => \SpecialChangePassword::class,
		],
		'BotPasswords' => [
			'class' => \SpecialBotPasswords::class,
			'services' => [
				'PasswordFactory',
				'AuthManager',
				'CentralIdLookup',
				'GrantsInfo',
				'GrantsLocalization',
			]
		],
		'PasswordReset' => [
			'class' => \SpecialPasswordReset::class,
			'services' => [
				'PasswordReset'
			]
		],
		'DeletedContributions' => [
			'class' => \SpecialDeletedContributions::class,
			'services' => [
				'PermissionManager',
				'DBLoadBalancer',
				'CommentStore',
				'RevisionFactory',
				'NamespaceInfo',
				'UserFactory',
				'UserNameUtils',
				'UserNamePrefixSearch',
			]
		],
		'Preferences' => [
			'class' => \SpecialPreferences::class,
			'services' => [
				'PreferencesFactory',
				'UserOptionsManager',
			]
		],
		'ResetTokens' => [
			'class' => \SpecialResetTokens::class,
		],
		'Contributions' => [
			'class' => \SpecialContributions::class,
			'services' => [
				'LinkBatchFactory',
				'PermissionManager',
				'DBLoadBalancer',
				'ActorMigration',
				'RevisionStore',
				'NamespaceInfo',
				'UserNameUtils',
				'UserNamePrefixSearch',
				'UserOptionsLookup',
				'CommentFormatter',
				'UserFactory',
			]
		],
		'Listgrouprights' => [
			'class' => \SpecialListGroupRights::class,
			'services' => [
				'NamespaceInfo',
				'UserGroupManager',
				'LanguageConverterFactory',
				'GroupPermissionsLookup',
			]
		],
		'Listgrants' => [
			'class' => \SpecialListGrants::class,
			'services' => [
				'GrantsLocalization',
			]
		],
		'Listusers' => [
			'class' => \SpecialListUsers::class,
			'services' => [
				'LinkBatchFactory',
				'DBLoadBalancer',
				'UserGroupManager',
			]
		],
		'Listadmins' => [
			'class' => \SpecialListAdmins::class,
		],
		'Listbots' => [
			'class' => \SpecialListBots::class,
		],
		'Userrights' => [
			'class' => \UserrightsPage::class,
			'services' => [
				'UserGroupManagerFactory',
				'UserNameUtils',
				'UserNamePrefixSearch',
				'UserFactory',
			]
		],
		'EditWatchlist' => [
			'class' => \SpecialEditWatchlist::class,
			'services' => [
				'WatchedItemStore',
				'TitleParser',
				'GenderCache',
				'LinkBatchFactory',
				'NamespaceInfo',
				'WikiPageFactory',
				'WatchlistManager',
			]
		],
		'PasswordPolicies' => [
			'class' => \SpecialPasswordPolicies::class,
			'services' => [
				'UserGroupManager',
			]
		],

		// Recent changes and logs
		'Newimages' => [
			'class' => \SpecialNewFiles::class,
			'services' => [
				'MimeAnalyzer',
				'GroupPermissionsLookup',
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'Log' => [
			'class' => \SpecialLog::class,
			'services' => [
				'LinkBatchFactory',
				'DBLoadBalancer',
				'ActorNormalization',
				'UserIdentityLookup',
			]
		],
		'Watchlist' => [
			'class' => \SpecialWatchlist::class,
			'services' => [
				'WatchedItemStore',
				'WatchlistManager',
				'DBLoadBalancer',
				'UserOptionsLookup',
			]
		],
		'Newpages' => [
			'class' => \SpecialNewpages::class,
			'services' => [
				'LinkBatchFactory',
				'CommentStore',
				'ContentHandlerFactory',
				'GroupPermissionsLookup',
				'DBLoadBalancer',
				'RevisionLookup',
				'NamespaceInfo',
				'UserOptionsLookup',
			]
		],
		'Recentchanges' => [
			'class' => \SpecialRecentChanges::class,
			'services' => [
				'WatchedItemStore',
				'MessageCache',
				'DBLoadBalancer',
				'UserOptionsLookup',
			]
		],
		'Recentchangeslinked' => [
			'class' => \SpecialRecentChangesLinked::class,
			'services' => [
				'WatchedItemStore',
				'MessageCache',
				'DBLoadBalancer',
				'UserOptionsLookup',
				'SearchEngineFactory',
			]
		],
		'Tags' => [
			'class' => \SpecialTags::class,
		],

		// Media reports and uploads
		'Listfiles' => [
			'class' => \SpecialListFiles::class,
			'services' => [
				'RepoGroup',
				'DBLoadBalancer',
				'CommentStore',
				'UserNameUtils',
				'UserNamePrefixSearch',
				'UserCache',
			]
		],
		'Filepath' => [
			'class' => \SpecialFilepath::class,
			'services' => [
				'SearchEngineFactory',
			]
		],
		'MediaStatistics' => [
			'class' => \SpecialMediaStatistics::class,
			'services' => [
				'MimeAnalyzer',
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'MIMEsearch' => [
			'class' => \SpecialMIMESearch::class,
			'services' => [
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
			]
		],
		'FileDuplicateSearch' => [
			'class' => \SpecialFileDuplicateSearch::class,
			'services' => [
				'LinkBatchFactory',
				'RepoGroup',
				'SearchEngineFactory',
				'LanguageConverterFactory',
			]
		],
		'Upload' => [
			'class' => \SpecialUpload::class,
			'services' => [
				'RepoGroup',
				'UserOptionsLookup',
				'NamespaceInfo',
			]
		],
		'UploadStash' => [
			'class' => \SpecialUploadStash::class,
			'services' => [
				'RepoGroup',
				'HttpRequestFactory',
			]
		],
		'ListDuplicatedFiles' => [
			'class' => \SpecialListDuplicatedFiles::class,
			'services' => [
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],

		// Data and tools
		'ApiSandbox' => [
			'class' => \SpecialApiSandbox::class,
		],
		'Statistics' => [
			'class' => \SpecialStatistics::class,
			'services' => [
				'UserGroupManager',
			]
		],
		'Allmessages' => [
			'class' => \SpecialAllMessages::class,
			'services' => [
				'LocalisationCache',
				'DBLoadBalancer',
			]
		],
		'Version' => [
			'class' => \SpecialVersion::class,
			'services' => [
				'Parser',
			]
		],
		'Lockdb' => [
			'class' => \SpecialLockdb::class,
		],
		'Unlockdb' => [
			'class' => \SpecialUnlockdb::class,
		],

		// Redirecting special pages
		'LinkSearch' => [
			'class' => \SpecialLinkSearch::class,
			'services' => [
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'Randompage' => [
			'class' => \SpecialRandomPage::class,
			'services' => [
				'DBLoadBalancer',
				'NamespaceInfo',
			]
		],
		'RandomInCategory' => [
			'class' => \SpecialRandomInCategory::class,
			'services' => [
				'DBLoadBalancer',
			]
		],
		'Randomredirect' => [
			'class' => \SpecialRandomRedirect::class,
			'services' => [
				'DBLoadBalancer',
				'NamespaceInfo',
			]
		],
		'Randomrootpage' => [
			'class' => \SpecialRandomRootPage::class,
			'services' => [
				'DBLoadBalancer',
				'NamespaceInfo',
			]
		],
		'GoToInterwiki' => [
			'class' => \SpecialGoToInterwiki::class,
		],

		// High use pages
		'Mostlinkedcategories' => [
			'class' => \SpecialMostLinkedCategories::class,
			'services' => [
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
			]
		],
		'Mostimages' => [
			'class' => \MostimagesPage::class,
			'services' => [
				'DBLoadBalancer',
				'LanguageConverterFactory',
			]
		],
		'Mostinterwikis' => [
			'class' => \SpecialMostInterwikis::class,
			'services' => [
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'Mostlinked' => [
			'class' => \SpecialMostLinked::class,
			'services' => [
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'Mostlinkedtemplates' => [
			'class' => \SpecialMostLinkedTemplates::class,
			'services' => [
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LinksMigration',
			]
		],
		'Mostcategories' => [
			'class' => \SpecialMostCategories::class,
			'services' => [
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
			]
		],
		'Mostrevisions' => [
			'class' => \SpecialMostRevisions::class,
			'services' => [
				// Same as for Fewestrevisions
				'NamespaceInfo',
				'DBLoadBalancer',
				'LinkBatchFactory',
				'LanguageConverterFactory',
			]
		],

		// Page tools
		'ComparePages' => [
			'class' => \SpecialComparePages::class,
			'services' => [
				'RevisionLookup',
				'ContentHandlerFactory',
			]
		],
		'Export' => [
			'class' => \SpecialExport::class,
			'services' => [
				'DBLoadBalancer',
				'WikiExporterFactory',
				'TitleFormatter',
				'LinksMigration',
			]
		],
		'Import' => [
			'class' => \SpecialImport::class,
			'services' => [
				'PermissionManager',
				'WikiImporterFactory',
			]
		],
		'Undelete' => [
			'class' => \SpecialUndelete::class,
			'services' => [
				'PermissionManager',
				'RevisionStore',
				'RevisionRenderer',
				'ContentHandlerFactory',
				'ChangeTagDefStore',
				'LinkBatchFactory',
				'RepoGroup',
				'DBLoadBalancer',
				'UserOptionsLookup',
				'WikiPageFactory',
				'SearchEngineFactory',
				'UndeletePageFactory',
				'ArchivedRevisionLookup',
			],
		],
		'Whatlinkshere' => [
			'class' => \SpecialWhatLinksHere::class,
			'services' => [
				'DBLoadBalancer',
				'LinkBatchFactory',
				'ContentHandlerFactory',
				'SearchEngineFactory',
				'NamespaceInfo',
				'TitleFactory',
				'LinksMigration',
			]
		],
		'MergeHistory' => [
			'class' => \SpecialMergeHistory::class,
			'services' => [
				'MergeHistoryFactory',
				'LinkBatchFactory',
				'DBLoadBalancer',
				'RevisionStore',
			]
		],
		'ExpandTemplates' => [
			'class' => \SpecialExpandTemplates::class,
			'services' => [
				'Parser',
				'UserOptionsLookup',
				'Tidy',
			],
		],
		'ChangeContentModel' => [
			'class' => \SpecialChangeContentModel::class,
			'services' => [
				'ContentHandlerFactory',
				'ContentModelChangeFactory',
				'SpamChecker',
				'RevisionLookup',
				'WikiPageFactory',
				'SearchEngineFactory',
			],
		],

		// Other
		'Booksources' => [
			'class' => \SpecialBookSources::class,
			'services' => [
				'RevisionLookup',
			]
		],

		// Unlisted / redirects
		'ApiHelp' => [
			'class' => \SpecialApiHelp::class,
		],
		'Blankpage' => [
			'class' => \SpecialBlankpage::class,
		],
		'DeletePage' => [
			'class' => \SpecialDeletePage::class,
			'services' => [
				'SearchEngineFactory',
			]
		],
		'Diff' => [
			'class' => \SpecialDiff::class,
		],
		'EditPage' => [
			'class' => \SpecialEditPage::class,
			'services' => [
				'SearchEngineFactory',
			]
		],
		'EditTags' => [
			'class' => \SpecialEditTags::class,
			'services' => [
				'PermissionManager',
			],
		],
		'Emailuser' => [
			'class' => \SpecialEmailUser::class,
			'services' => [
				'UserNameUtils',
				'UserNamePrefixSearch',
				'UserOptionsLookup',
			]
		],
		'Movepage' => [
			'class' => \MovePageForm::class,
			'services' => [
				'MovePageFactory',
				'PermissionManager',
				'UserOptionsLookup',
				'DBLoadBalancer',
				'ContentHandlerFactory',
				'NamespaceInfo',
				'LinkBatchFactory',
				'RepoGroup',
				'WikiPageFactory',
				'SearchEngineFactory',
				'WatchlistManager',
				'RestrictionStore',
			]
		],
		'Mycontributions' => [
			'class' => \SpecialMycontributions::class,
		],
		'MyLanguage' => [
			'class' => \SpecialMyLanguage::class,
			'services' => [
				'LanguageNameUtils',
				'RedirectLookup'
			]
		],
		'Mypage' => [
			'class' => \SpecialMypage::class,
		],
		'Mytalk' => [
			'class' => \SpecialMytalk::class,
		],
		'PageHistory' => [
			'class' => \SpecialPageHistory::class,
			'services' => [
				'SearchEngineFactory',
			]
		],
		'PageInfo' => [
			'class' => \SpecialPageInfo::class,
			'services' => [
				'SearchEngineFactory',
			]
		],
		'ProtectPage' => [
			'class' => \SpecialProtectPage::class,
			'services' => [
				'SearchEngineFactory',
			]
		],
		'Purge' => [
			'class' => \SpecialPurge::class,
			'services' => [
				'SearchEngineFactory',
			]
		],
		'Myuploads' => [
			'class' => \SpecialMyuploads::class,
		],
		'AllMyUploads' => [
			'class' => \SpecialAllMyUploads::class,
		],
		'NewSection' => [
			'class' => \SpecialNewSection::class,
			'services' => [
				'SearchEngineFactory',
			]
		],
		'PermanentLink' => [
			'class' => \SpecialPermanentLink::class,
		],
		'Redirect' => [
			'class' => \SpecialRedirect::class,
			'services' => [
				'RepoGroup',
				'UserFactory',
			]
		],
		'Revisiondelete' => [
			'class' => \SpecialRevisionDelete::class,
			'services' => [
				'PermissionManager',
				'RepoGroup',
			],
		],
		'RunJobs' => [
			'class' => \SpecialRunJobs::class,
			'services' => [
				'JobRunner',
				'ReadOnlyMode',
			]
		],
		'Specialpages' => [
			'class' => \SpecialSpecialpages::class,
		],
		'PageData' => [
			'class' => \SpecialPageData::class,
		],
	];

	/** @var array Special page name => class name */
	private $list;

	/** @var array */
	private $aliases;

	/** @var ServiceOptions */
	private $options;

	/** @var Language */
	private $contLang;

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var HookContainer */
	private $hookContainer;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::DisableInternalSearch,
		MainConfigNames::EmailAuthentication,
		MainConfigNames::EnableEmail,
		MainConfigNames::EnableJavaScriptTest,
		MainConfigNames::EnableSpecialMute,
		MainConfigNames::PageLanguageUseDB,
		MainConfigNames::SpecialPages,
	];

	/**
	 * @var TitleFactory
	 */
	private $titleFactory;

	/**
	 * @param ServiceOptions $options
	 * @param Language $contLang
	 * @param ObjectFactory $objectFactory
	 * @param TitleFactory $titleFactory
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		ServiceOptions $options,
		Language $contLang,
		ObjectFactory $objectFactory,
		TitleFactory $titleFactory,
		HookContainer $hookContainer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->contLang = $contLang;
		$this->objectFactory = $objectFactory;
		$this->titleFactory = $titleFactory;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Returns a list of canonical special page names.
	 * May be used to iterate over all registered special pages.
	 *
	 * @return string[]
	 */
	public function getNames(): array {
		return array_keys( $this->getPageList() );
	}

	/**
	 * Get the special page list as an array
	 *
	 * @return array
	 */
	private function getPageList(): array {
		if ( !is_array( $this->list ) ) {
			$this->list = self::CORE_LIST;

			if ( !$this->options->get( MainConfigNames::DisableInternalSearch ) ) {
				$this->list['Search'] = [
					'class' => \SpecialSearch::class,
					'services' => [
						'SearchEngineConfig',
						'SearchEngineFactory',
						'NamespaceInfo',
						'ContentHandlerFactory',
						'InterwikiLookup',
						'ReadOnlyMode',
						'UserOptionsManager',
						'LanguageConverterFactory'
					]
				];
			}

			if ( $this->options->get( MainConfigNames::EmailAuthentication ) ) {
				$this->list['Confirmemail'] = [
					'class' => \SpecialConfirmEmail::class,
					'services' => [
						'UserFactory',
					]
				];
				$this->list['Invalidateemail'] = [
					'class' => \SpecialEmailInvalidate::class,
					'services' => [
						'UserFactory',
					]
				];
			}

			if ( $this->options->get( MainConfigNames::EnableEmail ) ) {
				$this->list['ChangeEmail'] = [
					'class' => \SpecialChangeEmail::class,
					'services' => [
						'AuthManager',
					],
				];
			}

			if ( $this->options->get( MainConfigNames::EnableJavaScriptTest ) ) {
				$this->list['JavaScriptTest'] = [
					'class' => \SpecialJavaScriptTest::class
				];
			}

			if ( $this->options->get( MainConfigNames::EnableSpecialMute ) ) {
				$this->list['Mute'] = [
					'class' => \SpecialMute::class,
					'services' => [
						'CentralIdLookup',
						'UserOptionsManager',
						'UserIdentityLookup',
					]
				];
			}

			if ( $this->options->get( MainConfigNames::PageLanguageUseDB ) ) {
				$this->list['PageLanguage'] = [
					'class' => \SpecialPageLanguage::class,
					'services' => [
						'ContentHandlerFactory',
						'LanguageNameUtils',
						'DBLoadBalancer',
						'SearchEngineFactory',
					]
				];
			}

			// Add extension special pages
			$this->list = array_merge( $this->list,
				$this->options->get( MainConfigNames::SpecialPages ) );

			// This hook can be used to disable unwanted core special pages
			// or conditionally register special pages.
			$this->hookRunner->onSpecialPage_initList( $this->list );
		}

		return $this->list;
	}

	/**
	 * Initialise and return the list of special page aliases. Returns an array where
	 * the key is an alias, and the value is the canonical name of the special page.
	 * All registered special pages are guaranteed to map to themselves.
	 * @return array
	 */
	private function getAliasList(): array {
		if ( $this->aliases === null ) {
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
	 * @return array [ String, String|null ], or [ null, null ] if the page is invalid
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

			if ( is_array( $rec ) || is_string( $rec ) || is_callable( $rec ) ) {
				$page = $this->objectFactory->createObject(
					$rec,
					[
						'allowClassName' => true,
						'allowCallable' => true
					]
				);
			} else {
				$page = null;
			}

			if ( $page instanceof SpecialPage ) {
				$page->setHookContainer( $this->hookContainer );
				$page->setContentLanguage( $this->contLang );
				$page->setSpecialPageFactory( $this );
				return $page;
			}

			// It's not a classname, nor a callback, nor a legacy constructor array,
			// nor a special page object. Give up.
			wfLogWarning( "Cannot instantiate special page $realName: bad spec!" );
		}

		return null;
	}

	/**
	 * Get listed special pages available to the current user.
	 *
	 * This includes both unrestricted pages, and restricted pages
	 * that the current user has the required permissions for.
	 *
	 * @param User $user User object to check permissions provided
	 * @return SpecialPage[]
	 */
	public function getUsablePages( User $user ): array {
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
	 * Get listed special pages available to everyone by default.
	 *
	 * @return SpecialPage[]
	 */
	public function getRegularPages(): array {
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
	 * Execute a special page path.
	 * The path may contain parameters, e.g. Special:Name/Params
	 * Extracts the special page name and call the execute method, passing the parameters
	 *
	 * Returns a title object if the page is redirected, false if there was no such special
	 * page, and true if it was successful.
	 *
	 * @param PageReference|string $path
	 * @param IContextSource $context
	 * @param bool $including Bool output is being captured for use in {{special:whatever}}
	 * @param LinkRenderer|null $linkRenderer (since 1.28)
	 *
	 * @return bool|Title
	 */
	public function executePath( $path, IContextSource $context, $including = false,
		LinkRenderer $linkRenderer = null
	) {
		if ( $path instanceof PageReference ) {
			$path = $path->getDBkey();
		}

		$bits = explode( '/', $path, 2 );
		$name = $bits[0];
		$par = $bits[1] ?? null; // T4087

		$page = $this->getPage( $name );
		if ( !$page ) {
			$context->getOutput()->setArticleRelated( false );
			$context->getOutput()->setRobotPolicy( 'noindex,nofollow' );

			$send404Code = MediaWikiServices::getInstance()->getMainConfig()
				->get( MainConfigNames::Send404Code );
			if ( $send404Code ) {
				$context->getOutput()->setStatusCode( 404 );
			}

			$context->getOutput()->showErrorPage( 'nosuchspecialpage', 'nospecialpagetext' );

			return false;
		}

		if ( !$including ) {
			// Narrow DB query expectations for this HTTP request
			$trxLimits = $context->getConfig()->get( MainConfigNames::TrxProfilerLimits );
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
				$title = $page->getPageTitle( $par ?? false );
				$url = $title->getFullURL( $query );
				$context->getOutput()->redirect( $url );

				return $title;
			}

			// @phan-suppress-next-line PhanUndeclaredMethod
			$context->setTitle( $page->getPageTitle( $par ?? false ) );
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
	 * @param PageReference $page
	 * @param IContextSource $context
	 * @param LinkRenderer|null $linkRenderer (since 1.28)
	 * @return string HTML fragment
	 */
	public function capturePath(
		PageReference $page, IContextSource $context, LinkRenderer $linkRenderer = null
	) {
		// phpcs:ignore MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgUser
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

		// just needed for $wgTitle and RequestContext::setTitle
		$title = $this->titleFactory->castFromPageReference( $page );

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
		$ret = $this->executePath( $page, $context, true, $linkRenderer );

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

		if ( $subpage !== false && $subpage !== null ) {
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
