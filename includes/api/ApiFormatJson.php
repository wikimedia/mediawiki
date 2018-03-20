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

/**
 * API JSON output formatter
 * @ingroup API
 */
class ApiFormatJson extends ApiFormatBase {

	private $isRaw;

	public function __construct( ApiMain $main, $format ) {
		parent::__construct( $main, $format );
		$this->isRaw = ( $format === 'rawfm' );

		if ( $this->getMain()->getCheck( 'callback' ) ) {
			# T94015: jQuery appends a useless '_' parameter in jsonp mode.
			# Mark the parameter as used in that case to avoid a warning that's
			# outside the control of the end user.
			# (and do it here because ApiMain::reportUnusedParams() gets called
			# before our ::execute())
			$this->getMain()->markParamsUsed( '_' );
		}
	}

	public function getMimeType() {
		$params = $this->extractRequestParams();
		// callback:
		if ( isset( $params['callback'] ) ) {
			return 'text/javascript';
		}

		return 'application/json';
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$opt = 0;
		if ( $this->isRaw ) {
			$opt |= FormatJson::ALL_OK;
			$transform = [];
		} else {
			switch ( $params['formatversion'] ) {
				case 1:
					$opt |= $params['utf8'] ? FormatJson::ALL_OK : FormatJson::XMLMETA_OK;
					$transform = [
						'BC' => [],
						'Types' => [ 'AssocAsObject' => true ],
						'Strip' => 'all',
					];
					break;

				case 2:
				case 'latest':
					$opt |= $params['ascii'] ? FormatJson::XMLMETA_OK : FormatJson::ALL_OK;
					$transform = [
						'Types' => [ 'AssocAsObject' => true ],
						'Strip' => 'all',
					];
					break;

				default:
					// Should have been caught during parameter validation
					$this->dieDebug( __METHOD__, 'Unknown value for \'formatversion\'' );
			}
		}
		$data = $this->getResult()->getResultData( null, $transform );
		$json = FormatJson::encode( $data, $this->getIsHtml(), $opt );

		// T68776: OutputHandler::mangleFlashPolicy() avoids a nasty bug in
		// Flash, but what it does isn't friendly for the API, so we need to
		// work around it.
		if ( preg_match( '/\<\s*cross-domain-policy(?=\s|\>)/i', $json ) ) {
			$json = preg_replace(
				'/\<(\s*cross-domain-policy(?=\s|\>))/i', '\\u003C$1', $json
			);
		}

		if ( isset( $params['callback'] ) ) {
			$callback = preg_replace( "/[^][.\\'\\\"_A-Za-z0-9]/", '', $params['callback'] );
			# Prepend a comment to try to avoid attacks against content
			# sniffers, such as T70187.
			$this->printText( "/**/$callback($json)" );
		} else {
			$this->printText( $json );
		}
	}

	public function getAllowedParams() {
		if ( $this->isRaw ) {
			return parent::getAllowedParams();
		}

		$ret = parent::getAllowedParams() + [
			'callback' => [
				ApiBase::PARAM_HELP_MSG => 'apihelp-json-param-callback',
			],
			'utf8' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-json-param-utf8',
			],
			'ascii' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-json-param-ascii',
			],
			'formatversion' => [
				ApiBase::PARAM_TYPE => [ '1', '2', 'latest' ],
				ApiBase::PARAM_DFLT => '1',
				ApiBase::PARAM_HELP_MSG => 'apihelp-json-param-formatversion',
			],
		];
		return $ret;
	}
}
