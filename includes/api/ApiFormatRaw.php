<?php
/**
 * API for MediaWiki 1.8+
 *
 * Created on Feb 2, 2009
 *
 * Copyright © 2009 Roan Kattouw <Firstname>.<Lastname>@home.nl
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiFormatBase.php' );
}

/**
 * Formatter that spits out anything you like with any desired MIME type
 * @ingroup API
 */
class ApiFormatRaw extends ApiFormatBase {

	/**
	 * Constructor
	 * @param $main ApiMain object
	 * @param $errorFallback Formatter object to fall back on for errors
	 */
	public function __construct( $main, $errorFallback ) {
		parent::__construct( $main, 'raw' );
		$this->mErrorFallback = $errorFallback;
	}

	public function getMimeType() {
		$data = $this->getResultData();

		if ( isset( $data['error'] ) ) {
			return $this->mErrorFallback->getMimeType();
		}

		if ( !isset( $data['mime'] ) ) {
			ApiBase::dieDebug( __METHOD__, 'No MIME type set for raw formatter' );
		}

		return $data['mime'];
	}

	public function execute() {
		$data = $this->getResultData();
		if ( isset( $data['error'] ) ) {
			$this->mErrorFallback->execute();
			return;
		}

		if ( !isset( $data['text'] ) ) {
			ApiBase::dieDebug( __METHOD__, 'No text given for raw formatter' );
		}
		$this->printText( $data['text'] );
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
