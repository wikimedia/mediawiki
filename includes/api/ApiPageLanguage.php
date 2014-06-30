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

		$langNew = $params['lang'];
		$page = $params['page'];

		$res = PageLanguage::changeLanguage( $page, $langNew );

		if ( $res === true ) {
			$langNew = $langNew ? $langNew : 'default';
			// Success message
			$msg = $this->msg( 'changelang-success', $page, $langNew )->plain();
			$output = array( 'changelang-success' => $msg );
			$this->getResult()->addValue( null, $this->getModuleName(), $output );
		} else {
			$this->getResult()->addValue( null, $this->getModuleName(), $res );
		}
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'page' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'lang' => array(
				ApiBase::PARAM_TYPE => 'string'
			),
			'token' => null
		);
	}

	public function getParamDescription() {
		return array(
			'page' => 'Page for which language is being changed.',
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
