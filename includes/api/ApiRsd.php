<?php

/**
 * API for MediaWiki 1.17+
 *
 * Copyright © 2010 Bryan Tong Minh and Brion Vibber
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
 * API module for sending out RSD information
 * @ingroup API
 */
class ApiRsd extends ApiBase {

	public function execute() {
		$result = $this->getResult();

		$result->addValue( null, 'version', '1.0' );
		$result->addValue( null, 'xmlns', 'http://archipelago.phrasewise.com/rsd' );

		$service = [
			'apis' => $this->formatRsdApiList(),
			'engineName' => 'MediaWiki',
			'engineLink' => 'https://www.mediawiki.org/',
			'homePageLink' => Title::newMainPage()->getCanonicalURL(),
		];

		ApiResult::setSubelementsList( $service, [ 'engineName', 'engineLink', 'homePageLink' ] );
		ApiResult::setIndexedTagName( $service['apis'], 'api' );

		$result->addValue( null, 'service', $service );
	}

	public function getCustomPrinter() {
		return new ApiFormatXmlRsd( $this->getMain(), 'xml' );
	}

	protected function getExamplesMessages() {
		return [
			'action=rsd'
				=> 'apihelp-rsd-example-simple',
		];
	}

	public function isReadMode() {
		return false;
	}

	/**
	 * Builds an internal list of APIs to expose information about.
	 * Normally this only lists the MediaWiki API, with its base URL,
	 * link to documentation, and a marker as to available authentication
	 * (to aid in OAuth client apps switching to support in the future).
	 *
	 * Extensions can expose other APIs, such as WordPress or Twitter-
	 * compatible APIs, by hooking 'ApiRsdServiceApis' and adding more
	 * elements to the array.
	 *
	 * See https://cyber.harvard.edu/blogs/gems/tech/rsd.html for
	 * the base RSD spec, and check WordPress and StatusNet sites for
	 * in-production examples listing several blogging and micrblogging
	 * APIs.
	 *
	 * @return array[]
	 */
	protected function getRsdApiList() {
		$apis = [
			'MediaWiki' => [
				// The API link is required for all RSD API entries.
				'apiLink' => wfExpandUrl( wfScript( 'api' ), PROTO_CURRENT ),

				// Docs link is optional, but recommended.
				'docs' => 'https://www.mediawiki.org/wiki/Special:MyLanguage/API',

				// Some APIs may need a blog ID, but it may be left blank.
				'blogID' => '',

				// Additional settings are optional.
				'settings' => [
					// Change this to true in the future as an aid to
					// machine discovery of OAuth for API access.
					'OAuth' => false,
				]
			],
		];
		$this->getHookRunner()->onApiRsdServiceApis( $apis );

		return $apis;
	}

	/**
	 * Formats the internal list of exposed APIs into an array suitable
	 * to pass to the API's XML formatter.
	 *
	 * @return array
	 */
	protected function formatRsdApiList() {
		$apis = $this->getRsdApiList();

		$outputData = [];
		foreach ( $apis as $name => $info ) {
			$data = [
				'name' => $name,
				'preferred' => wfBoolToStr( $name == 'MediaWiki' ),
				'apiLink' => $info['apiLink'],
				'blogID' => $info['blogID'] ?? '',
			];
			$settings = [];
			if ( isset( $info['docs'] ) ) {
				$settings['docs'] = $info['docs'];
				ApiResult::setSubelementsList( $settings, 'docs' );
			}
			if ( isset( $info['settings'] ) ) {
				foreach ( $info['settings'] as $setting => $val ) {
					if ( is_bool( $val ) ) {
						$xmlVal = wfBoolToStr( $val );
					} else {
						$xmlVal = $val;
					}
					$setting = [ 'name' => $setting ];
					ApiResult::setContentValue( $setting, 'value', $xmlVal );
					$settings[] = $setting;
				}
			}
			if ( count( $settings ) ) {
				ApiResult::setIndexedTagName( $settings, 'setting' );
				$data['settings'] = $settings;
			}
			$outputData[] = $data;
		}

		return $outputData;
	}
}
