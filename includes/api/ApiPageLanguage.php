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
	public function execute() {
		$params = $this->extractRequestParams();
		$user = $this->getUser();

		$langNew = $params['lang'] ? $params['lang'] : null;
		$pageSet = $this->getPageSet();
		$pageSet->execute();

		$result = $pageSet->getInvalidTitlesAndRevisions();

		foreach ( $pageSet->getGoodTitles() as $title ) {
			$r = array();
			// Add the title info to the output
			ApiQueryBase::addTitleInfo( $r, $title );
			$status = PageLanguage::changeLanguage( $title, $langNew, $user );
			if ( $status->isGood() ) {
				$r['status'] = 'success';
			} else {
				$errors = $status->getErrorsArray();
				// Get only the error message
				$r['status'] = $errors[0][0];
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

		$apiResult->endContinuation();
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
		$result = array(
			'lang' => array(
				ApiBase::PARAM_TYPE => 'string'
			),
			'token' => null
		);
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	public function getParamDescription() {
		return $this->getPageSet()->getFinalParamDescription()
			+ array(
				'lang' => 'New language for page, set as default wiki language if not set.'
			);
	}

	public function getDescription() {
		return 'Change page language for a page.';
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	public function getExamples() {
		return 'api.php?action=pagelang&token=123ABC&titles=Project:Sandbox&lang=es';
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:PageLanguage';
	}
}
