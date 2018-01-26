<?php
/**
 * Copyright © 2008 Yuri Astrakhan "<Firstname><Lastname>@gmail.com",
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

/**
 * API module to allow users to watch a page
 *
 * @ingroup API
 */
class ApiWatch extends ApiBase {
	private $mPageSet = null;

	public function execute() {
		$user = $this->getUser();
		if ( !$user->isLoggedIn() ) {
			$this->dieWithError( 'watchlistanontext', 'notloggedin' );
		}

		$this->checkUserRightsAny( 'editmywatchlist' );

		$params = $this->extractRequestParams();

		$continuationManager = new ApiContinuationManager( $this, [], [] );
		$this->setContinuationManager( $continuationManager );

		$pageSet = $this->getPageSet();
		// by default we use pageset to extract the page to work on.
		// title is still supported for backward compatibility
		if ( !isset( $params['title'] ) ) {
			$pageSet->execute();
			$res = $pageSet->getInvalidTitlesAndRevisions( [
				'invalidTitles',
				'special',
				'missingIds',
				'missingRevIds',
				'interwikiTitles'
			] );

			foreach ( $pageSet->getMissingTitles() as $title ) {
				$r = $this->watchTitle( $title, $user, $params );
				$r['missing'] = true;
				$res[] = $r;
			}

			foreach ( $pageSet->getGoodTitles() as $title ) {
				$r = $this->watchTitle( $title, $user, $params );
				$res[] = $r;
			}
			ApiResult::setIndexedTagName( $res, 'w' );
		} else {
			// dont allow use of old title parameter with new pageset parameters.
			$extraParams = array_keys( array_filter( $pageSet->extractRequestParams(), function ( $x ) {
				return $x !== null && $x !== false;
			} ) );

			if ( $extraParams ) {
				$this->dieWithError(
					[
						'apierror-invalidparammix-cannotusewith',
						$this->encodeParamName( 'title' ),
						$pageSet->encodeParamName( $extraParams[0] )
					],
					'invalidparammix'
				);
			}

			$title = Title::newFromText( $params['title'] );
			if ( !$title || !$title->isWatchable() ) {
				$this->dieWithError( [ 'invalidtitle', $params['title'] ] );
			}
			$res = $this->watchTitle( $title, $user, $params, true );
		}
		$this->getResult()->addValue( null, $this->getModuleName(), $res );

		$this->setContinuationManager( null );
		$continuationManager->setContinuationIntoResult( $this->getResult() );
	}

	private function watchTitle( Title $title, User $user, array $params,
		$compatibilityMode = false
	) {
		if ( !$title->isWatchable() ) {
			return [ 'title' => $title->getPrefixedText(), 'watchable' => 0 ];
		}

		$res = [ 'title' => $title->getPrefixedText() ];

		if ( $params['unwatch'] ) {
			$status = UnwatchAction::doUnwatch( $title, $user );
			$res['unwatched'] = $status->isOK();
		} else {
			$status = WatchAction::doWatch( $title, $user );
			$res['watched'] = $status->isOK();
		}

		if ( !$status->isOK() ) {
			if ( $compatibilityMode ) {
				$this->dieStatus( $status );
			}
			$res['errors'] = $this->getErrorFormatter()->arrayFromStatus( $status, 'error' );
			$res['warnings'] = $this->getErrorFormatter()->arrayFromStatus( $status, 'warning' );
			if ( !$res['warnings'] ) {
				unset( $res['warnings'] );
			}
		}

		return $res;
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
		return 'watch';
	}

	public function getAllowedParams( $flags = 0 ) {
		$result = [
			'title' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_DEPRECATED => true
			],
			'unwatch' => false,
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
			'action=watch&titles=Main_Page&token=123ABC'
				=> 'apihelp-watch-example-watch',
			'action=watch&titles=Main_Page&unwatch=&token=123ABC'
				=> 'apihelp-watch-example-unwatch',
			'action=watch&generator=allpages&gapnamespace=0&token=123ABC'
				=> 'apihelp-watch-example-generator',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Watch';
	}
}
