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
 * API Serialized PHP output formatter
 * @ingroup API
 */
class ApiFormatPhp extends ApiFormatBase {

	public function getMimeType() {
		return 'application/vnd.php.serialized';
	}

	public function execute() {
		$params = $this->extractRequestParams();

		switch ( $params['formatversion'] ) {
			case 1:
				$transforms = [
					'BC' => [],
					'Types' => [],
					'Strip' => 'all',
				];
				break;

			case 2:
			case 'latest':
				$transforms = [
					'Types' => [],
					'Strip' => 'all',
				];
				break;

			default:
				// Should have been caught during parameter validation
				$this->dieDebug( __METHOD__, 'Unknown value for \'formatversion\'' );
		}
		$text = serialize( $this->getResult()->getResultData( null, $transforms ) );

		// T68776: OutputHandler::mangleFlashPolicy() avoids a nasty bug in
		// Flash, but what it does isn't friendly for the API. There's nothing
		// we can do here that isn't actively broken in some manner, so let's
		// just be broken in a useful manner.
		if ( $this->getConfig()->get( 'MangleFlashPolicy' ) &&
			in_array( 'MediaWiki\\OutputHandler::handle', ob_list_handlers(), true ) &&
			preg_match( '/\<\s*cross-domain-policy(?=\s|\>)/i', $text )
		) {
			$this->dieWithError( 'apierror-formatphp', 'internalerror' );
		}

		$this->printText( $text );
	}

	public function getAllowedParams() {
		$ret = parent::getAllowedParams() + [
			'formatversion' => [
				ApiBase::PARAM_TYPE => [ '1', '2', 'latest' ],
				ApiBase::PARAM_DFLT => '1',
				ApiBase::PARAM_HELP_MSG => 'apihelp-php-param-formatversion',
			],
		];
		return $ret;
	}
}
