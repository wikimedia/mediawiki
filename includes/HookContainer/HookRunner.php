<?php

namespace MediaWiki\HookContainer;

use Article;
use Config;
use File;
use IContextSource;
use JsonContent;
use ManualLogEntry;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Session\Session;
use MediaWiki\User\UserIdentity;
use Parser;
use ParserOptions;
use Skin;
use SpecialPage;
use StatusValue;
use Title;

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
	\MediaWiki\Auth\Hook\AuthManagerLoginAuthenticateAuditHook,
	\MediaWiki\Auth\Hook\ExemptFromAccountCreationThrottleHook,
	\MediaWiki\Auth\Hook\LocalUserCreatedHook,
	\MediaWiki\Auth\Hook\ResetPasswordExpirationHook,
	\MediaWiki\Auth\Hook\SecuritySensitiveOperationStatusHook,
	\MediaWiki\Auth\Hook\UserLoggedInHook,
	\MediaWiki\Block\Hook\AbortAutoblockHook,
	\MediaWiki\Block\Hook\GetAllBlockActionsHook,
	\MediaWiki\Block\Hook\GetUserBlockHook,
	\MediaWiki\Block\Hook\PerformRetroactiveAutoblockHook,
	\MediaWiki\Cache\Hook\BacklinkCacheGetConditionsHook,
	\MediaWiki\Cache\Hook\BacklinkCacheGetPrefixHook,
	\MediaWiki\Cache\Hook\HtmlCacheUpdaterAppendUrlsHook,
	\MediaWiki\Cache\Hook\HtmlCacheUpdaterVaryUrlsHook,
	\MediaWiki\Cache\Hook\HTMLFileCache__useFileCacheHook,
	\MediaWiki\Cache\Hook\MessageCacheReplaceHook,
	\MediaWiki\Cache\Hook\MessageCache__getHook,
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
	\MediaWiki\Diff\Hook\AbortDiffCacheHook,
	\MediaWiki\Diff\Hook\ArticleContentOnDiffHook,
	\MediaWiki\Diff\Hook\DifferenceEngineAfterLoadNewTextHook,
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
	\MediaWiki\Hook\AfterBuildFeedLinksHook,
	\MediaWiki\Hook\AfterFinalPageOutputHook,
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
	\MediaWiki\Hook\BaseTemplateAfterPortletHook,
	\MediaWiki\Hook\BeforeInitializeHook,
	\MediaWiki\Hook\BeforePageDisplayHook,
	\MediaWiki\Hook\BeforePageRedirectHook,
	\MediaWiki\Hook\BeforeParserFetchFileAndTitleHook,
	\MediaWiki\Hook\BeforeParserFetchTemplateAndtitleHook,
	\MediaWiki\Hook\BeforeParserFetchTemplateRevisionRecordHook,
	\MediaWiki\Hook\BeforeParserrenderImageGalleryHook,
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
	\MediaWiki\Hook\GetCacheVaryCookiesHook,
	\MediaWiki\Hook\GetCanonicalURLHook,
	\MediaWiki\Hook\GetDefaultSortkeyHook,
	\MediaWiki\Hook\GetDoubleUnderscoreIDsHook,
	\MediaWiki\Hook\GetExtendedMetadataHook,
	\MediaWiki\Hook\GetFullURLHook,
	\MediaWiki\Hook\GetHumanTimestampHook,
	\MediaWiki\Hook\GetInternalURLHook,
	\MediaWiki\Hook\GetIPHook,
	\MediaWiki\Hook\GetLangPreferredVariantHook,
	\MediaWiki\Hook\GetLinkColoursHook,
	\MediaWiki\Hook\GetLocalURLHook,
	\MediaWiki\Hook\GetLocalURL__ArticleHook,
	\MediaWiki\Hook\GetLocalURL__InternalHook,
	\MediaWiki\Hook\GetLogTypesOnUserHook,
	\MediaWiki\Hook\GetMagicVariableIDsHook,
	\MediaWiki\Hook\GetMetadataVersionHook,
	\MediaWiki\Hook\GetNewMessagesAlertHook,
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
	\MediaWiki\Hook\InternalParseBeforeSanitizeHook,
	\MediaWiki\Hook\IRCLineURLHook,
	\MediaWiki\Hook\IsTrustedProxyHook,
	\MediaWiki\Hook\IsUploadAllowedFromUrlHook,
	\MediaWiki\Hook\IsValidEmailAddrHook,
	\MediaWiki\Hook\LanguageGetNamespacesHook,
	\MediaWiki\Hook\LanguageLinksHook,
	\MediaWiki\Hook\LanguageSelectorHook,
	\MediaWiki\Hook\LinkerMakeExternalImageHook,
	\MediaWiki\Hook\LinkerMakeExternalLinkHook,
	\MediaWiki\Hook\LinkerMakeMediaLinkFileHook,
	\MediaWiki\Hook\LinksUpdateAfterInsertHook,
	\MediaWiki\Hook\LinksUpdateCompleteHook,
	\MediaWiki\Hook\LinksUpdateConstructedHook,
	\MediaWiki\Hook\LinksUpdateHook,
	\MediaWiki\Hook\LocalFilePurgeThumbnailsHook,
	\MediaWiki\Hook\LocalFile__getHistoryHook,
	\MediaWiki\Hook\LocalisationCacheRecacheFallbackHook,
	\MediaWiki\Hook\LocalisationCacheRecacheHook,
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
	\MediaWiki\Hook\MakeGlobalVariablesScriptHook,
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
	\MediaWiki\Hook\OutputPageAfterGetHeadLinksArrayHook,
	\MediaWiki\Hook\OutputPageBeforeHTMLHook,
	\MediaWiki\Hook\OutputPageBodyAttributesHook,
	\MediaWiki\Hook\OutputPageCheckLastModifiedHook,
	\MediaWiki\Hook\OutputPageMakeCategoryLinksHook,
	\MediaWiki\Hook\OutputPageParserOutputHook,
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
	\MediaWiki\Hook\ParserModifyImageHTML,
	\MediaWiki\Hook\ParserOptionsRegisterHook,
	\MediaWiki\Hook\ParserOutputPostCacheTransformHook,
	\MediaWiki\Hook\ParserPreSaveTransformCompleteHook,
	\MediaWiki\Hook\ParserSectionCreateHook,
	\MediaWiki\Hook\ParserTestGlobalsHook,
	\MediaWiki\Hook\ParserTestTablesHook,
	\MediaWiki\Hook\PasswordPoliciesForUserHook,
	\MediaWiki\Hook\PersonalUrlsHook,
	\MediaWiki\Hook\PostLoginRedirectHook,
	\MediaWiki\Hook\PreferencesGetLegendHook,
	\MediaWiki\Hook\PrefsEmailAuditHook,
	\MediaWiki\Hook\ProtectionForm__buildFormHook,
	\MediaWiki\Hook\ProtectionForm__saveHook,
	\MediaWiki\Hook\ProtectionForm__showLogExtractHook,
	\MediaWiki\Hook\ProtectionFormAddFormFieldsHook,
	\MediaWiki\Hook\RandomPageQueryHook,
	\MediaWiki\Hook\RawPageViewBeforeOutputHook,
	\MediaWiki\Hook\RecentChangesPurgeRowsHook,
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
	\MediaWiki\Hook\SkinCopyrightFooterHook,
	\MediaWiki\Hook\SkinEditSectionLinksHook,
	\MediaWiki\Hook\SkinPreloadExistenceHook,
	\MediaWiki\Hook\SkinSubPageSubtitleHook,
	\MediaWiki\Hook\SkinTemplateGetLanguageLinkHook,
	\MediaWiki\Hook\SkinTemplateNavigationHook,
	\MediaWiki\Hook\SkinTemplateNavigation__SpecialPageHook,
	\MediaWiki\Hook\SkinTemplateNavigation__UniversalHook,
	\MediaWiki\Hook\SoftwareInfoHook,
	\MediaWiki\Hook\SpecialBlockModifyFormFieldsHook,
	\MediaWiki\Hook\SpecialContributionsBeforeMainOutputHook,
	\MediaWiki\Hook\SpecialContributions__formatRow__flagsHook,
	\MediaWiki\Hook\SpecialExportGetExtraPagesHook,
	\MediaWiki\Hook\SpecialContributions__getForm__filtersHook,
	\MediaWiki\Hook\SpecialListusersDefaultQueryHook,
	\MediaWiki\Hook\SpecialListusersFormatRowHook,
	\MediaWiki\Hook\SpecialListusersHeaderFormHook,
	\MediaWiki\Hook\SpecialListusersHeaderHook,
	\MediaWiki\Hook\SpecialListusersQueryInfoHook,
	\MediaWiki\Hook\SpecialLogAddLogSearchRelationsHook,
	\MediaWiki\Hook\SpecialMovepageAfterMoveHook,
	\MediaWiki\Hook\SpecialMuteModifyFormFieldsHook,
	\MediaWiki\Hook\SpecialMuteSubmitHook,
	\MediaWiki\Hook\SpecialNewpagesConditionsHook,
	\MediaWiki\Hook\SpecialNewPagesFiltersHook,
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
	\MediaWiki\Hook\SpecialVersionVersionUrlHook,
	\MediaWiki\Hook\SpecialWatchlistGetNonRevisionTypesHook,
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
	\MediaWiki\Languages\Hook\LanguageGetTranslatedLanguageNamesHook,
	\MediaWiki\Languages\Hook\Language__getMessagesFileNameHook,
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
	\MediaWiki\Page\Hook\PageUndeleteHook,
	\MediaWiki\Page\Hook\PageViewUpdatesHook,
	\MediaWiki\Page\Hook\RevisionFromEditCompleteHook,
	\MediaWiki\Page\Hook\RevisionUndeletedHook,
	\MediaWiki\Page\Hook\RollbackCompleteHook,
	\MediaWiki\Page\Hook\ShowMissingArticleHook,
	\MediaWiki\Page\Hook\WikiPageDeletionUpdatesHook,
	\MediaWiki\Page\Hook\WikiPageFactoryHook,
	\MediaWiki\Permissions\Hook\PermissionErrorAuditHook,
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
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderGetConfigVarsHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderJqueryMsgModuleMagicWordsHook,
	\MediaWiki\Rest\Hook\SearchResultProvideDescriptionHook,
	\MediaWiki\Rest\Hook\SearchResultProvideThumbnailHook,
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
	\MediaWiki\Search\Hook\SearchResultsAugmentHook,
	\MediaWiki\Search\Hook\ShowSearchHitHook,
	\MediaWiki\Search\Hook\ShowSearchHitTitleHook,
	\MediaWiki\Search\Hook\SpecialSearchPowerBoxHook,
	\MediaWiki\Search\Hook\SpecialSearchProfileFormHook,
	\MediaWiki\Session\Hook\SessionCheckInfoHook,
	\MediaWiki\Session\Hook\SessionMetadataHook,
	\MediaWiki\Session\Hook\UserSetCookiesHook,
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
	\MediaWiki\User\Hook\UserLoadFromDatabaseHook,
	\MediaWiki\User\Hook\UserLogoutHook,
	\MediaWiki\User\Hook\UserRemoveGroupHook,
	\MediaWiki\User\Hook\UserSaveSettingsHook,
	\MediaWiki\User\Hook\UserSendConfirmationMailHook,
	\MediaWiki\User\Hook\UserSetEmailAuthenticationTimestampHook,
	\MediaWiki\User\Hook\UserSetEmailHook,
	\MediaWiki\User\Hook\User__mailPasswordInternalHook,
	\MediaWiki\User\Options\Hook\LoadUserOptionsHook,
	\MediaWiki\User\Options\Hook\SaveUserOptionsHook
{
	/** @var HookContainer */
	private $container;

	public function __construct( HookContainer $container ) {
		$this->container = $container;
	}

	public function onAbortAutoblock( $autoblockip, $block ) {
		return $this->container->run(
			'AbortAutoblock',
			[ $autoblockip, $block ]
		);
	}

	public function onAbortDiffCache( $diffEngine ) {
		return $this->container->run(
			'AbortDiffCache',
			[ $diffEngine ]
		);
	}

	public function onAbortEmailNotification( $editor, $title, $rc ) {
		return $this->container->run(
			'AbortEmailNotification',
			[ $editor, $title, $rc ]
		);
	}

	public function onAbortTalkPageEmailNotification( $targetUser, $title ) {
		return $this->container->run(
			'AbortTalkPageEmailNotification',
			[ $targetUser, $title ]
		);
	}

	public function onActionBeforeFormDisplay( $name, $form, $article ) {
		return $this->container->run(
			'ActionBeforeFormDisplay',
			[ $name, $form, $article ]
		);
	}

	public function onActionModifyFormFields( $name, &$fields, $article ) {
		return $this->container->run(
			'ActionModifyFormFields',
			[ $name, &$fields, $article ]
		);
	}

	public function onAddNewAccount( $user, $byEmail ) {
		return $this->container->run(
			'AddNewAccount',
			[ $user, $byEmail ]
		);
	}

	public function onAfterBuildFeedLinks( &$feedLinks ) {
		return $this->container->run(
			'AfterBuildFeedLinks',
			[ &$feedLinks ]
		);
	}

	public function onAfterFinalPageOutput( $output ): void {
		$this->container->run(
			'AfterFinalPageOutput',
			[ $output ],
			[ 'abortable' => false ]
		);
	}

	public function onAfterImportPage( $title, $foreignTitle, $revCount,
		$sRevCount, $pageInfo
	) {
		return $this->container->run(
			'AfterImportPage',
			[ $title, $foreignTitle, $revCount, $sRevCount, $pageInfo ]
		);
	}

	public function onAfterParserFetchFileAndTitle( $parser, $ig, &$html ) {
		return $this->container->run(
			'AfterParserFetchFileAndTitle',
			[ $parser, $ig, &$html ]
		);
	}

	public function onAlternateEdit( $editPage ) {
		return $this->container->run(
			'AlternateEdit',
			[ $editPage ]
		);
	}

	public function onAlternateEditPreview( $editPage, &$content, &$previewHTML,
		&$parserOutput
	) {
		return $this->container->run(
			'AlternateEditPreview',
			[ $editPage, &$content, &$previewHTML, &$parserOutput ]
		);
	}

	public function onAlternateUserMailer( $headers, $to, $from, $subject, $body ) {
		return $this->container->run(
			'AlternateUserMailer',
			[ $headers, $to, $from, $subject, $body ]
		);
	}

	public function onAncientPagesQuery( &$tables, &$conds, &$joinConds ) {
		return $this->container->run(
			'AncientPagesQuery',
			[ &$tables, &$conds, &$joinConds ]
		);
	}

	public function onApiBeforeMain( &$main ) {
		return $this->container->run(
			'ApiBeforeMain',
			[ &$main ]
		);
	}

	public function onArticleConfirmDelete( $article, $output, &$reason ) {
		return $this->container->run(
			'ArticleConfirmDelete',
			[ $article, $output, &$reason ]
		);
	}

	public function onArticleContentOnDiff( $diffEngine, $output ) {
		return $this->container->run(
			'ArticleContentOnDiff',
			[ $diffEngine, $output ]
		);
	}

	public function onArticleDelete( $wikiPage, $user, &$reason, &$error, &$status,
		$suppress
	) {
		return $this->container->run(
			'ArticleDelete',
			[ $wikiPage, $user, &$reason, &$error, &$status, $suppress ]
		);
	}

	public function onArticleDeleteAfterSuccess( $title, $outputPage ) {
		return $this->container->run(
			'ArticleDeleteAfterSuccess',
			[ $title, $outputPage ]
		);
	}

	public function onArticleDeleteComplete( $wikiPage, $user, $reason, $id,
		$content, $logEntry, $archivedRevisionCount
	) {
		return $this->container->run(
			'ArticleDeleteComplete',
			[ $wikiPage, $user, $reason, $id, $content, $logEntry,
				$archivedRevisionCount ]
		);
	}

	public function onArticleEditUpdateNewTalk( $wikiPage, $recipient ) {
		return $this->container->run(
			'ArticleEditUpdateNewTalk',
			[ $wikiPage, $recipient ]
		);
	}

	public function onArticleFromTitle( $title, &$article, $context ) {
		return $this->container->run(
			'ArticleFromTitle',
			[ $title, &$article, $context ]
		);
	}

	public function onArticleMergeComplete( $targetTitle, $destTitle ) {
		return $this->container->run(
			'ArticleMergeComplete',
			[ $targetTitle, $destTitle ]
		);
	}

	public function onArticlePageDataAfter( $wikiPage, &$row ) {
		return $this->container->run(
			'ArticlePageDataAfter',
			[ $wikiPage, &$row ]
		);
	}

	public function onArticlePageDataBefore( $wikiPage, &$fields, &$tables,
		&$joinConds
	) {
		return $this->container->run(
			'ArticlePageDataBefore',
			[ $wikiPage, &$fields, &$tables, &$joinConds ]
		);
	}

	public function onArticleParserOptions( Article $article, ParserOptions $popts ) {
		return $this->container->run(
			'ArticleParserOptions',
			[ $article, $popts ]
		);
	}

	public function onArticlePrepareTextForEdit( $wikiPage, $popts ) {
		return $this->container->run(
			'ArticlePrepareTextForEdit',
			[ $wikiPage, $popts ]
		);
	}

	public function onArticleProtect( $wikiPage, $user, $protect, $reason ) {
		return $this->container->run(
			'ArticleProtect',
			[ $wikiPage, $user, $protect, $reason ]
		);
	}

	public function onArticleProtectComplete( $wikiPage, $user, $protect, $reason ) {
		return $this->container->run(
			'ArticleProtectComplete',
			[ $wikiPage, $user, $protect, $reason ]
		);
	}

	public function onArticlePurge( $wikiPage ) {
		return $this->container->run(
			'ArticlePurge',
			[ $wikiPage ]
		);
	}

	public function onArticleRevisionViewCustom( $revision, $title, $oldid,
		$output
	) {
		return $this->container->run(
			'ArticleRevisionViewCustom',
			[ $revision, $title, $oldid, $output ]
		);
	}

	public function onArticleRevisionVisibilitySet( $title, $ids,
		$visibilityChangeMap
	) {
		return $this->container->run(
			'ArticleRevisionVisibilitySet',
			[ $title, $ids, $visibilityChangeMap ]
		);
	}

	public function onArticleShowPatrolFooter( $article ) {
		return $this->container->run(
			'ArticleShowPatrolFooter',
			[ $article ]
		);
	}

	public function onArticleUndelete( $title, $create, $comment, $oldPageId,
		$restoredPages
	) {
		return $this->container->run(
			'ArticleUndelete',
			[ $title, $create, $comment, $oldPageId, $restoredPages ]
		);
	}

	public function onArticleUpdateBeforeRedirect( $article, &$sectionanchor,
		&$extraq
	) {
		return $this->container->run(
			'ArticleUpdateBeforeRedirect',
			[ $article, &$sectionanchor, &$extraq ]
		);
	}

	public function onArticleViewFooter( $article, $patrolFooterShown ) {
		return $this->container->run(
			'ArticleViewFooter',
			[ $article, $patrolFooterShown ]
		);
	}

	public function onArticleViewHeader( $article, &$outputDone, &$pcache ) {
		return $this->container->run(
			'ArticleViewHeader',
			[ $article, &$outputDone, &$pcache ]
		);
	}

	public function onArticleViewRedirect( $article ) {
		return $this->container->run(
			'ArticleViewRedirect',
			[ $article ]
		);
	}

	public function onArticle__MissingArticleConditions( &$conds, $logTypes ) {
		return $this->container->run(
			'Article::MissingArticleConditions',
			[ &$conds, $logTypes ]
		);
	}

	public function onAuthChangeFormFields( $requests, $fieldInfo,
		&$formDescriptor, $action
	) {
		return $this->container->run(
			'AuthChangeFormFields',
			[ $requests, $fieldInfo, &$formDescriptor, $action ]
		);
	}

	public function onAuthManagerLoginAuthenticateAudit( $response, $user,
		$username, $extraData
	) {
		return $this->container->run(
			'AuthManagerLoginAuthenticateAudit',
			[ $response, $user, $username, $extraData ]
		);
	}

	public function onAutopromoteCondition( $type, $args, $user, &$result ) {
		return $this->container->run(
			'AutopromoteCondition',
			[ $type, $args, $user, &$result ]
		);
	}

	public function onBacklinkCacheGetConditions( $table, $title, &$conds ) {
		return $this->container->run(
			'BacklinkCacheGetConditions',
			[ $table, $title, &$conds ]
		);
	}

	public function onBacklinkCacheGetPrefix( $table, &$prefix ) {
		return $this->container->run(
			'BacklinkCacheGetPrefix',
			[ $table, &$prefix ]
		);
	}

	public function onBadImage( $name, &$bad ) {
		return $this->container->run(
			'BadImage',
			[ $name, &$bad ]
		);
	}

	public function onBaseTemplateAfterPortlet( $template, $portlet, &$html ) {
		return $this->container->run(
			'BaseTemplateAfterPortlet',
			[ $template, $portlet, &$html ]
		);
	}

	public function onBeforeDisplayNoArticleText( $article ) {
		return $this->container->run(
			'BeforeDisplayNoArticleText',
			[ $article ]
		);
	}

	public function onBeforeInitialize( $title, $unused, $output, $user, $request,
		$mediaWiki
	) {
		return $this->container->run(
			'BeforeInitialize',
			[ $title, $unused, $output, $user, $request, $mediaWiki ]
		);
	}

	public function onBeforePageDisplay( $out, $skin ): void {
		$this->container->run(
			'BeforePageDisplay',
			[ $out, $skin ],
			[ 'abortable' => false ]
		);
	}

	public function onBeforePageRedirect( $out, &$redirect, &$code ) {
		return $this->container->run(
			'BeforePageRedirect',
			[ $out, &$redirect, &$code ]
		);
	}

	public function onBeforeParserFetchFileAndTitle( $parser, $nt, &$options,
		&$descQuery
	) {
		return $this->container->run(
			'BeforeParserFetchFileAndTitle',
			[ $parser, $nt, &$options, &$descQuery ]
		);
	}

	public function onBeforeParserFetchTemplateAndtitle( $parser, $title, &$skip,
		&$id
	) {
		return $this->container->run(
			'BeforeParserFetchTemplateAndtitle',
			[ $parser, $title, &$skip, &$id ]
		);
	}

	public function onBeforeParserFetchTemplateRevisionRecord(
		?LinkTarget $contextTitle, LinkTarget $title,
		bool &$skip, ?RevisionRecord &$revRecord
	) {
		return $this->container->run(
			'BeforeParserFetchTemplateRevisionRecord',
			[ $contextTitle, $title, &$skip, &$revRecord ]
		);
	}

	public function onBeforeParserrenderImageGallery( $parser, $ig ) {
		return $this->container->run(
			'BeforeParserrenderImageGallery',
			[ $parser, $ig ]
		);
	}

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

	public function onBeforeWelcomeCreation( &$welcome_creation_msg,
		&$injected_html
	) {
		return $this->container->run(
			'BeforeWelcomeCreation',
			[ &$welcome_creation_msg, &$injected_html ]
		);
	}

	public function onBitmapHandlerCheckImageArea( $image, &$params,
		&$checkImageAreaHookResult
	) {
		return $this->container->run(
			'BitmapHandlerCheckImageArea',
			[ $image, &$params, &$checkImageAreaHookResult ]
		);
	}

	public function onBitmapHandlerTransform( $handler, $image, &$scalerParams,
		&$mto
	) {
		return $this->container->run(
			'BitmapHandlerTransform',
			[ $handler, $image, &$scalerParams, &$mto ]
		);
	}

	public function onBlockIp( $block, $user, &$reason ) {
		return $this->container->run(
			'BlockIp',
			[ $block, $user, &$reason ]
		);
	}

	public function onBlockIpComplete( $block, $user, $priorBlock ) {
		return $this->container->run(
			'BlockIpComplete',
			[ $block, $user, $priorBlock ]
		);
	}

	public function onBookInformation( $isbn, $output ) {
		return $this->container->run(
			'BookInformation',
			[ $isbn, $output ]
		);
	}

	public function onCanonicalNamespaces( &$namespaces ) {
		return $this->container->run(
			'CanonicalNamespaces',
			[ &$namespaces ]
		);
	}

	public function onCategoryAfterPageAdded( $category, $wikiPage ) {
		return $this->container->run(
			'CategoryAfterPageAdded',
			[ $category, $wikiPage ]
		);
	}

	public function onCategoryAfterPageRemoved( $category, $wikiPage, $id ) {
		return $this->container->run(
			'CategoryAfterPageRemoved',
			[ $category, $wikiPage, $id ]
		);
	}

	public function onCategoryPageView( $catpage ) {
		return $this->container->run(
			'CategoryPageView',
			[ $catpage ]
		);
	}

	public function onCategoryViewer__doCategoryQuery( $type, $res ) {
		return $this->container->run(
			'CategoryViewer::doCategoryQuery',
			[ $type, $res ]
		);
	}

	public function onCategoryViewer__generateLink( $type, $title, $html, &$link ) {
		return $this->container->run(
			'CategoryViewer::generateLink',
			[ $type, $title, $html, &$link ]
		);
	}

	public function onChangeAuthenticationDataAudit( $req, $status ) {
		return $this->container->run(
			'ChangeAuthenticationDataAudit',
			[ $req, $status ]
		);
	}

	public function onChangesListInitRows( $changesList, $rows ) {
		return $this->container->run(
			'ChangesListInitRows',
			[ $changesList, $rows ]
		);
	}

	public function onChangesListInsertArticleLink( $changesList, &$articlelink,
		&$s, $rc, $unpatrolled, $watched
	) {
		return $this->container->run(
			'ChangesListInsertArticleLink',
			[ $changesList, &$articlelink, &$s, $rc, $unpatrolled, $watched ]
		);
	}

	public function onChangesListSpecialPageQuery( $name, &$tables, &$fields,
		&$conds, &$query_options, &$join_conds, $opts
	) {
		return $this->container->run(
			'ChangesListSpecialPageQuery',
			[ $name, &$tables, &$fields, &$conds, &$query_options,
				&$join_conds, $opts ]
		);
	}

	public function onChangesListSpecialPageStructuredFilters( $special ) {
		return $this->container->run(
			'ChangesListSpecialPageStructuredFilters',
			[ $special ]
		);
	}

	public function onChangeTagAfterDelete( $tag, &$status ) {
		return $this->container->run(
			'ChangeTagAfterDelete',
			[ $tag, &$status ]
		);
	}

	public function onChangeTagCanCreate( $tag, $user, &$status ) {
		return $this->container->run(
			'ChangeTagCanCreate',
			[ $tag, $user, &$status ]
		);
	}

	public function onChangeTagCanDelete( $tag, $user, &$status ) {
		return $this->container->run(
			'ChangeTagCanDelete',
			[ $tag, $user, &$status ]
		);
	}

	public function onChangeTagsAfterUpdateTags( $addedTags, $removedTags,
		$prevTags, $rc_id, $rev_id, $log_id, $params, $rc, $user
	) {
		return $this->container->run(
			'ChangeTagsAfterUpdateTags',
			[ $addedTags, $removedTags, $prevTags, $rc_id, $rev_id, $log_id,
				$params, $rc, $user ]
		);
	}

	public function onChangeTagsAllowedAdd( &$allowedTags, $addTags, $user ) {
		return $this->container->run(
			'ChangeTagsAllowedAdd',
			[ &$allowedTags, $addTags, $user ]
		);
	}

	public function onChangeTagsListActive( &$tags ) {
		return $this->container->run(
			'ChangeTagsListActive',
			[ &$tags ]
		);
	}

	public function onChangeUserGroups( $performer, $user, &$add, &$remove ) {
		return $this->container->run(
			'ChangeUserGroups',
			[ $performer, $user, &$add, &$remove ]
		);
	}

	public function onCollation__factory( $collationName, &$collationObject ) {
		return $this->container->run(
			'Collation::factory',
			[ $collationName, &$collationObject ]
		);
	}

	public function onConfirmEmailComplete( $user ) {
		return $this->container->run(
			'ConfirmEmailComplete',
			[ $user ]
		);
	}

	public function onContentAlterParserOutput( $content, $title, $parserOutput ) {
		return $this->container->run(
			'ContentAlterParserOutput',
			[ $content, $title, $parserOutput ]
		);
	}

	public function onContentGetParserOutput( $content, $title, $revId, $options,
		$generateHtml, &$output
	) {
		return $this->container->run(
			'ContentGetParserOutput',
			[ $content, $title, $revId, $options, $generateHtml, &$output ]
		);
	}

	public function onContentHandlerDefaultModelFor( $title, &$model ) {
		return $this->container->run(
			'ContentHandlerDefaultModelFor',
			[ $title, &$model ]
		);
	}

	public function onContentHandlerForModelID( $modeName, &$handler ) {
		return $this->container->run(
			'ContentHandlerForModelID',
			[ $modeName, &$handler ]
		);
	}

	public function onContentModelCanBeUsedOn( $contentModel, $title, &$ok ) {
		return $this->container->run(
			'ContentModelCanBeUsedOn',
			[ $contentModel, $title, &$ok ]
		);
	}

	public function onContentSecurityPolicyDefaultSource( &$defaultSrc,
		$policyConfig, $mode
	) {
		return $this->container->run(
			'ContentSecurityPolicyDefaultSource',
			[ &$defaultSrc, $policyConfig, $mode ]
		);
	}

	public function onContentSecurityPolicyDirectives( &$directives, $policyConfig,
		$mode
	) {
		return $this->container->run(
			'ContentSecurityPolicyDirectives',
			[ &$directives, $policyConfig, $mode ]
		);
	}

	public function onContentSecurityPolicyScriptSource( &$scriptSrc,
		$policyConfig, $mode
	) {
		return $this->container->run(
			'ContentSecurityPolicyScriptSource',
			[ &$scriptSrc, $policyConfig, $mode ]
		);
	}

	public function onContribsPager__getQueryInfo( $pager, &$queryInfo ) {
		return $this->container->run(
			'ContribsPager::getQueryInfo',
			[ $pager, &$queryInfo ]
		);
	}

	public function onContribsPager__reallyDoQuery( &$data, $pager, $offset,
		$limit, $descending
	) {
		return $this->container->run(
			'ContribsPager::reallyDoQuery',
			[ &$data, $pager, $offset, $limit, $descending ]
		);
	}

	public function onContributionsLineEnding( $page, &$ret, $row, &$classes,
		&$attribs
	) {
		return $this->container->run(
			'ContributionsLineEnding',
			[ $page, &$ret, $row, &$classes, &$attribs ]
		);
	}

	public function onContributionsToolLinks( $id, Title $title, array &$tools, SpecialPage $specialPage ) {
		return $this->container->run(
			'ContributionsToolLinks',
			[ $id, $title, &$tools, $specialPage ]
		);
	}

	public function onConvertContent( $content, $toModel, $lossy, &$result ) {
		return $this->container->run(
			'ConvertContent',
			[ $content, $toModel, $lossy, &$result ]
		);
	}

	public function onCustomEditor( $article, $user ) {
		return $this->container->run(
			'CustomEditor',
			[ $article, $user ]
		);
	}

	public function onDeletedContribsPager__reallyDoQuery( &$data, $pager, $offset,
		$limit, $descending
	) {
		return $this->container->run(
			'DeletedContribsPager::reallyDoQuery',
			[ &$data, $pager, $offset, $limit, $descending ]
		);
	}

	public function onDeletedContributionsLineEnding( $page, &$ret, $row,
		&$classes, &$attribs
	) {
		return $this->container->run(
			'DeletedContributionsLineEnding',
			[ $page, &$ret, $row, &$classes, &$attribs ]
		);
	}

	public function onDeleteUnknownPreferences( &$where, $db ) {
		return $this->container->run(
			'DeleteUnknownPreferences',
			[ &$where, $db ]
		);
	}

	public function onDifferenceEngineAfterLoadNewText( $differenceEngine ) {
		return $this->container->run(
			'DifferenceEngineAfterLoadNewText',
			[ $differenceEngine ]
		);
	}

	public function onDifferenceEngineLoadTextAfterNewContentIsLoaded(
		$differenceEngine
	) {
		return $this->container->run(
			'DifferenceEngineLoadTextAfterNewContentIsLoaded',
			[ $differenceEngine ]
		);
	}

	public function onDifferenceEngineMarkPatrolledLink( $differenceEngine,
		&$markAsPatrolledLink, $rcid
	) {
		return $this->container->run(
			'DifferenceEngineMarkPatrolledLink',
			[ $differenceEngine, &$markAsPatrolledLink, $rcid ]
		);
	}

	public function onDifferenceEngineMarkPatrolledRCID( &$rcid, $differenceEngine,
		$change, $user
	) {
		return $this->container->run(
			'DifferenceEngineMarkPatrolledRCID',
			[ &$rcid, $differenceEngine, $change, $user ]
		);
	}

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

	public function onDifferenceEngineOldHeader( $differenceEngine, &$oldHeader,
		$prevlink, $oldminor, $diffOnly, $ldel, $unhide
	) {
		return $this->container->run(
			'DifferenceEngineOldHeader',
			[ $differenceEngine, &$oldHeader, $prevlink, $oldminor, $diffOnly,
				$ldel, $unhide ]
		);
	}

	public function onDifferenceEngineOldHeaderNoOldRev( &$oldHeader ) {
		return $this->container->run(
			'DifferenceEngineOldHeaderNoOldRev',
			[ &$oldHeader ]
		);
	}

	public function onDifferenceEngineRenderRevisionAddParserOutput(
		$differenceEngine, $out, $parserOutput, $wikiPage
	) {
		return $this->container->run(
			'DifferenceEngineRenderRevisionAddParserOutput',
			[ $differenceEngine, $out, $parserOutput, $wikiPage ]
		);
	}

	public function onDifferenceEngineRenderRevisionShowFinalPatrolLink() {
		return $this->container->run(
			'DifferenceEngineRenderRevisionShowFinalPatrolLink',
			[]
		);
	}

	public function onDifferenceEngineShowDiff( $differenceEngine ) {
		return $this->container->run(
			'DifferenceEngineShowDiff',
			[ $differenceEngine ]
		);
	}

	public function onDifferenceEngineShowDiffPage( $out ) {
		return $this->container->run(
			'DifferenceEngineShowDiffPage',
			[ $out ]
		);
	}

	public function onDifferenceEngineShowDiffPageMaybeShowMissingRevision(
		$differenceEngine
	) {
		return $this->container->run(
			'DifferenceEngineShowDiffPageMaybeShowMissingRevision',
			[ $differenceEngine ]
		);
	}

	public function onDifferenceEngineShowEmptyOldContent( $differenceEngine ) {
		return $this->container->run(
			'DifferenceEngineShowEmptyOldContent',
			[ $differenceEngine ]
		);
	}

	public function onDifferenceEngineViewHeader( $differenceEngine ) {
		return $this->container->run(
			'DifferenceEngineViewHeader',
			[ $differenceEngine ]
		);
	}

	public function onDiffTools( $newRevRecord, &$links, $oldRevRecord, $userIdentity ) {
		return $this->container->run(
			'DiffTools',
			[ $newRevRecord, &$links, $oldRevRecord, $userIdentity ]
		);
	}

	public function onDisplayOldSubtitle( $article, &$oldid ) {
		return $this->container->run(
			'DisplayOldSubtitle',
			[ $article, &$oldid ]
		);
	}

	public function onEditFilter( $editor, $text, $section, &$error, $summary ) {
		return $this->container->run(
			'EditFilter',
			[ $editor, $text, $section, &$error, $summary ]
		);
	}

	public function onEditFilterMergedContent( $context, $content, $status,
		$summary, $user, $minoredit
	) {
		return $this->container->run(
			'EditFilterMergedContent',
			[ $context, $content, $status, $summary, $user, $minoredit ]
		);
	}

	public function onEditFormInitialText( $editPage ) {
		return $this->container->run(
			'EditFormInitialText',
			[ $editPage ]
		);
	}

	public function onEditFormPreloadText( &$text, $title ) {
		return $this->container->run(
			'EditFormPreloadText',
			[ &$text, $title ]
		);
	}

	public function onEditPageBeforeConflictDiff( $editor, $out ) {
		return $this->container->run(
			'EditPageBeforeConflictDiff',
			[ $editor, $out ]
		);
	}

	public function onEditPageBeforeEditButtons( $editpage, &$buttons, &$tabindex ) {
		return $this->container->run(
			'EditPageBeforeEditButtons',
			[ $editpage, &$buttons, &$tabindex ]
		);
	}

	public function onEditPageBeforeEditToolbar( &$toolbar ) {
		return $this->container->run(
			'EditPageBeforeEditToolbar',
			[ &$toolbar ]
		);
	}

	public function onEditPageCopyrightWarning( $title, &$msg ) {
		return $this->container->run(
			'EditPageCopyrightWarning',
			[ $title, &$msg ]
		);
	}

	public function onEditPageGetCheckboxesDefinition( $editpage, &$checkboxes ) {
		return $this->container->run(
			'EditPageGetCheckboxesDefinition',
			[ $editpage, &$checkboxes ]
		);
	}

	public function onEditPageGetDiffContent( $editPage, &$newtext ) {
		return $this->container->run(
			'EditPageGetDiffContent',
			[ $editPage, &$newtext ]
		);
	}

	public function onEditPageGetPreviewContent( $editPage, &$content ) {
		return $this->container->run(
			'EditPageGetPreviewContent',
			[ $editPage, &$content ]
		);
	}

	public function onEditPageNoSuchSection( $editpage, &$res ) {
		return $this->container->run(
			'EditPageNoSuchSection',
			[ $editpage, &$res ]
		);
	}

	public function onEditPageTosSummary( $title, &$msg ) {
		return $this->container->run(
			'EditPageTosSummary',
			[ $title, &$msg ]
		);
	}

	public function onEditPage__attemptSave( $editpage_Obj ) {
		return $this->container->run(
			'EditPage::attemptSave',
			[ $editpage_Obj ]
		);
	}

	public function onEditPage__attemptSave_after( $editpage_Obj, $status,
		$resultDetails
	) {
		return $this->container->run(
			'EditPage::attemptSave:after',
			[ $editpage_Obj, $status, $resultDetails ]
		);
	}

	public function onEditPage__importFormData( $editpage, $request ) {
		return $this->container->run(
			'EditPage::importFormData',
			[ $editpage, $request ]
		);
	}

	public function onEditPage__showEditForm_fields( $editor, $out ) {
		return $this->container->run(
			'EditPage::showEditForm:fields',
			[ $editor, $out ]
		);
	}

	public function onEditPage__showEditForm_initial( $editor, $out ) {
		return $this->container->run(
			'EditPage::showEditForm:initial',
			[ $editor, $out ]
		);
	}

	public function onEditPage__showReadOnlyForm_initial( $editor, $out ) {
		return $this->container->run(
			'EditPage::showReadOnlyForm:initial',
			[ $editor, $out ]
		);
	}

	public function onEditPage__showStandardInputs_options( $editor, $out,
		&$tabindex
	) {
		return $this->container->run(
			'EditPage::showStandardInputs:options',
			[ $editor, $out, &$tabindex ]
		);
	}

	public function onEmailConfirmed( $user, &$confirmed ) {
		return $this->container->run(
			'EmailConfirmed',
			[ $user, &$confirmed ]
		);
	}

	public function onEmailUser( &$to, &$from, &$subject, &$text, &$error ) {
		return $this->container->run(
			'EmailUser',
			[ &$to, &$from, &$subject, &$text, &$error ]
		);
	}

	public function onEmailUserCC( &$to, &$from, &$subject, &$text ) {
		return $this->container->run(
			'EmailUserCC',
			[ &$to, &$from, &$subject, &$text ]
		);
	}

	public function onEmailUserComplete( $to, $from, $subject, $text ) {
		return $this->container->run(
			'EmailUserComplete',
			[ $to, $from, $subject, $text ]
		);
	}

	public function onEmailUserForm( &$form ) {
		return $this->container->run(
			'EmailUserForm',
			[ &$form ]
		);
	}

	public function onEmailUserPermissionsErrors( $user, $editToken, &$hookErr ) {
		return $this->container->run(
			'EmailUserPermissionsErrors',
			[ $user, $editToken, &$hookErr ]
		);
	}

	public function onEnhancedChangesListModifyBlockLineData( $changesList, &$data,
		$rc
	) {
		return $this->container->run(
			'EnhancedChangesListModifyBlockLineData',
			[ $changesList, &$data, $rc ]
		);
	}

	public function onEnhancedChangesListModifyLineData( $changesList, &$data,
		$block, $rc, &$classes, &$attribs
	) {
		return $this->container->run(
			'EnhancedChangesListModifyLineData',
			[ $changesList, &$data, $block, $rc, &$classes, &$attribs ]
		);
	}

	public function onEnhancedChangesList__getLogText( $changesList, &$links,
		$block
	) {
		return $this->container->run(
			'EnhancedChangesList::getLogText',
			[ $changesList, &$links, $block ]
		);
	}

	public function onExemptFromAccountCreationThrottle( $ip ) {
		return $this->container->run(
			'ExemptFromAccountCreationThrottle',
			[ $ip ]
		);
	}

	public function onExtensionTypes( &$extTypes ) {
		return $this->container->run(
			'ExtensionTypes',
			[ &$extTypes ]
		);
	}

	public function onFetchChangesList( $user, $skin, &$list, $groups ) {
		return $this->container->run(
			'FetchChangesList',
			[ $user, $skin, &$list, $groups ]
		);
	}

	public function onFileDeleteComplete( $file, $oldimage, $article, $user,
		$reason
	) {
		return $this->container->run(
			'FileDeleteComplete',
			[ $file, $oldimage, $article, $user, $reason ]
		);
	}

	public function onFileTransformed( $file, $thumb, $tmpThumbPath, $thumbPath ) {
		return $this->container->run(
			'FileTransformed',
			[ $file, $thumb, $tmpThumbPath, $thumbPath ]
		);
	}

	public function onFileUndeleteComplete( $title, $fileVersions, $user, $reason ) {
		return $this->container->run(
			'FileUndeleteComplete',
			[ $title, $fileVersions, $user, $reason ]
		);
	}

	public function onFileUpload( $file, $reupload, $hasDescription ) {
		return $this->container->run(
			'FileUpload',
			[ $file, $reupload, $hasDescription ]
		);
	}

	public function onFormatAutocomments( &$comment, $pre, $auto, $post, $title,
		$local, $wikiId
	) {
		return $this->container->run(
			'FormatAutocomments',
			[ &$comment, $pre, $auto, $post, $title, $local, $wikiId ]
		);
	}

	public function onGalleryGetModes( &$modeArray ) {
		return $this->container->run(
			'GalleryGetModes',
			[ &$modeArray ]
		);
	}

	public function onGetAllBlockActions( &$actions ) {
		return $this->container->run(
			'GetAllBlockActions',
			[ &$actions ],
			[ 'abortable' => false ]
		);
	}

	public function onGetAutoPromoteGroups( $user, &$promote ) {
		return $this->container->run(
			'GetAutoPromoteGroups',
			[ $user, &$promote ]
		);
	}

	public function onGetActionName( IContextSource $context, string &$action ): void {
		$this->container->run(
			'GetActionName',
			[ $context, &$action ],
			[ 'abortable' => false ]
		);
	}

	public function onGetCacheVaryCookies( $out, &$cookies ) {
		return $this->container->run(
			'GetCacheVaryCookies',
			[ $out, &$cookies ]
		);
	}

	public function onGetCanonicalURL( $title, &$url, $query ) {
		return $this->container->run(
			'GetCanonicalURL',
			[ $title, &$url, $query ]
		);
	}

	public function onGetContentModels( &$models ) {
		return $this->container->run(
			'GetContentModels',
			[ &$models ]
		);
	}

	public function onGetDefaultSortkey( $title, &$sortkey ) {
		return $this->container->run(
			'GetDefaultSortkey',
			[ $title, &$sortkey ]
		);
	}

	public function onGetDifferenceEngine( $context, $old, $new, $refreshCache,
		$unhide, &$differenceEngine
	) {
		return $this->container->run(
			'GetDifferenceEngine',
			[ $context, $old, $new, $refreshCache, $unhide,
				&$differenceEngine ]
		);
	}

	public function onGetDoubleUnderscoreIDs( &$doubleUnderscoreIDs ) {
		return $this->container->run(
			'GetDoubleUnderscoreIDs',
			[ &$doubleUnderscoreIDs ]
		);
	}

	public function onGetExtendedMetadata( &$combinedMeta, $file, $context,
		$single, &$maxCacheTime
	) {
		return $this->container->run(
			'GetExtendedMetadata',
			[ &$combinedMeta, $file, $context, $single, &$maxCacheTime ]
		);
	}

	public function onGetFullURL( $title, &$url, $query ) {
		return $this->container->run(
			'GetFullURL',
			[ $title, &$url, $query ]
		);
	}

	public function onGetHumanTimestamp( &$output, $timestamp, $relativeTo, $user,
		$lang
	) {
		return $this->container->run(
			'GetHumanTimestamp',
			[ &$output, $timestamp, $relativeTo, $user, $lang ]
		);
	}

	public function onGetInternalURL( $title, &$url, $query ) {
		return $this->container->run(
			'GetInternalURL',
			[ $title, &$url, $query ]
		);
	}

	public function onGetIP( &$ip ) {
		return $this->container->run(
			'GetIP',
			[ &$ip ]
		);
	}

	public function onGetLangPreferredVariant( &$req ) {
		return $this->container->run(
			'GetLangPreferredVariant',
			[ &$req ]
		);
	}

	public function onGetLinkColours( $linkcolour_ids, &$colours, $title ) {
		return $this->container->run(
			'GetLinkColours',
			[ $linkcolour_ids, &$colours, $title ]
		);
	}

	public function onGetLocalURL( $title, &$url, $query ) {
		return $this->container->run(
			'GetLocalURL',
			[ $title, &$url, $query ]
		);
	}

	public function onGetLocalURL__Article( $title, &$url ) {
		return $this->container->run(
			'GetLocalURL::Article',
			[ $title, &$url ]
		);
	}

	public function onGetLocalURL__Internal( $title, &$url, $query ) {
		return $this->container->run(
			'GetLocalURL::Internal',
			[ $title, &$url, $query ]
		);
	}

	public function onGetLogTypesOnUser( &$types ) {
		return $this->container->run(
			'GetLogTypesOnUser',
			[ &$types ]
		);
	}

	public function onGetMagicVariableIDs( &$variableIDs ) {
		return $this->container->run(
			'GetMagicVariableIDs',
			[ &$variableIDs ]
		);
	}

	public function onGetMetadataVersion( &$version ) {
		return $this->container->run(
			'GetMetadataVersion',
			[ &$version ]
		);
	}

	public function onGetNewMessagesAlert( &$newMessagesAlert, $newtalks, $user,
		$out
	) {
		return $this->container->run(
			'GetNewMessagesAlert',
			[ &$newMessagesAlert, $newtalks, $user, $out ]
		);
	}

	public function onGetPreferences( $user, &$preferences ) {
		return $this->container->run(
			'GetPreferences',
			[ $user, &$preferences ]
		);
	}

	public function onGetRelativeTimestamp( &$output, &$diff, $timestamp,
		$relativeTo, $user, $lang
	) {
		return $this->container->run(
			'GetRelativeTimestamp',
			[ &$output, &$diff, $timestamp, $relativeTo, $user, $lang ]
		);
	}

	public function onGetSlotDiffRenderer( $contentHandler, &$slotDiffRenderer,
		$context
	) {
		return $this->container->run(
			'GetSlotDiffRenderer',
			[ $contentHandler, &$slotDiffRenderer, $context ]
		);
	}

	public function onGetUserBlock( $user, $ip, &$block ) {
		return $this->container->run(
			'GetUserBlock',
			[ $user, $ip, &$block ]
		);
	}

	public function onPermissionErrorAudit(
		LinkTarget $title,
		UserIdentity $user,
		string $action,
		string $rigor,
		array $errors
	): void {
		$this->container->run(
			'PermissionErrorAudit',
			[ $title, $user, $action, $rigor, $errors ],
			[ 'abortable' => false ]
		);
	}

	public function onGetUserPermissionsErrors( $title, $user, $action, &$result ) {
		return $this->container->run(
			'getUserPermissionsErrors',
			[ $title, $user, $action, &$result ]
		);
	}

	public function onGetUserPermissionsErrorsExpensive( $title, $user, $action,
		&$result
	) {
		return $this->container->run(
			'getUserPermissionsErrorsExpensive',
			[ $title, $user, $action, &$result ]
		);
	}

	public function onGitViewers( &$extTypes ) {
		return $this->container->run(
			'GitViewers',
			[ &$extTypes ]
		);
	}

	public function onHistoryPageToolLinks( IContextSource $context, LinkRenderer $linkRenderer, array &$links ) {
		return $this->container->run(
			'HistoryPageToolLinks',
			[ $context, $linkRenderer, &$links ]
		);
	}

	public function onHistoryTools( $revRecord, &$links, $prevRevRecord, $userIdentity ) {
		return $this->container->run(
			'HistoryTools',
			[ $revRecord, &$links, $prevRevRecord, $userIdentity ]
		);
	}

	public function onHtmlCacheUpdaterAppendUrls( $title, $mode, &$append ) {
		return $this->container->run(
			'HtmlCacheUpdaterAppendUrls',
			[ $title, $mode, &$append ]
		);
	}

	public function onHtmlCacheUpdaterVaryUrls( $urls, &$append ) {
		return $this->container->run(
			'HtmlCacheUpdaterVaryUrls',
			[ $urls, &$append ]
		);
	}

	public function onHTMLFileCache__useFileCache( $context ) {
		return $this->container->run(
			'HTMLFileCache::useFileCache',
			[ $context ]
		);
	}

	public function onHtmlPageLinkRendererBegin( $linkRenderer, $target, &$text,
		&$customAttribs, &$query, &$ret
	) {
		return $this->container->run(
			'HtmlPageLinkRendererBegin',
			[ $linkRenderer, $target, &$text, &$customAttribs, &$query, &$ret ]
		);
	}

	public function onHtmlPageLinkRendererEnd( $linkRenderer, $target, $isKnown,
		&$text, &$attribs, &$ret
	) {
		return $this->container->run(
			'HtmlPageLinkRendererEnd',
			[ $linkRenderer, $target, $isKnown, &$text, &$attribs, &$ret ]
		);
	}

	public function onImageBeforeProduceHTML( $linker, &$title, &$file,
		&$frameParams, &$handlerParams, &$time, &$res, $parser, &$query, &$widthOption
	) {
		return $this->container->run(
			'ImageBeforeProduceHTML',
			[ $linker, &$title, &$file, &$frameParams, &$handlerParams, &$time,
				&$res, $parser, &$query, &$widthOption ]
		);
	}

	public function onImageOpenShowImageInlineBefore( $imagePage, $output ) {
		return $this->container->run(
			'ImageOpenShowImageInlineBefore',
			[ $imagePage, $output ]
		);
	}

	public function onImagePageAfterImageLinks( $imagePage, &$html ) {
		return $this->container->run(
			'ImagePageAfterImageLinks',
			[ $imagePage, &$html ]
		);
	}

	public function onImagePageFileHistoryLine( $imageHistoryList, $file, &$line, &$css ) {
		return $this->container->run(
			'ImagePageFileHistoryLine',
			[ $imageHistoryList, $file, &$line, &$css ]
		);
	}

	public function onImagePageFindFile( $page, &$file, &$displayFile ) {
		return $this->container->run(
			'ImagePageFindFile',
			[ $page, &$file, &$displayFile ]
		);
	}

	public function onImagePageShowTOC( $page, &$toc ) {
		return $this->container->run(
			'ImagePageShowTOC',
			[ $page, &$toc ]
		);
	}

	public function onImgAuthBeforeStream( &$title, &$path, &$name, &$result ) {
		return $this->container->run(
			'ImgAuthBeforeStream',
			[ &$title, &$path, &$name, &$result ]
		);
	}

	public function onImgAuthModifyHeaders( $title, &$headers ) {
		return $this->container->run(
			'ImgAuthModifyHeaders',
			[ $title, &$headers ]
		);
	}

	public function onImportHandleLogItemXMLTag( $reader, $logInfo ) {
		return $this->container->run(
			'ImportHandleLogItemXMLTag',
			[ $reader, $logInfo ]
		);
	}

	public function onImportHandlePageXMLTag( $reader, &$pageInfo ) {
		return $this->container->run(
			'ImportHandlePageXMLTag',
			[ $reader, &$pageInfo ]
		);
	}

	public function onImportHandleRevisionXMLTag( $reader, $pageInfo,
		$revisionInfo
	) {
		return $this->container->run(
			'ImportHandleRevisionXMLTag',
			[ $reader, $pageInfo, $revisionInfo ]
		);
	}

	public function onImportHandleContentXMLTag( $reader, $contentInfo ) {
		return $this->container->run(
			'ImportHandleContentXMLTag',
			[ $reader, $contentInfo ] );
	}

	public function onImportHandleToplevelXMLTag( $reader ) {
		return $this->container->run(
			'ImportHandleToplevelXMLTag',
			[ $reader ]
		);
	}

	public function onImportHandleUnknownUser( $name ) {
		return $this->container->run(
			'ImportHandleUnknownUser',
			[ $name ]
		);
	}

	public function onImportHandleUploadXMLTag( $reader, $revisionInfo ) {
		return $this->container->run(
			'ImportHandleUploadXMLTag',
			[ $reader, $revisionInfo ]
		);
	}

	public function onImportLogInterwikiLink( &$fullInterwikiPrefix, &$pageTitle ) {
		return $this->container->run(
			'ImportLogInterwikiLink',
			[ &$fullInterwikiPrefix, &$pageTitle ]
		);
	}

	public function onImportSources( &$importSources ) {
		return $this->container->run(
			'ImportSources',
			[ &$importSources ]
		);
	}

	public function onInfoAction( $context, &$pageInfo ) {
		return $this->container->run(
			'InfoAction',
			[ $context, &$pageInfo ]
		);
	}

	public function onInitializeArticleMaybeRedirect( $title, $request,
		&$ignoreRedirect, &$target, &$article
	) {
		return $this->container->run(
			'InitializeArticleMaybeRedirect',
			[ $title, $request, &$ignoreRedirect, &$target, &$article ]
		);
	}

	public function onInternalParseBeforeLinks( $parser, &$text, $stripState ) {
		return $this->container->run(
			'InternalParseBeforeLinks',
			[ $parser, &$text, $stripState ]
		);
	}

	public function onInternalParseBeforeSanitize( $parser, &$text, $stripState ) {
		return $this->container->run(
			'InternalParseBeforeSanitize',
			[ $parser, &$text, $stripState ]
		);
	}

	public function onInterwikiLoadPrefix( $prefix, &$iwData ) {
		return $this->container->run(
			'InterwikiLoadPrefix',
			[ $prefix, &$iwData ]
		);
	}

	public function onInvalidateEmailComplete( $user ) {
		return $this->container->run(
			'InvalidateEmailComplete',
			[ $user ]
		);
	}

	public function onIRCLineURL( &$url, &$query, $rc ) {
		return $this->container->run(
			'IRCLineURL',
			[ &$url, &$query, $rc ]
		);
	}

	public function onIsFileCacheable( $article ) {
		return $this->container->run(
			'IsFileCacheable',
			[ $article ]
		);
	}

	public function onIsTrustedProxy( $ip, &$result ) {
		return $this->container->run(
			'IsTrustedProxy',
			[ $ip, &$result ]
		);
	}

	public function onIsUploadAllowedFromUrl( $url, &$allowed ) {
		return $this->container->run(
			'IsUploadAllowedFromUrl',
			[ $url, &$allowed ]
		);
	}

	public function onIsValidEmailAddr( $addr, &$result ) {
		return $this->container->run(
			'isValidEmailAddr',
			[ $addr, &$result ]
		);
	}

	public function onIsValidPassword( $password, &$result, $user ) {
		return $this->container->run(
			'isValidPassword',
			[ $password, &$result, $user ]
		);
	}

	public function onJsonValidateSave( JsonContent $content, PageIdentity $pageIdentity, StatusValue $status ) {
		return $this->container->run(
			'JsonValidateSave',
			[ $content, $pageIdentity, &$status ]
		);
	}

	public function onLanguageGetNamespaces( &$namespaces ) {
		return $this->container->run(
			'LanguageGetNamespaces',
			[ &$namespaces ]
		);
	}

	public function onLanguageGetTranslatedLanguageNames( &$names, $code ) {
		return $this->container->run(
			'LanguageGetTranslatedLanguageNames',
			[ &$names, $code ]
		);
	}

	public function onLanguageLinks( $title, &$links, &$linkFlags ) {
		return $this->container->run(
			'LanguageLinks',
			[ $title, &$links, &$linkFlags ]
		);
	}

	public function onLanguageSelector( $out, $cssClassName ) {
		return $this->container->run(
			'LanguageSelector',
			[ $out, $cssClassName ]
		);
	}

	public function onLanguage__getMessagesFileName( $code, &$file ) {
		return $this->container->run(
			'Language::getMessagesFileName',
			[ $code, &$file ]
		);
	}

	public function onLinkerGenerateRollbackLink( $revRecord, $context, $options, &$inner ) {
		return $this->container->run(
			'LinkerGenerateRollbackLink',
			[ $revRecord, $context, $options, &$inner ]
		);
	}

	public function onLinkerMakeExternalImage( &$url, &$alt, &$img ) {
		return $this->container->run(
			'LinkerMakeExternalImage',
			[ &$url, &$alt, &$img ]
		);
	}

	public function onLinkerMakeExternalLink( &$url, &$text, &$link, &$attribs,
		$linkType
	) {
		return $this->container->run(
			'LinkerMakeExternalLink',
			[ &$url, &$text, &$link, &$attribs, $linkType ]
		);
	}

	public function onLinkerMakeMediaLinkFile( $title, $file, &$html, &$attribs,
		&$ret
	) {
		return $this->container->run(
			'LinkerMakeMediaLinkFile',
			[ $title, $file, &$html, &$attribs, &$ret ]
		);
	}

	public function onLinksUpdate( $linksUpdate ) {
		return $this->container->run(
			'LinksUpdate',
			[ $linksUpdate ]
		);
	}

	public function onLinksUpdateAfterInsert( $linksUpdate, $table, $insertions ) {
		return $this->container->run(
			'LinksUpdateAfterInsert',
			[ $linksUpdate, $table, $insertions ]
		);
	}

	public function onLinksUpdateComplete( $linksUpdate, $ticket ) {
		return $this->container->run(
			'LinksUpdateComplete',
			[ $linksUpdate, $ticket ]
		);
	}

	public function onLinksUpdateConstructed( $linksUpdate ) {
		return $this->container->run(
			'LinksUpdateConstructed',
			[ $linksUpdate ]
		);
	}

	public function onListDefinedTags( &$tags ) {
		return $this->container->run(
			'ListDefinedTags',
			[ &$tags ]
		);
	}

	public function onLoadExtensionSchemaUpdates( $updater ) {
		return $this->container->run(
			'LoadExtensionSchemaUpdates',
			[ $updater ],
			[ 'noServices' => true ]
		);
	}

	public function onLocalFilePurgeThumbnails( $file, $archiveName, $urls ) {
		return $this->container->run(
			'LocalFilePurgeThumbnails',
			[ $file, $archiveName, $urls ]
		);
	}

	public function onLocalFile__getHistory( $file, &$tables, &$fields, &$conds,
		&$opts, &$join_conds
	) {
		return $this->container->run(
			'LocalFile::getHistory',
			[ $file, &$tables, &$fields, &$conds, &$opts, &$join_conds ]
		);
	}

	public function onLocalisationCacheRecache( $cache, $code, &$alldata, $unused ) {
		return $this->container->run(
			'LocalisationCacheRecache',
			[ $cache, $code, &$alldata, $unused ]
		);
	}

	public function onLocalisationCacheRecacheFallback( $cache, $code, &$alldata ) {
		return $this->container->run(
			'LocalisationCacheRecacheFallback',
			[ $cache, $code, &$alldata ]
		);
	}

	public function onLocalUserCreated( $user, $autocreated ) {
		return $this->container->run(
			'LocalUserCreated',
			[ $user, $autocreated ]
		);
	}

	public function onLogEventsListGetExtraInputs( $type, $logEventsList, &$input,
		&$formDescriptor
	) {
		return $this->container->run(
			'LogEventsListGetExtraInputs',
			[ $type, $logEventsList, &$input, &$formDescriptor ]
		);
	}

	public function onLogEventsListLineEnding( $page, &$ret, $entry, &$classes,
		&$attribs
	) {
		return $this->container->run(
			'LogEventsListLineEnding',
			[ $page, &$ret, $entry, &$classes, &$attribs ]
		);
	}

	public function onLogEventsListShowLogExtract( &$s, $types, $page, $user,
		$param
	) {
		return $this->container->run(
			'LogEventsListShowLogExtract',
			[ &$s, $types, $page, $user, $param ]
		);
	}

	public function onLogException( $e, $suppressed ) {
		return $this->container->run(
			'LogException',
			[ $e, $suppressed ]
		);
	}

	public function onLoginFormValidErrorMessages( array &$messages ) {
		return $this->container->run(
			'LoginFormValidErrorMessages',
			[ &$messages ]
		);
	}

	public function onLogLine( $log_type, $log_action, $title, $paramArray,
		&$comment, &$revert, $time
	) {
		return $this->container->run(
			'LogLine',
			[ $log_type, $log_action, $title, $paramArray, &$comment,
				&$revert, $time ]
		);
	}

	public function onLonelyPagesQuery( &$tables, &$conds, &$joinConds ) {
		return $this->container->run(
			'LonelyPagesQuery',
			[ &$tables, &$conds, &$joinConds ]
		);
	}

	public function onMagicWordwgVariableIDs( &$variableIDs ) {
		return $this->container->run(
			'MagicWordwgVariableIDs',
			[ &$variableIDs ]
		);
	}

	public function onMaintenanceRefreshLinksInit( $refreshLinks ) {
		return $this->container->run(
			'MaintenanceRefreshLinksInit',
			[ $refreshLinks ]
		);
	}

	public function onMaintenanceShellStart(): void {
		$this->container->run(
			'MaintenanceShellStart',
			[],
			[ 'abortable' => false ]
		);
	}

	public function onMaintenanceUpdateAddParams( &$params ) {
		return $this->container->run(
			'MaintenanceUpdateAddParams',
			[ &$params ]
		);
	}

	public function onMakeGlobalVariablesScript( &$vars, $out ): void {
		$this->container->run(
			'MakeGlobalVariablesScript',
			[ &$vars, $out ],
			[ 'abortable' => false ]
		);
	}

	public function onManualLogEntryBeforePublish( $logEntry ): void {
		$this->container->run(
			'ManualLogEntryBeforePublish',
			[ $logEntry ],
			[ 'abortable' => false ]
		);
	}

	public function onMarkPatrolled( $rcid, $user, $wcOnlySysopsCanPatrol, $auto,
		&$tags
	) {
		return $this->container->run(
			'MarkPatrolled',
			[ $rcid, $user, $wcOnlySysopsCanPatrol, $auto, &$tags ]
		);
	}

	public function onMarkPatrolledComplete( $rcid, $user, $wcOnlySysopsCanPatrol,
		$auto
	) {
		return $this->container->run(
			'MarkPatrolledComplete',
			[ $rcid, $user, $wcOnlySysopsCanPatrol, $auto ]
		);
	}

	public function onMediaWikiPerformAction( $output, $article, $title, $user,
		$request, $mediaWiki
	) {
		return $this->container->run(
			'MediaWikiPerformAction',
			[ $output, $article, $title, $user, $request, $mediaWiki ]
		);
	}

	public function onMediaWikiServices( $services ) {
		return $this->container->run(
			'MediaWikiServices',
			[ $services ],
			[ 'noServices' => true ]
		);
	}

	public function onMessageCacheReplace( $title, $text ) {
		return $this->container->run(
			'MessageCacheReplace',
			[ $title, $text ]
		);
	}

	public function onMessageCache__get( &$key ) {
		return $this->container->run(
			'MessageCache::get',
			[ &$key ]
		);
	}

	public function onMessagesPreLoad( $title, &$message, $code ) {
		return $this->container->run(
			'MessagesPreLoad',
			[ $title, &$message, $code ]
		);
	}

	public function onMimeMagicGuessFromContent( $mimeMagic, &$head, &$tail, $file,
		&$mime
	) {
		return $this->container->run(
			'MimeMagicGuessFromContent',
			[ $mimeMagic, &$head, &$tail, $file, &$mime ]
		);
	}

	public function onMimeMagicImproveFromExtension( $mimeMagic, $ext, &$mime ) {
		return $this->container->run(
			'MimeMagicImproveFromExtension',
			[ $mimeMagic, $ext, &$mime ]
		);
	}

	public function onMimeMagicInit( $mimeMagic ) {
		return $this->container->run(
			'MimeMagicInit',
			[ $mimeMagic ]
		);
	}

	public function onModifyExportQuery( $db, &$tables, $cond, &$opts,
		&$join_conds, &$conds
	) {
		return $this->container->run(
			'ModifyExportQuery',
			[ $db, &$tables, $cond, &$opts, &$join_conds, &$conds ]
		);
	}

	public function onMovePageCheckPermissions( $oldTitle, $newTitle, $user,
		$reason, $status
	) {
		return $this->container->run(
			'MovePageCheckPermissions',
			[ $oldTitle, $newTitle, $user, $reason, $status ]
		);
	}

	public function onMovePageIsValidMove( $oldTitle, $newTitle, $status ) {
		return $this->container->run(
			'MovePageIsValidMove',
			[ $oldTitle, $newTitle, $status ]
		);
	}

	public function onMultiContentSave( $renderedRevision, $user, $summary, $flags,
		$status
	) {
		return $this->container->run(
			'MultiContentSave',
			[ $renderedRevision, $user, $summary, $flags, $status ]
		);
	}

	public function onNamespaceIsMovable( $index, &$result ) {
		return $this->container->run(
			'NamespaceIsMovable',
			[ $index, &$result ]
		);
	}

	public function onNewDifferenceEngine( $title, &$oldId, &$newId, $old, $new ) {
		return $this->container->run(
			'NewDifferenceEngine',
			[ $title, &$oldId, &$newId, $old, $new ]
		);
	}

	public function onNewPagesLineEnding( $page, &$ret, $row, &$classes, &$attribs ) {
		return $this->container->run(
			'NewPagesLineEnding',
			[ $page, &$ret, $row, &$classes, &$attribs ]
		);
	}

	public function onOldChangesListRecentChangesLine( $changeslist, &$s, $rc,
		&$classes, &$attribs
	) {
		return $this->container->run(
			'OldChangesListRecentChangesLine',
			[ $changeslist, &$s, $rc, &$classes, &$attribs ]
		);
	}

	public function onOpenSearchUrls( &$urls ) {
		return $this->container->run(
			'OpenSearchUrls',
			[ &$urls ]
		);
	}

	public function onOpportunisticLinksUpdate( $page, $title, $parserOutput ) {
		return $this->container->run(
			'OpportunisticLinksUpdate',
			[ $page, $title, $parserOutput ]
		);
	}

	public function onOtherAutoblockLogLink( &$otherBlockLink ) {
		return $this->container->run(
			'OtherAutoblockLogLink',
			[ &$otherBlockLink ]
		);
	}

	public function onOtherBlockLogLink( &$otherBlockLink, $ip ) {
		return $this->container->run(
			'OtherBlockLogLink',
			[ &$otherBlockLink, $ip ]
		);
	}

	public function onOutputPageAfterGetHeadLinksArray( &$tags, $out ) {
		return $this->container->run(
			'OutputPageAfterGetHeadLinksArray',
			[ &$tags, $out ]
		);
	}

	public function onOutputPageBeforeHTML( $out, &$text ) {
		return $this->container->run(
			'OutputPageBeforeHTML',
			[ $out, &$text ]
		);
	}

	public function onOutputPageBodyAttributes( $out, $sk, &$bodyAttrs ): void {
		$this->container->run(
			'OutputPageBodyAttributes',
			[ $out, $sk, &$bodyAttrs ],
			[ 'abortable' => false ]
		);
	}

	public function onOutputPageCheckLastModified( &$modifiedTimes, $out ) {
		return $this->container->run(
			'OutputPageCheckLastModified',
			[ &$modifiedTimes, $out ]
		);
	}

	public function onOutputPageMakeCategoryLinks( $out, $categories, &$links ) {
		return $this->container->run(
			'OutputPageMakeCategoryLinks',
			[ $out, $categories, &$links ]
		);
	}

	public function onOutputPageParserOutput( $outputPage, $parserOutput ): void {
		$this->container->run(
			'OutputPageParserOutput',
			[ $outputPage, $parserOutput ],
			[ 'abortable' => false ]
		);
	}

	public function onPageContentLanguage( $title, &$pageLang, $userLang ) {
		return $this->container->run(
			'PageContentLanguage',
			[ $title, &$pageLang, $userLang ]
		);
	}

	public function onPageContentSave( $wikiPage, $user, $content, &$summary,
		$isminor, $iswatch, $section, $flags, $status
	) {
		return $this->container->run(
			'PageContentSave',
			[ $wikiPage, $user, $content, &$summary, $isminor, $iswatch,
				$section, $flags, $status ]
		);
	}

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

	public function onPageDeletionDataUpdates( $title, $revision, &$updates ) {
		return $this->container->run(
			'PageDeletionDataUpdates',
			[ $title, $revision, &$updates ]
		);
	}

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

	public function onPageHistoryBeforeList( $article, $context ) {
		return $this->container->run(
			'PageHistoryBeforeList',
			[ $article, $context ]
		);
	}

	public function onPageHistoryLineEnding( $historyAction, &$row, &$s, &$classes,
		&$attribs
	) {
		return $this->container->run(
			'PageHistoryLineEnding',
			[ $historyAction, &$row, &$s, &$classes, &$attribs ]
		);
	}

	public function onPageHistoryPager__doBatchLookups( $pager, $result ) {
		return $this->container->run(
			'PageHistoryPager::doBatchLookups',
			[ $pager, $result ]
		);
	}

	public function onPageHistoryPager__getQueryInfo( $pager, &$queryInfo ) {
		return $this->container->run(
			'PageHistoryPager::getQueryInfo',
			[ $pager, &$queryInfo ]
		);
	}

	public function onPageMoveComplete( $old, $new, $user, $pageid, $redirid, $reason, $revision ) {
		return $this->container->run(
			'PageMoveComplete',
			[ $old, $new, $user, $pageid, $redirid, $reason, $revision ]
		);
	}

	public function onPageMoveCompleting( $old, $new, $user, $pageid, $redirid, $reason, $revision ) {
		return $this->container->run(
			'PageMoveCompleting',
			[ $old, $new, $user, $pageid, $redirid, $reason, $revision ]
		);
	}

	public function onPageRenderingHash( &$confstr, $user, &$forOptions ) {
		return $this->container->run(
			'PageRenderingHash',
			[ &$confstr, $user, &$forOptions ]
		);
	}

	public function onPageSaveComplete( $wikiPage, $user, $summary, $flags,
		$revisionRecord, $editResult
	) {
		return $this->container->run(
			'PageSaveComplete',
			[ $wikiPage, $user, $summary, $flags, $revisionRecord, $editResult ]
		);
	}

	public function onPageViewUpdates( $wikipage, $user ) {
		return $this->container->run(
			'PageViewUpdates',
			[ $wikipage, $user ]
		);
	}

	public function onParserAfterParse( $parser, &$text, $stripState ) {
		return $this->container->run(
			'ParserAfterParse',
			[ $parser, &$text, $stripState ]
		);
	}

	public function onParserAfterTidy( $parser, &$text ) {
		return $this->container->run(
			'ParserAfterTidy',
			[ $parser, &$text ]
		);
	}

	public function onParserBeforeInternalParse( $parser, &$text, $stripState ) {
		return $this->container->run(
			'ParserBeforeInternalParse',
			[ $parser, &$text, $stripState ]
		);
	}

	public function onParserBeforePreprocess( $parser, &$text, $stripState ) {
		return $this->container->run(
			'ParserBeforePreprocess',
			[ $parser, &$text, $stripState ]
		);
	}

	public function onParserCacheSaveComplete( $parserCache, $parserOutput, $title,
		$popts, $revId
	) {
		return $this->container->run(
			'ParserCacheSaveComplete',
			[ $parserCache, $parserOutput, $title, $popts, $revId ]
		);
	}

	public function onParserClearState( $parser ) {
		return $this->container->run(
			'ParserClearState',
			[ $parser ]
		);
	}

	public function onParserCloned( $parser ) {
		return $this->container->run(
			'ParserCloned',
			[ $parser ]
		);
	}

	public function onParserFetchTemplateData( array $titles, array &$tplData ): bool {
		return $this->container->run(
			'ParserFetchTemplateData',
			[ $titles, &$tplData ]
		);
	}

	public function onParserFirstCallInit( $parser ) {
		return $this->container->run(
			'ParserFirstCallInit',
			[ $parser ]
		);
	}

	public function onParserGetVariableValueSwitch( $parser, &$variableCache,
		$magicWordId, &$ret, $frame
	) {
		return $this->container->run(
			'ParserGetVariableValueSwitch',
			[ $parser, &$variableCache, $magicWordId, &$ret, $frame ]
		);
	}

	public function onParserGetVariableValueTs( $parser, &$time ) {
		return $this->container->run(
			'ParserGetVariableValueTs',
			[ $parser, &$time ]
		);
	}

	public function onParserLimitReportFormat( $key, &$value, &$report, $isHTML,
		$localize
	) {
		return $this->container->run(
			'ParserLimitReportFormat',
			[ $key, &$value, &$report, $isHTML, $localize ]
		);
	}

	public function onParserLimitReportPrepare( $parser, $output ) {
		return $this->container->run(
			'ParserLimitReportPrepare',
			[ $parser, $output ]
		);
	}

	public function onParserLogLinterData( string $title, int $revId, array $lints ): bool {
		return $this->container->run(
			'ParserLogLinterData',
			[ $title, $revId, $lints ]
		);
	}

	public function onParserMakeImageParams( $title, $file, &$params, $parser ) {
		return $this->container->run(
			'ParserMakeImageParams',
			[ $title, $file, &$params, $parser ]
		);
	}

	public function onParserModifyImageHTML( Parser $parser, File $file,
		array $params, string &$html
	): void {
		$this->container->run(
			'ParserModifyImageHTML',
			[ $parser, $file, $params, &$html ],
			[ 'abortable' => false ]
		);
	}

	public function onParserOptionsRegister( &$defaults, &$inCacheKey, &$lazyLoad ) {
		return $this->container->run(
			'ParserOptionsRegister',
			[ &$defaults, &$inCacheKey, &$lazyLoad ]
		);
	}

	public function onParserOutputPostCacheTransform( $parserOutput, &$text,
		&$options
	): void {
		$this->container->run(
			'ParserOutputPostCacheTransform',
			[ $parserOutput, &$text, &$options ],
			[ 'abortable' => false ]
		);
	}

	public function onParserOutputStashForEdit( $page, $content, $output, $summary,
		$user
	) {
		return $this->container->run(
			'ParserOutputStashForEdit',
			[ $page, $content, $output, $summary, $user ]
		);
	}

	public function onParserPreSaveTransformComplete( $parser, &$text ) {
		return $this->container->run(
			'ParserPreSaveTransformComplete',
			[ $parser, &$text ]
		);
	}

	public function onParserSectionCreate( $parser, $section, &$sectionContent,
		$showEditLinks
	) {
		return $this->container->run(
			'ParserSectionCreate',
			[ $parser, $section, &$sectionContent, $showEditLinks ]
		);
	}

	public function onParserTestGlobals( &$globals ) {
		return $this->container->run(
			'ParserTestGlobals',
			[ &$globals ]
		);
	}

	public function onParserTestTables( &$tables ) {
		return $this->container->run(
			'ParserTestTables',
			[ &$tables ]
		);
	}

	public function onPasswordPoliciesForUser( $user, &$effectivePolicy ) {
		return $this->container->run(
			'PasswordPoliciesForUser',
			[ $user, &$effectivePolicy ]
		);
	}

	public function onPerformRetroactiveAutoblock( $block, &$blockIds ) {
		return $this->container->run(
			'PerformRetroactiveAutoblock',
			[ $block, &$blockIds ]
		);
	}

	public function onPersonalUrls( &$personal_urls, &$title, $skin ): void {
		$this->container->run(
			'PersonalUrls',
			[ &$personal_urls, &$title, $skin ],
			[ 'abortable' => false ]
		);
	}

	public function onPingLimiter( $user, $action, &$result, $incrBy ) {
		return $this->container->run(
			'PingLimiter',
			[ $user, $action, &$result, $incrBy ]
		);
	}

	public function onPlaceNewSection( $content, $oldtext, $subject, &$text ) {
		return $this->container->run(
			'PlaceNewSection',
			[ $content, $oldtext, $subject, &$text ]
		);
	}

	public function onPostLoginRedirect( &$returnTo, &$returnToQuery, &$type ) {
		return $this->container->run(
			'PostLoginRedirect',
			[ &$returnTo, &$returnToQuery, &$type ]
		);
	}

	public function onPreferencesFormPreSave( $formData, $form, $user, &$result,
		$oldUserOptions
	) {
		return $this->container->run(
			'PreferencesFormPreSave',
			[ $formData, $form, $user, &$result, $oldUserOptions ]
		);
	}

	public function onPreferencesGetLegend( $form, $key, &$legend ) {
		return $this->container->run(
			'PreferencesGetLegend',
			[ $form, $key, &$legend ]
		);
	}

	public function onPrefixSearchBackend( $ns, $search, $limit, &$results,
		$offset
	) {
		return $this->container->run(
			'PrefixSearchBackend',
			[ $ns, $search, $limit, &$results, $offset ]
		);
	}

	public function onPrefixSearchExtractNamespace( &$namespaces, &$search ) {
		return $this->container->run(
			'PrefixSearchExtractNamespace',
			[ &$namespaces, &$search ]
		);
	}

	public function onPrefsEmailAudit( $user, $oldaddr, $newaddr ) {
		return $this->container->run(
			'PrefsEmailAudit',
			[ $user, $oldaddr, $newaddr ]
		);
	}

	public function onProtectionForm__buildForm( $article, &$output ) {
		return $this->container->run(
			'ProtectionForm::buildForm',
			[ $article, &$output ]
		);
	}

	public function onProtectionFormAddFormFields( $article, &$hookFormOptions ) {
		return $this->container->run(
			'ProtectionFormAddFormFields',
			[ $article, &$hookFormOptions ]
		);
	}

	public function onProtectionForm__save( $article, &$errorMsg, $reasonstr ) {
		return $this->container->run(
			'ProtectionForm::save',
			[ $article, &$errorMsg, $reasonstr ]
		);
	}

	public function onProtectionForm__showLogExtract( $article, $out ) {
		return $this->container->run(
			'ProtectionForm::showLogExtract',
			[ $article, $out ]
		);
	}

	public function onRandomPageQuery( &$tables, &$conds, &$joinConds ) {
		return $this->container->run(
			'RandomPageQuery',
			[ &$tables, &$conds, &$joinConds ]
		);
	}

	public function onRawPageViewBeforeOutput( $obj, &$text ) {
		return $this->container->run(
			'RawPageViewBeforeOutput',
			[ $obj, &$text ]
		);
	}

	public function onRecentChangesPurgeRows( $rows ) {
		return $this->container->run(
			'RecentChangesPurgeRows',
			[ $rows ]
		);
	}

	public function onRecentChange_save( $recentChange ) {
		return $this->container->run(
			'RecentChange_save',
			[ $recentChange ]
		);
	}

	public function onRedirectSpecialArticleRedirectParams( &$redirectParams ) {
		return $this->container->run(
			'RedirectSpecialArticleRedirectParams',
			[ &$redirectParams ]
		);
	}

	public function onRejectParserCacheValue( $parserOutput, $wikiPage,
		$parserOptions
	) {
		return $this->container->run(
			'RejectParserCacheValue',
			[ $parserOutput, $wikiPage, $parserOptions ]
		);
	}

	public function onRequestContextCreateSkin( $context, &$skin ) {
		return $this->container->run(
			'RequestContextCreateSkin',
			[ $context, &$skin ]
		);
	}

	public function onResetPasswordExpiration( $user, &$newExpire ) {
		return $this->container->run(
			'ResetPasswordExpiration',
			[ $user, &$newExpire ]
		);
	}

	public function onResourceLoaderGetConfigVars( array &$vars, $skin, Config $config ): void {
		$this->container->run(
			'ResourceLoaderGetConfigVars',
			[ &$vars, $skin, $config ],
			[ 'abortable' => false ]
		);
	}

	public function onResourceLoaderJqueryMsgModuleMagicWords( RL\Context $context,
		array &$magicWords
	): void {
		$this->container->run(
			'ResourceLoaderJqueryMsgModuleMagicWords',
			[ $context, &$magicWords ],
			[ 'abortable' => false ]
		);
	}

	public function onRevisionDataUpdates( $title, $renderedRevision, &$updates ) {
		return $this->container->run(
			'RevisionDataUpdates',
			[ $title, $renderedRevision, &$updates ]
		);
	}

	public function onRevisionFromEditComplete( $wikiPage, $rev, $originalRevId, $user, &$tags ) {
		return $this->container->run(
			'RevisionFromEditComplete',
			[ $wikiPage, $rev, $originalRevId, $user, &$tags ]
		);
	}

	public function onRevisionRecordInserted( $revisionRecord ) {
		return $this->container->run(
			'RevisionRecordInserted',
			[ $revisionRecord ]
		);
	}

	public function onRevisionUndeleted( $revisionRecord, $oldPageID ) {
		return $this->container->run(
			'RevisionUndeleted',
			[ $revisionRecord, $oldPageID ]
		);
	}

	public function onRollbackComplete( $wikiPage, $user, $revision, $current ) {
		return $this->container->run(
			'RollbackComplete',
			[ $wikiPage, $user, $revision, $current ]
		);
	}

	public function onSearchableNamespaces( &$arr ) {
		return $this->container->run(
			'SearchableNamespaces',
			[ &$arr ]
		);
	}

	public function onSearchAfterNoDirectMatch( $term, &$title ) {
		return $this->container->run(
			'SearchAfterNoDirectMatch',
			[ $term, &$title ]
		);
	}

	public function onSearchDataForIndex( &$fields, $handler, $page, $output,
		$engine
	) {
		return $this->container->run(
			'SearchDataForIndex',
			[ &$fields, $handler, $page, $output, $engine ]
		);
	}

	public function onSearchGetNearMatch( $term, &$title ) {
		return $this->container->run(
			'SearchGetNearMatch',
			[ $term, &$title ]
		);
	}

	public function onSearchGetNearMatchBefore( $allSearchTerms, &$titleResult ) {
		return $this->container->run(
			'SearchGetNearMatchBefore',
			[ $allSearchTerms, &$titleResult ]
		);
	}

	public function onSearchGetNearMatchComplete( $term, &$title ) {
		return $this->container->run(
			'SearchGetNearMatchComplete',
			[ $term, &$title ]
		);
	}

	public function onSearchIndexFields( &$fields, $engine ) {
		return $this->container->run(
			'SearchIndexFields',
			[ &$fields, $engine ]
		);
	}

	public function onSearchResultInitFromTitle( $title, &$id ) {
		return $this->container->run(
			'SearchResultInitFromTitle',
			[ $title, &$id ]
		);
	}

	public function onSearchResultProvideDescription( array $pageIdentities, &$descriptions ) {
		return $this->container->run(
			'SearchResultProvideDescription',
			[ $pageIdentities, &$descriptions ]
		);
	}

	public function onSearchResultProvideThumbnail( array $pageIdentities, &$thumbnails ) {
		return $this->container->run(
			'SearchResultProvideThumbnail',
			[ $pageIdentities, &$thumbnails ]
		);
	}

	public function onSearchResultsAugment( &$setAugmentors, &$rowAugmentors ) {
		return $this->container->run(
			'SearchResultsAugment',
			[ &$setAugmentors, &$rowAugmentors ]
		);
	}

	public function onSecuritySensitiveOperationStatus( &$status, $operation,
		$session, $timeSinceAuth
	) {
		return $this->container->run(
			'SecuritySensitiveOperationStatus',
			[ &$status, $operation, $session, $timeSinceAuth ]
		);
	}

	public function onSelfLinkBegin( $nt, &$html, &$trail, &$prefix, &$ret ) {
		return $this->container->run(
			'SelfLinkBegin',
			[ $nt, &$html, &$trail, &$prefix, &$ret ]
		);
	}

	public function onSendWatchlistEmailNotification( $targetUser, $title, $enotif ) {
		return $this->container->run(
			'SendWatchlistEmailNotification',
			[ $targetUser, $title, $enotif ]
		);
	}

	public function onSessionCheckInfo( &$reason, $info, $request, $metadata,
		$data
	) {
		return $this->container->run(
			'SessionCheckInfo',
			[ &$reason, $info, $request, $metadata, $data ]
		);
	}

	public function onSessionMetadata( $backend, &$metadata, $requests ) {
		return $this->container->run(
			'SessionMetadata',
			[ $backend, &$metadata, $requests ]
		);
	}

	public function onSetupAfterCache() {
		return $this->container->run(
			'SetupAfterCache',
			[]
		);
	}

	public function onShortPagesQuery( &$tables, &$conds, &$joinConds, &$options ) {
		return $this->container->run(
			'ShortPagesQuery',
			[ &$tables, &$conds, &$joinConds, &$options ]
		);
	}

	public function onShowMissingArticle( $article ) {
		return $this->container->run(
			'ShowMissingArticle',
			[ $article ]
		);
	}

	public function onShowSearchHit( $searchPage, $result, $terms, &$link,
		&$redirect, &$section, &$extract, &$score, &$size, &$date, &$related, &$html
	) {
		return $this->container->run(
			'ShowSearchHit',
			[ $searchPage, $result, $terms, &$link, &$redirect, &$section,
				&$extract, &$score, &$size, &$date, &$related, &$html ]
		);
	}

	public function onShowSearchHitTitle( &$title, &$titleSnippet, $result, $terms,
		$specialSearch, &$query, &$attributes
	) {
		return $this->container->run(
			'ShowSearchHitTitle',
			[ &$title, &$titleSnippet, $result, $terms, $specialSearch,
				&$query, &$attributes ]
		);
	}

	public function onSidebarBeforeOutput( $skin, &$sidebar ): void {
		$this->container->run(
			'SidebarBeforeOutput',
			[ $skin, &$sidebar ],
			[ 'abortable' => false ]
		);
	}

	public function onSiteNoticeAfter( &$siteNotice, $skin ) {
		return $this->container->run(
			'SiteNoticeAfter',
			[ &$siteNotice, $skin ]
		);
	}

	public function onSiteNoticeBefore( &$siteNotice, $skin ) {
		return $this->container->run(
			'SiteNoticeBefore',
			[ &$siteNotice, $skin ]
		);
	}

	public function onSkinPageReadyConfig( RL\Context $context,
		array &$config
	): void {
		$this->container->run(
			'SkinPageReadyConfig',
			[ $context, &$config ],
			[ 'abortable' => false ]
		);
	}

	public function onSkinAddFooterLinks( Skin $skin, string $key, array &$footerItems ) {
		$this->container->run(
			'SkinAddFooterLinks',
			[ $skin, $key, &$footerItems ]
		);
	}

	public function onSkinAfterBottomScripts( $skin, &$text ) {
		return $this->container->run(
			'SkinAfterBottomScripts',
			[ $skin, &$text ]
		);
	}

	public function onSkinAfterContent( &$data, $skin ) {
		return $this->container->run(
			'SkinAfterContent',
			[ &$data, $skin ]
		);
	}

	public function onSkinAfterPortlet( $skin, $portlet, &$html ) {
		return $this->container->run(
			'SkinAfterPortlet',
			[ $skin, $portlet, &$html ]
		);
	}

	public function onSkinBuildSidebar( $skin, &$bar ) {
		return $this->container->run(
			'SkinBuildSidebar',
			[ $skin, &$bar ]
		);
	}

	public function onSkinCopyrightFooter( $title, $type, &$msg, &$link ) {
		return $this->container->run(
			'SkinCopyrightFooter',
			[ $title, $type, &$msg, &$link ]
		);
	}

	public function onSkinEditSectionLinks( $skin, $title, $section, $tooltip,
		&$result, $lang
	) {
		return $this->container->run(
			'SkinEditSectionLinks',
			[ $skin, $title, $section, $tooltip, &$result, $lang ]
		);
	}

	public function onSkinPreloadExistence( &$titles, $skin ) {
		return $this->container->run(
			'SkinPreloadExistence',
			[ &$titles, $skin ]
		);
	}

	public function onSkinSubPageSubtitle( &$subpages, $skin, $out ) {
		return $this->container->run(
			'SkinSubPageSubtitle',
			[ &$subpages, $skin, $out ]
		);
	}

	public function onSkinTemplateGetLanguageLink( &$languageLink,
		$languageLinkTitle, $title, $outputPage
	) {
		return $this->container->run(
			'SkinTemplateGetLanguageLink',
			[ &$languageLink, $languageLinkTitle, $title, $outputPage ]
		);
	}

	/**
	 * @deprecated since 1.39 Use onSkinTemplateNavigation__Universal instead
	 */
	public function onSkinTemplateNavigation( $sktemplate, &$links ): void {
		$this->container->run(
			'SkinTemplateNavigation',
			[ $sktemplate, &$links ],
			[ 'abortable' => false ]
		);
	}

	/**
	 * @deprecated since 1.39 Use onSkinTemplateNavigation__Universal instead
	 */
	public function onSkinTemplateNavigation__SpecialPage( $sktemplate, &$links ): void {
		$this->container->run(
			'SkinTemplateNavigation::SpecialPage',
			[ $sktemplate, &$links ],
			[ 'abortable' => false ]
		);
	}

	public function onSkinTemplateNavigation__Universal( $sktemplate, &$links ): void {
		$this->container->run(
			'SkinTemplateNavigation::Universal',
			[ $sktemplate, &$links ],
			[ 'abortable' => false ]
		);
	}

	public function onSoftwareInfo( &$software ) {
		return $this->container->run(
			'SoftwareInfo',
			[ &$software ]
		);
	}

	public function onSpecialBlockModifyFormFields( $sp, &$fields ) {
		return $this->container->run(
			'SpecialBlockModifyFormFields',
			[ $sp, &$fields ]
		);
	}

	public function onSpecialContributionsBeforeMainOutput( $id, $user, $sp ) {
		return $this->container->run(
			'SpecialContributionsBeforeMainOutput',
			[ $id, $user, $sp ]
		);
	}

	public function onSpecialContributions__formatRow__flags( $context, $row,
		&$flags
	) {
		return $this->container->run(
			'SpecialContributions::formatRow::flags',
			[ $context, $row, &$flags ]
		);
	}

	public function onSpecialContributions__getForm__filters( $sp, &$filters ) {
		return $this->container->run(
			'SpecialContributions::getForm::filters',
			[ $sp, &$filters ]
		);
	}

	public function onSpecialExportGetExtraPages( $inputPages, &$extraPages ) {
		return $this->container->run(
			'SpecialExportGetExtraPages',
			[ $inputPages, &$extraPages ]
		);
	}

	public function onSpecialListusersDefaultQuery( $pager, &$query ) {
		return $this->container->run(
			'SpecialListusersDefaultQuery',
			[ $pager, &$query ]
		);
	}

	public function onSpecialListusersFormatRow( &$item, $row ) {
		return $this->container->run(
			'SpecialListusersFormatRow',
			[ &$item, $row ]
		);
	}

	public function onSpecialListusersHeader( $pager, &$out ) {
		return $this->container->run(
			'SpecialListusersHeader',
			[ $pager, &$out ]
		);
	}

	public function onSpecialListusersHeaderForm( $pager, &$out ) {
		return $this->container->run(
			'SpecialListusersHeaderForm',
			[ $pager, &$out ]
		);
	}

	public function onSpecialListusersQueryInfo( $pager, &$query ) {
		return $this->container->run(
			'SpecialListusersQueryInfo',
			[ $pager, &$query ]
		);
	}

	public function onSpecialLogAddLogSearchRelations( $type, $request, &$qc ) {
		return $this->container->run(
			'SpecialLogAddLogSearchRelations',
			[ $type, $request, &$qc ]
		);
	}

	public function onSpecialMovepageAfterMove( $movePage, $oldTitle, $newTitle ) {
		return $this->container->run(
			'SpecialMovepageAfterMove',
			[ $movePage, $oldTitle, $newTitle ]
		);
	}

	public function onSpecialMuteModifyFormFields( $target, $user, &$fields ) {
		return $this->container->run(
			'SpecialMuteModifyFormFields',
			[ $target, $user, &$fields ]
		);
	}

	public function onSpecialMuteSubmit( $data ) {
		return $this->container->run(
			'SpecialMuteSubmit',
			[ $data ]
		);
	}

	public function onSpecialNewpagesConditions( $special, $opts, &$conds,
		&$tables, &$fields, &$join_conds
	) {
		return $this->container->run(
			'SpecialNewpagesConditions',
			[ $special, $opts, &$conds, &$tables, &$fields, &$join_conds ]
		);
	}

	public function onSpecialNewPagesFilters( $special, &$filters ) {
		return $this->container->run(
			'SpecialNewPagesFilters',
			[ $special, &$filters ]
		);
	}

	public function onSpecialPageAfterExecute( $special, $subPage ) {
		return $this->container->run(
			'SpecialPageAfterExecute',
			[ $special, $subPage ]
		);
	}

	public function onSpecialPageBeforeExecute( $special, $subPage ) {
		return $this->container->run(
			'SpecialPageBeforeExecute',
			[ $special, $subPage ]
		);
	}

	public function onSpecialPageBeforeFormDisplay( $name, $form ) {
		return $this->container->run(
			'SpecialPageBeforeFormDisplay',
			[ $name, $form ]
		);
	}

	public function onSpecialPage_initList( &$list ) {
		return $this->container->run(
			'SpecialPage_initList',
			[ &$list ]
		);
	}

	public function onSpecialPasswordResetOnSubmit( &$users, $data, &$error ) {
		return $this->container->run(
			'SpecialPasswordResetOnSubmit',
			[ &$users, $data, &$error ]
		);
	}

	public function onSpecialRandomGetRandomTitle( &$randstr, &$isRedir,
		&$namespaces, &$extra, &$title
	) {
		return $this->container->run(
			'SpecialRandomGetRandomTitle',
			[ &$randstr, &$isRedir, &$namespaces, &$extra, &$title ]
		);
	}

	public function onSpecialRecentChangesPanel( &$extraOpts, $opts ) {
		return $this->container->run(
			'SpecialRecentChangesPanel',
			[ &$extraOpts, $opts ]
		);
	}

	public function onSpecialResetTokensTokens( &$tokens ) {
		return $this->container->run(
			'SpecialResetTokensTokens',
			[ &$tokens ]
		);
	}

	public function onSpecialSearchCreateLink( $t, &$params ) {
		return $this->container->run(
			'SpecialSearchCreateLink',
			[ $t, &$params ]
		);
	}

	public function onSpecialSearchGoResult( $term, $title, &$url ) {
		return $this->container->run(
			'SpecialSearchGoResult',
			[ $term, $title, &$url ]
		);
	}

	public function onSpecialSearchNogomatch( &$title ) {
		return $this->container->run(
			'SpecialSearchNogomatch',
			[ &$title ]
		);
	}

	public function onSpecialSearchPowerBox( &$showSections, $term, &$opts ) {
		return $this->container->run(
			'SpecialSearchPowerBox',
			[ &$showSections, $term, &$opts ]
		);
	}

	public function onSpecialSearchProfileForm( $search, &$form, $profile, $term,
		$opts
	) {
		return $this->container->run(
			'SpecialSearchProfileForm',
			[ $search, &$form, $profile, $term, $opts ]
		);
	}

	public function onSpecialSearchProfiles( &$profiles ) {
		return $this->container->run(
			'SpecialSearchProfiles',
			[ &$profiles ]
		);
	}

	public function onSpecialSearchResults( $term, &$titleMatches, &$textMatches ) {
		return $this->container->run(
			'SpecialSearchResults',
			[ $term, &$titleMatches, &$textMatches ]
		);
	}

	public function onSpecialSearchResultsAppend( $specialSearch, $output, $term ) {
		return $this->container->run(
			'SpecialSearchResultsAppend',
			[ $specialSearch, $output, $term ]
		);
	}

	public function onSpecialSearchResultsPrepend( $specialSearch, $output, $term ) {
		return $this->container->run(
			'SpecialSearchResultsPrepend',
			[ $specialSearch, $output, $term ]
		);
	}

	public function onSpecialSearchSetupEngine( $search, $profile, $engine ) {
		return $this->container->run(
			'SpecialSearchSetupEngine',
			[ $search, $profile, $engine ]
		);
	}

	public function onSpecialStatsAddExtra( &$extraStats, $context ) {
		return $this->container->run(
			'SpecialStatsAddExtra',
			[ &$extraStats, $context ]
		);
	}

	public function onSpecialTrackingCategories__generateCatLink( $specialPage,
		$catTitle, &$html
	) {
		return $this->container->run(
			'SpecialTrackingCategories::generateCatLink',
			[ $specialPage, $catTitle, &$html ]
		);
	}

	public function onSpecialTrackingCategories__preprocess( $specialPage,
		$trackingCategories
	) {
		return $this->container->run(
			'SpecialTrackingCategories::preprocess',
			[ $specialPage, $trackingCategories ]
		);
	}

	public function onSpecialUploadComplete( $form ) {
		return $this->container->run(
			'SpecialUploadComplete',
			[ $form ]
		);
	}

	public function onSpecialVersionVersionUrl( $version, &$versionUrl ) {
		return $this->container->run(
			'SpecialVersionVersionUrl',
			[ $version, &$versionUrl ]
		);
	}

	public function onSpecialWatchlistGetNonRevisionTypes( &$nonRevisionTypes ) {
		return $this->container->run(
			'SpecialWatchlistGetNonRevisionTypes',
			[ &$nonRevisionTypes ]
		);
	}

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

	public function onTestCanonicalRedirect( $request, $title, $output ) {
		return $this->container->run(
			'TestCanonicalRedirect',
			[ $request, $title, $output ]
		);
	}

	public function onThumbnailBeforeProduceHTML( $thumbnail, &$attribs,
		&$linkAttribs
	) {
		return $this->container->run(
			'ThumbnailBeforeProduceHTML',
			[ $thumbnail, &$attribs, &$linkAttribs ]
		);
	}

	public function onTitleExists( $title, &$exists ) {
		return $this->container->run(
			'TitleExists',
			[ $title, &$exists ]
		);
	}

	public function onTitleGetEditNotices( $title, $oldid, &$notices ) {
		return $this->container->run(
			'TitleGetEditNotices',
			[ $title, $oldid, &$notices ]
		);
	}

	public function onTitleGetRestrictionTypes( $title, &$types ) {
		return $this->container->run(
			'TitleGetRestrictionTypes',
			[ $title, &$types ]
		);
	}

	public function onTitleIsAlwaysKnown( $title, &$isKnown ) {
		return $this->container->run(
			'TitleIsAlwaysKnown',
			[ $title, &$isKnown ]
		);
	}

	public function onTitleIsMovable( $title, &$result ) {
		return $this->container->run(
			'TitleIsMovable',
			[ $title, &$result ]
		);
	}

	public function onTitleMove( $old, $nt, $user, $reason, &$status ) {
		return $this->container->run(
			'TitleMove',
			[ $old, $nt, $user, $reason, &$status ]
		);
	}

	public function onTitleMoveStarting( $old, $nt, $user ) {
		return $this->container->run(
			'TitleMoveStarting',
			[ $old, $nt, $user ]
		);
	}

	public function onTitleQuickPermissions( $title, $user, $action, &$errors,
		$doExpensiveQueries, $short
	) {
		return $this->container->run(
			'TitleQuickPermissions',
			[ $title, $user, $action, &$errors, $doExpensiveQueries, $short ]
		);
	}

	public function onTitleReadWhitelist( $title, $user, &$whitelisted ) {
		return $this->container->run(
			'TitleReadWhitelist',
			[ $title, $user, &$whitelisted ]
		);
	}

	public function onTitleSquidURLs( $title, &$urls ) {
		return $this->container->run(
			'TitleSquidURLs',
			[ $title, &$urls ]
		);
	}

	public function onUnblockUser( $block, $user, &$reason ) {
		return $this->container->run(
			'UnblockUser',
			[ $block, $user, &$reason ]
		);
	}

	public function onUnblockUserComplete( $block, $user ) {
		return $this->container->run(
			'UnblockUserComplete',
			[ $block, $user ]
		);
	}

	public function onUndeleteForm__showHistory( &$archive, $title ) {
		return $this->container->run(
			'UndeleteForm::showHistory',
			[ &$archive, $title ]
		);
	}

	public function onUndeleteForm__showRevision( &$archive, $title ) {
		return $this->container->run(
			'UndeleteForm::showRevision',
			[ &$archive, $title ]
		);
	}

	public function onUndeletePageToolLinks( IContextSource $context, LinkRenderer $linkRenderer, array &$links ) {
		return $this->container->run(
			'UndeletePageToolLinks',
			[ $context, $linkRenderer, &$links ]
		);
	}

	public function onUnitTestsAfterDatabaseSetup( $database, $prefix ) {
		return $this->container->run(
			'UnitTestsAfterDatabaseSetup',
			[ $database, $prefix ]
		);
	}

	public function onUnitTestsBeforeDatabaseTeardown() {
		return $this->container->run(
			'UnitTestsBeforeDatabaseTeardown',
			[]
		);
	}

	public function onUnitTestsList( &$paths ) {
		return $this->container->run(
			'UnitTestsList',
			[ &$paths ]
		);
	}

	public function onUnwatchArticle( $user, $page, &$status ) {
		return $this->container->run(
			'UnwatchArticle',
			[ $user, $page, &$status ]
		);
	}

	public function onUnwatchArticleComplete( $user, $page ) {
		return $this->container->run(
			'UnwatchArticleComplete',
			[ $user, $page ]
		);
	}

	public function onUpdateUserMailerFormattedPageStatus( &$formattedPageStatus ) {
		return $this->container->run(
			'UpdateUserMailerFormattedPageStatus',
			[ &$formattedPageStatus ]
		);
	}

	public function onUploadComplete( $uploadBase ) {
		return $this->container->run(
			'UploadComplete',
			[ $uploadBase ]
		);
	}

	public function onUploadCreateFromRequest( $type, &$className ) {
		return $this->container->run(
			'UploadCreateFromRequest',
			[ $type, &$className ]
		);
	}

	public function onUploadFormInitDescriptor( &$descriptor ) {
		return $this->container->run(
			'UploadFormInitDescriptor',
			[ &$descriptor ]
		);
	}

	public function onUploadFormSourceDescriptors( &$descriptor, &$radio,
		$selectedSourceType
	) {
		return $this->container->run(
			'UploadFormSourceDescriptors',
			[ &$descriptor, &$radio, $selectedSourceType ]
		);
	}

	public function onUploadForm_BeforeProcessing( $upload ) {
		return $this->container->run(
			'UploadForm:BeforeProcessing',
			[ $upload ]
		);
	}

	public function onUploadForm_getInitialPageText( &$pageText, $msg, $config ) {
		return $this->container->run(
			'UploadForm:getInitialPageText',
			[ &$pageText, $msg, $config ]
		);
	}

	public function onUploadForm_initial( $upload ) {
		return $this->container->run(
			'UploadForm:initial',
			[ $upload ]
		);
	}

	public function onUploadStashFile( $upload, $user, $props, &$error ) {
		return $this->container->run(
			'UploadStashFile',
			[ $upload, $user, $props, &$error ]
		);
	}

	public function onUploadVerifyFile( $upload, $mime, &$error ) {
		return $this->container->run(
			'UploadVerifyFile',
			[ $upload, $mime, &$error ]
		);
	}

	public function onUploadVerifyUpload( $upload, $user, $props, $comment,
		$pageText, &$error
	) {
		return $this->container->run(
			'UploadVerifyUpload',
			[ $upload, $user, $props, $comment, $pageText, &$error ]
		);
	}

	public function onUserAddGroup( $user, &$group, &$expiry ) {
		return $this->container->run(
			'UserAddGroup',
			[ $user, &$group, &$expiry ]
		);
	}

	public function onUserArrayFromResult( &$userArray, $res ) {
		return $this->container->run(
			'UserArrayFromResult',
			[ &$userArray, $res ]
		);
	}

	public function onUserCan( $title, $user, $action, &$result ) {
		return $this->container->run(
			'userCan',
			[ $title, $user, $action, &$result ]
		);
	}

	public function onUserCanSendEmail( $user, &$hookErr ) {
		return $this->container->run(
			'UserCanSendEmail',
			[ $user, &$hookErr ]
		);
	}

	public function onUserClearNewTalkNotification( $userIdentity, $oldid ) {
		return $this->container->run(
			'UserClearNewTalkNotification',
			[ $userIdentity, $oldid ]
		);
	}

	public function onUserEditCountUpdate( $infos ): void {
		$this->container->run(
			'UserEditCountUpdate',
			[ $infos ],
			[ 'abortable' => false ]
		);
	}

	public function onUserEffectiveGroups( $user, &$groups ) {
		return $this->container->run(
			'UserEffectiveGroups',
			[ $user, &$groups ]
		);
	}

	public function onUserGetAllRights( &$rights ) {
		return $this->container->run(
			'UserGetAllRights',
			[ &$rights ]
		);
	}

	public function onUserGetDefaultOptions( &$defaultOptions ) {
		return $this->container->run(
			'UserGetDefaultOptions',
			[ &$defaultOptions ]
		);
	}

	public function onUserGetEmail( $user, &$email ) {
		return $this->container->run(
			'UserGetEmail',
			[ $user, &$email ]
		);
	}

	public function onUserGetEmailAuthenticationTimestamp( $user, &$timestamp ) {
		return $this->container->run(
			'UserGetEmailAuthenticationTimestamp',
			[ $user, &$timestamp ]
		);
	}

	public function onUserGetLanguageObject( $user, &$code, $context ) {
		return $this->container->run(
			'UserGetLanguageObject',
			[ $user, &$code, $context ]
		);
	}

	public function onUserGetReservedNames( &$reservedUsernames ) {
		return $this->container->run(
			'UserGetReservedNames',
			[ &$reservedUsernames ]
		);
	}

	public function onUserGetRights( $user, &$rights ) {
		return $this->container->run(
			'UserGetRights',
			[ $user, &$rights ]
		);
	}

	public function onUserGetRightsRemove( $user, &$rights ) {
		return $this->container->run(
			'UserGetRightsRemove',
			[ $user, &$rights ]
		);
	}

	public function onUserGroupsChanged( $user, $added, $removed, $performer,
		$reason, $oldUGMs, $newUGMs
	) {
		return $this->container->run(
			'UserGroupsChanged',
			[ $user, $added, $removed, $performer, $reason, $oldUGMs,
				$newUGMs ]
		);
	}

	public function onUserIsBlockedFrom( $user, $title, &$blocked, &$allowUsertalk ) {
		return $this->container->run(
			'UserIsBlockedFrom',
			[ $user, $title, &$blocked, &$allowUsertalk ]
		);
	}

	public function onUserIsBlockedGlobally( $user, $ip, &$blocked, &$block ) {
		return $this->container->run(
			'UserIsBlockedGlobally',
			[ $user, $ip, &$blocked, &$block ]
		);
	}

	public function onUserIsBot( $user, &$isBot ) {
		return $this->container->run(
			'UserIsBot',
			[ $user, &$isBot ]
		);
	}

	public function onUserIsEveryoneAllowed( $right ) {
		return $this->container->run(
			'UserIsEveryoneAllowed',
			[ $right ]
		);
	}

	public function onUserIsLocked( $user, &$locked ) {
		return $this->container->run(
			'UserIsLocked',
			[ $user, &$locked ]
		);
	}

	public function onUserLoadAfterLoadFromSession( $user ) {
		return $this->container->run(
			'UserLoadAfterLoadFromSession',
			[ $user ]
		);
	}

	public function onUserLoadDefaults( $user, $name ) {
		return $this->container->run(
			'UserLoadDefaults',
			[ $user, $name ]
		);
	}

	public function onUserLoadFromDatabase( $user, &$s ) {
		return $this->container->run(
			'UserLoadFromDatabase',
			[ $user, &$s ]
		);
	}

	public function onLoadUserOptions( UserIdentity $user, array &$options ): void {
		$this->container->run(
			'LoadUserOptions',
			[ $user, &$options ],
			[ 'abortable' => false ]
		);
	}

	public function onUserLoggedIn( $user ) {
		return $this->container->run(
			'UserLoggedIn',
			[ $user ]
		);
	}

	public function onUserLoginComplete( $user, &$inject_html, $direct ) {
		return $this->container->run(
			'UserLoginComplete',
			[ $user, &$inject_html, $direct ]
		);
	}

	public function onUserLogout( $user ) {
		return $this->container->run(
			'UserLogout',
			[ $user ]
		);
	}

	public function onUserLogoutComplete( $user, &$inject_html, $oldName ) {
		return $this->container->run(
			'UserLogoutComplete',
			[ $user, &$inject_html, $oldName ]
		);
	}

	public function onUserMailerChangeReturnPath( $to, &$returnPath ) {
		return $this->container->run(
			'UserMailerChangeReturnPath',
			[ $to, &$returnPath ]
		);
	}

	public function onUserMailerSplitTo( &$to ) {
		return $this->container->run(
			'UserMailerSplitTo',
			[ &$to ]
		);
	}

	public function onUserMailerTransformContent( $to, $from, &$body, &$error ) {
		return $this->container->run(
			'UserMailerTransformContent',
			[ $to, $from, &$body, &$error ]
		);
	}

	public function onUserMailerTransformMessage( $to, $from, &$subject, &$headers,
		&$body, &$error
	) {
		return $this->container->run(
			'UserMailerTransformMessage',
			[ $to, $from, &$subject, &$headers, &$body, &$error ]
		);
	}

	public function onUserRemoveGroup( $user, &$group ) {
		return $this->container->run(
			'UserRemoveGroup',
			[ $user, &$group ]
		);
	}

	public function onSaveUserOptions( UserIdentity $user, array &$modifiedOptions, array $originalOptions ) {
		return $this->container->run(
			'SaveUserOptions',
			[ $user, &$modifiedOptions, $originalOptions ]
		);
	}

	public function onUserSaveSettings( $user ) {
		return $this->container->run(
			'UserSaveSettings',
			[ $user ]
		);
	}

	public function onUserSendConfirmationMail( $user, &$mail, $info ) {
		return $this->container->run(
			'UserSendConfirmationMail',
			[ $user, &$mail, $info ]
		);
	}

	public function onUserSetCookies( $user, &$session, &$cookies ) {
		return $this->container->run(
			'UserSetCookies',
			[ $user, &$session, &$cookies ]
		);
	}

	public function onUserSetEmail( $user, &$email ) {
		return $this->container->run(
			'UserSetEmail',
			[ $user, &$email ]
		);
	}

	public function onUserSetEmailAuthenticationTimestamp( $user, &$timestamp ) {
		return $this->container->run(
			'UserSetEmailAuthenticationTimestamp',
			[ $user, &$timestamp ]
		);
	}

	public function onUsersPagerDoBatchLookups( $dbr, $userIds, &$cache, &$groups ) {
		return $this->container->run(
			'UsersPagerDoBatchLookups',
			[ $dbr, $userIds, &$cache, &$groups ]
		);
	}

	public function onUserToolLinksEdit( $userId, $userText, &$items ) {
		return $this->container->run(
			'UserToolLinksEdit',
			[ $userId, $userText, &$items ]
		);
	}

	public function onUser__mailPasswordInternal( $user, $ip, $u ) {
		return $this->container->run(
			'User::mailPasswordInternal',
			[ $user, $ip, $u ]
		);
	}

	public function onValidateExtendedMetadataCache( $timestamp, $file ) {
		return $this->container->run(
			'ValidateExtendedMetadataCache',
			[ $timestamp, $file ]
		);
	}

	public function onWantedPages__getQueryInfo( $wantedPages, &$query ) {
		return $this->container->run(
			'WantedPages::getQueryInfo',
			[ $wantedPages, &$query ]
		);
	}

	public function onWatchArticle( $user, $page, &$status, $expiry ) {
		return $this->container->run(
			'WatchArticle',
			[ $user, $page, &$status, $expiry ]
		);
	}

	public function onWatchArticleComplete( $user, $page ) {
		return $this->container->run(
			'WatchArticleComplete',
			[ $user, $page ]
		);
	}

	public function onWatchedItemQueryServiceExtensions( &$extensions,
		$watchedItemQueryService
	) {
		return $this->container->run(
			'WatchedItemQueryServiceExtensions',
			[ &$extensions, $watchedItemQueryService ]
		);
	}

	public function onWatchlistEditorBeforeFormRender( &$watchlistInfo ) {
		return $this->container->run(
			'WatchlistEditorBeforeFormRender',
			[ &$watchlistInfo ]
		);
	}

	public function onWatchlistEditorBuildRemoveLine( &$tools, $title, $redirect,
		$skin, &$link
	) {
		return $this->container->run(
			'WatchlistEditorBuildRemoveLine',
			[ &$tools, $title, $redirect, $skin, &$link ]
		);
	}

	public function onWebRequestPathInfoRouter( $router ) {
		return $this->container->run(
			'WebRequestPathInfoRouter',
			[ $router ]
		);
	}

	public function onWebResponseSetCookie( &$name, &$value, &$expire, &$options ) {
		return $this->container->run(
			'WebResponseSetCookie',
			[ &$name, &$value, &$expire, &$options ]
		);
	}

	public function onWfShellWikiCmd( &$script, &$parameters, &$options ) {
		return $this->container->run(
			'wfShellWikiCmd',
			[ &$script, &$parameters, &$options ]
		);
	}

	public function onWgQueryPages( &$qp ) {
		return $this->container->run(
			'wgQueryPages',
			[ &$qp ]
		);
	}

	public function onWhatLinksHereProps( $row, $title, $target, &$props ) {
		return $this->container->run(
			'WhatLinksHereProps',
			[ $row, $title, $target, &$props ]
		);
	}

	public function onWikiExporter__dumpStableQuery( &$tables, &$opts, &$join ) {
		return $this->container->run(
			'WikiExporter::dumpStableQuery',
			[ &$tables, &$opts, &$join ]
		);
	}

	public function onWikiPageDeletionUpdates( $page, $content, &$updates ) {
		return $this->container->run(
			'WikiPageDeletionUpdates',
			[ $page, $content, &$updates ]
		);
	}

	public function onWikiPageFactory( $title, &$page ) {
		return $this->container->run(
			'WikiPageFactory',
			[ $title, &$page ]
		);
	}

	public function onXmlDumpWriterOpenPage( $obj, &$out, $row, $title ) {
		return $this->container->run(
			'XmlDumpWriterOpenPage',
			[ $obj, &$out, $row, $title ]
		);
	}

	public function onXmlDumpWriterWriteRevision( $obj, &$out, $row, $text, $rev ) {
		return $this->container->run(
			'XmlDumpWriterWriteRevision',
			[ $obj, &$out, $row, $text, $rev ]
		);
	}
}
