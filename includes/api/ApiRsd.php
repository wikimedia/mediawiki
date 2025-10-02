<?php

/**
 * API for MediaWiki 1.17+
 *
 * Copyright © 2010 Bryan Tong Minh and Brooke Vibber
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;

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

	/** @inheritDoc */
	public function getCustomPrinter() {
		return new ApiFormatXmlRsd( $this->getMain(), 'xml' );
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=rsd'
				=> 'apihelp-rsd-example-simple',
		];
	}

	/** @inheritDoc */
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
		// Loaded here rather than injected due to the direct extension of ApiBase.
		$urlUtils = MediaWikiServices::getInstance()->getUrlUtils();
		$apis = [
			'MediaWiki' => [
				// The API link is required for all RSD API entries.
				'apiLink' => (string)$urlUtils->expand( wfScript( 'api' ), PROTO_CURRENT ),

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

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Rsd';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiRsd::class, 'ApiRsd' );
