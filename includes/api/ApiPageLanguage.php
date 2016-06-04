<?php
/**
 *
 * Copyright © 2016 Kunal Grover
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
 * @since 1.28
 * @author Kunal Grover, 2016
 */

/**
 * API module to allow change page language. Requires API write mode
 * to be enabled.
 *
 * @ingroup API
 */
class ApiPageLanguage extends ApiBase {
	private $mPageSet;

	public function execute() {
		$params = $this->extractRequestParams();
		$user = $this->getUser();

		$continuationManager = new ApiContinuationManager( $this, [], [] );
		$this->setContinuationManager( $continuationManager );

		$langNew = $params['lang'] ? $params['lang'] : null;
		$pageSet = $this->getPageSet();
		$pageSet->execute();

		$result = $pageSet->getInvalidTitlesAndRevisions();

		foreach ( $pageSet->getGoodTitles() as $title ) {
			$r = [];
			// Add the title info to the output
			ApiQueryBase::addTitleInfo( $r, $title );
			$status = PageLanguage::changeLanguage( $title, $langNew, $user );
			if ( $status->isOK() ) {
				$r['status'] = 'success';
			} else {
				$error = $this->getErrorFromStatus( $status );
				$r['status'] = $error[0];
			}
			$result[] = $r;
		}
		$apiResult = $this->getResult();
		$apiResult->setIndexedTagName( $result, 'page' );
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

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
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

	public function getAllowedParams( $flags = 0 ) {
		$result = [
			'lang' => [
				ApiBase::PARAM_TYPE => 'string'
			]
		];
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=setpagelanguage&titles=Title1|Title2' .
			'&token=123ABC&lang=hi'
			=> 'apihelp-setpagelanguage-example-setpagelanguage',
			'action=setpagelanguage&titles=Title&token=123ABC'
			=> 'apihelp-setpagelanguage-example-setdefaultlanguage',

		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:PageLanguage';
	}
}
