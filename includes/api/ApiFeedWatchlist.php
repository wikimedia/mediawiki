<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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

use Exception;
use MediaWiki\Feed\ChannelFeed;
use MediaWiki\Feed\FeedItem;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * This action allows users to get their watchlist items in RSS/Atom formats.
 * When executed, it performs a nested call to the API to get the needed data,
 * and formats it in a proper format.
 *
 * @ingroup API
 */
class ApiFeedWatchlist extends ApiBase {

	/** @var ApiBase|null */
	private $watchlistModule = null;
	/** @var bool */
	private $linkToSections = false;

	private ParserFactory $parserFactory;

	public function __construct(
		ApiMain $main,
		string $action,
		ParserFactory $parserFactory
	) {
		parent::__construct( $main, $action );
		$this->parserFactory = $parserFactory;
	}

	/**
	 * This module uses a custom feed wrapper printer.
	 *
	 * @return ApiFormatFeedWrapper
	 */
	public function getCustomPrinter() {
		return new ApiFormatFeedWrapper( $this->getMain() );
	}

	/**
	 * Make a nested call to the API to request watchlist items in the last $hours.
	 * Wrap the result as an RSS/Atom feed.
	 */
	public function execute() {
		$config = $this->getConfig();
		$feedClasses = $config->get( MainConfigNames::FeedClasses );
		'@phan-var array<string,class-string<ChannelFeed>> $feedClasses';
		$params = [];
		$feedItems = [];
		try {
			$params = $this->extractRequestParams();

			if ( !$config->get( MainConfigNames::Feed ) ) {
				$this->dieWithError( 'feed-unavailable' );
			}

			if ( !isset( $feedClasses[$params['feedformat']] ) ) {
				$this->dieWithError( 'feed-invalid' );
			}

			// limit to the number of hours going from now back
			$endTime = wfTimestamp( TS_MW, time() - (int)$params['hours'] * 60 * 60 );

			// Prepare parameters for nested request
			$fauxReqArr = [
				'action' => 'query',
				'meta' => 'siteinfo',
				'siprop' => 'general',
				'list' => 'watchlist',
				'wlprop' => 'title|user|comment|timestamp|ids|loginfo',
				'wldir' => 'older', // reverse order - from newest to oldest
				'wlend' => $endTime, // stop at this time
				'wllimit' => min( 50, $this->getConfig()->get( MainConfigNames::FeedLimit ) )
			];

			if ( $params['wlowner'] !== null ) {
				$fauxReqArr['wlowner'] = $params['wlowner'];
			}
			if ( $params['wltoken'] !== null ) {
				$fauxReqArr['wltoken'] = $params['wltoken'];
			}
			if ( $params['wlexcludeuser'] !== null ) {
				$fauxReqArr['wlexcludeuser'] = $params['wlexcludeuser'];
			}
			if ( $params['wlshow'] !== null ) {
				$fauxReqArr['wlshow'] = ParamValidator::implodeMultiValue( $params['wlshow'] );
			}
			if ( $params['wltype'] !== null ) {
				$fauxReqArr['wltype'] = ParamValidator::implodeMultiValue( $params['wltype'] );
			}

			// Support linking directly to sections when possible
			// (possible only if section name is present in comment)
			if ( $params['linktosections'] ) {
				$this->linkToSections = true;
			}

			// Check for 'allrev' parameter, and if found, show all revisions to each page on wl.
			if ( $params['allrev'] ) {
				$fauxReqArr['wlallrev'] = '';
			}

			$fauxReq = new FauxRequest( $fauxReqArr );

			$module = new ApiMain( $fauxReq );
			$module->execute();

			$data = $module->getResult()->getResultData( [ 'query', 'watchlist' ] );
			foreach ( (array)$data as $key => $info ) {
				if ( ApiResult::isMetadataKey( $key ) ) {
					continue;
				}
				$feedItem = $this->createFeedItem( $info );
				if ( $feedItem ) {
					$feedItems[] = $feedItem;
				}
			}

			$msg = $this->msg( 'watchlist' )->inContentLanguage()->text();

			$feedTitle = $this->getConfig()->get( MainConfigNames::Sitename ) . ' - ' . $msg .
				' [' . $this->getConfig()->get( MainConfigNames::LanguageCode ) . ']';
			$feedUrl = SpecialPage::getTitleFor( 'Watchlist' )->getFullURL();

			$feed = new $feedClasses[$params['feedformat']] (
				$feedTitle,
				htmlspecialchars( $msg ),
				$feedUrl
			);

			ApiFormatFeedWrapper::setResult( $this->getResult(), $feed, $feedItems );
		} catch ( Exception $e ) {
			// Error results should not be cached
			$this->getMain()->setCacheMaxAge( 0 );

			// @todo FIXME: Localise  brackets
			$feedTitle = $this->getConfig()->get( MainConfigNames::Sitename ) . ' - Error - ' .
				$this->msg( 'watchlist' )->inContentLanguage()->text() .
				' [' . $this->getConfig()->get( MainConfigNames::LanguageCode ) . ']';
			$feedUrl = SpecialPage::getTitleFor( 'Watchlist' )->getFullURL();

			$feedFormat = $params['feedformat'] ?? 'rss';
			$msg = $this->msg( 'watchlist' )->inContentLanguage()->escaped();
			$feed = new $feedClasses[$feedFormat]( $feedTitle, $msg, $feedUrl );

			if ( $e instanceof ApiUsageException ) {
				foreach ( $e->getStatusValue()->getMessages() as $msg ) {
					// @phan-suppress-next-line PhanUndeclaredMethod
					$msg = ApiMessage::create( $msg )
						->inLanguage( $this->getLanguage() );
					$errorTitle = $this->msg( 'api-feed-error-title', $msg->getApiCode() );
					$errorText = $msg->text();
					$feedItems[] = new FeedItem( $errorTitle, $errorText, '', '', '' );
				}
			} else {
				// Something is seriously wrong
				$errorCode = 'internal_api_error';
				$errorTitle = $this->msg( 'api-feed-error-title', $errorCode );
				$errorText = $e->getMessage();
				$feedItems[] = new FeedItem( $errorTitle, $errorText, '', '', '' );
			}

			ApiFormatFeedWrapper::setResult( $this->getResult(), $feed, $feedItems );
		}
	}

	/**
	 * @param array $info
	 * @return FeedItem|null
	 */
	private function createFeedItem( $info ) {
		if ( !isset( $info['title'] ) ) {
			// Probably a revdeled log entry, skip it.
			return null;
		}

		$titleStr = $info['title'];
		$title = Title::newFromText( $titleStr );
		$curidParam = [];
		if ( !$title || $title->isExternal() ) {
			// Probably a formerly-valid title that's now conflicting with an
			// interwiki prefix or the like.
			if ( isset( $info['pageid'] ) ) {
				$title = Title::newFromID( $info['pageid'] );
				$curidParam = [ 'curid' => $info['pageid'] ];
			}
			if ( !$title || $title->isExternal() ) {
				return null;
			}
		}
		if ( isset( $info['revid'] ) ) {
			if ( $info['revid'] === 0 && isset( $info['logid'] ) ) {
				$logTitle = Title::makeTitle( NS_SPECIAL, 'Log' );
				$titleUrl = $logTitle->getFullURL( [ 'logid' => $info['logid'] ] );
			} else {
				$titleUrl = $title->getFullURL( [ 'diff' => $info['revid'] ] );
			}
		} else {
			$titleUrl = $title->getFullURL( $curidParam );
		}
		$comment = $info['comment'] ?? null;

		// Create an anchor to section.
		// The anchor won't work for sections that have dupes on page
		// as there's no way to strip that info from ApiWatchlist (apparently?).
		// RegExp in the line below is equal to MediaWiki\CommentFormatter\CommentParser::doSectionLinks().
		if ( $this->linkToSections && $comment !== null &&
			preg_match( '!(.*)/\*\s*(.*?)\s*\*/(.*)!', $comment, $matches )
		) {
			$titleUrl .= $this->parserFactory->getMainInstance()->guessSectionNameFromWikiText( $matches[ 2 ] );
		}

		$timestamp = $info['timestamp'];

		if ( isset( $info['user'] ) ) {
			$user = $info['user'];
			$completeText = "$comment ($user)";
		} else {
			$user = '';
			$completeText = (string)$comment;
		}

		return new FeedItem( $titleStr, $completeText, $titleUrl, $timestamp, $user );
	}

	/** @return ApiBase|null */
	private function getWatchlistModule() {
		$this->watchlistModule ??= $this->getMain()->getModuleManager()->getModule( 'query' )
			->getModuleManager()->getModule( 'watchlist' );

		return $this->watchlistModule;
	}

	public function getAllowedParams( $flags = 0 ) {
		$feedFormatNames = array_keys( $this->getConfig()->get( MainConfigNames::FeedClasses ) );
		$ret = [
			'feedformat' => [
				ParamValidator::PARAM_DEFAULT => 'rss',
				ParamValidator::PARAM_TYPE => $feedFormatNames
			],
			'hours' => [
				ParamValidator::PARAM_DEFAULT => 24,
				ParamValidator::PARAM_TYPE => 'integer',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => 72,
			],
			'linktosections' => false,
		];

		$copyParams = [
			'allrev' => 'allrev',
			'owner' => 'wlowner',
			'token' => 'wltoken',
			'show' => 'wlshow',
			'type' => 'wltype',
			'excludeuser' => 'wlexcludeuser',
		];
		// @phan-suppress-next-line PhanParamTooMany
		$wlparams = $this->getWatchlistModule()->getAllowedParams( $flags );
		foreach ( $copyParams as $from => $to ) {
			$p = $wlparams[$from];
			if ( !is_array( $p ) ) {
				$p = [ ParamValidator::PARAM_DEFAULT => $p ];
			}
			if ( !isset( $p[ApiBase::PARAM_HELP_MSG] ) ) {
				$p[ApiBase::PARAM_HELP_MSG] = "apihelp-query+watchlist-param-$from";
			}
			if ( isset( $p[ParamValidator::PARAM_TYPE] ) && is_array( $p[ParamValidator::PARAM_TYPE] ) &&
				isset( $p[ApiBase::PARAM_HELP_MSG_PER_VALUE] )
			) {
				foreach ( $p[ParamValidator::PARAM_TYPE] as $v ) {
					if ( !isset( $p[ApiBase::PARAM_HELP_MSG_PER_VALUE][$v] ) ) {
						$p[ApiBase::PARAM_HELP_MSG_PER_VALUE][$v] = "apihelp-query+watchlist-paramvalue-$from-$v";
					}
				}
			}
			$ret[$to] = $p;
		}

		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=feedwatchlist'
				=> 'apihelp-feedwatchlist-example-default',
			'action=feedwatchlist&allrev=&hours=6'
				=> 'apihelp-feedwatchlist-example-all6hrs',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Watchlist_feed';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiFeedWatchlist::class, 'ApiFeedWatchlist' );
