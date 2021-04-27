<?php
/**
 * Copyright © 2007 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * @ingroup API
 */
class ApiProtect extends ApiBase {

	use ApiWatchlistTrait;

	public function __construct( ApiMain $mainModule, $moduleName, $modulePrefix = '' ) {
		parent::__construct( $mainModule, $moduleName, $modulePrefix );

		$this->watchlistExpiryEnabled = $this->getConfig()->get( 'WatchlistExpiry' );
		$this->watchlistMaxDuration = $this->getConfig()->get( 'WatchlistExpiryMaxDuration' );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$pageObj = $this->getTitleOrPageId( $params, 'fromdbmaster' );
		$titleObj = $pageObj->getTitle();

		$this->checkTitleUserPermissions( $titleObj, 'protect' );

		$user = $this->getUser();
		$tags = $params['tags'];

		// Check if user can add tags
		if ( $tags !== null ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $tags, $user );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		$expiry = (array)$params['expiry'];
		if ( count( $expiry ) != count( $params['protections'] ) ) {
			if ( count( $expiry ) == 1 ) {
				$expiry = array_fill( 0, count( $params['protections'] ), $expiry[0] );
			} else {
				$this->dieWithError( [
					'apierror-toofewexpiries',
					count( $expiry ),
					count( $params['protections'] )
				] );
			}
		}

		$restrictionTypes = $titleObj->getRestrictionTypes();
		$levels = $this->getPermissionManager()->getNamespaceRestrictionLevels(
			$titleObj->getNamespace(),
			$user
		);

		$protections = [];
		$expiryarray = [];
		$resultProtections = [];
		foreach ( $params['protections'] as $i => $prot ) {
			$p = explode( '=', $prot );
			$protections[$p[0]] = ( $p[1] == 'all' ? '' : $p[1] );

			if ( $titleObj->exists() && $p[0] == 'create' ) {
				$this->dieWithError( 'apierror-create-titleexists' );
			}
			if ( !$titleObj->exists() && $p[0] != 'create' ) {
				$this->dieWithError( 'apierror-missingtitle-createonly' );
			}

			if ( !in_array( $p[0], $restrictionTypes ) && $p[0] != 'create' ) {
				$this->dieWithError( [ 'apierror-protect-invalidaction', wfEscapeWikiText( $p[0] ) ] );
			}
			if ( !in_array( $p[1], $levels ) && $p[1] != 'all' ) {
				$this->dieWithError( [ 'apierror-protect-invalidlevel', wfEscapeWikiText( $p[1] ) ] );
			}

			if ( wfIsInfinity( $expiry[$i] ) ) {
				$expiryarray[$p[0]] = 'infinity';
			} else {
				$exp = strtotime( $expiry[$i] );
				if ( $exp < 0 || !$exp ) {
					$this->dieWithError( [ 'apierror-invalidexpiry', wfEscapeWikiText( $expiry[$i] ) ] );
				}

				$exp = wfTimestamp( TS_MW, $exp );
				if ( $exp < wfTimestampNow() ) {
					$this->dieWithError( [ 'apierror-pastexpiry', wfEscapeWikiText( $expiry[$i] ) ] );
				}
				$expiryarray[$p[0]] = $exp;
			}
			$resultProtections[] = [
				$p[0] => $protections[$p[0]],
				'expiry' => ApiResult::formatExpiry( $expiryarray[$p[0]], 'infinite' ),
			];
		}

		$cascade = $params['cascade'];

		$watch = $params['watch'] ? 'watch' : $params['watchlist'];
		$watchlistExpiry = $this->getExpiryFromParams( $params );
		$this->setWatch( $watch, $titleObj, $user, 'watchdefault', $watchlistExpiry );

		$status = $pageObj->doUpdateRestrictions(
			$protections,
			$expiryarray,
			$cascade,
			$params['reason'],
			$user,
			$tags
		);

		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}
		$res = [
			'title' => $titleObj->getPrefixedText(),
			'reason' => $params['reason']
		];
		if ( $cascade ) {
			$res['cascade'] = true;
		}
		$res['protections'] = $resultProtections;
		$result = $this->getResult();
		ApiResult::setIndexedTagName( $res['protections'], 'protection' );
		$result->addValue( null, $this->getModuleName(), $res );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return [
			'title' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'pageid' => [
				ApiBase::PARAM_TYPE => 'integer',
			],
			'protections' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_REQUIRED => true,
			],
			'expiry' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_ALLOW_DUPLICATES => true,
				ApiBase::PARAM_DFLT => 'infinite',
			],
			'reason' => '',
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
			'cascade' => false,
			'watch' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			],
		] + $this->getWatchlistParams();
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=protect&title=Main%20Page&token=123ABC&' .
				'protections=edit=sysop|move=sysop&cascade=&expiry=20070901163000|never'
				=> 'apihelp-protect-example-protect',
			'action=protect&title=Main%20Page&token=123ABC&' .
				'protections=edit=all|move=all&reason=Lifting%20restrictions'
				=> 'apihelp-protect-example-unprotect',
			'action=protect&title=Main%20Page&token=123ABC&' .
				'protections=&reason=Lifting%20restrictions'
				=> 'apihelp-protect-example-unprotect2',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Protect';
	}
}
