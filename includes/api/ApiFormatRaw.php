<?php
/**
 * Copyright © 2009 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * Formatter that spits out anything you like with any desired MIME type
 * @ingroup API
 */
class ApiFormatRaw extends ApiFormatBase {

	private $errorFallback;
	private $mFailWithHTTPError = false;

	/**
	 * @param ApiMain $main
	 * @param ApiFormatBase|null $errorFallback Object to fall back on for errors
	 */
	public function __construct( ApiMain $main, ApiFormatBase $errorFallback = null ) {
		parent::__construct( $main, 'raw' );
		$this->errorFallback = $errorFallback ?:
			$main->createPrinterByName( $main->getParameter( 'format' ) );
	}

	public function getMimeType() {
		$data = $this->getResult()->getResultData();

		if ( isset( $data['error'] ) || isset( $data['errors'] ) ) {
			return $this->errorFallback->getMimeType();
		}

		if ( !isset( $data['mime'] ) ) {
			ApiBase::dieDebug( __METHOD__, 'No MIME type set for raw formatter' );
		}

		return $data['mime'];
	}

	public function getFilename() {
		$data = $this->getResult()->getResultData();
		if ( isset( $data['error'] ) ) {
			return $this->errorFallback->getFilename();
		} elseif ( !isset( $data['filename'] ) || $this->getIsWrappedHtml() || $this->getIsHtml() ) {
			return parent::getFilename();
		} else {
			return $data['filename'];
		}
	}

	public function initPrinter( $unused = false ) {
		$data = $this->getResult()->getResultData();
		if ( isset( $data['error'] ) || isset( $data['errors'] ) ) {
			$this->errorFallback->initPrinter( $unused );
			if ( $this->mFailWithHTTPError ) {
				$this->getMain()->getRequest()->response()->statusHeader( 400 );
			}
		} else {
			parent::initPrinter( $unused );
		}
	}

	public function closePrinter() {
		$data = $this->getResult()->getResultData();
		if ( isset( $data['error'] ) || isset( $data['errors'] ) ) {
			$this->errorFallback->closePrinter();
		} else {
			parent::closePrinter();
		}
	}

	public function execute() {
		$data = $this->getResult()->getResultData();
		if ( isset( $data['error'] ) || isset( $data['errors'] ) ) {
			$this->errorFallback->execute();
			return;
		}

		if ( !isset( $data['text'] ) ) {
			ApiBase::dieDebug( __METHOD__, 'No text given for raw formatter' );
		}
		$this->printText( $data['text'] );
	}

	/**
	 * Output HTTP error code 400 when if an error is encountered
	 *
	 * The purpose is for output formats where the user-agent will
	 * not be able to interpret the validity of the content in any
	 * other way. For example subtitle files read by browser video players.
	 *
	 * @param bool $fail
	 */
	public function setFailWithHTTPError( $fail ) {
		$this->mFailWithHTTPError = $fail;
	}
}
