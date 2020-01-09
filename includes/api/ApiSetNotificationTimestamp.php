<?php

/**
 * API for MediaWiki 1.14+
 *
 * Copyright © 2012 Wikimedia Foundation and contributors
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

use MediaWiki\MediaWikiServices;

/**
 * API interface for setting the wl_notificationtimestamp field
 * @ingroup API
 */
class ApiSetNotificationTimestamp extends ApiBase {

	private $mPageSet = null;

	public function execute() {
		$user = $this->getUser();

		if ( $user->isAnon() ) {
			$this->dieWithError( 'watchlistanontext', 'notloggedin' );
		}
		$this->checkUserRightsAny( 'editmywatchlist' );

		$params = $this->extractRequestParams();
		$this->requireMaxOneParameter( $params, 'timestamp', 'torevid', 'newerthanrevid' );

		$continuationManager = new ApiContinuationManager( $this, [], [] );
		$this->setContinuationManager( $continuationManager );

		$pageSet = $this->getPageSet();
		if ( $params['entirewatchlist'] && $pageSet->getDataSource() !== null ) {
			$this->dieWithError(
				[
					'apierror-invalidparammix-cannotusewith',
					$this->encodeParamName( 'entirewatchlist' ),
					$pageSet->encodeParamName( $pageSet->getDataSource() )
				],
				'multisource'
			);
		}

		$dbw = wfGetDB( DB_MASTER, 'api' );

		$timestamp = null;
		if ( isset( $params['timestamp'] ) ) {
			$timestamp = $dbw->timestamp( $params['timestamp'] );
		}

		if ( !$params['entirewatchlist'] ) {
			$pageSet->execute();
		}

		if ( isset( $params['torevid'] ) ) {
			if ( $params['entirewatchlist'] || $pageSet->getGoodTitleCount() > 1 ) {
				$this->dieWithError( [ 'apierror-multpages', $this->encodeParamName( 'torevid' ) ] );
			}
			$titles = $pageSet->getGoodTitles();
			$title = reset( $titles );
			if ( $title ) {
				// XXX $title isn't actually used, can we just get rid of the previous six lines?
				$timestamp = MediaWikiServices::getInstance()->getRevisionStore()
					->getTimestampFromId( $params['torevid'], IDBAccessObject::READ_LATEST );
				if ( $timestamp ) {
					$timestamp = $dbw->timestamp( $timestamp );
				} else {
					$timestamp = null;
				}
			}
		} elseif ( isset( $params['newerthanrevid'] ) ) {
			if ( $params['entirewatchlist'] || $pageSet->getGoodTitleCount() > 1 ) {
				$this->dieWithError( [ 'apierror-multpages', $this->encodeParamName( 'newerthanrevid' ) ] );
			}
			$titles = $pageSet->getGoodTitles();
			$title = reset( $titles );
			if ( $title ) {
				$timestamp = null;
				$rl = MediaWikiServices::getInstance()->getRevisionLookup();
				$currRev = $rl->getRevisionById( $params['newerthanrevid'], Title::READ_LATEST );
				if ( $currRev ) {
					$nextRev = $rl->getNextRevision( $currRev, Title::READ_LATEST );
					if ( $nextRev ) {
						$timestamp = $dbw->timestamp( $nextRev->getTimestamp() );
					}
				}
			}
		}

		$watchedItemStore = MediaWikiServices::getInstance()->getWatchedItemStore();
		$apiResult = $this->getResult();
		$result = [];
		if ( $params['entirewatchlist'] ) {
			// Entire watchlist mode: Just update the thing and return a success indicator
			$watchedItemStore->resetAllNotificationTimestampsForUser( $user, $timestamp );

			$result['notificationtimestamp'] = $timestamp === null
				? ''
				: wfTimestamp( TS_ISO_8601, $timestamp );
		} else {
			// First, log the invalid titles
			foreach ( $pageSet->getInvalidTitlesAndReasons() as $r ) {
				$r['invalid'] = true;
				$result[] = $r;
			}
			foreach ( $pageSet->getMissingPageIDs() as $p ) {
				$page = [];
				$page['pageid'] = $p;
				$page['missing'] = true;
				$page['notwatched'] = true;
				$result[] = $page;
			}
			foreach ( $pageSet->getMissingRevisionIDs() as $r ) {
				$rev = [];
				$rev['revid'] = $r;
				$rev['missing'] = true;
				$rev['notwatched'] = true;
				$result[] = $rev;
			}

			if ( $pageSet->getTitles() ) {
				// Now process the valid titles
				$watchedItemStore->setNotificationTimestampsForUser(
					$user,
					$timestamp,
					$pageSet->getTitles()
				);

				// Query the results of our update
				$timestamps = $watchedItemStore->getNotificationTimestampsBatch(
					$user,
					$pageSet->getTitles()
				);

				// Now, put the valid titles into the result
				/** @var Title $title */
				foreach ( $pageSet->getTitles() as $title ) {
					$ns = $title->getNamespace();
					$dbkey = $title->getDBkey();
					$r = [
						'ns' => (int)$ns,
						'title' => $title->getPrefixedText(),
					];
					if ( !$title->exists() ) {
						$r['missing'] = true;
						if ( $title->isKnown() ) {
							$r['known'] = true;
						}
					}
					if ( isset( $timestamps[$ns] ) && array_key_exists( $dbkey, $timestamps[$ns] ) ) {
						$r['notificationtimestamp'] = '';
						if ( $timestamps[$ns][$dbkey] !== null ) {
							$r['notificationtimestamp'] = wfTimestamp( TS_ISO_8601, $timestamps[$ns][$dbkey] );
						}
					} else {
						$r['notwatched'] = true;
					}
					$result[] = $r;
				}
			}

			ApiResult::setIndexedTagName( $result, 'page' );
		}
		$apiResult->addValue( null, $this->getModuleName(), $result );

		$this->setContinuationManager( null );
		$continuationManager->setContinuationIntoResult( $apiResult );
	}

	/**
	 * Get a cached instance of an ApiPageSet object
	 * @return ApiPageSet
	 */
	private function getPageSet() {
		if ( $this->mPageSet === null ) {
			$this->mPageSet = new ApiPageSet( $this );
		}

		return $this->mPageSet;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function needsToken() {
		return 'csrf';
	}

	public function getAllowedParams( $flags = 0 ) {
		$result = [
			'entirewatchlist' => [
				ApiBase::PARAM_TYPE => 'boolean'
			],
			'timestamp' => [
				ApiBase::PARAM_TYPE => 'timestamp'
			],
			'torevid' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'newerthanrevid' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	protected function getExamplesMessages() {
		return [
			'action=setnotificationtimestamp&entirewatchlist=&token=123ABC'
				=> 'apihelp-setnotificationtimestamp-example-all',
			'action=setnotificationtimestamp&titles=Main_page&token=123ABC'
				=> 'apihelp-setnotificationtimestamp-example-page',
			'action=setnotificationtimestamp&titles=Main_page&' .
				'timestamp=2012-01-01T00:00:00Z&token=123ABC'
				=> 'apihelp-setnotificationtimestamp-example-pagetimestamp',
			'action=setnotificationtimestamp&generator=allpages&gapnamespace=2&token=123ABC'
				=> 'apihelp-setnotificationtimestamp-example-allpages',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:SetNotificationTimestamp';
	}
}
