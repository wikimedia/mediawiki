<?php

namespace MediaWiki\Api;

use MediaWiki\HookContainer\HookContainer;

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
	Hook\APIQueryGeneratorAfterExecuteHook,
	Hook\APIQueryInfoTokensHook,
	Hook\APIQueryRecentChangesTokensHook,
	Hook\APIQueryRevisionsTokensHook,
	Hook\APIQuerySiteInfoGeneralInfoHook,
	Hook\APIQuerySiteInfoStatisticsInfoHook,
	Hook\ApiQueryTokensRegisterTypesHook,
	Hook\APIQueryUsersTokensHook,
	Hook\ApiQueryWatchlistExtractOutputDataHook,
	Hook\ApiQueryWatchlistPrepareWatchedItemQueryServiceOptionsHook,
	Hook\ApiQuery__moduleManagerHook,
	Hook\ApiRsdServiceApisHook,
	Hook\ApiTokensGetTokenTypesHook,
	Hook\ApiValidatePasswordHook,
	Hook\RequestHasSameOriginSecurityHook,
	\MediaWiki\Hook\EditFormPreloadTextHook,
	\MediaWiki\Hook\FileUndeleteCompleteHook,
	\MediaWiki\Hook\ImportSourcesHook,
	\MediaWiki\Hook\LanguageLinksHook,
	\MediaWiki\Hook\OutputPageCheckLastModifiedHook,
	\MediaWiki\Hook\UserLoginCompleteHook,
	\MediaWiki\Hook\UserLogoutCompleteHook,
	\MediaWiki\SpecialPage\Hook\ChangeAuthenticationDataAuditHook
{
	/** @var HookContainer */
	private $container;

	public function __construct( HookContainer $container ) {
		$this->container = $container;
	}

	public function onAPIAfterExecute( $module ) {
		return $this->container->run(
			'APIAfterExecute',
			[ $module ]
		);
	}

	public function onApiCheckCanExecute( $module, $user, &$message ) {
		return $this->container->run(
			'ApiCheckCanExecute',
			[ $module, $user, &$message ]
		);
	}

	public function onApiDeprecationHelp( &$msgs ) {
		return $this->container->run(
			'ApiDeprecationHelp',
			[ &$msgs ]
		);
	}

	public function onApiFeedContributions__feedItem( $row, $context, &$feedItem ) {
		return $this->container->run(
			'ApiFeedContributions::feedItem',
			[ $row, $context, &$feedItem ]
		);
	}

	public function onApiFormatHighlight( $context, $text, $mime, $format ) {
		return $this->container->run(
			'ApiFormatHighlight',
			[ $context, $text, $mime, $format ]
		);
	}

	public function onAPIGetAllowedParams( $module, &$params, $flags ) {
		return $this->container->run(
			'APIGetAllowedParams',
			[ $module, &$params, $flags ]
		);
	}

	public function onAPIGetDescriptionMessages( $module, &$msg ) {
		return $this->container->run(
			'APIGetDescriptionMessages',
			[ $module, &$msg ]
		);
	}

	public function onAPIGetParamDescriptionMessages( $module, &$msg ) {
		return $this->container->run(
			'APIGetParamDescriptionMessages',
			[ $module, &$msg ]
		);
	}

	public function onAPIHelpModifyOutput( $module, &$help, $options, &$tocData ) {
		return $this->container->run(
			'APIHelpModifyOutput',
			[ $module, &$help, $options, &$tocData ]
		);
	}

	public function onApiMain__moduleManager( $moduleManager ) {
		return $this->container->run(
			'ApiMain::moduleManager',
			[ $moduleManager ]
		);
	}

	public function onApiMain__onException( $apiMain, $e ) {
		return $this->container->run(
			'ApiMain::onException',
			[ $apiMain, $e ]
		);
	}

	public function onApiMakeParserOptions( $options, $title, $params, $module,
		&$reset, &$suppressCache
	) {
		return $this->container->run(
			'ApiMakeParserOptions',
			[ $options, $title, $params, $module, &$reset, &$suppressCache ]
		);
	}

	public function onApiMaxLagInfo( &$lagInfo ) : void {
		$this->container->run(
			'ApiMaxLagInfo',
			[ &$lagInfo ],
			[ 'abortable' => false ]
		);
	}

	public function onApiOpenSearchSuggest( &$results ) {
		return $this->container->run(
			'ApiOpenSearchSuggest',
			[ &$results ]
		);
	}

	public function onApiOptions( $apiModule, $user, $changes, $resetKinds ) {
		return $this->container->run(
			'ApiOptions',
			[ $apiModule, $user, $changes, $resetKinds ]
		);
	}

	public function onApiParseMakeOutputPage( $module, $output ) {
		return $this->container->run(
			'ApiParseMakeOutputPage',
			[ $module, $output ]
		);
	}

	public function onAPIQueryAfterExecute( $module ) {
		return $this->container->run(
			'APIQueryAfterExecute',
			[ $module ]
		);
	}

	public function onApiQueryBaseAfterQuery( $module, $result, &$hookData ) {
		return $this->container->run(
			'ApiQueryBaseAfterQuery',
			[ $module, $result, &$hookData ]
		);
	}

	public function onApiQueryBaseBeforeQuery( $module, &$tables, &$fields,
		&$conds, &$query_options, &$join_conds, &$hookData
	) {
		return $this->container->run(
			'ApiQueryBaseBeforeQuery',
			[ $module, &$tables, &$fields, &$conds, &$query_options,
				&$join_conds, &$hookData ]
		);
	}

	public function onApiQueryBaseProcessRow( $module, $row, &$data, &$hookData ) {
		return $this->container->run(
			'ApiQueryBaseProcessRow',
			[ $module, $row, &$data, &$hookData ]
		);
	}

	public function onAPIQueryGeneratorAfterExecute( $module, $resultPageSet ) {
		return $this->container->run(
			'APIQueryGeneratorAfterExecute',
			[ $module, $resultPageSet ]
		);
	}

	public function onAPIQueryInfoTokens( &$tokenFunctions ) {
		return $this->container->run(
			'APIQueryInfoTokens',
			[ &$tokenFunctions ]
		);
	}

	public function onAPIQueryRecentChangesTokens( &$tokenFunctions ) {
		return $this->container->run(
			'APIQueryRecentChangesTokens',
			[ &$tokenFunctions ]
		);
	}

	public function onAPIQueryRevisionsTokens( &$tokenFunctions ) {
		return $this->container->run(
			'APIQueryRevisionsTokens',
			[ &$tokenFunctions ]
		);
	}

	public function onAPIQuerySiteInfoGeneralInfo( $module, &$results ) {
		return $this->container->run(
			'APIQuerySiteInfoGeneralInfo',
			[ $module, &$results ]
		);
	}

	public function onAPIQuerySiteInfoStatisticsInfo( &$results ) {
		return $this->container->run(
			'APIQuerySiteInfoStatisticsInfo',
			[ &$results ]
		);
	}

	public function onApiQueryTokensRegisterTypes( &$salts ) {
		return $this->container->run(
			'ApiQueryTokensRegisterTypes',
			[ &$salts ]
		);
	}

	public function onAPIQueryUsersTokens( &$tokenFunctions ) {
		return $this->container->run(
			'APIQueryUsersTokens',
			[ &$tokenFunctions ]
		);
	}

	public function onApiQueryWatchlistExtractOutputData( $module, $watchedItem,
		$recentChangeInfo, &$vals
	) {
		return $this->container->run(
			'ApiQueryWatchlistExtractOutputData',
			[ $module, $watchedItem, $recentChangeInfo, &$vals ]
		);
	}

	public function onApiQueryWatchlistPrepareWatchedItemQueryServiceOptions(
		$module, $params, &$options
	) {
		return $this->container->run(
			'ApiQueryWatchlistPrepareWatchedItemQueryServiceOptions',
			[ $module, $params, &$options ]
		);
	}

	public function onApiQuery__moduleManager( $moduleManager ) {
		return $this->container->run(
			'ApiQuery::moduleManager',
			[ $moduleManager ]
		);
	}

	public function onApiRsdServiceApis( &$apis ) {
		return $this->container->run(
			'ApiRsdServiceApis',
			[ &$apis ]
		);
	}

	public function onApiTokensGetTokenTypes( &$tokenTypes ) {
		return $this->container->run(
			'ApiTokensGetTokenTypes',
			[ &$tokenTypes ]
		);
	}

	public function onApiValidatePassword( $module, &$r ) {
		return $this->container->run(
			'ApiValidatePassword',
			[ $module, &$r ]
		);
	}

	public function onChangeAuthenticationDataAudit( $req, $status ) {
		return $this->container->run(
			'ChangeAuthenticationDataAudit',
			[ $req, $status ]
		);
	}

	public function onEditFormPreloadText( &$text, $title ) {
		return $this->container->run(
			'EditFormPreloadText',
			[ &$text, $title ]
		);
	}

	public function onFileUndeleteComplete( $title, $fileVersions, $user, $reason ) {
		return $this->container->run(
			'FileUndeleteComplete',
			[ $title, $fileVersions, $user, $reason ]
		);
	}

	public function onImportSources( &$importSources ) {
		return $this->container->run(
			'ImportSources',
			[ &$importSources ]
		);
	}

	public function onLanguageLinks( $title, &$links, &$linkFlags ) {
		return $this->container->run(
			'LanguageLinks',
			[ $title, &$links, &$linkFlags ]
		);
	}

	public function onOutputPageCheckLastModified( &$modifiedTimes, $out ) {
		return $this->container->run(
			'OutputPageCheckLastModified',
			[ &$modifiedTimes, $out ]
		);
	}

	public function onRequestHasSameOriginSecurity( $request ) {
		return $this->container->run(
			'RequestHasSameOriginSecurity',
			[ $request ]
		);
	}

	public function onUserLoginComplete( $user, &$inject_html, $direct ) {
		return $this->container->run(
			'UserLoginComplete',
			[ $user, &$inject_html, $direct ]
		);
	}

	public function onUserLogoutComplete( $user, &$inject_html, $oldName ) {
		return $this->container->run(
			'UserLogoutComplete',
			[ $user, &$inject_html, $oldName ]
		);
	}
}
