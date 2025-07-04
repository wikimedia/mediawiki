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

namespace MediaWiki\Api;

use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\User;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;

/**
 * API module to allow users to watch a page
 *
 * @ingroup API
 */
class ApiWatch extends ApiBase {
	/** @var ApiPageSet|null */
	private $mPageSet = null;

	/** @var bool Whether watchlist expiries are enabled. */
	private $expiryEnabled;

	/** @var string Relative maximum expiry. */
	private $maxDuration;

	protected WatchlistManager $watchlistManager;
	private TitleFormatter $titleFormatter;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		WatchlistManager $watchlistManager,
		TitleFormatter $titleFormatter
	) {
		parent::__construct( $mainModule, $moduleName );

		$this->watchlistManager = $watchlistManager;
		$this->titleFormatter = $titleFormatter;
		$this->expiryEnabled = $this->getConfig()->get( MainConfigNames::WatchlistExpiry );
		$this->maxDuration = $this->getConfig()->get( MainConfigNames::WatchlistExpiryMaxDuration );
	}

	public function execute() {
		$user = $this->getUser();
		if ( !$user->isRegistered()
			|| ( $user->isTemp() && !$user->isAllowed( 'editmywatchlist' ) )
		) {
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

			foreach ( $pageSet->getMissingPages() as $page ) {
				$r = $this->watchTitle( $page, $user, $params );
				$r['missing'] = true;
				$res[] = $r;
			}

			foreach ( $pageSet->getGoodPages() as $page ) {
				$r = $this->watchTitle( $page, $user, $params );
				$res[] = $r;
			}
			ApiResult::setIndexedTagName( $res, 'w' );
		} else {
			// dont allow use of old title parameter with new pageset parameters.
			$extraParams = array_keys( array_filter( $pageSet->extractRequestParams(), static function ( $x ) {
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
			if ( !$title || !$this->watchlistManager->isWatchable( $title ) ) {
				$this->dieWithError( [ 'invalidtitle', $params['title'] ] );
			}
			$res = $this->watchTitle( $title, $user, $params, true );
		}
		$this->getResult()->addValue( null, $this->getModuleName(), $res );

		$this->setContinuationManager( null );
		$continuationManager->setContinuationIntoResult( $this->getResult() );
	}

	private function watchTitle( PageIdentity $page, User $user, array $params,
		bool $compatibilityMode = false
	): array {
		$res = [ 'title' => $this->titleFormatter->getPrefixedText( $page ), 'ns' => $page->getNamespace() ];

		if ( !$this->watchlistManager->isWatchable( $page ) ) {
			$res['watchable'] = 0;
			return $res;
		}

		if ( $params['unwatch'] ) {
			$status = $this->watchlistManager->removeWatch( $user, $page );
			$res['unwatched'] = $status->isOK();
		} else {
			$expiry = null;

			// NOTE: If an expiry parameter isn't given, any existing expiries remain unchanged.
			if ( $this->expiryEnabled && isset( $params['expiry'] ) ) {
				$expiry = $params['expiry'];
				$res['expiry'] = ApiResult::formatExpiry( $expiry );
			}

			$status = $this->watchlistManager->addWatch( $user, $page, $expiry );
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
		$this->mPageSet ??= new ApiPageSet( $this );

		return $this->mPageSet;
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'watch';
	}

	/** @inheritDoc */
	public function getAllowedParams( $flags = 0 ) {
		$result = [
			'title' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'expiry' => [
				ParamValidator::PARAM_TYPE => 'expiry',
				ExpiryDef::PARAM_MAX => $this->maxDuration,
				ExpiryDef::PARAM_USE_MAX => true,
			],
			'unwatch' => false,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];

		// If expiry is not enabled, don't accept the parameter.
		if ( !$this->expiryEnabled ) {
			unset( $result['expiry'] );
		}

		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$title = Title::newMainPage()->getPrefixedText();
		$mp = rawurlencode( $title );

		// Logically expiry example should go before unwatch examples.
		$examples = [
			"action=watch&titles={$mp}&token=123ABC"
				=> 'apihelp-watch-example-watch',
		];
		if ( $this->expiryEnabled ) {
			$examples["action=watch&titles={$mp}|Foo|Bar&expiry=1%20month&token=123ABC"]
				= 'apihelp-watch-example-watch-expiry';
		}

		return array_merge( $examples, [
			"action=watch&titles={$mp}&unwatch=&token=123ABC"
				=> 'apihelp-watch-example-unwatch',
			'action=watch&generator=allpages&gapnamespace=0&token=123ABC'
				=> 'apihelp-watch-example-generator',
		] );
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Watch';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiWatch::class, 'ApiWatch' );
