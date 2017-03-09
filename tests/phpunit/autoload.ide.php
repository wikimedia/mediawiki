<?php

/**
 * This file is PHPUnit autoload file for PhpStorm IDE and other JetBrains IDEs.
 *
 * This file should be set in `Languages and frameworks > PHP > PhpUnit`
 * select `Use Composer autoloader` and set `Path to script` to `<path to this file>`.
 * After that, tests can be run in PhpStorm using Right-click > Run or `Ctrl + Shift + F10`.
 * Also, tests can be run with debugger very easily.
 *
 * This file basically does almost the same thing as `tests/phpunit/phpunit.php`, except that all
 * code is going to be executed inside some function, so some hacks needed to make old code to be
 * executed as if it was executed on top of the execution stack.
 *
 * PS: Mostly it is copy-paste from `phpunit.php` and `doMaintenance.php`.
 *
 * @file
 */

// Set a flag which can be used to detect when other scripts have been entered
// through this entry point or not.
use MediaWiki\MediaWikiServices;

global $IP, $LocalInterwikis, $argc, $args, $argv, $canonicalDecomp, $checkBlacklist,
	   $compatibilityDecomp, $dumper, $errlog_fn, $expand, $filter, $messageMemc, $mmfl,
	   $options, $optionsWithArgs, $optionsWithoutArgs, $parserMemc, $someotherglobal, $sort,
	   $tmpdir, $wgAPIListModules, $wgAPIMetaModules, $wgAPIModules, $wgAPIPropModules,
	   $wgActionFilteredLogs, $wgActionPaths, $wgActions, $wgActiveUserDays,
	   $wgAdaptiveMessageCache, $wgAddGroups, $wgAdditionalMailParams,
	   $wgAdvancedSearchHighlighting, $wgAjaxEditStash, $wgAllDBsAreLocalhost,
	   $wgAllUnicodeFixes, $wgAllowCopyUploads, $wgAllowDisplayTitle, $wgAllowExternalImages,
	   $wgAllowExternalImagesFrom, $wgAllowHTMLEmail, $wgAllowImageMoving, $wgAllowImageTag,
	   $wgAllowJavaUploads, $wgAllowSchemaUpdates, $wgAllowSlowParserFunctions,
	   $wgAllowSpecialInclusion, $wgAllowTitlesInSVG, $wgAllowUserCss, $wgAllowUserJs,
	   $wgAmericanDates, $wgAntivirus, $wgAntivirusRequired, $wgAntivirusSetup,
	   $wgApiFrameOptions, $wgApplyIpBlocksToXff, $wgArticleCountMethod, $wgArticlePath,
	   $wgArticleRobotPolicies, $wgAssumeProxiesUseDefaultProtocolPorts,
	   $wgAttemptFailureEpoch, $wgAuth, $wgAuthManagerConfig, $wgAuthenticationTokenVersion,
	   $wgAutoblockExpiry, $wgAutoloadAttemptLowercase, $wgAutoloadClasses,
	   $wgAutoloadLocalClasses, $wgAutopromote, $wgAutopromoteOnce, $wgAutopromoteOnceLogInRC,
	   $wgAvailableRights, $wgBabelCategoryNames, $wgBarBarBar, $wgBlockAllowsUTEdit,
	   $wgBlockCIDRLimit, $wgBlockDisablesLogin, $wgBotPasswordsCluster,
	   $wgBotPasswordsDatabase, $wgBrowserBlackList, $wgCacheDirectory, $wgCacheEpoch,
	   $wgCachePages, $wgCachePrefix, $wgCanonicalNamespaceNames, $wgCanonicalServer,
	   $wgCapitalLinkOverrides, $wgCapitalLinks, $wgCapitalizeTitle,
	   $wgCascadingRestrictionLevels, $wgCategoryCollation, $wgCdnReboundPurgeDelay,
	   $wgCentralIdLookupProvider, $wgCentralIdLookupProviders, $wgCheckFileExtensions,
	   $wgCleanSignatures, $wgClockSkewFudge, $wgCommandLineDarkBg, $wgCommandLineMode,
	   $wgCompressRevisions, $wgConf, $wgContLang, $wgContentHandlerTextFallback,
	   $wgContentHandlerUseDB, $wgContentHandlers, $wgContentNamespaces, $wgCookieDomain,
	   $wgCookieExpiration, $wgCookieHttpOnly, $wgCookiePath, $wgCookiePrefix, $wgCookieSecure,
	   $wgCookieSetOnAutoblock, $wgCopyUploadProxy, $wgCopyUploadTimeout,
	   $wgCopyUploadsDomains, $wgCustomConvertCommand, $wgDBName, $wgDBOracleDRCP,
	   $wgDBTableOptions, $wgDBWindowsAuthentication, $wgDBadminpassword, $wgDBadminuser,
	   $wgDBerrorLog, $wgDBerrorLogTZ, $wgDBmysql5, $wgDBname, $wgDBpassword, $wgDBprefix,
	   $wgDBserver, $wgDBservers, $wgDBtype, $wgDBuser, $wgDebugComments, $wgDebugLogFile,
	   $wgDebugLogGroups, $wgDebugLogPrefix, $wgDebugRawPage, $wgDebugTidy, $wgDebugTimestamps,
	   $wgDebugToolbar, $wgDefaultExternalStore, $wgDefaultLanguageVariant,
	   $wgDefaultRobotPolicy, $wgDefaultSkin, $wgDefaultUserOptions, $wgDeleteRevisionsLimit,
	   $wgDeprecationReleaseLimit, $wgDevelopmentWarnings, $wgDiff, $wgDiff3, $wgDirectoryMode,
	   $wgDisableAnonTalk, $wgDisableCookieCheck, $wgDisableInternalSearch,
	   $wgDisableLangConversion, $wgDisableOutputCompression, $wgDisableQueryPageUpdate,
	   $wgDisableSearchUpdate, $wgDisableTitleConversion, $wgDisableUploadScriptChecks,
	   $wgDisableUserGroupExpiry, $wgDisabledVariants, $wgDjvuDump, $wgDjvuOutputExtension,
	   $wgDjvuPostProcessor, $wgDjvuRenderer, $wgDjvuToXML, $wgDjvuTxt, $wgDnsBlacklistUrls,
	   $wgDummyLanguageCodes, $wgEchoNotificationCategories, $wgEchoNotificationIcons,
	   $wgEchoNotifications, $wgEmailAuthentication, $wgEmailConfirmToEdit, $wgEnableAPI,
	   $wgEnableAutoRotation, $wgEnableBotPasswords, $wgEnableDnsBlacklist, $wgEnableEmail,
	   $wgEnableImageWhitelist, $wgEnableJavaScriptTest, $wgEnableMagicLinks,
	   $wgEnableParserLimitReporting, $wgEnableScaryTranscluding, $wgEnableSidebarCache,
	   $wgEnableUploads, $wgEnableUserEmail, $wgEnableWriteAPI, $wgEnotifFromEditor,
	   $wgEnotifImpersonal, $wgEnotifMaxRecips, $wgEnotifMinorEdits,
	   $wgEnotifRevealEditorAddress, $wgEnotifUseRealName, $wgEnotifUserTalk,
	   $wgEnotifWatchlist, $wgExceptionHooks, $wgExemptFromUserRobotsControl, $wgExiftool,
	   $wgExiv2Command, $wgExpensiveParserFunctionLimit, $wgExperiencedUserEdits,
	   $wgExperiencedUserMemberSince, $wgExperimentalHtmlIds, $wgExtModifiedFields,
	   $wgExtNewFields, $wgExtNewIndexes, $wgExtNewTables, $wgExtPGAlteredFields,
	   $wgExtPGNewFields, $wgExtendedLoginCookieExpiration, $wgExtensionAssetsPath,
	   $wgExtensionCredits, $wgExtensionDirectory, $wgExtensionEntryPointListFiles,
	   $wgExtensionFunctions, $wgExtensionInfoMTime, $wgExtensionMessagesFiles,
	   $wgExternalDiffEngine, $wgExternalLinkTarget, $wgExternalStores,
	   $wgExtraGenderNamespaces, $wgExtraInterlanguageLinkPrefixes, $wgExtraLanguageNames,
	   $wgExtraNamespaces, $wgExtraSignatureNamespaces, $wgFallbackSkin, $wgFavicon, $wgFeed,
	   $wgFeedCacheTimeout, $wgFeedClasses, $wgFeedDiffCutoff, $wgFileBackends,
	   $wgFileBlacklist, $wgFileCacheDepth, $wgFileCacheDirectory, $wgFileExtensions,
	   $wgFilterLogTypes, $wgFixArabicUnicode, $wgFixMalayalamUnicode, $wgFooterIcons,
	   $wgForceUIMsgAsContentMsg, $wgForeignFileRepos, $wgFullyInitialised, $wgGitBin,
	   $wgGitInfoCacheDirectory, $wgGitRepositoryViewers, $wgGrammarForms,
	   $wgGrantPermissionGroups, $wgGrantPermissions, $wgGroupPermissions, $wgGroupsAddToSelf,
	   $wgGroupsRemoveFromSelf, $wgHTCPMulticastTTL, $wgHTCPRouting, $wgHTTPConnectTimeout,
	   $wgHTTPImportTimeout, $wgHTTPProxy, $wgHTTPTimeout, $wgHiddenPrefs,
	   $wgHideInterlanguageLinks, $wgHideUserContribLimit, $wgHooks, $wgHtml5Version,
	   $wgHttpsPort, $wgIgnoreImageErrors, $wgIllegalFileChars, $wgImageLimits,
	   $wgImageMagickConvertCommand, $wgImageMagickTempDir, $wgImgAuthDetails,
	   $wgImgAuthUrlPathMap, $wgImplicitGroups, $wgIncludeLegacyJavaScript,
	   $wgInitialSessionId, $wgInternalServer, $wgInterwikiMagic, $wgInvalidRedirectTargets,
	   $wgInvalidUsernameCharacters, $wgJobBackoffThrottling, $wgJobClasses,
	   $wgJobQueueAggregator, $wgJobQueueMigrationConfig, $wgJobSerialCommitThreshold,
	   $wgJobTypeConf, $wgJobTypesExcludedFromDefaultQueue, $wgJpegPixelFormat, $wgJpegTran,
	   $wgJsMimeType, $wgLBFactoryConf, $wgLang, $wgLangObjCacheSize, $wgLanguageCode,
	   $wgLanguageConverterCacheType, $wgLearnerEdits, $wgLearnerMemberSince,
	   $wgLegacyEncoding, $wgLegacySchemaConversion, $wgLegalTitleChars,
	   $wgLinkHolderBatchSize, $wgLoadScript, $wgLocalDatabases, $wgLocalFileRepo,
	   $wgLocalInterwikis, $wgLocalTZoffset, $wgLocalVirtualHosts, $wgLocalisationCacheConf,
	   $wgLocaltimezone, $wgLockManagers, $wgLogActions, $wgLogActionsHandlers,
	   $wgLogAutopatrol, $wgLogExceptionBacktrace, $wgLogHeaders, $wgLogNames,
	   $wgLogRestrictions, $wgLogTypes, $wgLoginLanguageSelector, $wgLogo,
	   $wgMWLoggerDefaultSpi, $wgMainCacheType, $wgMainStash, $wgMainWANCache,
	   $wgMangleFlashPolicy, $wgMaxAnimatedGifArea, $wgMaxArticleSize, $wgMaxCredits,
	   $wgMaxGeneratedPPNodeCount, $wgMaxImageArea, $wgMaxInterlacingAreas,
	   $wgMaxMsgCacheEntrySize, $wgMaxNameChars, $wgMaxPPExpandDepth, $wgMaxPPNodeCount,
	   $wgMaxRedirects, $wgMaxShellFileSize, $wgMaxShellMemory, $wgMaxShellTime,
	   $wgMaxShellWallClockTime, $wgMaxSigChars, $wgMaxTemplateDepth, $wgMaxTocLevel,
	   $wgMaxUploadSize, $wgMaximumMovedPages, $wgMemCachedServers, $wgMemCachedTimeout,
	   $wgMemc, $wgMemoryLimit, $wgMessageCacheType, $wgMessagesDirs, $wgMetaNamespace,
	   $wgMetaNamespaceTalk, $wgMimeType, $wgMimeTypeBlacklist, $wgMinimalPasswordLength,
	   $wgMiserMode, $wgMsgCacheExpiry, $wgNamespaceAliases, $wgNamespaceContentModels,
	   $wgNamespaceProtection, $wgNamespaceRobotPolicies, $wgNamespacesToBeSearchedDefault,
	   $wgNamespacesWithSubpages, $wgNoFollowDomainExceptions, $wgNoFollowLinks,
	   $wgNoFollowNsExceptions, $wgNoReplyAddress, $wgNonincludableNamespaces,
	   $wgNotifyArticle, $wgObjectCaches, $wgOut, $wgOverrideHostname, $wgPageLanguageUseDB,
	   $wgPagePropLinkInvalidations, $wgPagePropsHaveSortkey, $wgParser,
	   $wgParserCacheExpireTime, $wgParserCacheType, $wgParserConf, $wgParserTestFiles,
	   $wgPasswordPolicy, $wgPasswordResetRoutes, $wgPasswordSender, $wgPhpCli,
	   $wgPoolCounterConf, $wgPopularPasswordFile, $wgPreprocessorCacheThreshold,
	   $wgPreviewOnOpenNamespaces, $wgProfileLimit, $wgProfiler, $wgProxyList,
	   $wgProxyWhitelist, $wgPutIPinRC, $wgQueryCacheLimit, $wgRCEngines, $wgRCFeeds,
	   $wgRCMaxAge, $wgRCWatchCategoryMembership, $wgRateLimits, $wgRateLimitsExcludedIPs,
	   $wgRawHtml, $wgReadOnly, $wgReadOnlyFile, $wgRecentChangesFlags, $wgRedirectOnLogin,
	   $wgRedirectSources, $wgRegisterInternalExternals, $wgRemoveGroups, $wgRenderHashAppend,
	   $wgRequest, $wgRequestTime, $wgReservedUsernames, $wgResourceBasePath,
	   $wgResourceLoaderDebug, $wgResourceModules, $wgResponsiveImages,
	   $wgRestrictDisplayTitle, $wgRestrictionLevels, $wgRestrictionTypes, $wgReverseTitle,
	   $wgRevisionCacheExpiry, $wgRevokePermissions, $wgRightsIcon, $wgRightsPage,
	   $wgRightsText, $wgRightsUrl, $wgSMTP, $wgSQLMode, $wgSQLiteDataDir, $wgSVGConverter,
	   $wgSVGConverterPath, $wgSVGConverters, $wgSVGMaxSize, $wgSVGMetadataCutoff, $wgScript,
	   $wgScriptPath, $wgSearchHighlightBoundaries, $wgSearchType, $wgSecretKey,
	   $wgSecureLogin, $wgSemiprotectedRestrictionLevels, $wgSend404Code, $wgServer,
	   $wgServerName, $wgSessionInsecureSecrets, $wgSessionPbkdf2Iterations,
	   $wgSessionProviders, $wgSessionSecret, $wgSharedDB, $wgSharedPrefix, $wgSharedSchema,
	   $wgSharedTables, $wgSharpenParameter, $wgSharpenReductionThreshold, $wgShellCgroup,
	   $wgShellLocale, $wgShowArchiveThumbnails, $wgShowCreditsIfMax, $wgShowDBErrorBacktrace,
	   $wgShowDebug, $wgShowEXIF, $wgShowExceptionDetails, $wgShowHostnames,
	   $wgShowRollbackEditCount, $wgShowSQLErrors, $wgShowUpdatedMarker, $wgSidebarCacheExpiry,
	   $wgSiteNotice, $wgSiteStatsAsyncFactor, $wgSiteTypes, $wgSitemapNamespaces,
	   $wgSitemapNamespacesPriorities, $wgSitename, $wgSkipSkins, $wgSoftBlockRanges,
	   $wgSomething, $wgSpamRegex, $wgSpecialPageCacheUpdates, $wgSpecialPages,
	   $wgSpecialVersionShowHooks, $wgSquidMaxage, $wgSquidPurgeUseHostHeader, $wgSquidServers,
	   $wgStrictFileExtensions, $wgStyleDirectory, $wgStylePath, $wgStyleVersion,
	   $wgSummarySpamRegex, $wgSysopEmailBans, $wgTextModelsToParse, $wgThumbLimits,
	   $wgThumbUpright, $wgThumbnailBuckets, $wgThumbnailEpoch,
	   $wgThumbnailMinimumBucketDistance, $wgTidyBin, $wgTidyConf, $wgTidyConfig,
	   $wgTidyInternal, $wgTidyOpts, $wgTiffThumbnailType, $wgTitle, $wgTmpDirectory,
	   $wgTrackingCategories, $wgTransactionalTimeLimit, $wgTranscludeCacheExpiry,
	   $wgTranslateNumerals, $wgTrivialMimeDetection, $wgTrustedMediaFormats,
	   $wgTrxProfilerLimits, $wgUpdateCompatibleMetadata, $wgUpdateRowsPerJob,
	   $wgUpdateRowsPerQuery, $wgUploadDirectory, $wgUploadMaintenance,
	   $wgUploadMissingFileUrl, $wgUploadNavigationUrl, $wgUploadSizeWarning,
	   $wgUploadStashMaxAge, $wgUploadThumbnailRenderHttpCustomDomain,
	   $wgUploadThumbnailRenderHttpCustomHost, $wgUploadThumbnailRenderMap,
	   $wgUploadThumbnailRenderMethod, $wgUrlProtocols, $wgUseAjax,
	   $wgUseAutomaticEditSummaries, $wgUseCategoryBrowser, $wgUseCombinedLoginLink,
	   $wgUseDatabaseMessages, $wgUseETag, $wgUseEnotif, $wgUseFileCache, $wgUseFilePatrol,
	   $wgUseGzip, $wgUseImageMagick, $wgUseImageResize, $wgUseKeyHeader,
	   $wgUseLocalMessageCache, $wgUseMediaWikiUIEverywhere, $wgUseNPPatrol, $wgUsePathInfo,
	   $wgUsePrivateIPs, $wgUseRCPatrol, $wgUseSquid, $wgUseTagFilter, $wgUseTidy,
	   $wgUseTinyRGBForJPGThumbnails, $wgUser, $wgUserEmailConfirmationTokenExpiry,
	   $wgUserrightsInterwikiDelimiter, $wgUsersNotifiedOnAllChanges, $wgValidateAllHtml,
	   $wgValueParsers, $wgVariantArticlePath, $wgVaryOnXFP, $wgVerifyMimeType, $wgVersion,
	   $wgWANObjectCaches, $wgWBClientDataTypes, $wgWBClientEntityTypes, $wgWBClientSettings,
	   $wgWBRepoDataTypes, $wgWBRepoEntityTypes, $wgWBRepoSettings, $wgWhitelistRead,
	   $wgWhitelistReadRegexp, $wgWikibaseInterwikiSorting, $wgXhtmlNamespaces;

$argv[1] = '--wiki';
$argv[2] = getenv( 'WIKI_NAME' ) ?: 'wiki';

require_once __DIR__ . "/phpunit.php";

// Get an object to start us off
/** @var Maintenance $maintenance */
$maintenance = new PHPUnitMaintClass();

// Basic sanity checks and such
$maintenance->setup();

// We used to call this variable $self, but it was moved
// to $maintenance->mSelf. Keep that here for b/c
$self = $maintenance->getName();

# Start the autoloader, so that extensions can derive classes from core files
require_once "$IP/includes/AutoLoader.php";
# Grab profiling functions
require_once "$IP/includes/profiler/ProfilerFunctions.php";

# Start the profiler
$wgProfiler = [];
if ( file_exists( "$IP/StartProfiler.php" ) ) {
	require "$IP/StartProfiler.php";
}

$requireOnceGlobalsScope = function ( $file ) use ( $self ) {
	foreach ( array_keys( $GLOBALS ) as $varName ) {
		eval( sprintf( 'global $%s;', $varName ) );
	}

	require_once $file;

	unset( $file );
	$definedVars = get_defined_vars();
	foreach ( $definedVars as $varName => $value ) {
		eval( sprintf( 'global $%s; $%s = $value;', $varName, $varName ) );
	}
};

// Some other requires
$requireOnceGlobalsScope( "$IP/includes/Defines.php" );
$requireOnceGlobalsScope( "$IP/includes/DefaultSettings.php" );
$requireOnceGlobalsScope( "$IP/includes/GlobalFunctions.php" );

foreach ( array_keys( $GLOBALS ) as $varName ) {
	eval( sprintf( 'global $%s;', $varName ) );
}

# Load composer's autoloader if present
if ( is_readable( "$IP/vendor/autoload.php" ) ) {
	require_once "$IP/vendor/autoload.php";
}

if ( defined( 'MW_CONFIG_CALLBACK' ) ) {
	# Use a callback function to configure MediaWiki
	call_user_func( MW_CONFIG_CALLBACK );
} else {
	// Require the configuration (probably LocalSettings.php)
	require $maintenance->loadSettings();
}

if ( $maintenance->getDbType() === Maintenance::DB_NONE ) {
	if (
		$wgLocalisationCacheConf['storeClass'] === false
		&& (
			$wgLocalisationCacheConf['store'] == 'db'
			|| ( $wgLocalisationCacheConf['store'] == 'detect' && !$wgCacheDirectory )
		)
	) {
		$wgLocalisationCacheConf['storeClass'] = 'LCStoreNull';
	}
}

$maintenance->finalSetup();
// Some last includes
$requireOnceGlobalsScope( "$IP/includes/Setup.php" );

// Initialize main config instance
$maintenance->setConfig( MediaWikiServices::getInstance()->getMainConfig() );

// Sanity-check required extensions are installed
$maintenance->checkRequiredExtensions();

// A good time when no DBs have writes pending is around lag checks.
// This avoids having long running scripts just OOM and lose all the updates.
$maintenance->setAgentAndTriggers();
