<?php

/**
 * API for MediaWiki 1.14+
 *
 * Created on Sep 2, 2008
 *
 * Copyright © 2008 Chad Horohoe
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
 * API interface for page purging
 * @ingroup API
 */
class ApiPurge extends ApiBase {
	private $mPageSet;

	/**
	 * Purges the cache of a page
	 */
	public function execute() {
		$params = $this->extractRequestParams();

		$continuationManager = new ApiContinuationManager( $this, array(), array() );
		$this->setContinuationManager( $continuationManager );

		$forceLinkUpdate = $params['forcelinkupdate'];
		$forceRecursiveLinkUpdate = $params['forcerecursivelinkupdate'];
		$pageSet = $this->getPageSet();
		$pageSet->execute();

		$result = $pageSet->getInvalidTitlesAndRevisions();

		foreach ( $pageSet->getGoodTitles() as $title ) {
			$r = array();
			ApiQueryBase::addTitleInfo( $r, $title );
			$page = WikiPage::factory( $title );
			$page->doPurge(); // Directly purge and skip the UI part of purge().
			$r['purged'] = true;

			if ( $forceLinkUpdate || $forceRecursiveLinkUpdate ) {
				if ( !$this->getUser()->pingLimiter( 'linkpurge' ) ) {
					$popts = $page->makeParserOptions( 'canonical' );

					# Parse content; note that HTML generation is only needed if we want to cache the result.
					$content = $page->getContent( Revision::RAW );
					$enableParserCache = $this->getConfig()->get( 'EnableParserCache' );
					$p_result = $content->getParserOutput(
						$title,
						$page->getLatest(),
						$popts,
						$enableParserCache
					);

					# Update the links tables
					$updates = $content->getSecondaryDataUpdates(
						$title, null, $forceRecursiveLinkUpdate, $p_result );
					DataUpdate::runUpdates( $updates );

					$r['linkupdate'] = true;

					if ( $enableParserCache ) {
						$pcache = ParserCache::singleton();
						$pcache->save( $p_result, $page, $popts );
					}
				} else {
					$error = $this->parseMsg( array( 'actionthrottledtext' ) );
					$this->setWarning( $error['info'] );
					$forceLinkUpdate = false;
				}
			}

			$result[] = $r;
		}
		$apiResult = $this->getResult();
		ApiResult::setIndexedTagName( $result, 'page' );
		$apiResult->addValue( null, $this->getModuleName(), $result );

		$values = $pageSet->getNormalizedTitlesAsResult( $apiResult );
		if ( $values ) {
			$apiResult->addValue( null, 'normalized', $values );
		}
		$values = $pageSet->getConvertedTitlesAsResult( $apiResult );
		if ( $values ) {
			$apiResult->addValue( null, 'converted', $values );
		}
		$values = $pageSet->getRedirectTitlesAsResult( $apiResult );
		if ( $values ) {
			$apiResult->addValue( null, 'redirects', $values );
		}

		$this->setContinuationManager( null );
		$continuationManager->setContinuationIntoResult( $apiResult );
	}

	/**
	 * Get a cached instance of an ApiPageSet object
	 * @return ApiPageSet
	 */
	private function getPageSet() {
		if ( !isset( $this->mPageSet ) ) {
			$this->mPageSet = new ApiPageSet( $this );
		}

		return $this->mPageSet;
	}

	public function isWriteMode() {
		return true;
	}

	public function mustBePosted() {
		// Anonymous users are not allowed a non-POST request
		return !$this->getUser()->isAllowed( 'purge' );
	}

	public function getAllowedParams( $flags = 0 ) {
		$result = array(
			'forcelinkupdate' => false,
			'forcerecursivelinkupdate' => false,
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
		);
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	protected function getExamplesMessages() {
		return array(
			'action=purge&titles=Main_Page|API'
				=> 'apihelp-purge-example-simple',
			'action=purge&generator=allpages&gapnamespace=0&gaplimit=10'
				=> 'apihelp-purge-example-generator',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Purge';
	}
}
