<?php
/**
 *
 *
 * Created on Sep 19, 2006
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
 * API JSON output formatter
 * @ingroup API
 */
class ApiFormatJson extends ApiFormatBase {

	private $mIsRaw;

	public function __construct( $main, $format ) {
		parent::__construct( $main, $format );
		$this->mIsRaw = ( $format === 'rawfm' );
	}

	public function getMimeType() {
		$params = $this->extractRequestParams();
		// callback:
		if ( $params['callback'] ) {
			return 'text/javascript';
		}

		return 'application/json';
	}

	public function getNeedsRawData() {
		return $this->mIsRaw;
	}

	public function getWantsHelp() {
		// Help is always ugly in JSON
		return false;
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$json = FormatJson::encode(
			$this->getResultData(),
			$this->getIsHtml(),
			$params['utf8'] ? FormatJson::ALL_OK : FormatJson::XMLMETA_OK
		);
		$callback = $params['callback'];
		if ( $callback !== null ) {
			$callback = preg_replace( "/[^][.\\'\\\"_A-Za-z0-9]/", '', $callback );
			$this->printText( "$callback($json)" );
		} else {
			$this->printText( $json );
		}
	}

	protected function formatHTML( $text ) {
		$hs = new ApiFormatHighlightState( $text );
		$mr = $hs->getMarkerRange();

		$pairs = array(
			// Escaped backslashes, linefeeds, double quotes
			'\\\\' => '<span class="mw-api-esc">\\\\</span>',
			'\n' => '<span class="mw-api-esc">\n</span><span class="mw-api-br"></span>',
			'\"' => '<span class="mw-api-esc">\&quot;</span>',
		);
		$hs->strReplaceHTML( array_keys( $pairs ), array_values( $pairs ) );

		$pairs = array(
			// Other escape sequences valid in JSON
			"/\\\\(?:u[0-9A-Fa-f]{4}|[^$mr])/" => '<span class="mw-api-esc">$0</span>',
			// Property names
			'/(?<=  )"([^"]*)":/' => '&quot;<span class="mw-api-json-prop">$1</span>&quot;:',
		);
		$hs->pregReplaceHTML( array_keys( $pairs ), array_values( $pairs ) );

		// Beginning of line
		$indent = array();
		for ( $i = 0; $i < 16; ++$i ) {
			$indent[$i] = $hs->addMarker( "<span class=\"mw-api-json-l$i\">" );
		}

		$hs->pregReplaceCallback( '/^ {0,60}/m', function ( $m ) use ( $indent ) {
			// 4 spaces = 1 indent level
			return $indent[strlen( $m[0] ) >> 2] . $m[0];
		} );

		// End of line
		$hs->strReplaceHTML( "\n", "\n</span>" );

		return $hs->getHTML() . '</span>';
	}

	public function getAllowedParams() {
		return array(
			'callback' => null,
			'utf8' => false,
		);
	}

	public function getParamDescription() {
		return array(
			'callback' => 'If specified, wraps the output into a given function ' .
				'call. For safety, all user-specific data will be restricted.',
			'utf8' => 'If specified, encodes most (but not all) non-ASCII ' .
				'characters as UTF-8 instead of replacing them with hexadecimal escape sequences.',
		);
	}

	public function getDescription() {
		if ( $this->mIsRaw ) {
			return 'Output data with the debugging elements in JSON format' . parent::getDescription();
		}

		return 'Output data in JSON format' . parent::getDescription();
	}
}
