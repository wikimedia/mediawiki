<?php

namespace MediaWiki\Api;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Page\Article;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Session\Session;
use MediaWiki\User\UserIdentity;

/**
 * This class provides an implementation of the hook interfaces used
 * by the core Action API, forwarding hook calls to HookContainer for
 * dispatch to extensions.
 *
 * To get an instance, use ApiBase::getHookRunner().
 */
class ApiHookRunner implements
	Hook\APIAfterExecuteHook,
	Hook\ApiCheckCanExecuteHook,
	Hook\ApiDeprecationHelpHook,
	Hook\ApiLogFeatureUsageHook,
	Hook\ApiFeedContributions__feedItemHook,
	Hook\ApiFormatHighlightHook,
	Hook\APIGetAllowedParamsHook,
	Hook\APIGetDescriptionMessagesHook,
	Hook\APIGetParamDescriptionMessagesHook,
	Hook\APIHelpModifyOutputHook,
	Hook\ApiMain__moduleManagerHook,
	Hook\ApiMain__onExceptionHook,
	Hook\ApiMakeParserOptionsHook,
	Hook\ApiMaxLagInfoHook,
	Hook\ApiOpenSearchSuggestHook,
	Hook\ApiOptionsHook,
	Hook\ApiParseMakeOutputPageHook,
	Hook\APIQueryAfterExecuteHook,
	Hook\ApiQueryBaseAfterQueryHook,
	Hook\ApiQueryBaseBeforeQueryHook,
	Hook\ApiQueryBaseProcessRowHook,
	Hook\ApiQueryCheckCanExecuteHook,
	Hook\APIQueryGeneratorAfterExecuteHook,
	Hook\APIQuerySiteInfoGeneralInfoHook,
	Hook\APIQuerySiteInfoStatisticsInfoHook,
	Hook\ApiQueryTokensRegisterTypesHook,
	Hook\ApiQueryWatchlistExtractOutputDataHook,
	Hook\ApiQueryWatchlistPrepareWatchedItemQueryServiceOptionsHook,
	Hook\ApiQuery__moduleManagerHook,
	Hook\ApiRsdServiceApisHook,
	Hook\ApiValidatePasswordHook,
	Hook\RequestHasSameOriginSecurityHook,
	\MediaWiki\Hook\EditFormPreloadTextHook,
	\MediaWiki\Hook\FileUndeleteCompleteHook,
	\MediaWiki\Hook\GetLinkColoursHook,
	\MediaWiki\Hook\ImportSourcesHook,
	\MediaWiki\Output\Hook\LanguageLinksHook,
	\MediaWiki\Output\Hook\OutputPageBeforeHTMLHook,
	\MediaWiki\Output\Hook\OutputPageCheckLastModifiedHook,
	\MediaWiki\Page\Hook\ArticleParserOptionsHook,
	\MediaWiki\Hook\TempUserCreatedRedirectHook,
	\MediaWiki\Hook\UserLoginCompleteHook,
	\MediaWiki\Hook\UserLogoutCompleteHook,
	\MediaWiki\SpecialPage\Hook\ChangeAuthenticationDataAuditHook
{
	private HookContainer $container;

	public function __construct( HookContainer $container ) {
		$this->container = $container;
	}

	/** @inheritDoc */
	public function onAPIAfterExecute( $module ) {
		return $this->container->run(
			'APIAfterExecute',
			[ $module ]
		);
	}

	/** @inheritDoc */
	public function onApiCheckCanExecute( $module, $user, &$message ) {
		return $this->container->run(
			'ApiCheckCanExecute',
			[ $module, $user, &$message ]
		);
	}

	/** @inheritDoc */
	public function onApiDeprecationHelp( &$msgs ) {
		return $this->container->run(
			'ApiDeprecationHelp',
			[ &$msgs ]
		);
	}

	/** @inheritDoc */
	public function onApiFeedContributions__feedItem( $row, $context, &$feedItem ) {
		return $this->container->run(
			'ApiFeedContributions::feedItem',
			[ $row, $context, &$feedItem ]
		);
	}

	/** @inheritDoc */
	public function onApiFormatHighlight( $context, $text, $mime, $format ) {
		return $this->container->run(
			'ApiFormatHighlight',
			[ $context, $text, $mime, $format ]
		);
	}

	/** @inheritDoc */
	public function onAPIGetAllowedParams( $module, &$params, $flags ) {
		return $this->container->run(
			'APIGetAllowedParams',
			[ $module, &$params, $flags ]
		);
	}

	/** @inheritDoc */
	public function onAPIGetDescriptionMessages( $module, &$msg ) {
		return $this->container->run(
			'APIGetDescriptionMessages',
			[ $module, &$msg ]
		);
	}

	/** @inheritDoc */
	public function onAPIGetParamDescriptionMessages( $module, &$msg ) {
		return $this->container->run(
			'APIGetParamDescriptionMessages',
			[ $module, &$msg ]
		);
	}

	/** @inheritDoc */
	public function onAPIHelpModifyOutput( $module, &$help, $options, &$tocData ) {
		return $this->container->run(
			'APIHelpModifyOutput',
			[ $module, &$help, $options, &$tocData ]
		);
	}

	/** @inheritDoc */
	public function onApiLogFeatureUsage( $feature, array $clientInfo ): void {
		$this->container->run(
			'ApiLogFeatureUsage',
			[ $feature, $clientInfo ]
		);
	}

	/** @inheritDoc */
	public function onApiMain__moduleManager( $moduleManager ) {
		return $this->container->run(
			'ApiMain::moduleManager',
			[ $moduleManager ]
		);
	}

	/** @inheritDoc */
	public function onApiMain__onException( $apiMain, $e ) {
		return $this->container->run(
			'ApiMain::onException',
			[ $apiMain, $e ]
		);
	}

	/** @inheritDoc */
	public function onApiMakeParserOptions( $options, $title, $params, $module,
		&$reset, &$suppressCache
	) {
		return $this->container->run(
			'ApiMakeParserOptions',
			[ $options, $title, $params, $module, &$reset, &$suppressCache ]
		);
	}

	/** @inheritDoc */
	public function onApiMaxLagInfo( &$lagInfo ): void {
		$this->container->run(
			'ApiMaxLagInfo',
			[ &$lagInfo ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onApiOpenSearchSuggest( &$results ) {
		return $this->container->run(
			'ApiOpenSearchSuggest',
			[ &$results ]
		);
	}

	/** @inheritDoc */
	public function onApiOptions( $apiModule, $user, $changes, $resetKinds ) {
		return $this->container->run(
			'ApiOptions',
			[ $apiModule, $user, $changes, $resetKinds ]
		);
	}

	/** @inheritDoc */
	public function onApiParseMakeOutputPage( $module, $output ) {
		return $this->container->run(
			'ApiParseMakeOutputPage',
			[ $module, $output ]
		);
	}

	/** @inheritDoc */
	public function onAPIQueryAfterExecute( $module ) {
		return $this->container->run(
			'APIQueryAfterExecute',
			[ $module ]
		);
	}

	/** @inheritDoc */
	public function onApiQueryBaseAfterQuery( $module, $result, &$hookData ) {
		return $this->container->run(
			'ApiQueryBaseAfterQuery',
			[ $module, $result, &$hookData ]
		);
	}

	/** @inheritDoc */
	public function onApiQueryBaseBeforeQuery( $module, &$tables, &$fields,
		&$conds, &$query_options, &$join_conds, &$hookData
	) {
		return $this->container->run(
			'ApiQueryBaseBeforeQuery',
			[ $module, &$tables, &$fields, &$conds, &$query_options,
				&$join_conds, &$hookData ]
		);
	}

	/** @inheritDoc */
	public function onApiQueryBaseProcessRow( $module, $row, &$data, &$hookData ) {
		return $this->container->run(
			'ApiQueryBaseProcessRow',
			[ $module, $row, &$data, &$hookData ]
		);
	}

	/** @inheritDoc */
	public function onApiQueryCheckCanExecute( $modules, $authority, &$message ) {
		return $this->container->run(
			'ApiQueryCheckCanExecute',
			[ $modules, $authority, &$message ]
		);
	}

	/** @inheritDoc */
	public function onAPIQueryGeneratorAfterExecute( $module, $resultPageSet ) {
		return $this->container->run(
			'APIQueryGeneratorAfterExecute',
			[ $module, $resultPageSet ]
		);
	}

	/** @inheritDoc */
	public function onAPIQuerySiteInfoGeneralInfo( $module, &$results ) {
		return $this->container->run(
			'APIQuerySiteInfoGeneralInfo',
			[ $module, &$results ]
		);
	}

	/** @inheritDoc */
	public function onAPIQuerySiteInfoStatisticsInfo( &$results ) {
		return $this->container->run(
			'APIQuerySiteInfoStatisticsInfo',
			[ &$results ]
		);
	}

	/** @inheritDoc */
	public function onApiQueryTokensRegisterTypes( &$salts ) {
		return $this->container->run(
			'ApiQueryTokensRegisterTypes',
			[ &$salts ]
		);
	}

	/** @inheritDoc */
	public function onApiQueryWatchlistExtractOutputData( $module, $watchedItem,
		$recentChangeInfo, &$vals
	) {
		return $this->container->run(
			'ApiQueryWatchlistExtractOutputData',
			[ $module, $watchedItem, $recentChangeInfo, &$vals ]
		);
	}

	/** @inheritDoc */
	public function onApiQueryWatchlistPrepareWatchedItemQueryServiceOptions(
		$module, $params, &$options
	) {
		return $this->container->run(
			'ApiQueryWatchlistPrepareWatchedItemQueryServiceOptions',
			[ $module, $params, &$options ]
		);
	}

	/** @inheritDoc */
	public function onApiQuery__moduleManager( $moduleManager ) {
		return $this->container->run(
			'ApiQuery::moduleManager',
			[ $moduleManager ]
		);
	}

	/** @inheritDoc */
	public function onApiRsdServiceApis( &$apis ) {
		return $this->container->run(
			'ApiRsdServiceApis',
			[ &$apis ]
		);
	}

	/** @inheritDoc */
	public function onApiValidatePassword( $module, &$r ) {
		return $this->container->run(
			'ApiValidatePassword',
			[ $module, &$r ]
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
	public function onChangeAuthenticationDataAudit( $req, $status ) {
		return $this->container->run(
			'ChangeAuthenticationDataAudit',
			[ $req, $status ]
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
	public function onFileUndeleteComplete( $title, $fileVersions, $user, $reason ) {
		return $this->container->run(
			'FileUndeleteComplete',
			[ $title, $fileVersions, $user, $reason ]
		);
	}

	/** @inheritDoc */
	public function onGetLinkColours( $pagemap, &$classes, $title ) {
		return $this->container->run(
			'GetLinkColours',
			[ $pagemap, &$classes, $title ]
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
	public function onLanguageLinks( $title, &$links, &$linkFlags ) {
		return $this->container->run(
			'LanguageLinks',
			[ $title, &$links, &$linkFlags ]
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
	public function onOutputPageCheckLastModified( &$modifiedTimes, $out ) {
		return $this->container->run(
			'OutputPageCheckLastModified',
			[ &$modifiedTimes, $out ]
		);
	}

	/** @inheritDoc */
	public function onRequestHasSameOriginSecurity( $request ) {
		return $this->container->run(
			'RequestHasSameOriginSecurity',
			[ $request ]
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
	public function onUserLoginComplete( $user, &$inject_html, $direct ) {
		return $this->container->run(
			'UserLoginComplete',
			[ $user, &$inject_html, $direct ]
		);
	}

	/** @inheritDoc */
	public function onUserLogoutComplete( $user, &$inject_html, $oldName ) {
		return $this->container->run(
			'UserLogoutComplete',
			[ $user, &$inject_html, $oldName ]
		);
	}
}
