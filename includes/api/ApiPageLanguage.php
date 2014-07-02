<?php
/**
 *
 * Copyright © 2014 Kunal Grover
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
 * @since 1.24
 * @author Kunal Grover, 2014
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

		$langNew = $params['lang'];
		$pageSet = $this->getPageSet();
		$pageSet->execute();

		$out = array();
		foreach ( $pageSet->getTitles() as $title ) {
			$res = PageLanguage::changeLanguage( $title, $langNew, $user );
			$out[$title->getText()] = $res->getValue();
		}
		$this->getResult()->addValue( null, $this->getModuleName(), $out );
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
		return array(
			'lang' => array(
				ApiBase::PARAM_TYPE => 'string'
			),
			'token' => null
		);
	}

	public function getParamDescription() {
		return array(
			'title' => 'Page for which language is being changed.',
			'lang' => 'New language for page- set as default wiki language if not set.'
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
		return 'api.php?action=pagelang&page=Main_page&lang=es';
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:PageLanguage';
	}
}
