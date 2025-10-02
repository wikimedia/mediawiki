<?php
/**
 * Copyright © 2009 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

/**
 * Formatter that spits out anything you like with any desired MIME type
 * @ingroup API
 */
class ApiFormatRaw extends ApiFormatBase {

	/** @var ApiFormatBase|null */
	private $errorFallback;
	/** @var bool */
	private $mFailWithHTTPError = false;

	/**
	 * @param ApiMain $main
	 * @param ApiFormatBase|null $errorFallback Object to fall back on for errors
	 */
	public function __construct( ApiMain $main, ?ApiFormatBase $errorFallback = null ) {
		parent::__construct( $main, 'raw' );
		$this->errorFallback = $errorFallback ?:
			$main->createPrinterByName( $main->getParameter( 'format' ) );
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
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

	/** @inheritDoc */
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

/** @deprecated class alias since 1.43 */
class_alias( ApiFormatRaw::class, 'ApiFormatRaw' );
