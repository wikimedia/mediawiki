<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @since 1.28
 */

namespace MediaWiki\Api;

use LogicException;
use MediaWiki\Context\IContextSource;
use SearchEngine;
use SearchEngineConfig;
use SearchEngineFactory;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * Traits for API components that use a SearchEngine.
 * @ingroup API
 */
trait SearchApi {

	private ?SearchEngineConfig $searchEngineConfig = null;
	private ?SearchEngineFactory $searchEngineFactory = null;

	private function checkDependenciesSet() {
		// Since this is a trait, we can't have a constructor where the services
		// that we need are injected. Instead, the api modules that use this trait
		// are responsible for setting them (since api modules *can* have services
		// injected). Double check that the api module did indeed set them
		if ( $this->searchEngineConfig === null || $this->searchEngineFactory === null ) {
			throw new LogicException(
				'SearchApi requires both a SearchEngineConfig and SearchEngineFactory to be set'
			);
		}
	}

	/**
	 * When $wgSearchType is null, $wgSearchAlternatives[0] is null. Null isn't
	 * a valid option for an array for PARAM_TYPE, so we'll use a fake name
	 * that can't possibly be a class name and describes what the null behavior
	 * does
	 * @var string
	 */
	private static $BACKEND_NULL_PARAM = 'database-backed';

	/**
	 * The set of api parameters that are shared between api calls that
	 * call the SearchEngine. Primarily this defines parameters that
	 * are utilized by self::buildSearchEngine().
	 *
	 * @param bool $isScrollable True if the api offers scrolling
	 * @return array
	 */
	public function buildCommonApiParams( $isScrollable = true ) {
		$this->checkDependenciesSet();

		$params = [
			'search' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'namespace' => [
				ParamValidator::PARAM_DEFAULT => NS_MAIN,
				ParamValidator::PARAM_TYPE => 'namespace',
				ParamValidator::PARAM_ISMULTI => true,
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2,
			],
		];
		if ( $isScrollable ) {
			$params['offset'] = [
				ParamValidator::PARAM_DEFAULT => 0,
				IntegerDef::PARAM_MIN => 0,
				ParamValidator::PARAM_TYPE => 'integer',
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			];
		}

		$alternatives = $this->searchEngineConfig->getSearchTypes();
		if ( count( $alternatives ) > 1 ) {
			$alternatives[0] ??= self::$BACKEND_NULL_PARAM;
			$params['backend'] = [
				ParamValidator::PARAM_DEFAULT => $this->searchEngineConfig->getSearchType(),
				ParamValidator::PARAM_TYPE => $alternatives,
			];
			// @todo: support profile selection when multiple
			// backends are available. The solution could be to
			// merge all possible profiles and let ApiBase
			// subclasses do the check. Making ApiHelp and ApiSandbox
			// comprehensive might be more difficult.
		} else {
			$params += $this->buildProfileApiParam();
		}

		return $params;
	}

	/**
	 * Build the profile api param definitions. Makes bold assumption only one search
	 * engine is available, ensure that is true before calling.
	 *
	 * @return array array containing available additional api param definitions.
	 *  Empty if profiles are not supported by the searchEngine implementation.
	 * @suppress PhanTypeMismatchDimFetch
	 */
	private function buildProfileApiParam() {
		$this->checkDependenciesSet();

		$configs = $this->getSearchProfileParams();
		$searchEngine = $this->searchEngineFactory->create();
		$params = [];
		foreach ( $configs as $paramName => $paramConfig ) {
			$profiles = $searchEngine->getProfiles(
				$paramConfig['profile-type'],
				$this->getContext()->getUser()
			);
			if ( !$profiles ) {
				continue;
			}

			$types = [];
			$helpMessages = [];
			$defaultProfile = null;
			foreach ( $profiles as $profile ) {
				$types[] = $profile['name'];
				if ( isset( $profile['desc-message'] ) ) {
					$helpMessages[$profile['name']] = $profile['desc-message'];
				}

				if ( !empty( $profile['default'] ) ) {
					$defaultProfile = $profile['name'];
				}
			}

			$params[$paramName] = [
				ParamValidator::PARAM_TYPE => $types,
				ApiBase::PARAM_HELP_MSG => $paramConfig['help-message'],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => $helpMessages,
				ParamValidator::PARAM_DEFAULT => $defaultProfile,
			];
		}

		return $params;
	}

	/**
	 * Build the search engine to use.
	 * If $params is provided then the following searchEngine options
	 * will be set:
	 *  - backend: which search backend to use
	 *  - limit: mandatory
	 *  - offset: optional
	 *  - namespace: mandatory
	 *  - search engine profiles defined by SearchApi::getSearchProfileParams()
	 * @param array|null $params API request params (must be sanitized by
	 * ApiBase::extractRequestParams() before)
	 * @return SearchEngine
	 */
	public function buildSearchEngine( ?array $params = null ) {
		$this->checkDependenciesSet();

		if ( $params == null ) {
			return $this->searchEngineFactory->create();
		}

		$type = $params['backend'] ?? null;
		if ( $type === self::$BACKEND_NULL_PARAM ) {
			$type = null;
		}
		$searchEngine = $this->searchEngineFactory->create( $type );
		$searchEngine->setNamespaces( $params['namespace'] );
		$searchEngine->setLimitOffset( $params['limit'], $params['offset'] ?? 0 );

		// Initialize requested search profiles.
		$configs = $this->getSearchProfileParams();
		foreach ( $configs as $paramName => $paramConfig ) {
			if ( isset( $params[$paramName] ) ) {
				$searchEngine->setFeatureData(
					$paramConfig['profile-type'],
					$params[$paramName]
				);
			}
		}
		return $searchEngine;
	}

	/**
	 * @return array[] array of arrays mapping from parameter name to a two value map
	 *  containing 'help-message' and 'profile-type' keys.
	 */
	abstract public function getSearchProfileParams();

	/**
	 * @return IContextSource
	 */
	abstract public function getContext();
}

/** @deprecated class alias since 1.43 */
class_alias( SearchApi::class, 'SearchApi' );
