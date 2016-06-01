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
	 * Build the profile api param definitions.
	 *
	 * @param string $profileType type of profile to customize
	 * @param string $helpMsg i18n message
	 * @param string|null $backendType SearchEngine backend type or null for default engine
	 * @return array|null the api param definition or null if profiles are
	 * not supported by the searchEngine implementation.
	 */
	public function buildProfileApiParam( $profileType, $helpMsg, $backendType = null ) {
		$searchEngine = null;
		if ( $backendType !== null ) {
			$searchEngine = MediaWikiServices::getInstance()
				->getSearchEngineFactory()->create( $backendType );
		} else {
			$searchEngine = MediaWikiServices::getInstance()->newSearchEngine();
		}

		$profiles = $searchEngine->getProfiles( $profileType );
		if ( $profiles ) {
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
			return [
				ApiBase::PARAM_TYPE => $types,
				ApiBase::PARAM_HELP_MSG => $helpMsg,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => $helpMessages,
				ApiBase::PARAM_DFLT => $defaultProfile,
			];
		}
		return null;
	}

	/**
	 * Build the search engine to use.
	 * If $params is provided then the following searchEngine options
	 * will be set:
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
			$searchEngine = MediaWikiServices::getInstance()->getSearchEngineFactory()->create( $type );
			$limit = $params['limit'];
			$namespaces = $params['namespace'];
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
			foreach ( $this->getSearchProfileParams() as $type => $param ) {
				if ( isset( $params[$param] ) ) {
					$searchEngine->setFeatureData( $type, $params[$param] );
				}
			}
		} else {
			$searchEngine = MediaWikiServices::getInstance()->newSearchEngine();
		}
		return $searchEngine;
	}

	/**
	 * @return string[] the list of supported search profile types. Key is
	 * the profile type and its associated value is the request param.
	 */
	abstract public function getSearchProfileParams();
}
