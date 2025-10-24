<?php

namespace MediaWiki\HookContainer;

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\JsonContent;
use MediaWiki\Context\IContextSource;
use MediaWiki\FileRepo\File\File;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Mail\MailAddress;
use MediaWiki\Mail\UserEmailContact;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\Article;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\RenameUser\RenameuserSQL;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Session\Session;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use SearchEngine;
use StatusValue;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * This class provides an implementation of the core hook interfaces,
 * forwarding hook calls to HookContainer for dispatch to extensions.
 * It is intended for use within MediaWiki core only. Extensions that
 * need a hook runner should create one for the hooks they need to run.
 *
 * To use it, create a new HookRunner object from a HookContainer obtained
 * by dependency injection, or as a last resort, from the global service
 * container. Then call the relevant method on the object:
 *   ( new HookRunner( $hookContainer ) )->onSomeHook( $param );
 *
 * @internal
 */
class HookRunner implements
	\MediaWiki\Actions\Hook\GetActionNameHook,
	\MediaWiki\Auth\Hook\AuthenticationAttemptThrottledHook,
	\MediaWiki\Auth\Hook\AuthManagerFilterProvidersHook,
	\MediaWiki\Auth\Hook\AuthManagerLoginAuthenticateAuditHook,
	\MediaWiki\Auth\Hook\AuthManagerVerifyAuthenticationHook,
	\MediaWiki\Auth\Hook\AuthPreserveQueryParamsHook,
	\MediaWiki\Auth\Hook\ExemptFromAccountCreationThrottleHook,
	\MediaWiki\Auth\Hook\LocalUserCreatedHook,
	\MediaWiki\Auth\Hook\ResetPasswordExpirationHook,
	\MediaWiki\Auth\Hook\SecuritySensitiveOperationStatusHook,
	\MediaWiki\Auth\Hook\UserLoggedInHook,
	\MediaWiki\Block\Hook\AbortAutoblockHook,
	\MediaWiki\Block\Hook\GetAllBlockActionsHook,
	\MediaWiki\Block\Hook\GetUserBlockHook,
	\MediaWiki\Block\Hook\PerformRetroactiveAutoblockHook,
	\MediaWiki\Block\Hook\SpreadAnyEditBlockHook,
	\MediaWiki\Cache\Hook\BacklinkCacheGetConditionsHook,
	\MediaWiki\Cache\Hook\BacklinkCacheGetPrefixHook,
	\MediaWiki\Cache\Hook\HtmlCacheUpdaterAppendUrlsHook,
	\MediaWiki\Cache\Hook\HtmlCacheUpdaterVaryUrlsHook,
	\MediaWiki\Cache\Hook\HTMLFileCache__useFileCacheHook,
	\MediaWiki\Cache\Hook\MessageCacheFetchOverridesHook,
	\MediaWiki\Cache\Hook\MessageCacheReplaceHook,
	\MediaWiki\Cache\Hook\MessageCache__getHook,
	\MediaWiki\Message\Hook\MessagePostProcessTextHook,
	\MediaWiki\Message\Hook\MessagePostProcessHtmlHook,
	\MediaWiki\Cache\Hook\MessagesPreLoadHook,
	\MediaWiki\Hook\TitleSquidURLsHook,
	\MediaWiki\ChangeTags\Hook\ChangeTagAfterDeleteHook,
	\MediaWiki\ChangeTags\Hook\ChangeTagCanCreateHook,
	\MediaWiki\ChangeTags\Hook\ChangeTagCanDeleteHook,
	\MediaWiki\ChangeTags\Hook\ChangeTagsAfterUpdateTagsHook,
	\MediaWiki\ChangeTags\Hook\ChangeTagsAllowedAddHook,
	\MediaWiki\ChangeTags\Hook\ChangeTagsListActiveHook,
	\MediaWiki\ChangeTags\Hook\ListDefinedTagsHook,
	\MediaWiki\Content\Hook\ContentAlterParserOutputHook,
	\MediaWiki\Content\Hook\ContentGetParserOutputHook,
	\MediaWiki\Content\Hook\ContentHandlerForModelIDHook,
	\MediaWiki\Content\Hook\ContentModelCanBeUsedOnHook,
	\MediaWiki\Content\Hook\ConvertContentHook,
	\MediaWiki\Content\Hook\GetContentModelsHook,
	\MediaWiki\Content\Hook\GetDifferenceEngineHook,
	\MediaWiki\Content\Hook\GetSlotDiffRendererHook,
	\MediaWiki\Content\Hook\JsonValidateSaveHook,
	\MediaWiki\Content\Hook\PageContentLanguageHook,
	\MediaWiki\Content\Hook\PlaceNewSectionHook,
	\MediaWiki\Content\Hook\SearchDataForIndexHook,
	\MediaWiki\Content\Hook\SearchDataForIndex2Hook,
	\MediaWiki\Specials\Contribute\Hook\ContributeCardsHook,
	\MediaWiki\Diff\Hook\AbortDiffCacheHook,
	\MediaWiki\Diff\Hook\ArticleContentOnDiffHook,
	\MediaWiki\Diff\Hook\DifferenceEngineAfterLoadNewTextHook,
	\MediaWiki\Diff\Hook\TextSlotDiffRendererTablePrefixHook,
	\MediaWiki\Diff\Hook\DifferenceEngineLoadTextAfterNewContentIsLoadedHook,
	\MediaWiki\Diff\Hook\DifferenceEngineMarkPatrolledLinkHook,
	\MediaWiki\Diff\Hook\DifferenceEngineMarkPatrolledRCIDHook,
	\MediaWiki\Diff\Hook\DifferenceEngineNewHeaderHook,
	\MediaWiki\Diff\Hook\DifferenceEngineOldHeaderHook,
	\MediaWiki\Diff\Hook\DifferenceEngineOldHeaderNoOldRevHook,
	\MediaWiki\Diff\Hook\DifferenceEngineRenderRevisionAddParserOutputHook,
	\MediaWiki\Diff\Hook\DifferenceEngineRenderRevisionShowFinalPatrolLinkHook,
	\MediaWiki\Diff\Hook\DifferenceEngineShowDiffHook,
	\MediaWiki\Diff\Hook\DifferenceEngineShowDiffPageHook,
	\MediaWiki\Diff\Hook\DifferenceEngineShowDiffPageMaybeShowMissingRevisionHook,
	\MediaWiki\Diff\Hook\DifferenceEngineShowEmptyOldContentHook,
	\MediaWiki\Diff\Hook\DifferenceEngineViewHeaderHook,
	\MediaWiki\Diff\Hook\DiffToolsHook,
	\MediaWiki\Diff\Hook\NewDifferenceEngineHook,
	\MediaWiki\Hook\AbortEmailNotificationHook,
	\MediaWiki\Hook\AbortTalkPageEmailNotificationHook,
	\MediaWiki\Hook\ActionBeforeFormDisplayHook,
	\MediaWiki\Hook\ActionModifyFormFieldsHook,
	\MediaWiki\Hook\AddNewAccountHook,
	\MediaWiki\Output\Hook\AfterBuildFeedLinksHook,
	\MediaWiki\Output\Hook\AfterFinalPageOutputHook,
	\MediaWiki\Hook\AfterImportPageHook,
	\MediaWiki\Hook\AfterParserFetchFileAndTitleHook,
	\MediaWiki\Hook\AlternateEditHook,
	\MediaWiki\Hook\AlternateEditPreviewHook,
	\MediaWiki\Hook\AlternateUserMailerHook,
	\MediaWiki\Hook\AncientPagesQueryHook,
	\MediaWiki\Hook\ApiBeforeMainHook,
	\MediaWiki\Hook\ArticleMergeCompleteHook,
	\MediaWiki\Hook\ArticleRevisionVisibilitySetHook,
	\MediaWiki\Hook\ArticleUpdateBeforeRedirectHook,
	\MediaWiki\Hook\BadImageHook,
	\MediaWiki\Hook\BeforeInitializeHook,
	\MediaWiki\Output\Hook\BeforePageDisplayHook,
	\MediaWiki\Output\Hook\BeforePageRedirectHook,
	\MediaWiki\Hook\BeforeParserFetchFileAndTitleHook,
	\MediaWiki\Hook\BeforeParserFetchTemplateRevisionRecordHook,
	\MediaWiki\Hook\BeforeWelcomeCreationHook,
	\MediaWiki\Hook\BitmapHandlerCheckImageAreaHook,
	\MediaWiki\Hook\BitmapHandlerTransformHook,
	\MediaWiki\Hook\BlockIpCompleteHook,
	\MediaWiki\Hook\BlockIpHook,
	\MediaWiki\Hook\BookInformationHook,
	\MediaWiki\Hook\CanonicalNamespacesHook,
	\MediaWiki\Hook\CategoryViewer__doCategoryQueryHook,
	\MediaWiki\Hook\CategoryViewer__generateLinkHook,
	\MediaWiki\Hook\ChangesListInitRowsHook,
	\MediaWiki\Hook\ChangesListInsertArticleLinkHook,
	\MediaWiki\Hook\ChangesListInsertLogEntryHook,
	\MediaWiki\Hook\ChangeUserGroupsHook,
	\MediaWiki\Hook\Collation__factoryHook,
	\MediaWiki\Hook\ContentSecurityPolicyDefaultSourceHook,
	\MediaWiki\Hook\ContentSecurityPolicyDirectivesHook,
	\MediaWiki\Hook\ContentSecurityPolicyScriptSourceHook,
	\MediaWiki\Hook\ContribsPager__getQueryInfoHook,
	\MediaWiki\Hook\ContribsPager__reallyDoQueryHook,
	\MediaWiki\Hook\ContributionsLineEndingHook,
	\MediaWiki\Hook\ContributionsToolLinksHook,
	\MediaWiki\Hook\CustomEditorHook,
	\MediaWiki\Hook\DeletedContribsPager__reallyDoQueryHook,
	\MediaWiki\Hook\DeletedContributionsLineEndingHook,
	\MediaWiki\Hook\DeleteUnknownPreferencesHook,
	\MediaWiki\Hook\EditFilterHook,
	\MediaWiki\Hook\EditFilterMergedContentHook,
	\MediaWiki\Hook\EditFormInitialTextHook,
	\MediaWiki\Hook\EditFormPreloadTextHook,
	\MediaWiki\Hook\EditPageBeforeConflictDiffHook,
	\MediaWiki\Hook\EditPageBeforeEditButtonsHook,
	\MediaWiki\Hook\EditPageBeforeEditToolbarHook,
	\MediaWiki\Hook\EditPageCopyrightWarningHook,
	\MediaWiki\Hook\EditPageGetCheckboxesDefinitionHook,
	\MediaWiki\Hook\EditPageGetDiffContentHook,
	\MediaWiki\Hook\EditPageGetPreviewContentHook,
	\MediaWiki\Hook\EditPageNoSuchSectionHook,
	\MediaWiki\Hook\EditPageTosSummaryHook,
	\MediaWiki\Hook\EditPage__attemptSaveHook,
	\MediaWiki\Hook\EditPage__attemptSave_afterHook,
	\MediaWiki\Hook\EditPage__importFormDataHook,
	\MediaWiki\Hook\EditPage__showEditForm_fieldsHook,
	\MediaWiki\Hook\EditPage__showEditForm_initialHook,
	\MediaWiki\Hook\EditPage__showReadOnlyForm_initialHook,
	\MediaWiki\Hook\EditPage__showStandardInputs_optionsHook,
	\MediaWiki\Hook\EmailUserCCHook,
	\MediaWiki\Hook\EmailUserCompleteHook,
	\MediaWiki\Hook\EmailUserFormHook,
	\MediaWiki\Hook\EmailUserHook,
	\MediaWiki\Hook\EmailUserPermissionsErrorsHook,
	\MediaWiki\Mail\Hook\EmailUserAuthorizeSendHook,
	\MediaWiki\Mail\Hook\EmailUserSendEmailHook,
	\MediaWiki\Hook\EnhancedChangesListModifyBlockLineDataHook,
	\MediaWiki\Hook\EnhancedChangesListModifyLineDataHook,
	\MediaWiki\Hook\EnhancedChangesList__getLogTextHook,
	\MediaWiki\Hook\ExtensionTypesHook,
	\MediaWiki\Hook\FetchChangesListHook,
	\MediaWiki\Hook\FileDeleteCompleteHook,
	\MediaWiki\Hook\FileTransformedHook,
	\MediaWiki\Hook\FileUndeleteCompleteHook,
	\MediaWiki\Hook\FileUploadHook,
	\MediaWiki\Hook\FormatAutocommentsHook,
	\MediaWiki\Hook\GalleryGetModesHook,
	\MediaWiki\Hook\GetBlockErrorMessageKeyHook,
	\MediaWiki\Output\Hook\GetCacheVaryCookiesHook,
	\MediaWiki\Hook\GetCanonicalURLHook,
	\MediaWiki\Hook\GetDefaultSortkeyHook,
	\MediaWiki\Hook\GetDoubleUnderscoreIDsHook,
	\MediaWiki\Hook\GetExtendedMetadataHook,
	\MediaWiki\Hook\GetFullURLHook,
	\MediaWiki\Language\Hook\GetHumanTimestampHook,
	\MediaWiki\Hook\GetInternalURLHook,
	\MediaWiki\Hook\GetIPHook,
	\MediaWiki\Language\Hook\GetLangPreferredVariantHook,
	\MediaWiki\Hook\GetLinkColoursHook,
	\MediaWiki\Hook\GetLocalURLHook,
	\MediaWiki\Hook\GetLocalURL__ArticleHook,
	\MediaWiki\Hook\GetLocalURL__InternalHook,
	\MediaWiki\Hook\GetLogTypesOnUserHook,
	\MediaWiki\Hook\GetMagicVariableIDsHook,
	\MediaWiki\Hook\GetMetadataVersionHook,
	\MediaWiki\Hook\GetNewMessagesAlertHook,
	\MediaWiki\Hook\GetSecurityLogContextHook,
	\MediaWiki\Hook\GetSessionJwtDataHook,
	\MediaWiki\Hook\GetRelativeTimestampHook,
	\MediaWiki\Hook\GitViewersHook,
	\MediaWiki\Hook\HistoryPageToolLinksHook,
	\MediaWiki\Hook\HistoryToolsHook,
	\MediaWiki\Hook\ImageBeforeProduceHTMLHook,
	\MediaWiki\Hook\ImgAuthBeforeStreamHook,
	\MediaWiki\Hook\ImgAuthModifyHeadersHook,
	\MediaWiki\Hook\ImportHandleContentXMLTagHook,
	\MediaWiki\Hook\ImportHandleLogItemXMLTagHook,
	\MediaWiki\Hook\ImportHandlePageXMLTagHook,
	\MediaWiki\Hook\ImportHandleRevisionXMLTagHook,
	\MediaWiki\Hook\ImportHandleToplevelXMLTagHook,
	\MediaWiki\Hook\ImportHandleUnknownUserHook,
	\MediaWiki\Hook\ImportHandleUploadXMLTagHook,
	\MediaWiki\Hook\ImportLogInterwikiLinkHook,
	\MediaWiki\Hook\ImportSourcesHook,
	\MediaWiki\Hook\InfoActionHook,
	\MediaWiki\Hook\InitializeArticleMaybeRedirectHook,
	\MediaWiki\Hook\InternalParseBeforeLinksHook,
	\MediaWiki\Hook\IRCLineURLHook,
	\MediaWiki\Hook\IsTrustedProxyHook,
	\MediaWiki\Hook\IsUploadAllowedFromUrlHook,
	\MediaWiki\Hook\IsValidEmailAddrHook,
	\MediaWiki\Language\Hook\LanguageGetNamespacesHook,
	\MediaWiki\Output\Hook\LanguageLinksHook,
	\MediaWiki\Hook\LanguageSelectorHook,
	\MediaWiki\Hook\LinkerMakeExternalImageHook,
	\MediaWiki\Hook\LinkerMakeExternalLinkHook,
	\MediaWiki\Hook\LinkerMakeMediaLinkFileHook,
	\MediaWiki\Hook\LinksUpdateCompleteHook,
	\MediaWiki\Hook\LinksUpdateHook,
	\MediaWiki\Hook\LocalFilePurgeThumbnailsHook,
	\MediaWiki\Hook\LocalFile__getHistoryHook,
	\MediaWiki\Language\Hook\LocalisationCacheRecacheFallbackHook,
	\MediaWiki\Language\Hook\LocalisationCacheRecacheHook,
	\MediaWiki\Hook\LogEventsListGetExtraInputsHook,
	\MediaWiki\Hook\LogEventsListLineEndingHook,
	\MediaWiki\Hook\LogEventsListShowLogExtractHook,
	\MediaWiki\Hook\LogExceptionHook,
	\MediaWiki\Hook\LoginFormValidErrorMessagesHook,
	\MediaWiki\Hook\LogLineHook,
	\MediaWiki\Hook\LonelyPagesQueryHook,
	\MediaWiki\Hook\MagicWordwgVariableIDsHook,
	\MediaWiki\Hook\MaintenanceRefreshLinksInitHook,
	\MediaWiki\Hook\MaintenanceShellStartHook,
	\MediaWiki\Hook\MaintenanceUpdateAddParamsHook,
	\MediaWiki\Output\Hook\MakeGlobalVariablesScriptHook,
	\MediaWiki\Hook\ManualLogEntryBeforePublishHook,
	\MediaWiki\Hook\MarkPatrolledCompleteHook,
	\MediaWiki\Hook\MarkPatrolledHook,
	\MediaWiki\Hook\MediaWikiPerformActionHook,
	\MediaWiki\Hook\MediaWikiServicesHook,
	\MediaWiki\Hook\MimeMagicGuessFromContentHook,
	\MediaWiki\Hook\MimeMagicImproveFromExtensionHook,
	\MediaWiki\Hook\MimeMagicInitHook,
	\MediaWiki\Hook\ModifyExportQueryHook,
	\MediaWiki\Hook\MovePageCheckPermissionsHook,
	\MediaWiki\Hook\MovePageIsValidMoveHook,
	\MediaWiki\Hook\NamespaceIsMovableHook,
	\MediaWiki\Hook\NewPagesLineEndingHook,
	\MediaWiki\Hook\OldChangesListRecentChangesLineHook,
	\MediaWiki\Hook\OpenSearchUrlsHook,
	\MediaWiki\Hook\OtherAutoblockLogLinkHook,
	\MediaWiki\Hook\OtherBlockLogLinkHook,
	\MediaWiki\Output\Hook\OutputPageAfterGetHeadLinksArrayHook,
	\MediaWiki\Output\Hook\OutputPageBeforeHTMLHook,
	\MediaWiki\Output\Hook\OutputPageBodyAttributesHook,
	\MediaWiki\Output\Hook\OutputPageCheckLastModifiedHook,
	\MediaWiki\Output\Hook\OutputPageParserOutputHook,
	\MediaWiki\Output\Hook\OutputPageRenderCategoryLinkHook,
	\MediaWiki\Hook\PageHistoryBeforeListHook,
	\MediaWiki\Hook\PageHistoryLineEndingHook,
	\MediaWiki\Hook\PageHistoryPager__doBatchLookupsHook,
	\MediaWiki\Hook\PageHistoryPager__getQueryInfoHook,
	\MediaWiki\Hook\PageMoveCompleteHook,
	\MediaWiki\Hook\PageMoveCompletingHook,
	\MediaWiki\Hook\PageRenderingHashHook,
	\MediaWiki\Hook\ParserAfterParseHook,
	\MediaWiki\Hook\ParserAfterTidyHook,
	\MediaWiki\Hook\ParserBeforeInternalParseHook,
	\MediaWiki\Hook\ParserBeforePreprocessHook,
	\MediaWiki\Hook\ParserCacheSaveCompleteHook,
	\MediaWiki\Hook\ParserClearStateHook,
	\MediaWiki\Hook\ParserClonedHook,
	\MediaWiki\Hook\ParserFetchTemplateDataHook,
	\MediaWiki\Hook\ParserFirstCallInitHook,
	\MediaWiki\Hook\ParserGetVariableValueSwitchHook,
	\MediaWiki\Hook\ParserGetVariableValueTsHook,
	\MediaWiki\Hook\ParserLimitReportFormatHook,
	\MediaWiki\Hook\ParserLimitReportPrepareHook,
	\MediaWiki\Hook\ParserLogLinterDataHook,
	\MediaWiki\Hook\ParserMakeImageParamsHook,
	\MediaWiki\Hook\ParserModifyImageHTMLHook,
	\MediaWiki\Hook\ParserOptionsRegisterHook,
	\MediaWiki\Hook\ParserOutputPostCacheTransformHook,
	\MediaWiki\Hook\ParserPreSaveTransformCompleteHook,
	\MediaWiki\Hook\ParserTestGlobalsHook,
	\MediaWiki\Hook\PasswordPoliciesForUserHook,
	\MediaWiki\Hook\PostLoginRedirectHook,
	\MediaWiki\Hook\PreferencesGetIconHook,
	\MediaWiki\Hook\PreferencesGetLayoutHook,
	\MediaWiki\Hook\PreferencesGetLegendHook,
	\MediaWiki\Hook\PrefsEmailAuditHook,
	\MediaWiki\Hook\UserCanChangeEmailHook,
	\MediaWiki\Hook\ProtectionForm__buildFormHook,
	\MediaWiki\Hook\ProtectionForm__saveHook,
	\MediaWiki\Hook\ProtectionForm__showLogExtractHook,
	\MediaWiki\Hook\ProtectionFormAddFormFieldsHook,
	\MediaWiki\Hook\RandomPageQueryHook,
	\MediaWiki\Hook\RawPageViewBeforeOutputHook,
	\MediaWiki\Hook\RecentChangesPurgeRowsHook,
	\MediaWiki\RecentChanges\Hook\RecentChangesPurgeQueryHook,
	\MediaWiki\Hook\RecentChange_saveHook,
	\MediaWiki\Hook\RejectParserCacheValueHook,
	\MediaWiki\Hook\RequestContextCreateSkinHook,
	\MediaWiki\Hook\SelfLinkBeginHook,
	\MediaWiki\Hook\SendWatchlistEmailNotificationHook,
	\MediaWiki\Hook\SetupAfterCacheHook,
	\MediaWiki\Hook\ShortPagesQueryHook,
	\MediaWiki\Hook\SidebarBeforeOutputHook,
	\MediaWiki\Hook\SiteNoticeAfterHook,
	\MediaWiki\Hook\SiteNoticeBeforeHook,
	\MediaWiki\Hook\SkinAddFooterLinksHook,
	\MediaWiki\Hook\SkinAfterBottomScriptsHook,
	\MediaWiki\Hook\SkinAfterContentHook,
	\MediaWiki\Hook\SkinBuildSidebarHook,
	\MediaWiki\Hook\SkinCopyrightFooterMessageHook,
	\MediaWiki\Hook\SkinEditSectionLinksHook,
	\MediaWiki\Hook\SkinPreloadExistenceHook,
	\MediaWiki\Hook\SkinSubPageSubtitleHook,
	\MediaWiki\Hook\SkinTemplateGetLanguageLinkHook,
	\MediaWiki\Hook\SkinTemplateNavigation__UniversalHook,
	\MediaWiki\Hook\SoftwareInfoHook,
	\MediaWiki\Hook\SpecialBlockModifyFormFieldsHook,
	\MediaWiki\Hook\SpecialContributionsBeforeMainOutputHook,
	\MediaWiki\Hook\SpecialContributions__formatRow__flagsHook,
	\MediaWiki\Hook\SpecialCreateAccountBenefitsHook,
	\MediaWiki\Hook\SpecialExportGetExtraPagesHook,
	\MediaWiki\Hook\SpecialContributions__getForm__filtersHook,
	\MediaWiki\Hook\SpecialListusersDefaultQueryHook,
	\MediaWiki\Hook\SpecialListusersFormatRowHook,
	\MediaWiki\Hook\SpecialListusersHeaderFormHook,
	\MediaWiki\Hook\SpecialListusersHeaderHook,
	\MediaWiki\Hook\SpecialListusersQueryInfoHook,
	\MediaWiki\Hook\SpecialLogAddLogSearchRelationsHook,
	\MediaWiki\Hook\SpecialLogResolveLogTypeHook,
	\MediaWiki\Hook\SpecialMovepageAfterMoveHook,
	\MediaWiki\Hook\SpecialMuteModifyFormFieldsHook,
	\MediaWiki\Hook\SpecialNewpagesConditionsHook,
	\MediaWiki\Hook\SpecialNewPagesFiltersHook,
	\MediaWiki\Hook\SpecialPrefixIndexGetFormFiltersHook,
	\MediaWiki\Hook\SpecialPrefixIndexQueryHook,
	\MediaWiki\Hook\SpecialRandomGetRandomTitleHook,
	\MediaWiki\Hook\SpecialRecentChangesPanelHook,
	\MediaWiki\Hook\SpecialResetTokensTokensHook,
	\MediaWiki\Hook\SpecialSearchCreateLinkHook,
	\MediaWiki\Hook\SpecialSearchGoResultHook,
	\MediaWiki\Hook\SpecialSearchNogomatchHook,
	\MediaWiki\Hook\SpecialSearchProfilesHook,
	\MediaWiki\Hook\SpecialSearchResultsAppendHook,
	\MediaWiki\Hook\SpecialSearchResultsHook,
	\MediaWiki\Hook\SpecialSearchResultsPrependHook,
	\MediaWiki\Hook\SpecialSearchSetupEngineHook,
	\MediaWiki\Hook\SpecialStatsAddExtraHook,
	\MediaWiki\Hook\SpecialTrackingCategories__generateCatLinkHook,
	\MediaWiki\Hook\SpecialTrackingCategories__preprocessHook,
	\MediaWiki\Hook\SpecialUploadCompleteHook,
	\MediaWiki\Hook\SpecialUserRightsChangeableGroupsHook,
	\MediaWiki\Hook\SpecialVersionVersionUrlHook,
	\MediaWiki\Hook\SpecialWhatLinksHereQueryHook,
	\MediaWiki\Hook\TestCanonicalRedirectHook,
	\MediaWiki\Hook\ThumbnailBeforeProduceHTMLHook,
	\MediaWiki\Hook\TempUserCreatedRedirectHook,
	\MediaWiki\Hook\TitleExistsHook,
	\MediaWiki\Hook\TitleGetEditNoticesHook,
	\MediaWiki\Hook\TitleGetRestrictionTypesHook,
	\MediaWiki\Hook\TitleIsAlwaysKnownHook,
	\MediaWiki\Hook\TitleIsMovableHook,
	\MediaWiki\Hook\TitleMoveHook,
	\MediaWiki\Hook\TitleMoveStartingHook,
	\MediaWiki\Hook\UnblockUserCompleteHook,
	\MediaWiki\Hook\UnblockUserHook,
	\MediaWiki\Hook\UndeleteForm__showHistoryHook,
	\MediaWiki\Hook\UndeleteForm__showRevisionHook,
	\MediaWiki\Hook\UndeletePageToolLinksHook,
	\MediaWiki\Hook\UnitTestsAfterDatabaseSetupHook,
	\MediaWiki\Hook\UnitTestsBeforeDatabaseTeardownHook,
	\MediaWiki\Hook\UnitTestsListHook,
	\MediaWiki\Hook\UnwatchArticleCompleteHook,
	\MediaWiki\Hook\UnwatchArticleHook,
	\MediaWiki\Hook\UpdateUserMailerFormattedPageStatusHook,
	\MediaWiki\Hook\UploadCompleteHook,
	\MediaWiki\Hook\UploadCreateFromRequestHook,
	\MediaWiki\Hook\UploadFormInitDescriptorHook,
	\MediaWiki\Hook\UploadFormSourceDescriptorsHook,
	\MediaWiki\Hook\UploadForm_BeforeProcessingHook,
	\MediaWiki\Hook\UploadForm_getInitialPageTextHook,
	\MediaWiki\Hook\UploadForm_initialHook,
	\MediaWiki\Hook\UploadStashFileHook,
	\MediaWiki\Hook\UploadVerifyFileHook,
	\MediaWiki\Hook\UploadVerifyUploadHook,
	\MediaWiki\Hook\UserEditCountUpdateHook,
	\MediaWiki\Hook\UserGetLanguageObjectHook,
	\MediaWiki\Hook\UserLoginCompleteHook,
	\MediaWiki\Hook\UserLogoutCompleteHook,
	\MediaWiki\Hook\UserMailerChangeReturnPathHook,
	\MediaWiki\Hook\UserMailerSplitToHook,
	\MediaWiki\Hook\UserMailerTransformContentHook,
	\MediaWiki\Hook\UserMailerTransformMessageHook,
	\MediaWiki\Hook\UsersPagerDoBatchLookupsHook,
	\MediaWiki\Hook\UserToolLinksEditHook,
	\MediaWiki\Hook\ValidateExtendedMetadataCacheHook,
	\MediaWiki\Hook\WantedPages__getQueryInfoHook,
	\MediaWiki\Hook\WatchArticleCompleteHook,
	\MediaWiki\Hook\WatchArticleHook,
	\MediaWiki\Hook\WatchedItemQueryServiceExtensionsHook,
	\MediaWiki\Hook\WatchlistEditorBeforeFormRenderHook,
	\MediaWiki\Hook\WatchlistEditorBuildRemoveLineHook,
	\MediaWiki\Hook\WebRequestPathInfoRouterHook,
	\MediaWiki\Hook\WebResponseSetCookieHook,
	\MediaWiki\Hook\WhatLinksHerePropsHook,
	\MediaWiki\Hook\WikiExporter__dumpStableQueryHook,
	\MediaWiki\Hook\XmlDumpWriterOpenPageHook,
	\MediaWiki\Hook\XmlDumpWriterWriteRevisionHook,
	\MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook,
	\MediaWiki\Interwiki\Hook\InterwikiLoadPrefixHook,
	\MediaWiki\Language\Hook\LanguageGetTranslatedLanguageNamesHook,
	\MediaWiki\Language\Hook\Language__getMessagesFileNameHook,
	\MediaWiki\Linker\Hook\LinkerGenerateRollbackLinkHook,
	\MediaWiki\Linker\Hook\HtmlPageLinkRendererBeginHook,
	\MediaWiki\Linker\Hook\HtmlPageLinkRendererEndHook,
	\MediaWiki\Page\Hook\ArticleConfirmDeleteHook,
	\MediaWiki\Page\Hook\ArticleDeleteAfterSuccessHook,
	\MediaWiki\Page\Hook\ArticleDeleteCompleteHook,
	\MediaWiki\Page\Hook\ArticleDeleteHook,
	\MediaWiki\Page\Hook\ArticleFromTitleHook,
	\MediaWiki\Page\Hook\ArticlePageDataAfterHook,
	\MediaWiki\Page\Hook\ArticlePageDataBeforeHook,
	\MediaWiki\Page\Hook\ArticleParserOptionsHook,
	\MediaWiki\Page\Hook\ArticleProtectCompleteHook,
	\MediaWiki\Page\Hook\ArticleProtectHook,
	\MediaWiki\Page\Hook\ArticlePurgeHook,
	\MediaWiki\Page\Hook\ArticleRevisionViewCustomHook,
	\MediaWiki\Page\Hook\ArticleShowPatrolFooterHook,
	\MediaWiki\Page\Hook\ArticleUndeleteHook,
	\MediaWiki\Page\Hook\ArticleViewFooterHook,
	\MediaWiki\Page\Hook\ArticleViewHeaderHook,
	\MediaWiki\Page\Hook\ArticleViewRedirectHook,
	\MediaWiki\Page\Hook\Article__MissingArticleConditionsHook,
	\MediaWiki\Page\Hook\BeforeDisplayNoArticleTextHook,
	\MediaWiki\Page\Hook\CategoryAfterPageAddedHook,
	\MediaWiki\Page\Hook\CategoryAfterPageRemovedHook,
	\MediaWiki\Page\Hook\CategoryPageViewHook,
	\MediaWiki\Page\Hook\DisplayOldSubtitleHook,
	\MediaWiki\Page\Hook\ImageOpenShowImageInlineBeforeHook,
	\MediaWiki\Page\Hook\ImagePageAfterImageLinksHook,
	\MediaWiki\Page\Hook\ImagePageFileHistoryLineHook,
	\MediaWiki\Page\Hook\ImagePageFindFileHook,
	\MediaWiki\Page\Hook\ImagePageShowTOCHook,
	\MediaWiki\Page\Hook\IsFileCacheableHook,
	\MediaWiki\Page\Hook\OpportunisticLinksUpdateHook,
	\MediaWiki\Page\Hook\PageDeleteCompleteHook,
	\MediaWiki\Page\Hook\PageDeleteHook,
	\MediaWiki\Page\Hook\PageDeletionDataUpdatesHook,
	\MediaWiki\Page\Hook\PageUndeleteCompleteHook,
	\MediaWiki\Page\Hook\PageUndeleteHook,
	\MediaWiki\Page\Hook\PageViewUpdatesHook,
	\MediaWiki\Page\Hook\RevisionFromEditCompleteHook,
	\MediaWiki\Page\Hook\RevisionUndeletedHook,
	\MediaWiki\Page\Hook\RollbackCompleteHook,
	\MediaWiki\Page\Hook\ShowMissingArticleHook,
	\MediaWiki\Page\Hook\WikiPageDeletionUpdatesHook,
	\MediaWiki\Page\Hook\WikiPageFactoryHook,
	\MediaWiki\Permissions\Hook\PermissionStatusAuditHook,
	\MediaWiki\Permissions\Hook\GetUserPermissionsErrorsExpensiveHook,
	\MediaWiki\Permissions\Hook\GetUserPermissionsErrorsHook,
	\MediaWiki\Permissions\Hook\TitleQuickPermissionsHook,
	\MediaWiki\Permissions\Hook\TitleReadWhitelistHook,
	\MediaWiki\Permissions\Hook\UserCanHook,
	\MediaWiki\Permissions\Hook\UserGetAllRightsHook,
	\MediaWiki\Permissions\Hook\UserGetRightsHook,
	\MediaWiki\Permissions\Hook\UserGetRightsRemoveHook,
	\MediaWiki\Permissions\Hook\UserIsBlockedFromHook,
	\MediaWiki\Permissions\Hook\UserIsEveryoneAllowedHook,
	\MediaWiki\Preferences\Hook\GetPreferencesHook,
	\MediaWiki\Preferences\Hook\PreferencesFormPreSaveHook,
	\MediaWiki\RenameUser\Hook\RenameUserAbortHook,
	\MediaWiki\RenameUser\Hook\RenameUserCompleteHook,
	\MediaWiki\RenameUser\Hook\RenameUserPreRenameHook,
	\MediaWiki\RenameUser\Hook\RenameUserSQLHook,
	\MediaWiki\RenameUser\Hook\RenameUserWarningHook,
	\MediaWiki\Rest\Hook\SearchResultProvideDescriptionHook,
	\MediaWiki\Revision\Hook\ContentHandlerDefaultModelForHook,
	\MediaWiki\Revision\Hook\RevisionRecordInsertedHook,
	\MediaWiki\Search\Hook\PrefixSearchBackendHook,
	\MediaWiki\Search\Hook\PrefixSearchExtractNamespaceHook,
	\MediaWiki\Search\Hook\SearchableNamespacesHook,
	\MediaWiki\Search\Hook\SearchAfterNoDirectMatchHook,
	\MediaWiki\Search\Hook\SearchGetNearMatchBeforeHook,
	\MediaWiki\Search\Hook\SearchGetNearMatchCompleteHook,
	\MediaWiki\Search\Hook\SearchGetNearMatchHook,
	\MediaWiki\Search\Hook\SearchIndexFieldsHook,
	\MediaWiki\Search\Hook\SearchResultInitFromTitleHook,
	\MediaWiki\Search\Hook\SearchResultProvideThumbnailHook,
	\MediaWiki\Search\Hook\SearchResultsAugmentHook,
	\MediaWiki\Search\Hook\ShowSearchHitHook,
	\MediaWiki\Search\Hook\ShowSearchHitTitleHook,
	\MediaWiki\Search\Hook\SpecialSearchPowerBoxHook,
	\MediaWiki\Search\Hook\SpecialSearchProfileFormHook,
	\MediaWiki\Session\Hook\SessionCheckInfoHook,
	\MediaWiki\Session\Hook\SessionMetadataHook,
	\MediaWiki\Shell\Hook\WfShellWikiCmdHook,
	\MediaWiki\Skins\Hook\SkinAfterPortletHook,
	\MediaWiki\Skins\Hook\SkinPageReadyConfigHook,
	\MediaWiki\SpecialPage\Hook\AuthChangeFormFieldsHook,
	\MediaWiki\SpecialPage\Hook\ChangeAuthenticationDataAuditHook,
	\MediaWiki\SpecialPage\Hook\ChangesListSpecialPageQueryHook,
	\MediaWiki\SpecialPage\Hook\ChangesListSpecialPageStructuredFiltersHook,
	\MediaWiki\SpecialPage\Hook\RedirectSpecialArticleRedirectParamsHook,
	\MediaWiki\SpecialPage\Hook\SpecialPageAfterExecuteHook,
	\MediaWiki\SpecialPage\Hook\SpecialPageBeforeExecuteHook,
	\MediaWiki\SpecialPage\Hook\SpecialPageBeforeFormDisplayHook,
	\MediaWiki\SpecialPage\Hook\SpecialPage_initListHook,
	\MediaWiki\SpecialPage\Hook\WgQueryPagesHook,
	\MediaWiki\Storage\Hook\ArticleEditUpdateNewTalkHook,
	\MediaWiki\Storage\Hook\ArticlePrepareTextForEditHook,
	\MediaWiki\Storage\Hook\BeforeRevertedTagUpdateHook,
	\MediaWiki\Storage\Hook\MultiContentSaveHook,
	\MediaWiki\Storage\Hook\PageContentSaveHook,
	\MediaWiki\Storage\Hook\PageSaveCompleteHook,
	\MediaWiki\Storage\Hook\ParserOutputStashForEditHook,
	\MediaWiki\Storage\Hook\RevisionDataUpdatesHook,
	\MediaWiki\User\Hook\AutopromoteConditionHook,
	\MediaWiki\User\Hook\ConfirmEmailCompleteHook,
	\MediaWiki\User\Hook\EmailConfirmedHook,
	\MediaWiki\User\Hook\GetAutoPromoteGroupsHook,
	\MediaWiki\User\Hook\InvalidateEmailCompleteHook,
	\MediaWiki\User\Hook\IsValidPasswordHook,
	\MediaWiki\User\Hook\PingLimiterHook,
	\MediaWiki\User\Hook\SpecialPasswordResetOnSubmitHook,
	\MediaWiki\User\Hook\UserAddGroupHook,
	\MediaWiki\User\Hook\UserArrayFromResultHook,
	\MediaWiki\User\Hook\UserCanSendEmailHook,
	\MediaWiki\User\Hook\UserClearNewTalkNotificationHook,
	\MediaWiki\User\Hook\UserRequirementsConditionHook,
	\MediaWiki\User\Hook\UserEffectiveGroupsHook,
	\MediaWiki\User\Hook\UserGetDefaultOptionsHook,
	\MediaWiki\User\Hook\UserGetEmailAuthenticationTimestampHook,
	\MediaWiki\User\Hook\UserGetEmailHook,
	\MediaWiki\User\Hook\UserGetReservedNamesHook,
	\MediaWiki\User\Hook\UserGroupsChangedHook,
	\MediaWiki\User\Hook\UserIsBlockedGloballyHook,
	\MediaWiki\User\Hook\UserIsBotHook,
	\MediaWiki\User\Hook\UserIsLockedHook,
	\MediaWiki\User\Hook\UserLoadAfterLoadFromSessionHook,
	\MediaWiki\User\Hook\UserLoadDefaultsHook,
	\MediaWiki\User\Hook\UserLogoutHook,
	\MediaWiki\User\Hook\UserPrivilegedGroupsHook,
	\MediaWiki\Linker\Hook\UserLinkRendererUserLinkPostRenderHook,
	\MediaWiki\User\Hook\UserRemoveGroupHook,
	\MediaWiki\User\Hook\UserSaveSettingsHook,
	\MediaWiki\User\Hook\UserSendConfirmationMailHook,
	\MediaWiki\User\Hook\UserSetEmailAuthenticationTimestampHook,
	\MediaWiki\User\Hook\UserSetEmailHook,
	\MediaWiki\User\Hook\User__mailPasswordInternalHook,
	\MediaWiki\User\Options\Hook\LoadUserOptionsHook,
	\MediaWiki\User\Options\Hook\LocalUserOptionsStoreSaveHook,
	\MediaWiki\User\Options\Hook\SaveUserOptionsHook,
	\MediaWiki\User\Options\Hook\ConditionalDefaultOptionsAddConditionHook
{
	/** @var HookContainer */
	private $container;

	public function __construct( HookContainer $container ) {
		$this->container = $container;
	}

	/** @inheritDoc */
	public function onAbortAutoblock( $autoblockip, $block ) {
		return $this->container->run(
			'AbortAutoblock',
			[ $autoblockip, $block ]
		);
	}

	/** @inheritDoc */
	public function onAbortDiffCache( $diffEngine ) {
		return $this->container->run(
			'AbortDiffCache',
			[ $diffEngine ]
		);
	}

	/** @inheritDoc */
	public function onAbortEmailNotification( $editor, $title, $rc ) {
		return $this->container->run(
			'AbortEmailNotification',
			[ $editor, $title, $rc ]
		);
	}

	/** @inheritDoc */
	public function onAbortTalkPageEmailNotification( $targetUser, $title ) {
		return $this->container->run(
			'AbortTalkPageEmailNotification',
			[ $targetUser, $title ]
		);
	}

	/** @inheritDoc */
	public function onActionBeforeFormDisplay( $name, $form, $article ) {
		return $this->container->run(
			'ActionBeforeFormDisplay',
			[ $name, $form, $article ]
		);
	}

	/** @inheritDoc */
	public function onActionModifyFormFields( $name, &$fields, $article ) {
		return $this->container->run(
			'ActionModifyFormFields',
			[ $name, &$fields, $article ]
		);
	}

	/** @inheritDoc */
	public function onAddNewAccount( $user, $byEmail ) {
		return $this->container->run(
			'AddNewAccount',
			[ $user, $byEmail ]
		);
	}

	/** @inheritDoc */
	public function onAfterBuildFeedLinks( &$feedLinks ) {
		return $this->container->run(
			'AfterBuildFeedLinks',
			[ &$feedLinks ]
		);
	}

	/** @inheritDoc */
	public function onAfterFinalPageOutput( $output ): void {
		$this->container->run(
			'AfterFinalPageOutput',
			[ $output ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onAfterImportPage( $title, $foreignTitle, $revCount,
		$sRevCount, $pageInfo
	) {
		return $this->container->run(
			'AfterImportPage',
			[ $title, $foreignTitle, $revCount, $sRevCount, $pageInfo ]
		);
	}

	/** @inheritDoc */
	public function onAfterParserFetchFileAndTitle( $parser, $ig, &$html ) {
		return $this->container->run(
			'AfterParserFetchFileAndTitle',
			[ $parser, $ig, &$html ]
		);
	}

	/** @inheritDoc */
	public function onAlternateEdit( $editPage ) {
		return $this->container->run(
			'AlternateEdit',
			[ $editPage ]
		);
	}

	/** @inheritDoc */
	public function onAlternateEditPreview( $editPage, &$content, &$previewHTML,
		&$parserOutput
	) {
		return $this->container->run(
			'AlternateEditPreview',
			[ $editPage, &$content, &$previewHTML, &$parserOutput ]
		);
	}

	/** @inheritDoc */
	public function onAlternateUserMailer( $headers, $to, $from, $subject, $body ) {
		return $this->container->run(
			'AlternateUserMailer',
			[ $headers, $to, $from, $subject, $body ]
		);
	}

	/** @inheritDoc */
	public function onAncientPagesQuery( &$tables, &$conds, &$joinConds ) {
		return $this->container->run(
			'AncientPagesQuery',
			[ &$tables, &$conds, &$joinConds ]
		);
	}

	/** @inheritDoc */
	public function onApiBeforeMain( &$main ) {
		return $this->container->run(
			'ApiBeforeMain',
			[ &$main ]
		);
	}

	/** @inheritDoc */
	public function onArticleConfirmDelete( $article, $output, &$reason ) {
		return $this->container->run(
			'ArticleConfirmDelete',
			[ $article, $output, &$reason ]
		);
	}

	/** @inheritDoc */
	public function onArticleContentOnDiff( $diffEngine, $output ) {
		return $this->container->run(
			'ArticleContentOnDiff',
			[ $diffEngine, $output ]
		);
	}

	/** @inheritDoc */
	public function onArticleDelete( $wikiPage, $user, &$reason, &$error, &$status,
		$suppress
	) {
		return $this->container->run(
			'ArticleDelete',
			[ $wikiPage, $user, &$reason, &$error, &$status, $suppress ]
		);
	}

	/** @inheritDoc */
	public function onArticleDeleteAfterSuccess( $title, $outputPage ) {
		return $this->container->run(
			'ArticleDeleteAfterSuccess',
			[ $title, $outputPage ]
		);
	}

	/** @inheritDoc */
	public function onArticleDeleteComplete( $wikiPage, $user, $reason, $id,
		$content, $logEntry, $archivedRevisionCount
	) {
		return $this->container->run(
			'ArticleDeleteComplete',
			[ $wikiPage, $user, $reason, $id, $content, $logEntry,
				$archivedRevisionCount ]
		);
	}

	/** @inheritDoc */
	public function onArticleEditUpdateNewTalk( $wikiPage, $recipient ) {
		return $this->container->run(
			'ArticleEditUpdateNewTalk',
			[ $wikiPage, $recipient ]
		);
	}

	/** @inheritDoc */
	public function onArticleFromTitle( $title, &$article, $context ) {
		return $this->container->run(
			'ArticleFromTitle',
			[ $title, &$article, $context ]
		);
	}

	/** @inheritDoc */
	public function onArticleMergeComplete( $targetTitle, $destTitle ) {
		return $this->container->run(
			'ArticleMergeComplete',
			[ $targetTitle, $destTitle ]
		);
	}

	/** @inheritDoc */
	public function onArticlePageDataAfter( $wikiPage, &$row ) {
		return $this->container->run(
			'ArticlePageDataAfter',
			[ $wikiPage, &$row ]
		);
	}

	/** @inheritDoc */
	public function onArticlePageDataBefore( $wikiPage, &$fields, &$tables,
		&$joinConds
	) {
		return $this->container->run(
			'ArticlePageDataBefore',
			[ $wikiPage, &$fields, &$tables, &$joinConds ]
		);
	}

	/** @inheritDoc */
	public function onArticleParserOptions( Article $article, ParserOptions $popts ) {
		return $this->container->run(
			'ArticleParserOptions',
			[ $article, $popts ]
		);
	}

	/** @inheritDoc */
	public function onArticlePrepareTextForEdit( $wikiPage, $popts ) {
		return $this->container->run(
			'ArticlePrepareTextForEdit',
			[ $wikiPage, $popts ]
		);
	}

	/** @inheritDoc */
	public function onArticleProtect( $wikiPage, $user, $protect, $reason ) {
		return $this->container->run(
			'ArticleProtect',
			[ $wikiPage, $user, $protect, $reason ]
		);
	}

	/** @inheritDoc */
	public function onArticleProtectComplete( $wikiPage, $user, $protect, $reason ) {
		return $this->container->run(
			'ArticleProtectComplete',
			[ $wikiPage, $user, $protect, $reason ]
		);
	}

	/** @inheritDoc */
	public function onArticlePurge( $wikiPage ) {
		return $this->container->run(
			'ArticlePurge',
			[ $wikiPage ]
		);
	}

	/** @inheritDoc */
	public function onArticleRevisionViewCustom( $revision, $title, $oldid,
		$output
	) {
		return $this->container->run(
			'ArticleRevisionViewCustom',
			[ $revision, $title, $oldid, $output ]
		);
	}

	/** @inheritDoc */
	public function onArticleRevisionVisibilitySet( $title, $ids,
		$visibilityChangeMap
	) {
		return $this->container->run(
			'ArticleRevisionVisibilitySet',
			[ $title, $ids, $visibilityChangeMap ]
		);
	}

	/** @inheritDoc */
	public function onArticleShowPatrolFooter( $article ) {
		return $this->container->run(
			'ArticleShowPatrolFooter',
			[ $article ]
		);
	}

	/** @inheritDoc */
	public function onArticleUndelete( $title, $create, $comment, $oldPageId,
		$restoredPages
	) {
		return $this->container->run(
			'ArticleUndelete',
			[ $title, $create, $comment, $oldPageId, $restoredPages ]
		);
	}

	/** @inheritDoc */
	public function onArticleUpdateBeforeRedirect( $article, &$sectionanchor,
		&$extraq
	) {
		return $this->container->run(
			'ArticleUpdateBeforeRedirect',
			[ $article, &$sectionanchor, &$extraq ]
		);
	}

	/** @inheritDoc */
	public function onArticleViewFooter( $article, $patrolFooterShown ) {
		return $this->container->run(
			'ArticleViewFooter',
			[ $article, $patrolFooterShown ]
		);
	}

	/** @inheritDoc */
	public function onArticleViewHeader( $article, &$outputDone, &$pcache ) {
		return $this->container->run(
			'ArticleViewHeader',
			[ $article, &$outputDone, &$pcache ]
		);
	}

	/** @inheritDoc */
	public function onArticleViewRedirect( $article ) {
		return $this->container->run(
			'ArticleViewRedirect',
			[ $article ]
		);
	}

	/** @inheritDoc */
	public function onArticle__MissingArticleConditions( &$conds, $logTypes ) {
		return $this->container->run(
			'Article::MissingArticleConditions',
			[ &$conds, $logTypes ]
		);
	}

	/** @inheritDoc */
	public function onAuthChangeFormFields( $requests, $fieldInfo,
		&$formDescriptor, $action
	) {
		return $this->container->run(
			'AuthChangeFormFields',
			[ $requests, $fieldInfo, &$formDescriptor, $action ]
		);
	}

	/** @inheritDoc */
	public function onAuthManagerFilterProviders( array &$providers ): void {
		$this->container->run(
			'AuthManagerFilterProviders',
			[ &$providers ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onAuthManagerLoginAuthenticateAudit( $response, $user,
		$username, $extraData
	) {
		return $this->container->run(
			'AuthManagerLoginAuthenticateAudit',
			[ $response, $user, $username, $extraData ]
		);
	}

	/** @inheritDoc */
	public function onAuthManagerVerifyAuthentication(
		?UserIdentity $user,
		AuthenticationResponse &$response,
		AuthManager $authManager,
		array $info
	): bool {
		return $this->container->run(
			'AuthManagerVerifyAuthentication',
			[ $user, &$response, $authManager, $info ],
		);
	}

	/** @inheritDoc */
	public function onAuthPreserveQueryParams( &$params, $options ) {
		return $this->container->run(
			'AuthPreserveQueryParams', [ &$params, $options ]
		);
	}

	/** @inheritDoc */
	public function onAuthenticationAttemptThrottled( string $type, ?string $username, ?string $ip ) {
		return $this->container->run(
			'AuthenticationAttemptThrottled', [ $type, $username, $ip ]
		);
	}

	/** @inheritDoc */
	public function onAutopromoteCondition( $type, $args, $user, &$result ) {
		return $this->container->run(
			'AutopromoteCondition',
			[ $type, $args, $user, &$result ]
		);
	}

	/** @inheritDoc */
	public function onBacklinkCacheGetConditions( $table, $title, &$conds ) {
		return $this->container->run(
			'BacklinkCacheGetConditions',
			[ $table, $title, &$conds ]
		);
	}

	/** @inheritDoc */
	public function onBacklinkCacheGetPrefix( $table, &$prefix ) {
		return $this->container->run(
			'BacklinkCacheGetPrefix',
			[ $table, &$prefix ]
		);
	}

	/** @inheritDoc */
	public function onBadImage( $name, &$bad ) {
		return $this->container->run(
			'BadImage',
			[ $name, &$bad ]
		);
	}

	/** @inheritDoc */
	public function onBeforeDisplayNoArticleText( $article ) {
		return $this->container->run(
			'BeforeDisplayNoArticleText',
			[ $article ]
		);
	}

	/** @inheritDoc */
	public function onBeforeInitialize( $title, $unused, $output, $user, $request,
		$mediaWiki
	) {
		return $this->container->run(
			'BeforeInitialize',
			[ $title, $unused, $output, $user, $request, $mediaWiki ]
		);
	}

	/** @inheritDoc */
	public function onBeforePageDisplay( $out, $skin ): void {
		$this->container->run(
			'BeforePageDisplay',
			[ $out, $skin ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onBeforePageRedirect( $out, &$redirect, &$code ) {
		return $this->container->run(
			'BeforePageRedirect',
			[ $out, &$redirect, &$code ]
		);
	}

	/** @inheritDoc */
	public function onBeforeParserFetchFileAndTitle( $parser, $nt, &$options,
		&$descQuery
	) {
		return $this->container->run(
			'BeforeParserFetchFileAndTitle',
			[ $parser, $nt, &$options, &$descQuery ]
		);
	}

	/** @inheritDoc */
	public function onBeforeParserFetchTemplateRevisionRecord(
		?LinkTarget $contextTitle, LinkTarget $title,
		bool &$skip, ?RevisionRecord &$revRecord
	) {
		return $this->container->run(
			'BeforeParserFetchTemplateRevisionRecord',
			[ $contextTitle, $title, &$skip, &$revRecord ]
		);
	}

	/** @inheritDoc */
	public function onBeforeRevertedTagUpdate( $wikiPage, $user,
		$summary, $flags, $revisionRecord, $editResult, &$approved
	): void {
		$this->container->run(
			'BeforeRevertedTagUpdate',
			[ $wikiPage, $user, $summary, $flags, $revisionRecord, $editResult,
				&$approved ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onBeforeWelcomeCreation( &$welcome_creation_msg,
		&$injected_html
	) {
		return $this->container->run(
			'BeforeWelcomeCreation',
			[ &$welcome_creation_msg, &$injected_html ]
		);
	}

	/** @inheritDoc */
	public function onBitmapHandlerCheckImageArea( $image, &$params,
		&$checkImageAreaHookResult
	) {
		return $this->container->run(
			'BitmapHandlerCheckImageArea',
			[ $image, &$params, &$checkImageAreaHookResult ]
		);
	}

	/** @inheritDoc */
	public function onBitmapHandlerTransform( $handler, $image, &$scalerParams,
		&$mto
	) {
		return $this->container->run(
			'BitmapHandlerTransform',
			[ $handler, $image, &$scalerParams, &$mto ]
		);
	}

	/** @inheritDoc */
	public function onBlockIp( $block, $user, &$reason ) {
		return $this->container->run(
			'BlockIp',
			[ $block, $user, &$reason ]
		);
	}

	/** @inheritDoc */
	public function onBlockIpComplete( $block, $user, $priorBlock ) {
		return $this->container->run(
			'BlockIpComplete',
			[ $block, $user, $priorBlock ]
		);
	}

	/** @inheritDoc */
	public function onBookInformation( $isbn, $output ) {
		return $this->container->run(
			'BookInformation',
			[ $isbn, $output ]
		);
	}

	/** @inheritDoc */
	public function onCanonicalNamespaces( &$namespaces ) {
		return $this->container->run(
			'CanonicalNamespaces',
			[ &$namespaces ]
		);
	}

	/** @inheritDoc */
	public function onCategoryAfterPageAdded( $category, $wikiPage ) {
		return $this->container->run(
			'CategoryAfterPageAdded',
			[ $category, $wikiPage ]
		);
	}

	/** @inheritDoc */
	public function onCategoryAfterPageRemoved( $category, $wikiPage, $id ) {
		return $this->container->run(
			'CategoryAfterPageRemoved',
			[ $category, $wikiPage, $id ]
		);
	}

	/** @inheritDoc */
	public function onCategoryPageView( $catpage ) {
		return $this->container->run(
			'CategoryPageView',
			[ $catpage ]
		);
	}

	/** @inheritDoc */
	public function onCategoryViewer__doCategoryQuery( $type, $res ) {
		return $this->container->run(
			'CategoryViewer::doCategoryQuery',
			[ $type, $res ]
		);
	}

	/** @inheritDoc */
	public function onCategoryViewer__generateLink( $type, $title, $html, &$link ) {
		return $this->container->run(
			'CategoryViewer::generateLink',
			[ $type, $title, $html, &$link ]
		);
	}

	/** @inheritDoc */
	public function onChangeAuthenticationDataAudit( $req, $status ) {
		return $this->container->run(
			'ChangeAuthenticationDataAudit',
			[ $req, $status ]
		);
	}

	/** @inheritDoc */
	public function onChangesListInitRows( $changesList, $rows ) {
		return $this->container->run(
			'ChangesListInitRows',
			[ $changesList, $rows ]
		);
	}

	/** @inheritDoc */
	public function onChangesListInsertArticleLink( $changesList, &$articlelink,
		&$s, $rc, $unpatrolled, $watched
	) {
		return $this->container->run(
			'ChangesListInsertArticleLink',
			[ $changesList, &$articlelink, &$s, $rc, $unpatrolled, $watched ]
		);
	}

	/** @inheritDoc */
	public function onChangesListInsertLogEntry( $entry, $context, &$html, &$classes, &$attribs ) {
		return $this->container->run(
			'ChangesListInsertLogEntry',
			[ $entry, $context, &$html, &$classes, &$attribs ]
		);
	}

	/** @inheritDoc */
	public function onChangesListSpecialPageQuery( $name, &$tables, &$fields,
		&$conds, &$query_options, &$join_conds, $opts
	) {
		return $this->container->run(
			'ChangesListSpecialPageQuery',
			[ $name, &$tables, &$fields, &$conds, &$query_options,
				&$join_conds, $opts ]
		);
	}

	/** @inheritDoc */
	public function onChangesListSpecialPageStructuredFilters( $special ) {
		return $this->container->run(
			'ChangesListSpecialPageStructuredFilters',
			[ $special ]
		);
	}

	/** @inheritDoc */
	public function onChangeTagAfterDelete( $tag, &$status ) {
		return $this->container->run(
			'ChangeTagAfterDelete',
			[ $tag, &$status ]
		);
	}

	/** @inheritDoc */
	public function onChangeTagCanCreate( $tag, $user, &$status ) {
		return $this->container->run(
			'ChangeTagCanCreate',
			[ $tag, $user, &$status ]
		);
	}

	/** @inheritDoc */
	public function onChangeTagCanDelete( $tag, $user, &$status ) {
		return $this->container->run(
			'ChangeTagCanDelete',
			[ $tag, $user, &$status ]
		);
	}

	/** @inheritDoc */
	public function onChangeTagsAfterUpdateTags( $addedTags, $removedTags,
		$prevTags, $rc_id, $rev_id, $log_id, $params, $rc, $user
	) {
		return $this->container->run(
			'ChangeTagsAfterUpdateTags',
			[ $addedTags, $removedTags, $prevTags, $rc_id, $rev_id, $log_id,
				$params, $rc, $user ]
		);
	}

	/** @inheritDoc */
	public function onChangeTagsAllowedAdd( &$allowedTags, $addTags, $user ) {
		return $this->container->run(
			'ChangeTagsAllowedAdd',
			[ &$allowedTags, $addTags, $user ]
		);
	}

	/** @inheritDoc */
	public function onChangeTagsListActive( &$tags ) {
		return $this->container->run(
			'ChangeTagsListActive',
			[ &$tags ]
		);
	}

	/** @inheritDoc */
	public function onChangeUserGroups( $performer, $user, &$add, &$remove ) {
		return $this->container->run(
			'ChangeUserGroups',
			[ $performer, $user, &$add, &$remove ]
		);
	}

	/** @inheritDoc */
	public function onCollation__factory( $collationName, &$collationObject ) {
		return $this->container->run(
			'Collation::factory',
			[ $collationName, &$collationObject ]
		);
	}

	/** @inheritDoc */
	public function onConfirmEmailComplete( $user ) {
		return $this->container->run(
			'ConfirmEmailComplete',
			[ $user ]
		);
	}

	/** @inheritDoc */
	public function onContentAlterParserOutput( $content, $title, $parserOutput ) {
		return $this->container->run(
			'ContentAlterParserOutput',
			[ $content, $title, $parserOutput ]
		);
	}

	/** @inheritDoc */
	public function onContentGetParserOutput( $content, $title, $revId, $options,
		$generateHtml, &$parserOutput
	) {
		return $this->container->run(
			'ContentGetParserOutput',
			[ $content, $title, $revId, $options, $generateHtml, &$parserOutput ]
		);
	}

	/** @inheritDoc */
	public function onContentHandlerDefaultModelFor( $title, &$model ) {
		return $this->container->run(
			'ContentHandlerDefaultModelFor',
			[ $title, &$model ]
		);
	}

	/** @inheritDoc */
	public function onContentHandlerForModelID( $modelName, &$handler ) {
		return $this->container->run(
			'ContentHandlerForModelID',
			[ $modelName, &$handler ]
		);
	}

	/** @inheritDoc */
	public function onContentModelCanBeUsedOn( $contentModel, $title, &$ok ) {
		return $this->container->run(
			'ContentModelCanBeUsedOn',
			[ $contentModel, $title, &$ok ]
		);
	}

	/** @inheritDoc */
	public function onContentSecurityPolicyDefaultSource( &$defaultSrc,
		$policyConfig, $mode
	) {
		return $this->container->run(
			'ContentSecurityPolicyDefaultSource',
			[ &$defaultSrc, $policyConfig, $mode ]
		);
	}

	/** @inheritDoc */
	public function onContentSecurityPolicyDirectives( &$directives, $policyConfig,
		$mode
	) {
		return $this->container->run(
			'ContentSecurityPolicyDirectives',
			[ &$directives, $policyConfig, $mode ]
		);
	}

	/** @inheritDoc */
	public function onContentSecurityPolicyScriptSource( &$scriptSrc,
		$policyConfig, $mode
	) {
		return $this->container->run(
			'ContentSecurityPolicyScriptSource',
			[ &$scriptSrc, $policyConfig, $mode ]
		);
	}

	/** @inheritDoc */
	public function onContribsPager__getQueryInfo( $pager, &$queryInfo ) {
		return $this->container->run(
			'ContribsPager::getQueryInfo',
			[ $pager, &$queryInfo ]
		);
	}

	/** @inheritDoc */
	public function onContribsPager__reallyDoQuery( &$data, $pager, $offset,
		$limit, $descending
	) {
		return $this->container->run(
			'ContribsPager::reallyDoQuery',
			[ &$data, $pager, $offset, $limit, $descending ]
		);
	}

	/** @inheritDoc */
	public function onContributeCards( &$cards ): void {
		$this->container->run(
			'ContributeCards',
			[ &$cards ]
		);
	}

	/** @inheritDoc */
	public function onContributionsLineEnding( $page, &$ret, $row, &$classes,
		&$attribs
	) {
		return $this->container->run(
			'ContributionsLineEnding',
			[ $page, &$ret, $row, &$classes, &$attribs ]
		);
	}

	/** @inheritDoc */
	public function onContributionsToolLinks( $id, Title $title, array &$tools, SpecialPage $specialPage ) {
		return $this->container->run(
			'ContributionsToolLinks',
			[ $id, $title, &$tools, $specialPage ]
		);
	}

	/** @inheritDoc */
	public function onConvertContent( $content, $toModel, $lossy, &$result ) {
		return $this->container->run(
			'ConvertContent',
			[ $content, $toModel, $lossy, &$result ]
		);
	}

	/** @inheritDoc */
	public function onCustomEditor( $article, $user ) {
		return $this->container->run(
			'CustomEditor',
			[ $article, $user ]
		);
	}

	/** @inheritDoc */
	public function onDeletedContribsPager__reallyDoQuery( &$data, $pager, $offset,
		$limit, $descending
	) {
		return $this->container->run(
			'DeletedContribsPager::reallyDoQuery',
			[ &$data, $pager, $offset, $limit, $descending ]
		);
	}

	/** @inheritDoc */
	public function onDeletedContributionsLineEnding( $page, &$ret, $row,
		&$classes, &$attribs
	) {
		return $this->container->run(
			'DeletedContributionsLineEnding',
			[ $page, &$ret, $row, &$classes, &$attribs ]
		);
	}

	/** @inheritDoc */
	public function onDeleteUnknownPreferences( &$where, $db ) {
		return $this->container->run(
			'DeleteUnknownPreferences',
			[ &$where, $db ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineAfterLoadNewText( $differenceEngine ) {
		return $this->container->run(
			'DifferenceEngineAfterLoadNewText',
			[ $differenceEngine ]
		);
	}

	/** @inheritDoc */
	public function onTextSlotDiffRendererTablePrefix(
		\TextSlotDiffRenderer $textSlotDiffRenderer,
		IContextSource $context,
		array &$parts
	) {
		return $this->container->run(
			'TextSlotDiffRendererTablePrefix',
			[ $textSlotDiffRenderer, $context, &$parts ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineLoadTextAfterNewContentIsLoaded(
		$differenceEngine
	) {
		return $this->container->run(
			'DifferenceEngineLoadTextAfterNewContentIsLoaded',
			[ $differenceEngine ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineMarkPatrolledLink( $differenceEngine,
		&$markAsPatrolledLink, $rcid
	) {
		return $this->container->run(
			'DifferenceEngineMarkPatrolledLink',
			[ $differenceEngine, &$markAsPatrolledLink, $rcid ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineMarkPatrolledRCID( &$rcid, $differenceEngine,
		$change, $user
	) {
		return $this->container->run(
			'DifferenceEngineMarkPatrolledRCID',
			[ &$rcid, $differenceEngine, $change, $user ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineNewHeader( $differenceEngine, &$newHeader,
		$formattedRevisionTools, $nextlink, $rollback, $newminor, $diffOnly, $rdel,
		$unhide
	) {
		return $this->container->run(
			'DifferenceEngineNewHeader',
			[ $differenceEngine, &$newHeader, $formattedRevisionTools,
				$nextlink, $rollback, $newminor, $diffOnly, $rdel, $unhide ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineOldHeader( $differenceEngine, &$oldHeader,
		$prevlink, $oldminor, $diffOnly, $ldel, $unhide
	) {
		return $this->container->run(
			'DifferenceEngineOldHeader',
			[ $differenceEngine, &$oldHeader, $prevlink, $oldminor, $diffOnly,
				$ldel, $unhide ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineOldHeaderNoOldRev( &$oldHeader ) {
		return $this->container->run(
			'DifferenceEngineOldHeaderNoOldRev',
			[ &$oldHeader ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineRenderRevisionAddParserOutput(
		$differenceEngine, $out, $parserOutput, $wikiPage
	) {
		return $this->container->run(
			'DifferenceEngineRenderRevisionAddParserOutput',
			[ $differenceEngine, $out, $parserOutput, $wikiPage ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineRenderRevisionShowFinalPatrolLink() {
		return $this->container->run(
			'DifferenceEngineRenderRevisionShowFinalPatrolLink',
			[]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineShowDiff( $differenceEngine ) {
		return $this->container->run(
			'DifferenceEngineShowDiff',
			[ $differenceEngine ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineShowDiffPage( $out ) {
		return $this->container->run(
			'DifferenceEngineShowDiffPage',
			[ $out ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineShowDiffPageMaybeShowMissingRevision(
		$differenceEngine
	) {
		return $this->container->run(
			'DifferenceEngineShowDiffPageMaybeShowMissingRevision',
			[ $differenceEngine ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineShowEmptyOldContent( $differenceEngine ) {
		return $this->container->run(
			'DifferenceEngineShowEmptyOldContent',
			[ $differenceEngine ]
		);
	}

	/** @inheritDoc */
	public function onDifferenceEngineViewHeader( $differenceEngine ) {
		return $this->container->run(
			'DifferenceEngineViewHeader',
			[ $differenceEngine ]
		);
	}

	/** @inheritDoc */
	public function onDiffTools( $newRevRecord, &$links, $oldRevRecord, $userIdentity ) {
		return $this->container->run(
			'DiffTools',
			[ $newRevRecord, &$links, $oldRevRecord, $userIdentity ]
		);
	}

	/** @inheritDoc */
	public function onDisplayOldSubtitle( $article, &$oldid ) {
		return $this->container->run(
			'DisplayOldSubtitle',
			[ $article, &$oldid ]
		);
	}

	/** @inheritDoc */
	public function onEditFilter( $editor, $text, $section, &$error, $summary ) {
		return $this->container->run(
			'EditFilter',
			[ $editor, $text, $section, &$error, $summary ]
		);
	}

	/** @inheritDoc */
	public function onEditFilterMergedContent( $context, $content, $status,
		$summary, $user, $minoredit
	) {
		return $this->container->run(
			'EditFilterMergedContent',
			[ $context, $content, $status, $summary, $user, $minoredit ]
		);
	}

	/** @inheritDoc */
	public function onEditFormInitialText( $editPage ) {
		return $this->container->run(
			'EditFormInitialText',
			[ $editPage ]
		);
	}

	/** @inheritDoc */
	public function onEditFormPreloadText( &$text, $title ) {
		return $this->container->run(
			'EditFormPreloadText',
			[ &$text, $title ]
		);
	}

	/** @inheritDoc */
	public function onEditPageBeforeConflictDiff( $editor, $out ) {
		return $this->container->run(
			'EditPageBeforeConflictDiff',
			[ $editor, $out ]
		);
	}

	/** @inheritDoc */
	public function onEditPageBeforeEditButtons( $editpage, &$buttons, &$tabindex ) {
		return $this->container->run(
			'EditPageBeforeEditButtons',
			[ $editpage, &$buttons, &$tabindex ]
		);
	}

	/** @inheritDoc */
	public function onEditPageBeforeEditToolbar( &$toolbar ) {
		return $this->container->run(
			'EditPageBeforeEditToolbar',
			[ &$toolbar ]
		);
	}

	/** @inheritDoc */
	public function onEditPageCopyrightWarning( $title, &$msg ) {
		return $this->container->run(
			'EditPageCopyrightWarning',
			[ $title, &$msg ]
		);
	}

	/** @inheritDoc */
	public function onEditPageGetCheckboxesDefinition( $editpage, &$checkboxes ) {
		return $this->container->run(
			'EditPageGetCheckboxesDefinition',
			[ $editpage, &$checkboxes ]
		);
	}

	/** @inheritDoc */
	public function onEditPageGetDiffContent( $editPage, &$newtext ) {
		return $this->container->run(
			'EditPageGetDiffContent',
			[ $editPage, &$newtext ]
		);
	}

	/** @inheritDoc */
	public function onEditPageGetPreviewContent( $editPage, &$content ) {
		return $this->container->run(
			'EditPageGetPreviewContent',
			[ $editPage, &$content ]
		);
	}

	/** @inheritDoc */
	public function onEditPageNoSuchSection( $editpage, &$res ) {
		return $this->container->run(
			'EditPageNoSuchSection',
			[ $editpage, &$res ]
		);
	}

	/** @inheritDoc */
	public function onEditPageTosSummary( $title, &$msg ) {
		return $this->container->run(
			'EditPageTosSummary',
			[ $title, &$msg ]
		);
	}

	/** @inheritDoc */
	public function onEditPage__attemptSave( $editpage_Obj ) {
		return $this->container->run(
			'EditPage::attemptSave',
			[ $editpage_Obj ]
		);
	}

	/** @inheritDoc */
	public function onEditPage__attemptSave_after( $editpage_Obj, $status,
		$resultDetails
	) {
		return $this->container->run(
			'EditPage::attemptSave:after',
			[ $editpage_Obj, $status, $resultDetails ]
		);
	}

	/** @inheritDoc */
	public function onEditPage__importFormData( $editpage, $request ) {
		return $this->container->run(
			'EditPage::importFormData',
			[ $editpage, $request ]
		);
	}

	/** @inheritDoc */
	public function onEditPage__showEditForm_fields( $editor, $out ) {
		return $this->container->run(
			'EditPage::showEditForm:fields',
			[ $editor, $out ]
		);
	}

	/** @inheritDoc */
	public function onEditPage__showEditForm_initial( $editor, $out ) {
		return $this->container->run(
			'EditPage::showEditForm:initial',
			[ $editor, $out ]
		);
	}

	/** @inheritDoc */
	public function onEditPage__showReadOnlyForm_initial( $editor, $out ) {
		return $this->container->run(
			'EditPage::showReadOnlyForm:initial',
			[ $editor, $out ]
		);
	}

	/** @inheritDoc */
	public function onEditPage__showStandardInputs_options( $editor, $out,
		&$tabindex
	) {
		return $this->container->run(
			'EditPage::showStandardInputs:options',
			[ $editor, $out, &$tabindex ]
		);
	}

	/** @inheritDoc */
	public function onEmailConfirmed( $user, &$confirmed ) {
		return $this->container->run(
			'EmailConfirmed',
			[ $user, &$confirmed ]
		);
	}

	/** @inheritDoc */
	public function onEmailUser( &$to, &$from, &$subject, &$text, &$error ) {
		return $this->container->run(
			'EmailUser',
			[ &$to, &$from, &$subject, &$text, &$error ]
		);
	}

	/** @inheritDoc */
	public function onEmailUserCC( &$to, &$from, &$subject, &$text ) {
		return $this->container->run(
			'EmailUserCC',
			[ &$to, &$from, &$subject, &$text ]
		);
	}

	/** @inheritDoc */
	public function onEmailUserComplete( $to, $from, $subject, $text ) {
		return $this->container->run(
			'EmailUserComplete',
			[ $to, $from, $subject, $text ]
		);
	}

	/** @inheritDoc */
	public function onEmailUserForm( &$form ) {
		return $this->container->run(
			'EmailUserForm',
			[ &$form ]
		);
	}

	/** @inheritDoc */
	public function onEmailUserPermissionsErrors( $user, $editToken, &$hookErr ) {
		return $this->container->run(
			'EmailUserPermissionsErrors',
			[ $user, $editToken, &$hookErr ]
		);
	}

	/** @inheritDoc */
	public function onEmailUserAuthorizeSend( Authority $sender, StatusValue $status ) {
		return $this->container->run(
			'EmailUserAuthorizeSend',
			[ $sender, $status ]
		);
	}

	/** @inheritDoc */
	public function onEmailUserSendEmail(
		Authority $from,
		MailAddress $fromAddress,
		UserEmailContact $to,
		MailAddress $toAddress,
		string $subject,
		string $text,
		StatusValue $status
	) {
		return $this->container->run(
			'EmailUserSendEmail',
			[ $from, $fromAddress, $to, $toAddress, $subject, $text, $status ]
		);
	}

	/** @inheritDoc */
	public function onEnhancedChangesListModifyBlockLineData( $changesList, &$data,
		$rc
	) {
		return $this->container->run(
			'EnhancedChangesListModifyBlockLineData',
			[ $changesList, &$data, $rc ]
		);
	}

	/** @inheritDoc */
	public function onEnhancedChangesListModifyLineData( $changesList, &$data,
		$block, $rc, &$classes, &$attribs
	) {
		return $this->container->run(
			'EnhancedChangesListModifyLineData',
			[ $changesList, &$data, $block, $rc, &$classes, &$attribs ]
		);
	}

	/** @inheritDoc */
	public function onEnhancedChangesList__getLogText( $changesList, &$links,
		$block
	) {
		return $this->container->run(
			'EnhancedChangesList::getLogText',
			[ $changesList, &$links, $block ]
		);
	}

	/** @inheritDoc */
	public function onExemptFromAccountCreationThrottle( $ip ) {
		return $this->container->run(
			'ExemptFromAccountCreationThrottle',
			[ $ip ]
		);
	}

	/** @inheritDoc */
	public function onExtensionTypes( &$extTypes ) {
		return $this->container->run(
			'ExtensionTypes',
			[ &$extTypes ]
		);
	}

	/** @inheritDoc */
	public function onFetchChangesList( $user, $skin, &$list, $groups ) {
		return $this->container->run(
			'FetchChangesList',
			[ $user, $skin, &$list, $groups ]
		);
	}

	/** @inheritDoc */
	public function onFileDeleteComplete( $file, $oldimage, $article, $user,
		$reason
	) {
		return $this->container->run(
			'FileDeleteComplete',
			[ $file, $oldimage, $article, $user, $reason ]
		);
	}

	/** @inheritDoc */
	public function onFileTransformed( $file, $thumb, $tmpThumbPath, $thumbPath ) {
		return $this->container->run(
			'FileTransformed',
			[ $file, $thumb, $tmpThumbPath, $thumbPath ]
		);
	}

	/** @inheritDoc */
	public function onFileUndeleteComplete( $title, $fileVersions, $user, $reason ) {
		return $this->container->run(
			'FileUndeleteComplete',
			[ $title, $fileVersions, $user, $reason ]
		);
	}

	/** @inheritDoc */
	public function onFileUpload( $file, $reupload, $hasDescription ) {
		return $this->container->run(
			'FileUpload',
			[ $file, $reupload, $hasDescription ]
		);
	}

	/** @inheritDoc */
	public function onFormatAutocomments( &$comment, $pre, $auto, $post, $title,
		$local, $wikiId
	) {
		return $this->container->run(
			'FormatAutocomments',
			[ &$comment, $pre, $auto, $post, $title, $local, $wikiId ]
		);
	}

	/** @inheritDoc */
	public function onGalleryGetModes( &$modeArray ) {
		return $this->container->run(
			'GalleryGetModes',
			[ &$modeArray ]
		);
	}

	/** @inheritDoc */
	public function onGetAllBlockActions( &$actions ) {
		return $this->container->run(
			'GetAllBlockActions',
			[ &$actions ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onGetAutoPromoteGroups( $user, &$promote ) {
		return $this->container->run(
			'GetAutoPromoteGroups',
			[ $user, &$promote ]
		);
	}

	/** @inheritDoc */
	public function onGetActionName( IContextSource $context, string &$action ): void {
		$this->container->run(
			'GetActionName',
			[ $context, &$action ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onGetCacheVaryCookies( $out, &$cookies ) {
		return $this->container->run(
			'GetCacheVaryCookies',
			[ $out, &$cookies ]
		);
	}

	/** @inheritDoc */
	public function onGetCanonicalURL( $title, &$url, $query ) {
		return $this->container->run(
			'GetCanonicalURL',
			[ $title, &$url, $query ]
		);
	}

	/** @inheritDoc */
	public function onGetContentModels( &$models ) {
		return $this->container->run(
			'GetContentModels',
			[ &$models ]
		);
	}

	/** @inheritDoc */
	public function onGetDefaultSortkey( $title, &$sortkey ) {
		return $this->container->run(
			'GetDefaultSortkey',
			[ $title, &$sortkey ]
		);
	}

	/** @inheritDoc */
	public function onGetDifferenceEngine( $context, $old, $new, $refreshCache,
		$unhide, &$differenceEngine
	) {
		return $this->container->run(
			'GetDifferenceEngine',
			[ $context, $old, $new, $refreshCache, $unhide,
				&$differenceEngine ]
		);
	}

	/** @inheritDoc */
	public function onGetDoubleUnderscoreIDs( &$doubleUnderscoreIDs ) {
		return $this->container->run(
			'GetDoubleUnderscoreIDs',
			[ &$doubleUnderscoreIDs ]
		);
	}

	/** @inheritDoc */
	public function onGetExtendedMetadata( &$combinedMeta, $file, $context,
		$single, &$maxCacheTime
	) {
		return $this->container->run(
			'GetExtendedMetadata',
			[ &$combinedMeta, $file, $context, $single, &$maxCacheTime ]
		);
	}

	/** @inheritDoc */
	public function onGetFullURL( $title, &$url, $query ) {
		return $this->container->run(
			'GetFullURL',
			[ $title, &$url, $query ]
		);
	}

	/** @inheritDoc */
	public function onGetHumanTimestamp( &$output, $timestamp, $relativeTo, $user,
		$lang
	) {
		return $this->container->run(
			'GetHumanTimestamp',
			[ &$output, $timestamp, $relativeTo, $user, $lang ]
		);
	}

	/** @inheritDoc */
	public function onGetInternalURL( $title, &$url, $query ) {
		return $this->container->run(
			'GetInternalURL',
			[ $title, &$url, $query ]
		);
	}

	/** @inheritDoc */
	public function onGetIP( &$ip ) {
		return $this->container->run(
			'GetIP',
			[ &$ip ]
		);
	}

	/** @inheritDoc */
	public function onGetLangPreferredVariant( &$req ) {
		return $this->container->run(
			'GetLangPreferredVariant',
			[ &$req ]
		);
	}

	/** @inheritDoc */
	public function onGetLinkColours( $linkcolour_ids, &$colours, $title ) {
		return $this->container->run(
			'GetLinkColours',
			[ $linkcolour_ids, &$colours, $title ]
		);
	}

	/** @inheritDoc */
	public function onGetLocalURL( $title, &$url, $query ) {
		return $this->container->run(
			'GetLocalURL',
			[ $title, &$url, $query ]
		);
	}

	/** @inheritDoc */
	public function onGetLocalURL__Article( $title, &$url ) {
		return $this->container->run(
			'GetLocalURL::Article',
			[ $title, &$url ]
		);
	}

	/** @inheritDoc */
	public function onGetLocalURL__Internal( $title, &$url, $query ) {
		return $this->container->run(
			'GetLocalURL::Internal',
			[ $title, &$url, $query ]
		);
	}

	/** @inheritDoc */
	public function onGetLogTypesOnUser( &$types ) {
		return $this->container->run(
			'GetLogTypesOnUser',
			[ &$types ]
		);
	}

	/** @inheritDoc */
	public function onGetMagicVariableIDs( &$variableIDs ) {
		return $this->container->run(
			'GetMagicVariableIDs',
			[ &$variableIDs ]
		);
	}

	/** @inheritDoc */
	public function onGetMetadataVersion( &$version ) {
		return $this->container->run(
			'GetMetadataVersion',
			[ &$version ]
		);
	}

	/** @inheritDoc */
	public function onGetNewMessagesAlert( &$newMessagesAlert, $newtalks, $user,
		$out
	) {
		return $this->container->run(
			'GetNewMessagesAlert',
			[ &$newMessagesAlert, $newtalks, $user, $out ]
		);
	}

	/** @inheritDoc */
	public function onGetPreferences( $user, &$preferences ) {
		return $this->container->run(
			'GetPreferences',
			[ $user, &$preferences ]
		);
	}

	/** @inheritDoc */
	public function onGetRelativeTimestamp( &$output, &$diff, $timestamp,
		$relativeTo, $user, $lang
	) {
		return $this->container->run(
			'GetRelativeTimestamp',
			[ &$output, &$diff, $timestamp, $relativeTo, $user, $lang ]
		);
	}

	/** @inheritDoc */
	public function onGetSlotDiffRenderer( $contentHandler, &$slotDiffRenderer,
		$context
	) {
		return $this->container->run(
			'GetSlotDiffRenderer',
			[ $contentHandler, &$slotDiffRenderer, $context ]
		);
	}

	/** @inheritDoc */
	public function onGetUserBlock( $user, $ip, &$block ) {
		return $this->container->run(
			'GetUserBlock',
			[ $user, $ip, &$block ]
		);
	}

	/** @inheritDoc */
	public function onPermissionStatusAudit(
		LinkTarget $title,
		UserIdentity $user,
		string $action,
		string $rigor,
		PermissionStatus $status
	): void {
		$this->container->run(
			'PermissionStatusAudit',
			[ $title, $user, $action, $rigor, $status ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onGetSecurityLogContext( array $info, array &$context ): void {
		$this->container->run(
			'GetSecurityLogContext',
			[ $info, &$context ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onGetSessionJwtData( ?UserIdentity $user, array &$jwtData ): void {
		$this->container->run(
			'GetSessionJwtData',
			[ $user, &$jwtData ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onGetUserPermissionsErrors( $title, $user, $action, &$result ) {
		return $this->container->run(
			'getUserPermissionsErrors',
			[ $title, $user, $action, &$result ]
		);
	}

	/** @inheritDoc */
	public function onGetUserPermissionsErrorsExpensive( $title, $user, $action,
		&$result
	) {
		return $this->container->run(
			'getUserPermissionsErrorsExpensive',
			[ $title, $user, $action, &$result ]
		);
	}

	/** @inheritDoc */
	public function onGitViewers( &$extTypes ) {
		return $this->container->run(
			'GitViewers',
			[ &$extTypes ]
		);
	}

	/** @inheritDoc */
	public function onHistoryPageToolLinks( IContextSource $context, LinkRenderer $linkRenderer, array &$links ) {
		return $this->container->run(
			'HistoryPageToolLinks',
			[ $context, $linkRenderer, &$links ]
		);
	}

	/** @inheritDoc */
	public function onHistoryTools( $revRecord, &$links, $prevRevRecord, $userIdentity ) {
		return $this->container->run(
			'HistoryTools',
			[ $revRecord, &$links, $prevRevRecord, $userIdentity ]
		);
	}

	/** @inheritDoc */
	public function onHtmlCacheUpdaterAppendUrls( $title, $mode, &$append ) {
		return $this->container->run(
			'HtmlCacheUpdaterAppendUrls',
			[ $title, $mode, &$append ]
		);
	}

	/** @inheritDoc */
	public function onHtmlCacheUpdaterVaryUrls( $urls, &$append ) {
		return $this->container->run(
			'HtmlCacheUpdaterVaryUrls',
			[ $urls, &$append ]
		);
	}

	/** @inheritDoc */
	public function onHTMLFileCache__useFileCache( $context ) {
		return $this->container->run(
			'HTMLFileCache::useFileCache',
			[ $context ]
		);
	}

	/** @inheritDoc */
	public function onHtmlPageLinkRendererBegin( $linkRenderer, $target, &$text,
		&$customAttribs, &$query, &$ret
	) {
		return $this->container->run(
			'HtmlPageLinkRendererBegin',
			[ $linkRenderer, $target, &$text, &$customAttribs, &$query, &$ret ]
		);
	}

	/** @inheritDoc */
	public function onHtmlPageLinkRendererEnd( $linkRenderer, $target, $isKnown,
		&$text, &$attribs, &$ret
	) {
		return $this->container->run(
			'HtmlPageLinkRendererEnd',
			[ $linkRenderer, $target, $isKnown, &$text, &$attribs, &$ret ]
		);
	}

	/** @inheritDoc */
	public function onImageBeforeProduceHTML( $linker, &$title, &$file,
		&$frameParams, &$handlerParams, &$time, &$res, $parser, &$query, &$widthOption
	) {
		return $this->container->run(
			'ImageBeforeProduceHTML',
			[ $linker, &$title, &$file, &$frameParams, &$handlerParams, &$time,
				&$res, $parser, &$query, &$widthOption ]
		);
	}

	/** @inheritDoc */
	public function onImageOpenShowImageInlineBefore( $imagePage, $output ) {
		return $this->container->run(
			'ImageOpenShowImageInlineBefore',
			[ $imagePage, $output ]
		);
	}

	/** @inheritDoc */
	public function onImagePageAfterImageLinks( $imagePage, &$html ) {
		return $this->container->run(
			'ImagePageAfterImageLinks',
			[ $imagePage, &$html ]
		);
	}

	/** @inheritDoc */
	public function onImagePageFileHistoryLine( $imageHistoryList, $file, &$line, &$css ) {
		return $this->container->run(
			'ImagePageFileHistoryLine',
			[ $imageHistoryList, $file, &$line, &$css ]
		);
	}

	/** @inheritDoc */
	public function onImagePageFindFile( $page, &$file, &$displayFile ) {
		return $this->container->run(
			'ImagePageFindFile',
			[ $page, &$file, &$displayFile ]
		);
	}

	/** @inheritDoc */
	public function onImagePageShowTOC( $page, &$toc ) {
		return $this->container->run(
			'ImagePageShowTOC',
			[ $page, &$toc ]
		);
	}

	/** @inheritDoc */
	public function onImgAuthBeforeStream( &$title, &$path, &$name, &$result ) {
		return $this->container->run(
			'ImgAuthBeforeStream',
			[ &$title, &$path, &$name, &$result ]
		);
	}

	/** @inheritDoc */
	public function onImgAuthModifyHeaders( $title, &$headers ) {
		return $this->container->run(
			'ImgAuthModifyHeaders',
			[ $title, &$headers ]
		);
	}

	/** @inheritDoc */
	public function onImportHandleLogItemXMLTag( $reader, $logInfo ) {
		return $this->container->run(
			'ImportHandleLogItemXMLTag',
			[ $reader, $logInfo ]
		);
	}

	/** @inheritDoc */
	public function onImportHandlePageXMLTag( $reader, &$pageInfo ) {
		return $this->container->run(
			'ImportHandlePageXMLTag',
			[ $reader, &$pageInfo ]
		);
	}

	/** @inheritDoc */
	public function onImportHandleRevisionXMLTag( $reader, $pageInfo,
		$revisionInfo
	) {
		return $this->container->run(
			'ImportHandleRevisionXMLTag',
			[ $reader, $pageInfo, $revisionInfo ]
		);
	}

	/** @inheritDoc */
	public function onImportHandleContentXMLTag( $reader, $contentInfo ) {
		return $this->container->run(
			'ImportHandleContentXMLTag',
			[ $reader, $contentInfo ] );
	}

	/** @inheritDoc */
	public function onImportHandleToplevelXMLTag( $reader ) {
		return $this->container->run(
			'ImportHandleToplevelXMLTag',
			[ $reader ]
		);
	}

	/** @inheritDoc */
	public function onImportHandleUnknownUser( $name ) {
		return $this->container->run(
			'ImportHandleUnknownUser',
			[ $name ]
		);
	}

	/** @inheritDoc */
	public function onImportHandleUploadXMLTag( $reader, $revisionInfo ) {
		return $this->container->run(
			'ImportHandleUploadXMLTag',
			[ $reader, $revisionInfo ]
		);
	}

	/** @inheritDoc */
	public function onImportLogInterwikiLink( &$fullInterwikiPrefix, &$pageTitle ) {
		return $this->container->run(
			'ImportLogInterwikiLink',
			[ &$fullInterwikiPrefix, &$pageTitle ]
		);
	}

	/** @inheritDoc */
	public function onImportSources( &$importSources ) {
		return $this->container->run(
			'ImportSources',
			[ &$importSources ]
		);
	}

	/** @inheritDoc */
	public function onInfoAction( $context, &$pageInfo ) {
		return $this->container->run(
			'InfoAction',
			[ $context, &$pageInfo ]
		);
	}

	/** @inheritDoc */
	public function onInitializeArticleMaybeRedirect( $title, $request,
		&$ignoreRedirect, &$target, &$article
	) {
		return $this->container->run(
			'InitializeArticleMaybeRedirect',
			[ $title, $request, &$ignoreRedirect, &$target, &$article ]
		);
	}

	/** @inheritDoc */
	public function onInternalParseBeforeLinks( $parser, &$text, $stripState ) {
		return $this->container->run(
			'InternalParseBeforeLinks',
			[ $parser, &$text, $stripState ]
		);
	}

	/** @inheritDoc */
	public function onInterwikiLoadPrefix( $prefix, &$iwData ) {
		return $this->container->run(
			'InterwikiLoadPrefix',
			[ $prefix, &$iwData ]
		);
	}

	/** @inheritDoc */
	public function onInvalidateEmailComplete( $user ) {
		return $this->container->run(
			'InvalidateEmailComplete',
			[ $user ]
		);
	}

	/** @inheritDoc */
	public function onIRCLineURL( &$url, &$query, $rc ) {
		return $this->container->run(
			'IRCLineURL',
			[ &$url, &$query, $rc ]
		);
	}

	/** @inheritDoc */
	public function onIsFileCacheable( $article ) {
		return $this->container->run(
			'IsFileCacheable',
			[ $article ]
		);
	}

	/** @inheritDoc */
	public function onIsTrustedProxy( $ip, &$result ) {
		return $this->container->run(
			'IsTrustedProxy',
			[ $ip, &$result ]
		);
	}

	/** @inheritDoc */
	public function onIsUploadAllowedFromUrl( $url, &$allowed ) {
		return $this->container->run(
			'IsUploadAllowedFromUrl',
			[ $url, &$allowed ]
		);
	}

	/** @inheritDoc */
	public function onIsValidEmailAddr( $addr, &$result ) {
		return $this->container->run(
			'isValidEmailAddr',
			[ $addr, &$result ]
		);
	}

	/** @inheritDoc */
	public function onIsValidPassword( $password, &$result, $user ) {
		return $this->container->run(
			'isValidPassword',
			[ $password, &$result, $user ]
		);
	}

	/** @inheritDoc */
	public function onJsonValidateSave( JsonContent $content, PageIdentity $pageIdentity, StatusValue $status ) {
		return $this->container->run(
			'JsonValidateSave',
			[ $content, $pageIdentity, &$status ]
		);
	}

	/** @inheritDoc */
	public function onLanguageGetNamespaces( &$namespaces ) {
		return $this->container->run(
			'LanguageGetNamespaces',
			[ &$namespaces ]
		);
	}

	/** @inheritDoc */
	public function onLanguageGetTranslatedLanguageNames( &$names, $code ) {
		return $this->container->run(
			'LanguageGetTranslatedLanguageNames',
			[ &$names, $code ]
		);
	}

	/** @inheritDoc */
	public function onLanguageLinks( $title, &$links, &$linkFlags ) {
		return $this->container->run(
			'LanguageLinks',
			[ $title, &$links, &$linkFlags ]
		);
	}

	/** @inheritDoc */
	public function onLanguageSelector( $out, $cssClassName ) {
		return $this->container->run(
			'LanguageSelector',
			[ $out, $cssClassName ]
		);
	}

	/** @inheritDoc */
	public function onLanguage__getMessagesFileName( $code, &$file ) {
		return $this->container->run(
			'Language::getMessagesFileName',
			[ $code, &$file ]
		);
	}

	/** @inheritDoc */
	public function onLinkerGenerateRollbackLink( $revRecord, $context, $options, &$inner ) {
		return $this->container->run(
			'LinkerGenerateRollbackLink',
			[ $revRecord, $context, $options, &$inner ]
		);
	}

	/** @inheritDoc */
	public function onLinkerMakeExternalImage( &$url, &$alt, &$img ) {
		return $this->container->run(
			'LinkerMakeExternalImage',
			[ &$url, &$alt, &$img ]
		);
	}

	/** @inheritDoc */
	public function onLinkerMakeExternalLink( &$url, &$text, &$link, &$attribs,
		$linkType
	) {
		return $this->container->run(
			'LinkerMakeExternalLink',
			[ &$url, &$text, &$link, &$attribs, $linkType ]
		);
	}

	/** @inheritDoc */
	public function onLinkerMakeMediaLinkFile( $title, $file, &$html, &$attribs,
		&$ret
	) {
		return $this->container->run(
			'LinkerMakeMediaLinkFile',
			[ $title, $file, &$html, &$attribs, &$ret ]
		);
	}

	/** @inheritDoc */
	public function onLinksUpdate( $linksUpdate ) {
		return $this->container->run(
			'LinksUpdate',
			[ $linksUpdate ]
		);
	}

	/** @inheritDoc */
	public function onLinksUpdateComplete( $linksUpdate, $ticket ) {
		return $this->container->run(
			'LinksUpdateComplete',
			[ $linksUpdate, $ticket ]
		);
	}

	/** @inheritDoc */
	public function onListDefinedTags( &$tags ) {
		return $this->container->run(
			'ListDefinedTags',
			[ &$tags ]
		);
	}

	/** @inheritDoc */
	public function onLoadExtensionSchemaUpdates( $updater ) {
		return $this->container->run(
			'LoadExtensionSchemaUpdates',
			[ $updater ],
			[ 'noServices' => true ]
		);
	}

	/** @inheritDoc */
	public function onLocalFilePurgeThumbnails( $file, $archiveName, $urls ) {
		return $this->container->run(
			'LocalFilePurgeThumbnails',
			[ $file, $archiveName, $urls ]
		);
	}

	/** @inheritDoc */
	public function onLocalFile__getHistory( $file, &$tables, &$fields, &$conds,
		&$opts, &$join_conds
	) {
		return $this->container->run(
			'LocalFile::getHistory',
			[ $file, &$tables, &$fields, &$conds, &$opts, &$join_conds ]
		);
	}

	/** @inheritDoc */
	public function onLocalisationCacheRecache( $cache, $code, &$alldata, $unused ) {
		return $this->container->run(
			'LocalisationCacheRecache',
			[ $cache, $code, &$alldata, $unused ]
		);
	}

	/** @inheritDoc */
	public function onLocalisationCacheRecacheFallback( $cache, $code, &$alldata ) {
		return $this->container->run(
			'LocalisationCacheRecacheFallback',
			[ $cache, $code, &$alldata ]
		);
	}

	/** @inheritDoc */
	public function onLocalUserCreated( $user, $autocreated ) {
		return $this->container->run(
			'LocalUserCreated',
			[ $user, $autocreated ]
		);
	}

	/** @inheritDoc */
	public function onLogEventsListGetExtraInputs( $type, $logEventsList, &$input,
		&$formDescriptor
	) {
		return $this->container->run(
			'LogEventsListGetExtraInputs',
			[ $type, $logEventsList, &$input, &$formDescriptor ]
		);
	}

	/** @inheritDoc */
	public function onLogEventsListLineEnding( $page, &$ret, $entry, &$classes,
		&$attribs
	) {
		return $this->container->run(
			'LogEventsListLineEnding',
			[ $page, &$ret, $entry, &$classes, &$attribs ]
		);
	}

	/** @inheritDoc */
	public function onLogEventsListShowLogExtract( &$s, $types, $page, $user,
		$param
	) {
		return $this->container->run(
			'LogEventsListShowLogExtract',
			[ &$s, $types, $page, $user, $param ]
		);
	}

	/** @inheritDoc */
	public function onLogException( $e, $suppressed ) {
		return $this->container->run(
			'LogException',
			[ $e, $suppressed ]
		);
	}

	/** @inheritDoc */
	public function onLoginFormValidErrorMessages( array &$messages ) {
		return $this->container->run(
			'LoginFormValidErrorMessages',
			[ &$messages ]
		);
	}

	/** @inheritDoc */
	public function onLogLine( $log_type, $log_action, $title, $paramArray,
		&$comment, &$revert, $time
	) {
		return $this->container->run(
			'LogLine',
			[ $log_type, $log_action, $title, $paramArray, &$comment,
				&$revert, $time ]
		);
	}

	/** @inheritDoc */
	public function onLonelyPagesQuery( &$tables, &$conds, &$joinConds ) {
		return $this->container->run(
			'LonelyPagesQuery',
			[ &$tables, &$conds, &$joinConds ]
		);
	}

	/** @inheritDoc */
	public function onMagicWordwgVariableIDs( &$variableIDs ) {
		return $this->container->run(
			'MagicWordwgVariableIDs',
			[ &$variableIDs ]
		);
	}

	/** @inheritDoc */
	public function onMaintenanceRefreshLinksInit( $refreshLinks ) {
		return $this->container->run(
			'MaintenanceRefreshLinksInit',
			[ $refreshLinks ]
		);
	}

	/** @inheritDoc */
	public function onMaintenanceShellStart(): void {
		$this->container->run(
			'MaintenanceShellStart',
			[],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onMaintenanceUpdateAddParams( &$params ) {
		return $this->container->run(
			'MaintenanceUpdateAddParams',
			[ &$params ]
		);
	}

	/** @inheritDoc */
	public function onMakeGlobalVariablesScript( &$vars, $out ): void {
		$this->container->run(
			'MakeGlobalVariablesScript',
			[ &$vars, $out ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onManualLogEntryBeforePublish( $logEntry ): void {
		$this->container->run(
			'ManualLogEntryBeforePublish',
			[ $logEntry ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onMarkPatrolled( $rcid, $user, $wcOnlySysopsCanPatrol, $auto,
		&$tags
	) {
		return $this->container->run(
			'MarkPatrolled',
			[ $rcid, $user, $wcOnlySysopsCanPatrol, $auto, &$tags ]
		);
	}

	/** @inheritDoc */
	public function onMarkPatrolledComplete( $rcid, $user, $wcOnlySysopsCanPatrol,
		$auto
	) {
		return $this->container->run(
			'MarkPatrolledComplete',
			[ $rcid, $user, $wcOnlySysopsCanPatrol, $auto ]
		);
	}

	/** @inheritDoc */
	public function onMediaWikiPerformAction( $output, $article, $title, $user,
		$request, $mediaWiki
	) {
		return $this->container->run(
			'MediaWikiPerformAction',
			[ $output, $article, $title, $user, $request, $mediaWiki ]
		);
	}

	/** @inheritDoc */
	public function onMediaWikiServices( $services ) {
		return $this->container->run(
			'MediaWikiServices',
			[ $services ],
			[ 'noServices' => true ]
		);
	}

	/** @inheritDoc */
	public function onMessageCacheFetchOverrides( array &$messages ): void {
		$this->container->run(
			'MessageCacheFetchOverrides',
			[ &$messages ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onMessageCacheReplace( $title, $text ) {
		return $this->container->run(
			'MessageCacheReplace',
			[ $title, $text ]
		);
	}

	/** @inheritDoc */
	public function onMessageCache__get( &$key ) {
		return $this->container->run(
			'MessageCache::get',
			[ &$key ]
		);
	}

	/** @inheritDoc */
	public function onMessagePostProcessHtml( &$value, $format, $key ): void {
		$this->container->run(
			'MessagePostProcessHtml',
			[ &$value, $format, $key ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onMessagePostProcessText( &$value, $format, $key ): void {
		$this->container->run(
			'MessagePostProcessText',
			[ &$value, $format, $key ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onMessagesPreLoad( $title, &$message, $code ) {
		return $this->container->run(
			'MessagesPreLoad',
			[ $title, &$message, $code ]
		);
	}

	/** @inheritDoc */
	public function onMimeMagicGuessFromContent( $mimeMagic, &$head, &$tail, $file,
		&$mime
	) {
		return $this->container->run(
			'MimeMagicGuessFromContent',
			[ $mimeMagic, &$head, &$tail, $file, &$mime ]
		);
	}

	/** @inheritDoc */
	public function onMimeMagicImproveFromExtension( $mimeMagic, $ext, &$mime ) {
		return $this->container->run(
			'MimeMagicImproveFromExtension',
			[ $mimeMagic, $ext, &$mime ]
		);
	}

	/** @inheritDoc */
	public function onMimeMagicInit( $mimeMagic ) {
		return $this->container->run(
			'MimeMagicInit',
			[ $mimeMagic ]
		);
	}

	/** @inheritDoc */
	public function onGetBlockErrorMessageKey( $block, &$key ) {
		return $this->container->run(
			'GetBlockErrorMessageKey',
			[ $block, &$key ]
		);
	}

	/** @inheritDoc */
	public function onModifyExportQuery( $db, &$tables, $cond, &$opts,
		&$join_conds, &$conds
	) {
		return $this->container->run(
			'ModifyExportQuery',
			[ $db, &$tables, $cond, &$opts, &$join_conds, &$conds ]
		);
	}

	/** @inheritDoc */
	public function onMovePageCheckPermissions( $oldTitle, $newTitle, $user,
		$reason, $status
	) {
		return $this->container->run(
			'MovePageCheckPermissions',
			[ $oldTitle, $newTitle, $user, $reason, $status ]
		);
	}

	/** @inheritDoc */
	public function onMovePageIsValidMove( $oldTitle, $newTitle, $status ) {
		return $this->container->run(
			'MovePageIsValidMove',
			[ $oldTitle, $newTitle, $status ]
		);
	}

	/** @inheritDoc */
	public function onMultiContentSave( $renderedRevision, $user, $summary, $flags,
		$status
	) {
		return $this->container->run(
			'MultiContentSave',
			[ $renderedRevision, $user, $summary, $flags, $status ]
		);
	}

	/** @inheritDoc */
	public function onNamespaceIsMovable( $index, &$result ) {
		return $this->container->run(
			'NamespaceIsMovable',
			[ $index, &$result ]
		);
	}

	/** @inheritDoc */
	public function onNewDifferenceEngine( $title, &$oldId, &$newId, $old, $new ) {
		return $this->container->run(
			'NewDifferenceEngine',
			[ $title, &$oldId, &$newId, $old, $new ]
		);
	}

	/** @inheritDoc */
	public function onNewPagesLineEnding( $page, &$ret, $row, &$classes, &$attribs ) {
		return $this->container->run(
			'NewPagesLineEnding',
			[ $page, &$ret, $row, &$classes, &$attribs ]
		);
	}

	/** @inheritDoc */
	public function onOldChangesListRecentChangesLine( $changeslist, &$s, $rc,
		&$classes, &$attribs
	) {
		return $this->container->run(
			'OldChangesListRecentChangesLine',
			[ $changeslist, &$s, $rc, &$classes, &$attribs ]
		);
	}

	/** @inheritDoc */
	public function onOpenSearchUrls( &$urls ) {
		return $this->container->run(
			'OpenSearchUrls',
			[ &$urls ]
		);
	}

	/** @inheritDoc */
	public function onOpportunisticLinksUpdate( $page, $title, $parserOutput ) {
		return $this->container->run(
			'OpportunisticLinksUpdate',
			[ $page, $title, $parserOutput ]
		);
	}

	/** @inheritDoc */
	public function onOtherAutoblockLogLink( &$otherBlockLink ) {
		return $this->container->run(
			'OtherAutoblockLogLink',
			[ &$otherBlockLink ]
		);
	}

	/** @inheritDoc */
	public function onOtherBlockLogLink( &$otherBlockLink, $ip ) {
		return $this->container->run(
			'OtherBlockLogLink',
			[ &$otherBlockLink, $ip ]
		);
	}

	/** @inheritDoc */
	public function onOutputPageAfterGetHeadLinksArray( &$tags, $out ) {
		return $this->container->run(
			'OutputPageAfterGetHeadLinksArray',
			[ &$tags, $out ]
		);
	}

	/** @inheritDoc */
	public function onOutputPageBeforeHTML( $out, &$text ) {
		return $this->container->run(
			'OutputPageBeforeHTML',
			[ $out, &$text ]
		);
	}

	/** @inheritDoc */
	public function onOutputPageBodyAttributes( $out, $sk, &$bodyAttrs ): void {
		$this->container->run(
			'OutputPageBodyAttributes',
			[ $out, $sk, &$bodyAttrs ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onOutputPageCheckLastModified( &$modifiedTimes, $out ) {
		return $this->container->run(
			'OutputPageCheckLastModified',
			[ &$modifiedTimes, $out ]
		);
	}

	/** @inheritDoc */
	public function onOutputPageParserOutput( $outputPage, $parserOutput ): void {
		$this->container->run(
			'OutputPageParserOutput',
			[ $outputPage, $parserOutput ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onOutputPageRenderCategoryLink(
		OutputPage $outputPage,
		ProperPageIdentity $categoryTitle,
		string $text,
		?string &$link
	): void {
		$this->container->run(
			'OutputPageRenderCategoryLink',
			[ $outputPage, $categoryTitle, $text, &$link ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onPageContentLanguage( $title, &$pageLang, $userLang ) {
		return $this->container->run(
			'PageContentLanguage',
			[ $title, &$pageLang, $userLang ]
		);
	}

	/** @inheritDoc */
	public function onPageContentSave( $wikiPage, $user, $content, &$summary,
		$isminor, $iswatch, $section, $flags, $status
	) {
		return $this->container->run(
			'PageContentSave',
			[ $wikiPage, $user, $content, &$summary, $isminor, $iswatch,
				$section, $flags, $status ]
		);
	}

	/** @inheritDoc */
	public function onPageDelete(
		ProperPageIdentity $page,
		Authority $deleter,
		string $reason,
		StatusValue $status,
		bool $suppress
	) {
		return $this->container->run(
			'PageDelete',
			[ $page, $deleter, $reason, $status, $suppress ]
		);
	}

	/** @inheritDoc */
	public function onPageDeleteComplete(
		ProperPageIdentity $page,
		Authority $deleter,
		string $reason,
		int $pageID,
		RevisionRecord $deletedRev,
		ManualLogEntry $logEntry,
		int $archivedRevisionCount
	) {
		return $this->container->run(
			'PageDeleteComplete',
			[ $page, $deleter, $reason, $pageID, $deletedRev, $logEntry, $archivedRevisionCount ]
		);
	}

	/** @inheritDoc */
	public function onPageDeletionDataUpdates( $title, $revision, &$updates ) {
		return $this->container->run(
			'PageDeletionDataUpdates',
			[ $title, $revision, &$updates ]
		);
	}

	/** @inheritDoc */
	public function onPageUndelete(
		ProperPageIdentity $page,
		Authority $performer,
		string $reason,
		bool $unsuppress,
		array $timestamps,
		array $fileVersions,
		StatusValue $status
	) {
		return $this->container->run(
			'PageUndelete',
			[ $page, $performer, $reason, $unsuppress, $timestamps, $fileVersions, $status ]
		);
	}

	/** @inheritDoc */
	public function onPageUndeleteComplete(
		ProperPageIdentity $page,
		Authority $restorer,
		string $reason,
		RevisionRecord $restoredRev,
		ManualLogEntry $logEntry,
		int $restoredRevisionCount,
		bool $created,
		array $restoredPageIds
	): void {
		$this->container->run(
			'PageUndeleteComplete',
			[
				$page,
				$restorer,
				$reason,
				$restoredRev,
				$logEntry,
				$restoredRevisionCount,
				$created,
				$restoredPageIds
			],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onPageHistoryBeforeList( $article, $context ) {
		return $this->container->run(
			'PageHistoryBeforeList',
			[ $article, $context ]
		);
	}

	/** @inheritDoc */
	public function onPageHistoryLineEnding( $historyAction, &$row, &$s, &$classes,
		&$attribs
	) {
		return $this->container->run(
			'PageHistoryLineEnding',
			[ $historyAction, &$row, &$s, &$classes, &$attribs ]
		);
	}

	/** @inheritDoc */
	public function onPageHistoryPager__doBatchLookups( $pager, $result ) {
		return $this->container->run(
			'PageHistoryPager::doBatchLookups',
			[ $pager, $result ]
		);
	}

	/** @inheritDoc */
	public function onPageHistoryPager__getQueryInfo( $pager, &$queryInfo ) {
		return $this->container->run(
			'PageHistoryPager::getQueryInfo',
			[ $pager, &$queryInfo ]
		);
	}

	/** @inheritDoc */
	public function onPageMoveComplete( $old, $new, $user, $pageid, $redirid, $reason, $revision ) {
		return $this->container->run(
			'PageMoveComplete',
			[ $old, $new, $user, $pageid, $redirid, $reason, $revision ]
		);
	}

	/** @inheritDoc */
	public function onPageMoveCompleting( $old, $new, $user, $pageid, $redirid, $reason, $revision ) {
		return $this->container->run(
			'PageMoveCompleting',
			[ $old, $new, $user, $pageid, $redirid, $reason, $revision ]
		);
	}

	/** @inheritDoc */
	public function onPageRenderingHash( &$confstr, $user, &$forOptions ) {
		return $this->container->run(
			'PageRenderingHash',
			[ &$confstr, $user, &$forOptions ]
		);
	}

	/** @inheritDoc */
	public function onPageSaveComplete( $wikiPage, $user, $summary, $flags,
		$revisionRecord, $editResult
	) {
		return $this->container->run(
			'PageSaveComplete',
			[ $wikiPage, $user, $summary, $flags, $revisionRecord, $editResult ]
		);
	}

	/** @inheritDoc */
	public function onPageViewUpdates( $wikipage, $user ) {
		return $this->container->run(
			'PageViewUpdates',
			[ $wikipage, $user ]
		);
	}

	/** @inheritDoc */
	public function onParserAfterParse( $parser, &$text, $stripState ) {
		return $this->container->run(
			'ParserAfterParse',
			[ $parser, &$text, $stripState ]
		);
	}

	/** @inheritDoc */
	public function onParserAfterTidy( $parser, &$text ) {
		return $this->container->run(
			'ParserAfterTidy',
			[ $parser, &$text ]
		);
	}

	/** @inheritDoc */
	public function onParserBeforeInternalParse( $parser, &$text, $stripState ) {
		return $this->container->run(
			'ParserBeforeInternalParse',
			[ $parser, &$text, $stripState ]
		);
	}

	/** @inheritDoc */
	public function onParserBeforePreprocess( $parser, &$text, $stripState ) {
		return $this->container->run(
			'ParserBeforePreprocess',
			[ $parser, &$text, $stripState ]
		);
	}

	/** @inheritDoc */
	public function onParserCacheSaveComplete( $parserCache, $parserOutput, $title,
		$popts, $revId
	) {
		return $this->container->run(
			'ParserCacheSaveComplete',
			[ $parserCache, $parserOutput, $title, $popts, $revId ]
		);
	}

	/** @inheritDoc */
	public function onParserClearState( $parser ) {
		return $this->container->run(
			'ParserClearState',
			[ $parser ]
		);
	}

	/** @inheritDoc */
	public function onParserCloned( $parser ) {
		return $this->container->run(
			'ParserCloned',
			[ $parser ]
		);
	}

	/** @inheritDoc */
	public function onParserFetchTemplateData( array $titles, array &$tplData ): bool {
		return $this->container->run(
			'ParserFetchTemplateData',
			[ $titles, &$tplData ]
		);
	}

	/** @inheritDoc */
	public function onParserFirstCallInit( $parser ) {
		return $this->container->run(
			'ParserFirstCallInit',
			[ $parser ]
		);
	}

	/** @inheritDoc */
	public function onParserGetVariableValueSwitch( $parser, &$variableCache,
		$magicWordId, &$ret, $frame
	) {
		return $this->container->run(
			'ParserGetVariableValueSwitch',
			[ $parser, &$variableCache, $magicWordId, &$ret, $frame ]
		);
	}

	/** @inheritDoc */
	public function onParserGetVariableValueTs( $parser, &$time ) {
		return $this->container->run(
			'ParserGetVariableValueTs',
			[ $parser, &$time ]
		);
	}

	/** @inheritDoc */
	public function onParserLimitReportFormat( $key, &$value, &$report, $isHTML,
		$localize
	) {
		return $this->container->run(
			'ParserLimitReportFormat',
			[ $key, &$value, &$report, $isHTML, $localize ]
		);
	}

	/** @inheritDoc */
	public function onParserLimitReportPrepare( $parser, $output ) {
		return $this->container->run(
			'ParserLimitReportPrepare',
			[ $parser, $output ]
		);
	}

	/** @inheritDoc */
	public function onParserLogLinterData( string $title, int $revId, array $lints ): bool {
		return $this->container->run(
			'ParserLogLinterData',
			[ $title, $revId, $lints ]
		);
	}

	/** @inheritDoc */
	public function onParserMakeImageParams( $title, $file, &$params, $parser ) {
		return $this->container->run(
			'ParserMakeImageParams',
			[ $title, $file, &$params, $parser ]
		);
	}

	/** @inheritDoc */
	public function onParserModifyImageHTML( Parser $parser, File $file,
		array $params, string &$html
	): void {
		$this->container->run(
			'ParserModifyImageHTML',
			[ $parser, $file, $params, &$html ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onParserOptionsRegister( &$defaults, &$inCacheKey, &$lazyLoad ) {
		return $this->container->run(
			'ParserOptionsRegister',
			[ &$defaults, &$inCacheKey, &$lazyLoad ]
		);
	}

	/** @inheritDoc */
	public function onParserOutputPostCacheTransform( $parserOutput, &$text,
		&$options
	): void {
		$this->container->run(
			'ParserOutputPostCacheTransform',
			[ $parserOutput, &$text, &$options ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onParserOutputStashForEdit( $page, $content, $output, $summary,
		$user
	) {
		return $this->container->run(
			'ParserOutputStashForEdit',
			[ $page, $content, $output, $summary, $user ]
		);
	}

	/** @inheritDoc */
	public function onParserPreSaveTransformComplete( $parser, &$text ) {
		return $this->container->run(
			'ParserPreSaveTransformComplete',
			[ $parser, &$text ]
		);
	}

	/** @inheritDoc */
	public function onParserTestGlobals( &$globals ) {
		return $this->container->run(
			'ParserTestGlobals',
			[ &$globals ]
		);
	}

	/** @inheritDoc */
	public function onPasswordPoliciesForUser( $user, &$effectivePolicy ) {
		return $this->container->run(
			'PasswordPoliciesForUser',
			[ $user, &$effectivePolicy ]
		);
	}

	/** @inheritDoc */
	public function onPerformRetroactiveAutoblock( $block, &$blockIds ) {
		return $this->container->run(
			'PerformRetroactiveAutoblock',
			[ $block, &$blockIds ]
		);
	}

	/** @inheritDoc */
	public function onPingLimiter( $user, $action, &$result, $incrBy ) {
		return $this->container->run(
			'PingLimiter',
			[ $user, $action, &$result, $incrBy ]
		);
	}

	/** @inheritDoc */
	public function onPlaceNewSection( $content, $oldtext, $subject, &$text ) {
		return $this->container->run(
			'PlaceNewSection',
			[ $content, $oldtext, $subject, &$text ]
		);
	}

	/** @inheritDoc */
	public function onPostLoginRedirect( &$returnTo, &$returnToQuery, &$type ) {
		return $this->container->run(
			'PostLoginRedirect',
			[ &$returnTo, &$returnToQuery, &$type ]
		);
	}

	/** @inheritDoc */
	public function onPreferencesFormPreSave( $formData, $form, $user, &$result,
		$oldUserOptions
	) {
		return $this->container->run(
			'PreferencesFormPreSave',
			[ $formData, $form, $user, &$result, $oldUserOptions ]
		);
	}

	/** @inheritDoc */
	public function onPreferencesGetIcon( &$iconNames ) {
		return $this->container->run(
			'PreferencesGetIcon',
			[ &$iconNames ]
		);
	}

	/** @inheritDoc */
	public function onPreferencesGetLayout( &$useMobileLayout, $skinName,
		$skinProperties = []
	) {
		return $this->container->run(
			'PreferencesGetLayout',
			[ &$useMobileLayout, $skinName, $skinProperties ]
		);
	}

	/** @inheritDoc */
	public function onPreferencesGetLegend( $form, $key, &$legend ) {
		return $this->container->run(
			'PreferencesGetLegend',
			[ $form, $key, &$legend ]
		);
	}

	/** @inheritDoc */
	public function onPrefixSearchBackend( $ns, $search, $limit, &$results,
		$offset
	) {
		return $this->container->run(
			'PrefixSearchBackend',
			[ $ns, $search, $limit, &$results, $offset ]
		);
	}

	/** @inheritDoc */
	public function onPrefixSearchExtractNamespace( &$namespaces, &$search ) {
		return $this->container->run(
			'PrefixSearchExtractNamespace',
			[ &$namespaces, &$search ]
		);
	}

	/** @inheritDoc */
	public function onPrefsEmailAudit( $user, $oldaddr, $newaddr ) {
		return $this->container->run(
			'PrefsEmailAudit',
			[ $user, $oldaddr, $newaddr ]
		);
	}

	/** @inheritDoc */
	public function onProtectionForm__buildForm( $article, &$output ) {
		return $this->container->run(
			'ProtectionForm::buildForm',
			[ $article, &$output ]
		);
	}

	/** @inheritDoc */
	public function onProtectionFormAddFormFields( $article, &$hookFormOptions ) {
		return $this->container->run(
			'ProtectionFormAddFormFields',
			[ $article, &$hookFormOptions ]
		);
	}

	/** @inheritDoc */
	public function onProtectionForm__save( $article, &$errorMsg, $reasonstr ) {
		return $this->container->run(
			'ProtectionForm::save',
			[ $article, &$errorMsg, $reasonstr ]
		);
	}

	/** @inheritDoc */
	public function onProtectionForm__showLogExtract( $article, $out ) {
		return $this->container->run(
			'ProtectionForm::showLogExtract',
			[ $article, $out ]
		);
	}

	/** @inheritDoc */
	public function onRandomPageQuery( &$tables, &$conds, &$joinConds ) {
		return $this->container->run(
			'RandomPageQuery',
			[ &$tables, &$conds, &$joinConds ]
		);
	}

	/** @inheritDoc */
	public function onRawPageViewBeforeOutput( $obj, &$text ) {
		return $this->container->run(
			'RawPageViewBeforeOutput',
			[ $obj, &$text ]
		);
	}

	/** @inheritDoc */
	public function onRecentChangesPurgeRows( $rows ): void {
		$this->container->run(
			'RecentChangesPurgeRows',
			[ $rows ]
		);
	}

	/** @inheritDoc */
	public function onRecentChangesPurgeQuery( $query, &$callbacks ): void {
		$this->container->run(
			'RecentChangesPurgeQuery',
			[ $query, &$callbacks ]
		);
	}

	/** @inheritDoc */
	public function onRecentChange_save( $recentChange ) {
		return $this->container->run(
			'RecentChange_save',
			[ $recentChange ]
		);
	}

	/** @inheritDoc */
	public function onRedirectSpecialArticleRedirectParams( &$redirectParams ) {
		return $this->container->run(
			'RedirectSpecialArticleRedirectParams',
			[ &$redirectParams ]
		);
	}

	/** @inheritDoc */
	public function onRejectParserCacheValue( $parserOutput, $wikiPage,
		$parserOptions
	) {
		return $this->container->run(
			'RejectParserCacheValue',
			[ $parserOutput, $wikiPage, $parserOptions ]
		);
	}

	/** @inheritDoc */
	public function onRenameUserAbort( int $uid, string $old, string $new ) {
		return $this->container->run(
			'RenameUserAbort',
			[ $uid, $old, $new ]
		);
	}

	/** @inheritDoc */
	public function onRenameUserComplete( int $uid, string $old, string $new ): void {
		$this->container->run(
			'RenameUserComplete',
			[ $uid, $old, $new ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onRenameUserPreRename( int $uid, string $old, string $new ): void {
		$this->container->run(
			'RenameUserPreRename',
			[ $uid, $old, $new ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onRenameUserSQL( RenameuserSQL $renameUserSql ): void {
		$this->container->run(
			'RenameUserSQL',
			[ $renameUserSql ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onRenameUserWarning( string $oldUsername, string $newUsername, array &$warnings ): void {
		$this->container->run(
			'RenameUserWarning',
			[ $oldUsername, $newUsername, &$warnings ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onRequestContextCreateSkin( $context, &$skin ) {
		return $this->container->run(
			'RequestContextCreateSkin',
			[ $context, &$skin ]
		);
	}

	/** @inheritDoc */
	public function onResetPasswordExpiration( $user, &$newExpire ) {
		return $this->container->run(
			'ResetPasswordExpiration',
			[ $user, &$newExpire ]
		);
	}

	/** @inheritDoc */
	public function onRevisionDataUpdates( $title, $renderedRevision, &$updates ) {
		return $this->container->run(
			'RevisionDataUpdates',
			[ $title, $renderedRevision, &$updates ]
		);
	}

	/** @inheritDoc */
	public function onRevisionFromEditComplete( $wikiPage, $rev, $originalRevId, $user, &$tags ) {
		return $this->container->run(
			'RevisionFromEditComplete',
			[ $wikiPage, $rev, $originalRevId, $user, &$tags ]
		);
	}

	/** @inheritDoc */
	public function onRevisionRecordInserted( $revisionRecord ) {
		return $this->container->run(
			'RevisionRecordInserted',
			[ $revisionRecord ]
		);
	}

	/** @inheritDoc */
	public function onRevisionUndeleted( $revisionRecord, $oldPageID ) {
		return $this->container->run(
			'RevisionUndeleted',
			[ $revisionRecord, $oldPageID ]
		);
	}

	/** @inheritDoc */
	public function onRollbackComplete( $wikiPage, $user, $revision, $current ) {
		return $this->container->run(
			'RollbackComplete',
			[ $wikiPage, $user, $revision, $current ]
		);
	}

	/** @inheritDoc */
	public function onSearchableNamespaces( &$arr ) {
		return $this->container->run(
			'SearchableNamespaces',
			[ &$arr ]
		);
	}

	/** @inheritDoc */
	public function onSearchAfterNoDirectMatch( $term, &$title ) {
		return $this->container->run(
			'SearchAfterNoDirectMatch',
			[ $term, &$title ]
		);
	}

	/** @inheritDoc */
	public function onSearchDataForIndex( &$fields, $handler, $page, $output, $engine ) {
		return $this->container->run(
			'SearchDataForIndex',
			[ &$fields, $handler, $page, $output, $engine ]
		);
	}

	/** @inheritDoc */
	public function onSearchDataForIndex2( array &$fields, ContentHandler $handler,
		WikiPage $page, ParserOutput $output, SearchEngine $engine, RevisionRecord $revision
	) {
		return $this->container->run(
			'SearchDataForIndex2',
			[ &$fields, $handler, $page, $output, $engine, $revision ]
		);
	}

	/** @inheritDoc */
	public function onSearchGetNearMatch( $term, &$title ) {
		return $this->container->run(
			'SearchGetNearMatch',
			[ $term, &$title ]
		);
	}

	/** @inheritDoc */
	public function onSearchGetNearMatchBefore( $allSearchTerms, &$titleResult ) {
		return $this->container->run(
			'SearchGetNearMatchBefore',
			[ $allSearchTerms, &$titleResult ]
		);
	}

	/** @inheritDoc */
	public function onSearchGetNearMatchComplete( $term, &$title ) {
		return $this->container->run(
			'SearchGetNearMatchComplete',
			[ $term, &$title ]
		);
	}

	/** @inheritDoc */
	public function onSearchIndexFields( &$fields, $engine ) {
		return $this->container->run(
			'SearchIndexFields',
			[ &$fields, $engine ]
		);
	}

	/** @inheritDoc */
	public function onSearchResultInitFromTitle( $title, &$id ) {
		return $this->container->run(
			'SearchResultInitFromTitle',
			[ $title, &$id ]
		);
	}

	/** @inheritDoc */
	public function onSearchResultProvideDescription( array $pageIdentities, &$descriptions ) {
		return $this->container->run(
			'SearchResultProvideDescription',
			[ $pageIdentities, &$descriptions ]
		);
	}

	/** @inheritDoc */
	public function onSearchResultProvideThumbnail( array $pageIdentities, &$thumbnails, ?int $size = null ) {
		return $this->container->run(
			'SearchResultProvideThumbnail',
			[ $pageIdentities, &$thumbnails, $size ]
		);
	}

	/** @inheritDoc */
	public function onSearchResultsAugment( &$setAugmentors, &$rowAugmentors ) {
		return $this->container->run(
			'SearchResultsAugment',
			[ &$setAugmentors, &$rowAugmentors ]
		);
	}

	/** @inheritDoc */
	public function onSecuritySensitiveOperationStatus( &$status, $operation,
		$session, $timeSinceAuth
	) {
		return $this->container->run(
			'SecuritySensitiveOperationStatus',
			[ &$status, $operation, $session, $timeSinceAuth ]
		);
	}

	/** @inheritDoc */
	public function onSelfLinkBegin( $nt, &$html, &$trail, &$prefix, &$ret ) {
		return $this->container->run(
			'SelfLinkBegin',
			[ $nt, &$html, &$trail, &$prefix, &$ret ]
		);
	}

	/** @inheritDoc */
	public function onSendWatchlistEmailNotification( $targetUser, $title, $enotif ) {
		return $this->container->run(
			'SendWatchlistEmailNotification',
			[ $targetUser, $title, $enotif ]
		);
	}

	/** @inheritDoc */
	public function onSessionCheckInfo( &$reason, $info, $request, $metadata,
		$data
	) {
		return $this->container->run(
			'SessionCheckInfo',
			[ &$reason, $info, $request, $metadata, $data ]
		);
	}

	/** @inheritDoc */
	public function onSessionMetadata( $backend, &$metadata, $requests ) {
		return $this->container->run(
			'SessionMetadata',
			[ $backend, &$metadata, $requests ]
		);
	}

	/** @inheritDoc */
	public function onSetupAfterCache() {
		return $this->container->run(
			'SetupAfterCache',
			[]
		);
	}

	/** @inheritDoc */
	public function onShortPagesQuery( &$tables, &$conds, &$joinConds, &$options ) {
		return $this->container->run(
			'ShortPagesQuery',
			[ &$tables, &$conds, &$joinConds, &$options ]
		);
	}

	/** @inheritDoc */
	public function onShowMissingArticle( $article ) {
		return $this->container->run(
			'ShowMissingArticle',
			[ $article ]
		);
	}

	/** @inheritDoc */
	public function onShowSearchHit( $searchPage, $result, $terms, &$link,
		&$redirect, &$section, &$extract, &$score, &$size, &$date, &$related, &$html
	) {
		return $this->container->run(
			'ShowSearchHit',
			[ $searchPage, $result, $terms, &$link, &$redirect, &$section,
				&$extract, &$score, &$size, &$date, &$related, &$html ]
		);
	}

	/** @inheritDoc */
	public function onShowSearchHitTitle( &$title, &$titleSnippet, $result, $terms,
		$specialSearch, &$query, &$attributes
	) {
		return $this->container->run(
			'ShowSearchHitTitle',
			[ &$title, &$titleSnippet, $result, $terms, $specialSearch,
				&$query, &$attributes ]
		);
	}

	/** @inheritDoc */
	public function onSidebarBeforeOutput( $skin, &$sidebar ): void {
		$this->container->run(
			'SidebarBeforeOutput',
			[ $skin, &$sidebar ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onSiteNoticeAfter( &$siteNotice, $skin ) {
		return $this->container->run(
			'SiteNoticeAfter',
			[ &$siteNotice, $skin ]
		);
	}

	/** @inheritDoc */
	public function onSiteNoticeBefore( &$siteNotice, $skin ) {
		return $this->container->run(
			'SiteNoticeBefore',
			[ &$siteNotice, $skin ]
		);
	}

	/** @inheritDoc */
	public function onSkinPageReadyConfig( RL\Context $context,
		array &$config
	) {
		$this->container->run(
			'SkinPageReadyConfig',
			[ $context, &$config ],
			[ 'abortable' => true ]
		);
	}

	/** @inheritDoc */
	public function onSkinAddFooterLinks( Skin $skin, string $key, array &$footerItems ) {
		$this->container->run(
			'SkinAddFooterLinks',
			[ $skin, $key, &$footerItems ]
		);
	}

	/** @inheritDoc */
	public function onSkinAfterBottomScripts( $skin, &$text ) {
		return $this->container->run(
			'SkinAfterBottomScripts',
			[ $skin, &$text ]
		);
	}

	/** @inheritDoc */
	public function onSkinAfterContent( &$data, $skin ) {
		return $this->container->run(
			'SkinAfterContent',
			[ &$data, $skin ]
		);
	}

	/** @inheritDoc */
	public function onSkinAfterPortlet( $skin, $portlet, &$html ) {
		return $this->container->run(
			'SkinAfterPortlet',
			[ $skin, $portlet, &$html ]
		);
	}

	/** @inheritDoc */
	public function onSkinBuildSidebar( $skin, &$bar ) {
		return $this->container->run(
			'SkinBuildSidebar',
			[ $skin, &$bar ]
		);
	}

	/** @inheritDoc */
	public function onSkinCopyrightFooterMessage( $title, $type, &$msg ) {
		return $this->container->run(
			'SkinCopyrightFooterMessage',
			[ $title, $type, &$msg ]
		);
	}

	/** @inheritDoc */
	public function onSkinEditSectionLinks( $skin, $title, $section, $tooltip,
		&$result, $lang
	) {
		return $this->container->run(
			'SkinEditSectionLinks',
			[ $skin, $title, $section, $tooltip, &$result, $lang ]
		);
	}

	/** @inheritDoc */
	public function onSkinPreloadExistence( &$titles, $skin ) {
		return $this->container->run(
			'SkinPreloadExistence',
			[ &$titles, $skin ]
		);
	}

	/** @inheritDoc */
	public function onSkinSubPageSubtitle( &$subpages, $skin, $out ) {
		return $this->container->run(
			'SkinSubPageSubtitle',
			[ &$subpages, $skin, $out ]
		);
	}

	/** @inheritDoc */
	public function onSkinTemplateGetLanguageLink( &$languageLink,
		$languageLinkTitle, $title, $outputPage
	) {
		return $this->container->run(
			'SkinTemplateGetLanguageLink',
			[ &$languageLink, $languageLinkTitle, $title, $outputPage ]
		);
	}

	/** @inheritDoc */
	public function onSkinTemplateNavigation__Universal( $sktemplate, &$links ): void {
		$this->container->run(
			'SkinTemplateNavigation::Universal',
			[ $sktemplate, &$links ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onSoftwareInfo( &$software ) {
		return $this->container->run(
			'SoftwareInfo',
			[ &$software ]
		);
	}

	/** @inheritDoc */
	public function onSpecialBlockModifyFormFields( $sp, &$fields ) {
		return $this->container->run(
			'SpecialBlockModifyFormFields',
			[ $sp, &$fields ]
		);
	}

	/** @inheritDoc */
	public function onSpecialContributionsBeforeMainOutput( $id, $user, $sp ) {
		return $this->container->run(
			'SpecialContributionsBeforeMainOutput',
			[ $id, $user, $sp ]
		);
	}

	/** @inheritDoc */
	public function onSpecialContributions__formatRow__flags( $context, $row,
		&$flags
	) {
		return $this->container->run(
			'SpecialContributions::formatRow::flags',
			[ $context, $row, &$flags ]
		);
	}

	/** @inheritDoc */
	public function onSpecialContributions__getForm__filters( $sp, &$filters ) {
		return $this->container->run(
			'SpecialContributions::getForm::filters',
			[ $sp, &$filters ]
		);
	}

	/** @inheritDoc */
	public function onSpecialCreateAccountBenefits( ?string &$html, array $info, array &$options ) {
		return $this->container->run(
			'SpecialCreateAccountBenefits',
			[ &$html, $info, &$options ]
		);
	}

	/** @inheritDoc */
	public function onSpecialExportGetExtraPages( $inputPages, &$extraPages ) {
		return $this->container->run(
			'SpecialExportGetExtraPages',
			[ $inputPages, &$extraPages ]
		);
	}

	/** @inheritDoc */
	public function onSpecialListusersDefaultQuery( $pager, &$query ) {
		return $this->container->run(
			'SpecialListusersDefaultQuery',
			[ $pager, &$query ]
		);
	}

	/** @inheritDoc */
	public function onSpecialListusersFormatRow( &$item, $row ) {
		return $this->container->run(
			'SpecialListusersFormatRow',
			[ &$item, $row ]
		);
	}

	/** @inheritDoc */
	public function onSpecialListusersHeader( $pager, &$out ) {
		return $this->container->run(
			'SpecialListusersHeader',
			[ $pager, &$out ]
		);
	}

	/** @inheritDoc */
	public function onSpecialListusersHeaderForm( $pager, &$out ) {
		return $this->container->run(
			'SpecialListusersHeaderForm',
			[ $pager, &$out ]
		);
	}

	/** @inheritDoc */
	public function onSpecialListusersQueryInfo( $pager, &$query ) {
		return $this->container->run(
			'SpecialListusersQueryInfo',
			[ $pager, &$query ]
		);
	}

	/** @inheritDoc */
	public function onSpecialLogAddLogSearchRelations( $type, $request, &$qc ) {
		return $this->container->run(
			'SpecialLogAddLogSearchRelations',
			[ $type, $request, &$qc ]
		);
	}

	/** @inheritDoc */
	public function onSpecialLogResolveLogType(
		array $params,
		string &$type
	): void {
		$this->container->run(
			'SpecialLogResolveLogType',
			[ $params, &$type ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onSpecialMovepageAfterMove( $movePage, $oldTitle, $newTitle ) {
		return $this->container->run(
			'SpecialMovepageAfterMove',
			[ $movePage, $oldTitle, $newTitle ]
		);
	}

	/** @inheritDoc */
	public function onSpecialMuteModifyFormFields( $target, $user, &$fields ) {
		return $this->container->run(
			'SpecialMuteModifyFormFields',
			[ $target, $user, &$fields ]
		);
	}

	/** @inheritDoc */
	public function onSpecialNewpagesConditions( $special, $opts, &$conds,
		&$tables, &$fields, &$join_conds
	) {
		return $this->container->run(
			'SpecialNewpagesConditions',
			[ $special, $opts, &$conds, &$tables, &$fields, &$join_conds ]
		);
	}

	/** @inheritDoc */
	public function onSpecialNewPagesFilters( $special, &$filters ) {
		return $this->container->run(
			'SpecialNewPagesFilters',
			[ $special, &$filters ]
		);
	}

	/** @inheritDoc */
	public function onSpecialPageAfterExecute( $special, $subPage ) {
		return $this->container->run(
			'SpecialPageAfterExecute',
			[ $special, $subPage ]
		);
	}

	/** @inheritDoc */
	public function onSpecialPageBeforeExecute( $special, $subPage ) {
		return $this->container->run(
			'SpecialPageBeforeExecute',
			[ $special, $subPage ]
		);
	}

	/** @inheritDoc */
	public function onSpecialPageBeforeFormDisplay( $name, $form ) {
		return $this->container->run(
			'SpecialPageBeforeFormDisplay',
			[ $name, $form ]
		);
	}

	/** @inheritDoc */
	public function onSpecialPage_initList( &$list ) {
		return $this->container->run(
			'SpecialPage_initList',
			[ &$list ]
		);
	}

	/** @inheritDoc */
	public function onSpecialPasswordResetOnSubmit( &$users, $data, &$error ) {
		return $this->container->run(
			'SpecialPasswordResetOnSubmit',
			[ &$users, $data, &$error ]
		);
	}

	/** @inheritDoc */
	public function onSpecialPrefixIndexGetFormFilters( IContextSource $contextSource, array &$filters ) {
		$this->container->run(
			'SpecialPrefixIndexGetFormFilters',
			[ $contextSource, &$filters ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onSpecialPrefixIndexQuery( array $fieldData, SelectQueryBuilder $queryBuilder ) {
		$this->container->run(
			'SpecialPrefixIndexQuery',
			[ $fieldData, $queryBuilder ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onSpecialRandomGetRandomTitle( &$randstr, &$isRedir,
		&$namespaces, &$extra, &$title
	) {
		return $this->container->run(
			'SpecialRandomGetRandomTitle',
			[ &$randstr, &$isRedir, &$namespaces, &$extra, &$title ]
		);
	}

	/** @inheritDoc */
	public function onSpecialRecentChangesPanel( &$extraOpts, $opts ) {
		return $this->container->run(
			'SpecialRecentChangesPanel',
			[ &$extraOpts, $opts ]
		);
	}

	/** @inheritDoc */
	public function onSpecialResetTokensTokens( &$tokens ) {
		return $this->container->run(
			'SpecialResetTokensTokens',
			[ &$tokens ]
		);
	}

	/** @inheritDoc */
	public function onSpecialSearchCreateLink( $t, &$params ) {
		return $this->container->run(
			'SpecialSearchCreateLink',
			[ $t, &$params ]
		);
	}

	/** @inheritDoc */
	public function onSpecialSearchGoResult( $term, $title, &$url ) {
		return $this->container->run(
			'SpecialSearchGoResult',
			[ $term, $title, &$url ]
		);
	}

	/** @inheritDoc */
	public function onSpecialSearchNogomatch( &$title ) {
		return $this->container->run(
			'SpecialSearchNogomatch',
			[ &$title ]
		);
	}

	/** @inheritDoc */
	public function onSpecialSearchPowerBox( &$showSections, $term, &$opts ) {
		return $this->container->run(
			'SpecialSearchPowerBox',
			[ &$showSections, $term, &$opts ]
		);
	}

	/** @inheritDoc */
	public function onSpecialSearchProfileForm( $search, &$form, $profile, $term,
		$opts
	) {
		return $this->container->run(
			'SpecialSearchProfileForm',
			[ $search, &$form, $profile, $term, $opts ]
		);
	}

	/** @inheritDoc */
	public function onSpecialSearchProfiles( &$profiles ) {
		return $this->container->run(
			'SpecialSearchProfiles',
			[ &$profiles ]
		);
	}

	/** @inheritDoc */
	public function onSpecialSearchResults( $term, &$titleMatches, &$textMatches ) {
		return $this->container->run(
			'SpecialSearchResults',
			[ $term, &$titleMatches, &$textMatches ]
		);
	}

	/** @inheritDoc */
	public function onSpecialSearchResultsAppend( $specialSearch, $output, $term ) {
		return $this->container->run(
			'SpecialSearchResultsAppend',
			[ $specialSearch, $output, $term ]
		);
	}

	/** @inheritDoc */
	public function onSpecialSearchResultsPrepend( $specialSearch, $output, $term ) {
		return $this->container->run(
			'SpecialSearchResultsPrepend',
			[ $specialSearch, $output, $term ]
		);
	}

	/** @inheritDoc */
	public function onSpecialSearchSetupEngine( $search, $profile, $engine ) {
		return $this->container->run(
			'SpecialSearchSetupEngine',
			[ $search, $profile, $engine ]
		);
	}

	/** @inheritDoc */
	public function onSpecialStatsAddExtra( &$extraStats, $context ) {
		return $this->container->run(
			'SpecialStatsAddExtra',
			[ &$extraStats, $context ]
		);
	}

	/** @inheritDoc */
	public function onSpecialTrackingCategories__generateCatLink( $specialPage,
		$catTitle, &$html
	) {
		return $this->container->run(
			'SpecialTrackingCategories::generateCatLink',
			[ $specialPage, $catTitle, &$html ]
		);
	}

	/** @inheritDoc */
	public function onSpecialTrackingCategories__preprocess( $specialPage,
		$trackingCategories
	) {
		return $this->container->run(
			'SpecialTrackingCategories::preprocess',
			[ $specialPage, $trackingCategories ]
		);
	}

	/** @inheritDoc */
	public function onSpecialUploadComplete( $form ) {
		return $this->container->run(
			'SpecialUploadComplete',
			[ $form ]
		);
	}

	/** @inheritDoc */
	public function onSpecialUserRightsChangeableGroups(
		Authority $authority,
		UserIdentity $target,
		array $addableGroups,
		array &$restrictedGroups
	): void {
		$this->container->run(
			'SpecialUserRightsChangeableGroups',
			[ $authority, $target, $addableGroups, &$restrictedGroups ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onSpecialVersionVersionUrl( $version, &$versionUrl ) {
		return $this->container->run(
			'SpecialVersionVersionUrl',
			[ $version, &$versionUrl ]
		);
	}

	/** @inheritDoc */
	public function onSpreadAnyEditBlock( $user, bool &$blockWasSpread ) {
		return $this->container->run(
			'SpreadAnyEditBlock',
			[ $user, &$blockWasSpread ]
		);
	}

	/** @inheritDoc */
	public function onSpecialWhatLinksHereQuery( $table, $data, $queryBuilder ): void {
		$this->container->run(
			'SpecialWhatLinksHereQuery',
			[ $table, $data, $queryBuilder ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onTempUserCreatedRedirect(
		Session $session,
		UserIdentity $user,
		string $returnTo,
		string $returnToQuery,
		string $returnToAnchor,
		&$redirectUrl
	) {
		return $this->container->run(
			'TempUserCreatedRedirect',
			[ $session, $user, $returnTo, $returnToQuery, $returnToAnchor, &$redirectUrl ]
		);
	}

	/** @inheritDoc */
	public function onTestCanonicalRedirect( $request, $title, $output ) {
		return $this->container->run(
			'TestCanonicalRedirect',
			[ $request, $title, $output ]
		);
	}

	/** @inheritDoc */
	public function onThumbnailBeforeProduceHTML( $thumbnail, &$attribs,
		&$linkAttribs
	) {
		return $this->container->run(
			'ThumbnailBeforeProduceHTML',
			[ $thumbnail, &$attribs, &$linkAttribs ]
		);
	}

	/** @inheritDoc */
	public function onTitleExists( $title, &$exists ) {
		return $this->container->run(
			'TitleExists',
			[ $title, &$exists ]
		);
	}

	/** @inheritDoc */
	public function onTitleGetEditNotices( $title, $oldid, &$notices ) {
		return $this->container->run(
			'TitleGetEditNotices',
			[ $title, $oldid, &$notices ]
		);
	}

	/** @inheritDoc */
	public function onTitleGetRestrictionTypes( $title, &$types ) {
		return $this->container->run(
			'TitleGetRestrictionTypes',
			[ $title, &$types ]
		);
	}

	/** @inheritDoc */
	public function onTitleIsAlwaysKnown( $title, &$isKnown ) {
		return $this->container->run(
			'TitleIsAlwaysKnown',
			[ $title, &$isKnown ]
		);
	}

	/** @inheritDoc */
	public function onTitleIsMovable( $title, &$result ) {
		return $this->container->run(
			'TitleIsMovable',
			[ $title, &$result ]
		);
	}

	/** @inheritDoc */
	public function onTitleMove( $old, $nt, $user, $reason, &$status ) {
		return $this->container->run(
			'TitleMove',
			[ $old, $nt, $user, $reason, &$status ]
		);
	}

	/** @inheritDoc */
	public function onTitleMoveStarting( $old, $nt, $user ) {
		return $this->container->run(
			'TitleMoveStarting',
			[ $old, $nt, $user ]
		);
	}

	/** @inheritDoc */
	public function onTitleQuickPermissions( $title, $user, $action, &$errors,
		$doExpensiveQueries, $short
	) {
		return $this->container->run(
			'TitleQuickPermissions',
			[ $title, $user, $action, &$errors, $doExpensiveQueries, $short ]
		);
	}

	/** @inheritDoc */
	public function onTitleReadWhitelist( $title, $user, &$whitelisted ) {
		return $this->container->run(
			'TitleReadWhitelist',
			[ $title, $user, &$whitelisted ]
		);
	}

	/** @inheritDoc */
	public function onTitleSquidURLs( $title, &$urls ) {
		return $this->container->run(
			'TitleSquidURLs',
			[ $title, &$urls ]
		);
	}

	/** @inheritDoc */
	public function onUnblockUser( $block, $user, &$reason ) {
		return $this->container->run(
			'UnblockUser',
			[ $block, $user, &$reason ]
		);
	}

	/** @inheritDoc */
	public function onUnblockUserComplete( $block, $user ) {
		return $this->container->run(
			'UnblockUserComplete',
			[ $block, $user ]
		);
	}

	/** @inheritDoc */
	public function onUndeleteForm__showHistory( &$archive, $title ) {
		return $this->container->run(
			'UndeleteForm::showHistory',
			[ &$archive, $title ]
		);
	}

	/** @inheritDoc */
	public function onUndeleteForm__showRevision( &$archive, $title ) {
		return $this->container->run(
			'UndeleteForm::showRevision',
			[ &$archive, $title ]
		);
	}

	/** @inheritDoc */
	public function onUndeletePageToolLinks( IContextSource $context, LinkRenderer $linkRenderer, array &$links ) {
		return $this->container->run(
			'UndeletePageToolLinks',
			[ $context, $linkRenderer, &$links ]
		);
	}

	/** @inheritDoc */
	public function onUnitTestsAfterDatabaseSetup( $database, $prefix ) {
		return $this->container->run(
			'UnitTestsAfterDatabaseSetup',
			[ $database, $prefix ]
		);
	}

	/** @inheritDoc */
	public function onUnitTestsBeforeDatabaseTeardown() {
		return $this->container->run(
			'UnitTestsBeforeDatabaseTeardown',
			[]
		);
	}

	/** @inheritDoc */
	public function onUnitTestsList( &$paths ) {
		return $this->container->run(
			'UnitTestsList',
			[ &$paths ]
		);
	}

	/** @inheritDoc */
	public function onUnwatchArticle( $user, $page, &$status ) {
		return $this->container->run(
			'UnwatchArticle',
			[ $user, $page, &$status ]
		);
	}

	/** @inheritDoc */
	public function onUnwatchArticleComplete( $user, $page ) {
		return $this->container->run(
			'UnwatchArticleComplete',
			[ $user, $page ]
		);
	}

	/** @inheritDoc */
	public function onUpdateUserMailerFormattedPageStatus( &$formattedPageStatus ) {
		return $this->container->run(
			'UpdateUserMailerFormattedPageStatus',
			[ &$formattedPageStatus ]
		);
	}

	/** @inheritDoc */
	public function onUploadComplete( $uploadBase ) {
		return $this->container->run(
			'UploadComplete',
			[ $uploadBase ]
		);
	}

	/** @inheritDoc */
	public function onUploadCreateFromRequest( $type, &$className ) {
		return $this->container->run(
			'UploadCreateFromRequest',
			[ $type, &$className ]
		);
	}

	/** @inheritDoc */
	public function onUploadFormInitDescriptor( &$descriptor ) {
		return $this->container->run(
			'UploadFormInitDescriptor',
			[ &$descriptor ]
		);
	}

	/** @inheritDoc */
	public function onUploadFormSourceDescriptors( &$descriptor, &$radio,
		$selectedSourceType
	) {
		return $this->container->run(
			'UploadFormSourceDescriptors',
			[ &$descriptor, &$radio, $selectedSourceType ]
		);
	}

	/** @inheritDoc */
	public function onUploadForm_BeforeProcessing( $upload ) {
		return $this->container->run(
			'UploadForm:BeforeProcessing',
			[ $upload ]
		);
	}

	/** @inheritDoc */
	public function onUploadForm_getInitialPageText( &$pageText, $msg, $config ) {
		return $this->container->run(
			'UploadForm:getInitialPageText',
			[ &$pageText, $msg, $config ]
		);
	}

	/** @inheritDoc */
	public function onUploadForm_initial( $upload ) {
		return $this->container->run(
			'UploadForm:initial',
			[ $upload ]
		);
	}

	/** @inheritDoc */
	public function onUploadStashFile( $upload, $user, $props, &$error ) {
		return $this->container->run(
			'UploadStashFile',
			[ $upload, $user, $props, &$error ]
		);
	}

	/** @inheritDoc */
	public function onUploadVerifyFile( $upload, $mime, &$error ) {
		return $this->container->run(
			'UploadVerifyFile',
			[ $upload, $mime, &$error ]
		);
	}

	/** @inheritDoc */
	public function onUploadVerifyUpload( $upload, $user, $props, $comment,
		$pageText, &$error
	) {
		return $this->container->run(
			'UploadVerifyUpload',
			[ $upload, $user, $props, $comment, $pageText, &$error ]
		);
	}

	/** @inheritDoc */
	public function onUserAddGroup( $user, &$group, &$expiry ) {
		return $this->container->run(
			'UserAddGroup',
			[ $user, &$group, &$expiry ]
		);
	}

	/** @inheritDoc */
	public function onUserArrayFromResult( &$userArray, $res ) {
		return $this->container->run(
			'UserArrayFromResult',
			[ &$userArray, $res ]
		);
	}

	/** @inheritDoc */
	public function onUserCan( $title, $user, $action, &$result ) {
		return $this->container->run(
			'userCan',
			[ $title, $user, $action, &$result ]
		);
	}

	/** @inheritDoc */
	public function onUserCanChangeEmail( $user, $oldaddr, $newaddr, &$status ) {
		return $this->container->run(
			'UserCanChangeEmail',
			[ $user, $oldaddr, $newaddr, &$status ]
		);
	}

	/** @inheritDoc */
	public function onUserCanSendEmail( $user, &$hookErr ) {
		return $this->container->run(
			'UserCanSendEmail',
			[ $user, &$hookErr ]
		);
	}

	/** @inheritDoc */
	public function onUserClearNewTalkNotification( $userIdentity, $oldid ) {
		return $this->container->run(
			'UserClearNewTalkNotification',
			[ $userIdentity, $oldid ]
		);
	}

	/** @inheritDoc */
	public function onUserEditCountUpdate( $infos ): void {
		$this->container->run(
			'UserEditCountUpdate',
			[ $infos ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onUserEffectiveGroups( $user, &$groups ) {
		return $this->container->run(
			'UserEffectiveGroups',
			[ $user, &$groups ]
		);
	}

	/** @inheritDoc */
	public function onUserGetAllRights( &$rights ) {
		return $this->container->run(
			'UserGetAllRights',
			[ &$rights ]
		);
	}

	/** @inheritDoc */
	public function onUserGetDefaultOptions( &$defaultOptions ) {
		return $this->container->run(
			'UserGetDefaultOptions',
			[ &$defaultOptions ]
		);
	}

	/** @inheritDoc */
	public function onConditionalDefaultOptionsAddCondition( &$extraConditions ): void {
		$this->container->run(
			'ConditionalDefaultOptionsAddCondition',
			[ &$extraConditions ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onUserGetEmail( $user, &$email ) {
		return $this->container->run(
			'UserGetEmail',
			[ $user, &$email ]
		);
	}

	/** @inheritDoc */
	public function onUserGetEmailAuthenticationTimestamp( $user, &$timestamp ) {
		return $this->container->run(
			'UserGetEmailAuthenticationTimestamp',
			[ $user, &$timestamp ]
		);
	}

	/** @inheritDoc */
	public function onUserGetLanguageObject( $user, &$code, $context ) {
		return $this->container->run(
			'UserGetLanguageObject',
			[ $user, &$code, $context ]
		);
	}

	/** @inheritDoc */
	public function onUserPrivilegedGroups( $userIdentity, &$groups ) {
		return $this->container->run(
			'UserPrivilegedGroups',
			[ $userIdentity, &$groups ]
		);
	}

	/** @inheritDoc */
	public function onUserGetReservedNames( &$reservedUsernames ) {
		return $this->container->run(
			'UserGetReservedNames',
			[ &$reservedUsernames ]
		);
	}

	/** @inheritDoc */
	public function onUserGetRights( $user, &$rights ) {
		return $this->container->run(
			'UserGetRights',
			[ $user, &$rights ]
		);
	}

	/** @inheritDoc */
	public function onUserGetRightsRemove( $user, &$rights ) {
		return $this->container->run(
			'UserGetRightsRemove',
			[ $user, &$rights ]
		);
	}

	/** @inheritDoc */
	public function onUserGroupsChanged( $user, $added, $removed, $performer,
		$reason, $oldUGMs, $newUGMs
	) {
		return $this->container->run(
			'UserGroupsChanged',
			[ $user, $added, $removed, $performer, $reason, $oldUGMs,
				$newUGMs ]
		);
	}

	/** @inheritDoc */
	public function onUserIsBlockedFrom( $user, $title, &$blocked, &$allowUsertalk ) {
		return $this->container->run(
			'UserIsBlockedFrom',
			[ $user, $title, &$blocked, &$allowUsertalk ]
		);
	}

	/** @inheritDoc */
	public function onUserIsBlockedGlobally( $user, $ip, &$blocked, &$block ) {
		return $this->container->run(
			'UserIsBlockedGlobally',
			[ $user, $ip, &$blocked, &$block ]
		);
	}

	/** @inheritDoc */
	public function onUserIsBot( $user, &$isBot ) {
		return $this->container->run(
			'UserIsBot',
			[ $user, &$isBot ]
		);
	}

	/** @inheritDoc */
	public function onUserIsEveryoneAllowed( $right ) {
		return $this->container->run(
			'UserIsEveryoneAllowed',
			[ $right ]
		);
	}

	/** @inheritDoc */
	public function onUserIsLocked( $user, &$locked ) {
		return $this->container->run(
			'UserIsLocked',
			[ $user, &$locked ]
		);
	}

	/** @inheritDoc */
	public function onUserLinkRendererUserLinkPostRender(
		UserIdentity $targetUser, IContextSource $context, &$html, &$prefix, &$postfix
	) {
		return $this->container->run(
			'UserLinkRendererUserLinkPostRender',
			[ $targetUser, $context, &$html, &$prefix, &$postfix ]
		);
	}

	/** @inheritDoc */
	public function onUserLoadAfterLoadFromSession( $user ) {
		return $this->container->run(
			'UserLoadAfterLoadFromSession',
			[ $user ]
		);
	}

	/** @inheritDoc */
	public function onUserLoadDefaults( $user, $name ) {
		return $this->container->run(
			'UserLoadDefaults',
			[ $user, $name ]
		);
	}

	/** @inheritDoc */
	public function onLoadUserOptions( UserIdentity $user, array &$options ): void {
		$this->container->run(
			'LoadUserOptions',
			[ $user, &$options ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onUserLoggedIn( $user ) {
		return $this->container->run(
			'UserLoggedIn',
			[ $user ]
		);
	}

	/** @inheritDoc */
	public function onUserLoginComplete( $user, &$inject_html, $direct ) {
		return $this->container->run(
			'UserLoginComplete',
			[ $user, &$inject_html, $direct ]
		);
	}

	/** @inheritDoc */
	public function onUserLogout( $user ) {
		return $this->container->run(
			'UserLogout',
			[ $user ]
		);
	}

	/** @inheritDoc */
	public function onUserLogoutComplete( $user, &$inject_html, $oldName ) {
		return $this->container->run(
			'UserLogoutComplete',
			[ $user, &$inject_html, $oldName ]
		);
	}

	/** @inheritDoc */
	public function onUserMailerChangeReturnPath( $to, &$returnPath ) {
		return $this->container->run(
			'UserMailerChangeReturnPath',
			[ $to, &$returnPath ]
		);
	}

	/** @inheritDoc */
	public function onUserMailerSplitTo( &$to ) {
		return $this->container->run(
			'UserMailerSplitTo',
			[ &$to ]
		);
	}

	/** @inheritDoc */
	public function onUserMailerTransformContent( $to, $from, &$body, &$error ) {
		return $this->container->run(
			'UserMailerTransformContent',
			[ $to, $from, &$body, &$error ]
		);
	}

	/** @inheritDoc */
	public function onUserMailerTransformMessage( $to, $from, &$subject, &$headers,
		&$body, &$error
	) {
		return $this->container->run(
			'UserMailerTransformMessage',
			[ $to, $from, &$subject, &$headers, &$body, &$error ]
		);
	}

	/** @inheritDoc */
	public function onUserRemoveGroup( $user, &$group ) {
		return $this->container->run(
			'UserRemoveGroup',
			[ $user, &$group ]
		);
	}

	/** @inheritDoc */
	public function onUserRequirementsCondition( $type, array $args, UserIdentity $user,
		bool $isPerformingRequest, ?bool &$result
	): void {
		$this->container->run(
			'UserRequirementsCondition',
			[ $type, $args, $user, $isPerformingRequest, &$result ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onSaveUserOptions( UserIdentity $user, array &$modifiedOptions, array $originalOptions ) {
		return $this->container->run(
			'SaveUserOptions',
			[ $user, &$modifiedOptions, $originalOptions ]
		);
	}

	/** @inheritDoc */
	public function onLocalUserOptionsStoreSave( UserIdentity $user, array $oldOptions, array $newOptions ): void {
		$this->container->run(
			'LocalUserOptionsStoreSave',
			[ $user, $oldOptions, $newOptions ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onUserSaveSettings( $user ) {
		return $this->container->run(
			'UserSaveSettings',
			[ $user ]
		);
	}

	/** @inheritDoc */
	public function onUserSendConfirmationMail( $user, &$mail, $info ) {
		return $this->container->run(
			'UserSendConfirmationMail',
			[ $user, &$mail, $info ]
		);
	}

	/** @inheritDoc */
	public function onUserSetEmail( $user, &$email ) {
		return $this->container->run(
			'UserSetEmail',
			[ $user, &$email ]
		);
	}

	/** @inheritDoc */
	public function onUserSetEmailAuthenticationTimestamp( $user, &$timestamp ) {
		return $this->container->run(
			'UserSetEmailAuthenticationTimestamp',
			[ $user, &$timestamp ]
		);
	}

	/** @inheritDoc */
	public function onUsersPagerDoBatchLookups( $dbr, $userIds, &$cache, &$groups ) {
		return $this->container->run(
			'UsersPagerDoBatchLookups',
			[ $dbr, $userIds, &$cache, &$groups ]
		);
	}

	/** @inheritDoc */
	public function onUserToolLinksEdit( $userId, $userText, &$items ) {
		return $this->container->run(
			'UserToolLinksEdit',
			[ $userId, $userText, &$items ]
		);
	}

	/** @inheritDoc */
	public function onUser__mailPasswordInternal( $user, $ip, $u ) {
		return $this->container->run(
			'User::mailPasswordInternal',
			[ $user, $ip, $u ]
		);
	}

	/** @inheritDoc */
	public function onValidateExtendedMetadataCache( $timestamp, $file ) {
		return $this->container->run(
			'ValidateExtendedMetadataCache',
			[ $timestamp, $file ]
		);
	}

	/** @inheritDoc */
	public function onWantedPages__getQueryInfo( $wantedPages, &$query ) {
		return $this->container->run(
			'WantedPages::getQueryInfo',
			[ $wantedPages, &$query ]
		);
	}

	/** @inheritDoc */
	public function onWatchArticle( $user, $page, &$status, $expiry ) {
		return $this->container->run(
			'WatchArticle',
			[ $user, $page, &$status, $expiry ]
		);
	}

	/** @inheritDoc */
	public function onWatchArticleComplete( $user, $page ) {
		return $this->container->run(
			'WatchArticleComplete',
			[ $user, $page ]
		);
	}

	/** @inheritDoc */
	public function onWatchedItemQueryServiceExtensions( &$extensions,
		$watchedItemQueryService
	) {
		return $this->container->run(
			'WatchedItemQueryServiceExtensions',
			[ &$extensions, $watchedItemQueryService ]
		);
	}

	/** @inheritDoc */
	public function onWatchlistEditorBeforeFormRender( &$watchlistInfo ) {
		return $this->container->run(
			'WatchlistEditorBeforeFormRender',
			[ &$watchlistInfo ]
		);
	}

	/** @inheritDoc */
	public function onWatchlistEditorBuildRemoveLine( &$tools, $title, $redirect,
		$skin, &$link
	) {
		return $this->container->run(
			'WatchlistEditorBuildRemoveLine',
			[ &$tools, $title, $redirect, $skin, &$link ]
		);
	}

	/** @inheritDoc */
	public function onWebRequestPathInfoRouter( $router ) {
		return $this->container->run(
			'WebRequestPathInfoRouter',
			[ $router ]
		);
	}

	/** @inheritDoc */
	public function onWebResponseSetCookie( &$name, &$value, &$expire, &$options ) {
		return $this->container->run(
			'WebResponseSetCookie',
			[ &$name, &$value, &$expire, &$options ]
		);
	}

	/** @inheritDoc */
	public function onWfShellWikiCmd( &$script, &$parameters, &$options ) {
		return $this->container->run(
			'wfShellWikiCmd',
			[ &$script, &$parameters, &$options ]
		);
	}

	/** @inheritDoc */
	public function onWgQueryPages( &$qp ) {
		return $this->container->run(
			'wgQueryPages',
			[ &$qp ]
		);
	}

	/** @inheritDoc */
	public function onWhatLinksHereProps( $row, $title, $target, &$props ) {
		return $this->container->run(
			'WhatLinksHereProps',
			[ $row, $title, $target, &$props ]
		);
	}

	/** @inheritDoc */
	public function onWikiExporter__dumpStableQuery( &$tables, &$opts, &$join ) {
		return $this->container->run(
			'WikiExporter::dumpStableQuery',
			[ &$tables, &$opts, &$join ]
		);
	}

	/** @inheritDoc */
	public function onWikiPageDeletionUpdates( $page, $content, &$updates ) {
		return $this->container->run(
			'WikiPageDeletionUpdates',
			[ $page, $content, &$updates ]
		);
	}

	/** @inheritDoc */
	public function onWikiPageFactory( $title, &$page ) {
		return $this->container->run(
			'WikiPageFactory',
			[ $title, &$page ]
		);
	}

	/** @inheritDoc */
	public function onXmlDumpWriterOpenPage( $obj, &$out, $row, $title ) {
		return $this->container->run(
			'XmlDumpWriterOpenPage',
			[ $obj, &$out, $row, $title ]
		);
	}

	/** @inheritDoc */
	public function onXmlDumpWriterWriteRevision( $obj, &$out, $row, $text, $rev ) {
		return $this->container->run(
			'XmlDumpWriterWriteRevision',
			[ $obj, &$out, $row, $text, $rev ]
		);
	}
}
