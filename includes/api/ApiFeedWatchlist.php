<?php
/**
 *
 *
 * Created on Oct 13, 2006
 *
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

/**
 * This action allows users to get their watchlist items in RSS/Atom formats.
 * When executed, it performs a nested call to the API to get the needed data,
 * and formats it in a proper format.
 *
 * @ingroup API
 */
class ApiFeedWatchlist extends ApiBase {

	private $watchlistModule = null;
	private $linkToSections = false;

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
		$feedClasses = $config->get( 'FeedClasses' );
		try {
			$params = $this->extractRequestParams();

			if ( !$config->get( 'Feed' ) ) {
				$this->dieUsage( 'Syndication feeds are not available', 'feed-unavailable' );
			}

			if ( !isset( $feedClasses[$params['feedformat']] ) ) {
				$this->dieUsage( 'Invalid subscription feed type', 'feed-invalid' );
			}

			// limit to the number of hours going from now back
			$endTime = wfTimestamp( TS_MW, time() - intval( $params['hours'] * 60 * 60 ) );

			// Prepare parameters for nested request
			$fauxReqArr = [
				'action' => 'query',
				'meta' => 'siteinfo',
				'siprop' => 'general',
				'list' => 'watchlist',
				'wlprop' => 'title|user|comment|timestamp|ids',
				'wldir' => 'older', // reverse order - from newest to oldest
				'wlend' => $endTime, // stop at this time
				'wllimit' => min( 50, $this->getConfig()->get( 'FeedLimit' ) )
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
				$fauxReqArr['wlshow'] = $params['wlshow'];
			}
			if ( $params['wltype'] !== null ) {
				$fauxReqArr['wltype'] = $params['wltype'];
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

			// Create the request
			$fauxReq = new FauxRequest( $fauxReqArr );

			// Execute
			$module = new ApiMain( $fauxReq );
			$module->execute();

			$data = $module->getResult()->getResultData( [ 'query', 'watchlist' ] );
			$feedItems = [];
			foreach ( (array)$data as $key => $info ) {
				if ( ApiResult::isMetadataKey( $key ) ) {
					continue;
				}
				$feedItem = $this->createFeedItem( $info );
				if ( $feedItem ) {
					$feedItems[] = $feedItem;
				}
			}

			$msg = wfMessage( 'watchlist' )->inContentLanguage()->text();

			$feedTitle = $this->getConfig()->get( 'Sitename' ) . ' - ' . $msg .
				' [' . $this->getConfig()->get( 'LanguageCode' ) . ']';
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
			$feedTitle = $this->getConfig()->get( 'Sitename' ) . ' - Error - ' .
				wfMessage( 'watchlist' )->inContentLanguage()->text() .
				' [' . $this->getConfig()->get( 'LanguageCode' ) . ']';
			$feedUrl = SpecialPage::getTitleFor( 'Watchlist' )->getFullURL();

			$feedFormat = isset( $params['feedformat'] ) ? $params['feedformat'] : 'rss';
			$msg = wfMessage( 'watchlist' )->inContentLanguage()->escaped();
			$feed = new $feedClasses[$feedFormat] ( $feedTitle, $msg, $feedUrl );

			if ( $e instanceof UsageException ) {
				$errorCode = $e->getCodeString();
			} else {
				// Something is seriously wrong
				$errorCode = 'internal_api_error';
			}

			$errorText = $e->getMessage();
			$feedItems[] = new FeedItem( "Error ($errorCode)", $errorText, '', '', '' );
			ApiFormatFeedWrapper::setResult( $this->getResult(), $feed, $feedItems );
		}
	}

	/**
	 * @param array $info
	 * @return FeedItem
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
			$titleUrl = $title->getFullURL( [ 'diff' => $info['revid'] ] );
		} else {
			$titleUrl = $title->getFullURL( $curidParam );
		}
		$comment = isset( $info['comment'] ) ? $info['comment'] : null;

		// Create an anchor to section.
		// The anchor won't work for sections that have dupes on page
		// as there's no way to strip that info from ApiWatchlist (apparently?).
		// RegExp in the line below is equal to Linker::formatAutocomments().
		if ( $this->linkToSections && $comment !== null &&
			preg_match( '!(.*)/\*\s*(.*?)\s*\*/(.*)!', $comment, $matches )
		) {
			global $wgParser;

			$sectionTitle = $wgParser->stripSectionName( $matches[2] );
			$sectionTitle = Sanitizer::normalizeSectionNameWhitespace( $sectionTitle );
			$titleUrl .= Title::newFromText( '#' . $sectionTitle )->getFragmentForURL();
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

	private function getWatchlistModule() {
		if ( $this->watchlistModule === null ) {
			$this->watchlistModule = $this->getMain()->getModuleManager()->getModule( 'query' )
				->getModuleManager()->getModule( 'watchlist' );
		}

		return $this->watchlistModule;
	}

	public function getAllowedParams( $flags = 0 ) {
		$feedFormatNames = array_keys( $this->getConfig()->get( 'FeedClasses' ) );
		$ret = [
			'feedformat' => [
				ApiBase::PARAM_DFLT => 'rss',
				ApiBase::PARAM_TYPE => $feedFormatNames
			],
			'hours' => [
				ApiBase::PARAM_DFLT => 24,
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => 72,
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
		if ( $flags ) {
			$wlparams = $this->getWatchlistModule()->getAllowedParams( $flags );
			foreach ( $copyParams as $from => $to ) {
				$p = $wlparams[$from];
				if ( !is_array( $p ) ) {
					$p = [ ApiBase::PARAM_DFLT => $p ];
				}
				if ( !isset( $p[ApiBase::PARAM_HELP_MSG] ) ) {
					$p[ApiBase::PARAM_HELP_MSG] = "apihelp-query+watchlist-param-$from";
				}
				if ( isset( $p[ApiBase::PARAM_TYPE] ) && is_array( $p[ApiBase::PARAM_TYPE] ) &&
					isset( $p[ApiBase::PARAM_HELP_MSG_PER_VALUE] )
				) {
					foreach ( $p[ApiBase::PARAM_TYPE] as $v ) {
						if ( !isset( $p[ApiBase::PARAM_HELP_MSG_PER_VALUE][$v] ) ) {
							$p[ApiBase::PARAM_HELP_MSG_PER_VALUE][$v] = "apihelp-query+watchlist-paramvalue-$from-$v";
						}
					}
				}
				$ret[$to] = $p;
			}
		} else {
			foreach ( $copyParams as $from => $to ) {
				$ret[$to] = null;
			}
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
		return 'https://www.mediawiki.org/wiki/API:Watchlist_feed';
	}
}
