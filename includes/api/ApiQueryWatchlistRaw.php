<?php
/**
 * Copyright © 2008 Roan Kattouw <roan.kattouw@gmail.com>
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
 */

namespace MediaWiki\Api;

use MediaWiki\Cache\GenderCache;
use MediaWiki\Language\Language;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\Watchlist\WatchedItemQueryService;
use MediaWiki\Watchlist\WatchedItemStore;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * This query action allows clients to retrieve a list of pages
 * on the logged-in user's watchlist.
 *
 * @ingroup API
 */
class ApiQueryWatchlistRaw extends ApiQueryGeneratorBase {

	private WatchedItemQueryService $watchedItemQueryService;
	private Language $contentLanguage;
	private NamespaceInfo $namespaceInfo;
	private GenderCache $genderCache;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		WatchedItemQueryService $watchedItemQueryService,
		Language $contentLanguage,
		NamespaceInfo $namespaceInfo,
		GenderCache $genderCache
	) {
		parent::__construct( $query, $moduleName, 'wr' );
		$this->watchedItemQueryService = $watchedItemQueryService;
		$this->contentLanguage = $contentLanguage;
		$this->namespaceInfo = $namespaceInfo;
		$this->genderCache = $genderCache;
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		$user = $this->getWatchlistUser( $params );

		$prop = array_fill_keys( (array)$params['prop'], true );
		$show = array_fill_keys( (array)$params['show'], true );
		if ( isset( $show[WatchedItemQueryService::FILTER_CHANGED] )
			&& isset( $show[WatchedItemQueryService::FILTER_NOT_CHANGED] )
		) {
			$this->dieWithError( 'apierror-show' );
		}

		$options = [];
		if ( $params['namespace'] ) {
			$options['namespaceIds'] = $params['namespace'];
		}
		if ( isset( $show[WatchedItemQueryService::FILTER_CHANGED] ) ) {
			$options['filter'] = WatchedItemQueryService::FILTER_CHANGED;
		}
		if ( isset( $show[WatchedItemQueryService::FILTER_NOT_CHANGED] ) ) {
			$options['filter'] = WatchedItemQueryService::FILTER_NOT_CHANGED;
		}

		if ( isset( $params['continue'] ) ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'string' ] );
			$options['startFrom'] = TitleValue::tryNew( $cont[0], $cont[1] );
			$this->dieContinueUsageIf( !$options['startFrom'] );
		}

		if ( isset( $params['fromtitle'] ) ) {
			$options['from'] = $this->parsePrefixedTitlePart( $params['fromtitle'] );
		}

		if ( isset( $params['totitle'] ) ) {
			$options['until'] = $this->parsePrefixedTitlePart( $params['totitle'] );
		}

		$options['sort'] = WatchedItemStore::SORT_ASC;
		if ( $params['dir'] === 'descending' ) {
			$options['sort'] = WatchedItemStore::SORT_DESC;
		}
		$options['limit'] = $params['limit'] + 1;

		$titles = [];
		$count = 0;
		$items = $this->watchedItemQueryService->getWatchedItemsForUser( $user, $options );

		// Get gender information
		if ( $items !== [] && $resultPageSet === null &&
			$this->contentLanguage->needsGenderDistinction()
		) {
			$usernames = [];
			foreach ( $items as $item ) {
				$linkTarget = $item->getTarget();
				if ( $this->namespaceInfo->hasGenderDistinction( $linkTarget->getNamespace() ) ) {
					$usernames[] = $linkTarget->getText();
				}
			}
			if ( $usernames !== [] ) {
				$this->genderCache->doQuery( $usernames, __METHOD__ );
			}
		}

		foreach ( $items as $item ) {
			$ns = $item->getTarget()->getNamespace();
			$dbKey = $item->getTarget()->getDBkey();
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $ns . '|' . $dbKey );
				break;
			}
			$t = Title::makeTitle( $ns, $dbKey );

			if ( $resultPageSet === null ) {
				$vals = [];
				ApiQueryBase::addTitleInfo( $vals, $t );
				if ( isset( $prop['changed'] ) && $item->getNotificationTimestamp() !== null ) {
					$vals['changed'] = wfTimestamp( TS_ISO_8601, $item->getNotificationTimestamp() );
				}
				$fit = $this->getResult()->addValue( $this->getModuleName(), null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $ns . '|' . $dbKey );
					break;
				}
			} else {
				$titles[] = $t;
			}
		}
		if ( $resultPageSet === null ) {
			$this->getResult()->addIndexedTagName( $this->getModuleName(), 'wr' );
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'namespace' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace'
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
					'changed',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'show' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
					WatchedItemQueryService::FILTER_CHANGED,
					WatchedItemQueryService::FILTER_NOT_CHANGED
				]
			],
			'owner' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name' ],
			],
			'token' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_SENSITIVE => true,
			],
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				],
			],
			'fromtitle' => [
				ParamValidator::PARAM_TYPE => 'string'
			],
			'totitle' => [
				ParamValidator::PARAM_TYPE => 'string'
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=watchlistraw'
				=> 'apihelp-query+watchlistraw-example-simple',
			'action=query&generator=watchlistraw&gwrshow=changed&prop=info'
				=> 'apihelp-query+watchlistraw-example-generator',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Watchlistraw';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryWatchlistRaw::class, 'ApiQueryWatchlistRaw' );
