<?php
use MediaWiki\MediaWikiServices;

/**
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
 * @since 1.28
 */

/**
 * Traits for API components that use a SearchEngine.
 * @ingroup API
 */
trait SearchApi {

	/**
	 * When $wgSearchType is null, $wgSearchAlternatives[0] is null. Null isn't
	 * a valid option for an array for PARAM_TYPE, so we'll use a fake name
	 * that can't possibly be a class name and describes what the null behavior
	 * does
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
		$params = [
			'search' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			],
			'namespace' => [
				ApiBase::PARAM_DFLT => NS_MAIN,
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_ISMULTI => true,
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2,
			],
		];
		if ( $isScrollable ) {
			$params['offset'] = [
				ApiBase::PARAM_DFLT => 0,
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			];
		}

		$searchConfig = MediaWikiServices::getInstance()->getSearchEngineConfig();
		$alternatives = $searchConfig->getSearchTypes();
		if ( count( $alternatives ) > 1 ) {
			if ( $alternatives[0] === null ) {
				$alternatives[0] = self::$BACKEND_NULL_PARAM;
			}
			$this->allowedParams['backend'] = [
				ApiBase::PARAM_DFLT => $searchConfig->getSearchType(),
				ApiBase::PARAM_TYPE => $alternatives,
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
	 */
	private function buildProfileApiParam() {
		$configs = $this->getSearchProfileParams();
		$searchEngine = MediaWikiServices::getInstance()->newSearchEngine();
		$params = [];
		foreach ( $configs as $paramName => $paramConfig ) {
			$profiles = $searchEngine->getProfiles( $paramConfig['profile-type'],
				$this->getContext()->getUser() );
			if ( !$profiles ) {
				continue;
			}

			$types = [];
			$helpMessages = [];
			$defaultProfile = null;
			foreach ( $profiles as $profile ) {
				$types[] = $profile['name'];
				if ( isset ( $profile['desc-message'] ) ) {
					$helpMessages[$profile['name']] = $profile['desc-message'];
				}
				if ( !empty( $profile['default'] ) ) {
					$defaultProfile = $profile['name'];
				}
			}

			$params[$paramName] = [
				ApiBase::PARAM_TYPE => $types,
				ApiBase::PARAM_HELP_MSG => $paramConfig['help-message'],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => $helpMessages,
				ApiBase::PARAM_DFLT => $defaultProfile,
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
	 *  - offset: optional, if set limit will be incremented by
	 *    one ( to support the continue parameter )
	 *  - namespace: mandatory
	 *  - search engine profiles defined by SearchApi::getSearchProfileParams()
	 * @param string[]|null API request params (must be sanitized by
	 * ApiBase::extractRequestParams() before)
	 * @return SearchEngine the search engine
	 */
	public function buildSearchEngine( array $params = null ) {
		if ( $params != null ) {
			$type = isset( $params['backend'] ) ? $params['backend'] : null;
			if ( $type === self::$BACKEND_NULL_PARAM ) {
				$type = null;
			}
			$searchEngine = MediaWikiServices::getInstance()->getSearchEngineFactory()->create( $type );
			$limit = $params['limit'];
			$searchEngine->setNamespaces( $params['namespace'] );
			$offset = null;
			if ( isset( $params['offset'] ) ) {
				// If the API supports offset then it probably
				// wants to fetch limit+1 so it can check if
				// more results are available to properly set
				// the continue param
				$offset = $params['offset'];
				$limit += 1;
			}
			$searchEngine->setLimitOffset( $limit, $offset );

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
		} else {
			$searchEngine = MediaWikiServices::getInstance()->newSearchEngine();
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
